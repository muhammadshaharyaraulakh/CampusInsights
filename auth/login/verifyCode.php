<?php
require_once __DIR__ . "/../../includes/header.php";


$email = $_SESSION['otp_email'];
?>
<section class="login-section">
  <div class="container login-container">
    <div class="login-form-card fade-in">
      <h2>Email Verification</h2>
      <p style="text-align:center;margin-bottom:1rem;">
        Enter the verification code sent to <br>
        <strong><?= htmlspecialchars($email) ?></strong>
      </p>

      <form id="verifyForm" class="login-form">
        <div class="input-group">
          <label for="code">Verification Code</label>
          <div class="input-field-wrapper">
            <input type="text" id="code" name="code" placeholder="Enter code">
            <i class="fas fa-key icon"></i>
          </div>
          <div class="error-message" id="codeError"></div>
        </div>

        <button class="btn btn-primary login-btn" type="submit">Verify</button>

        <div id="formMessage"></div>
        <div id="spinner">
          <i class="fas fa-spinner fa-spin"></i> Verifying
        </div>
      </form>



    </div>
  </div>
</section>

<?php require_once __DIR__ . "/../../includes/footer.php"; ?>