<?php
require __DIR__ . "/../../config/config.php";
require __DIR__ . "/../../includes/header.php";
?>
<section class="error-section">
  <div class="container error-container">
    <div class="error-card fade-in visible">
      <h2>This Session is currently disabled.</h2>
      <a href="/admin/admin.php" class="btn btn-primary">Go To dashboard</a>
    </div>
  </div>
</section>
<?php
require __DIR__ . "/../../includes/footer.php";
?>
