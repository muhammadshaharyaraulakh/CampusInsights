<?php 
require_once __DIR__ . "/includes/header.php"; 
require_once __DIR__ . "/../config/config.php";

// ========================================================
// 1. PHP BACKEND: FETCH DATA
// ========================================================

$currentYear = date('Y');

// A. Identify Active Session & Semesters directly from Batches Table
// Hum sirf 'enable' batches uthayenge taake X-Axis set ho jaye
$batchStmt = $connection->prepare("SELECT id, current_semester FROM batches WHERE status = 'enable' ORDER BY current_semester ASC");
$batchStmt->execute();
$activeBatches = $batchStmt->fetchAll(PDO::FETCH_ASSOC);

$activeSemesters = [];
$validSessions = [];

foreach ($activeBatches as $batch) {
    $sem = (int)$batch['current_semester'];
    
    // Sirf unique semesters rakhein (Duplicate bachne k liye)
    if (!in_array($sem, $activeSemesters)) {
        $activeSemesters[] = $sem;
    }
    
    $season = ($sem % 2 == 0) ? "Spring" : "Fall"; 
    $validSessions[] = "$currentYear($season)";
}

// Semesters ko sort karein (Taake graph mein 2, 4, 6, 8 line se ayen)
sort($activeSemesters);

$validSessions = array_unique($validSessions);
$sessionString = implode("', '", $validSessions); 

// B. Fetch Completed Surveys
if (!empty($validSessions)) {
    $sql = "SELECT * FROM survey_progress WHERE year_session IN ('$sessionString')";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $surveys = [];
}

// C. Fetch Total Students Per Semester
// IMPORTANT: Left Join use kar rahe hain taake agar student na bhi ho to semester count 0 aye
$totalStdSql = "SELECT b.current_semester, COUNT(u.id) as count 
                FROM batches b
                LEFT JOIN batch_sections bs ON b.id = bs.batch_id
                LEFT JOIN user u ON bs.id = u.batch_section_id AND u.status = 'active'
                WHERE b.status = 'enable'
                GROUP BY b.current_semester";
$stmtStd = $connection->prepare($totalStdSql);
$stmtStd->execute();
$totalStudentsPerSem = $stmtStd->fetchAll(PDO::FETCH_KEY_PAIR); // [Semester => Count]

// D. Initialize Data Containers
$totalResponses = count($surveys);
$completedPerSem = []; // Graph 1 k liye data
$textCommentsCount = 0;
$recentFeedback = [];

// Arrays Setup
$teacherKeys = ['Knowledge', 'Clarity', 'Punctuality', 'Preparedness', 'Engagement', 'Approachability', 'Fairness', 'Examples', 'Pace', 'Professionalism'];
$facilityKeys = ['Class Cleanliness', 'Furniture', 'Multimedia', 'Ventilation', 'Lab Hardware', 'Lab Software', 'Lab Internet', 'Lab Support', 'Lab Access', 'Lab Peripherals'];
$envTransportKeys = ['Safety', 'Security Staff', 'Harassment Policy', 'Complaint System', 'Admin Respect', 'Transport Routes', 'Transport Condition', 'Canteen Hygiene', 'Canteen Quality', 'Canteen Price'];

function initRatingArray($questions) {
    $arr = [];
    foreach ($questions as $q) $arr[$q] = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];
    return $arr;
}

$teacherStats = initRatingArray($teacherKeys);
$envTransportStats = initRatingArray($envTransportKeys);
$facilitySums = array_fill_keys($facilityKeys, 0);

