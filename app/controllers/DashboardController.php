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

        $role = strtolower($_SESSION['role'] ?? '');

        if ($role === 'superadmin') {
            $ringkasan      = $this->pesananModel->getRingkasanBulanan();
            $dataMingguan   = $this->pesananModel->getPesananMingguan();
            $perluPerhatian = $this->pesananModel->getPerluPerhatian();
        
            $namaHari = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
            $jumlahHarian = array_map('intval', array_column($dataMingguan, 'jumlahPesanan'));
            $totalMasuk   = array_sum($jumlahHarian);
            $hariBerjalan = (int) date('N');
            $rataRata     = $hariBerjalan > 0 ? (int) round($totalMasuk / $hariBerjalan) : 0;
            $maksHarian   = !empty($jumlahHarian) ? max($jumlahHarian) : 0;
            $hariTersibuk = $maksHarian > 0 ? $namaHari[array_search($maksHarian, $jumlahHarian) + 1] : '-';
            $skalaMaks    = max(5, (int) ceil($maksHarian / 5) * 5);
        
            require_once __DIR__ . '/../views/dashboard/dashboard_owner.php';
            
        } elseif ($role === 'karyawan') {
            // 👇 MENGGUNAKAN METHOD YANG SUDAH ADA 👇
            $ringkasan      = $this->pesananModel->getRingkasanBulanan();
            $perluPerhatian = $this->pesananModel->getPerluPerhatian();
            
            // Ambil total pesanan aktif dari ringkasan
            $pesananAktifCount = $ringkasan['pesananAktif'] ?? 0;
            
            require_once __DIR__ . '/../views/dashboard/dashboard_karyawan.php';
            
        } else {
            require_once __DIR__ . '/../views/dashboard/dashboard_karyawan.php';
        }
    }
}