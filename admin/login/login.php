<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="/assests/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="login-page">
    
    <div class="login-container">
        <div class="login-card">
            <div class="login-form-card fade-in" id="loginFormCard">
                <h2>Admin Login</h2>

                <form id="adminLoginForm" class="login-form" novalidate>
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
                            <input type="password" id="password" name="password" placeholder="Password">
                            <i class="fas fa-lock icon"></i>
                            <button type="button" class="toggle-password" id="togglePassword">
                                <i class="far fa-eye-slash"></i>
                            </button>
                        </div>
                        <div class="error-message" id="passwordError"></div>
                    </div>

                    <button type="submit" class="btn btn-primary login-btn">Log In Securely</button>
                    <div id="formMessage"></div>
                    <div id="spinner" style="display:none;">
                        <i class="fas fa-spinner fa-spin"></i> Logging In
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const togglePasswordBtn = document.querySelector(".toggle-password");
    const form = document.getElementById("adminLoginForm"); 
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const emailError = document.getElementById("emailError");
    const passwordError = document.getElementById("passwordError");
    const generalError = document.getElementById("formMessage");
    const spinner = document.getElementById("spinner");

    togglePasswordBtn.addEventListener("click", () => {
        const icon = togglePasswordBtn.querySelector("i");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            passwordInput.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    });


    [emailInput, passwordInput].forEach(input => {
        input.addEventListener("input", () => {
            emailError.textContent = "";
            passwordError.textContent = "";
            generalError.textContent = "";
        });
    });


    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        emailError.textContent = "";
        passwordError.textContent = "";
        generalError.textContent = "";
        spinner.style.display = "block";
        await new Promise(resolve => setTimeout(resolve, 1000));

        const formData = new FormData(form);
        formData.set("email", emailInput.value.trim());
        formData.set("password", passwordInput.value.trim());

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
                    setTimeout(() => window.location.href = data.redirect, 1000);
                }
            } else {
                if (data.field === "email") emailError.textContent = data.message;
                else if (data.field === "password") passwordError.textContent = data.message;
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
</body>
</html>