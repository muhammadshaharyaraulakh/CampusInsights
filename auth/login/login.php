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

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const togglePasswordBtn = document.querySelector(".toggle-password");
    const form = document.getElementById("loginForm");
    const emailInput = document.getElementById("email");
    const registrationInput = document.getElementById("registration_no");
    const emailError = document.getElementById("emailError");
    const registrationError = document.getElementById("registrationError");
    const generalError = document.getElementById("formMessage");
    const spinner = document.getElementById("spinner");


    togglePasswordBtn.addEventListener("click", () => {
      const icon = togglePasswordBtn.querySelector("i");
      if (registrationInput.type === "password") {
        registrationInput.type = "text";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      } else {
        registrationInput.type = "password";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      }
    });


    [emailInput, registrationInput].forEach(input => {
      input.addEventListener("input", () => {
        emailError.textContent = "";
        registrationError.textContent = "";
        generalError.textContent = "";
      });
    });


    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      emailError.textContent = "";
      registrationError.textContent = "";
      generalError.textContent = "";
      spinner.style.display = "block";

      await new Promise(resolve => setTimeout(resolve, 1000));

      const formData = new FormData(form);
      formData.set("email", emailInput.value.trim());
      formData.set("registration_no", registrationInput.value.trim());

      try {
        const response = await fetch("handler.php", {
          method: "POST",
          body: formData
        });
        const data = await response.json();

        spinner.style.display = "none";

        if (data.status === "success") {
          generalError.style.color = "green";
          generalError.textContent = data.message;
          if (data.redirect) {
            setTimeout(() => window.location.href = data.redirect, 2000);
          }
        } else {
          if (data.field === "email") emailError.textContent = data.message;
          else if (data.field === "registration_no") registrationError.textContent = data.message;
          else {
            generalError.style.color = "red";
            generalError.textContent = data.message;
          }
        }
      } catch (err) {
        spinner.style.display = "none";
        generalError.style.color = "red";
        generalError.textContent = "An unexpected error occurred. Please try again.";
      }
    });
  });
</script>


<?php require __DIR__ . "/../../includes/footer.php"; ?>