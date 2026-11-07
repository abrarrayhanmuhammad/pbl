<?php

require_once __DIR__ . '/../models/Pegawai.php';

use PHPMailer\PHPMailer\PHPMailer;

class AuthController
{
    private Pegawai $pegawaiModel;
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->pegawaiModel = new Pegawai($db);
    }

    private function createCsrfToken(): void
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    private function verifyCsrfToken(): bool
    {
        return isset($_SESSION['csrf_token'], $_POST['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
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

            catatLog(
                $this->db,
                'Autentikasi',
                'Login Gagal',
                'AUTH-LOGIN-GAGAL',
                'Percobaan login gagal untuk username "' . mb_substr($username, 0, 30) . '".',
                $pegawai ? (int) $pegawai['id_pegawai'] : null
            );
            
            header('Location: /login');
            exit;
        }

        session_regenerate_id(true);

        $_SESSION['id_pegawai'] = $pegawai['id_pegawai'];
        $_SESSION['nama'] = $pegawai['nama'];
        $_SESSION['role'] = $pegawai['role'];

        catatLog(
            $this->db,
            'Autentikasi',
            'Login Berhasil',
            'AUTH-LOGIN',
            $pegawai['nama'] . ' berhasil login ke sistem.'
        );

        header('Location: /dashboard');

        exit;
    }

    public function forgotPassword(): void
    {
        if (!$this->verifyCsrfToken()) {
            $_SESSION['error'] = 'Permintaan tidak valid. Silakan coba kembali.';
            header('Location: /login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Format email tidak valid.';
            header('Location: /login');
            exit;
        }

        $pegawai = $this->pegawaiModel->findByEmail($email);

        if ($pegawai) {
            try {
                $token = bin2hex(random_bytes(32));
                $tokenHash = hash('sha256', $token);

                $this->pegawaiModel->createPasswordReset(
                    (int) $pegawai['id_pegawai'],
                    $tokenHash
                );

                $this->sendResetEmail($email, $pegawai['nama'], $token);
            } catch (Throwable $e) {
                error_log('Forgot Password Error: ' . $e->getMessage());
            }
        }

        $_SESSION['success'] = 'Jika email terdaftar, tautan reset password akan dikirim ke email tersebut.';
        header('Location: /login');
        exit;
    }

    private function sendResetEmail(string $email, string $nama, string $token): void
    {
        require_once __DIR__ . '/../../vendor/autoload.php';

        $config = require __DIR__ . '/../config/mail.php';

        $resetUrl = rtrim($config['app_url'], '/'). '/reset-password?token=' . urlencode($token);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $config['port'];
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($email, $nama);
        $mail->isHTML(true);
        $mail->Subject = 'Reset Password KickDropShoe';

        $mail->Body = '
            <div style="font-family:Arial,sans-serif;max-width:600px;margin:auto;padding:25px;color:#1e293b;">
                <h2 style="color:#0f172a;">Reset Password KickDropShoe</h2>
                <p>Halo ' . htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') . ',</p>
                <p>Kami menerima permintaan untuk mengatur ulang password akun KickDropShoe kamu.</p>
                <p>Tekan tombol berikut untuk membuat password baru:</p>
                <p style="margin:30px 0;">
                    <a href="' . htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8') . '"
                       style="background:#facc15;color:#0f172a;padding:14px 24px;text-decoration:none;border-radius:8px;font-weight:bold;">
                       Reset Password
                    </a>
                </p>
                <p>Tautan ini hanya berlaku selama 30 menit dan hanya dapat digunakan satu kali.</p>
                <p>Jika kamu tidak meminta reset password, abaikan email ini.</p>
                <p>Salam,<br><strong>KickDropShoe</strong></p>
            </div>
        ';

        $mail->AltBody = "Halo $nama, buka tautan berikut untuk reset password: $resetUrl. Tautan berlaku 30 menit.";

        $mail->send();
    }

    public function showResetPassword(): void
    {
        $token = $_GET['token'] ?? '';

        if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
            $_SESSION['error'] = 'Tautan reset password tidak valid.';
            header('Location: /login');
            exit;
        }

        $reset = $this->pegawaiModel->findValidPasswordReset(
            hash('sha256', $token)
        );

        if (!$reset) {
            $_SESSION['error'] = 'Tautan reset password sudah kedaluwarsa atau tidak valid.';
            header('Location: /login');
            exit;
        }

        $this->createCsrfToken();
        require_once __DIR__ . '/../views/auth/reset_password.php';
    }

    public function resetPassword(): void
    {
        $token = $_POST['token'] ?? '';

        if (!$this->verifyCsrfToken()) {
            $_SESSION['error'] = 'Permintaan tidak valid. Silakan coba kembali.';
            header('Location: /login');
            exit;
        }

        if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
            $_SESSION['error'] = 'Tautan reset password tidak valid.';
            header('Location: /login');
            exit;
        }

        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (strlen($password) < 8) {
            $_SESSION['error'] = 'Password minimal harus 8 karakter.';
            header('Location: /reset-password?token=' . urlencode($token));
            exit;
        }

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Konfirmasi password tidak cocok.';
            header('Location: /reset-password?token=' . urlencode($token));
            exit;
        }

        $reset = $this->pegawaiModel->findValidPasswordReset(
            hash('sha256', $token)
        );

        if (!$reset) {
            $_SESSION['error'] = 'Tautan reset password sudah kedaluwarsa atau tidak valid.';
            header('Location: /login');
            exit;
        }

        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $this->pegawaiModel->updatePasswordAndDeleteReset(
                (int) $reset['id_reset'],
                (int) $reset['id_pegawai'],
                $passwordHash
            );

            catatLog(
                $this->db,
                'Autentikasi',
                'Reset Password',
                'AUTH-RESET',
                'Password akun berhasil diatur ulang lewat tautan email.',
                (int) $reset['id_pegawai']
            );

            $_SESSION['success'] = 'Password berhasil diubah. Silakan login menggunakan password baru.';
            header('Location: /login');
            exit;
        } catch (Throwable $e) {
            error_log('Reset Password Error: ' . $e->getMessage());
            $_SESSION['error'] = 'Terjadi kesalahan saat mengubah password.';
            header('Location: /reset-password?token=' . urlencode($token));
            exit;
        }
    }

    public function logout(): void
    {
        if (isset($_SESSION['id_pegawai'])) {
                catatLog(
                    $this->db,
                    'Autentikasi',
                    'Logout',
                    'AUTH-LOGOUT',
                    ($_SESSION['nama'] ?? 'Pengguna') . ' keluar dari sistem.'
                );
            }
            
        session_unset();
        session_destroy();

        header('Location: /login');
        exit;
    }
}
