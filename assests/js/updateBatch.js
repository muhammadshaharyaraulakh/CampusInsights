document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("updateBatchForm");
    const spinner = document.getElementById("spinner");


    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        batchSelectError.textContent = "";
        spinner.style.display        = "inline-block";

        const formData = new FormData(form);

        try {
            const response = await fetch("/admin/handlers/updateBatch.php", {
                method: "POST",
                body: formData
            });

            const data = await response.json();
            spinner.style.display = "none";

            if (data.status === "success") {
                showToast(data.message, "success");
                form.reset();
            } else {

                if (data.status !== "success") {
                    showToast(data.message, "error");
                }
            }
        } catch (err) {
            spinner.style.display = "none";
            showToast("Unexpected error occurred.", "error");
        }
    });
});
