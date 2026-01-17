<?php require_once __DIR__ . "/../includes/header.php"; ?>

<main class="main-content ">
    <div class="container">
        
        <div class="page-header">
            <h1><i class="fas fa-university"></i> Manage Batches</h1>
            <p>Computer Science Department</p>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Create New Batch / Add Section</h3>
            </div>
            <div class="card-body">
                <form class="admin-form-row">
                    <div class="form-group">
                        <label>Batch Year (e.g., 2022-2026)</label>
                        <input type="text" class="form-input" placeholder="2022-2026">
                    </div>
                    <div class="form-group">
                        <label>Section Name (e.g., M1, E2)</label>
                        <input type="text" class="form-input" placeholder="M1">
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Create</button>
                    </div>
                </form>
            </div>
        </div>

        <hr class="divider">

        <h2 class="section-title">Available Batches</h2>
        <div class="batch-grid">
            <a href="sections.php?batch=2022-2026" class="batch-card">
                <div class="batch-icon"><i class="fas fa-layer-group"></i></div>
                <div class="batch-info">
                    <h3>Batch 2022-2026</h3>
                    <p>Computer Science</p>
                </div>
                <div class="arrow"><i class="fas fa-chevron-right"></i></div>
            </a>

            <a href="sections.php?batch=2023-2027" class="batch-card">
                <div class="batch-icon"><i class="fas fa-layer-group"></i></div>
                <div class="batch-info">
                    <h3>Batch 2023-2027</h3>
                    <p>Computer Science</p>
                </div>
                <div class="arrow"><i class="fas fa-chevron-right"></i></div>
            </a>
            </div>

    </div>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>