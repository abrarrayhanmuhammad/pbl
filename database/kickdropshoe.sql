-- ============================================================
-- DATABASE KICKDROPSHOE
-- SISTEM INFORMASI MANAJEMEN KICKDROPSHOE
-- ============================================================


-- ============================================================
-- 1. HAPUS TABEL LAMA JIKA ADA
-- ============================================================

DROP TABLE IF EXISTS pembayaran CASCADE;
DROP TABLE IF EXISTS detail_pesanan CASCADE;
DROP TABLE IF EXISTS pesanan CASCADE;
DROP TABLE IF EXISTS extra_treatment CASCADE;
DROP TABLE IF EXISTS layanan CASCADE;
DROP TABLE IF EXISTS jenis_barang CASCADE;
DROP TABLE IF EXISTS customer CASCADE;
DROP TABLE IF EXISTS pegawai CASCADE;


-- ============================================================
-- 2. TABEL PEGAWAI
-- ============================================================

CREATE TABLE pegawai (
    id_pegawai SERIAL PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    role VARCHAR(20) NOT NULL,
    uname VARCHAR(30) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);


-- ============================================================
-- 3. TABEL CUSTOMER
-- ============================================================

CREATE TABLE customer (
    id_customer SERIAL PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    no_hp VARCHAR(15) NOT NULL,
    kode_customer VARCHAR(15) UNIQUE
);


-- ============================================================
-- 4. TABEL JENIS BARANG
-- ============================================================

CREATE TABLE jenis_barang (
    id_jenis SERIAL PRIMARY KEY,
    nama_jenis VARCHAR(50) NOT NULL UNIQUE
);


-- ============================================================
-- 5. TABEL LAYANAN
-- ============================================================

CREATE TABLE layanan (
    id_layanan SERIAL PRIMARY KEY,
    id_jenis INTEGER NOT NULL,
    nama_layanan VARCHAR(100) NOT NULL,
    kategori VARCHAR(30) NOT NULL,
    variasi VARCHAR(30),
    harga_min DECIMAL(10,2) NOT NULL,
    harga_max DECIMAL(10,2),
    estimasi_min INTEGER NOT NULL,
    estimasi_max INTEGER NOT NULL,

    FOREIGN KEY (id_jenis)
        REFERENCES jenis_barang(id_jenis)
);


-- ============================================================
-- 6. TABEL EXTRA TREATMENT
-- ============================================================

CREATE TABLE extra_treatment (
    id_treatment SERIAL PRIMARY KEY,
    nama_treatment VARCHAR(50) NOT NULL UNIQUE,
    tambahan_harga_min DECIMAL(10,2) NOT NULL,
    tambahan_harga_max DECIMAL(10,2),
    estimasi_min INTEGER NOT NULL,
    estimasi_max INTEGER NOT NULL
);


-- ============================================================
-- 7. TABEL PESANAN
-- ============================================================

CREATE TABLE pesanan (
    id_pesanan SERIAL PRIMARY KEY,
    tgl_masuk DATE NOT NULL DEFAULT CURRENT_DATE,
    status VARCHAR(30) NOT NULL DEFAULT 'Menunggu',
    id_customer INTEGER NOT NULL,
    id_pegawai INTEGER NOT NULL,

    FOREIGN KEY (id_customer)
        REFERENCES customer(id_customer),

    FOREIGN KEY (id_pegawai)
        REFERENCES pegawai(id_pegawai)
);


-- ============================================================
-- 8. TABEL DETAIL PESANAN
-- ============================================================

