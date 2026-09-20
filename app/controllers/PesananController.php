<?php

require_once __DIR__ . '/../models/Pesanan.php';

class PesananController
{
    private Pesanan $pesananModel;

    public function __construct(PDO $db)
    {
        $this->pesananModel = new Pesanan($db);
    }

    public function index(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        $customer = trim($_GET['customer'] ?? '');
        $jenis = $_GET['jenis'] ?? '';
        $status = $_GET['status'] ?? '';

        $limit = 10;

        $page = isset($_GET['page'])
            ? max(1, (int) $_GET['page'])
            : 1;

        $offset = ($page - 1) * $limit;

        $pesanan = $this->pesananModel->getPesanan(
            $customer,
            $jenis,
            $status,
            $limit,
            $offset
        );

        $totalPesanan = $this->pesananModel->countPesanan(
            $customer,
            $jenis,
            $status
        );

        $totalPages = max(
            1,
            (int) ceil($totalPesanan / $limit)
        );

        $jenisBarang = $this->pesananModel->getJenisBarang();


        require_once __DIR__ . '/../views/pesanan/index.php';
    }

    public function delete(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);

        if ($id > 0) {
            $this->pesananModel->delete($id);
        }

        header('Location: /pesanan');
        exit;
    }
}