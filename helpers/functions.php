<?php
/**
 * Global Helper Functions
 */

// Escape HTML for XSS prevention
function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Generate or get CSRF token
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Generate CSRF hidden input field
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

// Check CSRF token validity
function csrf_verify(?string $token): bool {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// Redirect to URL
function redirect(string $path): void {
    header('Location: ' . url($path));
    exit();
}

// Helper to construct URLs
function url(string $path = ''): string {
    $path = ltrim($path, '/');
    return BASE_URL . ($path ? '/' . $path : '');
}

// Helper to construct asset URLs
function asset(string $path = ''): string {
    return url('assets/' . ltrim($path, '/'));
}

// Get old input value from form submissions
function old(string $key, $default = '') {
    if (isset($_SESSION['old_input'][$key])) {
        $val = $_SESSION['old_input'][$key];
        unset($_SESSION['old_input'][$key]);
        return $val;
    }
    return $default;
}

// Store old inputs for next request redirection
function keep_old_input(array $data): void {
    $_SESSION['old_input'] = $data;
}

// Set flash message
function set_flash(string $type, string $message): void {
    $_SESSION['flash'][$type] = $message;
}

// Get flash message
function get_flash(string $type): ?string {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

// Check for flash message presence
function has_flash(string $type): bool {
    return isset($_SESSION['flash'][$type]);
}

// Get currently logged-in user
function auth(): ?array {
    return $_SESSION['user'] ?? null;
}

// Check if user is logged in
function is_logged_in(): bool {
    return isset($_SESSION['user']);
}

// Check if logged-in user is Admin
function is_admin(): bool {
    return is_logged_in() && (int)$_SESSION['user']['role_id'] === 1; // Assuming 1 = Admin
}

// Profile image path checker
function get_avatar_url(?string $filename): string {
    if ($filename && file_exists(ROOT_PATH . 'assets' . DS . 'uploads' . DS . 'profile' . DS . $filename)) {
        return asset('uploads/profile/' . $filename);
    }
    return asset('images/default-avatar.png');
}
