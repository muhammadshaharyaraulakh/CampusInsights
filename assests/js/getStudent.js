
document.querySelectorAll('.js-section-btn').forEach(btn => {
    btn.addEventListener('click', async e => {
        e.preventDefault();
        const sectionId = btn.dataset.sectionId;
        const batchId = btn.dataset.batchId;

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

            if (data.status === "success") {
                sectionTitle.textContent = `Section ${data.section_name} Students`;
                batchInfo.textContent = `Batch ${data.batch_year}`;
                totalCount.textContent = data.total;
                pendingCount.textContent = data.pending;

                data.students.forEach(s => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td><div class="user-info"><div><span style="display:block;font-weight:600;">${s.username}</span></div></div></td>
                        <td><div class="user-info"><div><span style="display:block;font-weight:600;">${s.registration_no}</span></div></div></td>
                        <td><span class="badge ${s.status === 'Pending' ? 'badge-pending' : 'badge-success'}">${s.status}</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon edit" data-id="${s.id}"><i class="fas fa-edit"></i></button>
                                <button class="btn-icon delete" data-id="${s.id}"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                tableSection.style.display = 'none';
                showToast(data.message, "error");
            }
        } catch(err) {
            tableSection.style.display = 'none';
            showToast("Unexpected error occurred.", "error");
        }
    });
});
