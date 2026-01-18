
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

    function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');

    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <i class="fas ${
            type === 'success' ? 'fa-check-circle' :
            type === 'error' ? 'fa-times-circle' :
            'fa-info-circle'
        }"></i>
        <span>${message}</span>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 4000);
}

    function showForm(type) {
        const bulkSection = document.getElementById('bulkImportSection');
        const singleSection = document.getElementById('singleStudentSection');
        
        const btnBulk = document.getElementById('btnShowBulk');
        const btnSingle = document.getElementById('btnShowSingle');

        if (type === 'bulk') {
            bulkSection.style.display = 'block';
            singleSection.style.display = 'none';
            
            btnBulk.classList.remove('btn-secondary');
            btnBulk.classList.add('btn-primary');
            
            btnSingle.classList.remove('btn-primary');
            btnSingle.classList.add('btn-secondary');

        } else if (type === 'single') {
            bulkSection.style.display = 'none';
            singleSection.style.display = 'block';

            btnSingle.classList.remove('btn-secondary');
            btnSingle.classList.add('btn-primary');
            
            btnBulk.classList.remove('btn-primary');
            btnBulk.classList.add('btn-secondary');
        }
    }
