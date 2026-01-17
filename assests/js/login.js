
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
            const response = await fetch("/auth/login/handler.php", {
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


