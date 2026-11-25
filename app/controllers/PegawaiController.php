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
            catatLog(
                $this->db,
                'Pegawai',
                'Ganti Password Gagal',
                'USER-PASSWORD-GAGAL',
                ($_SESSION['nama'] ?? 'Pegawai') .
                    ' salah memasukkan password lama saat mengganti password.'
            );

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

        catatLog(
            $this->db,
            'Pegawai',
            'Ganti Password',
            'USER-PASSWORD',
            ($_SESSION['nama'] ?? 'Pegawai') .
                ' mengganti password akunnya.'
        );

        header('Location: /akun?status=password_berhasil');
        exit;
    }

    private function gagalTambahKaryawan(string $pesan, array $input = []): void
    {
        $_SESSION['old_input'] = [
            'nama' => trim((string) ($input['nama'] ?? '')),
            'uname' => trim((string) ($input['uname'] ?? '')),
            'role' => trim((string) ($input['role'] ?? 'karyawan')),
            'email' => trim((string) ($input['email'] ?? '')),
        ];

        $_SESSION['buka_modal_tambah'] = true;
        $_SESSION['error'] = $pesan;

        header('Location: /tambah/karyawan');
        exit;
    }

    public function tambahKaryawan(): void
    {
        if (!isset($_SESSION['id_pegawai'], $_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
            header('Location: /login');
            exit;
        }

        $limit = 5;
        $totalData = $this->pegawaiModel->countAllPegawai();
        $totalHalaman = max(1, (int) ceil($totalData / $limit));

        $halamanAktif = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1;
        $halamanAktif = max(1, min($halamanAktif, $totalHalaman));

        $offset = ($halamanAktif - 1) * $limit;

        $dataPegawai = $this->pegawaiModel->getPegawaiPaginated($limit, $offset);

        $oldInput = $_SESSION['old_input'] ?? [];
        $bukaModalTambah = !empty($_SESSION['buka_modal_tambah']);

        unset($_SESSION['old_input']);
        unset($_SESSION['buka_modal_tambah']);

        require_once __DIR__ . '/../views/karyawan/karyawan.php';
    }

    public function simpanKaryawan(): void
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

        $nama = trim($_POST['nama'] ?? '');
        $uname = trim($_POST['uname'] ?? '');
        $role = trim($_POST['role'] ?? 'karyawan');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $konfirmasiPassword = $_POST['password_konfirmasi'] ?? '';

        $oldInput = [
            'nama' => $nama,
            'uname' => $uname,
            'role' => $role,
            'email' => $email,
        ];

        if ($nama === '' || $uname === '' || $password === '') {
            $this->gagalTambahKaryawan(
                'Nama lengkap, username, dan password wajib diisi.',
                $oldInput
            );
        }

        if (!in_array($role, ['karyawan', 'superadmin'], true)) {
            $this->gagalTambahKaryawan(
                'Peran karyawan tidak valid.',
                $oldInput
            );
        }

        if (strlen($password) < 8) {
            $this->gagalTambahKaryawan(
                'Password minimal 8 karakter.',
                $oldInput
            );
        }

        if ($password !== $konfirmasiPassword) {
            $this->gagalTambahKaryawan(
                'Konfirmasi password tidak cocok.',
                $oldInput
            );
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->gagalTambahKaryawan(
                'Format email tidak valid.',
                $oldInput
            );
        }

        try {
            $stmt = $this->db->prepare("
                SELECT id_pegawai
                FROM pegawai
                WHERE uname = ?
            ");

            $stmt->execute([$uname]);

            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->gagalTambahKaryawan(
                    'Username sudah digunakan, silakan pilih yang lain.',
                    $oldInput
                );
            }

            if ($email !== '') {
                $stmt = $this->db->prepare("
                    SELECT id_pegawai
                    FROM pegawai
                    WHERE email = ?
                ");

                $stmt->execute([$email]);

                if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                    $this->gagalTambahKaryawan(
                        'Email sudah terdaftar.',
                        $oldInput
                    );
                }
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->db->prepare("
                INSERT INTO pegawai
                    (nama, uname, role, email, password)
                VALUES
                    (?, ?, ?, ?, ?)
            ");

            $berhasil = $stmt->execute([
                $nama,
                $uname,
                $role,
                $email !== '' ? $email : null,
                $passwordHash
            ]);

            if (!$berhasil) {
                $this->gagalTambahKaryawan(
                    'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.',
                    $oldInput
                );
            }

            $idBaru = (int) $this->db->lastInsertId();

            catatLog(
                $this->db,
                'Pegawai',
                'Tambah Karyawan',
                'USER-CREATE',
                ($_SESSION['nama'] ?? 'Superadmin') .
                    ' menambahkan karyawan baru: ' . $nama .
                    ' (Username: ' . $uname .
                    ', Role: ' . $role .
                    ', ID: ' . $idBaru . ').'
            );

            unset($_SESSION['old_input']);
            unset($_SESSION['buka_modal_tambah']);

            $_SESSION['success'] = 'Karyawan baru berhasil ditambahkan.';
        } catch (PDOException $e) {
            $this->gagalTambahKaryawan(
                'Karyawan gagal ditambahkan. Periksa kembali data yang dimasukkan atau coba lagi.',
                $oldInput
            );
        }

        header('Location: /tambah/karyawan');
        exit;
    }

    public function updateKaryawan(): void
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

        $idPegawai = $_POST['id_pegawai'] ?? '';
        $nama = trim($_POST['nama'] ?? '');
        $uname = trim($_POST['uname'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $konfirmasiPassword = $_POST['password_konfirmasi'] ?? '';

        if (!ctype_digit((string) $idPegawai) || (int) $idPegawai < 1) {
            $_SESSION['error'] = 'Data karyawan tidak valid.';
            header('Location: /tambah/karyawan');
            exit;
        }

        if ($nama === '' || $uname === '') {
            $_SESSION['error'] = 'Nama lengkap dan username wajib diisi.';
            header('Location: /tambah/karyawan');
            exit;
        }

        if (!in_array($role, ['karyawan', 'superadmin'], true)) {
            $_SESSION['error'] = 'Role karyawan tidak valid.';
            header('Location: /tambah/karyawan');
            exit;
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Format email tidak valid.';
            header('Location: /tambah/karyawan');
            exit;
        }

        $stmt = $this->db->prepare("
            SELECT id_pegawai
            FROM pegawai
            WHERE id_pegawai = ?
        ");

        $stmt->execute([$idPegawai]);

        if (!$stmt->fetch()) {
            $_SESSION['error'] = 'Data karyawan tidak ditemukan.';
            header('Location: /tambah/karyawan');
            exit;
        }

        $stmt = $this->db->prepare("
            SELECT id_pegawai
            FROM pegawai
            WHERE uname = ? AND id_pegawai != ?
        ");

        $stmt->execute([$uname, $idPegawai]);

        if ($stmt->fetch()) {
            $_SESSION['error'] = 'Username sudah digunakan, silakan pilih yang lain.';
            header('Location: /tambah/karyawan');
            exit;
        }

        if ($email !== '') {
            $stmt = $this->db->prepare("
                SELECT id_pegawai
                FROM pegawai
                WHERE email = ? AND id_pegawai != ?
            ");

            $stmt->execute([$email, $idPegawai]);

            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Email sudah terdaftar.';
                header('Location: /tambah/karyawan');
                exit;
            }
        }

        if ($password !== '' && strlen($password) < 8) {
            $_SESSION['error'] = 'Password minimal 8 karakter.';
            header('Location: /tambah/karyawan');
            exit;
        }

        if ($password !== $konfirmasiPassword) {
            $_SESSION['error'] = 'Konfirmasi password tidak cocok.';
            header('Location: /tambah/karyawan');
            exit;
        }

        if ($password !== '') {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->db->prepare("
                UPDATE pegawai
                SET nama = ?, uname = ?, role = ?, email = ?, password = ?
                WHERE id_pegawai = ?
            ");

            $berhasil = $stmt->execute([
                $nama,
                $uname,
                $role,
                $email !== '' ? $email : null,
                $passwordHash,
                $idPegawai
            ]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE pegawai
                SET nama = ?, uname = ?, role = ?, email = ?
                WHERE id_pegawai = ?
            ");

            $berhasil = $stmt->execute([
                $nama,
                $uname,
                $role,
                $email !== '' ? $email : null,
                $idPegawai
            ]);
        }

        if ($berhasil) {
            catatLog(
                $this->db,
                'Pegawai',
                'Update Karyawan',
                'USER-UPDATE',
                ($_SESSION['nama'] ?? 'Superadmin') .
                    ' memperbarui data karyawan ' . $nama .
                    ' (Username: ' . $uname .
                    ', Role: ' . $role .
                    ', ID: ' . $idPegawai . ').' .
                    ($password !== '' ? ' Password akun juga diperbarui.' : '')
            );

            $_SESSION['success'] = 'Data karyawan berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Terjadi kesalahan, silakan coba lagi.';
        }

        header('Location: /tambah/karyawan');
        exit;
    }

    public function hapusKaryawan(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        if (($_SESSION['role'] ?? '') !== 'superadmin') {
            http_response_code(403);
            exit('Anda tidak memiliki akses untuk menghapus karyawan.');
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

        $idPegawai = $_POST['id_pegawai'] ?? '';

        if (!ctype_digit((string) $idPegawai) || (int) $idPegawai < 1) {
            $_SESSION['error'] = 'Data karyawan tidak valid.';
            header('Location: /tambah/karyawan');
            exit;
        }

        $idPegawai = (int) $idPegawai;
        $currentUserId = (int) $_SESSION['id_pegawai'];

        if ($idPegawai === $currentUserId) {
            $_SESSION['error'] = 'Anda tidak dapat menghapus akun sendiri.';
            header('Location: /tambah/karyawan');
            exit;
        }

        try {
            $stmt = $this->db->prepare("
                SELECT id_pegawai, nama, role
                FROM pegawai
                WHERE id_pegawai = ?
            ");

            $stmt->execute([$idPegawai]);
            $pegawai = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pegawai) {
                $_SESSION['error'] = 'Data karyawan tidak ditemukan.';
                header('Location: /tambah/karyawan');
                exit;
            }

            $stmt = $this->db->prepare("
                DELETE FROM pegawai
                WHERE id_pegawai = ?
            ");

            $berhasil = $stmt->execute([$idPegawai]);

            if ($berhasil && $stmt->rowCount() > 0) {
                $_SESSION['success'] = 'Karyawan ' . $pegawai['nama'] . ' berhasil dihapus.';

                catatLog(
                    $this->db,
                    'Pegawai',
                    'Hapus Karyawan',
                    'USER-DELETE',
                    ($_SESSION['nama'] ?? 'Superadmin') .
                        ' menghapus akun karyawan ' . $pegawai['nama'] .
                        ' (Role: ' . $pegawai['role'] .
                        ', ID: ' . $idPegawai . ').'
                );
            } else {
                $_SESSION['error'] = 'Karyawan gagal dihapus.';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Karyawan gagal dihapus karena masih memiliki data yang terhubung atau terjadi kesalahan database.';
        }

        header('Location: /tambah/karyawan');
        exit;
    }
}
