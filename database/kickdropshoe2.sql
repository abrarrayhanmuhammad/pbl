-- =========================================================
-- DATABASE KICKDROPSHOE
-- PostgreSQL | Schema + data awal berdasarkan pricelist
-- Jalankan di database kosong, misalnya: kickdropshoe
-- Harga disimpan dalam rupiah (INTEGER).
-- =========================================================

BEGIN;

-- Hapus tabel jika script dijalankan ulang (urutan FK dari anak ke induk)
DROP TABLE IF EXISTS detail_treatment_tambahan CASCADE;
DROP TABLE IF EXISTS treatment_tambahan CASCADE;
DROP TABLE IF EXISTS detail_pesanan CASCADE;
DROP TABLE IF EXISTS pesanan CASCADE;
DROP TABLE IF EXISTS paket_layanan CASCADE;
DROP TABLE IF EXISTS paket CASCADE;
DROP TABLE IF EXISTS layanan CASCADE;
DROP TABLE IF EXISTS jenis_barang CASCADE;
DROP TABLE IF EXISTS drop_point CASCADE;
DROP TABLE IF EXISTS customer CASCADE;
DROP TABLE IF EXISTS pegawai CASCADE;

-- =========================================================
-- 1. PEGAWAI / LOGIN
-- =========================================================
CREATE TABLE pegawai (
    id_pegawai SERIAL PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    role VARCHAR(20) NOT NULL CHECK (role IN ('karyawan', 'superadmin')),
    uname VARCHAR(30) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Akun contoh. Ganti password_hash_bcrypt dengan hash bcrypt hasil
-- password_hash('passwordanda', PASSWORD_BCRYPT) dari PHP sebelum dipakai.
INSERT INTO pegawai (nama, role, uname, password) VALUES
('Super Admin', 'superadmin', 'admin', '$2y$12$4r7yJPa3qU0vs8Oa9bzjWuuAAhoTq350RCwclJeNweW6FSVYCHYYy'),
('Karyawan Demo', 'karyawan', 'karyawan', '$2y$12$E8VHd/KTb.k3fTFHt1j9lOAIenqQEJ8v6X5nh9ja1bmJH4fqbzbTa');


-- =========================================================
-- 2. CUSTOMER
-- =========================================================
CREATE TABLE customer (
    id_customer SERIAL PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    username VARCHAR(30),
    password VARCHAR(255)
);

INSERT INTO customer (nama, no_hp) VALUES
('Pelanggan Demo', '081234567890');

-- =========================================================
-- 3. DROP POINT
-- =========================================================
CREATE TABLE drop_point (
    id_drop_point SERIAL PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    alamat_lokasi VARCHAR(150) NOT NULL
);

INSERT INTO drop_point (nama, alamat_lokasi) VALUES
('Surkun', 'Jl. Rlayatan GG 11 No 19 (Surkun)'),
('Jetis', 'Jl. Margobasuki GG IV No 5 Jetis'),
('Kopi Satu Arah', 'Oro-Oro Dowo');

-- =========================================================
-- 4. JENIS BARANG
-- =========================================================
CREATE TABLE jenis_barang (
    id_jenis SERIAL PRIMARY KEY,
    nama_jenis VARCHAR(50) NOT NULL,
    ukuran VARCHAR(30)
);

INSERT INTO jenis_barang (nama_jenis, ukuran) VALUES
('Sepatu', NULL),
('Sandal Strap', NULL),
('Dompet', NULL),
('Topi', NULL),
('Tas', 'XS'),
('Tas', 'S'),
('Tas', 'M'),
('Tas', 'L'),
('Carrier', NULL),
('Helm', 'Half Face'),
('Helm', 'Full Face'),
('Bantal Bayi', NULL),
('Bouncer Bayi', NULL),
('Koper', 'S'),
('Koper', 'M'),
('Koper', 'L'),
('Stroller', 'S'),
('Stroller', 'M'),
('Stroller', 'L'),
('Car Seat', 'S'),
('Car Seat', 'M'),
('Car Seat', 'L');

-- =========================================================
-- 5. LAYANAN
-- kategori: Deep Clean Shoes Care, Deep Clean Others Care, Repair
-- harga_min dan harga_max untuk harga tetap / rentang / start from.
-- estimasi_min_hari dan estimasi_max_hari adalah estimasi hari.
-- =========================================================
CREATE TABLE layanan (
    id_layanan SERIAL PRIMARY KEY,
    nama_layanan VARCHAR(100) NOT NULL,
    kategori VARCHAR(40) NOT NULL,
    id_jenis INTEGER REFERENCES jenis_barang(id_jenis),
    variasi VARCHAR(30),
    harga_min INTEGER NOT NULL CHECK (harga_min >= 0),
    harga_max INTEGER,
    estimasi_min_hari INTEGER NOT NULL DEFAULT 1,
    estimasi_max_hari INTEGER NOT NULL DEFAULT 1,
    keterangan TEXT,
    CHECK (harga_max IS NULL OR harga_max >= harga_min),
    CHECK (estimasi_max_hari >= estimasi_min_hari)
);

-- Deep Clean Shoes Care (4-5 hari)
INSERT INTO layanan
(nama_layanan, kategori, id_jenis, variasi, harga_min, harga_max, estimasi_min_hari, estimasi_max_hari, keterangan)
VALUES
('Dark Shoes', 'Deep Clean Shoes Care', 1, NULL, 25000, 25000, 4, 5, 'Mencuci seluruh bagian sepatu'),
('Basic & White Shoes', 'Deep Clean Shoes Care', 1, NULL, 30000, 30000, 4, 5, 'Mencuci seluruh bagian sepatu'),
('Leather/Suede/Bludru', 'Deep Clean Shoes Care', 1, NULL, 30000, 30000, 4, 5, 'Mencuci seluruh bagian sepatu'),
('Premium Deep Clean', 'Deep Clean Shoes Care', 1, NULL, 50000, 50000, 4, 5, 'Mencuci seluruh bagian sepatu'),
('Caterpillar/Boots', 'Deep Clean Shoes Care', 1, NULL, 35000, 35000, 4, 5, 'Mencuci seluruh bagian sepatu'),
('Flatshoes', 'Deep Clean Shoes Care', 1, NULL, 20000, 20000, 4, 5, 'Mencuci seluruh bagian sepatu'),
('Heels', 'Deep Clean Shoes Care', 1, NULL, 25000, 25000, 4, 5, 'Mencuci seluruh bagian sepatu'),
('Kids Shoes (Under Size 35)', 'Deep Clean Shoes Care', 1, 'Under Size 35', 20000, 20000, 4, 5, 'Sepatu anak ukuran di bawah 35'),
('Kids White Shoes', 'Deep Clean Shoes Care', 1, NULL, 25000, 25000, 4, 5, 'Sepatu putih anak'),
('Sandal Strap', 'Deep Clean Shoes Care', 2, NULL, 20000, 20000, 4, 5, 'Mencuci seluruh bagian sandal');

-- Deep Clean Others Care (3-7 hari)
INSERT INTO layanan
(nama_layanan, kategori, id_jenis, variasi, harga_min, harga_max, estimasi_min_hari, estimasi_max_hari, keterangan)
VALUES
('Dompet', 'Deep Clean Others Care', 3, NULL, 20000, 20000, 3, 7, 'Mencuci seluruh bagian dompet'),
('Topi', 'Deep Clean Others Care', 4, NULL, 15000, 15000, 3, 7, 'Mencuci seluruh bagian topi'),
('Tas', 'Deep Clean Others Care', 5, 'XS', 25000, 25000, 3, 7, 'Cuci tas ukuran XS'),
('Tas', 'Deep Clean Others Care', 6, 'S', 30000, 30000, 3, 7, 'Cuci tas ukuran S'),
('Tas', 'Deep Clean Others Care', 7, 'M', 35000, 35000, 3, 7, 'Cuci tas ukuran M'),
('Tas', 'Deep Clean Others Care', 8, 'L', 40000, 40000, 3, 7, 'Cuci tas ukuran L'),
('Carrier', 'Deep Clean Others Care', 9, NULL, 40000, 60000, 3, 7, 'Harga Rp40.000-Rp60.000'),
('Helm', 'Deep Clean Others Care', 10, 'Half Face', 30000, 30000, 3, 7, 'Cuci helm half face'),
('Helm', 'Deep Clean Others Care', 11, 'Full Face', 40000, 40000, 3, 7, 'Cuci helm full face'),
('Bantal Bayi', 'Deep Clean Others Care', 12, NULL, 25000, NULL, 3, 7, 'Harga mulai dari Rp25.000'),
('Bouncer Bayi', 'Deep Clean Others Care', 13, NULL, 50000, NULL, 3, 7, 'Harga mulai dari Rp50.000'),
('Koper', 'Deep Clean Others Care', 14, 'S', 60000, 60000, 3, 7, 'Koper ukuran S'),
('Koper', 'Deep Clean Others Care', 15, 'M', 80000, 80000, 3, 7, 'Koper ukuran M'),
('Koper', 'Deep Clean Others Care', 16, 'L', 100000, 100000, 3, 7, 'Koper ukuran L'),
('Stroller', 'Deep Clean Others Care', 17, 'S', 70000, 70000, 3, 7, 'Stroller ukuran S'),
('Stroller', 'Deep Clean Others Care', 18, 'M', 100000, 100000, 3, 7, 'Stroller ukuran M'),
('Stroller', 'Deep Clean Others Care', 19, 'L', 130000, 130000, 3, 7, 'Stroller ukuran L'),
('Car Seat', 'Deep Clean Others Care', 20, 'S', 70000, 70000, 3, 7, 'Car seat ukuran S'),
('Car Seat', 'Deep Clean Others Care', 21, 'M', 100000, 100000, 3, 7, 'Car seat ukuran M'),
('Car Seat', 'Deep Clean Others Care', 22, 'L', 130000, 130000, 3, 7, 'Car seat ukuran L');

-- Repair sepatu (5-14 hari)
INSERT INTO layanan
(nama_layanan, kategori, id_jenis, variasi, harga_min, harga_max, estimasi_min_hari, estimasi_max_hari, keterangan)
VALUES
('Unyellowing', 'Repair', 1, NULL, 30000, 30000, 5, 14, 'Memutihkan midsole yang menguning'),
('Repaint', 'Repair', 1, NULL, 100000, NULL, 5, 14, 'Mewarnai ulang sepatu + cuci; harga mulai dari'),
('Recolour', 'Repair', 1, NULL, 120000, NULL, 5, 14, 'Mengganti warna sepatu + cuci; harga mulai dari'),
('Reglue Shoes', 'Repair', 1, NULL, 20000, NULL, 5, 14, 'Merekatkan sepatu; harga mulai dari'),
('Whitening', 'Repair', 1, NULL, 30000, 30000, 5, 14, 'Memutihkan sepatu');

-- =========================================================
-- 6. TREATMENT TAMBAHAN / EXTRA TREATMENT
-- =========================================================
CREATE TABLE treatment_tambahan (
    id_treatment_tambahan SERIAL PRIMARY KEY,
    nama_treatment VARCHAR(60) UNIQUE NOT NULL,
    harga_min INTEGER NOT NULL CHECK (harga_min >= 0),
    harga_max INTEGER,
    estimasi_min_hari INTEGER,
    estimasi_max_hari INTEGER,
    keterangan TEXT,
    CHECK (harga_max IS NULL OR harga_max >= harga_min)
);

INSERT INTO treatment_tambahan
(nama_treatment, harga_min, harga_max, estimasi_min_hari, estimasi_max_hari, keterangan)
VALUES
('Express', 10000, 10000, 1, 2, '1-2 hari'),
('Prioritas', 5000, 5000, 3, 3, '3 hari'),
('Pipis Hewan', 15000, 15000, 8, 8, '8 hari'),
('Noda Berat', 5000, 10000, 5, 8, '5-8 hari');

-- =========================================================
-- 7. PAKET
-- =========================================================
CREATE TABLE paket (
    id_paket SERIAL PRIMARY KEY,
    nama_paket VARCHAR(100) UNIQUE NOT NULL,
    harga INTEGER NOT NULL CHECK (harga >= 0),
    estimasi_min_hari INTEGER NOT NULL,
    estimasi_max_hari INTEGER NOT NULL,
    keterangan TEXT,
    CHECK (estimasi_max_hari >= estimasi_min_hari)
);

INSERT INTO paket (nama_paket, harga, estimasi_min_hari, estimasi_max_hari, keterangan)
VALUES
('Dark Shoes + Unyellowing', 45000, 5, 14, 'Cuci bersih dan memutihkan sole'),
('Basic Shoes + Unyellowing', 50000, 5, 14, 'Cuci bersih dan memutihkan sole'),
('White Shoes + Whitening', 35000, 5, 14, 'Cuci bersih dan memutihkan upper (1x)');

-- Relasi paket dengan layanan penyusunnya
CREATE TABLE paket_layanan (
    id_paket_layanan SERIAL PRIMARY KEY,
    id_paket INTEGER NOT NULL REFERENCES paket(id_paket) ON DELETE CASCADE,
    id_layanan INTEGER NOT NULL REFERENCES layanan(id_layanan),
    UNIQUE (id_paket, id_layanan)
);

INSERT INTO paket_layanan (id_paket, id_layanan)
SELECT p.id_paket, l.id_layanan
FROM paket p
JOIN layanan l ON
    (p.nama_paket = 'Dark Shoes + Unyellowing' AND l.nama_layanan IN ('Dark Shoes', 'Unyellowing'))
 OR (p.nama_paket = 'Basic Shoes + Unyellowing' AND l.nama_layanan IN ('Basic & White Shoes', 'Unyellowing'))
 OR (p.nama_paket = 'White Shoes + Whitening' AND l.nama_layanan IN ('Basic & White Shoes', 'Whitening'));

-- =========================================================
-- 8. PESANAN
-- =========================================================

CREATE TABLE pesanan (
    id_pesanan SERIAL PRIMARY KEY,
    kode_pesanan VARCHAR(20) UNIQUE NOT NULL,
    id_pegawai INTEGER REFERENCES pegawai(id_pegawai),
    id_customer INTEGER NOT NULL REFERENCES customer(id_customer),
    id_drop_point INTEGER REFERENCES drop_point(id_drop_point),
    tanggal_masuk DATE NOT NULL DEFAULT CURRENT_DATE,
    status VARCHAR(30) NOT NULL DEFAULT 'Menunggu'
        CHECK (status IN ('Menunggu', 'Diproses', 'Selesai', 'Diambil')),
    metode_bayar VARCHAR(30),
    status_bayar VARCHAR(20) NOT NULL DEFAULT 'Belum Bayar'
        CHECK (status_bayar IN ('Belum Bayar', 'Lunas')),
    tanggal_selesai DATE,
    total_bayar INTEGER NOT NULL DEFAULT 0 CHECK (total_bayar >= 0),
    catatan TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);


-- =========================================================
-- 9. DETAIL PESANAN
-- Simpan foto_before dan foto_sesudah sebagai path file, bukan binary.
-- =========================================================
CREATE TABLE detail_pesanan (
    id_detail_pesanan SERIAL PRIMARY KEY,
    id_pesanan INTEGER NOT NULL REFERENCES pesanan(id_pesanan) ON DELETE CASCADE,
    id_layanan INTEGER REFERENCES layanan(id_layanan),
    id_paket INTEGER REFERENCES paket(id_paket),
    nama_barang VARCHAR(100) NOT NULL,
    merk VARCHAR(100),
    warna VARCHAR(50),
    ukuran VARCHAR(30),
    jumlah INTEGER NOT NULL DEFAULT 1 CHECK (jumlah > 0),
    harga_satuan INTEGER NOT NULL CHECK (harga_satuan >= 0),
    subtotal INTEGER NOT NULL CHECK (subtotal >= 0),
    status_pengerjaan VARCHAR(30) NOT NULL DEFAULT 'Menunggu',
    foto_before VARCHAR(255),
    foto_sesudah VARCHAR(255),
    catatan TEXT
);

-- =========================================================
-- 10. DETAIL TREATMENT TAMBAHAN
-- =========================================================
CREATE TABLE detail_treatment_tambahan (
    id_detail_treatment SERIAL PRIMARY KEY,
    id_detail_pesanan INTEGER NOT NULL REFERENCES detail_pesanan(id_detail_pesanan) ON DELETE CASCADE,
    id_treatment_tambahan INTEGER NOT NULL REFERENCES treatment_tambahan(id_treatment_tambahan),
    harga INTEGER NOT NULL CHECK (harga >= 0)
);

-- Indeks untuk pencarian pesanan dan relasi yang sering digunakan
CREATE INDEX idx_pesanan_customer ON pesanan(id_customer);
CREATE INDEX idx_pesanan_status ON pesanan(status);
CREATE INDEX idx_pesanan_tanggal_masuk ON pesanan(tanggal_masuk);
CREATE INDEX idx_detail_pesanan_pesanan ON detail_pesanan(id_pesanan);
CREATE INDEX idx_layanan_kategori ON layanan(kategori);

COMMIT;
