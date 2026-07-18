<?php
/**
 * Dynamic Breadcrumbs Partial
 */
$currentUrl = '/' . trim($_GET['url'] ?? '', '/');
?>
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>"><i class="bi bi-house-door-fill"></i> Home</a></li>
        
        <?php if ($currentUrl === '/dashboard'): ?>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        <?php elseif ($currentUrl === '/profile'): ?>
            <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile Settings</li>
        <?php elseif ($currentUrl === '/admin/users' || strpos($currentUrl, '/admin/users') === 0): ?>
            <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">User Management</li>
        <?php else: ?>
            <li class="breadcrumb-item active" aria-current="page">Page</li>
        <?php endif; ?>
    </ol>
</nav>
