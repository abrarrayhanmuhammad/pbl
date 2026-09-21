<?php

class Pesanan
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getPesanan(
        string $customer = '',
        string $jenis = '',
        string $status = '',
        int $limit = 10,
        int $offset = 0
    ): array {
        $sql = "
        SELECT
            p.id_pesanan,
            c.id_customer,
            c.kode_customer,
            c.nama AS customer,
            j.id_jenis,
            j.nama_jenis AS jenis,
            dp.merk,
            l.nama_layanan AS layanan,
            p.tgl_masuk,
            p.tgl_masuk + dp.estimasi_max AS estimasi_selesai,
            p.status

        FROM pesanan p
        JOIN customer c
            ON p.id_customer = c.id_customer
        JOIN detail_pesanan dp
            ON p.id_pesanan = dp.id_pesanan
        JOIN layanan l
            ON dp.id_layanan = l.id_layanan
        JOIN jenis_barang j
            ON l.id_jenis = j.id_jenis
        WHERE 1 = 1
    ";

        $params = [];

        if ($customer !== '') {
            $sql .= " AND c.nama ILIKE :customer";
            $params['customer'] = '%' . $customer . '%';
        }

        if ($jenis !== '') {
            $sql .= " AND j.id_jenis = :jenis";
            $params['jenis'] = $jenis;
        }

        if ($status !== '') {
            $sql .= " AND p.status = :status";
            $params['status'] = $status;
        }

        $sql .= "
        ORDER BY p.id_pesanan DESC
        LIMIT :limit
        OFFSET :offset
    ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetailPesanan(int $id): ?array
    {

        $sqlPesanan = "
        SELECT
            p.id_pesanan,
            p.tgl_masuk,
            p.status,

            c.id_customer,
            c.nama AS customer,
            c.kode_customer,
            c.no_hp,

            pb.total AS total_pembayaran,
            pb.metode,
            pb.status AS status_pembayaran,
            pb.tgl_bayar

        FROM pesanan p

        JOIN customer c
            ON p.id_customer = c.id_customer

        LEFT JOIN pembayaran pb
            ON p.id_pesanan = pb.id_pesanan

        WHERE p.id_pesanan = :id

        LIMIT 1
    ";

        $stmtPesanan = $this->db->prepare($sqlPesanan);

        $stmtPesanan->bindValue(
            ':id',
            $id,
            PDO::PARAM_INT
        );

        $stmtPesanan->execute();

        $data = $stmtPesanan->fetch(PDO::FETCH_ASSOC);


        if (!$data) {
            return null;
        }



        $sqlDetail = "
        SELECT
            dp.id_detail_pesanan,

            j.id_jenis,
            j.nama_jenis AS jenis,

            l.id_layanan,
            l.nama_layanan AS layanan,
            l.kategori,

            dp.merk,
            dp.warna,
            dp.variasi,
            dp.catatan,

            dp.harga_layanan,
            dp.harga_treatment,
            dp.subtotal,

            dp.estimasi_min,
            dp.estimasi_max,

            et.id_treatment,
            et.nama_treatment AS treatment,

            dp.foto_before,
            dp.foto_after

        FROM detail_pesanan dp

        JOIN layanan l
            ON dp.id_layanan = l.id_layanan

        JOIN jenis_barang j
            ON l.id_jenis = j.id_jenis

        LEFT JOIN extra_treatment et
            ON dp.id_treatment = et.id_treatment

        WHERE dp.id_pesanan = :id

        ORDER BY dp.id_detail_pesanan ASC
    ";

        $stmtDetail = $this->db->prepare($sqlDetail);

        $stmtDetail->bindValue(
            ':id',
            $id,
            PDO::PARAM_INT
        );

        $stmtDetail->execute();

        $detailBarang = $stmtDetail->fetchAll(PDO::FETCH_ASSOC);
        $data['detail_barang'] = $detailBarang;

        return $data;
    }

    public function countPesanan(
        string $customer = '',
        string $jenis = '',
        string $status = ''
    ): int {
        $sql = "
            SELECT COUNT(*)
            FROM pesanan p

            JOIN customer c
                ON p.id_customer = c.id_customer

            JOIN detail_pesanan dp
                ON p.id_pesanan = dp.id_pesanan

            JOIN layanan l
                ON dp.id_layanan = l.id_layanan

            JOIN jenis_barang j
                ON l.id_jenis = j.id_jenis

            WHERE 1 = 1
        ";

        $params = [];

        if ($customer !== '') {
            $sql .= " AND c.nama ILIKE :customer";
            $params['customer'] = '%' . $customer . '%';
        }

        if ($jenis !== '') {
            $sql .= " AND j.id_jenis = :jenis";
            $params['jenis'] = $jenis;
        }

        if ($status !== '') {
            $sql .= " AND p.status = :status";
            $params['status'] = $status;
        }

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getJenisBarang(): array
    {
        $sql = "
            SELECT
                id_jenis,
                nama_jenis
            FROM jenis_barang
            ORDER BY id_jenis ASC
        ";

        return $this->db
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): bool
    {
        $sql = "
            DELETE FROM pesanan
            WHERE id_pesanan = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id
        ]);
    }
}
