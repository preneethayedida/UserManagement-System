<?php
/**
 * Application Bootstrap Loader
 */

// Load Constants
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'constants.php';

// Load Config
$appConfig = require_once ROOT_PATH . 'config' . DIRECTORY_SEPARATOR . 'app.php';

// Session Security Configurations
$sessionConfig = $appConfig['session'];
session_set_cookie_params([
    'lifetime' => $sessionConfig['lifetime'],
    'path'     => $sessionConfig['path'],
    'domain'   => $sessionConfig['domain'],
    'secure'   => $sessionConfig['secure'],
    'httponly' => $sessionConfig['httponly'],
    'samesite' => $sessionConfig['samesite']
]);

if (session_status() === PHP_SESSION_NONE) {
    session_name($sessionConfig['name']);
    session_start();
}

// Session Timeout Handler
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $sessionConfig['lifetime'])) {
    session_unset();
    session_destroy();
    session_start();
}
$_SESSION['LAST_ACTIVITY'] = time();

// Autoload Classes (Controllers, Models, Middleware)
spl_autoload_register(function ($class) {
    $dirs = [
        ROOT_PATH . 'controllers' . DS,
        ROOT_PATH . 'models' . DS,
        ROOT_PATH . 'middleware' . DS,
        ROOT_PATH . 'config' . DS,
        ROOT_PATH . 'routes' . DS
    ];
    
    foreach ($dirs as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Centralized Error and Exception Logger
function logSystemError($severity, $message, $file, $line, $trace = '') {
    $logDir = ROOT_PATH . 'storage' . DS . 'logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . DS . 'app.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [$severity] File: $file | Line: $line | Message: $message\n";
    if ($trace) {
        $logMessage .= "Stack Trace:\n$trace\n";
    }
    $logMessage .= str_repeat('-', 80) . "\n";
    
    error_log($logMessage, 3, $logFile);
}

// Exception Handler
set_exception_handler(function (Throwable $exception) {
    logSystemError(
        'EXCEPTION',
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString()
    );
    
    // Check if it's an AJAX Request
    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
              || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
              
    if ($isAjax) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => 'An internal server error occurred. Please contact the administrator.'
        ]);
    } else {
        http_response_code(500);
        $errorFile = ROOT_PATH . 'views' . DS . 'errors' . DS . '500.php';
        if (file_exists($errorFile)) {
            include $errorFile;
        } else {
            echo "<h1>500 Internal Server Error</h1><p>An internal server error occurred.</p>";
        }
    }
    exit();
});

// Error Handler
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return false;
    }
    
    $severity = match ($errno) {
        E_USER_ERROR => 'ERROR',
        E_USER_WARNING, E_WARNING => 'WARNING',
        E_USER_NOTICE, E_NOTICE => 'NOTICE',
        default => 'UNKNOWN'
    };
    
    logSystemError($severity, $errstr, $errfile, $errline);
    
    if ($errno === E_USER_ERROR || $errno === E_ERROR) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    
    return true;
});

// Load Helper Functions
require_once ROOT_PATH . 'helpers' . DS . 'functions.php';
