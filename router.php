<?php
/**
 * Routing helper for PHP's built-in web server
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// If the file or directory exists physically, serve it directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; 
}

// Emulate .htaccess: Rewrite clean URLs to index.php?url=URI
$_GET['url'] = ltrim($uri, '/');

require_once __DIR__ . '/index.php';