CREATE TABLE detail_pesanan (
    id_detail_pesanan SERIAL PRIMARY KEY,
    id_pesanan INTEGER NOT NULL,
    id_jenis INTEGER NOT NULL,
    id_layanan INTEGER NOT NULL,
    id_treatment INTEGER,
    merk VARCHAR(50),
    warna VARCHAR(30),
    variasi VARCHAR(30),
    catatan VARCHAR(255),
    harga_layanan DECIMAL(10,2) NOT NULL,
    harga_treatment DECIMAL(10,2) NOT NULL DEFAULT 0,
    subtotal DECIMAL(10,2) NOT NULL,
    estimasi_min INTEGER NOT NULL,
    estimasi_max INTEGER NOT NULL,
    foto_before VARCHAR(255),
    foto_after VARCHAR(255),

    FOREIGN KEY (id_pesanan)
        REFERENCES pesanan(id_pesanan)
        ON DELETE CASCADE,

    FOREIGN KEY (id_jenis)
        REFERENCES jenis_barang(id_jenis),

    FOREIGN KEY (id_layanan)
        REFERENCES layanan(id_layanan),

    FOREIGN KEY (id_treatment)
        REFERENCES extra_treatment(id_treatment)
);


-- ============================================================
-- 9. TABEL PEMBAYARAN
-- ============================================================

CREATE TABLE pembayaran (
    id_pembayaran SERIAL PRIMARY KEY,
    id_pesanan INTEGER NOT NULL UNIQUE,
    total DECIMAL(10,2) NOT NULL,
    metode VARCHAR(20) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Belum Dibayar',

    tgl_bayar DATE,

    FOREIGN KEY (id_pesanan)
        REFERENCES pesanan(id_pesanan)
        ON DELETE CASCADE
);


-- ============================================================
-- 10. DATA PEGAWAI
-- ============================================================

INSERT INTO pegawai (
    nama,
    role,
    uname,
    password
)
VALUES
(
    'Super Admin',
    'superadmin',
    'admin',
    '$2y$12$4r7yJPa3qU0vs8Oa9bzjWuuAAhoTq350RCwclJeNweW6FSVYCHYYy'
),
(
    'Karyawan',
    'karyawan',
    'karyawan',
    '$2y$12$E8VHd/KTb.k3fTFHt1j9lOAIenqQEJ8v6X5nh9ja1bmJH4fqbzbTa'
);


-- ============================================================
-- 11. DATA CUSTOMER
-- ============================================================

INSERT INTO customer (
    nama,
    no_hp
)
VALUES
    ('Andi', '081234567890'),
    ('Budi', '082345678901'),
    ('Citra', '083456789012');


-- ============================================================
-- 12. DATA JENIS BARANG
-- ============================================================

INSERT INTO jenis_barang (
    nama_jenis
)
VALUES
    ('Sepatu'),
    ('Dompet'),
    ('Topi'),
    ('Tas'),
    ('Helm'),
    ('Bantal Bayi'),
    ('Bouncer Bayi'),
    ('Koper'),
    ('Stroller'),
    ('Car Seat');


-- ============================================================
-- 13. DATA LAYANAN - DEEP CLEAN SHOES
-- ============================================================

INSERT INTO layanan (
    id_jenis,
    nama_layanan,
    kategori,
    variasi,
    harga_min,
    harga_max,
    estimasi_min,
    estimasi_max
)
VALUES

-- Dark Shoes
(
    1,
    'Dark Shoes',
    'Deep Clean',
    NULL,
    25000,
    25000,
    4,
    5
),

-- Basic & White Shoes
(
    1,
    'Basic & White Shoes',
    'Deep Clean',
    NULL,
    30000,
    30000,
    4,
    5
),

-- Leather / Suede / Bludru
(
    1,
    'Leather/Suede/Bludru',
    'Deep Clean',
    NULL,
    30000,
    30000,
    4,
    5
),

-- Premium Deep Clean
(
    1,
    'Premium Deep Clean',
    'Deep Clean',
    NULL,
    50000,
    50000,
    4,
    5
),

-- Caterpillar / Boots
(
    1,
    'Caterpillar/Boots',
    'Deep Clean',
    NULL,
    35000,
    35000,
    4,
    5
),

-- Flatshoes
(
    1,
    'Flatshoes',
    'Deep Clean',
    NULL,
    20000,
    20000,
    4,
    5
),

-- Heels
(
    1,
    'Heels',
    'Deep Clean',
    NULL,
    25000,
    25000,
    4,
    5
),

-- Kids Shoes
(
    1,
    'Kids Shoes',
    'Deep Clean',
    'Under Size 35',
    20000,
    20000,
    4,
    5
),

