<?php

function getClientIp(): string
{
    $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    // Isi dengan IP proxy/CDN yang dipercaya. Kosong = pakai REMOTE_ADDR.
    $trustedProxies = [];

    if (in_array($remoteAddr, $trustedProxies, true)) {
        $forwarded = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
        $ip = trim(explode(',', $forwarded)[0]);

        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
    }

    return $remoteAddr;
}

function ambilDeviceHash(): ?string
{
    $token = $_COOKIE['deviceId'] ?? '';

    if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
        return null;
    }

    return hash('sha256', $token);
}

function terbitkanDeviceToken(): void
{
    $token = $_COOKIE['deviceId'] ?? '';

    if (preg_match('/^[a-f0-9]{64}$/', $token)) {
        return;
    }

    $token = bin2hex(random_bytes(32));
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';

    setcookie('deviceId', $token, [
        'expires'  => time() + 60 * 60 * 24 * 365,
        'path'     => '/',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    $_COOKIE['deviceId'] = $token;
}

function catatLog(PDO $pdo, string $kategori, string $aksi, ?string $referensi, string $keterangan, ?int $idPegawai = null): void
{
    try {
        $stmt = $pdo->prepare(
            'INSERT INTO log_aktivitas (id_pegawai, kategori, aksi, referensi, keterangan, ip_address, user_agent, device_hash)
             VALUES (:idPegawai, :kategori, :aksi, :referensi, :keterangan, :ipAddress, :userAgent, :deviceHash)'
        );
        $stmt->execute([
            'idPegawai'  => $idPegawai ?? $_SESSION['id_pegawai'] ?? null,
            'kategori'   => $kategori,
            'aksi'       => $aksi,
            'referensi'  => $referensi,
            'keterangan' => $keterangan,
            'ipAddress'  => getClientIp(),
            'userAgent'  => mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '-', 0, 255),
            'deviceHash' => ambilDeviceHash(),
        ]);
    } catch (PDOException $e) {
        error_log('Gagal catat log: ' . $e->getMessage());
    }
}

function ambilKodePesanan(PDO $db, int $idPesanan): string
{
    $stmt = $db->prepare('SELECT kode_pesanan FROM pesanan WHERE id_pesanan = :id');
    $stmt->execute(['id' => $idPesanan]);
    $kode = $stmt->fetchColumn();

    return $kode !== false ? (string) $kode : 'ID ' . $idPesanan;
}