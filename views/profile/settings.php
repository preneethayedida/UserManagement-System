<?php include ROOT_PATH . 'views/layouts/header.php'; ?>

<div class="app-container">
    <?php include ROOT_PATH . 'views/partials/sidebar.php'; ?>

    <div class="main-content">
        <?php include ROOT_PATH . 'views/partials/navbar.php'; ?>

        <div class="container-fluid p-4">
            <?php include ROOT_PATH . 'views/partials/breadcrumbs.php'; ?>

            <!-- Page Header -->
            <div class="page-title-row animate-fade-in">
                <div>
                    <h1 class="page-title">General Settings</h1>
                    <p class="page-subtitle">Configure application defaults, notifications, and security preferences.</p>
                </div>
                <div class="date-pill">
                    <i class="bi bi-clock-history"></i>
                    Last Activity: <?= date('H:i') ?>
                </div>
            </div>

            <!-- Settings Grid -->
            <div class="row g-4 animate-slide-up">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex align-items-center gap-2">
                            <i class="bi bi-sliders" style="color:var(--neon-cyan);"></i>
                            <h5 class="mb-0 fw-bold font-heading" style="color:var(--text-white);">System Preferences</h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="<?= url('/settings/save') ?>">
                                <?= csrf_field() ?>

                                <!-- Default Interface Theme -->
                                <div class="mb-4">
                                    <label class="auth-label">Default Interface Theme</label>
                                    <div class="row g-3 mt-1">
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded border d-flex align-items-center justify-content-between" style="background:rgba(255,255,255,0.02); border-color:var(--border);">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bi bi-moon-stars-fill" style="color:var(--neon-violet);"></i>
                                                    <span>Dark Space (Default)</span>
                                                </div>
                                                <input type="radio" name="default_theme" value="dark" checked style="accent-color:var(--neon-violet);">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded border d-flex align-items-center justify-content-between" style="background:rgba(255,255,255,0.02); border-color:var(--border);">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bi bi-sun-fill" style="color:var(--neon-amber);"></i>
                                                    <span>Classic Light</span>
                                                </div>
                                                <input type="radio" name="default_theme" value="light" style="accent-color:var(--neon-violet);">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="neon-divider">

                                <!-- Notification Switches -->
                                <div class="mb-4">
                                    <label class="auth-label">Notification Settings</label>
                                    <div class="d-flex flex-column gap-3 mt-2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h6 class="mb-0 fw-semibold" style="color:var(--text-white);">Email Notifications</h6>
                                                <small class="text-muted fs-8">Send emails for user creation, deletions, and password resets.</small>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" checked style="width:2.4em; height:1.2em; accent-color:var(--neon-cyan);">
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h6 class="mb-0 fw-semibold" style="color:var(--text-white);">Security Alerts</h6>
                                                <small class="text-muted fs-8">Get notified instantly of unauthorized login attempts.</small>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" checked style="width:2.4em; height:1.2em; accent-color:var(--neon-cyan);">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="neon-divider">

                                <!-- Security Toggles -->
                                <div class="mb-4">
                                    <label class="auth-label">Session Security</label>
                                    <div class="row g-3 mt-1">
                                        <div class="col-sm-6">
                                            <label class="auth-label" style="font-size:0.7rem;">Session Timeout</label>
                                            <select class="form-select" name="session_timeout">
                                                <option value="900">15 Minutes</option>
                                                <option value="1800" selected>30 Minutes (Recommended)</option>
                                                <option value="3600">1 Hour</option>
                                                <option value="86400">24 Hours</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="auth-label" style="font-size:0.7rem;">MFA / 2FA Verification</label>
                                            <select class="form-select" name="mfa_setting">
                                                <option value="disabled">Disabled</option>
                                                <option value="optional" selected>Optional for Users</option>
                                                <option value="required">Enforced for Admin Only</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2">
                                        <i class="bi bi-check-circle"></i>
                                        Save Preferences
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card p-4 text-center">
                        <h5 class="fw-bold font-heading mb-3" style="color:var(--text-white);">System Info</h5>
                        <div class="text-start d-flex flex-column gap-3 mt-2">
                            <div>
                                <span class="text-muted fs-8 d-block fw-semibold text-uppercase">Application Name</span>
                                <span class="fw-bold text-white fs-7"><?= e(APP_NAME) ?></span>
                            </div>
                            <div>
                                <span class="text-muted fs-8 d-block fw-semibold text-uppercase">Version</span>
                                <span class="fw-bold text-white fs-7">v<?= e(APP_VERSION) ?></span>
                            </div>
                            <div>
                                <span class="text-muted fs-8 d-block fw-semibold text-uppercase">Environment</span>
                                <span class="badge bg-secondary-subtle text-secondary border fs-9">Development</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ROOT_PATH . 'views/layouts/footer.php'; ?>
