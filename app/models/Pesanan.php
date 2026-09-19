<?php

class Pesanan
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function countPesanan(): int
    {
        $sql = "SELECT COUNT(*) FROM pesanan";

        return (int) $this->db
            ->query($sql)
            ->fetchColumn();
    }

    public function countPesananAktif(): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM pesanan
            WHERE status != 'Selesai'
        ";

        return (int) $this->db
            ->query($sql)
            ->fetchColumn();
    }

    public function countSelesaiHariIni(): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM pesanan
            WHERE status = 'Selesai'
            AND tgl_masuk = CURRENT_DATE
        ";

        return (int) $this->db
            ->query($sql)
            ->fetchColumn();
    }

    public function getPesananTerbaru(): array
    {
        $sql = "
            SELECT
                p.id_pesanan,
                c.nama AS nama_customer,
                dp.merk,
                dp.warna,
                pb.total,
                pb.metode
            FROM pesanan p
            JOIN customer c
                ON p.id_customer = c.id_customer
            JOIN detail_pesanan dp
                ON p.id_pesanan = dp.id_pesanan
            JOIN pembayaran pb
                ON p.id_pembayaran = pb.id_pembayaran
            ORDER BY p.id_pesanan DESC
            LIMIT 5
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}