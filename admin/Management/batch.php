 <?php require_once __DIR__ . "/../includes/header.php";
$stmt = $connection->prepare("
    SELECT * 
    FROM batches 
");
$stmt->execute();

$batches = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<main class="main-content">
    <div class="container">

        <div class="page-header">
            <h1><i class="fas fa-university"></i> Manage Batches</h1>
            <p>Computer Science Department</p>
            <p>Current Smester</p>
        </div>

        <h2 class="section-title">Available Batches</h2>
        <div class="batch-grid">
            <?php if ($batches): ?>
                <?php foreach ($batches as $batch): ?>
                    <a href="/admin/Management/section.php?id=<?= htmlspecialchars($batch->id) ?>"
                        class="batch-card">

                        <div class="batch-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>

                        <div class="batch-info">
                            <h3>Batch <?= htmlspecialchars($batch->batch_year) ?></h3>
                            <p>Computer Science</p>
                        </div>

                        <div class="arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                        
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No batches found.</p>
            <?php endif; ?>
        </div>
    </div>
    <main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>