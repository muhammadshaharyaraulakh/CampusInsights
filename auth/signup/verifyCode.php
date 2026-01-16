<?php
require_once __DIR__ . "/../../includes/header.php";
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    header("Location: /index.php"); 
    exit;
}

if (!isset($_SESSION['pending_signup'])) {
  header("Location: /auth/signup/signup.php");
  exit;
}

$email = $_SESSION['pending_signup']['email'];
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
  // ==========================
  // GET DOM ELEMENTS
  // ==========================
  const verifyForm = document.getElementById("verifyForm");
  const codeInput = document.getElementById("code");
  const codeError = document.getElementById("codeError");
  const verifyMsg = document.getElementById("formMessage");
  const spinner = document.getElementById("spinner");

  // ==========================
  // CLEAR ERRORS ON INPUT
  // ==========================
  codeInput.addEventListener("input", () => {
    codeError.textContent = "";
    verifyMsg.textContent = "";
  });

  // ==========================
  // FORM SUBMISSION HANDLER
  // ==========================
  verifyForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    // ==========================
    // CLEARING THE PREVIUOS ERRORS FOR SUBMISSION
    // ==========================
    codeError.textContent = "";
    verifyMsg.textContent = "";
    spinner.style.display = "block";
    const formData = new FormData();
    formData.append("code", codeInput.value);

    try {
      const response = await fetch("verificationHandler.php", {
        method: "POST",
        body: formData
      });

      const data = await response.json();
      spinner.style.display = "none";

      // ==========================
      // HANDLE SERVER RESPONSE
      // ==========================
      if (data.status === "success") {
        verifyMsg.style.color = "green";
        verifyMsg.textContent = data.message;
        if (data.redirect) {
          setTimeout(() => {
            window.location.href = data.redirect;
          }, 1200);
        }

      } else {
        verifyMsg.style.color = "red";
        if (data.field === "code") codeError.textContent = data.message;
        else verifyMsg.textContent = data.message;
      }

    } catch (err) {
      // ==========================
      // HANDLE UNEXPECTED ERRORS
      // ==========================
      spinner.style.display = "none";
      verifyMsg.style.color = "red";
      verifyMsg.textContent = "An unexpected error occurred. Please try again.";
    }
  });
</script>
<?php require_once __DIR__ . "/../../includes/footer.php"; ?><?php
