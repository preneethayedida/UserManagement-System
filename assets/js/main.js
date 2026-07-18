/**
 * Secure User Management System - Main JS
 * Features: Dark/Light Mode, Sidebar collapse, Ajax helper, SweetAlert wrappers, Client validations.
 */

document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initSidebar();
    initValidationListeners();
});

/**
 * 1. Theme Engine (Light/Dark Mode)
 */
function initTheme() {
    const themeToggle = document.getElementById('theme-toggle');
    if (!themeToggle) return;

    themeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    });
}

/**
 * 2. Responsive Sidebar Manager
 */
function initSidebar() {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('app-sidebar');
    const appContainer = document.querySelector('.app-container');

    if (!sidebarToggle || !sidebar) return;

    sidebarToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        if (window.innerWidth >= 992) {
            // Desktop Collapse
            sidebar.classList.toggle('collapsed');
        } else {
            // Mobile Overlay
            sidebar.classList.toggle('show-mobile');
            appContainer.classList.toggle('sidebar-open-mobile');
        }
    });

    // Close mobile sidebar on outer clicks
    document.addEventListener('click', (e) => {
        if (window.innerWidth < 992 && sidebar.classList.contains('show-mobile')) {
            if (!sidebar.contains(e.target) && e.target !== sidebarToggle && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('show-mobile');
                appContainer.classList.remove('sidebar-open-mobile');
            }
        }
    });
}

/**
 * 3. Centralized HTTP AJAX Client Wrapper
 * Attaches CSRF headers and parses standard JSON responses
 */
async function ajaxRequest(url, method = 'GET', data = null, isMultipart = false) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    const headers = {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken || ''
    };

    if (!isMultipart && !(data instanceof FormData)) {
        headers['Content-Type'] = 'application/json';
    }

    const config = {
        method: method.toUpperCase(),
        headers: headers
    };

    if (data) {
        if (isMultipart || data instanceof FormData) {
            config.body = data;
        } else {
            config.body = JSON.stringify(data);
        }
    }

    try {
        const response = await fetch(url, config);
        const jsonResult = await response.json();
        
        if (!response.ok) {
            return {
                ok: false,
                status: response.status,
                message: jsonResult.message || `HTTP error! Status: ${response.status}`,
                errors: jsonResult.errors || null
            };
        }
        
        return {
            ok: true,
            status: response.status,
            ...jsonResult
        };
    } catch (error) {
        console.error('AJAX Error:', error);
        return {
            ok: false,
            status: 500,
            message: 'Network error or connection lost. Please try again.'
        };
    }
}

/**
 * 4. Client-side Form Validation Listeners
 */
function initValidationListeners() {
    // Password Strength Indicator Hook
    const passwordInputs = document.querySelectorAll('.strength-check');
    passwordInputs.forEach(input => {
        input.addEventListener('input', () => {
            checkPasswordStrength(input);
        });
    });
}

// Visual indicator for Password strength checks
function checkPasswordStrength(input) {
    const password = input.value;
    const feedbackId = input.id + '-strength';
    let feedbackEl = document.getElementById(feedbackId);

    // Create feedback element if it doesn't exist
    if (!feedbackEl) {
        feedbackEl = document.createElement('div');
        feedbackEl.id = feedbackId;
        feedbackEl.className = 'form-text mt-1 fs-8 fw-semibold';
        input.parentNode.appendChild(feedbackEl);
    }

    if (!password) {
        feedbackEl.textContent = '';
        return;
    }

    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[@$!%*?&]/.test(password)) strength++;

    let strengthText = '';
    let color = '';

    switch (strength) {
        case 1:
        case 2:
            strengthText = 'Weak (Needs length / uppercase / numbers / symbols)';
            color = '#dc3545';
            break;
        case 3:
        case 4:
            strengthText = 'Medium (Almost secure, add special characters)';
            color = '#ffc107';
            break;
        case 5:
            strengthText = 'Strong Password';
            color = '#198754';
            break;
        default:
            strengthText = 'Too short';
            color = '#dc3545';
    }

    feedbackEl.textContent = strengthText;
    feedbackEl.style.color = color;
}

// Global Validation Helper (Form UI visual state)
function checkFormValidity(formElement) {
    if (!formElement.checkValidity()) {
        formElement.classList.add('was-validated');
        return false;
    }
    return true;
}
