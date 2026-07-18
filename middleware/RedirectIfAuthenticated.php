<?php
/**
 * Redirect If Authenticated Middleware
 */

class RedirectIfAuthenticated {
    
    public function handle(array $params = []): void {
        if (is_logged_in()) {
            redirect('/dashboard');
        }
    }
}
