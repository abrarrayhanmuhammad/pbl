<?php

class LogAktivitas
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Mengambil semua log
    public function getAllLogs(): array
    {
        $sql = "
            SELECT l.*, p.nama AS nama_pegawai 
            FROM log_aktivitas l
            LEFT JOIN pegawai p ON p.id_pegawai = l.id_pegawai
            ORDER BY l.created_at DESC
        ";
        
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fungsi untuk mencatat log (bisa dipanggil dari controller lain nanti)
    // Pastikan kolom yang di-insert sesuai dengan struktur tabelmu
    public function catatLog(
        int $idPegawai, 
        string $kategori, 
        string $aksi, 
        ?string $referensi = null, 
        ?string $keterangan = null
    ): void {
        $sql = "INSERT INTO log_aktivitas (id_pegawai, kategori, aksi, referensi, keterangan, ip_address, created_at) 
                VALUES (:id_pegawai, :kategori, :aksi, :referensi, :keterangan, :ip_address, CURRENT_TIMESTAMP)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_pegawai' => $idPegawai,
            'kategori'   => $kategori,
            'aksi'       => $aksi,
            'referensi'  => $referensi,
            'keterangan' => $keterangan,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
        ]);
    }
}