<?php

class Pesanan
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllPesanan(): array
    {
        $sql = "
        SELECT
            p.id_pesanan,
            p.kode_pesanan,
            c.nama AS nama_customer,
            c.no_hp,
            p.tanggal_masuk,
            p.tanggal_selesai,
            p.status,
            p.status_bayar,
            p.total_bayar,
            COUNT(DISTINCT dp.id_detail_pesanan) AS jumlah_barang,
            STRING_AGG(DISTINCT jb.nama_jenis, ', ') AS jenis_barang,
            STRING_AGG(DISTINCT l.nama_layanan, ', ') AS nama_layanan
        FROM pesanan p
        JOIN customer c
            ON c.id_customer = p.id_customer
        LEFT JOIN detail_pesanan dp
            ON dp.id_pesanan = p.id_pesanan
        LEFT JOIN layanan l
            ON l.id_layanan = dp.id_layanan
        LEFT JOIN jenis_barang jb
            ON jb.id_jenis = l.id_jenis
        GROUP BY
            p.id_pesanan,
            p.kode_pesanan,
            c.nama,
            c.no_hp,
            p.tanggal_masuk,
            p.tanggal_selesai,
            p.status,
            p.status_bayar,
            p.total_bayar
        ORDER BY p.id_pesanan DESC
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
