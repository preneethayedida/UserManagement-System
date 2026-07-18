<?php include ROOT_PATH . 'views/layouts/header.php'; ?>

<style>
/* ════════════════════════════════════════════════════════════
   NEON LOGIN PAGE — Full Premium Rebuild
   ════════════════════════════════════════════════════════════ */

/* Remove default app-container flex so login is standalone */
.app-container { display: block !important; }

.auth-shell {
    min-height: 100vh;
    display: flex;
    background: var(--bg-cosmos);
    background-image:
        radial-gradient(ellipse at 15% 15%, rgba(191,95,255,0.18) 0%, transparent 50%),
        radial-gradient(ellipse at 85% 85%, rgba(6,255,165,0.1)  0%, transparent 50%),
        radial-gradient(ellipse at 50% 100%, rgba(255,45,120,0.06) 0%, transparent 40%);
}

/* ── Left Neon Brand Panel ───────────────────────────────── */
.auth-brand {
    flex: 0 0 46%;
    max-width: 46%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 3rem 3.5rem;
    position: relative;
    overflow: hidden;
}

/* Decorative orbiting rings */
.auth-brand-ring {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(191,95,255,0.1);
    pointer-events: none;
}
.auth-brand-ring.r1 { width: 500px; height: 500px; top: -160px; left: -160px; }
.auth-brand-ring.r2 { width: 350px; height: 350px; top: -80px;  left: -80px;  border-color: rgba(6,255,165,0.07); }
.auth-brand-ring.r3 { width: 600px; height: 600px; bottom: -250px; right: -250px; border-color: rgba(191,95,255,0.06); }

/* Glowing orb blobs */
.auth-orb {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    filter: blur(60px);
}
.auth-orb.o1 { width: 280px; height: 280px; background: rgba(191,95,255,0.14); top: -50px; left: -50px; }
.auth-orb.o2 { width: 200px; height: 200px; background: rgba(6,255,165,0.08);   bottom: 60px; right: 30px; }

/* Brand logo */
.brand-logo {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 0.95rem;
    background: linear-gradient(135deg, var(--neon-violet), var(--neon-cyan));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    position: relative;
    z-index: 2;
}
.brand-logo i {
    font-size: 1.5rem;
    color: var(--neon-violet);
    -webkit-text-fill-color: var(--neon-violet);
    filter: drop-shadow(0 0 10px rgba(191,95,255,0.6));
}

/* Headline */
.brand-body { position: relative; z-index: 2; }

.brand-headline {
    font-family: var(--font-heading);
    font-size: 2.6rem;
    font-weight: 800;
    line-height: 1.15;
    color: var(--text-white);
    margin-bottom: 1.1rem;
}

.brand-headline .grad-text {
    background: linear-gradient(135deg, var(--neon-violet) 0%, var(--neon-cyan) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.brand-subtitle {
    font-size: 0.88rem;
    color: var(--text-muted);
    line-height: 1.75;
    max-width: 340px;
    margin-bottom: 2.2rem;
}

/* Feature list */
.feature-list { display: flex; flex-direction: column; gap: 1rem; }

.feature-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--text-body);
}

.feature-dot {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
    border: 1px solid;
}

.feature-dot.violet {
    background: rgba(191,95,255,0.1);
    border-color: rgba(191,95,255,0.25);
    color: var(--neon-violet);
    box-shadow: 0 0 14px rgba(191,95,255,0.15);
}
.feature-dot.cyan {
    background: rgba(6,255,165,0.08);
    border-color: rgba(6,255,165,0.2);
    color: var(--neon-cyan);
    box-shadow: 0 0 14px rgba(6,255,165,0.12);
}
.feature-dot.pink {
    background: rgba(255,45,120,0.08);
    border-color: rgba(255,45,120,0.2);
    color: var(--neon-pink);
    box-shadow: 0 0 14px rgba(255,45,120,0.12);
}

/* Stats row */
.brand-stats {
    display: flex;
    gap: 2rem;
    margin-top: 2.5rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(191,95,255,0.1);
    position: relative;
    z-index: 2;
}

.brand-stat-val {
    font-family: var(--font-heading);
    font-size: 1.6rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--neon-violet), var(--neon-cyan));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: block;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.brand-stat-label {
    font-size: 0.72rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.6px;
    font-weight: 600;
}

