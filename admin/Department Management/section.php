<?php require_once __DIR__ . "/../includes/header.php"; ?>

<main class="main-content ">
    <div class="container">
        
        <div class="page-header">
            <a href="manage_batches.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Batches</a>
            <h1>Batch 2022-2026</h1>
            <p>Select a Section to view students</p>
        </div>

        <div class="sections-grid">
            <div class="section-group">
                <h3><i class="fas fa-sun"></i> Morning</h3>
                <div class="btn-grid">
                    <a href="students.php?section=M1" class="section-btn">M1</a>
                    <a href="students.php?section=M2" class="section-btn">M2</a>
                    <a href="students.php?section=M3" class="section-btn">M3</a>
                </div>
            </div>

            <div class="section-group">
                <h3><i class="fas fa-moon"></i> Evening</h3>
                <div class="btn-grid">
                    <a href="students.php?section=E1" class="section-btn">E1</a>
                    <a href="students.php?section=E2" class="section-btn">E2</a>
                </div>
            </div>
        </div>

    </div>


        <div class="secction-header hidden">
            <div>
                <h1>Section M1 Students</h1>
                <p>Batch 2022-2026</p>
            </div>
                    <div class="stats-panel">
            <div class="stat-card total">
                <div class="icon"><i class="fas fa-users"></i></div>
                <div class="info">
                    <h3>50</h3>
                    <p>Total Students</p>
                </div>
            </div>
            <div class="stat-card success">
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <div class="info">
                    <h3>32</h3>
                    <p>Survey Completed</p>
                </div>
            </div>
            <div class="stat-card warning">
                <div class="icon"><i class="fas fa-clock"></i></div>
                <div class="info">
                    <h3>18</h3>
                    <p>Pending</p>
                </div>
            </div>
        </div>

        <div class="table-responsive dashboard-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Reg No</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="user-info">
                                <span class="avatar">A</span>
                                <span>Ali Khan</span>
                            </div>
                        </td>
                        <td>ali.khan@example.com</td>
                        <td>BS-CS-22-01</td>
                        <td><span class="badge badge-success">Completed</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon edit" title="Update"><i class="fas fa-edit"></i></button>
                                <button class="btn-icon delete" title="Delete"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="user-info">
                                <span class="avatar">S</span>
                                <span>Sara Ahmed</span>
                            </div>
                        </td>
                        <td>sara.ahmed@example.com</td>
                        <td>BS-CS-22-02</td>
                        <td><span class="badge badge-warning">Pending</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-sm btn-notify" title="Send Reminder Email">
                                    <i class="fas fa-paper-plane"></i> Notify
                                </button>
                                <button class="btn-icon edit" title="Update"><i class="fas fa-edit"></i></button>
                                <button class="btn-icon delete" title="Delete"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>




</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>