<?php

class Layanan
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getLayanan(): array
    {
        $sql = "SELECT DISTINCT kategori FROM layanan ORDER BY kategori ASC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllLayanan(): array
    {
        $sql = "SELECT id_layanan, nama_layanan, kategori, harga_min, variasi, estimasi_min_hari, estimasi_max_hari FROM layanan ORDER BY kategori ASC, id_layanan ASC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllPaket(): array
    {
        $sql = "SELECT id_paket, nama_paket, harga, estimasi_min_hari, estimasi_max_hari FROM paket ORDER BY id_paket ASC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
