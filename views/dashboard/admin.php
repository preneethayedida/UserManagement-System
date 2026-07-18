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

            <!-- Dashboard Title -->
            <div class="page-title-row animate-fade-in">
                <div>
                    <h1 class="page-title">Admin Dashboard</h1>
                    <p class="page-subtitle">Real-time user metrics, analytics charts, and management actions.</p>
                </div>
                <div class="date-pill">
                    <i class="bi bi-calendar3"></i><?= date('F d, Y') ?>
                </div>
            </div>

            <!-- Neon Stat Cards Grid -->
            <div class="dashboard-stats-grid mb-4 animate-slide-up">
                <div class="stat-card">
                    <div class="stat-card-icon violet"><i class="bi bi-people-fill"></i></div>
                    <div class="stat-card-body">
                        <span class="stat-card-label">Total Users</span>
                        <span class="stat-card-value"><?= $stats['total_users'] ?></span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon pink"><i class="bi bi-shield-lock-fill"></i></div>
                    <div class="stat-card-body">
                        <span class="stat-card-label">Administrators</span>
                        <span class="stat-card-value"><?= $stats['admins'] ?></span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon cyan"><i class="bi bi-person-fill"></i></div>
                    <div class="stat-card-body">
                        <span class="stat-card-label">Regular Users</span>
                        <span class="stat-card-value"><?= $stats['users'] ?></span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon amber"><i class="bi bi-check-circle-fill"></i></div>
                    <div class="stat-card-body">
                        <span class="stat-card-label">Active Accounts</span>
                        <span class="stat-card-value"><?= $stats['active'] ?></span>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row g-4 mb-4 animate-slide-up" style="animation-delay: 0.1s;">
                <!-- User Growth Line Chart -->
                <div class="col-lg-8">
                    <div class="card border shadow-sm h-100">
                        <div class="card-header bg-transparent border-bottom p-3">
                            <h5 class="mb-0 fw-bold font-heading"><i class="bi bi-graph-up text-primary me-2"></i>User Registration Trend</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="chart-container">
                                <canvas id="userGrowthChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Status Doughnut Chart -->
                <div class="col-lg-4">
                    <div class="card border shadow-sm h-100">
                        <div class="card-header bg-transparent border-bottom p-3">
                            <h5 class="mb-0 fw-bold font-heading"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Status Distribution</h5>
                        </div>
                        <div class="card-body p-3 d-flex align-items-center justify-content-center">
                            <div class="chart-container" style="max-height: 240px;">
                                <canvas id="userStatusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="row g-4 mb-4 animate-slide-up" style="animation-delay: 0.2s;">
                <!-- Profile Gauge & Quick Actions -->
                <div class="col-lg-4">
                    <div class="row g-4">
                        <!-- Profile Gauge -->
                        <div class="col-12">
                            <div class="card p-4 border shadow-sm text-center">
                                <h5 class="fw-bold font-heading mb-3">Your Profile Completion</h5>
                                <div class="gauge-container mb-3">
                                    <svg class="gauge-circle" width="120" height="120">
                                        <circle class="gauge-circle-bg" cx="60" cy="60" r="50" />
                                        <circle class="gauge-circle-val" cx="60" cy="60" r="50" style="stroke-dasharray: 314; stroke-dashoffset: <?= 314 - (314 * $profileCompletion / 100) ?>;" />
                                    </svg>
                                    <div class="gauge-text"><?= $profileCompletion ?>%</div>
                                </div>
                                <p class="text-muted fs-7 mb-0">
                                    <?= $profileCompletion < 100 ? 'Fill in all fields (avatar & phone) to reach 100%!' : 'Awesome! Your profile is complete.' ?>
                                </p>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="col-12">
                            <div class="card p-4 border shadow-sm">
                                <h5 class="fw-bold font-heading mb-3">Quick Actions</h5>
                                <div class="quick-actions-grid">
                                    <a href="<?= url('/admin/users') ?>" class="btn btn-primary py-2.5 w-100">
                                        <i class="bi bi-people-fill"></i> <span>Manage All Users</span>
                                    </a>
                                    <a href="<?= url('/profile') ?>" class="btn btn-outline-secondary py-2.5 w-100">
                                        <i class="bi bi-person-gear"></i> <span>Edit My Profile</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Analytics split panel -->
                <div class="col-lg-8">
                    <div class="row g-4 h-100">
                        <!-- Recent Registrations -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-header bg-transparent border-bottom p-3 d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 fw-bold font-heading" style="font-size:0.95rem; color:var(--text-white);"><i class="bi bi-clock-history me-2 text-primary"></i>Registrations</h5>
                                    <a href="<?= url('/admin/users') ?>" class="text-decoration-none fw-semibold fs-8" style="color:var(--neon-cyan);">View All</a>
                                </div>
                                <div class="card-body p-0">
                                    <?php if (empty($stats['recent_users'])): ?>
                                        <div class="p-4 text-center text-muted">No recent registrations found.</div>
                                    <?php else: ?>
                                        <div class="table-responsive border-0 rounded-0">
                                            <table class="table align-middle mb-0 table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="px-3" style="font-size:0.65rem !important;">User</th>
                                                        <th style="font-size:0.65rem !important;">Role</th>
                                                        <th class="px-3 text-end" style="font-size:0.65rem !important;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($stats['recent_users'] as $user): ?>
                                                        <tr>
                                                            <td class="px-3">
                                                                <div class="d-flex align-items-center">
                                                                    <img src="<?= get_avatar_url($user['profile_image']) ?>" alt="Avatar" width="28" height="28" class="rounded-circle border me-2 object-fit-cover" style="border-color:var(--border-bright) !important;">
                                                                    <div>
                                                                        <div class="fw-semibold text-white" style="font-size:0.78rem; line-height:1.2;"><?= e($user['full_name']) ?></div>
                                                                        <small class="text-muted fs-9">@<?= e($user['username']) ?></small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-secondary-subtle text-secondary border fs-9"><?= e($user['role_name']) ?></span>
                                                            </td>
                                                            <td class="px-3 text-end">
                                                                <span class="badge-status <?= e($user['status']) ?>" style="font-size:0.62rem; padding:0.25em 0.6em;"><?= ucfirst(e($user['status'])) ?></span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Recent System Activities -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-header bg-transparent border-bottom p-3">
                                    <h5 class="mb-0 fw-bold font-heading" style="font-size:0.95rem; color:var(--text-white);"><i class="bi bi-activity me-2" style="color:var(--neon-cyan);"></i>System Activity Log</h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="d-flex gap-3 align-items-start">
                                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background:rgba(191,95,255,0.08); color:var(--neon-violet); width:32px; height:32px;">
                                                <i class="bi bi-person-plus-fill"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold text-white fs-8">New user registration auto-seeded</p>
                                                <small class="text-muted fs-9"><i class="bi bi-clock me-1"></i>Just now</small>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-3 align-items-start">
                                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background:rgba(6,255,165,0.08); color:var(--neon-cyan); width:32px; height:32px;">
                                                <i class="bi bi-shield-lock-fill"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold text-white fs-8">Admin session token regenerated</p>
                                                <small class="text-muted fs-9"><i class="bi bi-clock me-1"></i>3 mins ago</small>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-3 align-items-start">
                                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background:rgba(255,45,120,0.08); color:var(--neon-pink); width:32px; height:32px;">
                                                <i class="bi bi-trash3-fill"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold text-white fs-8">Wiped session cache tables</p>
                                                <small class="text-muted fs-9"><i class="bi bi-clock me-1"></i>1 hour ago</small>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-3 align-items-start">
                                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background:rgba(255,214,10,0.08); color:var(--neon-amber); width:32px; height:32px;">
                                                <i class="bi bi-database-fill-check"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold text-white fs-8">Clean database reinstallation</p>
                                                <small class="text-muted fs-9"><i class="bi bi-clock me-1"></i>1 hour ago</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Prepare Chart.js arrays from PHP
