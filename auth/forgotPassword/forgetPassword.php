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
<main>
    <section class="login-section">
        <div class="container login-container">
            <div class="login-form-card fade-in" id="forgotPasswordCard">
                <h2>Forgot Password?</h2>
                <p class="subtitle">
                    Enter your email and we'll send a reset code.
                </p>

                <form id="forgotPasswordForm" class="login-form" novalidate>
                    <div class="input-group">
                        <label for="email">Email Address</label>
                        <div class="input-field-wrapper">
                            <input type="email" id="email" name="email" placeholder="Enter your email">
                            <i class="fas fa-envelope icon"></i>
                        </div>
                        <div class="error-message" id="emailError"></div>
                    </div>

                    <button type="submit" class="btn btn-primary login-btn">
                        Send Reset Code
                    </button>

                    <div id="formMessage" class="error-message"></div>
                    <div id="spinner" style="display:none;">
                        <i class="fas fa-spinner fa-spin"></i> Sending
                    </div>
                </form>

                <div class="signup-link">
                    <p>Remembered your password? <a href="/auth/login/login.php">Log In</a></p>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
// ==========================
// GET DOM ELEMENTS
// ==========================
const emailInput = document.getElementById("email");
const emailError = document.getElementById("emailError");
const formMessage = document.getElementById("formMessage");
const spinner = document.getElementById("spinner");
const form = document.getElementById("forgotPasswordForm");

// ==========================
// CLEAR ERROR MESSAGES ON INPUT
// ==========================
emailInput.addEventListener("input", () => {
    emailError.textContent = "";
    formMessage.textContent = "";
    formMessage.style.color = "red"; // Reset color
});

// ==========================
// FORM SUBMISSION (Using JSON fetch consistent with the handler)
// ==========================
form.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Clear previous errors
    emailError.textContent = "";
    formMessage.textContent = "";
    formMessage.style.color = "red"; 
    spinner.style.display = "block";

    const emailValue = emailInput.value.trim();
    const dataToSend = { email: emailValue };

    try {
        // ==========================
        // SEND AJAX REQUEST
        // ==========================
        const response = await fetch("handler.php", {
            method: "POST",
            body: JSON.stringify(dataToSend),
            headers: { "Content-Type": "application/json" }
        });

        const data = await response.json();
        spinner.style.display = "none";

        // ==========================
        // HANDLE RESPONSE (Matching Signup Style)
        // ==========================
        if (data.status === "success") {
            formMessage.style.color = "green";
            formMessage.textContent = data.message;
            
            // Redirect to the reset page with email in query string
            setTimeout(() => {
                window.location.href = `resetPassword.php?email=${encodeURIComponent(emailValue)}`;
            }, 1500);

        } else {
            // Handle specific field errors or general errors
            if (data.field === "email") {
                emailError.textContent = data.message;
            } else {
                formMessage.textContent = data.message;
            }
        }

    } catch(err) {
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

<?php 
require __DIR__ . "/../../includes/footer.php"; 
?>