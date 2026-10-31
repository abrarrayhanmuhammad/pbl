<?php

require_once __DIR__ . '/../models/Pegawai.php';

class PegawaiController
{
    private PDO $db;
    private Pegawai $pegawaiModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
       $this->pegawaiModel = new Pegawai($db);
    }

    public function index(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        require_once __DIR__ . '/../views/akun/akun.php';
    }

    public function updatePassword(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Metode tidak diizinkan.');
        }

        if (
            empty($_SESSION['csrf_token']) ||
            !is_string($_POST['csrf_token'] ?? null) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            http_response_code(403);
            exit('Token keamanan tidak valid.');
        }

        $passwordLama = $_POST['password_lama'] ?? '';
        $passwordBaru = $_POST['password_baru'] ?? '';
        $idPegawai = $_SESSION['id_pegawai'];

        if ($passwordLama === '' || $passwordBaru === '') {
            header('Location: /akun?status=kosong');
            exit;
        }

        if (strlen($passwordBaru) < 8) {
            header('Location: /akun?status=password_pendek');
            exit;
        }

        $stmt = $this->db->prepare("
        SELECT password
        FROM pegawai
        WHERE id_pegawai = :id
    ");
        $stmt->execute(['id' => $idPegawai]);
        $pegawai = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pegawai || !password_verify($passwordLama, $pegawai['password'])) {
            header('Location: /akun?status=password_salah');
            exit;
        }

        $passwordHash = password_hash($passwordBaru, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("
        UPDATE pegawai
        SET password = :password
        WHERE id_pegawai = :id
    ");

        $stmt->execute([
            'password' => $passwordHash,
            'id' => $idPegawai
        ]);

        header('Location: /akun?status=password_berhasil');
        exit;
    }
}
