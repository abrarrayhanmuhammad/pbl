<?php

require_once __DIR__ . '/../models/LogAktivitas.php';

class LogAktivitasController
{
    private LogAktivitas $logModel;

    public function __construct(PDO $db)
    {
        $this->logModel = new LogAktivitas($db);
    }

    public function index(): void
    {
        // Pastikan hanya superadmin yang bisa lihat log
        if (!isset($_SESSION['id_pegawai']) || strtolower($_SESSION['role'] ?? '') !== 'superadmin') {
            header('Location: /login');
            exit;
        }

        // Ambil data dari model
        $logs = $this->logModel->getAllLogs();

        // Panggil view (sesuaikan path-nya dengan struktur foldermu)
        require_once __DIR__ . '/../views/log/log_aktivitas.php'; 
    }
}