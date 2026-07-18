<?php
/**
 * Admin Only Access Middleware
 */

class AdminOnly {
    
    public function handle(array $params = []): void {
        // Must be logged in first
        if (!is_logged_in()) {
            set_flash('error', 'Please log in to access this page.');
            redirect('/login');
        }

        // Must be Admin
        if (!is_admin()) {
            // Check if AJAX
            $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
                      || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
            
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Access Denied. Administrator privileges required.'
                ]);
                exit();
            } else {
                http_response_code(403);
                $forbiddenFile = ROOT_PATH . 'views' . DS . 'errors' . DS . '403.php';
                if (file_exists($forbiddenFile)) {
                    include $forbiddenFile;
                } else {
                    echo "<h1>403 Forbidden</h1><p>You do not have administrative privileges to access this resource.</p>";
                }
                exit();
            }
        }
    }
}
