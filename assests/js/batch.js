document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("createBatchForm");

    const batchYearInput = document.getElementById("batch_year");
    const batchYearError = document.getElementById("batchYearError");
    const sectionsError  = document.getElementById("sectionsError");
    const spinner        = document.getElementById("spinner");

    form.addEventListener("input", () => {
        batchYearError.textContent = "";
        sectionsError.textContent  = "";
    });

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        batchYearError.textContent = "";
        sectionsError.textContent  = "";
        spinner.style.display      = "block";

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
                showToast(data.message, "success");
                form.reset();
            } else {
                if (data.field === "batch_year") {
                    batchYearError.textContent = data.message;
                } else if (data.field === "sections") {
                    sectionsError.textContent = data.message;
                } else {
                    showToast(data.message, "error");
                }
            }

        } catch (err) {
            spinner.style.display = "none";
            showToast("Unexpected error occurred.", "error");
        }
    });

});
