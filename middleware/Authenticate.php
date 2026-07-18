<?php
/**
 * Authenticate Middleware
 */

class Authenticate {
    
    public function handle(array $params = []): void {
        // First check if user is in session
        if (!is_logged_in()) {
            // Check for Remember Me token before booting them out
            if ($this->checkRememberMe()) {
                return;
            }

            // Flash error
            set_flash('error', 'Please log in to access this page.');

            // Check if AJAX
            $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
                      || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
            
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Unauthorized. Please log in.'
                ]);
                exit();
            } else {
                redirect('/login');
            }
        }
    }

    // Try to auto-login using Remember Me cookie
    private function checkRememberMe(): bool {
        if (!empty($_COOKIE['remember_me'])) {
            $cookie = $_COOKIE['remember_me'];
            $parts = explode(':', $cookie);
            if (count($parts) === 2) {
                list($selector, $validator) = $parts;

                $tokenModel = new Token();
                $token = $tokenModel->findTokenBySelector($selector);

                if ($token) {
                    // Verify the validator hash
                    if (hash_equals($token['hashed_validator'], hash('sha256', $validator))) {
                        // User is active check
                        if ($token['status'] === 'active') {
                            // Boot session
                            $_SESSION['user'] = [
                                'id'            => $token['user_id'],
                                'role_id'       => $token['role_id'],
                                'full_name'     => $token['full_name'],
                                'username'      => $token['username'],
                                'email'         => $token['email'],
                                'profile_image' => $token['profile_image']
                            ];
                            // Regenerate session ID for security
                            session_regenerate_id(true);
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }
}
