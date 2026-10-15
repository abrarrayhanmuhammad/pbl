<?php


class LaporanController
{
    public function index(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        require_once __DIR__ . '/../views/laporan/laporan.php';
    }
}