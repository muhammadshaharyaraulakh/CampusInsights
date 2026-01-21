<?php
require __DIR__ . "/../config/config.php";
require __DIR__ . "/../includes/header.php";
?>
<section class="error-section">
  <div class="container error-container">
    <div class="error-card fade-in visible">
      <h1>404</h1>
      <h2> Not Found</h2>
      <p>Oops! The page you are looking for doesn't exist or has been moved.</p>
      <?php if($_SESSION['role']==="admin"): ?>
        <a href="/admin/admin.php" class="btn btn-primary">Go To Admin Panel</a>
      <?php else: ?>
        <a href="/index.php" class="btn btn-primary">Go To Home</a>
        <?php endif; ?>
    </div>
  </div>
</section>
<?php
require __DIR__ . "/../includes/footer.php";
?>
