
document.querySelectorAll('.js-section-btn').forEach(btn => {
    btn.addEventListener('click', async e => {
        e.preventDefault();

        const sectionId = btn.dataset.sectionId;

        const tableSection = document.querySelector('.students-dashboard-section');
        const tbody = tableSection.querySelector('.students-tbody');
        const sectionTitle = tableSection.querySelector('.section-title');
        const batchInfo = tableSection.querySelector('.batch-info');
        const totalCount = tableSection.querySelector('.total-count');
        const pendingCount = tableSection.querySelector('.pending-count');

        tbody.innerHTML = '';
        sectionTitle.textContent = '';
        batchInfo.textContent = '';
        totalCount.textContent = '0';
        pendingCount.textContent = '0';
        tableSection.style.display = 'block';

        try {
            const res = await fetch(`/admin/handlers/getStudents.php?section_id=${sectionId}`);
            const data = await res.json();

            if (data.status !== "success") {
                tableSection.style.display = 'none';
                showToast(data.message, "error");
                return;
            }

            sectionTitle.textContent = `Section ${data.section_name} Students`;
            batchInfo.textContent = `Batch ${data.batch_year}`;
            totalCount.textContent = data.total;
            pendingCount.textContent = data.pending;

            data.students.forEach(s => {
                const tr = document.createElement('tr');
                tr.dataset.status = s.status;

                tr.innerHTML = `
                    <td>${s.username}</td>
                    <td>${s.registration_no}</td>
                    <td>
                        <span class="badge ${s.status === 'Pending' ? 'badge-pending' : 'badge-success'}">
                            ${s.status}
                        </span>
                    </td>
                    <td>
                        <button class="btn-icon delete" data-id="${s.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                `;

                tbody.appendChild(tr);
            });

        } catch {
            tableSection.style.display = 'none';
            showToast("Unexpected error occurred.", "error");
        }
    });
});


document.addEventListener('click', async e => {
    const deleteBtn = e.target.closest('.btn-icon.delete');
    if (!deleteBtn) return;

    e.preventDefault();

    

    const studentId = deleteBtn.dataset.id;
    const row = deleteBtn.closest('tr');
    const isPending = row.dataset.status === 'Pending';

    try {
        const res = await fetch('/admin/handlers/deleteStudent.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id=${studentId}`
        });

        const data = await res.json();

        if (data.status === 'success') {
            row.remove();

            const totalEl = document.querySelector('.total-count');
            const pendingEl = document.querySelector('.pending-count');

            totalEl.textContent = Math.max(0, +totalEl.textContent - 1);
            if (isPending) {
                pendingEl.textContent = Math.max(0, +pendingEl.textContent - 1);
            }

            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }

    } catch {
        showToast('Unexpected error occurred.', 'error');
    }
});
let currentSectionId = null;

document.querySelectorAll('.js-section-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        currentSectionId = btn.dataset.sectionId;
    });
});

document.getElementById('downloadPendingBtn').addEventListener('click', () => {
    if (!currentSectionId) {
        showToast('Select a section first', 'error');
        return;
    }

    window.location.href =
        `/admin/handlers/downloadPendingStudents.php?section_id=${currentSectionId}`;
});
