
    document.addEventListener('DOMContentLoaded', function() {
        
        // Elements Select karein
        const toggleBtn = document.getElementById('menu-toggle');
        const closeBtn = document.getElementById('sidebar-close');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        // Function: Menu Kholnay/Band karnay ke liye
        function toggleMenu() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        // Function: Menu Band karnay ke liye (Force Close)
        function closeMenu() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }

        // 1. Hamburger Icon Click -> Toggle
        if (toggleBtn) {
            toggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleMenu();
            });
        }

        // 2. Close Button (X) inside Sidebar -> Close
        if (closeBtn) {
            closeBtn.addEventListener('click', closeMenu);
        }

        // 3. Overlay (Black area) Click -> Close
        if (overlay) {
            overlay.addEventListener('click', closeMenu);
        }

        // 4. Escape Key Press -> Close
        document.addEventListener('keydown', (e) => {
            if (e.key === "Escape" && sidebar.classList.contains('active')) {
                closeMenu();
            }
        });
    });
