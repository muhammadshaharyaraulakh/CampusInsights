document.addEventListener('DOMContentLoaded', function() {
    const adminPage = document.querySelector('.admin-page');
    const menuToggle = document.getElementById('menu-toggle');
    const mainWrapper = document.querySelector('.main-wrapper');

    if (menuToggle && adminPage) {
        
        // Function to toggle the sidebar state
        function toggleSidebar() {
            adminPage.classList.toggle('sidebar-open');
        }

        // 1. Toggle button handler
        menuToggle.addEventListener('click', toggleSidebar);

        // 2. Close sidebar if screen resizes past the mobile threshold 
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768 && adminPage.classList.contains('sidebar-open')) {
                 adminPage.classList.remove('sidebar-open');
            }
        });

        // 3. Close sidebar if user clicks outside of it (on mobile)
        mainWrapper.addEventListener('click', function(e) {
            // Check if we are on a mobile screen AND the sidebar is open
            if (window.innerWidth <= 768 && adminPage.classList.contains('sidebar-open')) {
                // Prevent closing if clicking on the menu button itself
                if (!menuToggle.contains(e.target)) {
                    adminPage.classList.remove('sidebar-open');
                }
            }
        });
    }
      const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.full-response-table input[type="checkbox"][name="response[]"]');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        });
    }
});