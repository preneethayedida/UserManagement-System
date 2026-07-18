/**
 * User Management CRUD AJAX controller
 */

const state = {
    search: '',
    status: '',
    sort: 'id',
    order: 'asc',
    page: 1,
    limit: 10
};

// Modals
let createModal;
let editModal;

document.addEventListener('DOMContentLoaded', () => {
    // Instantiate Bootstrap Modals
    createModal = new bootstrap.Modal(document.getElementById('createUserModal'));
    editModal = new bootstrap.Modal(document.getElementById('editUserModal'));

    // Init UI Listeners
    initFilterListeners();
    initSortableColumns();
    initCrudForms();
    
    // Initial Load
    fetchUsers();
});

/**
 * Fetch users via AJAX based on state parameters
 */
async function fetchUsers() {
    const spinner = document.getElementById('table-spinner');
    if (spinner) spinner.classList.add('show');

    const params = new URLSearchParams({
        search: state.search,
        status: state.status,
        sort: state.sort,
        order: state.order,
        page: state.page,
        limit: state.limit
    });

    const response = await ajaxRequest(`/admin/users/list?${params.toString()}`);
    
    if (spinner) spinner.classList.remove('show');

    if (response.ok && response.success) {
        renderTableRows(response.data);
        renderPagination(response.pagination);
        updateTableInfo(response.pagination);
    } else {
        showErrorToast('Failed to load users list.');
    }
}

/**
 * Render dynamic rows inside the table body
 */
function renderTableRows(users) {
    const tbody = document.getElementById('user-table-body');
    tbody.innerHTML = '';

    if (users.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4 text-muted">
                    <i class="bi bi-people fs-1 mb-2 d-block"></i> No users matching the criteria.
                </td>
            </tr>
        `;
        return;
    }

    users.forEach(user => {
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td class="px-4">
                <div class="d-flex align-items-center">
                    <img src="${user.profile_image}" alt="Avatar" width="36" height="36" class="rounded-circle border me-2 object-fit-cover shadow-sm">
                    <div class="fw-semibold fs-7">${user.full_name}</div>
                </div>
            </td>
            <td>@${user.username}</td>
            <td>${user.email}</td>
            <td>${user.phone || '<span class="text-muted fs-8">N/A</span>'}</td>
            <td>
                <span class="badge bg-secondary-subtle text-secondary border fs-9">${user.role_name}</span>
            </td>
            <td>
                <span class="badge-status ${user.status}">${capitalizeFirst(user.status)}</span>
            </td>
            <td class="text-muted fs-8">${user.created_at}</td>
            <td class="px-4 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-secondary" onclick="openEditModal(${user.id})" title="Edit Account">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteUser(${user.id})" title="Delete Account">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

/**
 * Render pagination links
 */
function renderPagination(pagination) {
    const paginationContainer = document.getElementById('table-pagination');
    paginationContainer.innerHTML = '';

    const { total_pages, current_page } = pagination;
    if (total_pages <= 1) return;

    // Previous Page Button
    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${current_page === 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${current_page - 1})"><i class="bi bi-chevron-left"></i></a>`;
    paginationContainer.appendChild(prevLi);

    // Numbered Pages
    for (let i = 1; i <= total_pages; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${current_page === i ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i})">${i}</a>`;
        paginationContainer.appendChild(li);
    }

    // Next Page Button
    const nextLi = document.createElement('li');
    nextLi.className = `page-item ${current_page === total_pages ? 'disabled' : ''}`;
    nextLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${current_page + 1})"><i class="bi bi-chevron-right"></i></a>`;
    paginationContainer.appendChild(nextLi);
}

/**
 * Update dynamic info footer (e.g. "Showing 1 to 10 of 42 entries")
 */
function updateTableInfo(pagination) {
    const infoContainer = document.getElementById('table-info');
    const { total_records, current_page, limit } = pagination;

    if (total_records === 0) {
        infoContainer.textContent = 'Showing 0 entries';
        return;
    }

    const start = (current_page - 1) * limit + 1;
    const end = Math.min(current_page * limit, total_records);
    infoContainer.textContent = `Showing ${start} to ${end} of ${total_records} entries`;
}

/**
 * Actions: Page & Search triggers
 */
window.changePage = function(pageNumber) {
    event.preventDefault();
    state.page = pageNumber;
    fetchUsers();
};

let searchTimer;
function initFilterListeners() {
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const resetBtn = document.getElementById('reset-filters-btn');

    if (searchInput) {
        // Debounce text search
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                state.search = searchInput.value;
                state.page = 1;
                fetchUsers();
            }, 300);
        });
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', () => {
            state.status = statusFilter.value;
            state.page = 1;
            fetchUsers();
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            state.search = '';
            state.status = '';
            state.sort = 'id';
            state.order = 'asc';
            state.page = 1;

            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            
            resetSortIcons();
            fetchUsers();
        });
    }
}

