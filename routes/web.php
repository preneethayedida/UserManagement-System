<?php
/**
 * Application Web Routes
 */

$router = new Router();

// -------------------------------------------------------------
// Guest Routes
// -------------------------------------------------------------
$router->get('', 'AuthController@showLogin', [RedirectIfAuthenticated::class]); // Default page maps to login
$router->get('/login', 'AuthController@showLogin', [RedirectIfAuthenticated::class]);
$router->post('/login', 'AuthController@login', [RedirectIfAuthenticated::class]);
$router->get('/register', 'AuthController@showRegister', [RedirectIfAuthenticated::class]);
$router->post('/register', 'AuthController@register', [RedirectIfAuthenticated::class]);
$router->get('/forgot-password', 'AuthController@showForgotPassword', [RedirectIfAuthenticated::class]);
$router->post('/forgot-password', 'AuthController@forgotPassword', [RedirectIfAuthenticated::class]);

// -------------------------------------------------------------
// Authenticated General Routes
// -------------------------------------------------------------
$router->get('/logout', 'AuthController@logout', [Authenticate::class]);
$router->get('/dashboard', 'DashboardController@index', [Authenticate::class]);
$router->get('/settings', 'ProfileController@settings', [Authenticate::class]);
$router->post('/settings/save', 'ProfileController@saveSettings', [Authenticate::class]);

// Profile Routes
$router->get('/profile', 'ProfileController@index', [Authenticate::class]);
$router->post('/profile/update', 'ProfileController@update', [Authenticate::class]);
$router->post('/profile/password', 'ProfileController@changePassword', [Authenticate::class]);
$router->post('/profile/avatar', 'ProfileController@uploadAvatar', [Authenticate::class]);

// -------------------------------------------------------------
// Admin Restricted CRUD Routes
// -------------------------------------------------------------
$router->get('/admin/users', 'AdminController@index', [AdminOnly::class]);
$router->get('/admin/users/list', 'AdminController@listUsers', [AdminOnly::class]); // AJAX pagination list
$router->get('/admin/users/show/{id}', 'AdminController@show', [AdminOnly::class]); // AJAX fetch single user
$router->post('/admin/users/store', 'AdminController@store', [AdminOnly::class]); // AJAX store
$router->post('/admin/users/update/{id}', 'AdminController@update', [AdminOnly::class]); // AJAX update
$router->post('/admin/users/delete/{id}', 'AdminController@delete', [AdminOnly::class]); // AJAX delete
$router->get('/admin/roles', 'AdminController@roles', [AdminOnly::class]); // Admin roles view

return $router;
