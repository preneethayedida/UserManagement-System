<?php
/**
 * Application Settings
 */

return [
    'session' => [
        'name' => 'SECURE_USER_SESSION',
        'lifetime' => 1800, // 30 minutes in seconds
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Strict',
    ],
    'security' => [
        'csrf_token_name' => 'csrf_token',
        'csp' => "default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; img-src 'self' data:; connect-src 'self'",
    ],
    'uploads' => [
        'profile_path' => ROOT_PATH . 'assets' . DS . 'uploads' . DS . 'profile' . DS,
        'profile_url' => '/assets/uploads/profile/',
        'max_size' => 2 * 1024 * 1024, // 2MB
        'allowed_types' => [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/jpg' => 'jpg'
        ]
    ]
];
