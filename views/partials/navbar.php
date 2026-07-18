<?php
/**
 * Top Navbar Partial
 */
$currentUser = auth();
?>
<header class="app-navbar" id="app-navbar">
    <div class="navbar-left">
        <button class="navbar-toggle-btn" id="sidebar-toggle" aria-label="Toggle Sidebar">
            <i class="bi bi-list"></i>
        </button>
        <a class="navbar-brand" href="<?= url('/dashboard') ?>">
            <i class="bi bi-shield-check navbar-brand-icon"></i>
            <span><?= e(APP_NAME) ?></span>
        </a>
    </div>

    <div class="navbar-right">
        <!-- Theme Toggle -->
        <button class="navbar-icon-btn" id="theme-toggle" aria-label="Toggle Theme">
            <i class="bi bi-sun-fill" id="theme-icon-light"></i>
            <i class="bi bi-moon-stars-fill" id="theme-icon-dark" style="display:none;"></i>
        </button>

        <!-- User Dropdown -->
        <?php if ($currentUser): ?>
        <div class="navbar-user-dropdown" id="navbar-user-dropdown">
            <button class="navbar-user-btn" id="navbar-user-toggle">
                <img src="<?= get_avatar_url($currentUser['profile_image']) ?>"
                     alt="Avatar"
                     class="navbar-user-avatar"
                     id="navbar-avatar">
                <div class="navbar-user-info d-none d-md-block">
                    <span class="navbar-user-name"><?= e($currentUser['full_name']) ?></span>
                    <span class="navbar-user-role"><?= e($currentUser['role_name'] ?? ($currentUser['role_id'] == 1 ? 'Admin' : 'User')) ?></span>
                </div>
                <i class="bi bi-chevron-down navbar-chevron"></i>
            </button>

            <div class="navbar-dropdown-menu" id="navbar-dropdown-menu">
                <div class="navbar-dropdown-header">
                    <strong><?= e($currentUser['username']) ?></strong>
                    <span><?= e($currentUser['email']) ?></span>
                </div>
                <hr class="navbar-dropdown-divider">
                <a href="<?= url('/profile') ?>" class="navbar-dropdown-item">
                    <i class="bi bi-person"></i> Profile Settings
                </a>
                <a href="<?= url('/dashboard') ?>" class="navbar-dropdown-item">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <hr class="navbar-dropdown-divider">
                <a href="<?= url('/logout') ?>" class="navbar-dropdown-item navbar-dropdown-danger">
                    <i class="bi bi-box-arrow-right"></i> Sign Out
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</header>
