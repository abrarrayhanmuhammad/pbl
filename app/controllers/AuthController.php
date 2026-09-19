<?php

require_once __DIR__ . '/../models/Pegawai.php';

class AuthController
{
    private Pegawai $pegawaiModel;

    public function __construct(PDO $db)
    {
        $this->pegawaiModel = new Pegawai($db);
    }

    public function showLogin(): void
    {
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $_SESSION['error'] = 'Username dan password wajib diisi.';
            header('Location: /login');
            exit;
        }

        $pegawai = $this->pegawaiModel->findByUsername($username);

        if (!$pegawai || !password_verify($password, $pegawai['password'])) {
            $_SESSION['error'] = 'Username atau password salah.';
            header('Location: /login');
            exit;
        }

        session_regenerate_id(true);

        $_SESSION['id_pegawai'] = $pegawai['id_pegawai'];
        $_SESSION['nama'] = $pegawai['nama'];
        $_SESSION['role'] = $pegawai['role'];

        if ($pegawai['role'] === 'superadmin') {
            header('Location: /dashboard');
        } else {
            header('Location: /dashboard');
        }

        exit;
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();

        header('Location: /login');
        exit;
    }
}