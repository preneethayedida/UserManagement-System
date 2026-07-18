<?php include ROOT_PATH . 'views/layouts/header.php'; ?>

<style>
.app-container { display: block !important; }

.auth-shell {
    min-height: 100vh;
    display: flex;
    background: var(--bg-cosmos);
    background-image:
        radial-gradient(ellipse at 15% 15%, rgba(191,95,255,0.15) 0%, transparent 50%),
        radial-gradient(ellipse at 85% 85%, rgba(255,45,120,0.1) 0%, transparent 50%);
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
.auth-orb.o1 { width: 260px; height: 260px; background: rgba(191,95,255,0.12); top: -50px; right: 0; }

.brand-ring { position: absolute; border-radius: 50%; pointer-events: none; border: 1px solid rgba(191,95,255,0.07); }
.brand-ring.r1 { width: 450px; height: 450px; top: -140px; right: -140px; }

.brand-logo {
    display: inline-flex; align-items: center; gap: 0.75rem;
    font-family: var(--font-heading); font-weight: 700; font-size: 0.95rem;
    background: linear-gradient(135deg, var(--neon-violet), var(--neon-cyan));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    position: relative; z-index: 2;
}
.brand-logo i { font-size: 1.5rem; color: var(--neon-violet); -webkit-text-fill-color: var(--neon-violet); filter: drop-shadow(0 0 10px var(--glow-violet)); }

.brand-body { position: relative; z-index: 2; }

.brand-headline { font-family: var(--font-heading); font-size: 2.4rem; font-weight: 800; line-height: 1.15; color: var(--text-white); margin-bottom: 1rem; }
.brand-headline .grad-text { background: linear-gradient(135deg, var(--neon-violet) 0%, var(--neon-cyan) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.brand-subtitle { font-size: 0.87rem; color: var(--text-muted); line-height: 1.75; max-width: 340px; margin-bottom: 2rem; }

.brand-footer { font-size: 0.73rem; color: var(--text-muted); position: relative; z-index: 2; }

.auth-form-panel {
    flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem;
    position: relative;
    background: linear-gradient(160deg, rgba(19,14,40,0.6) 0%, rgba(7,5,18,0.8) 100%);
}
.auth-form-panel::before {
    content: ''; position: absolute; inset: 0;
    background-image: linear-gradient(rgba(191,95,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(191,95,255,0.03) 1px, transparent 1px);
    background-size: 40px 40px; pointer-events: none;
}

.auth-card {
    width: 100%; max-width: 440px;
    background: rgba(19,14,40,0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(191,95,255,0.2); border-radius: 20px; padding: 2.5rem;
    box-shadow: 0 0 0 1px rgba(191,95,255,0.04) inset, 0 30px 60px rgba(0,0,0,0.5), 0 0 40px rgba(191,95,255,0.04);
    position: relative; z-index: 2;
}
.auth-card::before {
    content: ''; position: absolute; top: 0; left: 10%; right: 10%; height: 1px;
    background: linear-gradient(90deg, transparent, var(--neon-violet), var(--neon-cyan), transparent);
}

.auth-card-title { font-family: var(--font-heading); font-size: 1.45rem; font-weight: 700; color: var(--text-white); margin-bottom: 0.25rem; }
.auth-card-sub { font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1.8rem; }

.auth-label { display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.5px; }

.auth-input-wrap { position: relative; margin-bottom: 1.5rem; }
.auth-input-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.95rem; z-index: 5; }
.auth-input {
    width: 100%; padding: 0.78rem 1rem 0.78rem 2.6rem;
    background: rgba(255,255,255,0.04) !important; border: 1px solid rgba(255,255,255,0.08) !important;
    border-radius: 10px !important; color: var(--text-white) !important; font-size: 0.86rem !important;
    transition: var(--transition) !important;
}
.auth-input:focus {
    background: rgba(191,95,255,0.04) !important; border-color: var(--neon-violet) !important;
    box-shadow: 0 0 0 3px rgba(191,95,255,0.1), 0 0 16px rgba(191,95,255,0.06) !important;
}

.btn-reset {
    width: 100%; padding: 0.85rem; border-radius: 10px; border: none;
    background: linear-gradient(135deg, var(--neon-violet) 0%, #7B3FE4 100%);
    color: #fff; font-family: var(--font-heading); font-size: 0.9rem; font-weight: 700;
    cursor: pointer; transition: var(--transition);
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    box-shadow: 0 4px 20px rgba(191,95,255,0.25); position: relative; overflow: hidden;
}
.btn-reset::after {
    content: ''; position: absolute; top: 0; left: -100%; width: 60%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
    transition: left 0.55s var(--ease);
}
.btn-reset:hover::after { left: 160%; }
.btn-reset:hover { transform: translateY(-2px); box-shadow: 0 0 30px rgba(191,95,255,0.4), 0 6px 24px rgba(0,0,0,0.3); }

@media (max-width: 767.98px) {
    .auth-brand { display: none !important; }
}
</style>

<div class="auth-shell">
    <!-- Brand Panel -->
    <div class="auth-brand">
        <div class="auth-orb o1"></div>
        <div class="brand-ring r1"></div>

        <div class="brand-logo">
            <i class="bi bi-shield-check"></i>
            <span><?= e(APP_NAME) ?></span>
        </div>

        <div class="brand-body">
            <h1 class="brand-headline">
                Recover <span>Your Account</span><br>Safely &amp; Fast
            </h1>
            <p class="brand-subtitle">
                Enter your email address and we'll simulate sending you a password recovery link to restore your account access.
            </p>
        </div>

        <div class="brand-footer">
            &copy; <?= date('Y') ?> <?= e(APP_NAME) ?>. All rights reserved.
        </div>
    </div>

    <!-- Form Panel -->
    <div class="auth-form-panel">
        <div class="auth-card">
            <h2 class="auth-card-title">Forgot Password 🔒</h2>
            <p class="auth-card-sub">Recover your credentials safely.</p>

            <?php if (has_flash('error')): ?>
                <div class="alert alert-danger mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= get_flash('error') ?>
                </div>
            <?php endif; ?>

            <form id="forgot-form" method="POST" action="<?= url('/forgot-password') ?>">
                <?= csrf_field() ?>

                <div class="auth-input-wrap">
                    <label class="auth-label">Email Address</label>
                    <div style="position:relative;">
                        <i class="bi bi-envelope auth-input-icon"></i>
                        <input type="email" class="form-control auth-input" name="email"
                               placeholder="john@example.com" required>
                    </div>
                </div>

                <button type="submit" class="btn-reset">
                    <i class="bi bi-send-fill"></i>
                    Send Recovery Link
                </button>

                <p class="text-center mt-3 mb-0" style="font-size:0.82rem; color:var(--text-muted);">
                    Remembered your password?
                    <a href="<?= url('/login') ?>" style="color:var(--neon-cyan); font-weight:700;">
                        Sign in
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

<?php include ROOT_PATH . 'views/layouts/footer.php'; ?>
