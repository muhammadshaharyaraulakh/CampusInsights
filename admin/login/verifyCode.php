<?php
require_once __DIR__ . "/../../config/config.php";

if (!isset($_SESSION['admin_otp_email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['admin_otp_email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Admin Login</title>
    <link rel="stylesheet" href="/assests/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<section class="login-section">
  <div class="container login-container">
    <div class="login-form-card fade-in">
      <h2>Authentication</h2>
      <p style="text-align:center;margin-bottom:1rem;">
        Enter the OTP sent to <br>
        <strong><?= htmlspecialchars($email) ?></strong>
      </p>

      <form id="adminVerifyForm" class="login-form">
        <div class="input-group">
          <label for="code">Verification Code</label>
          <div class="input-field-wrapper">
            <input type="text" id="code" name="code" placeholder="Enter 16-digit code">
            <i class="fas fa-key icon"></i>
          </div>
          <div class="error-message" id="codeError"></div>
        </div>

        <button class="btn btn-primary login-btn" type="submit">Verify Access</button>

        <div id="formMessage"></div>
        <div id="spinner" style="display:none;">
          <i class="fas fa-spinner fa-spin"></i> Verifying
        </div>
      </form>
    </div>
  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const verifyForm = document.getElementById("adminVerifyForm");
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
            const response = await fetch("/admin/login/verifyHandler.php", {
                method: "POST",
                body: formData
            });

            const data = await response.json();
            spinner.style.display = "none";

            if (data.status === "success") {
                verifyMsg.style.color = "green";
                verifyMsg.textContent = "Verified SuccessFully";

                setTimeout(() => {
                    if (data.redirect) window.location.href = data.redirect;
                }, 1500);
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
</body>
</html>