/* Footer */
.brand-footer {
    font-size: 0.73rem;
    color: var(--text-muted);
    position: relative;
    z-index: 2;
}

/* ── Right Form Panel ────────────────────────────────────── */
.auth-form-panel {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    position: relative;
    background: linear-gradient(160deg, rgba(19,14,40,0.6) 0%, rgba(7,5,18,0.8) 100%);
    backdrop-filter: blur(4px);
}

/* Subtle grid pattern */
.auth-form-panel::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(191,95,255,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(191,95,255,0.04) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
}

/* Auth Card */
.auth-card {
    width: 100%;
    max-width: 420px;
    background: rgba(19,14,40,0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(191,95,255,0.25);
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow:
        0 0 0 1px rgba(191,95,255,0.06) inset,
        0 30px 60px rgba(0,0,0,0.5),
        0 0 40px rgba(191,95,255,0.06);
    position: relative;
    z-index: 2;
}

/* Top glow line */
.auth-card::before {
    content: '';
    position: absolute;
    top: 0; left: 10%; right: 10%;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--neon-violet), var(--neon-cyan), transparent);
    border-radius: 0 0 999px 999px;
}

.auth-card-title {
    font-family: var(--font-heading);
    font-size: 1.55rem;
    font-weight: 700;
    color: var(--text-white);
    margin-bottom: 0.3rem;
}

.auth-card-sub {
    font-size: 0.83rem;
    color: var(--text-muted);
    margin-bottom: 1.8rem;
}

/* Input overrides */
.auth-input-wrap {
    position: relative;
    margin-bottom: 1rem;
}

.auth-input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 0.95rem;
    z-index: 5;
    pointer-events: none;
}

.auth-input {
    width: 100%;
    padding: 0.82rem 1rem 0.82rem 2.6rem;
    background: rgba(255,255,255,0.04) !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    border-radius: 10px !important;
    color: var(--text-white) !important;
    font-size: 0.88rem !important;
    transition: var(--transition) !important;
    font-family: var(--font-sans) !important;
}

.auth-input::placeholder { color: var(--text-muted) !important; }

.auth-input:focus {
    background: rgba(191,95,255,0.06) !important;
    border-color: var(--neon-violet) !important;
    box-shadow: 0 0 0 3px rgba(191,95,255,0.12), 0 0 16px rgba(191,95,255,0.08) !important;
    outline: none !important;
    color: var(--text-white) !important;
}

.auth-label {
    display: block;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 0.4rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.pw-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    font-size: 1rem;
    padding: 0;
    z-index: 5;
    transition: color 0.2s;
}

.pw-toggle:hover { color: var(--neon-violet); }

/* Checkbox */
.auth-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.82rem;
    color: var(--text-muted);
}

.auth-check input[type="checkbox"] {
    width: 16px; height: 16px;
    accent-color: var(--neon-violet);
    cursor: pointer;
}

/* Sign In button */
.btn-signin {
    width: 100%;
    padding: 0.85rem 1rem;
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, var(--neon-violet) 0%, #7B3FE4 100%);
    color: #fff;
    font-family: var(--font-heading);
    font-size: 0.92rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    box-shadow: 0 4px 20px rgba(191,95,255,0.3);
    position: relative;
    overflow: hidden;
}

.btn-signin::after {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 60%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
    transition: left 0.55s var(--ease);
}

.btn-signin:hover::after { left: 160%; }

.btn-signin:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 30px rgba(191,95,255,0.45), 0 6px 24px rgba(0,0,0,0.3);
}

