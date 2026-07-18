<?php
/**
 * Base Controller Class
 */

abstract class Controller {
    // Render a view file with extracted variables
    protected function view(string $path, array $data = []): void {
        // CSRF Token generated for the session, to be accessible in templates
        $data['csrf_token'] = csrf_token();
        extract($data);
        
        $viewFile = ROOT_PATH . 'views' . DS . str_replace('/', DS, $path) . '.php';
        if (file_exists($viewFile)) {
            // Apply security headers
            $appConfig = require ROOT_PATH . 'config' . DS . 'app.php';
            header("Content-Security-Policy: " . $appConfig['security']['csp']);
            header("X-Content-Type-Options: nosniff");
            header("X-Frame-Options: SAMEORIGIN");
            header("X-XSS-Protection: 1; mode=block");
            header("Referrer-Policy: strict-origin-when-cross-origin");
            
            include $viewFile;
        } else {
            throw new Exception("View [$path] not found.");
        }
    }

    // Return JSON response (useful for AJAX)
    protected function json(array $data, int $statusCode = 200): void {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data);
        exit();
    }

    // Helper to check if request is AJAX
    protected function isAjax(): bool {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
               || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    }

    // Check if request is POST
    protected function isPost(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    // Validate CSRF token in state-changing requests
    protected function validateCsrf(): void {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        if (!csrf_verify($token)) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'CSRF Token verification failed.'], 403);
            } else {
                http_response_code(403);
                throw new Exception("CSRF Token verification failed.");
            }
        }
    }

    // Get and trim request variables
    protected function getRequestData(): array {
        $data = [];
        $rawInputs = array_merge($_GET, $_POST);
        foreach ($rawInputs as $key => $value) {
            if (is_array($value)) {
                $data[$key] = array_map('trim', $value);
            } else {
                $data[$key] = trim($value);
            }
        }
        return $data;
    }
}
