<?php include ROOT_PATH . 'views/layouts/header.php'; ?>

<style>
/* Reuse auth styles from login — same shell */
.app-container { display: block !important; }

.auth-shell {
    min-height: 100vh;
    display: flex;
    background: var(--bg-cosmos);
    background-image:
        radial-gradient(ellipse at 15% 15%, rgba(6,255,165,0.12) 0%, transparent 50%),
        radial-gradient(ellipse at 85% 85%, rgba(191,95,255,0.14) 0%, transparent 50%);
}

.auth-brand {
    flex: 0 0 42%;
    max-width: 42%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 3rem 3.5rem;
    position: relative;
    overflow: hidden;
}

.auth-orb { position: absolute; border-radius: 50%; pointer-events: none; filter: blur(60px); }
.auth-orb.o1 { width: 260px; height: 260px; background: rgba(6,255,165,0.1); top: -50px; right: 0; }
.auth-orb.o2 { width: 220px; height: 220px; background: rgba(191,95,255,0.1); bottom: 50px; left: -30px; }

.brand-ring { position: absolute; border-radius: 50%; pointer-events: none; border: 1px solid rgba(6,255,165,0.07); }
.brand-ring.r1 { width: 450px; height: 450px; top: -140px; right: -140px; }
.brand-ring.r2 { width: 300px; height: 300px; bottom: -100px; left: -80px; border-color: rgba(191,95,255,0.07); }

.brand-logo {
    display: inline-flex; align-items: center; gap: 0.75rem;
    font-family: var(--font-heading); font-weight: 700; font-size: 0.95rem;
    background: linear-gradient(135deg, var(--neon-cyan), var(--neon-violet));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    position: relative; z-index: 2;
}
.brand-logo i { font-size: 1.5rem; color: var(--neon-cyan); -webkit-text-fill-color: var(--neon-cyan); filter: drop-shadow(0 0 10px rgba(6,255,165,0.5)); }

.brand-body { position: relative; z-index: 2; }

