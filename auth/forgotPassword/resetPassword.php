<?php
require __DIR__ . "/../../includes/header.php";

// ==========================
// ACCESS CONTROL
// ==========================
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    header("Location: /index.php");
    exit;
}


?>

<section class="login-section">
  <div class="container login-container">
    <div class="login-form-card fade-in" id="resetPasswordCard">
      <h2>Reset Your Password</h2>
      <p class="subtitle">Enter the code you received and your new password.</p>

      <form id="resetPasswordForm" class="login-form" novalidate>
        
        <div class="input-group">
          <label for="email">Email Address</label>
          <div class="input-field-wrapper">
            <input type="email" id="email" name="email" placeholder="Enter gmail">
            <i class="fas fa-user icon"></i>
          </div>
          <div class="error-message" id="emailError"></div>
        </div>

        <div class="input-group">
          <label for="code">Reset Code</label>
          <div class="input-field-wrapper">
            <input type="text" id="code" name="code" placeholder="16-character code">
            <i class="fas fa-key icon"></i>
          </div>
          <div class="error-message" id="codeError"></div>
        </div>

        <div class="input-group">
          <label for="password">New Password</label>
          <div class="input-field-wrapper">
            <input type="password" id="password" name="password" placeholder="New password">
            <i class="fas fa-lock icon"></i>
            <button type="button" class="toggle-password" id="togglePassword">
              <i class="far fa-eye-slash"></i>
            </button>
          </div>
          <div class="error-message" id="passwordError"></div>
        </div>
        
        <div class="input-group">
          <label for="confirm_password">Confirm New Password</label>
          <div class="input-field-wrapper">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm  password">
            <i class="fas fa-lock icon"></i>
            <button type="button" class="toggle-password" id="toggleConfirmPassword">
              <i class="far fa-eye-slash"></i>
            </button>
          </div>
          <div class="error-message" id="confirmError"></div>
        </div>


        <button type="submit" class="btn btn-primary login-btn">Reset Password</button>
        <div id="formMessage"></div>
        <div id="spinner" style="display:none;">
          <i class="fas fa-spinner fa-spin"></i> Resetting password
        </div>
      </form>

      <div class="signup-link">
        <p>Remembered your password? <a href="/auth/login/login.php">Login</a></p>
      </div>
    </div>
  </div>
</section>

<script>
// ==========================
// GET DOM ELEMENTS
// ==========================
const emailInput = document.getElementById("email");
const codeInput = document.getElementById("code");
const passwordInput = document.getElementById("password");
const confirmInput = document.getElementById("confirm_password"); 

const emailError = document.getElementById("emailError");
const codeError = document.getElementById("codeError");
const passwordError = document.getElementById("passwordError");
const confirmError = document.getElementById("confirmError"); 
const formMessage = document.getElementById("formMessage"); 
const spinner = document.getElementById("spinner");

const form = document.getElementById("resetPasswordForm");

// ==========================
// CLEAR ERROR MESSAGES ON INPUT
// ==========================
[emailInput, codeInput, passwordInput, confirmInput].forEach(input => {
  input.addEventListener("input", () => {
    emailError.textContent = "";
    codeError.textContent = "";
    passwordError.textContent = "";
    confirmError.textContent = "";
    formMessage.textContent = "";
    formMessage.style.color = "red";
  });
});

// ==========================
// FORM SUBMISSION
// ==========================
form.addEventListener("submit", async (e) => {
  e.preventDefault();

  // Clear previous errors
  emailError.textContent = "";
  codeError.textContent = "";
  passwordError.textContent = "";
  confirmError.textContent = "";
  formMessage.textContent = "";
  formMessage.style.color = "red";

  spinner.style.display = "block";

  const payload = {
    email: emailInput.value.trim(),
    code: codeInput.value.trim(),
    password: passwordInput.value, 
    confirm_password: confirmInput.value 
  };

  try {
    const response = await fetch("resetHandler.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    });

    const data = await response.json();
    spinner.style.display = "none";

    // ==========================
    // HANDLE RESPONSE 
    // ==========================
    if (data.status === "success") {
      formMessage.style.color = "green";
      formMessage.textContent = data.message;
      if (data.redirect) {
          setTimeout(() => window.location.href = data.redirect, 1500);
      }
    } else {
      if (data.field === "email") emailError.textContent = data.message;
      else if (data.field === "code") codeError.textContent = data.message;
      else if (data.field === "password") passwordError.textContent = data.message;
      else if (data.field === "confirm_password") confirmError.textContent = data.message;
      else formMessage.textContent = data.message;
    }

  } catch (err) {
    // ==========================
    // HANDLE NETWORK OR UNEXPECTED ERRORS
    // ==========================
    spinner.style.display = "none";
    formMessage.style.color = "red";
    formMessage.textContent = "An unexpected error occurred. Please try again.";
    console.error("Fetch Error:", err);
  }
});
</script>

<?php require __DIR__ . "/../../includes/footer.php"; ?>