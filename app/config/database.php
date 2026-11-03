<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$rootPath = dirname(__DIR__, 2);

$dotenv = Dotenv\Dotenv::createImmutable($rootPath);
$dotenv->safeLoad();

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$port = $_ENV['DB_PORT'] ?? '5432';
$dbname = $_ENV['DB_NAME'] ?? 'kickdropshoe';
$username = $_ENV['DB_USER'] ?? '';
$password = $_ENV['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    error_log('Koneksi database gagal: ' . $e->getMessage());

    http_response_code(500);
    die('Koneksi database gagal. Silakan hubungi administrator.');
}