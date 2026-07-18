<?php include ROOT_PATH . 'views/layouts/header.php'; ?>

<style>
/* Profile Cover Banner and overlap overrides */
.profile-card-header {
    position: relative;
    overflow: hidden;
}

.profile-cover {
    height: 110px;
    background: linear-gradient(135deg, var(--primary-color) 0%, #4F46E5 100%);
    position: relative;
}

.profile-avatar-container {
    margin-top: -50px;
    position: relative;
    z-index: 5;
}

.profile-avatar-img {
    border: 4px solid var(--bg-white);
    background-color: var(--bg-white);
}

.tab-card {
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-md);
    background-color: var(--bg-white);
}
</style>

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
            <div class="mb-4 animate-fade-in">
                <h2 class="fw-bold font-heading mb-1 text-primary">Profile Settings</h2>
                <p class="text-muted mb-0">Update account credentials, passwords, and manage your visual profile avatar.</p>
            </div>

            <div class="row g-4 animate-slide-up">
                <!-- Left Details Banner / Actions Column -->
                <div class="col-lg-4">
                    <!-- Profile Header banner card -->
                    <div class="card border shadow-sm overflow-hidden text-center mb-4">
                        <div class="profile-cover"></div>
                        <div class="profile-avatar-container pb-4 px-3">
                            <div class="position-relative d-inline-block mx-auto mb-3" style="width: 100px; height: 100px;">
                                <img src="<?= get_avatar_url($profile['profile_image']) ?>" alt="Avatar" 
                                     class="rounded-circle shadow object-fit-cover w-100 h-100 profile-avatar-img" id="profile-avatar-preview">
                                
                                <!-- Loading Spinner Overlay -->
                                <div class="spinner-overlay rounded-circle" id="avatar-spinner">
                                    <div class="spinner-border text-primary" style="width: 1.5rem; height: 1.5rem;" role="status">
                                        <span class="visually-hidden">Uploading...</span>
                                    </div>
                                </div>
                            </div>
                            
                            <h5 class="fw-bold font-heading mb-1"><?= e($profile['full_name']) ?></h5>
                            <p class="text-muted fs-8 mb-3">@<?= e($profile['username']) ?></p>

                            <form id="avatar-form" enctype="multipart/form-data">
                                <input type="file" id="avatar-input" name="profile_image" accept=".jpg,.jpeg,.png,.webp" class="d-none">
                                <button type="button" class="btn btn-outline-primary btn-sm px-3 fw-bold" onclick="document.getElementById('avatar-input').click()">
                                    <i class="bi bi-camera me-1"></i> Update Avatar
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Details Stats Card -->
                    <div class="card border shadow-sm p-4 text-center">
                        <h5 class="fw-bold font-heading mb-2">Account Status</h5>
                        <div>
                            <span class="badge-status active"><i class="bi bi-patch-check-fill me-1"></i>Active Account</span>
                        </div>
                        <hr class="my-3 border-secondary-subtle">
                        <div class="text-start">
                            <div class="fs-8 text-muted fw-semibold mb-1">SYSTEM ROLE</div>
                            <div class="fw-bold fs-7 mb-3 text-primary"><i class="bi bi-person-badge me-1"></i><?= e($profile['role_name']) ?></div>
                            <div class="fs-8 text-muted fw-semibold mb-1">MEMBER JOINED</div>
                            <div class="fw-bold fs-7"><i class="bi bi-calendar2-check me-1"></i><?= date('Y-m-d H:i', strtotime($profile['created_at'])) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Right Settings Forms Column -->
                <div class="col-lg-8">
                    <!-- Personal Info Card -->
                    <div class="tab-card border shadow-sm mb-4">
                        <div class="card-header bg-transparent border-bottom p-3">
                            <h5 class="mb-0 fw-bold font-heading"><i class="bi bi-person-fill text-primary me-2"></i>Personal Details</h5>
                        </div>
                        <div class="card-body p-4">
                            <form id="profile-details-form" class="needs-validation" novalidate>
                                <?= csrf_field() ?>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                                   value="<?= e($profile['full_name']) ?>" required pattern="^[a-zA-Z\s]{3,100}$">
                                            <label for="full_name">Full Name</label>
                                            <div class="invalid-feedback">Enter a valid full name (letters only, min 3).</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="username" name="username" 
                                                   value="<?= e($profile['username']) ?>" required pattern="^[a-zA-Z0-9_]{3,50}$">
                                            <label for="username">Username</label>
                                            <div class="invalid-feedback">Enter alphanumeric characters or underscores (3-50).</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="<?= e($profile['email']) ?>" required>
                                            <label for="email">Email Address</label>
                                            <div class="invalid-feedback">Please enter a valid email address.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="tel" class="form-control" id="phone" name="phone" 
                                                   value="<?= e($profile['phone']) ?>" pattern="^\+?[0-9]{7,15}$">
                                            <label for="phone">Phone Number (Optional)</label>
                                            <div class="invalid-feedback">Please enter a valid phone number (7-15 digits).</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold" id="details-submit-btn">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Password Update Card -->
                    <div class="tab-card border shadow-sm">
                        <div class="card-header bg-transparent border-bottom p-3">
                            <h5 class="mb-0 fw-bold font-heading"><i class="bi bi-shield-lock-fill text-primary me-2"></i>Security Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <form id="password-change-form" class="needs-validation" novalidate>
                                <?= csrf_field() ?>
                                <div class="mb-3 form-floating">
                                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Current Password" required>
                                    <label for="current_password">Current Password</label>
                                    <div class="invalid-feedback">Enter your current password.</div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="password" class="form-control strength-check" id="new_password" name="new_password" placeholder="New Password" required>
                                            <label for="new_password">New Password</label>
                                            <div class="invalid-feedback">Create a new password.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                                            <label for="confirm_password">Confirm New Password</label>
                                            <div class="invalid-feedback" id="pw-confirm-feedback">Re-type the new password.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. AJAX Profile details update
    const detailsForm = document.getElementById('profile-details-form');
    if (detailsForm) {
        detailsForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!checkFormValidity(detailsForm)) return;

            const submitBtn = document.getElementById('details-submit-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

            const formData = new FormData(detailsForm);
            
            const response = await ajaxRequest('<?= url('/profile/update') ?>', 'POST', formData);

            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Save Changes';

            if (response.ok && response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    html: response.message,
                    confirmButtonColor: 'var(--bs-primary)'
                });
            }
        });
    }

    // 2. AJAX Password change update
    const passwordForm = document.getElementById('password-change-form');
    const newPass = document.getElementById('new_password');
    const confirmPass = document.getElementById('confirm_password');
    const confirmFb = document.getElementById('pw-confirm-feedback');

    function checkPasswordsMatch() {
        if (newPass.value !== confirmPass.value) {
            confirmPass.setCustomValidity("Passwords don't match");
            confirmFb.textContent = "Passwords do not match.";
        } else {
            confirmPass.setCustomValidity("");
        }
    }
    newPass.addEventListener('change', checkPasswordsMatch);
    confirmPass.addEventListener('keyup', checkPasswordsMatch);

    if (passwordForm) {
        passwordForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            checkPasswordsMatch();
            if (!checkFormValidity(passwordForm)) return;

            const submitBtn = passwordForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';

            const formData = new FormData(passwordForm);
            const response = await ajaxRequest('<?= url('/profile/password') ?>', 'POST', formData);

            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Update Password';

            if (response.ok && response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Password Updated',
                    text: response.message,
                    confirmButtonColor: 'var(--bs-primary)'
                });
                passwordForm.reset();
                passwordForm.classList.remove('was-validated');
                const strengthLabel = document.getElementById('new_password-strength');
                if (strengthLabel) strengthLabel.textContent = '';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    html: response.message,
                    confirmButtonColor: 'var(--bs-primary)'
                });
            }
        });
    }

    // 3. AJAX Avatar upload update
    const avatarInput = document.getElementById('avatar-input');
    const avatarSpinner = document.getElementById('avatar-spinner');
    const previewImg = document.getElementById('profile-avatar-preview');
    const navbarAvatar = document.getElementById('navbar-avatar');
    const sidebarAvatar = document.querySelector('.sidebar-avatar');

    if (avatarInput) {
        avatarInput.addEventListener('change', async () => {
            const file = avatarInput.files[0];
            if (!file) return;

            // Client-side sizes & types checks
            const maxSize = 2 * 1024 * 1024; // 2MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
            
            if (file.size > maxSize) {
                Swal.fire('File Too Large', 'Maximum file size allowed is 2MB.', 'error');
                avatarInput.value = '';
                return;
            }
            if (!allowedTypes.includes(file.type)) {
                Swal.fire('Invalid File', 'Only JPG, JPEG, PNG, and WEBP formats are allowed.', 'error');
                avatarInput.value = '';
                return;
            }

            // Start spinner
            avatarSpinner.classList.add('show');

            const formData = new FormData();
            formData.append('profile_image', file);
            formData.append('csrf_token', '<?= csrf_token() ?>');

            const response = await ajaxRequest('<?= url('/profile/avatar') ?>', 'POST', formData);
            
            avatarSpinner.classList.remove('show');
            avatarInput.value = ''; // Reset input

            if (response.ok && response.success) {
                // Update previews
                previewImg.src = response.avatar_url;
                if (navbarAvatar) navbarAvatar.src = response.avatar_url;
                if (sidebarAvatar) sidebarAvatar.src = response.avatar_url;

                Swal.fire({
                    icon: 'success',
                    title: 'Avatar Uploaded',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: response.message,
                    confirmButtonColor: 'var(--bs-primary)'
                });
            }
        });
    }
});
</script>

<?php include ROOT_PATH . 'views/layouts/footer.php'; ?>
