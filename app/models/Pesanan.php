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
            STRING_AGG(DISTINCT l.nama_layanan, ', ') AS nama_layanan,
            STRING_AGG(DISTINCT pk.nama_paket, ', ') AS nama_paket
        FROM pesanan p
        JOIN customer c
            ON c.id_customer = p.id_customer
        LEFT JOIN detail_pesanan dp
            ON dp.id_pesanan = p.id_pesanan
        LEFT JOIN layanan l
            ON l.id_layanan = dp.id_layanan
        LEFT JOIN jenis_barang jb
            ON jb.id_jenis = l.id_jenis
        LEFT JOIN paket pk
            ON pk.id_paket = dp.id_paket
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

    public function getRingkasanBulanan(): array
    {
        $sql = "
            SELECT
                COUNT(pesanan.id_pesanan) FILTER (
                    WHERE pesanan.tanggal_masuk >= date_trunc('month', CURRENT_DATE)
                    AND pesanan.tanggal_masuk < date_trunc('month', CURRENT_DATE) + INTERVAL '1 month'
                ) AS \"totalPesanan\",
                COUNT(pesanan.id_pesanan) FILTER (
                    WHERE pesanan.status IN ('Menunggu', 'Diproses')
                ) AS \"pesananAktif\",
                COALESCE(SUM(pesanan.total_bayar) FILTER (
                    WHERE pesanan.status_bayar = 'Lunas'
                    AND pesanan.tanggal_masuk >= date_trunc('month', CURRENT_DATE)
                    AND pesanan.tanggal_masuk < date_trunc('month', CURRENT_DATE) + INTERVAL '1 month'
                ), 0) AS \"pendapatan\"
            FROM pesanan
        ";
        return $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function getPesananMingguan(): array
    {
        $sql = "
            SELECT
                hari.tanggal::date AS \"tanggal\",
                COUNT(pesanan.id_pesanan) AS \"jumlahPesanan\",
                COALESCE(SUM(pesanan.total_bayar) FILTER (WHERE pesanan.status_bayar = 'Lunas'), 0) AS \"omset\",
                COUNT(pesanan.id_pesanan) FILTER (WHERE pesanan.status_bayar = 'Lunas') AS \"jumlahLunas\"
            FROM generate_series(
                date_trunc('week', CURRENT_DATE),
                date_trunc('week', CURRENT_DATE) + INTERVAL '6 days',
                INTERVAL '1 day'
            ) AS hari(tanggal)
            LEFT JOIN pesanan ON pesanan.tanggal_masuk = hari.tanggal::date
            GROUP BY hari.tanggal
            ORDER BY hari.tanggal
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPerluPerhatian(): array
    {
        $sql = "
            SELECT DISTINCT
                pesanan.id_pesanan AS \"idPesanan\",
                pesanan.kode_pesanan AS \"kodePesanan\",
                customer.nama AS \"namaCustomer\",
                treatment_tambahan.nama_treatment AS \"namaTreatment\",
                (pesanan.tanggal_masuk + treatment_tambahan.estimasi_max_hari) - CURRENT_DATE AS \"sisaHari\"
            FROM pesanan
            JOIN customer ON pesanan.id_customer = customer.id_customer
            JOIN detail_pesanan ON detail_pesanan.id_pesanan = pesanan.id_pesanan
            JOIN detail_treatment_tambahan ON detail_treatment_tambahan.id_detail_pesanan = detail_pesanan.id_detail_pesanan
            JOIN treatment_tambahan ON detail_treatment_tambahan.id_treatment_tambahan = treatment_tambahan.id_treatment_tambahan
            WHERE pesanan.status IN ('Menunggu', 'Diproses')
            AND treatment_tambahan.nama_treatment IN ('Express', 'Prioritas')
            AND (pesanan.tanggal_masuk + treatment_tambahan.estimasi_max_hari) - CURRENT_DATE <= 3
            ORDER BY \"sisaHari\" ASC
            LIMIT 5
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
