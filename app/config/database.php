<?php

$host = "localhost";
$port = "5432";
$dbname = "kickdropshoe";
$username = "postgres";
$password = "1234";

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Koneksi database berhasil!";
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}