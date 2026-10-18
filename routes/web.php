<?php

require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/PesananController.php';
require_once __DIR__ . '/../app/controllers/LaporanController.php';
require_once __DIR__ . '/../app/controllers/PegawaiController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$authController = new AuthController($pdo);
$dashboardController = new DashboardController($pdo);
$pesananController = new PesananController($pdo);
$laporanController = new LaporanController($pdo);
$pegawaiController = new PegawaiController($pdo);

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
} elseif ($uri === '/pesanan' && $method === 'GET') {
    $pesananController->index();
} elseif ($uri === '/pesanan/tambah' && $method === 'GET') {
    $pesananController->tambahPesanan();
} elseif ($uri === '/pesanan/simpan' && $method === 'POST') {
    $pesananController->simpanPesanan();
} elseif ($uri === '/pesanan/edit' && $method === 'GET') {
    $pesananController->editPesanan();
} elseif ($uri === '/pesanan/detail' && $method === 'GET') {
    $pesananController->detail();
} elseif ($uri === '/pesanan/detail-data' && $method === 'GET') {
    $pesananController->detailData();
} elseif ($uri === '/invoice' && $method === 'GET') {
    $pesananController->invoice();
} elseif ($uri === '/laporan' && $method === 'GET') {
    $laporanController->index();
} elseif ($uri === '/akun' && $method === 'GET') {
    $pegawaiController->index();
} else {
    http_response_code(404);
    require_once __DIR__ . '/../app/views/errors/404.php';
    // echo "404 - Halaman tidak ditemukan";
}
