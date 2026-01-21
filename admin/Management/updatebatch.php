<?php
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__."/../../function/function.php";
AdminAccess();
$stmt = $connection->prepare("
    SELECT *
    FROM batches
    ORDER BY batch_year DESC
");
$stmt->execute();
$batches = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<main class="main-content">
    <div class="container">

        <div class="page-header">
            <h1><i class="fas fa-university"></i> Update Batch</h1>
            <p>Computer Science Department</p>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Update Batch Details</h3>
            </div>

            <div class="card-body">
                <form
                    id="updateBatchForm"
                    class="admin-form-row js-update-batch-form"
                    method="POST"
                    novalidate
                >

                    <div class="form-group">
                        <label for="selectBatch">Select Batch</label>
                        <select
                            id="selectBatch"
                            name="batch_id"
                            class="form-input js-batch-select"
                        >
                            <option value="" disabled selected>Choose a batch</option>
                            <?php foreach ($batches as $batch): ?>
                                <option value="<?= htmlspecialchars($batch->id) ?>">
                                    Batch <?= htmlspecialchars($batch->batch_year) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="error-message" id="batchSelectError"></div>
                    </div>

                    <div class="form-group">
                        <label for="batchName">Batch Year</label>
                        <input
                            type="text"
                            id="batchName"
                            name="batch_year"
                            class="form-input js-batch-year"
                            placeholder="2023-2027"
                            autocomplete="off"
                        >
                        
                    </div>

                    <div class="form-group">
                        <label for="semesterSelect">Update Semester</label>
                        <select
                            id="semesterSelect"
                            name="update_semester"
                            class="form-input js-semester-select"
                        >
                            <option value="" disabled selected>Choose a Semester</option>
                            <?php for ($i = 1; $i <= 8; $i++): ?>
                                <option value="<?= $i ?>">Semester <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                        
                    </div>

                    <div class="form-group">
                        <label for="statusSelect">Status</label>
                        <select
                            id="statusSelect"
                            name="status"
                            class="form-input js-status-select"
                        >
                            <option value="enable">Enable</option>
                            <option value="disable">Disable</option>
                        </select>
                        
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-full-width">
                            Update Batch
                            <span id="spinner" style="display:none; margin-left:8px;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</main>

<div id="toast-container"></div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
