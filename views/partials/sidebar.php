<?php
/**
 * Sidebar Navigation Partial
 */
$currentUser = auth();
$currentUrl = '/' . trim($_GET['url'] ?? '', '/');
?>
<aside class="app-sidebar" id="app-sidebar">
    <!-- Brand / User Header -->
    <div class="sidebar-header">
        <div class="sidebar-user-info">
            <img src="<?= get_avatar_url($currentUser['profile_image']) ?>"
                 alt="Avatar"
                 class="sidebar-avatar sidebar-avatar-img"
                 id="sidebar-user-avatar">
            <div class="sidebar-user-text">
                <span class="sidebar-username"><?= e($currentUser['full_name']) ?></span>
                <span class="sidebar-email"><?= e($currentUser['email']) ?></span>
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="sidebar-nav">
        <ul class="sidebar-menu">
            <li class="sidebar-section-label">General</li>
            <li class="sidebar-menu-item">
                <a href="<?= url('/dashboard') ?>"
                   class="sidebar-link <?= $currentUrl === '/dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="<?= url('/profile') ?>"
                   class="sidebar-link <?= $currentUrl === '/profile' ? 'active' : '' ?>">
                    <i class="bi bi-person-circle"></i>
                    <span>Profile Settings</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="<?= url('/settings') ?>"
                   class="sidebar-link <?= $currentUrl === '/settings' ? 'active' : '' ?>">
                    <i class="bi bi-sliders"></i>
                    <span>System Settings</span>
                </a>
            </li>

            <?php if (is_admin()): ?>
                <li class="sidebar-section-label">Administration</li>
                <li class="sidebar-menu-item">
                    <a href="<?= url('/admin/users') ?>"
                       class="sidebar-link <?= ($currentUrl === '/admin/users' || strpos($currentUrl, '/admin/users/') === 0) ? 'active' : '' ?>">
                        <i class="bi bi-people-fill"></i>
                        <span>User Management</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="<?= url('/admin/roles') ?>"
                       class="sidebar-link <?= $currentUrl === '/admin/roles' ? 'active' : '' ?>">
                        <i class="bi bi-shield-lock-fill"></i>
                        <span>Roles &amp; Permissions</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Sign Out Footer -->
    <div class="sidebar-footer">
        <a href="<?= url('/logout') ?>" class="sidebar-signout">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sign Out</span>
        </a>
    </div>
</aside>
