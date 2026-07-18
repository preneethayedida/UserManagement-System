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
                    <h1 class="page-title">Roles &amp; Permissions</h1>
                    <p class="page-subtitle">Configure system access levels and RBAC role definitions.</p>
                </div>
                <div class="date-pill">
                    <i class="bi bi-shield-fill-check"></i>
                    Role Enforcement: Active
                </div>
            </div>

            <!-- Roles Grid -->
            <div class="row g-4 animate-slide-up">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center gap-2">
                            <i class="bi bi-person-badge-fill" style="color:var(--neon-violet);"></i>
                            <h5 class="mb-0 fw-bold font-heading" style="color:var(--text-white);">Access Levels Overview</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive border-0 rounded-0">
                                <table class="table align-middle mb-0 table-hover">
                                    <thead>
                                        <tr>
                                            <th class="px-4">Role ID</th>
                                            <th>Role Name</th>
                                            <th>Permissions / Capabilities</th>
                                            <th>Access Level Badge</th>
                                            <th class="px-4 text-end">Date Initialized</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($rolesList)): ?>
                                            <tr>
                                                <td colspan="5" class="p-4 text-center text-muted">No roles found.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($rolesList as $role): ?>
                                                <tr>
                                                    <td class="px-4 fw-bold" style="color:var(--neon-cyan);"><?= e($role['id']) ?></td>
                                                    <td>
                                                        <span class="fw-semibold text-white"><?= e($role['role_name']) ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if ((int)$role['id'] === 1): ?>
                                                            <div class="d-flex flex-wrap gap-1">
                                                                <span class="badge bg-secondary-subtle text-secondary border fs-9">FULL_ACCESS</span>
                                                                <span class="badge bg-secondary-subtle text-secondary border fs-9">MANAGE_USERS</span>
                                                                <span class="badge bg-secondary-subtle text-secondary border fs-9">MANAGE_ROLES</span>
                                                                <span class="badge bg-secondary-subtle text-secondary border fs-9">VIEW_ANALYTICS</span>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="d-flex flex-wrap gap-1">
                                                                <span class="badge bg-secondary-subtle text-secondary border fs-9">READ_PROFILE</span>
                                                                <span class="badge bg-secondary-subtle text-secondary border fs-9">UPDATE_PROFILE</span>
                                                                <span class="badge bg-secondary-subtle text-secondary border fs-9">VIEW_OWN_DASHBOARD</span>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ((int)$role['id'] === 1): ?>
                                                            <span class="badge-status active">
                                                                <i class="bi bi-shield-fill-check"></i> Super Administrator
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge-status active" style="color:var(--neon-cyan); border-color:var(--border-cyan); background:rgba(6,255,165,0.04);">
                                                                <i class="bi bi-person-fill"></i> Regular User
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-4 text-end text-muted fs-8">
                                                        <?= date('Y-m-d H:i', strtotime($role['created_at'])) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ROOT_PATH . 'views/layouts/footer.php'; ?>