-- Kids White Shoes
(
    1,
    'Kids White Shoes',
    'Deep Clean',
    NULL,
    25000,
    25000,
    4,
    5
),

-- Sandal Strap
(
    1,
    'Sandal Strap',
    'Deep Clean',
    NULL,
    20000,
    20000,
    4,
    5
);


-- ============================================================
-- 14. DATA LAYANAN - DEEP CLEAN OTHERS
-- ============================================================

-- DOMPET
INSERT INTO layanan (
    id_jenis,
    nama_layanan,
    kategori,
    variasi,
    harga_min,
    harga_max,
    estimasi_min,
    estimasi_max
)
VALUES
(
    2,
    'Deep Clean Dompet',
    'Deep Clean',
    NULL,
    20000,
    20000,
    3,
    7
);


-- TOPI
INSERT INTO layanan (
    id_jenis,
    nama_layanan,
    kategori,
    variasi,
    harga_min,
    harga_max,
    estimasi_min,
    estimasi_max
)
VALUES
(
    3,
    'Deep Clean Topi',
    'Deep Clean',
    NULL,
    15000,
    15000,
    3,
    7
);


-- TAS
INSERT INTO layanan (
    id_jenis,
    nama_layanan,
    kategori,
    variasi,
    harga_min,
    harga_max,
    estimasi_min,
    estimasi_max
)
VALUES
(
    4,
    'Deep Clean Tas',
    'Deep Clean',
    'XS',
    25000,
    25000,
    3,
    7
),
(
    4,
    'Deep Clean Tas',
    'Deep Clean',
    'S',
    30000,
    30000,
    3,
    7
),
(
    4,
    'Deep Clean Tas',
    'Deep Clean',
    'M',
    35000,
    35000,
    3,
    7
),
(
    4,
    'Deep Clean Tas',
    'Deep Clean',
    'L',
    40000,
    40000,
    3,
    7
),
(
    4,
    'Deep Clean Tas',
    'Deep Clean',
    'Carrier',
    40000,
    60000,
    3,
    7
);


-- HELM
INSERT INTO layanan (
    id_jenis,
    nama_layanan,
    kategori,
    variasi,
    harga_min,
    harga_max,
    estimasi_min,
    estimasi_max
)
VALUES
(
    5,
    'Deep Clean Helm',
    'Deep Clean',
    'Half Face',
    30000,
    30000,
    3,
    7
),
(
    5,
    'Deep Clean Helm',
    'Deep Clean',
    'Full Face',
    40000,
    40000,
    3,
    7
);


-- BANTAL BAYI
INSERT INTO layanan (
    id_jenis,
    nama_layanan,
    kategori,
    variasi,
    harga_min,
    harga_max,
    estimasi_min,
    estimasi_max
)
VALUES
(
    6,
    'Deep Clean Bantal Bayi',
    'Deep Clean',
    NULL,
    25000,
    NULL,
    3,
    7
);


-- BOUNCER BAYI
INSERT INTO layanan (
    id_jenis,
    nama_layanan,
    kategori,
    variasi,
    harga_min,
    harga_max,
    estimasi_min,
    estimasi_max
)
VALUES
(
    7,
    'Deep Clean Bouncer Bayi',
    'Deep Clean',
    NULL,
    50000,
    NULL,
    3,
    7
);


-- KOPER
INSERT INTO layanan (
    id_jenis,
    nama_layanan,
    kategori,
    variasi,
    harga_min,
    harga_max,
    estimasi_min,
    estimasi_max
)
VALUES
(
    8,
    'Deep Clean Koper',
    'Deep Clean',
    'S',
    60000,
    60000,
    3,
    7
),
(
    8,
    'Deep Clean Koper',
    'Deep Clean',
    'M',
    80000,
    80000,
    3,
    7
),
(
    8,
    'Deep Clean Koper',
    'Deep Clean',
    'L',
    100000,
    100000,
    3,
    7
);


