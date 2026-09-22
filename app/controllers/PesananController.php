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
        $jenis = trim($_GET['jenis'] ?? '');
        $status = trim($_GET['status'] ?? '');

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

        if ($page > $totalPages) {
            $page = $totalPages;

            $offset = ($page - 1) * $limit;

            $pesanan = $this->pesananModel->getPesanan(
                $customer,
                $jenis,
                $status,
                $limit,
                $offset
            );
        }

        $jenisBarang = $this->pesananModel->getJenisBarang();

        require_once __DIR__ . '/../views/pesanan/index.php';
    }

    public function detail(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        $id = isset($_GET['id'])
            ? (int) $_GET['id']
            : 0;

        if ($id <= 0) {
            header('Location: /pesanan');
            exit;
        }

        $pesanan = $this->pesananModel->getDetailPesanan($id);

        if (!$pesanan) {
            http_response_code(404);

            require_once __DIR__ . '/../views/errors/404.php';
            exit;
        }

        require_once __DIR__ . '/../views/pesanan/detail.php';
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
