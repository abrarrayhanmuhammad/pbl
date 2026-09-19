<?php

require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$authController = new AuthController($pdo);
$dashboardController = new DashboardController($pdo);

if ($uri === '/' && $method === 'GET') {

    header('Location: /login');
    exit;

} elseif ($uri === '/login' && $method === 'GET') {

    $authController->showLogin();

} elseif ($uri === '/login/process' && $method === 'POST') {

    $authController->login();

} elseif ($uri === '/logout' && $method === 'GET') {

    $authController->logout();

} elseif ($uri === '/dashboard' && $method === 'GET') {
    $dashboardController->index();
} else {
    http_response_code(404);
    require_once __DIR__ . '/../app/views/errors/404.php';
    echo "404 - Halaman tidak ditemukan";
}