    </div><!-- End .app-container -->

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <!-- Custom Main JS -->
    <script src="<?= asset('js/main.js') ?>"></script>

    <!-- ── UI Shell Scripts ─────────────────────────────────── -->
    <script>
    (function() {
        /* ─ Sidebar Toggle ─ */
        const sidebar  = document.getElementById('app-sidebar');
        const toggle   = document.getElementById('sidebar-toggle');

        // Create overlay element
        const overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);

        if (toggle && sidebar) {
            toggle.addEventListener('click', () => {
                const isMobile = window.innerWidth < 992;
                if (isMobile) {
                    sidebar.classList.toggle('mobile-open');
                    overlay.classList.toggle('show');
                } else {
                    sidebar.classList.toggle('collapsed');
                }
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('show');
            });
        }

        /* ─ Navbar User Dropdown ─ */
        const userDropdown = document.getElementById('navbar-user-dropdown');
        const userToggle   = document.getElementById('navbar-user-toggle');
        const dropMenu     = document.getElementById('navbar-dropdown-menu');

        if (userToggle && userDropdown) {
            userToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('open');
            });

            document.addEventListener('click', (e) => {
                if (!userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('open');
                }
            });
        }

        /* ─ Theme Toggle ─ */
        const themeBtn   = document.getElementById('theme-toggle');
        const iconLight  = document.getElementById('theme-icon-light');
        const iconDark   = document.getElementById('theme-icon-dark');
        const htmlEl     = document.documentElement;

        function applyTheme(theme) {
            htmlEl.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);
            if (theme === 'dark') {
                if (iconLight) iconLight.style.display = 'none';
                if (iconDark)  iconDark.style.display  = 'inline-block';
            } else {
                if (iconLight) iconLight.style.display = 'inline-block';
                if (iconDark)  iconDark.style.display  = 'none';
            }
        }

        // Initialise from saved preference
        const saved = localStorage.getItem('theme') || 'light';
        applyTheme(saved);

        if (themeBtn) {
            themeBtn.addEventListener('click', () => {
                const current = htmlEl.getAttribute('data-bs-theme') || 'light';
                applyTheme(current === 'dark' ? 'light' : 'dark');
            });
        }

    })();
    </script>

    <!-- Global Flash Notification Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            <?php if (has_flash('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: <?= json_encode(get_flash('success')) ?>,
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            <?php endif; ?>

            <?php if (has_flash('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: <?= json_encode(get_flash('error')) ?>,
                    confirmButtonColor: '#6366F1'
                });
            <?php endif; ?>

            <?php if (has_flash('errors')): ?>
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Errors',
                    html: <?= json_encode(get_flash('errors')) ?>,
                    confirmButtonColor: '#6366F1'
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