// E. Process Loop
foreach ($surveys as $row) {
    
    // 1. Completed Count per Semester
    $sem = $row['student_semester'];
    if (!isset($completedPerSem[$sem])) $completedPerSem[$sem] = 0;
    $completedPerSem[$sem]++;

    // 2. Section 1: Teachers
    $s1 = json_decode($row['section_1'], true) ?? [];
    if($s1) {
        $map = [
            'q_teacher_knowledge' => 'Knowledge', 'q_teacher_clarity' => 'Clarity', 'q_teacher_punctuality' => 'Punctuality',
            'q_teacher_preparedness' => 'Preparedness', 'q_teacher_engagement' => 'Engagement', 'q_teacher_approachability' => 'Approachability',
            'q_teacher_fairness' => 'Fairness', 'q_teacher_examples' => 'Examples', 'q_teacher_pace' => 'Pace', 'q_teacher_professionalism' => 'Professionalism'
        ];
        foreach($map as $dbKey => $chartKey) {
            $val = (int)($s1[$dbKey] ?? 0);
            if($val >= 1 && $val <= 5) $teacherStats[$chartKey][$val]++;
        }
    }

    // 3. Section 2: Facilities
    $s2 = json_decode($row['section_2'], true) ?? [];
    if($s2) {
        $map = [
            'q_class_cleanliness' => 'Class Cleanliness', 'q_class_furniture' => 'Furniture', 'q_class_multimedia' => 'Multimedia',
            'q_class_ventilation' => 'Ventilation', 'q_lab_hardware' => 'Lab Hardware', 'q_lab_software' => 'Lab Software',
            'q_lab_internet' => 'Lab Internet', 'q_lab_support' => 'Lab Support', 'q_lab_access' => 'Lab Access', 'q_lab_peripherals' => 'Lab Peripherals'
        ];
        foreach($map as $dbKey => $chartKey) {
            $facilitySums[$chartKey] += (int)($s2[$dbKey] ?? 0);
        }
    }

    // 4. Section 3 & 4: Env/Trans
    $s3 = json_decode($row['section_3'], true) ?? [];
    $s4 = json_decode($row['section_4'], true) ?? [];
    
    $processEnv = function($source, $dbKey, $chartKey) use (&$envTransportStats) {
        $val = (int)($source[$dbKey] ?? 0);
        if($val >= 1 && $val <= 5) $envTransportStats[$chartKey][$val]++;
    };

    $processEnv($s3, 'env_safety', 'Safety');
    $processEnv($s3, 'env_security_staff', 'Security Staff');
    $processEnv($s3, 'env_harassment_policy', 'Harassment Policy');
    $processEnv($s3, 'env_complaint_system', 'Complaint System');
    $processEnv($s3, 'env_admin_respect', 'Admin Respect');
    $processEnv($s4, 'trans_routes', 'Transport Routes');
    $processEnv($s4, 'trans_condition', 'Transport Condition');
    $processEnv($s4, 'cant_hygiene', 'Canteen Hygiene');
    $processEnv($s4, 'cant_quality', 'Canteen Quality');
    $processEnv($s4, 'cant_price', 'Canteen Price');

    // 5. Suggestions
    $s5 = json_decode($row['section_5'], true) ?? [];
    if (!empty($s5['final_feedback'])) {
        $textCommentsCount++;
        if (count($recentFeedback) < 5) {
            $recentFeedback[] = [
                'text' => $s5['final_feedback'],
                'target' => $s5['complaint_target'] ?? 'General',
                'time' => date('d M', strtotime($row['updated_at']))
            ];
        }
    }
}

// F. Final Calculations for Charts

// Fix: Ensure ALL active semesters are in the chart, even if count is 0
$semLabels = [];
$completedCounts = [];
$totalStudentCounts = [];

foreach($activeSemesters as $sem) {
    $semLabels[] = "Semester $sem";
    // Agar data nahi hai to 0 daal do
    $completedCounts[] = $completedPerSem[$sem] ?? 0;
    $totalStudentCounts[] = $totalStudentsPerSem[$sem] ?? 0;
}

// Facility Averages
$facilityAvg = [];
foreach($facilitySums as $k => $sum) {
    $facilityAvg[] = $totalResponses > 0 ? round($sum / $totalResponses, 1) : 0;
}

// Total Stats
$totalActiveStudents = array_sum($totalStudentCounts);
$responseRate = ($totalActiveStudents > 0) ? round(($totalResponses / $totalActiveStudents) * 100, 1) : 0;
$pendingSurveys = $totalActiveStudents - $totalResponses;

