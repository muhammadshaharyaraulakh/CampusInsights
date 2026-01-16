<?php require_once __DIR__ . "/../../includes/header.php";
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
  header("Location: /index.php");
  exit;
} 
?>
<section class="login-section">
  <div class="container login-container">
    <div class="login-form-card fade-in" id="signupFormCard">
      <h2>Join Our Community</h2>
      <form id="signupForm" class="login-form" novalidate>
        <div class="input-group">
          <label for="username">Username</label>
          <div class="input-field-wrapper">
            <input type="text" id="username" name="username" placeholder="Choose a unique username">
            <i class="fas fa-user icon"></i>
          </div>
          <div class="error-message" id="usernameError"></div>
        </div>

        <div class="input-group">
          <label for="email">Email Address</label>
          <div class="input-field-wrapper">
            <input type="email" id="email" name="email" placeholder="Enter Gmail">
            <i class="fas fa-envelope icon"></i>
          </div>
          <div class="error-message" id="emailError"></div>
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <div class="input-field-wrapper">
            <input type="password" id="password" name="password" placeholder="Password">
            <i class="fas fa-lock icon"></i>
            <button type="button" class="toggle-password" id="togglePassword"><i class="far fa-eye-slash"></i></button>
          </div>
          <div class="error-message" id="passwordError"></div>
        </div>

        <div class="input-group">
          <label for="confirm_password">Confirm Password</label>
          <div class="input-field-wrapper">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
            <i class="fas fa-lock icon"></i>
            <button type="button" class="toggle-password" id="toggleConfirmPassword"><i class="far fa-eye-slash"></i></button>
          </div>
          <div class="error-message" id="confirmError"></div>
        </div>

        <div class="input-group" style="text-align:center;font-size:0.9rem;">
          <input type="checkbox" id="terms" name="terms" style="width:auto;margin-right:0.5rem;">
          <label for="terms" style="display:inline;font-weight:400;">
            I agree to the <a href="#">Terms and Privacy Policy</a>
          </label>
          <div class="error-message" id="termsError"></div>
        </div>

        <button type="submit" class="btn btn-primary login-btn">Create Account</button>
        <div id="formMessage"></div>
        <div id="spinner">
          <i class="fas fa-spinner fa-spin"></i> Creating account
        </div>
      </form>

      <div class="signup-link">
        <p>Already have an account? <a href="../login/login.php">Login</a></p>
      </div>
    </div>
  </div>
</section>

<script>
  // ==========================
  // GET DOM ELEMENTS
  // ==========================
  const usernameInput = document.getElementById("username");
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password");
  const confirmInput = document.getElementById("confirm_password");
  const termsInput = document.getElementById("terms");

  const usernameError = document.getElementById("usernameError");
  const emailError = document.getElementById("emailError");
  const passwordError = document.getElementById("passwordError");
  const confirmError = document.getElementById("confirmError");
  const termsError = document.getElementById("termsError");
  const formMessage = document.getElementById("formMessage");
  const spinner = document.getElementById("spinner");

  let Signup = document.getElementById("signupForm");

  // ==========================
  // CLEAR ERROR MESSAGES ON INPUT
  // ==========================
  [usernameInput, emailInput, passwordInput, confirmInput, termsInput].forEach(input => {
    input.addEventListener("input", () => {
      usernameError.innerHTML = "";
      emailError.innerHTML = "";
      passwordError.innerHTML = "";
      confirmError.innerHTML = "";
      termsError.innerHTML = "";
      formMessage.innerHTML = "";
    });
  });

  // ==========================
  // FORM SUBMISSION
  // ==========================
  Signup.addEventListener("submit", async (e) => {
    e.preventDefault();
    usernameError.innerHTML = "";
    emailError.innerHTML = "";
    passwordError.innerHTML = "";
    confirmError.innerHTML = "";
    termsError.innerHTML = "";
    formMessage.innerHTML = "";

    spinner.style.display = "block";

    const formData = new FormData(e.target);

    try {
      // ==========================
      // SEND AJAX REQUEST TO HANDLER
      // ==========================
      const response = await fetch("SignUpHandler.php", {
        method: "POST",
        body: formData
      });

      const data = await response.json();
      spinner.style.display = "none";

      // ==========================
      // HANDLE RESPONSE
      // ==========================
      if (data.status === "success") {
        formMessage.style.color = "green";
        formMessage.innerHTML = data.message;
        if (data.redirect) {
          setTimeout(() => {
            window.location.href = data.redirect;
          }, 1200);
        }

      } else {
        formMessage.style.color = "red";
        if (data.field === "username") usernameError.innerHTML = data.message;
        else if (data.field === "email") emailError.innerHTML = data.message;
        else if (data.field === "password") passwordError.innerHTML = data.message;
        else if (data.field === "confirm_password") confirmError.innerHTML = data.message;
        else if (data.field === "terms") termsError.innerHTML = data.message;
        else formMessage.innerHTML = data.message; 
      }
    } catch (err) {
      // ==========================
      // HANDLE NETWORK OR UNEXPECTED ERRORS
      // ==========================
      spinner.style.display = "none";
      formMessage.style.color = "red";
      formMessage.innerHTML = "An unexpected error occurred. Please try again.";
    }
  });
</script>


<?php require_once __DIR__ . "/../../includes/footer.php" ?>