<?php
function catatLog(PDO $pdo, string $kategori, string $aksi, ?string $referensi, string $keterangan, ?int $idPegawai = null): void
{
    try {
        $stmt = $pdo->prepare(
            'INSERT INTO log_aktivitas (id_pegawai, kategori, aksi, referensi, keterangan, ip_address)
             VALUES (:idPegawai, :kategori, :aksi, :referensi, :keterangan, :ipAddress)'
        );
        $stmt->execute([
            'idPegawai'  => $idPegawai ?? $_SESSION['id_pegawai'] ?? null,
            'kategori'   => $kategori,
            'aksi'       => $aksi,
            'referensi'  => $referensi,
            'keterangan' => $keterangan,
            'ipAddress'  => $_SERVER['REMOTE_ADDR'] ?? null,
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