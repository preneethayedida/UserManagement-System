<?php include ROOT_PATH . 'views/layouts/header.php'; ?>

<div class="app-container">
    <!-- Sidebar Navigation -->
    <?php include ROOT_PATH . 'views/partials/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Top Navbar -->
        <?php include ROOT_PATH . 'views/partials/navbar.php'; ?>

        <div class="container-fluid p-4">
            <!-- Breadcrumbs -->
            <?php include ROOT_PATH . 'views/partials/breadcrumbs.php'; ?>

            <!-- Page Title -->
            <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h2 class="fw-bold font-heading mb-1 text-primary">User Management</h2>
                    <p class="text-muted mb-0">Create, search, filter, edit, and delete system user accounts.</p>
                </div>
                <div>
                    <button class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2.5 fw-bold" 
                            data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="bi bi-person-plus-fill"></i> <span>Add New User</span>
                    </button>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="card border shadow-sm mb-4">
                <div class="card-body p-3">
                    <form id="filter-form" class="row g-3 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-body text-muted border-end-0"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" id="search-input" 
                                       placeholder="Search by name, email, username or phone...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="status-filter">
                                <option value="">Filter by Status: All</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div class="col-md-2 ms-auto text-end">
                            <button type="button" class="btn btn-outline-secondary w-100 fw-bold" id="reset-filters-btn">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Listing Table -->
            <div class="card border shadow-sm position-relative">
                <!-- Spinner loader overlay -->
                <div class="spinner-overlay" id="table-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive border-0">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 sortable-col" data-sort="full_name" style="cursor:pointer;">
                                        User <i class="bi bi-arrow-down-up fs-9 text-muted ms-1" id="sort-icon-full_name"></i>
                                    </th>
                                    <th class="sortable-col" data-sort="username" style="cursor:pointer;">
                                        Username <i class="bi bi-arrow-down-up fs-9 text-muted ms-1" id="sort-icon-username"></i>
                                    </th>
                                    <th class="sortable-col" data-sort="email" style="cursor:pointer;">
                                        Email <i class="bi bi-arrow-down-up fs-9 text-muted ms-1" id="sort-icon-email"></i>
                                    </th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th class="sortable-col" data-sort="status" style="cursor:pointer;">
                                        Status <i class="bi bi-arrow-down-up fs-9 text-muted ms-1" id="sort-icon-status"></i>
                                    </th>
                                    <th class="sortable-col" data-sort="created_at" style="cursor:pointer;">
                                        Joined <i class="bi bi-arrow-down-up fs-9 text-muted ms-1" id="sort-icon-created_at"></i>
                                    </th>
                                    <th class="px-4 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="user-table-body">
                                <!-- Async HTML dynamic insertion -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Table Footer Pagination -->
                <div class="card-footer bg-transparent border-top p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="text-muted fs-7" id="table-info">
                        Showing 0 to 0 of 0 entries
                    </div>
                    <nav aria-label="Table pagination">
                        <ul class="pagination mb-0" id="table-pagination">
                            <!-- Dynamically loaded -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================= -->
<!-- CREATE USER MODAL -->
<!-- ======================================================================= -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold font-heading" id="createUserModalLabel"><i class="bi bi-person-plus-fill text-primary me-2"></i>Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create-user-form" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="c_full_name" class="form-label fs-7 fw-semibold">Full Name</label>
                        <input type="text" class="form-control" id="c_full_name" name="full_name" required pattern="^[a-zA-Z\s]{3,100}$">
                        <div class="invalid-feedback">Full name is required (letters/spaces only, min 3).</div>
                    </div>
                    <div class="mb-3">
                        <label for="c_username" class="form-label fs-7 fw-semibold">Username</label>
                        <input type="text" class="form-control" id="c_username" name="username" required pattern="^[a-zA-Z0-9_]{3,50}$">
                        <div class="invalid-feedback">Username is required (alphanumeric/underscore, min 3).</div>
                    </div>
                    <div class="mb-3">
                        <label for="c_email" class="form-label fs-7 fw-semibold">Email Address</label>
                        <input type="email" class="form-control" id="c_email" name="email" required>
                        <div class="invalid-feedback">Valid email address is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="c_phone" class="form-label fs-7 fw-semibold">Phone Number <span class="text-muted fw-normal">(Optional)</span></label>
                        <input type="tel" class="form-control" id="c_phone" name="phone" pattern="^\+?[0-9]{7,15}$">
                        <div class="invalid-feedback">Provide a valid phone number (7-15 digits).</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="c_role_id" class="form-label fs-7 fw-semibold">System Role</label>
                            <select class="form-select" id="c_role_id" name="role_id" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>" <?= $role['id'] == 2 ? 'selected' : '' ?>><?= e($role['role_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="c_status" class="form-label fs-7 fw-semibold">Status</label>
                            <select class="form-select" id="c_status" name="status" required>
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="c_password" class="form-label fs-7 fw-semibold">Password</label>
                        <input type="password" class="form-control strength-check" id="c_password" name="password" required>
                        <div class="invalid-feedback">Password is required (min 8 chars, mixed case, number, symbol).</div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================================= -->
<!-- EDIT USER MODAL -->
<!-- ======================================================================= -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold font-heading" id="editUserModalLabel"><i class="bi bi-pencil-square text-primary me-2"></i>Edit User Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-user-form" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" id="edit_user_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="e_full_name" class="form-label fs-7 fw-semibold">Full Name</label>
                        <input type="text" class="form-control" id="e_full_name" name="full_name" required pattern="^[a-zA-Z\s]{3,100}$">
                        <div class="invalid-feedback">Full name is required (letters/spaces, min 3).</div>
                    </div>
                    <div class="mb-3">
                        <label for="e_username" class="form-label fs-7 fw-semibold">Username</label>
                        <input type="text" class="form-control" id="e_username" name="username" required pattern="^[a-zA-Z0-9_]{3,50}$">
                        <div class="invalid-feedback">Username is required (alphanumeric/underscore, min 3).</div>
                    </div>
                    <div class="mb-3">
                        <label for="e_email" class="form-label fs-7 fw-semibold">Email Address</label>
                        <input type="email" class="form-control" id="e_email" name="email" required>
                        <div class="invalid-feedback">Valid email address is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="e_phone" class="form-label fs-7 fw-semibold">Phone Number <span class="text-muted fw-normal">(Optional)</span></label>
                        <input type="tel" class="form-control" id="e_phone" name="phone" pattern="^\+?[0-9]{7,15}$">
                        <div class="invalid-feedback">Provide a valid phone number (7-15 digits).</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="e_role_id" class="form-label fs-7 fw-semibold">System Role</label>
                            <select class="form-select" id="e_role_id" name="role_id" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>"><?= e($role['role_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="e_status" class="form-label fs-7 fw-semibold">Status</label>
                            <select class="form-select" id="e_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="e_password" class="form-label fs-7 fw-semibold">Password <span class="text-muted fw-normal">(Leave blank to keep current)</span></label>
                        <input type="password" class="form-control strength-check" id="e_password" name="password">
                        <div class="invalid-feedback">Password must be min 8 characters and contain mixed cases, numbers, symbols.</div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Load Custom CRUD JS -->
<script src="<?= asset('js/crud.js') ?>"></script>

<?php include ROOT_PATH . 'views/layouts/footer.php'; ?>
