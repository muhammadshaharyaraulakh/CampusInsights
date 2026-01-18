<?php require_once __DIR__ . "/../includes/header.php";

?>

<main class="main-content">
    <div class="container">

        <div class="page-header">
            <h1><i class="fas fa-university"></i>Register Batch</h1>
            <p>Computer Science Department</p>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Create New Batch and Sections</h3>
            </div>
            <div class="card-body">
                <form class="admin-form-row" id="createBatchForm" method="POST" novalidate>

                    <div class="form-group">
                        <label>Batch Year</label>
                        <input type="text" class="form-input" placeholder="2022-2026" name="batch_year" id="batch_year">
                        <div class="error-message" id="batchYearError"></div>
                    </div>

                    <div class="form-group form-group-wide">
                        <label>Select Sections</label>

                        <div class="checkbox-container">
                            <label class="checkbox-pill">
                                <input type="checkbox" name="sections[]" value="M1"> <strong>M1</strong>
                            </label>
                            <label class="checkbox-pill">
                                <input type="checkbox" name="sections[]" value="M2"> <strong>M2</strong>
                            </label>
                            <label class="checkbox-pill">
                                <input type="checkbox" name="sections[]" value="M3"> <strong>M3</strong>
                            </label>

                            <label class="checkbox-pill">
                                <input type="checkbox" name="sections[]" value="E1"> <strong>E1</strong>
                            </label>
                            <label class="checkbox-pill">
                                <input type="checkbox" name="sections[]" value="E2"> <strong>E2</strong>
                            </label>
                            <label class="checkbox-pill">
                                <input type="checkbox" name="sections[]" value="E3"> <strong>E3</strong>
                            </label>
                        </div>

                        <div class="error-message" id="sectionsError"></div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-full-width">
                            Create Batch
                            <div id="spinner" style="display:none;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </button>


                    </div>
                </form>

            </div>
        </div>


    </div>
    <div id="toast-container"></div>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>