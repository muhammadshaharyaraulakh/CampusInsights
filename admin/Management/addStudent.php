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
            <h1><i class="fas fa-user-graduate"></i> Add Students</h1>
        </div>

        <div class="selection-container">
            <button id="btnShowBulk" class="btn btn-primary" onclick="showForm('bulk')">
                 Add Multiple Students
            </button>
            
            <button id="btnShowSingle" class="btn btn-secondary" onclick="showForm('single')">
                Add Single Student
            </button>
        </div>

        <div id="bulkImportSection">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3> For New Batch Smester only</h3>
                </div>
                <div class="card-body">
                    <form class="admin-form-row" id="bulkImportForm" method="POST" enctype="multipart/form-data">
                        
                        <div class="form-group">
                            <label>Select Batch</label>
                            <select class="form-input" name="session" required>
                                <option value="" disabled selected>Choose Batch</option>
                               <?php foreach ($batches as $batch): ?>
                                   <option value="<?= htmlspecialchars($batch->id) ?>">Batch <?= htmlspecialchars($batch->batch_year) ?></option>
                               <?php endforeach; ?>
                            </select>
                            <div class="error-message" id="batchSession"></div>
                        </div>

                        <div class="form-group">
                            <label>Select Section</label>
                            <select class="form-input" name="section" required>
                                <option value="" disabled selected>Choose Section</option>
                                <option value="M1">M1</option>
                                <option value="M2">M2</option>
                                <option value="M3">M3</option>
                                <option value="E1">E1</option>
                                <option value="E2">E2</option>
                                <option value="E3">E3</option>
                            </select>
                            <div class="error-message" id="section"></div>
                        </div>

                        <div class="form-group">
                            <label>Upload CSV File</label>
                            <div style="position: relative;">
                                <input type="file" class="form-input" name="student_csv" accept=".csv" required style="padding-top: 0.6rem;">
                            </div>
                            <div class="error-message" id="csvFileError"></div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-full-width">
                            Add Students
                            <div id="spinner" style="display:none;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div id="singleStudentSection" style="display: none;">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3> Adding Single Student</h3>
                </div>
                <div class="card-body">
                    <form class="admin-form-row" id="singleStudentForm" method="POST">

                        <div class="form-group">
                            <label>Batch</label>
                            <select class="form-input" name="batch_id" required>
                                <option value="" disabled selected>Select Batch...</option>
                                <option value="2022">Batch 2022-2026</option>
                                <option value="2023">Batch 2023-2027</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Section</label>
                            <select class="form-input" name="section" required>
                                <option value="" disabled selected>Select Section...</option>
                                <option value="M1">M1</option>
                                <option value="M2">M2</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Section</label>
                            <select class="form-input" name="section" required>
                                <option value="" disabled selected>Select Smester</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" class="form-input" name="full_name" placeholder="e.g. Ali Khan" required>
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" class="form-input" name="email" placeholder="e.g. ali@example.com" required>
                        </div>

                        <div class="form-group">
                            <label>Registration No (Roll No)</label>
                            <input type="text" class="form-input" name="reg_no" placeholder="e.g. BS-CS-22-01" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-full-width">
                                <i class="fas fa-plus-circle"></i> Add Student
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
    </div>
    <div id="toast-container"></div>
</main>



<?php require_once __DIR__ . "/../includes/footer.php"; ?>