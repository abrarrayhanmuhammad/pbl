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
                c.nama AS customer,
                j.nama_jenis AS jenis,
                l.nama_layanan AS layanan,
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