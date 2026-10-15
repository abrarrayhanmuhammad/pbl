<?php

require_once __DIR__ . '/../models/Pesanan.php';
require_once __DIR__ . '/../models/Pembayaran.php';

class DashboardController
{
    private Pesanan $pesananModel;
    private Pembayaran $pembayaranModel;

    public function __construct(PDO $db)
    {
        $this->pesananModel = new Pesanan($db);
        $this->pembayaranModel = new Pembayaran($db);
    }

    public function index(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        // if ($_SESSION['role'] !== 'superadmin') {
        //     http_response_code(403);
        //     require_once __DIR__ . '/../views/errors/403.php';
        //     exit;
        // }

        // $totalPesanan = $this->pesananModel->countPesanan();
        // $pesananAktif = $this->pesananModel->countPesananAktif();
        // $pesananSelesaiHariIni = $this->pesananModel->countSelesaiHariIni();
        // $pendapatan = $this->pembayaranModel->getTotalPendapatan();
        // $pesananTerbaru = $this->pesananModel->getPesananTerbaru();

        $totalPesanan = 148;
        $pesananAktif = 32;
        $selesaiHariIni = 8;
        $pendapatan = 4500000;

        $pesananTerbaru = [
            [
                'id' => '0881',
                'customer' => 'Budi Santoso',
                'barang' => 'Sepatu Nike',
                'bayar' => 'Lunas'
            ],
            [
                'id' => '0882',
                'customer' => 'Dewi Lestari',
                'barang' => 'Tas Coach',
                'bayar' => 'Belum'
            ],
            [
                'id' => '0883',
                'customer' => 'Adit Jaelani',
                'barang' => 'Helm Cargloss',
                'bayar' => 'Lunas'
            ]
        ];

        if ($_SESSION['role'] === 'superadmin') {
            require_once __DIR__ . '/../views/dashboard/dashboard_owner.php';
        }else {
            require_once __DIR__ . '/../views/dashboard/dashboard.php';
        }
    }
}