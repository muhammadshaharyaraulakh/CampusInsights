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
                verifyMsg.textContent = "Verified! Redirecting";

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