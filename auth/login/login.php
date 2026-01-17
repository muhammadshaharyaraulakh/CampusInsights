<?php
require __DIR__ . "/../../includes/header.php";
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
  header("Location: /index.php");
  exit;
}
?>

<section class="login-section">
  <div class="container login-container">
    <div class="login-form-card fade-in" id="loginFormCard">
      <h2>Welcome Back!</h2>

      <form id="loginForm" class="login-form" novalidate>
        <div class="input-group">
          <label for="email">Email</label>
          <div class="input-field-wrapper">
            <input type="text" id="email" name="email" placeholder="Enter Email">
            <i class="fas fa-user icon"></i>
          </div>
          <div class="error-message" id="emailError"></div>
        </div>

        <div class="input-group">
          <label for="registration_no">Registration Number</label>
          <div class="input-field-wrapper">
            <input
              type="password"
              id="registration_no"
              name="registration_no"
              placeholder="Registartion BS-_ _-_ _-_ _"
              required>
            <i class="fas fa-id-card icon"></i>
            <button type="button" class="toggle-password">
              <i class="far fa-eye-slash"></i>
            </button>
          </div>
          <div class="error-message" id="registrationError"></div>
        </div>


        <button type="submit" class="btn btn-primary login-btn">Log In Securely</button>
        <div id="formMessage"></div>
        <div id="spinner">
          <i class="fas fa-spinner fa-spin"></i> Processing
        </div>
      </form>
    </div>
  </div>
</section>

<?php require __DIR__ . "/../../includes/footer.php"; ?>