<?php

require_once __DIR__ . '/../models/Pesanan.php';
require_once __DIR__ . '/../models/DropPoint.php';
require_once __DIR__ . '/../models/Layanan.php';
require_once __DIR__ . '/../models/TreatmentTambahan.php';
require_once __DIR__ . '/../models/Customer.php';

class PesananController
{
    private PDO $db;
    private Pesanan $pesananModel;
    private DropPoint $dropPointModel;
    private Layanan $layananModel;
    private TreatmentTambahan $treatmentTambahanModel;
    private Customer $customerModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->pesananModel = new Pesanan($db);
        $this->dropPointModel = new DropPoint($db);
        $this->layananModel = new Layanan($db);
        $this->treatmentTambahanModel = new TreatmentTambahan($db);
        $this->customerModel = new Customer($db);
    }

    public function index(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        $dataPesanan = $this->pesananModel->getAllPesanan();

        require_once __DIR__ . '/../views/pesanan/pesanan.php';
    }

    public function tambahPesanan(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        $dropPoint = $this->dropPointModel->getDropPoint();
        $layanan = $this->layananModel->getLayanan();
        $getAllLayanan = $this->layananModel->getAllLayanan();
        $getAllPaket = $this->layananModel->getAllPaket();
        $treatment = $this->treatmentTambahanModel->getTreatmentTambahan();
        $customer = $this->customerModel->getAllCustomer();

        $treatmentKecepatan = $treatment['kecepatan'];
        $treatmentTambahan = $treatment['tambahan'];

        require_once __DIR__ . '/../views/pesanan/tambah_pesanan.php';
    }


    public function simpanPesanan(): void
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
            exit('Token keamanan tidak valid.');
        }

        $nama = trim($_POST['nama_pelanggan'] ?? '');
        $noWa = trim($_POST['no_wa'] ?? '');
        $tglMasuk = $_POST['tgl_masuk'] ?? '';
        $tglSelesai = $_POST['tgl_selesai'] ?? null;
        $metodeBayar = $_POST['metode_pembayaran'] ?? '';
        $dropPoint = $_POST['drop_point'] ?? '';

        $namaBarang = $_POST['nama_barang'] ?? [];
        $warnaBarang = $_POST['warna_barang'] ?? [];
        $layananIds = $_POST['layanan_id'] ?? [];
        $tipeLayanan = $_POST['tipe_layanan'] ?? [];
        $speed = $_POST['speed'] ?? [];
        $treatments = $_POST['treatment_tambahan'] ?? [];
        $catatan = $_POST['catatan'] ?? [];

        $uploadedFiles = [];

        $validDate = static function ($date): bool {
            if (!is_string($date) || $date === '') {
                return false;
            }

            $d = DateTime::createFromFormat('!Y-m-d', $date);

            return $d !== false && $d->format('Y-m-d') === $date;
        };

        if (
            $nama === '' ||
            strlen($nama) > 50 ||
            !preg_match('/^[0-9+\-\s]{8,15}$/', $noWa) ||
            !$validDate($tglMasuk)
        ) {
            exit('Nama, nomor WhatsApp, atau tanggal masuk tidak valid.');
        }

        if ($tglSelesai !== null && $tglSelesai !== '') {
            if (!$validDate($tglSelesai) || $tglSelesai < $tglMasuk) {
                exit('Tanggal selesai tidak valid.');
            }
        } else {
            $tglSelesai = null;
        }

        if (!in_array($metodeBayar, ['Cash', 'Transfer', 'QRIS'], true)) {
            exit('Metode pembayaran tidak valid.');
        }

        if (
            !is_array($namaBarang) ||
            count($namaBarang) < 1 ||
            count($namaBarang) > 20 ||
            !is_array($warnaBarang) ||
            !is_array($layananIds) ||
            !is_array($tipeLayanan) ||
            !is_array($speed) ||
            !is_array($treatments) ||
            !is_array($catatan)
        ) {
            exit('Data barang tidak valid.');
        }

        $itemIds = array_keys($namaBarang);

        foreach ([$warnaBarang, $layananIds, $tipeLayanan, $speed] as $field) {
            if (array_keys($field) !== $itemIds) {
                exit('Data barang tidak lengkap.');
            }
        }

        foreach ($itemIds as $id) {
            if (
                !isset($warnaBarang[$id], $layananIds[$id], $tipeLayanan[$id], $speed[$id]) ||
                trim((string) $namaBarang[$id]) === '' ||
                trim((string) $warnaBarang[$id]) === '' ||
                !ctype_digit((string) $layananIds[$id]) ||
                (int) $layananIds[$id] < 1 ||
                !in_array($tipeLayanan[$id], ['layanan', 'package'], true) ||
                !is_string($speed[$id]) ||
                !is_array($treatments[$id] ?? []) ||
                !is_string($catatan[$id] ?? '')
            ) {
                exit('Data barang tidak valid.');
            }

            foreach (($treatments[$id] ?? []) as $treatmentId) {
                if (
                    !is_scalar($treatmentId) ||
                    !ctype_digit((string) $treatmentId) ||
                    (int) $treatmentId < 1
                ) {
                    exit('Treatment tambahan tidak valid.');
                }
            }
        }

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare(
                'SELECT id_customer, nama FROM customer WHERE no_hp = :no_hp LIMIT 1'
            );
            $stmt->execute(['no_hp' => $noWa]);
            $idCustomer = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($idCustomer) {
                if (strcasecmp(trim($idCustomer['nama']), trim($nama)) !== 0) {
                    $_SESSION['error'] = 'Nomor HP sudah terdaftar atas nama pelanggan lain.';
                    header('Location: /pesanan/tambah');
                    exit;
                }

                $idCustomer = (int) $idCustomer;
            } else {
                $stmt = $this->db->prepare(
                    'INSERT INTO customer (nama, no_hp)
                 VALUES (:nama, :no_hp)
                 RETURNING id_customer'
                );
                $stmt->execute([
                    'nama' => $nama,
                    'no_hp' => $noWa
                ]);
                $idCustomer = (int) $stmt->fetchColumn();
            }

            $stmt = $this->db->prepare(
                "INSERT INTO pesanan
                (kode_pesanan, id_pegawai, id_customer, id_drop_point, tanggal_masuk, status,
                 metode_bayar, status_bayar, tanggal_selesai,
                 total_bayar, catatan, created_at)
             VALUES
                (:kode, :pegawai, :customer, :dropPoint, :masuk, 'Menunggu',
                 :metode, 'Belum Bayar', :selesai,
                 0, NULL, CURRENT_TIMESTAMP)
             RETURNING id_pesanan"
            );

            $kodePesanan = '#' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

            $stmt->execute([
                'kode' => $kodePesanan,
                'pegawai' => $_SESSION['id_pegawai'],
                'customer' => $idCustomer,
                'dropPoint' => $dropPoint,
                'masuk' => $tglMasuk,
                'metode' => $metodeBayar,
                'selesai' => $tglSelesai
            ]);

            $idPesanan = (int) $stmt->fetchColumn();

            $totalBayar = 0;

            $stmtLayanan = $this->db->prepare(
                'SELECT id_layanan, harga_min
                FROM layanan
                WHERE id_layanan = :id'
            );

            $stmtPaket = $this->db->prepare(
                'SELECT id_paket, harga
                FROM paket
                WHERE id_paket = :id'
            );

            $stmtTreatment = $this->db->prepare(
                'SELECT id_treatment_tambahan, nama_treatment, harga_min
                FROM treatment_tambahan
                WHERE id_treatment_tambahan = :id'
            );

            $stmtDetail = $this->db->prepare(
                "INSERT INTO detail_pesanan
                (
                    id_pesanan,
                    id_layanan,
                    id_paket,
                    nama_barang,
                    warna,
                    jumlah,
                    harga_satuan,
                    subtotal,
                    status_pengerjaan,
                    foto_before,
                    catatan
                )
                VALUES
                (
                    :pesanan,
                    :layanan,
                    :paket,
                    :barang,
                    :warna,
                    1,
                    :harga,
                    :subtotal,
                    'Menunggu',
                    :foto,
                    :catatan
                )
                RETURNING id_detail_pesanan"
            );

            $stmtDetailTreatment = $this->db->prepare(
                'INSERT INTO detail_treatment_tambahan
                (id_detail_pesanan, id_treatment_tambahan, harga)
                VALUES (:detail, :treatment, :harga)'
            );

            foreach ($itemIds as $id) {
                $referensiId = (int) $layananIds[$id];
                $tipe = $tipeLayanan[$id];

                $idLayanan = null;
                $idPaket = null;
                $hargaTambahan = [];
                $selectedTreatments = [];


                if ($tipe === 'layanan') {
                    $stmtLayanan->execute([
                        'id' => $referensiId
                    ]);

                    $referensiRow = $stmtLayanan->fetch(PDO::FETCH_ASSOC);

                    if (!$referensiRow) {
                        throw new RuntimeException('Layanan tidak ditemukan.');
                    }

                    $idLayanan = $referensiId;
                    $hargaLayanan = (int) $referensiRow['harga_min'];
                } elseif ($tipe === 'package') {
                    $stmtPaket->execute([
                        'id' => $referensiId
                    ]);

                    $referensiRow = $stmtPaket->fetch(PDO::FETCH_ASSOC);

                    if (!$referensiRow) {
                        throw new RuntimeException('Paket tidak ditemukan.');
                    }

                    $idPaket = $referensiId;
                    $hargaLayanan = (int) $referensiRow['harga'];
                } else {
                    throw new RuntimeException('Tipe layanan tidak valid.');
                }

                if ($speed[$id] !== 'reguler') {
                    $selectedTreatments[] = (int) $speed[$id];
                }

                foreach (($treatments[$id] ?? []) as $treatmentId) {

                    $treatmentId = (int) $treatmentId;

                    if (!in_array($treatmentId, $selectedTreatments, true)) {
                        $selectedTreatments[] = $treatmentId;
                    }
                }

                foreach ($selectedTreatments as $treatmentId) {
                    $stmtTreatment->execute([
                        'id' => $treatmentId
                    ]);

                    $row = $stmtTreatment->fetch(PDO::FETCH_ASSOC);

                    if (!$row) {
                        throw new RuntimeException('Treatment tidak ditemukan.');
                    }

                    $hargaTambahan[$treatmentId] = (int) $row['harga_min'];
                }

                $subtotal = $hargaLayanan + array_sum($hargaTambahan);


                $fotoPath = null;
                $files = $_FILES['foto_before'] ?? null;

                if (
                    !is_array($files) ||
                    !isset($files['error'][$id]) ||
                    $files['error'][$id] === UPLOAD_ERR_NO_FILE
                ) {
                    throw new RuntimeException(
                        'Foto Before wajib diunggah untuk setiap barang.'
                    );
                }

                $error = $files['error'][$id];

                if ($error !== UPLOAD_ERR_OK) {
                    throw new RuntimeException('Upload foto gagal.');
                }

                $tmpName = $files['tmp_name'][$id];
                $size = (int) $files['size'][$id];

                if (
                    !is_uploaded_file($tmpName) ||
                    $size < 1 ||
                    $size > 5 * 1024 * 1024
                ) {
                    throw new RuntimeException(
                        'Foto tidak valid atau melebihi 5 MB.'
                    );
                }

                $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmpName);

                $extensions = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp'
                ];

                if (!isset($extensions[$mime])) {
                    throw new RuntimeException(
                        'Foto harus JPG, PNG, atau WEBP.'
                    );
                }

                $folder = dirname(__DIR__, 2) . '/public/uploads/pesanan/before';

                if (!is_dir($folder) && !mkdir($folder, 0755, true)) {
                    throw new RuntimeException(
                        'Folder upload gagal dibuat.'
                    );
                }

                $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];

                $destination = $folder . '/' . $filename;

                if (!move_uploaded_file($tmpName, $destination)) {
                    throw new RuntimeException('Foto gagal disimpan.');
                }

                $fotoPath = 'uploads/pesanan/before/' . $filename;
                $uploadedFiles[] = $destination;


                $stmtDetail->execute([
                    'pesanan' => $idPesanan,
                    'layanan' => $idLayanan,
                    'paket' => $idPaket,
                    'barang' => trim($namaBarang[$id]),
                    'warna' => trim($warnaBarang[$id]),
                    'harga' => $hargaLayanan,
                    'subtotal' => $subtotal,
                    'foto' => $fotoPath,
                    'catatan' => trim($catatan[$id] ?? '') ?: null
                ]);

                $idDetail = (int) $stmtDetail->fetchColumn();

                foreach ($hargaTambahan as $treatmentId => $harga) {
                    $stmtDetailTreatment->execute([
                        'detail' => $idDetail,
                        'treatment' => $treatmentId,
                        'harga' => $harga
                    ]);
                }

                $totalBayar += $subtotal;
            }

            $stmt = $this->db->prepare(
                'UPDATE pesanan
                SET total_bayar = :total
                WHERE id_pesanan = :id'
            );

            $stmt->execute([
                'total' => $totalBayar,
                'id' => $idPesanan
            ]);

            $this->db->commit();

            unset($_SESSION['csrf_token']);

            $_SESSION['success'] = 'Pesanan berhasil ditambahkan.';
            header('Location: /pesanan');
            exit;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            foreach ($uploadedFiles as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }

            echo '<h2>Pesanan Gagal Disimpan</h2>';
            echo '<p><strong>Pesan error:</strong> ' .
                htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') .
                '</p>';
            echo '<p><strong>File:</strong> ' .
                htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8') .
                '</p>';
            echo '<p><strong>Baris:</strong> ' . $e->getLine() . '</p>';

            error_log($e->getMessage());

            exit;
        }
    }

    #Funcitoni tambah data disesuaikan dengan neon dan cloudinary
    // public function simpanPesanan(): void
    // {
    //     if (!isset($_SESSION['id_pegawai'])) {
    //         header('Location: /login');
    //         exit;
    //     }

    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         http_response_code(405);
    //         exit('Metode tidak diizinkan.');
    //     }

    //     if (
    //         empty($_SESSION['csrf_token']) ||
    //         !is_string($_POST['csrf_token'] ?? null) ||
    //         !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    //     ) {
    //         exit('Token keamanan tidak valid.');
    //     }

    //     $nama = trim($_POST['nama_pelanggan'] ?? '');
    //     $noWa = trim($_POST['no_wa'] ?? '');
    //     $tglMasuk = $_POST['tgl_masuk'] ?? '';
    //     $tglSelesai = $_POST['tgl_selesai'] ?? null;
    //     $metodeBayar = $_POST['metode_pembayaran'] ?? '';
    //     $dropPoint = $_POST['drop_point'] ?? '';

    //     $namaBarang = $_POST['nama_barang'] ?? [];
    //     $warnaBarang = $_POST['warna_barang'] ?? [];
    //     $layananIds = $_POST['layanan_id'] ?? [];
    //     $tipeLayanan = $_POST['tipe_layanan'] ?? [];
    //     $speed = $_POST['speed'] ?? [];
    //     $treatments = $_POST['treatment_tambahan'] ?? [];
    //     $catatan = $_POST['catatan'] ?? [];

    //     $uploadedCloudinaryIds = [];
    //     $cloudinary = null;

    //     $validDate = static function ($date): bool {
    //         if (!is_string($date) || $date === '') {
    //             return false;
    //         }

    //         $d = DateTime::createFromFormat('!Y-m-d', $date);

    //         return $d !== false && $d->format('Y-m-d') === $date;
    //     };

    //     if (
    //         $nama === '' ||
    //         strlen($nama) > 50 ||
    //         !preg_match('/^[0-9+\-\s]{8,15}$/', $noWa) ||
    //         !$validDate($tglMasuk)
    //     ) {
    //         exit('Nama, nomor WhatsApp, atau tanggal masuk tidak valid.');
    //     }

    //     if ($tglSelesai !== null && $tglSelesai !== '') {
    //         if (!$validDate($tglSelesai) || $tglSelesai < $tglMasuk) {
    //             exit('Tanggal selesai tidak valid.');
    //         }
    //     } else {
    //         $tglSelesai = null;
    //     }

    //     if (!in_array($metodeBayar, ['Cash', 'Transfer', 'QRIS'], true)) {
    //         exit('Metode pembayaran tidak valid.');
    //     }

    //     if (
    //         !is_array($namaBarang) ||
    //         count($namaBarang) < 1 ||
    //         count($namaBarang) > 20 ||
    //         !is_array($warnaBarang) ||
    //         !is_array($layananIds) ||
    //         !is_array($tipeLayanan) ||
    //         !is_array($speed) ||
    //         !is_array($treatments) ||
    //         !is_array($catatan)
    //     ) {
    //         exit('Data barang tidak valid.');
    //     }

    //     $itemIds = array_keys($namaBarang);

    //     foreach ([$warnaBarang, $layananIds, $tipeLayanan, $speed] as $field) {
    //         if (array_keys($field) !== $itemIds) {
    //             exit('Data barang tidak lengkap.');
    //         }
    //     }

    //     foreach ($itemIds as $id) {
    //         if (
    //             !isset($warnaBarang[$id], $layananIds[$id], $tipeLayanan[$id], $speed[$id]) ||
    //             trim((string) $namaBarang[$id]) === '' ||
    //             trim((string) $warnaBarang[$id]) === '' ||
    //             !ctype_digit((string) $layananIds[$id]) ||
    //             (int) $layananIds[$id] < 1 ||
    //             !in_array($tipeLayanan[$id], ['layanan', 'package'], true) ||
    //             !is_string($speed[$id]) ||
    //             !is_array($treatments[$id] ?? []) ||
    //             !is_string($catatan[$id] ?? '')
    //         ) {
    //             exit('Data barang tidak valid.');
    //         }

    //         foreach (($treatments[$id] ?? []) as $treatmentId) {
    //             if (
    //                 !is_scalar($treatmentId) ||
    //                 !ctype_digit((string) $treatmentId) ||
    //                 (int) $treatmentId < 1
    //             ) {
    //                 exit('Treatment tambahan tidak valid.');
    //             }
    //         }
    //     }

    //     try {
    //         $cloudinary = require __DIR__ . '/../config/cloudinary.php';

    //         $this->db->beginTransaction();

    //         $stmt = $this->db->prepare(
    //             'SELECT id_customer, nama FROM customer WHERE no_hp = :no_hp LIMIT 1'
    //         );
    //         $stmt->execute(['no_hp' => $noWa]);
    //         $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    //         if ($customer) {
    //             if (strcasecmp(trim($customer['nama']), trim($nama)) !== 0) {
    //                 throw new RuntimeException(
    //                     'Nomor HP sudah terdaftar atas nama pelanggan lain.'
    //                 );
    //             }

    //             $idCustomer = (int) $customer['id_customer'];
    //         } else {
    //             $stmt = $this->db->prepare(
    //                 'INSERT INTO customer (nama, no_hp)
    //              VALUES (:nama, :no_hp)
    //              RETURNING id_customer'
    //             );
    //             $stmt->execute([
    //                 'nama' => $nama,
    //                 'no_hp' => $noWa
    //             ]);
    //             $idCustomer = (int) $stmt->fetchColumn();
    //         }

    //         $stmt = $this->db->prepare(
    //             "INSERT INTO pesanan
    //         (
    //             kode_pesanan,
    //             id_pegawai,
    //             id_customer,
    //             id_drop_point,
    //             tanggal_masuk,
    //             status,
    //             metode_bayar,
    //             status_bayar,
    //             tanggal_selesai,
    //             total_bayar,
    //             catatan,
    //             created_at
    //         )
    //         VALUES
    //         (
    //             :kode,
    //             :pegawai,
    //             :customer,
    //             :dropPoint,
    //             :masuk,
    //             'Menunggu',
    //             :metode,
    //             'Belum Bayar',
    //             :selesai,
    //             0,
    //             NULL,
    //             CURRENT_TIMESTAMP
    //         )
    //         RETURNING id_pesanan"
    //         );

    //         $kodePesanan = '#' . str_pad(
    //             (string) random_int(0, 9999),
    //             4,
    //             '0',
    //             STR_PAD_LEFT
    //         );

    //         $stmt->execute([
    //             'kode' => $kodePesanan,
    //             'pegawai' => $_SESSION['id_pegawai'],
    //             'customer' => $idCustomer,
    //             'dropPoint' => $dropPoint,
    //             'masuk' => $tglMasuk,
    //             'metode' => $metodeBayar,
    //             'selesai' => $tglSelesai
    //         ]);

    //         $idPesanan = (int) $stmt->fetchColumn();

    //         $totalBayar = 0;

    //         $stmtLayanan = $this->db->prepare(
    //             'SELECT id_layanan, harga_min
    //          FROM layanan
    //          WHERE id_layanan = :id'
    //         );

    //         $stmtPaket = $this->db->prepare(
    //             'SELECT id_paket, harga
    //          FROM paket
    //          WHERE id_paket = :id'
    //         );

    //         $stmtTreatment = $this->db->prepare(
    //             'SELECT id_treatment_tambahan, nama_treatment, harga_min
    //          FROM treatment_tambahan
    //          WHERE id_treatment_tambahan = :id'
    //         );

    //         $stmtDetail = $this->db->prepare(
    //             "INSERT INTO detail_pesanan
    //         (
    //             id_pesanan,
    //             id_layanan,
    //             id_paket,
    //             nama_barang,
    //             warna,
    //             jumlah,
    //             harga_satuan,
    //             subtotal,
    //             status_pengerjaan,
    //             foto_before,
    //             catatan
    //         )
    //         VALUES
    //         (
    //             :pesanan,
    //             :layanan,
    //             :paket,
    //             :barang,
    //             :warna,
    //             1,
    //             :harga,
    //             :subtotal,
    //             'Menunggu',
    //             :foto,
    //             :catatan
    //         )
    //         RETURNING id_detail_pesanan"
    //         );

    //         $stmtDetailTreatment = $this->db->prepare(
    //             'INSERT INTO detail_treatment_tambahan
    //         (id_detail_pesanan, id_treatment_tambahan, harga)
    //         VALUES (:detail, :treatment, :harga)'
    //         );

    //         foreach ($itemIds as $id) {
    //             $referensiId = (int) $layananIds[$id];
    //             $tipe = $tipeLayanan[$id];

    //             $idLayanan = null;
    //             $idPaket = null;
    //             $hargaTambahan = [];
    //             $selectedTreatments = [];

    //             if ($tipe === 'layanan') {
    //                 $stmtLayanan->execute([
    //                     'id' => $referensiId
    //                 ]);

    //                 $referensiRow = $stmtLayanan->fetch(PDO::FETCH_ASSOC);

    //                 if (!$referensiRow) {
    //                     throw new RuntimeException('Layanan tidak ditemukan.');
    //                 }

    //                 $idLayanan = $referensiId;
    //                 $hargaLayanan = (int) $referensiRow['harga_min'];
    //             } elseif ($tipe === 'package') {
    //                 $stmtPaket->execute([
    //                     'id' => $referensiId
    //                 ]);

    //                 $referensiRow = $stmtPaket->fetch(PDO::FETCH_ASSOC);

    //                 if (!$referensiRow) {
    //                     throw new RuntimeException('Paket tidak ditemukan.');
    //                 }

    //                 $idPaket = $referensiId;
    //                 $hargaLayanan = (int) $referensiRow['harga'];
    //             } else {
    //                 throw new RuntimeException('Tipe layanan tidak valid.');
    //             }

    //             if ($speed[$id] !== 'reguler') {
    //                 $selectedTreatments[] = (int) $speed[$id];
    //             }

    //             foreach (($treatments[$id] ?? []) as $treatmentId) {
    //                 $treatmentId = (int) $treatmentId;

    //                 if (!in_array($treatmentId, $selectedTreatments, true)) {
    //                     $selectedTreatments[] = $treatmentId;
    //                 }
    //             }

    //             foreach ($selectedTreatments as $treatmentId) {
    //                 $stmtTreatment->execute([
    //                     'id' => $treatmentId
    //                 ]);

    //                 $row = $stmtTreatment->fetch(PDO::FETCH_ASSOC);

    //                 if (!$row) {
    //                     throw new RuntimeException('Treatment tidak ditemukan.');
    //                 }

    //                 $hargaTambahan[$treatmentId] = (int) $row['harga_min'];
    //             }

    //             $subtotal = $hargaLayanan + array_sum($hargaTambahan);

    //             $fotoPath = null;
    //             $files = $_FILES['foto_before'] ?? null;

    //             if (
    //                 !is_array($files) ||
    //                 !isset($files['error'][$id]) ||
    //                 $files['error'][$id] === UPLOAD_ERR_NO_FILE
    //             ) {
    //                 throw new RuntimeException(
    //                     'Foto Before wajib diunggah untuk setiap barang.'
    //                 );
    //             }

    //             $error = $files['error'][$id];

    //             if ($error !== UPLOAD_ERR_OK) {
    //                 throw new RuntimeException('Upload foto gagal.');
    //             }

    //             $tmpName = $files['tmp_name'][$id] ?? '';
    //             $size = (int) ($files['size'][$id] ?? 0);

    //             if (
    //                 !is_string($tmpName) ||
    //                 !is_uploaded_file($tmpName) ||
    //                 $size < 1 ||
    //                 $size > 5 * 1024 * 1024
    //             ) {
    //                 throw new RuntimeException(
    //                     'Foto tidak valid atau melebihi 5 MB.'
    //                 );
    //             }

    //             $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmpName);

    //             $extensions = [
    //                 'image/jpeg' => 'jpg',
    //                 'image/png' => 'png',
    //                 'image/webp' => 'webp'
    //             ];

    //             if (!isset($extensions[$mime])) {
    //                 throw new RuntimeException(
    //                     'Foto harus JPG, PNG, atau WEBP.'
    //                 );
    //             }

    //             $result = $cloudinary->uploadApi()->upload(
    //                 $tmpName,
    //                 [
    //                     'resource_type' => 'image',
    //                     'folder' => 'kickdropshoe/pesanan/before',
    //                     'public_id' => bin2hex(random_bytes(16)),
    //                     'format' => $extensions[$mime]
    //                 ]
    //             );

    //             if (
    //                 empty($result['secure_url']) ||
    //                 empty($result['public_id'])
    //             ) {
    //                 throw new RuntimeException(
    //                     'Foto gagal diunggah ke Cloudinary.'
    //                 );
    //             }

    //             $fotoPath = $result['secure_url'];

    //             $uploadedCloudinaryIds[] = $result['public_id'];

    //             $stmtDetail->execute([
    //                 'pesanan' => $idPesanan,
    //                 'layanan' => $idLayanan,
    //                 'paket' => $idPaket,
    //                 'barang' => trim($namaBarang[$id]),
    //                 'warna' => trim($warnaBarang[$id]),
    //                 'harga' => $hargaLayanan,
    //                 'subtotal' => $subtotal,
    //                 'foto' => $fotoPath,
    //                 'catatan' => trim($catatan[$id] ?? '') ?: null
    //             ]);

    //             $idDetail = (int) $stmtDetail->fetchColumn();

    //             foreach ($hargaTambahan as $treatmentId => $harga) {
    //                 $stmtDetailTreatment->execute([
    //                     'detail' => $idDetail,
    //                     'treatment' => $treatmentId,
    //                     'harga' => $harga
    //                 ]);
    //             }

    //             $totalBayar += $subtotal;
    //         }

    //         $stmt = $this->db->prepare(
    //             'UPDATE pesanan
    //          SET total_bayar = :total
    //          WHERE id_pesanan = :id'
    //         );

    //         $stmt->execute([
    //             'total' => $totalBayar,
    //             'id' => $idPesanan
    //         ]);

    //         $this->db->commit();

    //         unset($_SESSION['csrf_token']);

    //         $_SESSION['success'] = 'Pesanan berhasil ditambahkan.';
    //         header('Location: /pesanan');
    //         exit;
    //     } catch (Throwable $e) {
    //         if ($this->db->inTransaction()) {
    //             $this->db->rollBack();
    //         }

    //         if ($cloudinary !== null) {
    //             foreach ($uploadedCloudinaryIds as $publicId) {
    //                 try {
    //                     $cloudinary->uploadApi()->destroy(
    //                         $publicId,
    //                         [
    //                             'resource_type' => 'image'
    //                         ]
    //                     );
    //                 } catch (Throwable $deleteError) {
    //                     error_log(
    //                         'Gagal menghapus foto Cloudinary: ' .
    //                             $deleteError->getMessage()
    //                     );
    //                 }
    //             }
    //         }

    //         echo '<h2>Pesanan Gagal Disimpan</h2>';
    //         echo '<p><strong>Pesan error:</strong> ' .
    //             htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') .
    //             '</p>';
    //         echo '<p><strong>File:</strong> ' .
    //             htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8') .
    //             '</p>';
    //         echo '<p><strong>Baris:</strong> ' . $e->getLine() . '</p>';

    //         error_log($e->getMessage());

    //         exit;
    //     }
    // }



    public function editPesanan(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            header('Location: /pesanan');
            exit;
        }

        $idPesanan = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$idPesanan || $idPesanan < 1) {
            header('Location: /pesanan');
            exit;
        }

        try {
            $sql = "
                SELECT
                    p.id_pesanan,
                    p.kode_pesanan,
                    p.tanggal_masuk,
                    p.tanggal_selesai,
                    p.status,
                    p.status_bayar,
                    p.metode_bayar,
                    p.total_bayar,
                    p.catatan AS catatan_pesanan,
                    c.nama AS nama_customer,
                    c.no_hp,
                    d.alamat_lokasi,
                    p.id_drop_point,
                    (
                        SELECT MAX(
                            p.tanggal_masuk
                            + COALESCE(
                                l2.estimasi_max_hari,
                                l2.estimasi_min_hari,
                                pk.estimasi_max_hari,
                                pk.estimasi_min_hari
                            ) * INTERVAL '1 day'
                        )
                        FROM detail_pesanan dp2
                        LEFT JOIN layanan l2
                            ON l2.id_layanan = dp2.id_layanan
                        LEFT JOIN paket pk
                            ON pk.id_paket = dp2.id_paket
                        WHERE dp2.id_pesanan = p.id_pesanan
                    ) AS estimasi_selesai
                FROM pesanan p
                JOIN customer c
                    ON c.id_customer = p.id_customer
                LEFT JOIN drop_point d
                    ON d.id_drop_point = p.id_drop_point
                WHERE p.id_pesanan = :id
                LIMIT 1
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $idPesanan]);
            $pesanan = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pesanan) {
                http_response_code(404);
                exit('Pesanan tidak ditemukan.');
            }

            $sqlDetail = "
                SELECT
                    dp.id_detail_pesanan,
                    dp.nama_barang,
                    dp.merk,
                    dp.warna,
                    dp.jumlah,
                    dp.harga_satuan,
                    dp.subtotal,
                    dp.status_pengerjaan,
                    dp.foto_before,
                    dp.foto_sesudah,
                    dp.catatan,
                    COALESCE(l.nama_layanan, pk.nama_paket) AS nama_layanan,
                    COALESCE(l.kategori, 'Package') AS kategori,
                    dp.id_layanan,
                    dp.id_paket
                FROM detail_pesanan dp
                LEFT JOIN layanan l
                    ON l.id_layanan = dp.id_layanan
                LEFT JOIN paket pk
                    ON pk.id_paket = dp.id_paket
                WHERE dp.id_pesanan = :id
                ORDER BY dp.id_detail_pesanan
            ";

            $stmtBarang = $this->db->prepare($sqlDetail);
            $stmtBarang->execute(['id' => $idPesanan]);

            $barang = $stmtBarang->fetchAll(PDO::FETCH_ASSOC);

            $sqlTreatment = "
                SELECT
                    dtt.id_detail_pesanan,
                    dtt.id_treatment_tambahan,
                    tt.nama_treatment,
                    dtt.harga
                FROM detail_treatment_tambahan dtt
                JOIN treatment_tambahan tt
                    ON tt.id_treatment_tambahan = dtt.id_treatment_tambahan
                JOIN detail_pesanan dp
                    ON dp.id_detail_pesanan = dtt.id_detail_pesanan
                WHERE dp.id_pesanan = :id
                ORDER BY dtt.id_detail_pesanan
            ";

            $stmtTreatment = $this->db->prepare($sqlTreatment);
            $stmtTreatment->execute(['id' => $idPesanan]);

            $treatments = $stmtTreatment->fetchAll(PDO::FETCH_ASSOC);

            $treatmentByDetail = [];

            foreach ($treatments as $treatment) {
                $detailId = $treatment['id_detail_pesanan'];
                $treatmentByDetail[$detailId][] = $treatment;
            }

            foreach ($barang as &$item) {
                $detailId = $item['id_detail_pesanan'];

                $item['treatments'] = $treatmentByDetail[$detailId] ?? [];
            }

            unset($item);

            $sqlDropPoint = "
                SELECT id_drop_point, alamat_lokasi
                FROM drop_point
                ORDER BY alamat_lokasi
            ";

            $stmtDropPoint = $this->db->query($sqlDropPoint);
            $dropPoints = $stmtDropPoint->fetchAll(PDO::FETCH_ASSOC);

            $layanan = $this->layananModel->getLayanan();
            $getAllLayanan = $this->layananModel->getAllLayanan();
            $getAllPaket = $this->layananModel->getAllPaket();
            $treatment = $this->treatmentTambahanModel->getTreatmentTambahan();

            $treatmentKecepatan = $treatment['kecepatan'];
            $treatmentTambahan = $treatment['tambahan'];
            $customer = $this->customerModel->getAllCustomer();

            require_once __DIR__ . '/../views/pesanan/edit_pesanan.php';
        } catch (\Throwable $e) {
            echo '<h3>Terjadi Kesalahan</h3>';
            echo '<p><strong>Pesan:</strong> ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
            echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8') . '</p>';
            echo '<p><strong>Baris:</strong> ' . $e->getLine() . '</p>';
            exit('Terjadi kesalahan saat mengambil data pesanan.');
        }
    }



    public function simpanEditPesanan(): void
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

        $idPesanan = filter_var(
            $_POST['id_pesanan'] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($idPesanan === false || $idPesanan === null || $idPesanan < 1) {
            exit('ID pesanan tidak valid.');
        }

        // echo '<pre>';
        // print_r($_POST);
        // echo '</pre>';
        // exit;

        $nama = trim($_POST['nama_customer'] ?? '');
        $noWa = trim($_POST['no_wa'] ?? '');
        $tglMasuk = $_POST['tgl_masuk'] ?? '';
        $tglSelesai = $_POST['tgl_selesai'] ?? null;
        $dropPoint = $_POST['drop_point'] ?? '';

        $status = $_POST['status'] ?? null;
        $statusBayar = $_POST['status_bayar'] ?? null;

        $namaBarang = $_POST['nama_barang'] ?? [];
        $warnaBarang = $_POST['warna_barang'] ?? [];
        $layananIds = $_POST['layanan_id'] ?? [];
        $tipeLayanan = $_POST['tipe_layanan'] ?? [];
        $speed = $_POST['speed'] ?? [];
        $treatments = $_POST['treatment_tambahan'] ?? [];
        $catatan = $_POST['catatan'] ?? [];
        $detailPost = $_POST['id_detail_pesanan'] ?? [];

        $uploadedFiles = [];
        $fotoUntukDihapus = [];

        if ($_POST['status'] !== 'Selesai' && $_POST['status'] !== 'Diambil') {
            $tglSelesai = null;
        }


        $validDate = static function ($date): bool {
            if (!is_string($date) || $date === '') {
                return false;
            }

            $d = DateTime::createFromFormat('!Y-m-d', $date);

            return $d !== false && $d->format('Y-m-d') === $date;
        };

        if (
            !is_string($nama) ||
            $nama === '' ||
            strlen($nama) > 50 ||
            !is_string($noWa) ||
            !preg_match('/^[0-9+\-\s]{8,15}$/', $noWa) ||
            !$validDate($tglMasuk)
        ) {
            exit('Nama, nomor WhatsApp, atau tanggal masuk tidak valid.');
        }

        if ($tglSelesai !== null && $tglSelesai !== '') {
            if (!$validDate($tglSelesai) || $tglSelesai < $tglMasuk) {
                exit('Tanggal selesai tidak valid.');
            }
        } else {
            $tglSelesai = null;
        }

        if (
            !is_array($namaBarang) ||
            count($namaBarang) < 1 ||
            count($namaBarang) > 20 ||
            !is_array($warnaBarang) ||
            !is_array($layananIds) ||
            !is_array($tipeLayanan) ||
            !is_array($speed) ||
            !is_array($treatments) ||
            !is_array($catatan) ||
            !is_array($detailPost)
        ) {
            exit('Data barang tidak valid.');
        }

        $itemIds = array_keys($namaBarang);

        $fieldNames = [
            'warna_barang' => $warnaBarang,
            'layanan_id' => $layananIds,
            'tipe_layanan' => $tipeLayanan,
            'speed' => $speed
        ];

        foreach ($fieldNames as $fieldName => $field) {
            foreach ($itemIds as $formId) {
                if (!array_key_exists($formId, $field)) {
                    exit('Data barang tidak lengkap: field "' .
                        $fieldName .
                        '" tidak ditemukan untuk ID form "' .
                        $formId .
                        '".');
                }
            }
        }

        foreach ($itemIds as $formId) {
            if (
                !isset(
                    $warnaBarang[$formId],
                    $layananIds[$formId],
                    $tipeLayanan[$formId],
                    $speed[$formId]
                ) ||
                !is_string($namaBarang[$formId]) ||
                trim($namaBarang[$formId]) === '' ||
                !is_string($warnaBarang[$formId]) ||
                trim($warnaBarang[$formId]) === '' ||
                !is_scalar($layananIds[$formId]) ||
                !ctype_digit((string) $layananIds[$formId]) ||
                (int) $layananIds[$formId] < 1 ||
                !in_array($tipeLayanan[$formId], ['layanan', 'package'], true) ||
                !is_string($speed[$formId]) ||
                !is_array($treatments[$formId] ?? []) ||
                !is_string($catatan[$formId] ?? '')
            ) {
                exit('Data barang tidak valid.');
            }

            foreach (($treatments[$formId] ?? []) as $treatmentId) {
                if (
                    !is_scalar($treatmentId) ||
                    !ctype_digit((string) $treatmentId) ||
                    (int) $treatmentId < 1
                ) {
                    exit('Treatment tambahan tidak valid.');
                }
            }
        }

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare(
                'SELECT id_pesanan, id_customer, metode_bayar
             FROM pesanan
             WHERE id_pesanan = :id
             FOR UPDATE'
            );

            $stmt->execute(['id' => $idPesanan]);
            $pesananLama = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pesananLama) {
                throw new RuntimeException('Pesanan tidak ditemukan.');
            }

            $stmt = $this->db->prepare(
                'SELECT id_detail_pesanan, foto_before, foto_sesudah
             FROM detail_pesanan
             WHERE id_pesanan = :id
             FOR UPDATE'
            );

            $stmt->execute(['id' => $idPesanan]);
            $detailLamaRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $detailLama = [];
            $fotoLama = [];
            $fotoAfterLama = [];

            foreach ($detailLamaRows as $row) {
                $idDetailLama = (int) $row['id_detail_pesanan'];

                $detailLama[] = $idDetailLama;
                $fotoLama[$idDetailLama] = $row['foto_before'];
                $fotoAfterLama[$idDetailLama] = $row['foto_sesudah'];
            }

            $formDetailMap = [];
            $detailAktif = [];

            foreach ($detailPost as $formId => $databaseId) {
                if (!array_key_exists($formId, $namaBarang)) {
                    continue;
                }

                if (
                    !is_scalar($databaseId) ||
                    !ctype_digit((string) $databaseId) ||
                    (int) $databaseId < 1
                ) {
                    throw new RuntimeException('ID detail barang tidak valid.');
                }

                $databaseId = (int) $databaseId;

                if (!in_array($databaseId, $detailLama, true)) {
                    throw new RuntimeException(
                        'Detail barang tidak sesuai dengan pesanan.'
                    );
                }

                if (in_array($databaseId, $detailAktif, true)) {
                    throw new RuntimeException(
                        'ID detail barang dikirim lebih dari sekali.'
                    );
                }

                $detailAktif[] = $databaseId;
                $formDetailMap[(string) $formId] = $databaseId;
            }

            $detailDihapus = array_diff($detailLama, $detailAktif);

            if (in_array($status, ['Selesai', 'Diambil'], true)) {
                $filesAfter = $_FILES['foto_after'] ?? [];

                foreach ($itemIds as $formId) {
                    $idDetail = $formDetailMap[(string) $formId] ?? null;

                    $fotoAfterSudahAda = $idDetail !== null
                        && !empty($fotoAfterLama[$idDetail]);

                    $errorUpload = $filesAfter['error'][$formId] ?? UPLOAD_ERR_NO_FILE;

                    $fotoAfterDiunggah = $errorUpload !== UPLOAD_ERR_NO_FILE;

                    if (!$fotoAfterSudahAda && !$fotoAfterDiunggah) {
                        throw new RuntimeException(
                            'Foto After wajib diunggah untuk barang: '
                                . trim($namaBarang[$formId])
                                . ' sebelum status diubah menjadi '
                                . $status
                                . '.'
                        );
                    }
                }
            }

            foreach ($detailDihapus as $idDetail) {
                if (!empty($fotoLama[$idDetail])) {
                    $fotoUntukDihapus[] = $fotoLama[$idDetail];
                }

                if (!empty($fotoAfterLama[$idDetail])) {
                    $fotoUntukDihapus[] = $fotoAfterLama[$idDetail];
                }
            }

            $stmtHapusTreatment = $this->db->prepare(
                'DELETE FROM detail_treatment_tambahan
             WHERE id_detail_pesanan = :id'
            );

            $stmtHapusDetail = $this->db->prepare(
                'DELETE FROM detail_pesanan
             WHERE id_detail_pesanan = :id
               AND id_pesanan = :pesanan'
            );

            foreach ($detailDihapus as $idDetail) {
                $stmtHapusTreatment->execute(['id' => $idDetail]);

                $stmtHapusDetail->execute([
                    'id' => $idDetail,
                    'pesanan' => $idPesanan,
                ]);
            }

            $stmt = $this->db->prepare(
                'SELECT id_customer
             FROM customer
             WHERE no_hp = :no_hp
             LIMIT 1'
            );

            $stmt->execute(['no_hp' => $noWa]);
            $idCustomer = $stmt->fetchColumn();

            if ($idCustomer !== false) {
                $idCustomer = (int) $idCustomer;

                $stmt = $this->db->prepare(
                    'UPDATE customer
                 SET nama = :nama
                 WHERE id_customer = :id'
                );

                $stmt->execute([
                    'nama' => $nama,
                    'id' => $idCustomer,
                ]);
            } else {
                $stmt = $this->db->prepare(
                    'INSERT INTO customer (nama, no_hp)
                 VALUES (:nama, :no_hp)
                 RETURNING id_customer'
                );

                $stmt->execute([
                    'nama' => $nama,
                    'no_hp' => $noWa,
                ]);

                $idCustomer = (int) $stmt->fetchColumn();
            }

            $stmt = $this->db->prepare(
                'UPDATE pesanan
             SET id_customer = :customer,
                 id_drop_point = :drop_point,
                 tanggal_masuk = :masuk,
                 tanggal_selesai = :selesai,
                 status = COALESCE(:status, status),
                 status_bayar = COALESCE(:status_bayar, status_bayar)
             WHERE id_pesanan = :id'
            );

            $stmt->execute([
                'customer' => $idCustomer,
                'drop_point' => $dropPoint !== '' ? $dropPoint : null,
                'masuk' => $tglMasuk,
                'selesai' => $tglSelesai,
                'status' => $status,
                'status_bayar' => $statusBayar,
                'id' => $idPesanan,
            ]);

            $stmtLayanan = $this->db->prepare(
                'SELECT id_layanan, harga_min
             FROM layanan
             WHERE id_layanan = :id'
            );

            $stmtPaket = $this->db->prepare(
                'SELECT id_paket, harga
             FROM paket
             WHERE id_paket = :id'
            );

            $stmtTreatment = $this->db->prepare(
                'SELECT id_treatment_tambahan, nama_treatment, harga_min
             FROM treatment_tambahan
             WHERE id_treatment_tambahan = :id'
            );

            $stmtUpdateDetail = $this->db->prepare(
                'UPDATE detail_pesanan
             SET id_layanan = :layanan,
                 id_paket = :paket,
                 nama_barang = :barang,
                 warna = :warna,
                 jumlah = 1,
                 harga_satuan = :harga,
                 subtotal = :subtotal,
                 catatan = :catatan,
                 foto_before = COALESCE(:foto, foto_before),
                 foto_sesudah = COALESCE(:foto_after, foto_sesudah)
             WHERE id_detail_pesanan = :detail
               AND id_pesanan = :pesanan'
            );

            $stmtInsertDetail = $this->db->prepare(
                "INSERT INTO detail_pesanan
             (
                 id_pesanan, id_layanan, id_paket,
                 nama_barang, warna, jumlah, harga_satuan,
                 subtotal, status_pengerjaan, foto_before,
                 foto_sesudah, catatan
             )
             VALUES
             (
                 :pesanan, :layanan, :paket,
                 :barang, :warna, 1, :harga,
                 :subtotal, 'Menunggu', :foto,
                 :foto_after, :catatan
             )
             RETURNING id_detail_pesanan"
            );

            $stmtHapusTreatmentPerDetail = $this->db->prepare(
                'DELETE FROM detail_treatment_tambahan
             WHERE id_detail_pesanan = :id'
            );

            $stmtInsertTreatment = $this->db->prepare(
                'INSERT INTO detail_treatment_tambahan
             (id_detail_pesanan, id_treatment_tambahan, harga)
             VALUES (:detail, :treatment, :harga)'
            );

            $totalBayar = 0;

            foreach ($itemIds as $formId) {
                $referensiId = (int) $layananIds[$formId];
                $tipe = $tipeLayanan[$formId];

                $idLayanan = null;
                $idPaket = null;

                if ($tipe === 'layanan') {
                    $stmtLayanan->execute(['id' => $referensiId]);
                    $referensiRow = $stmtLayanan->fetch(PDO::FETCH_ASSOC);

                    if (!$referensiRow) {
                        throw new RuntimeException('Layanan tidak ditemukan.');
                    }

                    $idLayanan = $referensiId;
                    $hargaLayanan = (int) $referensiRow['harga_min'];
                } else {
                    $stmtPaket->execute(['id' => $referensiId]);
                    $referensiRow = $stmtPaket->fetch(PDO::FETCH_ASSOC);

                    if (!$referensiRow) {
                        throw new RuntimeException('Paket tidak ditemukan.');
                    }

                    $idPaket = $referensiId;
                    $hargaLayanan = (int) $referensiRow['harga'];
                }

                $selectedTreatments = [];

                if ($speed[$formId] !== 'reguler') {
                    if (
                        !ctype_digit((string) $speed[$formId]) ||
                        (int) $speed[$formId] < 1
                    ) {
                        throw new RuntimeException('Pilihan speed tidak valid.');
                    }

                    $selectedTreatments[] = (int) $speed[$formId];
                }

                foreach (($treatments[$formId] ?? []) as $treatmentId) {
                    $treatmentId = (int) $treatmentId;

                    if (!in_array($treatmentId, $selectedTreatments, true)) {
                        $selectedTreatments[] = $treatmentId;
                    }
                }

                $hargaTambahan = [];

                foreach ($selectedTreatments as $treatmentId) {
                    $stmtTreatment->execute(['id' => $treatmentId]);
                    $row = $stmtTreatment->fetch(PDO::FETCH_ASSOC);

                    if (!$row) {
                        throw new RuntimeException('Treatment tidak ditemukan.');
                    }

                    $hargaTambahan[$treatmentId] = (int) $row['harga_min'];
                }

                $subtotal = $hargaLayanan + array_sum($hargaTambahan);

                $fotoPath = null;
                $files = $_FILES['foto_before'] ?? null;

                if (
                    is_array($files) &&
                    isset($files['error'][$formId]) &&
                    $files['error'][$formId] !== UPLOAD_ERR_NO_FILE
                ) {
                    $error = $files['error'][$formId];

                    if ($error !== UPLOAD_ERR_OK) {
                        throw new RuntimeException('Upload foto before gagal.');
                    }

                    $tmpName = $files['tmp_name'][$formId];
                    $size = (int) $files['size'][$formId];

                    if (
                        !is_uploaded_file($tmpName) ||
                        $size < 1 ||
                        $size > 5 * 1024 * 1024
                    ) {
                        throw new RuntimeException(
                            'Foto before tidak valid atau melebihi 5 MB.'
                        );
                    }

                    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmpName);

                    $extensions = [
                        'image/jpeg' => 'jpg',
                        'image/png' => 'png',
                        'image/webp' => 'webp',
                    ];

                    if (!isset($extensions[$mime])) {
                        throw new RuntimeException(
                            'Foto before harus JPG, PNG, atau WEBP.'
                        );
                    }

                    $folder = dirname(__DIR__, 2)
                        . '/public/uploads/pesanan/before';

                    if (!is_dir($folder) && !mkdir($folder, 0755, true)) {
                        throw new RuntimeException(
                            'Folder upload foto before gagal dibuat.'
                        );
                    }

                    $filename = bin2hex(random_bytes(16))
                        . '.'
                        . $extensions[$mime];

                    $destination = $folder . '/' . $filename;

                    if (!move_uploaded_file($tmpName, $destination)) {
                        throw new RuntimeException('Foto before gagal disimpan.');
                    }

                    $fotoPath = 'uploads/pesanan/before/' . $filename;
                    $uploadedFiles[] = $destination;
                }

                $fotoAfterPath = null;
                $filesAfter = $_FILES['foto_after'] ?? null;

                if (
                    is_array($filesAfter) &&
                    isset($filesAfter['error'][$formId]) &&
                    $filesAfter['error'][$formId] !== UPLOAD_ERR_NO_FILE
                ) {
                    $error = $filesAfter['error'][$formId];

                    if ($error !== UPLOAD_ERR_OK) {
                        throw new RuntimeException('Upload foto after gagal.');
                    }

                    $tmpName = $filesAfter['tmp_name'][$formId];
                    $size = (int) $filesAfter['size'][$formId];

                    if (
                        !is_uploaded_file($tmpName) ||
                        $size < 1 ||
                        $size > 5 * 1024 * 1024
                    ) {
                        throw new RuntimeException(
                            'Foto after tidak valid atau melebihi 5 MB.'
                        );
                    }

                    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmpName);

                    $extensions = [
                        'image/jpeg' => 'jpg',
                        'image/png' => 'png',
                        'image/webp' => 'webp',
                    ];

                    if (!isset($extensions[$mime])) {
                        throw new RuntimeException(
                            'Foto after harus JPG, PNG, atau WEBP.'
                        );
                    }

                    $folder = dirname(__DIR__, 2)
                        . '/public/uploads/pesanan/after';

                    if (!is_dir($folder) && !mkdir($folder, 0755, true)) {
                        throw new RuntimeException(
                            'Folder upload foto after gagal dibuat.'
                        );
                    }

                    $filename = bin2hex(random_bytes(16))
                        . '.'
                        . $extensions[$mime];

                    $destination = $folder . '/' . $filename;

                    if (!move_uploaded_file($tmpName, $destination)) {
                        throw new RuntimeException('Foto after gagal disimpan.');
                    }

                    $fotoAfterPath = 'uploads/pesanan/after/' . $filename;
                    $uploadedFiles[] = $destination;
                }

                $idDetail = $formDetailMap[(string) $formId] ?? null;

                if ($idDetail !== null) {
                    if (
                        $fotoPath !== null &&
                        !empty($fotoLama[$idDetail])
                    ) {
                        $fotoUntukDihapus[] = $fotoLama[$idDetail];
                    }

                    if (
                        $fotoAfterPath !== null &&
                        !empty($fotoAfterLama[$idDetail])
                    ) {
                        $fotoUntukDihapus[] = $fotoAfterLama[$idDetail];
                    }
                }

                $params = [
                    'pesanan' => $idPesanan,
                    'layanan' => $idLayanan,
                    'paket' => $idPaket,
                    'barang' => trim($namaBarang[$formId]),
                    'warna' => trim($warnaBarang[$formId]),
                    'harga' => $hargaLayanan,
                    'subtotal' => $subtotal,
                    'catatan' => trim($catatan[$formId] ?? '') ?: null,
                ];

                if ($idDetail !== null) {
                    $stmtUpdateDetail->execute($params + [
                        'foto' => $fotoPath,
                        'foto_after' => $fotoAfterPath,
                        'detail' => $idDetail,
                    ]);

                    if ($stmtUpdateDetail->rowCount() === 0) {
                        $cek = $this->db->prepare(
                            'SELECT 1
                         FROM detail_pesanan
                         WHERE id_detail_pesanan = :detail
                           AND id_pesanan = :pesanan'
                        );

                        $cek->execute([
                            'detail' => $idDetail,
                            'pesanan' => $idPesanan,
                        ]);

                        if (!$cek->fetchColumn()) {
                            throw new RuntimeException(
                                'Detail barang tidak ditemukan.'
                            );
                        }
                    }
                } else {
                    $stmtInsertDetail->execute($params + [
                        'foto' => $fotoPath,
                        'foto_after' => $fotoAfterPath,
                    ]);

                    $idDetail = (int) $stmtInsertDetail->fetchColumn();
                }

                $stmtHapusTreatmentPerDetail->execute([
                    'id' => $idDetail,
                ]);

                foreach ($hargaTambahan as $treatmentId => $harga) {
                    $stmtInsertTreatment->execute([
                        'detail' => $idDetail,
                        'treatment' => $treatmentId,
                        'harga' => $harga,
                    ]);
                }

                $totalBayar += $subtotal;
            }

            $stmt = $this->db->prepare(
                'UPDATE pesanan
             SET total_bayar = :total
             WHERE id_pesanan = :id'
            );

            $stmt->execute([
                'total' => $totalBayar,
                'id' => $idPesanan,
            ]);

            $this->db->commit();

            $folderBefore = realpath(
                dirname(__DIR__, 2) . '/public/uploads/pesanan/before'
            );

            $folderAfter = realpath(
                dirname(__DIR__, 2) . '/public/uploads/pesanan/after'
            );

            foreach (array_unique($fotoUntukDihapus) as $foto) {
                if (!is_string($foto)) {
                    continue;
                }

                if (str_starts_with($foto, 'uploads/pesanan/before/')) {
                    $folderUpload = $folderBefore;
                } elseif (str_starts_with($foto, 'uploads/pesanan/after/')) {
                    $folderUpload = $folderAfter;
                } else {
                    continue;
                }

                if ($folderUpload === false) {
                    continue;
                }

                $namaFile = basename($foto);
                $pathFile = $folderUpload . DIRECTORY_SEPARATOR . $namaFile;

                if (is_file($pathFile) && !unlink($pathFile)) {
                    error_log('Gagal menghapus foto lama: ' . $pathFile);
                }
            }

            unset($_SESSION['csrf_token']);

            $_SESSION['success'] = 'Pesanan berhasil diperbarui.';
            header('Location: /pesanan');
            exit;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            foreach ($uploadedFiles as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }

            error_log(
                'simpanEditPesanan: ' . $e->getMessage()
                    . ' | ' . $e->getFile()
                    . ':' . $e->getLine()
            );

            http_response_code(500);

            echo '<h3>Pesanan gagal diperbarui</h3>';
            echo '<p><strong>Error:</strong> '
                . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
                . '</p>';
            echo '<p><strong>File:</strong> '
                . htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8')
                . '</p>';
            echo '<p><strong>Baris:</strong> '
                . $e->getLine()
                . '</p>';

            exit;
        }
    }


    public function detail(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            header('Location: /pesanan');
            exit;
        }

        $idPesanan = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$idPesanan || $idPesanan < 1) {
            header('Location: /pesanan');
            exit;
        }

        try {
            $sql = "
                SELECT
                    p.id_pesanan,
                    p.kode_pesanan,
                    p.tanggal_masuk,
                    p.tanggal_selesai,
                    p.status,
                    p.status_bayar,
                    p.metode_bayar,
                    p.total_bayar,
                    p.catatan AS catatan_pesanan,
                    c.nama AS nama_customer,
                    c.no_hp,
                    d.alamat_lokasi,
                    (
                        SELECT MAX(
                            p.tanggal_masuk
                            + COALESCE(
                                l2.estimasi_max_hari,
                                l2.estimasi_min_hari,
                                pk.estimasi_max_hari,
                                pk.estimasi_min_hari
                            ) * INTERVAL '1 day'
                        )
                        FROM detail_pesanan dp2
                        LEFT JOIN layanan l2
                            ON l2.id_layanan = dp2.id_layanan
                        LEFT JOIN paket pk
                            ON pk.id_paket = dp2.id_paket
                        WHERE dp2.id_pesanan = p.id_pesanan
                    ) AS estimasi_selesai
                FROM pesanan p
                JOIN customer c
                    ON c.id_customer = p.id_customer
                LEFT JOIN drop_point d
                    ON d.id_drop_point = p.id_drop_point
                WHERE p.id_pesanan = :id
                LIMIT 1
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $idPesanan]);
            $pesanan = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pesanan) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Pesanan tidak ditemukan.']);
                return;
            }

            $sqlDetail = "
                SELECT
                    dp.id_detail_pesanan,
                    dp.nama_barang,
                    dp.merk,
                    dp.warna,
                    dp.jumlah,
                    dp.harga_satuan,
                    dp.subtotal,
                    dp.status_pengerjaan,
                    dp.foto_before,
                    dp.foto_sesudah,
                    dp.catatan,
                    COALESCE(l.nama_layanan, pk.nama_paket) AS nama_layanan,
                    COALESCE(l.kategori, 'Package') AS kategori,
                    dp.id_layanan,
                    dp.id_paket
                FROM detail_pesanan dp
                LEFT JOIN layanan l
                    ON l.id_layanan = dp.id_layanan
                LEFT JOIN paket pk
                    ON pk.id_paket = dp.id_paket
                WHERE dp.id_pesanan = :id
                ORDER BY dp.id_detail_pesanan
            ";

            $stmtDetail = $this->db->prepare($sqlDetail);
            $stmtDetail->execute(['id' => $idPesanan]);
            $detailBarang = $stmtDetail->fetchAll(PDO::FETCH_ASSOC);

            $stmtTreatment = $this->db->prepare("
                SELECT
                    dtt.id_detail_pesanan,
                    tt.nama_treatment,
                    dtt.harga
                FROM detail_treatment_tambahan dtt
                JOIN treatment_tambahan tt
                    ON tt.id_treatment_tambahan = dtt.id_treatment_tambahan
                JOIN detail_pesanan dp
                    ON dp.id_detail_pesanan = dtt.id_detail_pesanan
                WHERE dp.id_pesanan = :id
                ORDER BY dtt.id_detail_pesanan
            ");

            $stmtTreatment->execute(['id' => $idPesanan]);
            $treatments = $stmtTreatment->fetchAll(PDO::FETCH_ASSOC);

            $treatmentByDetail = [];

            foreach ($treatments as $treatment) {
                $detailId = $treatment['id_detail_pesanan'];
                $treatmentByDetail[$detailId][] = $treatment;
            }

            foreach ($detailBarang as &$barang) {
                $detailId = $barang['id_detail_pesanan'];
                $barang['treatments'] = $treatmentByDetail[$detailId] ?? [];
            }

            $pesanan;
            $detailBarang;

            require_once __DIR__ . '/../views/pesanan/detail_pesanan.php';
        } catch (\Throwable $e) {
            error_log('Detail pesanan error: ' . $e->getMessage());

            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);

            exit;
        }
    }

    public function invoice(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            header('Location: /pesanan');
            exit;
        }

        $idPesanan = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$idPesanan || $idPesanan < 1) {
            header('Location: /pesanan');
            exit;
        }

        try {
            $sql = "
                SELECT
                    p.id_pesanan,
                    p.kode_pesanan,
                    p.tanggal_masuk,
                    p.tanggal_selesai,
                    p.status,
                    p.status_bayar,
                    p.metode_bayar,
                    p.total_bayar,
                    p.catatan AS catatan_pesanan,
                    c.nama AS nama_customer,
                    c.no_hp,
                    d.alamat_lokasi,
                    pegawai.nama AS nama_pegawai,
                    (
                        SELECT MAX(
                            p.tanggal_masuk
                            + COALESCE(
                                l2.estimasi_max_hari,
                                l2.estimasi_min_hari,
                                pk.estimasi_max_hari,
                                pk.estimasi_min_hari
                            ) * INTERVAL '1 day'
                        )
                        FROM detail_pesanan dp2
                        LEFT JOIN layanan l2
                            ON l2.id_layanan = dp2.id_layanan
                        LEFT JOIN paket pk
                            ON pk.id_paket = dp2.id_paket
                        WHERE dp2.id_pesanan = p.id_pesanan
                    ) AS estimasi_selesai
                FROM pesanan p
                JOIN customer c
                    ON c.id_customer = p.id_customer
                LEFT JOIN pegawai
                    ON pegawai.id_pegawai = p.id_pegawai
                LEFT JOIN drop_point d
                    ON d.id_drop_point = p.id_drop_point
                WHERE p.id_pesanan = :id
                LIMIT 1
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $idPesanan]);

            $pesanan = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pesanan) {
                http_response_code(404);
                exit('Pesanan tidak ditemukan.');
            }

            $sqlBarang = "
                SELECT
                    dp.id_detail_pesanan,
                    dp.nama_barang,
                    dp.merk,
                    dp.warna,
                    dp.jumlah,
                    dp.harga_satuan,
                    dp.subtotal,
                    dp.status_pengerjaan,
                    dp.foto_before,
                    dp.foto_sesudah,
                    dp.catatan,
                    COALESCE(l.nama_layanan, pk.nama_paket) AS nama_layanan,
                    COALESCE(l.kategori, 'Package') AS kategori,
                    dp.id_layanan,
                    dp.id_paket
                FROM detail_pesanan dp
                LEFT JOIN layanan l
                    ON l.id_layanan = dp.id_layanan
                LEFT JOIN paket pk
                    ON pk.id_paket = dp.id_paket
                WHERE dp.id_pesanan = :id
                ORDER BY dp.id_detail_pesanan
            ";

            $stmtBarang = $this->db->prepare($sqlBarang);
            $stmtBarang->execute(['id' => $idPesanan]);

            $barang = $stmtBarang->fetchAll(PDO::FETCH_ASSOC);

            $sqlTreatment = "
                SELECT
                    dtt.id_detail_pesanan,
                    tt.nama_treatment,
                    dtt.harga
                FROM detail_treatment_tambahan dtt
                JOIN treatment_tambahan tt
                    ON tt.id_treatment_tambahan = dtt.id_treatment_tambahan
                JOIN detail_pesanan dp
                    ON dp.id_detail_pesanan = dtt.id_detail_pesanan
                WHERE dp.id_pesanan = :id
                ORDER BY dtt.id_detail_pesanan
            
            ";

            $stmtTreatment = $this->db->prepare($sqlTreatment);
            $stmtTreatment->execute(['id' => $idPesanan]);

            $treatments = $stmtTreatment->fetchAll(PDO::FETCH_ASSOC);

            $treatmentByDetail = [];

            foreach ($treatments as $treatment) {
                $detailId = $treatment['id_detail_pesanan'];
                $treatmentByDetail[$detailId][] = $treatment;
            }

            foreach ($barang as &$item) {
                $detailId = $item['id_detail_pesanan'];

                $item['treatments'] = $treatmentByDetail[$detailId] ?? [];
            }

            unset($item);

            $sqlDropPoint = "
            SELECT id_drop_point, alamat_lokasi
            FROM drop_point
            ORDER BY alamat_lokasi
            ";

            $stmtDropPoint = $this->db->query($sqlDropPoint);
            $dropPoints = $stmtDropPoint->fetchAll(PDO::FETCH_ASSOC);

            require_once __DIR__ . '/../views/pesanan/invoice.php';
        } catch (\Throwable $e) {
            echo '<h3>Terjadi Kesalahan</h3>';
            echo '<p><strong>Pesan:</strong> ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
            echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8') . '</p>';
            echo '<p><strong>Baris:</strong> ' . $e->getLine() . '</p>';
            exit('Terjadi kesalahan saat mengambil data pesanan.');
        }
    }

    public function updateStatus(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_SESSION['id_pegawai'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Silakan login kembali.'
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Metode tidak diizinkan.'
            ]);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Data permintaan tidak valid.'
            ]);
            return;
        }

        $csrfToken = $data['csrf_token'] ?? '';

        if (
            empty($_SESSION['csrf_token']) ||
            !is_string($csrfToken) ||
            !hash_equals($_SESSION['csrf_token'], $csrfToken)
        ) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'Token keamanan tidak valid.'
            ]);
            return;
        }

        $idPesanan = filter_var(
            $data['id_pesanan'] ?? null,
            FILTER_VALIDATE_INT
        );

        $status = $data['status'] ?? '';

        if (
            $idPesanan === false ||
            $idPesanan === null ||
            $idPesanan < 1 ||
            !is_string($status) ||
            !in_array($status, ['Menunggu', 'Diproses', 'Selesai', 'Diambil'], true)
        ) {
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'message' => 'ID pesanan atau status tidak valid.'
            ]);
            return;
        }

        try {
            if (in_array($status, ['Selesai', 'Diambil'], true)) {
                $stmt = $this->db->prepare(
                    'SELECT
                    COUNT(*) AS total_barang,
                    COUNT(*) FILTER (
                        WHERE foto_sesudah IS NOT NULL
                        AND BTRIM(foto_sesudah) <> \'\'
                    ) AS total_foto_sesudah
                 FROM detail_pesanan
                 WHERE id_pesanan = :id'
                );

                $stmt->execute(['id' => $idPesanan]);
                $foto = $stmt->fetch(PDO::FETCH_ASSOC);

                if (
                    (int) $foto['total_barang'] === 0 ||
                    (int) $foto['total_barang'] !== (int) $foto['total_foto_sesudah']
                ) {
                    http_response_code(422);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Status tidak dapat diubah menjadi ' . $status . '. Pastikan foto after semua barang sudah diunggah.'
                    ]);
                    return;
                }
            }

            $stmt = $this->db->prepare(
                'UPDATE pesanan
             SET status = :status
             WHERE id_pesanan = :id'
            );

            $stmt->execute([
                'status' => $status,
                'id' => $idPesanan
            ]);

            if ($stmt->rowCount() === 0) {
                $cek = $this->db->prepare(
                    'SELECT 1 FROM pesanan WHERE id_pesanan = :id'
                );

                $cek->execute(['id' => $idPesanan]);

                if (!$cek->fetchColumn()) {
                    http_response_code(404);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Pesanan tidak ditemukan.'
                    ]);
                    return;
                }
            }

            echo json_encode([
                'success' => true,
                'message' => 'Status berhasil diperbarui.',
                'status' => $status
            ]);
        } catch (Throwable $e) {
            error_log('updateStatus: ' . $e->getMessage());

            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memperbarui status pesanan.'
            ]);
        }
    }

    public function hapus(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        if (($_SESSION['role'] ?? '') !== 'superadmin') {
            http_response_code(403);
            exit('Anda tidak memiliki izin untuk menghapus pesanan.');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Metode request tidak diizinkan.');
        }

        if (
            empty($_SESSION['csrf_token']) ||
            !is_string($_POST['csrf_token'] ?? null) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            http_response_code(403);
            exit('Token keamanan tidak valid. Silakan muat ulang halaman.');
        }

        $idPesanan = filter_var(
            $_POST['id_pesanan'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$idPesanan || $idPesanan < 1) {
            header('Location: /pesanan');
            exit;
        }

        try {
            $stmtFoto = $this->db->prepare(
                'SELECT foto_before, foto_sesudah
                FROM detail_pesanan
                WHERE id_pesanan = :id_pesanan'
            );

            $stmtFoto->execute([
                ':id_pesanan' => $idPesanan
            ]);

            $daftarFoto = $stmtFoto->fetchAll(PDO::FETCH_ASSOC);

            $fotoUntukDihapus = [];

            foreach ($daftarFoto as $foto) {
                if (!empty($foto['foto_before'])) {
                    $fotoUntukDihapus[] = $foto['foto_before'];
                }

                if (!empty($foto['foto_sesudah'])) {
                    $fotoUntukDihapus[] = $foto['foto_sesudah'];
                }
            }

            $stmt = $this->db->prepare(
                'DELETE FROM pesanan
                WHERE id_pesanan = :id_pesanan'
            );

            $stmt->execute([
                ':id_pesanan' => $idPesanan
            ]);

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                exit('Pesanan tidak ditemukan.');
            }

            $folderBefore = realpath(
                dirname(__DIR__, 2) . '/public/uploads/pesanan/before'
            );

            $folderAfter = realpath(
                dirname(__DIR__, 2) . '/public/uploads/pesanan/after'
            );

            foreach (array_unique($fotoUntukDihapus) as $foto) {
                if (!is_string($foto)) {
                    continue;
                }

                if (str_starts_with($foto, 'uploads/pesanan/before/')) {
                    $folderUpload = $folderBefore;
                } elseif (str_starts_with($foto, 'uploads/pesanan/after/')) {
                    $folderUpload = $folderAfter;
                } else {
                    continue;
                }

                if ($folderUpload === false) {
                    continue;
                }

                $namaFile = basename($foto);
                $pathFile = $folderUpload . DIRECTORY_SEPARATOR . $namaFile;

                if (is_file($pathFile) && !unlink($pathFile)) {
                    error_log('Gagal menghapus foto lama: ' . $pathFile);
                }
            }

            $_SESSION['success'] = 'Pesanan berhasil dihapus';
            header('Location: /pesanan');
            exit;
        } catch (PDOException $e) {
            error_log('Gagal menghapus pesanan: ' . $e->getMessage());

            http_response_code(500);
            exit('Pesanan gagal dihapus. Periksa relasi foreign key database.');
        }
    }

    public function detailData(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_SESSION['id_pegawai'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Silakan login kembali.']);
            return;
        }

        $idPesanan = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$idPesanan || $idPesanan < 1) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID pesanan tidak valid.']);
            return;
        }

        try {
            $sql = "
                SELECT
                    p.id_pesanan,
                    p.kode_pesanan,
                    p.tanggal_masuk,
                    p.tanggal_selesai,
                    p.status,
                    p.status_bayar,
                    p.metode_bayar,
                    p.total_bayar,
                    p.catatan AS catatan_pesanan,
                    c.nama AS nama_customer,
                    c.no_hp,
                    d.alamat_lokasi,
                    (
                        SELECT MAX(
                            p.tanggal_masuk
                            + COALESCE(
                                l2.estimasi_max_hari,
                                l2.estimasi_min_hari,
                                pk.estimasi_max_hari,
                                pk.estimasi_min_hari
                            ) * INTERVAL '1 day'
                        )
                        FROM detail_pesanan dp2
                        LEFT JOIN layanan l2
                            ON l2.id_layanan = dp2.id_layanan
                        LEFT JOIN paket pk
                            ON pk.id_paket = dp2.id_paket
                        WHERE dp2.id_pesanan = p.id_pesanan
                    ) AS estimasi_selesai
                FROM pesanan p
                JOIN customer c
                    ON c.id_customer = p.id_customer
                LEFT JOIN drop_point d
                    ON d.id_drop_point = p.id_drop_point
                WHERE p.id_pesanan = :id
                LIMIT 1
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $idPesanan]);
            $pesanan = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pesanan) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Pesanan tidak ditemukan.']);
                return;
            }

            $sqlDetail = "
                SELECT
                    dp.id_detail_pesanan,
                    dp.nama_barang,
                    dp.merk,
                    dp.warna,
                    dp.jumlah,
                    dp.harga_satuan,
                    dp.subtotal,
                    dp.status_pengerjaan,
                    dp.foto_before,
                    dp.foto_sesudah,
                    dp.catatan,
                    COALESCE(l.nama_layanan, pk.nama_paket) AS nama_layanan,
                    COALESCE(l.kategori, 'Package') AS kategori,
                    dp.id_layanan,
                    dp.id_paket
                FROM detail_pesanan dp
                LEFT JOIN layanan l
                    ON l.id_layanan = dp.id_layanan
                LEFT JOIN paket pk
                    ON pk.id_paket = dp.id_paket
                WHERE dp.id_pesanan = :id
                ORDER BY dp.id_detail_pesanan
            ";

            $stmtDetail = $this->db->prepare($sqlDetail);
            $stmtDetail->execute(['id' => $idPesanan]);
            $detailBarang = $stmtDetail->fetchAll(PDO::FETCH_ASSOC);

            $stmtTreatment = $this->db->prepare("
                SELECT
                    dtt.id_detail_pesanan,
                    tt.nama_treatment,
                    dtt.harga
                FROM detail_treatment_tambahan dtt
                JOIN treatment_tambahan tt
                    ON tt.id_treatment_tambahan = dtt.id_treatment_tambahan
                JOIN detail_pesanan dp
                    ON dp.id_detail_pesanan = dtt.id_detail_pesanan
                WHERE dp.id_pesanan = :id
                ORDER BY dtt.id_detail_pesanan
            ");

            $stmtTreatment->execute(['id' => $idPesanan]);
            $treatments = $stmtTreatment->fetchAll(PDO::FETCH_ASSOC);

            $treatmentByDetail = [];

            foreach ($treatments as $treatment) {
                $detailId = $treatment['id_detail_pesanan'];
                $treatmentByDetail[$detailId][] = $treatment;
            }

            foreach ($detailBarang as &$barang) {
                $detailId = $barang['id_detail_pesanan'];
                $barang['treatments'] = $treatmentByDetail[$detailId] ?? [];
            }
            unset($barang);

            echo json_encode([
                'success' => true,
                'pesanan' => $pesanan,
                'barang' => $detailBarang
            ]);
        } catch (\Throwable $e) {
            error_log('Detail pesanan error: ' . $e->getMessage());

            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');

            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);

            exit;
        }
    }
}