.brand-headline { font-family: var(--font-heading); font-size: 2.4rem; font-weight: 800; line-height: 1.15; color: var(--text-white); margin-bottom: 1rem; }
.brand-headline .grad-text { background: linear-gradient(135deg, var(--neon-cyan) 0%, var(--neon-violet) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.brand-subtitle { font-size: 0.87rem; color: var(--text-muted); line-height: 1.75; max-width: 340px; margin-bottom: 2rem; }

.feature-list { display: flex; flex-direction: column; gap: 0.9rem; }
.feature-item { display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--text-body); }
.feature-dot { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0; border: 1px solid; }
.feature-dot.cyan   { background: rgba(6,255,165,0.08);   border-color: rgba(6,255,165,0.2);   color: var(--neon-cyan);   box-shadow: 0 0 14px rgba(6,255,165,0.12); }
.feature-dot.violet { background: rgba(191,95,255,0.08);  border-color: rgba(191,95,255,0.2);  color: var(--neon-violet); box-shadow: 0 0 14px rgba(191,95,255,0.12); }
.feature-dot.amber  { background: rgba(255,214,10,0.08);  border-color: rgba(255,214,10,0.2);  color: var(--neon-amber);  box-shadow: 0 0 14px rgba(255,214,10,0.1); }

.brand-footer { font-size: 0.73rem; color: var(--text-muted); position: relative; z-index: 2; }

/* Form Panel */
.auth-form-panel {
    flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem;
    position: relative;
    background: linear-gradient(160deg, rgba(19,14,40,0.6) 0%, rgba(7,5,18,0.8) 100%);
}
.auth-form-panel::before {
    content: ''; position: absolute; inset: 0;
    background-image: linear-gradient(rgba(6,255,165,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(6,255,165,0.03) 1px, transparent 1px);
    background-size: 40px 40px; pointer-events: none;
}

.auth-card {
    width: 100%; max-width: 480px;
    background: rgba(19,14,40,0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(6,255,165,0.2); border-radius: 20px; padding: 2.2rem;
    box-shadow: 0 0 0 1px rgba(6,255,165,0.04) inset, 0 30px 60px rgba(0,0,0,0.5), 0 0 40px rgba(6,255,165,0.04);
    position: relative; z-index: 2;
}
.auth-card::before {
    content: ''; position: absolute; top: 0; left: 10%; right: 10%; height: 1px;
    background: linear-gradient(90deg, transparent, var(--neon-cyan), var(--neon-violet), transparent);
}

.auth-card-title { font-family: var(--font-heading); font-size: 1.45rem; font-weight: 700; color: var(--text-white); margin-bottom: 0.25rem; }
.auth-card-sub { font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1.5rem; }

.auth-label { display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.5px; }

.auth-input {
    width: 100%; padding: 0.78rem 1rem;
    background: rgba(255,255,255,0.04) !important; border: 1px solid rgba(255,255,255,0.08) !important;
    border-radius: 10px !important; color: var(--text-white) !important; font-size: 0.86rem !important;
    transition: var(--transition) !important; font-family: var(--font-sans) !important;
}
.auth-input::placeholder { color: var(--text-muted) !important; }
.auth-input:focus {
    background: rgba(6,255,165,0.04) !important; border-color: var(--neon-cyan) !important;
    box-shadow: 0 0 0 3px rgba(6,255,165,0.1), 0 0 16px rgba(6,255,165,0.06) !important;
    outline: none !important; color: var(--text-white) !important;
}

.auth-alert {
    padding: 0.8rem 1rem; border-radius: 10px; font-size: 0.82rem;
    background: rgba(255,45,120,0.08); border: 1px solid rgba(255,45,120,0.25);
    color: var(--neon-pink); margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.6rem;
}

.btn-register {
    width: 100%; padding: 0.85rem; border-radius: 10px; border: none;
    background: linear-gradient(135deg, var(--neon-cyan) 0%, var(--neon-cyan-dark) 100%);
    color: #060C14; font-family: var(--font-heading); font-size: 0.9rem; font-weight: 700;
    cursor: pointer; transition: var(--transition);
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    box-shadow: 0 4px 20px rgba(6,255,165,0.25); position: relative; overflow: hidden;
}
.btn-register::after {
    content: ''; position: absolute; top: 0; left: -100%; width: 60%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.18), transparent);
    transition: left 0.55s var(--ease);
}
.btn-register:hover::after { left: 160%; }
.btn-register:hover { transform: translateY(-2px); box-shadow: 0 0 30px rgba(6,255,165,0.4), 0 6px 24px rgba(0,0,0,0.3); }

@media (max-width: 767.98px) {
    .auth-brand { display: none !important; }
}
</style>

<div class="auth-shell">
    <!-- Brand Panel -->
    <div class="auth-brand">
        <div class="auth-orb o1"></div>
        <div class="auth-orb o2"></div>
        <div class="brand-ring r1"></div>
        <div class="brand-ring r2"></div>

        <div class="brand-logo">
            <i class="bi bi-shield-check"></i>
            <span><?= e(APP_NAME) ?></span>
        </div>

        <div class="brand-body">
            <h1 class="brand-headline">
                Join the <span class="grad-text">Secure Platform</span><br>Built for Teams
            </h1>
            <p class="brand-subtitle">
                Create your account and gain access to powerful user management tools, analytics, and role-based access control.
            </p>
            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-dot cyan"><i class="bi bi-lightning-charge-fill"></i></div>
                    <span>Get up and running in seconds</span>
                </div>
                <div class="feature-item">
                    <div class="feature-dot violet"><i class="bi bi-person-check-fill"></i></div>
                    <span>Manage your profile and settings instantly</span>
                </div>
                <div class="feature-item">
                    <div class="feature-dot amber"><i class="bi bi-lock-fill"></i></div>
                    <span>Secured with bcrypt hashing &amp; CSRF protection</span>
                </div>
            </div>
        </div>

        <div class="brand-footer">
            &copy; <?= date('Y') ?> <?= e(APP_NAME) ?>. All rights reserved.
        </div>
    </div>

    <!-- Form Panel -->
    <div class="auth-form-panel">
        <div class="auth-card">
            <h2 class="auth-card-title">Create Account ✨</h2>
            <p class="auth-card-sub">Fill in the details below to register your account.</p>

            <?php if (has_flash('errors')): ?>
                <div class="auth-alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <?= get_flash('errors') ?>
                </div>
            <?php endif; ?>

            <form id="register-form" method="POST" action="<?= url('/register') ?>">
                <?= csrf_field() ?>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="auth-label">Full Name</label>
                        <input type="text" class="form-control auth-input" name="full_name"
                               placeholder="John Doe"
                               value="<?= old('full_name') ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="auth-label">Username</label>
                        <input type="text" class="form-control auth-input" name="username"
                               placeholder="johndoe"
                               value="<?= old('username') ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="auth-label">Email Address</label>
                    <input type="email" class="form-control auth-input" name="email"
                           placeholder="john@example.com"
                           value="<?= old('email') ?>" required>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <label class="auth-label">Password</label>
                        <input type="password" class="form-control auth-input" name="password"
                               placeholder="Min. 8 characters" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="auth-label">Confirm Password</label>
                        <input type="password" class="form-control auth-input" name="confirm_password"
                               placeholder="Repeat password" required>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    <i class="bi bi-person-plus-fill"></i>
                    Create My Account
                </button>

                <p class="text-center mt-3 mb-0" style="font-size:0.82rem; color:var(--text-muted);">
                    Already have an account?
                    <a href="<?= url('/login') ?>" style="color:var(--neon-violet); font-weight:700;">
                        Sign in
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

<?php include ROOT_PATH . 'views/layouts/footer.php'; ?>
