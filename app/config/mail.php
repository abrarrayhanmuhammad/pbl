<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->safeLoad();

return [
    'host' => $_ENV['MAIL_HOST'],
    'port' => (int) $_ENV['MAIL_PORT'],
    'username' => $_ENV['MAIL_USERNAME'],
    'password' => $_ENV['MAIL_PASSWORD'],
    'from_email' => $_ENV['MAIL_FROM_EMAIL'],
    'from_name' => $_ENV['MAIL_FROM_NAME'],
    'app_url' => $_ENV['APP_URL']
];