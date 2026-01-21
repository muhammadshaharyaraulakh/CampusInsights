<?php
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__."/../../function/function.php";
AdminAccess();
$batch_id = $_GET['id'] ?? 0;
if (!$batch_id || !ctype_digit($batch_id)) {
    header("Location: /pages/404.php");
    exit;
}
$batch_id = (int)$batch_id;

$fetch = $connection->prepare("
    SELECT 
        batches.id AS batch_id,
        batches.batch_year,
        batches.current_semester,
        batches.status,
        batch_sections.id AS section_id,
        batch_sections.section_name
    FROM batches 
    JOIN batch_sections ON batches.id = batch_sections.batch_id 
    WHERE batches.id = :id
");
$fetch->execute([':id' => $batch_id]);
$batch_details = $fetch->fetchAll(PDO::FETCH_OBJ);
?>

<main class="main-content">
    <div class="container">
        <div class="page-header">
            <a href="/admin/Management/batch.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Batches</a>
            <h1>Batch <?= htmlspecialchars($batch_details[0]->batch_year) ?></h1>
            <p>Select a Section to view students</p>
            <p class="semester-label">
                Current Semester: <span><?= htmlspecialchars($batch_details[0]->current_semester) ?></span>
            </p>
        </div>

        <div class="dashboard-card">
            <div class="card-body">
                <div class="single-row-grid">
                    <?php foreach ($batch_details as $detail): ?>
                        <a
                            href="#"
                            class="section-btn js-section-btn"
                            data-section-id="<?= htmlspecialchars($detail->section_id) ?>"
                            data-batch-id="<?= htmlspecialchars($detail->batch_id) ?>">
                            <?= htmlspecialchars($detail->section_name) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <section class="students-dashboard-section" style="display: none;">
            <div class="header-content">
                <div>
                    <h1 class="section-title"></h1>
                    <p class="batch-info"></p>
                </div>
                <div class="stats-mini">
                    <span class="tag tag-total"><i class="fas fa-users"></i> <span class="total-count">0</span> Total</span>
                    <span class="tag tag-pending"><i class="fas fa-clock"></i> <span class="pending-count">0</span> Pending</span>
                </div>
            </div>

            <table class="students-data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>RollNumber</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="students-tbody"></tbody>
            </table>
            <div class="download-wrapper">
    <button id="downloadPendingBtn" class="btn btn-primary">
         Download Pending Students CSV
    </button>
</div>

        </section>
        
    </div>
</main>

<div id="toast-container"></div>



<?php require_once __DIR__ . "/../includes/footer.php"; ?>
