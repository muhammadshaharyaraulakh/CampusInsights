<?php require_once __DIR__ . "/../includes/header.php"; ?>

<main class="main-content">
    <div class="container">

        <div class="page-header">
            <a href="manage_batches.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Batches</a>
            <h1>Batch 2022-2026</h1>
            <p>Select a Section to view students</p>
        </div>

        <div class="dashboard-card">
            <div class="card-body">
                <div class="single-row-grid">
                    <a href="students.php?section=M1" class="section-btn">M1</a>
                    <a href="students.php?section=M2" class="section-btn">M2</a>
                    <a href="students.php?section=M3" class="section-btn">M3</a>
                    <a href="students.php?section=E1" class="section-btn">E1</a>
                    <a href="students.php?section=E2" class="section-btn">E2</a>
                    <a href="students.php?section=E3" class="section-btn">E3</a>
                </div>
            </div>
        </div>

        <!-- UPDATED SECTION AND TABLE CLASS NAMES -->
        <section class="students-dashboard-section">

            <div class="header-content">
                <div>
                    <h1>Section M1 Students</h1>
                    <p>Batch 2022-2026</p>
                </div>

                <div class="stats-mini">
                    <span class="tag tag-total"><i class="fas fa-users"></i> 50 Total</span>
                    <span class="tag tag-done"><i class="fas fa-check"></i> 32 Done</span>
                    <span class="tag tag-pending"><i class="fas fa-clock"></i> 18 Pending</span>
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
                    <tbody>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div>
                                        <span style="display:block; font-weight:600;">Ali Khan</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="user-info">
                                    <div>
                                        <span style="display:block; font-weight:600;">BSCS-M1-22-08</span>
                                    </div>
                                </div>
                            </td>

                            <td><span class="badge badge-success">Completed</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-icon edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn-icon delete"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div>
                                        <span style="display:block; font-weight:600;">Ali Khan</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="user-info">
                                    <div>
                                        <span style="display:block; font-weight:600;">BSCS-M1-22-08</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-pending">Pending</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-icon edit "><i class="fas fa-edit"></i></button>
                                    <button class="btn-icon delete"><i class="fas fa-trash-alt"></i></button>
                                </div>
                                
                            </td>
                        </tr>
                    </tbody>
                </table>

        </section>

    </div>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>