/* Alert */
.auth-alert {
    padding: 0.85rem 1rem;
    border-radius: 10px;
    font-size: 0.83rem;
    background: rgba(255,45,120,0.08);
    border: 1px solid rgba(255,45,120,0.25);
    color: var(--neon-pink);
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

@media (max-width: 767.98px) {
    .auth-brand { display: none !important; }
    .auth-form-panel { padding: 1.5rem; }
}
</style>

<div class="auth-shell">
    <!-- ── Brand Panel ──────────────────────────────────── -->
    <div class="auth-brand">
        <!-- Decorative rings -->
        <div class="auth-brand-ring r1"></div>
        <div class="auth-brand-ring r2"></div>
        <div class="auth-brand-ring r3"></div>
        <!-- Glow orbs -->
        <div class="auth-orb o1"></div>
        <div class="auth-orb o2"></div>

        <!-- Logo -->
        <div class="brand-logo">
            <i class="bi bi-shield-check"></i>
            <span><?= e(APP_NAME) ?></span>
        </div>

        <!-- Main content -->
        <div class="brand-body">
            <h1 class="brand-headline">
                Secure <span class="grad-text">User Management</span><br>at Your Fingertips
            </h1>
            <p class="brand-subtitle">
                A production-grade SaaS platform for user roles, permissions, authentication, and real-time analytics — all in one powerful dashboard.
            </p>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-dot violet">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <span>Enterprise-grade authentication &amp; security</span>
                </div>
                <div class="feature-item">
                    <div class="feature-dot cyan">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <span>Role-based access control for all users</span>
                </div>
                <div class="feature-item">
                    <div class="feature-dot pink">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <span>Real-time analytics and audit tracking</span>
                </div>
            </div>

            <div class="brand-stats">
                <div>
                    <span class="brand-stat-val">100%</span>
                    <span class="brand-stat-label">Secure</span>
                </div>
                <div>
                    <span class="brand-stat-val">RBAC</span>
                    <span class="brand-stat-label">Access Control</span>
                </div>
                <div>
                    <span class="brand-stat-val">Live</span>
                    <span class="brand-stat-label">Analytics</span>
                </div>
            </div>
        </div>

        <div class="brand-footer">
            &copy; <?= date('Y') ?> <?= e(APP_NAME) ?>. All rights reserved.
        </div>
    </div>

    <!-- ── Form Panel ───────────────────────────────────── -->
    <div class="auth-form-panel">
        <div class="auth-card">
            <h2 class="auth-card-title">Welcome Back 👋</h2>
            <p class="auth-card-sub">Sign in to your account to continue.</p>

            <?php if (has_flash('error')): ?>
                <div class="auth-alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <?= get_flash('error') ?>
                </div>
            <?php endif; ?>

            <form id="login-form" method="POST" action="<?= url('/login') ?>">
                <?= csrf_field() ?>

                <!-- Username / Email -->
                <div class="auth-input-wrap">
                    <label class="auth-label">Username or Email</label>
                    <div style="position:relative;">
                        <i class="bi bi-person auth-input-icon"></i>
                        <input type="text"
                               class="form-control auth-input"
                               id="login_input"
                               name="login_input"
                               placeholder="Enter username or email"
                               value="<?= old('login_input') ?>"
                               required
                               autocomplete="username">
                    </div>
                </div>

                <!-- Password -->
                <div class="auth-input-wrap">
                    <label class="auth-label">Password</label>
                    <div style="position:relative;">
                        <i class="bi bi-lock auth-input-icon"></i>
                        <input type="password"
                               class="form-control auth-input"
                               id="password"
                               name="password"
                               placeholder="Enter your password"
                               required
                               autocomplete="current-password">
                        <button type="button" class="pw-toggle" id="toggle-password">
                            <i class="bi bi-eye" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember & Forgot -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <label class="auth-check">
                        <input type="checkbox" name="remember" value="1">
                        Remember me
                    </label>
                    <a href="<?= url('/forgot-password') ?>"
                       style="font-size:0.8rem; color:var(--neon-violet); font-weight:600;">
                        Forgot password?
                    </a>
                </div>

                <button type="submit" class="btn-signin" id="login-submit-btn">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Sign In
                </button>

                <p class="text-center mt-3 mb-0" style="font-size:0.82rem; color:var(--text-muted);">
                    Don't have an account?
                    <a href="<?= url('/register') ?>"
                       style="color:var(--neon-cyan); font-weight:700;">
                        Create one
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Password toggle
    const toggleBtn = document.getElementById('toggle-password');
    const passInput = document.getElementById('password');
    const eyeIcon   = document.getElementById('eye-icon');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const show = passInput.type === 'password';
            passInput.type = show ? 'text' : 'password';
            eyeIcon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }

    // Animate card on load
    const card = document.querySelector('.auth-card');
    if (card) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        requestAnimationFrame(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        });
    }
});
</script>

<?php include ROOT_PATH . 'views/layouts/footer.php'; ?>
