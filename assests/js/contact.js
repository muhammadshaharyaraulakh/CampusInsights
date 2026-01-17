
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');

    

    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!contactForm.checkValidity()) {
            showToast('Please fill out all required fields.', 'error');
            return;
        }
        submitBtn.disabled = true;
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = 'Sending <i class="fas fa-spinner fa-spin"></i>';

        const formData = new FormData(contactForm);

        fetch('/pages/contact_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                contactForm.reset();
                setTimeout(() => {
                    window.location.href = '/index.php';
                }, 2000);
            } else {
                showToast(data.message || 'An error occurred.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to connect to the server.', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    });
});
