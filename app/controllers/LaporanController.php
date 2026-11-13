<?php

require_once __DIR__ . '/../config/database.php'; // menyediakan $pdo

class LaporanController
{
    private PDO $db;

    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
    }

    public function index(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        $startDate = $_GET['start_date'] ?? null;
        $endDate   = $_GET['end_date']   ?? null;
        $hasFilter = !empty($startDate) && !empty($endDate);

        // =========================================================
        // 1. STATISTIK CARD ATAS
        // =========================================================
        // Pesanan Selesai -> COUNT dari pesanan
        $sqlPesanan = "SELECT COUNT(*) AS total_pesanan
                       FROM pesanan
                       WHERE status = 'Selesai'";
        if ($hasFilter) {
            $sqlPesanan .= " AND tanggal_masuk BETWEEN :start AND :end";
        }
        $stmt = $this->db->prepare($sqlPesanan);
        if ($hasFilter) {
            $stmt->bindValue(':start', $startDate);
            $stmt->bindValue(':end',   $endDate);
        }
        $stmt->execute();
        $totalPesanan = (int) $stmt->fetchColumn();

        // Barang Dicuci -> SUM(jumlah) dari detail_pesanan, JOIN pesanan untuk filter
        $sqlBarang = "SELECT COALESCE(SUM(dp.jumlah), 0) AS total_barang
                      FROM detail_pesanan dp
                      JOIN pesanan p ON p.id_pesanan = dp.id_pesanan
                      WHERE p.status = 'Selesai'";
        if ($hasFilter) {
            $sqlBarang .= " AND p.tanggal_masuk BETWEEN :start AND :end";
        }
        $stmt = $this->db->prepare($sqlBarang);
        if ($hasFilter) {
            $stmt->bindValue(':start', $startDate);
            $stmt->bindValue(':end',   $endDate);
        }
        $stmt->execute();
        $totalBarang = (int) $stmt->fetchColumn();

        // Total Pendapatan -> SUM(total_bayar), filter status Lunas biar akurat
        $sqlPendapatan = "SELECT COALESCE(SUM(total_bayar), 0) AS total_pendapatan
                          FROM pesanan
                          WHERE status = 'Selesai'
                            AND status_bayar = 'Lunas'";
        if ($hasFilter) {
            $sqlPendapatan .= " AND tanggal_masuk BETWEEN :start AND :end";
        }
        $stmt = $this->db->prepare($sqlPendapatan);
        if ($hasFilter) {
            $stmt->bindValue(':start', $startDate);
            $stmt->bindValue(':end',   $endDate);
        }
        $stmt->execute();
        $totalPendapatan = (int) $stmt->fetchColumn();

        $statistik = [
            'total_pesanan'    => $totalPesanan,
            'total_barang'     => $totalBarang,
            'total_pendapatan' => $totalPendapatan,
        ];

        // =========================================================
        // 2. RINCIAN PER METODE PEMBAYARAN
        // =========================================================
        $sqlBayar = "SELECT
                        COALESCE(metode_bayar, 'Belum Ditentukan') AS metode,
                        COALESCE(SUM(total_bayar), 0) AS total
                     FROM pesanan
                     WHERE status = 'Selesai'
                       AND status_bayar = 'Lunas'";
        if ($hasFilter) {
            $sqlBayar .= " AND tanggal_masuk BETWEEN :start AND :end";
        }
        $sqlBayar .= " GROUP BY metode_bayar ORDER BY total DESC";

        $stmt = $this->db->prepare($sqlBayar);
        if ($hasFilter) {
            $stmt->bindValue(':start', $startDate);
            $stmt->bindValue(':end',   $endDate);
        }
        $stmt->execute();
        $rincianPembayaran = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // =========================================================
        // 3. Kirim ke view
        // =========================================================
        require_once __DIR__ . '/../views/laporan/laporan.php';
    }
}