$chartLabels = [];
$chartData = [];
if (!empty($regTrend)) {
    foreach ($regTrend as $trend) {
        $chartLabels[] = date('M d', strtotime($trend['reg_date']));
        $chartData[] = (int)$trend['user_count'];
    }
} else {
    // Mock default if empty
    $chartLabels = [date('M d')];
    $chartData = [1];
}
?>

<!-- Initialize SaaS charts -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Line Growth Chart
    const ctxLine = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                label: 'New Registrations',
                data: <?= json_encode($chartData) ?>,
                borderColor: '#BF5FFF',
                backgroundColor: 'rgba(191, 95, 255, 0.08)',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointBackgroundColor: '#06FFA5',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: 'rgba(240, 230, 255, 0.4)'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.04)'
                    }
                },
                x: {
                    ticks: {
                        color: 'rgba(240, 230, 255, 0.4)'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // 2. Status Doughnut Chart
    const ctxDoughnut = document.getElementById('userStatusChart').getContext('2d');
    new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive', 'Suspended'],
            datasets: [{
                data: [
                    <?= (int)$stats['active'] ?>,
                    <?= (int)$stats['inactive'] ?>,
                    <?= (int)$stats['suspended'] ?>
                ],
                backgroundColor: ['#06FFA5', '#9333EA', '#FF2D78'],
                borderWidth: 2,
                borderColor: '#130E28',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'rgba(240, 230, 255, 0.6)',
                        font: { family: 'var(--font-sans)', size: 11 }
                    }
                }
            },
            cutout: '70%'
        }
    });
});
</script>

<?php include ROOT_PATH . 'views/layouts/footer.php'; ?>
