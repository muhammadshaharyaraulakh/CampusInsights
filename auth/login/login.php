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
          <label for="password">Password</label>
          <div class="input-field-wrapper">
            <input type="password" id="password" name="password" placeholder="Enter Password">
            <i class="fas fa-lock icon"></i>
            <button type="button" class="toggle-password" id="togglePassword">
              <i class="far fa-eye-slash"></i>
            </button>
          </div>
          <div class="error-message" id="passwordError"></div>
        </div>

        <div class="forgot-password">
          <a href="/auth/forgotPassword/forgetPassword.php">Forgot Password?</a>
        </div>

        <button type="submit" class="btn btn-primary login-btn">Log In Securely</button>
        <div id="formMessage"></div>
        <div id="spinner">
          <i class="fas fa-spinner fa-spin"></i> Logging In
        </div>
      </form>

      <div class="signup-link">
        <p>Don't have an account? <a href="../signup/signup.php">Sign Up</a></p>
      </div>
    </div>
  </div>
</section>

<script>
  // ==========================
  // GET DOM ELEMENTS
  // ==========================
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password");
  const emailError = document.getElementById("emailError");
  const passwordError = document.getElementById("passwordError");
  const generalError = document.getElementById("formMessage");
  const spinner = document.getElementById("spinner");
  const form = document.getElementById("loginForm");

  // ==========================
  // CLEAR ERRORS ON INPUT
  // ==========================
  [emailInput, passwordInput].forEach(input => {
    input.addEventListener("input", () => {
      emailError.textContent = "";
      passwordError.textContent = "";
      generalError.textContent = "";
    });
  });

  // ==========================
  // HANDLE FORM SUBMISSION
  // ==========================
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    // ==========================
    // PREPARE FORM DATA
    // ==========================
    const formData = new FormData(form);
    formData.set("email", emailInput.value.trim());
    formData.set("password", passwordInput.value.trim());

    // ==========================
    // CLEAR PREVIOUS ERRORS
    // ==========================
    emailError.textContent = "";
    passwordError.textContent = "";
    generalError.textContent = "";

    // ==========================
    // SHOW LOADING SPINNER
    // ==========================
    spinner.style.display = "block";

    try {
      // ==========================
      // SEND LOGIN REQUEST TO SERVER
      // ==========================
      const response = await fetch("handler.php", { method: "POST", body: formData });
      const data = await response.json();
      spinner.style.display = "none";

      // ==========================
      // HANDLE SERVER RESPONSE
      // ==========================
      if (data.status === "success") {
        emailError.textContent = "";
        passwordError.textContent = "";
        generalError.textContent = "Logging In";
        generalError.style.color="green";
        setTimeout(() => window.location.href = data.redirect,1500);
      } else {
        if (data.field === "email") {
          emailError.textContent = data.message;
        } else if (data.field === "password") {
          passwordError.textContent = data.message;
        } else {
          generalError.textContent = data.message;
          generalError.style.color="red";
        }
      }
    } catch (err) {
      // ==========================
      // HANDLE UNEXPECTED ERRORS
      // ==========================
      spinner.style.display = "none";
      generalError.textContent = "An unexpected error occurred. Please try again.";
    }
  });
</script>


<?php require __DIR__ . "/../../includes/footer.php"; ?>
