<?php

class LaporanController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
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

        // ===== 1. Pesanan Selesai =====
        $sql = "SELECT COUNT(*) FROM pesanan WHERE status = 'Selesai'";
        if ($hasFilter) $sql .= " AND tanggal_masuk BETWEEN :start AND :end";
        $stmt = $this->db->prepare($sql);
        if ($hasFilter) {
            $stmt->bindValue(':start', $startDate);
            $stmt->bindValue(':end',   $endDate);
        }
        $stmt->execute();
        $totalPesanan = (int) $stmt->fetchColumn();

        // ===== 2. Barang Dicuci =====
        $sql = "SELECT COALESCE(SUM(dp.jumlah), 0)
                FROM detail_pesanan dp
                JOIN pesanan p ON p.id_pesanan = dp.id_pesanan
                WHERE p.status = 'Selesai'";
        if ($hasFilter) $sql .= " AND p.tanggal_masuk BETWEEN :start AND :end";
        $stmt = $this->db->prepare($sql);
        if ($hasFilter) {
            $stmt->bindValue(':start', $startDate);
            $stmt->bindValue(':end',   $endDate);
        }
        $stmt->execute();
        $totalBarang = (int) $stmt->fetchColumn();

        // ===== 3. Total Pendapatan (Lunas) =====
        $sql = "SELECT COALESCE(SUM(total_bayar), 0)
                FROM pesanan
                WHERE status_bayar = 'Lunas'";
        if ($hasFilter) $sql .= " AND tanggal_masuk BETWEEN :start AND :end";
        $stmt = $this->db->prepare($sql);
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

        // ===== 4. Rincian per Metode (Lunas) =====
        $sql = "SELECT COALESCE(metode_bayar, 'Belum Ditentukan') AS metode,
                       COALESCE(SUM(total_bayar), 0) AS total
                FROM pesanan
                WHERE status_bayar = 'Lunas'";
        if ($hasFilter) $sql .= " AND tanggal_masuk BETWEEN :start AND :end";
        $sql .= " GROUP BY metode_bayar ORDER BY total DESC";

        $stmt = $this->db->prepare($sql);
        if ($hasFilter) {
            $stmt->bindValue(':start', $startDate);
            $stmt->bindValue(':end',   $endDate);
        }
        $stmt->execute();
        $rincianPembayaran = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Kirim ke view
        require_once __DIR__ . '/../views/laporan/laporan.php';
    }

    public function exportPdf(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        $startDate = $_GET['start_date'] ?? null;
        $endDate   = $_GET['end_date']   ?? null;
        $hasFilter = !empty($startDate) && !empty($endDate);

        // ===== 1. Pesanan Selesai =====
        $sql = "SELECT COUNT(*) FROM pesanan WHERE status = 'Selesai'";
        if ($hasFilter) $sql .= " AND tanggal_masuk BETWEEN :start AND :end";
        $stmt = $this->db->prepare($sql);
        if ($hasFilter) {
            $stmt->bindValue(':start', $startDate);
            $stmt->bindValue(':end',   $endDate);
        }
        $stmt->execute();
        $totalPesanan = (int) $stmt->fetchColumn();

        // ===== 2. Barang Dicuci =====
        $sql = "SELECT COALESCE(SUM(dp.jumlah), 0)
                FROM detail_pesanan dp
                JOIN pesanan p ON p.id_pesanan = dp.id_pesanan
                WHERE p.status = 'Selesai'";
        if ($hasFilter) $sql .= " AND p.tanggal_masuk BETWEEN :start AND :end";
        $stmt = $this->db->prepare($sql);
        if ($hasFilter) {
            $stmt->bindValue(':start', $startDate);
            $stmt->bindValue(':end',   $endDate);
        }
        $stmt->execute();
        $totalBarang = (int) $stmt->fetchColumn();

        // ===== 3. Total Pendapatan (Lunas) =====
        $sql = "SELECT COALESCE(SUM(total_bayar), 0)
                FROM pesanan
                WHERE status_bayar = 'Lunas'";
        if ($hasFilter) $sql .= " AND tanggal_masuk BETWEEN :start AND :end";
        $stmt = $this->db->prepare($sql);
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

        // ===== 4. Rincian per Metode (Lunas) =====
        $sql = "SELECT COALESCE(metode_bayar, 'Belum Ditentukan') AS metode,
                       COALESCE(SUM(total_bayar), 0) AS total
                FROM pesanan
                WHERE status_bayar = 'Lunas'";
        if ($hasFilter) $sql .= " AND tanggal_masuk BETWEEN :start AND :end";
        $sql .= " GROUP BY metode_bayar ORDER BY total DESC";

        $stmt = $this->db->prepare($sql);
        if ($hasFilter) {
            $stmt->bindValue(':start', $startDate);
            $stmt->bindValue(':end',   $endDate);
        }
        $stmt->execute();
        $rincianPembayaran = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Kirim ke view khusus PDF
        require_once __DIR__ . '/../views/laporan/laporan_pdf.php';
    }
}