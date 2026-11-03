
<?php

use Cloudinary\Cloudinary;
use Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';

$rootPath = dirname(__DIR__, 2);

$dotenv = Dotenv::createImmutable($rootPath);
$dotenv->safeLoad();

$cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? getenv('CLOUDINARY_CLOUD_NAME') ?: '';
$apiKey = $_ENV['CLOUDINARY_API_KEY'] ?? getenv('CLOUDINARY_API_KEY') ?: '';
$apiSecret = $_ENV['CLOUDINARY_API_SECRET'] ?? getenv('CLOUDINARY_API_SECRET') ?: '';

if (
    $cloudName === '' ||
    $apiKey === '' ||
    $apiSecret === ''
) {
    throw new RuntimeException(
        'Konfigurasi Cloudinary tidak ditemukan. Periksa file .env.'
    );
}

return new Cloudinary([
    'cloud' => [
        'cloud_name' => $cloudName,
        'api_key' => $apiKey,
        'api_secret' => $apiSecret
    ],
    'url' => [
        'secure' => true
    ]
]);
