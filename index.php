<?php
/**
 * Front Controller Entrypoint
 */

// Initialize Bootstrapper
require_once __DIR__ . '/bootstrap.php';

// Retrieve requested URL and HTTP method
$url = $_GET['url'] ?? '';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Load routes definitions
$router = require_once ROOT_PATH . 'routes' . DS . 'web.php';

// Dispatch request to matching route
$router->dispatch($url, $method);
