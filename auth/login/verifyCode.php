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
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const verifyForm = document.getElementById("verifyForm");
    const codeInput = document.getElementById("code");
    const codeError = document.getElementById("codeError");
    const verifyMsg = document.getElementById("formMessage");
    const spinner = document.getElementById("spinner");
    codeInput.addEventListener("input", () => {
      codeError.textContent = "";
      verifyMsg.textContent = "";
    });

    verifyForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      codeError.textContent = "";
      verifyMsg.textContent = "";
      spinner.style.display = "block";
      await new Promise(resolve => setTimeout(resolve, 1000));

      const formData = new FormData();
      formData.append("code", codeInput.value.trim());

      try {
        const response = await fetch("verificationHandler.php", {
          method: "POST",
          body: formData
        });

        const data = await response.json();
        spinner.style.display = "none";

        if (data.status === "success") {
          verifyMsg.style.color = "green";
          verifyMsg.textContent = "Verification code sent successfully.";

          setTimeout(() => {
            if (data.redirect) window.location.href = data.redirect;
          }, 2000);
        } else {
          verifyMsg.style.color = "red";
          if (data.field === "code") {
            codeError.textContent = data.message;
          } else {
            verifyMsg.textContent = data.message;
          }
        }
      } catch (err) {
        spinner.style.display = "none";
        verifyMsg.style.color = "red";
        verifyMsg.textContent = "An unexpected error occurred. Please try again.";
      }
    });
  });
</script>

<?php require_once __DIR__ . "/../../includes/footer.php"; ?>