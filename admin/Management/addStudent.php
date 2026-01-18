<?php require_once __DIR__ . "/../includes/header.php"; ?>

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
                            <select class="form-input" name="bulk_batch_id" required>
                                <option value="" disabled selected>Choose Batch...</option>
                                <option value="2022">Batch 2022-2026</option>
                                <option value="2023">Batch 2023-2027</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Select Section</label>
                            <select class="form-input" name="bulk_section" required>
                                <option value="" disabled selected>Choose Section...</option>
                                <option value="M1">M1</option>
                                <option value="M2">M2</option>
                                <option value="E1">E1</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Upload CSV File</label>
                            <div style="position: relative;">
                                <input type="file" class="form-input" name="student_csv" accept=".csv" required style="padding-top: 0.6rem;">
                            </div>
                            <small style="color: #666; display: block; margin-top: 5px;">Format: Name, Email, Registration No, Batch</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-full-width">
                                <i class="fas fa-upload"></i> Upload Students
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
</main>

<style>
    /* Styling to center the buttons */
    .selection-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap; /* Good for mobile */
    }

    /* Optional: Make buttons slightly larger for this selection area */
    .selection-container .btn {
        padding: 12px 24px;
        font-size: 1rem;
    }
</style>

<script>
    function showForm(type) {
        // Get the sections
        const bulkSection = document.getElementById('bulkImportSection');
        const singleSection = document.getElementById('singleStudentSection');
        
        // Get the buttons (to update active state style if needed, optional)
        const btnBulk = document.getElementById('btnShowBulk');
        const btnSingle = document.getElementById('btnShowSingle');

        if (type === 'bulk') {
            // Show Bulk, Hide Single
            bulkSection.style.display = 'block';
            singleSection.style.display = 'none';
            
            // Visual feedback on buttons (optional logic)
            btnBulk.classList.remove('btn-secondary');
            btnBulk.classList.add('btn-primary');
            
            btnSingle.classList.remove('btn-primary');
            btnSingle.classList.add('btn-secondary');

        } else if (type === 'single') {
            // Show Single, Hide Bulk
            bulkSection.style.display = 'none';
            singleSection.style.display = 'block';

            // Visual feedback on buttons
            btnSingle.classList.remove('btn-secondary');
            btnSingle.classList.add('btn-primary');
            
            btnBulk.classList.remove('btn-primary');
            btnBulk.classList.add('btn-secondary');
        }
    }
</script>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>