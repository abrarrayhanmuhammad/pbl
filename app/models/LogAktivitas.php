<?php

class LogAktivitas
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Mengambil semua log
    public function getAllLogs(int $limit = 200): array
    {
        $sql = '
            SELECT
                log_aktivitas.created_at AS "createdAt",
                pegawai.nama AS "namaPegawai",
                pegawai.role AS "rolePegawai",
                log_aktivitas.kategori AS "kategori",
                log_aktivitas.referensi AS "referensi",
                log_aktivitas.aksi AS "aksi",
                log_aktivitas.keterangan AS "keterangan",
                log_aktivitas.ip_address AS "ipAddress"
            FROM log_aktivitas
            LEFT JOIN pegawai
                ON log_aktivitas.id_pegawai = pegawai.id_pegawai
            ORDER BY log_aktivitas.created_at DESC
            LIMIT :limit
        ';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    
    public function getAllLogsById(int $limit, int $id): array
    {
        $sql = '
        SELECT
            log_aktivitas.created_at AS "createdAt",
            pegawai.nama AS "namaPegawai",
            pegawai.role AS "rolePegawai",
            log_aktivitas.kategori AS "kategori",
            log_aktivitas.referensi AS "referensi",
            log_aktivitas.aksi AS "aksi",
            log_aktivitas.keterangan AS "keterangan",
            log_aktivitas.ip_address AS "ipAddress",
            pesanan.kode_pesanan AS "kodePesanan"
        FROM log_aktivitas
        LEFT JOIN pegawai
            ON log_aktivitas.id_pegawai = pegawai.id_pegawai
        INNER JOIN pesanan
            ON log_aktivitas.referensi = pesanan.kode_pesanan
        WHERE log_aktivitas.kategori = :kategori
            AND pesanan.id_pesanan = :id
        ORDER BY log_aktivitas.created_at DESC
        LIMIT :limit
    ';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':kategori', 'Pesanan', PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