/**
 * Interactive columns sorting
 */
function initSortableColumns() {
    const headers = document.querySelectorAll('.sortable-col');
    
    headers.forEach(header => {
        header.addEventListener('click', () => {
            const field = header.getAttribute('data-sort');
            if (state.sort === field) {
                state.order = state.order === 'asc' ? 'desc' : 'asc';
            } else {
                state.sort = field;
                state.order = 'asc';
            }
            state.page = 1;
            
            updateSortIcons(field, state.order);
            fetchUsers();
        });
    });
}

function updateSortIcons(activeField, activeOrder) {
    resetSortIcons();
    const activeIcon = document.getElementById(`sort-icon-${activeField}`);
    if (activeIcon) {
        activeIcon.className = activeOrder === 'asc' ? 'bi bi-arrow-up text-primary' : 'bi bi-arrow-down text-primary';
    }
}

function resetSortIcons() {
    const icons = document.querySelectorAll('.sortable-col i');
    icons.forEach(icon => {
        icon.className = 'bi bi-arrow-down-up fs-9 text-muted ms-1';
    });
}

/**
 * Open edit user dialog, fetch data asynchronously
 */
window.openEditModal = async function(userId) {
    const editForm = document.getElementById('edit-user-form');
    editForm.reset();
    editForm.classList.remove('was-validated');

    const response = await ajaxRequest(`/admin/users/show/${userId}`);
    
    if (response.ok && response.success) {
        const u = response.data;
        document.getElementById('edit_user_id').value = u.id;
        document.getElementById('e_full_name').value = u.full_name;
        document.getElementById('e_username').value = u.username;
        document.getElementById('e_email').value = u.email;
        document.getElementById('e_phone').value = u.phone || '';
        document.getElementById('e_role_id').value = u.role_id;
        document.getElementById('e_status').value = u.status;
        
        editModal.show();
    } else {
        Swal.fire('Error', response.message || 'Failed to fetch user data.', 'error');
    }
};

/**
 * Handle CREATE and UPDATE form submissions via AJAX
 */
function initCrudForms() {
    const createForm = document.getElementById('create-user-form');
    if (createForm) {
        createForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!checkFormValidity(createForm)) return;

            const submitBtn = createForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Creating...';

            const formData = new FormData(createForm);
            const response = await ajaxRequest('/admin/users/store', 'POST', formData);

            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Create User';

            if (response.ok && response.success) {
                createModal.hide();
                createForm.reset();
                createForm.classList.remove('was-validated');
                
                // Reset password visual meter strength text
                const pMeter = document.getElementById('c_password-strength');
                if (pMeter) pMeter.textContent = '';

                Swal.fire({
                    icon: 'success',
                    title: 'Created!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                fetchUsers();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    html: response.message,
                    confirmButtonColor: 'var(--bs-primary)'
                });
            }
        });
    }

    const editForm = document.getElementById('edit-user-form');
    if (editForm) {
        editForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!checkFormValidity(editForm)) return;

            const userId = document.getElementById('edit_user_id').value;
            const submitBtn = editForm.querySelector('button[type="submit"]');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Updating...';

            const formData = new FormData(editForm);
            const response = await ajaxRequest(`/admin/users/update/${userId}`, 'POST', formData);

            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Update User';

            if (response.ok && response.success) {
                editModal.hide();
                editForm.reset();
                editForm.classList.remove('was-validated');
                
                // Reset password visual meter strength text
                const pMeter = document.getElementById('e_password-strength');
                if (pMeter) pMeter.textContent = '';

                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                fetchUsers();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    html: response.message,
                    confirmButtonColor: 'var(--bs-primary)'
                });
            }
        });
    }
}

/**
 * Handle user DELETE trigger via AJAX
 */
window.deleteUser = function(userId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this account deletion!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            // Spinner indicator
            Swal.fire({
                title: 'Deleting...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData();
            formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            const response = await ajaxRequest(`/admin/users/delete/${userId}`, 'POST', formData);
            Swal.close();

            if (response.ok && response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Adjust pagination page if page empty
                const rowCount = document.querySelectorAll('#user-table-body tr').length;
                if (rowCount === 1 && state.page > 1) {
                    state.page--;
                }
                
                fetchUsers();
            } else {
                Swal.fire('Failed', response.message || 'Failed to delete user.', 'error');
            }
        }
    });
};

/**
 * Visual tools & text formatting helpers
 */
function capitalizeFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function showErrorToast(msg) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: msg,
        toast: true,
        position: 'top-end',
        timer: 3000,
        showConfirmButton: false
    });
}
