<?php require_once __DIR__ . "/includes/header.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="analytics-container">
    
    <div class="analytics-header">
        <div>
            <h1><i class="fas fa-chart-pie"></i> Survey Analytics Report</h1>
            <p>Real-time analysis of student feedback.</p>
        </div>
        <div class="date-filter">
            <select class="form-select">
                <option>Last 30 Days</option>
                <option>This Semester</option>
                <option>All Time</option>
            </select>
            <button class="btn btn-primary"><i class="fas fa-download"></i> Print PDF</button>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon-box blue"><i class="fas fa-users"></i></div>
            <div>
                <h3>1,245</h3>
                <span>Total Responses</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-box green"><i class="fas fa-smile"></i></div>
            <div>
                <h3>4.2 / 5.0</h3>
                <span>Overall Satisfaction</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-box purple"><i class="fas fa-comment-dots"></i></div>
            <div>
                <h3>850</h3>
                <span>Text Comments</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-box orange"><i class="fas fa-exclamation-circle"></i></div>
            <div>
                <h3>15</h3>
                <span>Critical Flags</span>
            </div>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-card">
            <div class="card-header">
                <h4><i class="fas fa-university"></i> Responses by Department</h4>
            </div>
            <div class="chart-body">
                <canvas id="deptChart"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <div class="card-header">
                <h4><i class="fas fa-user-graduate"></i> Semester Distribution</h4>
            </div>
            <div class="chart-body">
                <canvas id="semesterChart"></canvas>
            </div>
        </div>
    </div>

    <div class="chart-card full-width">
        <div class="card-header">
            <h4><i class="fas fa-chalkboard-teacher"></i> Academic Experience (Quality Ratings)</h4>
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
                <h4><i class="fas fa-heart"></i> Environment & Safety</h4>
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
            <div class="feedback-item">
                <div class="fb-header">
                    <span class="badge badge-success">Positive</span>
                    <span class="fb-date">2 hours ago</span>
                </div>
                <p>"The new library timing is excellent! It helps us study late night during exams."</p>
            </div>
            
            <div class="feedback-item">
                <div class="fb-header">
                    <span class="badge badge-danger">Critical</span>
                    <span class="fb-date">5 hours ago</span>
                </div>
                <p>"The WiFi in the CS department (2nd floor) is not working properly. Please fix it."</p>
            </div>

            <div class="feedback-item">
                <div class="fb-header">
                    <span class="badge badge-warning">Suggestion</span>
                    <span class="fb-date">1 day ago</span>
                </div>
                <p>"We need more water dispensers in the engineering block."</p>
            </div>
        </div>
    </div>

</div>

<style>
    .analytics-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f8fafc;
    }

    /* Header */
    .analytics-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .analytics-header h1 {
        font-size: 1.8rem;
        color: #1e293b;
        margin-bottom: 5px;
    }
    
    .date-filter {
        display: flex;
        gap: 10px;
    }

    .form-select {
        padding: 8px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        outline: none;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .icon-box {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .blue { background: #e0f2fe; color: #0284c7; }
    .green { background: #dcfce7; color: #16a34a; }
    .purple { background: #f3e8ff; color: #9333ea; }
    .orange { background: #ffedd5; color: #ea580c; }

    .stat-card h3 { margin: 0; font-size: 1.5rem; }
    .stat-card span { color: #64748b; font-size: 0.9rem; }

    /* Charts Layout */
    .charts-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    .chart-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        padding: 20px;
        border: 1px solid #e2e8f0;
    }

    .full-width {
        grid-column: 1 / -1; /* Spans full width */
        margin-bottom: 30px;
    }

    .card-header {
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }

    .card-header h4 {
        margin: 0;
        font-size: 1.1rem;
        color: #334155;
    }

    .chart-body {
        position: relative;
        height: 300px; /* Fixed height for charts */
    }

    /* Feedback List */
    .feedback-list {
        max-height: 300px;
        overflow-y: auto;
    }

    .feedback-item {
        border-bottom: 1px solid #f1f5f9;
        padding: 15px 0;
    }
    .feedback-item:last-child { border-bottom: none; }

    .fb-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        color: white;
        font-weight: 600;
    }
    .badge-success { background: #10b981; }
    .badge-danger { background: #ef4444; }
    .badge-warning { background: #f59e0b; }

    .fb-date { font-size: 0.8rem; color: #94a3b8; }
    
    .feedback-item p { margin: 0; color: #475569; font-size: 0.95rem; }

    /* Responsive */
    @media (max-width: 768px) {
        .charts-row { grid-template-columns: 1fr; }
    }
</style>

<script>
    // Global Config for Fonts
    Chart.defaults.font.family = "'Segoe UI', 'Helvetica', 'Arial', sans-serif";
    Chart.defaults.color = '#64748b';

    // 1. Department Chart (Pie)
    new Chart(document.getElementById('deptChart'), {
        type: 'doughnut',
        data: {
            labels: ['Computer Science', 'Electrical Eng', 'BBA', 'Social Sciences'],
            datasets: [{
                data: [450, 300, 200, 150], // Dummy Data
                backgroundColor: ['#4f46e5', '#06b6d4', '#f59e0b', '#ec4899'],
                borderWidth: 0
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // 2. Semester Chart (Bar)
    new Chart(document.getElementById('semesterChart'), {
        type: 'bar',
        data: {
            labels: ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th'],
            datasets: [{
                label: 'Students Count',
                data: [120, 115, 130, 110, 140, 125, 100, 95],
                backgroundColor: '#4f46e5',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        }
    });

    // 3. Academic Experience (Stacked Bar) - Matches your "q_" questions
    new Chart(document.getElementById('academicChart'), {
        type: 'bar',
        data: {
            labels: ['Teaching Quality', 'Course Material', 'Lectures', 'Instructor Help', 'Grading'],
            datasets: [
                { label: 'Excellent', data: [50, 40, 45, 60, 55], backgroundColor: '#10b981' }, // Green
                { label: 'Good', data: [30, 35, 30, 25, 30], backgroundColor: '#3b82f6' },      // Blue
                { label: 'Average', data: [15, 20, 20, 10, 10], backgroundColor: '#f59e0b' },   // Orange
                { label: 'Poor', data: [5, 5, 5, 5, 5], backgroundColor: '#ef4444' }            // Red
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
        }
    });

    // 4. Facilities (Radar Chart) - Matches your "r_" questions
    new Chart(document.getElementById('facilitiesChart'), {
        type: 'radar',
        data: {
            labels: ['Classrooms', 'Library', 'WiFi', 'Cafeteria', 'Transport', 'Security'],
            datasets: [{
                label: 'Average Rating (1-5)',
                data: [4.2, 4.5, 2.5, 3.0, 3.8, 4.7], // Dummy averages
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

    // 5. Environment (Horizontal Bar) - Matches your "e_" questions
    new Chart(document.getElementById('environmentChart'), {
        type: 'bar',
        indexAxis: 'y', // Makes it horizontal
        data: {
            labels: ['Safety', 'Anti-Bullying', 'Societies', 'Mental Health', 'Placement Cell'],
            datasets: [{
                label: 'Positive Feedback %',
                data: [90, 85, 70, 40, 60], // % of students who said Good/Excellent
                backgroundColor: [
                    '#10b981', '#10b981', '#3b82f6', '#ef4444', '#f59e0b'
                ]
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: { x: { max: 100 } }
        }
    });
</script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>