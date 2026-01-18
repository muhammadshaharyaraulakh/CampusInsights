
document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("createBatchForm");

    const batchYearInput = document.getElementById("batch_year");
    const batchYearError = document.getElementById("batchYearError");
    const sectionsError  = document.getElementById("sectionsError");
    const generalError   = document.getElementById("formMessage");
    const spinner        = document.getElementById("spinner");

    // Clear errors
    form.addEventListener("input", () => {
        batchYearError.textContent = "";
        sectionsError.textContent = "";
        generalError.textContent  = "";
    });

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        batchYearError.textContent = "";
        sectionsError.textContent = "";
        generalError.textContent  = "";
        spinner.style.display     = "block";

        const formData = new FormData(form);
        formData.set("batch_year", batchYearInput.value.trim());

        try {
            const response = await fetch("/admin/handlers/batch.php", {
                method: "POST",
                body: formData
            });

            const data = await response.json();
            spinner.style.display = "none";

            if (data.status === "success") {
                generalError.style.color = "green";
                generalError.textContent = data.message;
                form.reset();
            } else {
                if (data.field === "batch_year") {
                    batchYearError.textContent = data.message;
                } else if (data.field === "sections") {
                    sectionsError.textContent = data.message;
                } else {
                    generalError.style.color = "red";
                    generalError.textContent = data.message;
                }
            }

        } catch (err) {
            spinner.style.display = "none";
            generalError.style.color = "red";
            generalError.textContent = "Unexpected error occurred.";
        }
    });

});

