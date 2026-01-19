const studentForm = document.getElementById('singleStudentForm');

studentForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Hide previous errors
    studentForm.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    
    // Show spinner if any
    const spinner = studentForm.querySelector('#spinner');
    if (spinner) spinner.style.display = 'inline-block';

    const formData = new FormData(studentForm);

    try {
        const res = await fetch('/admin/handlers/addStudent.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();

        if (data.status === 'success') {
            showToast(data.message, 'success');
            studentForm.reset();
        } else {
            // If field-specific error
            if (data.field && data.field !== 'general') {
                const errorEl = document.getElementById(`error-${data.field}`);
                if (errorEl) errorEl.textContent = data.message;
            } else {
                // General error toast
                showToast(data.message, 'error');
            }
        }
    } catch (err) {
        showToast('Unexpected error occurred.', 'error');
        console.error(err);
    } finally {
        if (spinner) spinner.style.display = 'none';
    }
});
