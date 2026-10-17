<?php

require_once __DIR__ . '/../models/Pesanan.php';
require_once __DIR__ . '/../models/DropPoint.php';
require_once __DIR__ . '/../models/Layanan.php';
require_once __DIR__ . '/../models/TreatmentTambahan.php';

class PesananController
{
    private PDO $db;
    private Pesanan $pesananModel;
    private DropPoint $dropPointModel;
    private Layanan $layananModel;
    private TreatmentTambahan $treatmentTambahanModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->pesananModel = new Pesanan($db);
        $this->dropPointModel = new DropPoint($db);
        $this->layananModel = new Layanan($db);
        $this->treatmentTambahanModel = new TreatmentTambahan($db);
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

        $treatmentKecepatan = $treatment['kecepatan'];
        $treatmentTambahan = $treatment['tambahan'];

        require_once __DIR__ . '/../views/pesanan/tambah_pesanan.php';
    }


    public function simpanPesanan(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

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

        $nama = trim($_POST['nama_pelanggang'] ?? '');
        $noWa = trim($_POST['no_wa'] ?? '');
        $tglMasuk = $_POST['tgl_masuk'] ?? '';
        $tglSelesai = $_POST['tgl_selesai'] ?? null;
        $metodeBayar = $_POST['metode_pembayaran'] ?? '';
        $dropPoint = $_POST['drop_point'] ?? '';

        $namaBarang = $_POST['nama_barang'] ?? [];
        $warnaBarang = $_POST['warna_barang'] ?? [];
        $layananIds = $_POST['layanan_id'] ?? [];
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
            !is_array($speed) ||
            !is_array($treatments) ||
            !is_array($catatan)
        ) {
            exit('Data barang tidak valid.');
        }

        $itemIds = array_keys($namaBarang);

        foreach ([$warnaBarang, $layananIds, $speed] as $field) {
            if (array_keys($field) !== $itemIds) {
                exit('Data barang tidak lengkap.');
            }
        }

        foreach ($itemIds as $id) {
            if (
                !isset($warnaBarang[$id], $layananIds[$id], $speed[$id]) ||
                trim((string) $namaBarang[$id]) === '' ||
                trim((string) $warnaBarang[$id]) === '' ||
                !ctype_digit((string) $layananIds[$id]) ||
                (int) $layananIds[$id] < 1 ||
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
                'SELECT id_customer FROM customer WHERE no_hp = :no_hp LIMIT 1'
            );
            $stmt->execute(['no_hp' => $noWa]);
            $idCustomer = $stmt->fetchColumn();

            if ($idCustomer) {
                $idCustomer = (int) $idCustomer;

                $stmt = $this->db->prepare(
                    'UPDATE customer SET nama = :nama WHERE id_customer = :id'
                );
                $stmt->execute([
                    'nama' => $nama,
                    'id' => $idCustomer
                ]);
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
             FROM layanan WHERE id_layanan = :id'
            );

            $stmtTreatment = $this->db->prepare(
                'SELECT id_treatment_tambahan, nama_treatment, harga_min
             FROM treatment_tambahan
             WHERE id_treatment_tambahan = :id'
            );

            $stmtDetail = $this->db->prepare(
                "INSERT INTO detail_pesanan
                (id_pesanan, id_layanan, nama_barang, warna, jumlah,
                 harga_satuan, subtotal, status_pengerjaan,
                 foto_before, catatan)
             VALUES
                (:pesanan, :layanan, :barang, :warna, 1,
                 :harga, :subtotal, 'Menunggu', :foto, :catatan)
             RETURNING id_detail_pesanan"
            );

            $stmtDetailTreatment = $this->db->prepare(
                'INSERT INTO detail_treatment_tambahan
                (id_detail_pesanan, id_treatment_tambahan, harga)
             VALUES (:detail, :treatment, :harga)'
            );

            foreach ($itemIds as $id) {
                $stmtLayanan->execute([
                    'id' => (int) $layananIds[$id]
                ]);
                $layananRow = $stmtLayanan->fetch(PDO::FETCH_ASSOC);

                if (!$layananRow) {
                    throw new RuntimeException('Layanan tidak ditemukan.');
                }

                $hargaLayanan = (int) $layananRow['harga_min'];
                $selectedTreatments = [];

                if ($speed[$id] !== 'reguler') {
                    if (!ctype_digit((string) $speed[$id])) {
                        throw new RuntimeException('Kecepatan tidak valid.');
                    }

                    $speedId = (int) $speed[$id];

                    $selectedTreatments[] = $speedId;
                }

                foreach (($treatments[$id] ?? []) as $treatmentId) {
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
                    isset($files['error'][$id]) &&
                    $files['error'][$id] !== UPLOAD_ERR_NO_FILE
                ) {
                    $error = $files['error'][$id];

                    if ($error !== UPLOAD_ERR_OK) {
                        throw new RuntimeException('Upload foto gagal.');
                    }

                    $tmpName = $files['tmp_name'][$id];
                    $size = (int) $files['size'][$id];

                    if (!is_uploaded_file($tmpName) || $size < 1 || $size > 5 * 1024 * 1024) {
                        throw new RuntimeException('Foto tidak valid atau melebihi 5 MB.');
                    }

                    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmpName);
                    $extensions = [
                        'image/jpeg' => 'jpg',
                        'image/png' => 'png',
                        'image/webp' => 'webp'
                    ];

                    if (!isset($extensions[$mime])) {
                        throw new RuntimeException('Foto harus JPG, PNG, atau WEBP.');
                    }

                    $folder = dirname(__DIR__, 2) . '/public/uploads/pesanan';

                    if (!is_dir($folder) && !mkdir($folder, 0755, true)) {
                        throw new RuntimeException('Folder upload gagal dibuat.');
                    }

                    $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
                    $destination = $folder . '/' . $filename;

                    if (!move_uploaded_file($tmpName, $destination)) {
                        throw new RuntimeException('Foto gagal disimpan.');
                    }

                    $fotoPath = 'uploads/pesanan/' . $filename;
                    $uploadedFiles[] = $destination;
                }

                $stmtDetail->execute([
                    'pesanan' => $idPesanan,
                    'layanan' => (int) $layananIds[$id],
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
                'UPDATE pesanan SET total_bayar = :total WHERE id_pesanan = :id'
            );
            $stmt->execute([
                'total' => $totalBayar,
                'id' => $idPesanan
            ]);

            $this->db->commit();

            unset($_SESSION['csrf_token']);

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



    public function editPesanan(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        require_once __DIR__ . '/../views/pesanan/edit_pesanan.php';
    }

    public function detail(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        require_once __DIR__ . '/../views/pesanan/detail_pesanan.php';
    }

    public function invoice(): void
    {
        if (!isset($_SESSION['id_pegawai'])) {
            header('Location: /login');
            exit;
        }

        require_once __DIR__ . '/../views/pesanan/invoice.php';
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
                d.alamat_lokasi
            FROM pesanan p
            JOIN customer c ON c.id_customer = p.id_customer
            LEFT JOIN drop_point d ON d.id_drop_point = p.id_drop_point
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
                l.nama_layanan,
                l.kategori
            FROM detail_pesanan dp
            LEFT JOIN layanan l ON l.id_layanan = dp.id_layanan
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
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
