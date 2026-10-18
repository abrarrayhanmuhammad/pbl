<?php

require_once __DIR__ . '/../models/Pesanan.php';

class DashboardController
{
    private Pesanan $pesananModel;

    public function __construct(PDO $db)
    {
        $this->pesananModel = new Pesanan($db);
    }

    public function index(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }


        if ($_SESSION['role'] === 'superadmin') {
            require_once __DIR__ . '/../views/dashboard/dashboard_owner.php';
        }else {
            require_once __DIR__ . '/../views/dashboard/dashboard.php';
        }
    }
}