<?php
class Laporan {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // 1. Mengambil statistik untuk Card Atas (Pesanan Selesai, Barang Dicuci, Total Pendapatan)
    public function getStatistik($startDate = null, $endDate = null) {
        $query = "SELECT 
                    COUNT(id_pesanan) as total_pesanan,
                    SUM(total_harga) as total_pendapatan,
                    SUM(jumlah_barang) as total_barang -- Asumsi ada kolom jumlah barang
                  FROM pesanan 
                  WHERE status_pesanan = 'Selesai'";
        
        // Tambahkan filter tanggal jika ada
        if ($startDate && $endDate) {
            $query .= " AND tanggal_pesanan BETWEEN :start_date AND :end_date";
        }

        $stmt = $this->db->prepare($query);
        
        if ($startDate && $endDate) {
            $stmt->bindParam(':start_date', $startDate);
            $stmt->bindParam(':end_date', $endDate);
        }
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2. Mengambil rincian berdasarkan metode pembayaran (Cash, QRIS, Transfer)
    public function getPendapatanByMetode($startDate = null, $endDate = null) {
        $query = "SELECT 
                    metode_pembayaran, 
                    SUM(total_harga) as total 
                  FROM pesanan 
                  WHERE status_pesanan = 'Selesai'";
                  
        if ($startDate && $endDate) {
            $query .= " AND tanggal_pesanan BETWEEN :start_date AND :end_date";
        }
        
        $query .= " GROUP BY metode_pembayaran";

        $stmt = $this->db->prepare($query);
        
        if ($startDate && $endDate) {
            $stmt->bindParam(':start_date', $startDate);
            $stmt->bindParam(':end_date', $endDate);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>