-- STROLLER
INSERT INTO layanan (
    id_jenis,
    nama_layanan,
    kategori,
    variasi,
    harga_min,
    harga_max,
    estimasi_min,
    estimasi_max
)
VALUES
(
    9,
    'Deep Clean Stroller',
    'Deep Clean',
    'S',
    70000,
    70000,
    3,
    7
),
(
    9,
    'Deep Clean Stroller',
    'Deep Clean',
    'M',
    100000,
    100000,
    3,
    7
),
(
    9,
    'Deep Clean Stroller',
    'Deep Clean',
    'L',
    130000,
    130000,
    3,
    7
);


-- CAR SEAT
INSERT INTO layanan (
    id_jenis,
    nama_layanan,
    kategori,
    variasi,
    harga_min,
    harga_max,
    estimasi_min,
    estimasi_max
)
VALUES
(
    10,
    'Deep Clean Car Seat',
    'Deep Clean',
    'S',
    70000,
    70000,
    3,
    7
),
(
    10,
    'Deep Clean Car Seat',
    'Deep Clean',
    'M',
    100000,
    100000,
    3,
    7
),
(
    10,
    'Deep Clean Car Seat',
    'Deep Clean',
    'L',
    130000,
    130000,
    3,
    7
);


-- ============================================================
-- 15. DATA LAYANAN - REPAIR
-- ============================================================

INSERT INTO layanan (
    id_jenis,
    nama_layanan,
    kategori,
    variasi,
    harga_min,
    harga_max,
    estimasi_min,
    estimasi_max
)
VALUES
(
    1,
    'Unyellowing',
    'Repair',
    NULL,
    30000,
    30000,
    5,
    14
),
(
    1,
    'Repaint',
    'Repair',
    NULL,
    100000,
    NULL,
    5,
    14
),
(
    1,
    'Recolour',
    'Repair',
    NULL,
    120000,
    NULL,
    5,
    14
),
(
    1,
    'Reglue Shoes',
    'Repair',
    NULL,
    20000,
    NULL,
    5,
    14
),
(
    1,
    'Whitening',
    'Repair',
    NULL,
    30000,
    30000,
    5,
    14
);


-- ============================================================
-- 16. DATA LAYANAN - PACKAGE
-- ============================================================

INSERT INTO layanan (
    id_jenis,
    nama_layanan,
    kategori,
    variasi,
    harga_min,
    harga_max,
    estimasi_min,
    estimasi_max
)
VALUES
(
    1,
    'Dark Shoes + Unyellowing',
    'Package',
    NULL,
    45000,
    45000,
    5,
    7
),
(
    1,
    'Basic Shoes + Unyellowing',
    'Package',
    NULL,
    50000,
    50000,
    5,
    7
),
(
    1,
    'White Shoes + Whitening',
    'Package',
    NULL,
    35000,
    35000,
    5,
    7
);


-- ============================================================
-- 17. DATA EXTRA TREATMENT
-- ============================================================

INSERT INTO extra_treatment (
    nama_treatment,
    tambahan_harga_min,
    tambahan_harga_max,
    estimasi_min,
    estimasi_max
)
VALUES
(
    'Express',
    10000,
    10000,
    1,
    2
),
(
    'Prioritas',
    5000,
    5000,
    3,
    3
),
(
    'Pipi Hewan',
    15000,
    15000,
    8,
    8
),
(
    'Noda Berat',
    5000,
    10000,
    5,
    8
);


-- ============================================================
-- 18. DATA CONTOH PESANAN
-- ============================================================

INSERT INTO pesanan (
    tgl_masuk,
    status,
    id_customer,
    id_pegawai
)
VALUES
(
    '2026-09-20',
    'Menunggu',
    1,
    2
),
(
    '2026-09-20',
    'Diproses',
    2,
    2
),
(
    '2026-09-19',
    'Selesai',
    3,
    2
);


-- ============================================================
-- 19. DATA CONTOH DETAIL PESANAN
-- ============================================================

