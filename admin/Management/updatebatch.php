<?php require_once __DIR__ . "/../includes/header.php";

?>

<main class="main-content">
    <div class="container">

        <div class="page-header">
            <h1><i class="fas fa-university"></i>Update Batch</h1>
            <p>Computer Science Department</p>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Update Your Batch</h3>
            </div>
            <div class="card-body">
                <form class="admin-form-row" id="updateBatchForm" method="POST" novalidate>

                    <div class="form-group">
        <label for="selectBatch">Select Batch</label>
        <select id="selectBatch" class="form-input">
            <option value="" disabled selected>Choose a batch</option>
            <option value="2022">Batch 2022</option>
            <option value="2023">Batch 2023</option>
            <option value="2024">Batch 2024</option>
        </select>
    </div>

    <div class="form-group">
        <label for="batchName">Batch Name</label>
        <input type="text" id="batchName" class="form-input" placeholder="2023-2027">
    </div>

    <div class="form-group">
        <label for="semesterSelect">Current Semester</label>
        <select id="semesterSelect" class="form-input">
            <option value="1">Semester 1</option>
            <option value="2">Semester 2</option>
            <option value="3">Semester 3</option>
            <option value="4">Semester 4</option>
            <option value="5">Semester 5</option>
            <option value="6">Semester 6</option>
            <option value="7">Semester 7</option>
            <option value="8">Semester 8</option>
        </select>
    </div>

    <div class="form-group">
        <label for="statusSelect">Status</label>
        <select id="statusSelect" class="form-input">
            <option value="enable">Enable</option>
            <option value="disable">Disable</option>
        </select>
    </div>

    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-full-width">
                          Updating
                        </button>
                        <div id="formMessage"></div>
                        <div id="spinner" style="display:none;">
                            <i class="fas fa-spinner fa-spin"></i> Updating
                        </div>
                    </div>
                </form>

</div>
                    
                </form>
            </div>
        </div>


    </div>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>