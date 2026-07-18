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
                    <h1 class="page-title">Dashboard</h1>
                    <p class="page-subtitle">Your personal account overview panel.</p>
                </div>
                <div class="date-pill">
                    <i class="bi bi-calendar3"></i>
                    <?= date('F d, Y') ?>
                </div>
            </div>

            <!-- Welcome Banner -->
            <div class="welcome-banner mb-4 animate-slide-up">
                <div class="d-flex align-items-center gap-3">
                    <div style="font-size:2rem; filter:drop-shadow(0 0 10px var(--glow-cyan));">
                        <i class="bi bi-stars" style="color:var(--neon-cyan);"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold font-heading mb-1" style="color:var(--text-white);">
                            Hello, <?= e($profile['full_name']) ?>!
                        </h4>
                        <p class="mb-0 fs-7" style="color:var(--text-muted);">
                            Manage your account information, update your profile image, and review your security credentials.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Grid -->
            <div class="row g-4 animate-slide-up animate-delay-1">
                <!-- Account Summary -->
                <div class="col-lg-8">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center gap-2">
                            <i class="bi bi-person-badge-fill" style="color:var(--neon-cyan); filter:drop-shadow(0 0 6px var(--glow-cyan));"></i>
                            <h5 class="mb-0 fw-bold font-heading" style="color:var(--text-white);">Account Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <p class="fs-8 mb-1 fw-semibold text-uppercase" style="color:var(--neon-violet); letter-spacing:0.7px;">Full Name</p>
                                    <p class="fw-semibold mb-0" style="color:var(--text-white);"><?= e($profile['full_name']) ?></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="fs-8 mb-1 fw-semibold text-uppercase" style="color:var(--neon-violet); letter-spacing:0.7px;">Username</p>
                                    <p class="fw-semibold mb-0" style="color:var(--text-white);">@<?= e($profile['username']) ?></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="fs-8 mb-1 fw-semibold text-uppercase" style="color:var(--neon-violet); letter-spacing:0.7px;">Email Address</p>
                                    <p class="fw-semibold mb-0" style="color:var(--text-white);"><?= e($profile['email']) ?></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="fs-8 mb-1 fw-semibold text-uppercase" style="color:var(--neon-violet); letter-spacing:0.7px;">Phone Number</p>
                                    <p class="fw-semibold mb-0" style="color:var(--text-white);">
                                        <?= $profile['phone'] ? e($profile['phone']) : '<span style="color:var(--text-muted);font-size:0.8rem;">Not Provided</span>' ?>
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="fs-8 mb-1 fw-semibold text-uppercase" style="color:var(--neon-violet); letter-spacing:0.7px;">Account Status</p>
                                    <span class="badge-status active">
                                        <i class="bi bi-patch-check-fill"></i>
                                        <?= ucfirst(e($profile['status'])) ?>
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <p class="fs-8 mb-1 fw-semibold text-uppercase" style="color:var(--neon-violet); letter-spacing:0.7px;">Member Since</p>
                                    <p class="fw-semibold mb-0 fs-7" style="color:var(--text-muted);"><?= date('F d, Y H:i', strtotime($profile['created_at'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Completion Gauge -->
                <div class="col-lg-4">
                    <div class="card h-100 text-center">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center gap-3" style="padding:2rem !important;">
                            <h5 class="fw-bold font-heading mb-0" style="color:var(--text-white);">Profile Completion</h5>

                            <div class="gauge-container">
                                <svg class="gauge-circle" width="120" height="120">
                                    <defs>
                                        <linearGradient id="gaugeGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                            <stop offset="0%"   stop-color="var(--neon-violet)" />
                                            <stop offset="100%" stop-color="var(--neon-cyan)" />
                                        </linearGradient>
                                    </defs>
                                    <circle class="gauge-circle-bg" cx="60" cy="60" r="50" />
                                    <circle class="gauge-circle-val" cx="60" cy="60" r="50"
                                        style="stroke:url(#gaugeGradient); stroke-dasharray:314; stroke-dashoffset:<?= 314 - (314 * $profileCompletion / 100) ?>;" />
                                </svg>
                                <div class="gauge-text neon-text"><?= $profileCompletion ?>%</div>
                            </div>

                            <p class="fs-7 mb-0 px-2" style="color:var(--text-muted);">
                                <?= $profileCompletion < 100
                                    ? 'Add phone number &amp; profile photo to reach 100%.'
                                    : 'Your profile is fully complete! Great job.' ?>
                            </p>

                            <a href="<?= url('/profile') ?>" class="btn btn-primary w-100">
                                <i class="bi bi-pencil-square"></i>
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include ROOT_PATH . 'views/layouts/footer.php'; ?>