-- PESANAN 1
-- Nike Putih + Basic & White Shoes
INSERT INTO detail_pesanan (
    id_pesanan,
    id_jenis,
    id_layanan,
    id_treatment,
    merk,
    warna,
    variasi,
    catatan,
    harga_layanan,
    harga_treatment,
    subtotal,
    estimasi_min,
    estimasi_max
)
VALUES
(
    1,
    1,
    2,
    NULL,
    'Nike',
    'Putih',
    NULL,
    'Ada noda di bagian upper',
    30000,
    0,
    30000,
    4,
    5
);


-- PESANAN 2
-- Adidas Hitam + Dark Shoes + Express
INSERT INTO detail_pesanan (
    id_pesanan,
    id_jenis,
    id_layanan,
    id_treatment,
    merk,
    warna,
    variasi,
    catatan,
    harga_layanan,
    harga_treatment,
    subtotal,
    estimasi_min,
    estimasi_max
)
VALUES
(
    2,
    1,
    1,
    1,
    'Adidas',
    'Hitam',
    NULL,
    NULL,
    25000,
    10000,
    35000,
    1,
    2
);


-- PESANAN 3
-- Tas Coach ukuran M
INSERT INTO detail_pesanan (
    id_pesanan,
    id_jenis,
    id_layanan,
    id_treatment,
    merk,
    warna,
    variasi,
    catatan,
    harga_layanan,
    harga_treatment,
    subtotal,
    estimasi_min,
    estimasi_max
)
VALUES
(
    3,
    4,
    16,
    NULL,
    'Coach',
    'Coklat',
    'M',
    NULL,
    35000,
    0,
    35000,
    3,
    7
);


-- ============================================================
-- 20. DATA CONTOH PEMBAYARAN
-- ============================================================

INSERT INTO pembayaran (
    id_pesanan,
    total,
    metode,
    status,
    tgl_bayar
)
VALUES
(
    1,
    30000,
    'Cash',
    'Dibayar',
    '2026-09-20'
),
(
    2,
    35000,
    'QRIS',
    'Dibayar',
    '2026-09-20'
),
(
    3,
    35000,
    'Cash',
    'Belum Dibayar',
    NULL
);


-- ============================================================
-- 21. CEK DATA
-- ============================================================

-- Cek pegawai
SELECT * FROM pegawai;

-- Cek customer
SELECT * FROM customer;

-- Cek jenis barang
SELECT * FROM jenis_barang;

-- Cek layanan
SELECT * FROM layanan;

-- Cek extra treatment
SELECT * FROM extra_treatment;

-- Cek pesanan
SELECT * FROM pesanan;

-- Cek detail pesanan
SELECT * FROM detail_pesanan;

-- Cek pembayaran
SELECT * FROM pembayaran;


-- ============================================================
-- 22. QUERY MENAMPILKAN PRICELIST
-- ============================================================

SELECT
    l.id_layanan,
    jb.nama_jenis,
    l.kategori,
    l.nama_layanan,
    l.variasi,
    l.harga_min,
    l.harga_max,
    l.estimasi_min,
    l.estimasi_max
FROM layanan l
JOIN jenis_barang jb
    ON l.id_jenis = jb.id_jenis
ORDER BY
    l.id_layanan;


-- ============================================================
-- 23. QUERY MENAMPILKAN DETAIL PESANAN
-- ============================================================

SELECT
    p.id_pesanan,
    p.tgl_masuk,
    c.nama AS nama_customer,
    c.no_hp,
    jb.nama_jenis,
    dp.merk,
    dp.warna,
    dp.variasi,
    l.nama_layanan,
    et.nama_treatment,
    dp.harga_layanan,
    dp.harga_treatment,
    dp.subtotal,
    dp.estimasi_min,
    dp.estimasi_max,
    p.status
FROM pesanan p

JOIN customer c
    ON p.id_customer = c.id_customer

JOIN detail_pesanan dp
    ON p.id_pesanan = dp.id_pesanan

JOIN jenis_barang jb
    ON dp.id_jenis = jb.id_jenis

JOIN layanan l
    ON dp.id_layanan = l.id_layanan

LEFT JOIN extra_treatment et
    ON dp.id_treatment = et.id_treatment

ORDER BY
    p.id_pesanan DESC;