?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="analytics-container">
    
    <div class="analytics-header">
        <div>
            <h1> Survey Analytics Report</h1>
            <p>Real-time analysis for session: <strong><?= implode(', ', $validSessions) ?></strong></p>
        </div>
        <div class="date-filter">
            <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Print Report</button>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon-box blue"><i class="fas fa-users"></i></div>
            <div>
                <h3><?= number_format($totalResponses) ?></h3>
                <span>Total Responses</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-box green"><i class="fas fa-chart-pie"></i></div>
            <div>
                <h3><?= $responseRate ?>%</h3>
                <span>Response Percentage</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-box purple"><i class="fas fa-comment-dots"></i></div>
            <div>
                <h3><?= number_format($textCommentsCount) ?></h3>
                <span>Text Comments</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-box orange"><i class="fas fa-exclamation-circle"></i></div>
            <div>
                <h3><?= number_format($pendingSurveys) ?></h3>
                <span>Pending Surveys</span>
            </div>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-card">
            <div class="card-header">
                <h4><i class="fas fa-university"></i> Completed Surveys (By Semester)</h4>
            </div>
            <div class="chart-body">
                <canvas id="deptChart"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <div class="card-header">
                <h4><i class="fas fa-user-graduate"></i> Total Registered Students</h4>
            </div>
            <div class="chart-body">
                <canvas id="semesterChart"></canvas>
            </div>
        </div>
    </div>

    <div class="chart-card full-width">
        <div class="card-header">
            <h4><i class="fas fa-chalkboard-teacher"></i> Teacher Evaluation (Rating Distribution)</h4>
        </div>
        <div class="chart-body">
            <canvas id="academicChart" height="100"></canvas>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-card">
            <div class="card-header">
                <h4><i class="fas fa-building"></i> Campus Facilities Satisfaction</h4>
            </div>
            <div class="chart-body">
                <canvas id="facilitiesChart"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <div class="card-header">
                <h4><i class="fas fa-heart"></i> Environment, Transport & Canteen</h4>
            </div>
            <div class="chart-body">
                <canvas id="environmentChart"></canvas>
            </div>
        </div>
    </div>

    <div class="chart-card full-width">
        <div class="card-header">
            <h4><i class="fas fa-comments"></i> Recent Student Suggestions</h4>
        </div>
        <div class="feedback-list">
             <?php if(empty($recentFeedback)): ?>
                <p style="padding:15px; text-align:center; color:#999;">No text feedback received yet.</p>
            <?php else: ?>
                <?php foreach($recentFeedback as $fb): ?>
                <div class="feedback-item">
                    <div class="fb-header">
                        <span class="badge badge-warning"><?= htmlspecialchars($fb['target']) ?></span>
                        <span class="fb-date"><?= $fb['time'] ?></span>
                    </div>
                    <p>"<?= htmlspecialchars($fb['text']) ?>"</p>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<style>
    .analytics-container { max-width: 1200px; margin: 0 auto; padding: 20px; background-color: #f8fafc; margin-top: 3rem; }
    .analytics-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px; }
    .analytics-header h1 { font-size: 1.8rem; color: #1e293b; margin-bottom: 5px; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 15px; }
    .icon-box { width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .blue { background: #e0f2fe; color: #0284c7; }
    .green { background: #dcfce7; color: #16a34a; }
    .purple { background: #f3e8ff; color: #9333ea; }
    .orange { background: #ffedd5; color: #ea580c; }
    .stat-card h3 { margin: 0; font-size: 1.5rem; }
    .stat-card span { color: #64748b; font-size: 0.9rem; }
    .charts-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
    .chart-card { background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); padding: 20px; border: 1px solid #e2e8f0; }
    .full-width { grid-column: 1 / -1; margin-bottom: 30px; }
    .card-header { border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 15px; }
    .card-header h4 { margin: 0; font-size: 1.1rem; color: #334155; }
    .chart-body { position: relative; height: 300px; }
    .feedback-list { max-height: 300px; overflow-y: auto; }
    .feedback-item { border-bottom: 1px solid #f1f5f9; padding: 15px 0; }
    .feedback-item:last-child { border-bottom: none; }
    .fb-header { display: flex; justify-content: space-between; margin-bottom: 8px; }
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; color: white; font-weight: 600; }
    .badge-warning { background: #f59e0b; }
    .fb-date { font-size: 0.8rem; color: #94a3b8; }
    .feedback-item p { margin: 0; color: #475569; font-size: 0.95rem; }
    @media (max-width: 768px) { .charts-row { grid-template-columns: 1fr; } }
</style>

<script>
    Chart.defaults.font.family = "'Segoe UI', 'Helvetica', 'Arial', sans-serif";
    Chart.defaults.color = '#64748b';

    function getStackedDatasets(dataObj) {
        const d5 = [], d4 = [], d3 = [], d2 = [], d1 = [];
        for (const key in dataObj) {
            d5.push(dataObj[key][5]);
            d4.push(dataObj[key][4]);
            d3.push(dataObj[key][3]);
            d2.push(dataObj[key][2]);
            d1.push(dataObj[key][1]);
        }
        return [
            { label: 'Excellent', data: d5, backgroundColor: '#10b981' },
            { label: 'Good', data: d4, backgroundColor: '#3b82f6' },
            { label: 'Average', data: d3, backgroundColor: '#f59e0b' },
            { label: 'Poor', data: d2, backgroundColor: '#ef4444' },
            { label: 'Very Poor', data: d1, backgroundColor: '#7f1d1d' }
        ];
    }

    // Graph 1: Completed Surveys (Doughnut)
    new Chart(document.getElementById('deptChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($semLabels) ?>,
            datasets: [{
                data: <?= json_encode($completedCounts) ?>,
                backgroundColor: ['#4f46e5', '#06b6d4', '#f59e0b', '#ec4899'],
                borderWidth: 0
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Graph 2: Total Students (Bar)
    new Chart(document.getElementById('semesterChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($semLabels) ?>,
            datasets: [{
                label: 'Total Students',
                data: <?= json_encode($totalStudentCounts) ?>,
                backgroundColor: '#4f46e5',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        }
    });

    // Graph 3: Teachers
    const teacherData = <?= json_encode($teacherStats) ?>;
    new Chart(document.getElementById('academicChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(teacherData),
            datasets: getStackedDatasets(teacherData)
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
        }
    });

    // Graph 4: Facilities (Radar)
    new Chart(document.getElementById('facilitiesChart'), {
        type: 'radar',
        data: {
            labels: <?= json_encode($facilityKeys) ?>,
            datasets: [{
                label: 'Average Score (1-5)',
                data: <?= json_encode($facilityAvg) ?>,
                fill: true,
                backgroundColor: 'rgba(79, 70, 229, 0.2)',
                borderColor: '#4f46e5',
                pointBackgroundColor: '#4f46e5'
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: { r: { min: 0, max: 5 } }
        }
    });

    // Graph 5: Env & Transport (Stacked)
    const envData = <?= json_encode($envTransportStats) ?>;
    new Chart(document.getElementById('environmentChart'), {
        type: 'bar',
        indexAxis: 'y', 
        data: {
            labels: Object.keys(envData),
            datasets: getStackedDatasets(envData)
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: { x: { stacked: true }, y: { stacked: true } }
        }
    });
</script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>