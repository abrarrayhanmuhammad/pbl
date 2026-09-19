-- =========================================================
-- DATABASE KICKDROPSHOE
-- PostgreSQL
-- =========================================================

-- =========================================================
-- 1. TABEL PEGAWAI
-- =========================================================

CREATE TABLE pegawai (
    id_pegawai SERIAL PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    role VARCHAR(15) NOT NULL,
    username VARCHAR(30) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);


-- =========================================================
-- 2. TABEL CUSTOMER
-- =========================================================

CREATE TABLE customer (
    id_customer SERIAL PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    no_hp VARCHAR(15) NOT NULL
);


-- =========================================================
-- 3. TABEL PEMBAYARAN
-- =========================================================

CREATE TABLE pembayaran (
    id_pembayaran SERIAL PRIMARY KEY,
    total DECIMAL(10,2) NOT NULL,
    metode VARCHAR(20) NOT NULL,
    tgl_bayar DATE NOT NULL
);


-- =========================================================
-- 4. TABEL JENIS BARANG
-- =========================================================

CREATE TABLE jenis_barang (
    id_jenis SERIAL PRIMARY KEY,
    nama_jenis VARCHAR(20) NOT NULL
);


-- =========================================================
-- 5. TABEL LAYANAN
-- =========================================================

CREATE TABLE layanan (
    id_layanan SERIAL PRIMARY KEY,
    nama_layanan VARCHAR(50) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    estimasi_hari INTEGER NOT NULL,
    id_jenis INTEGER NOT NULL,

    CONSTRAINT fk_layanan_jenis FOREIGN KEY (id_jenis) REFERENCES jenis_barang(id_jenis)
);


-- =========================================================
-- 6. TABEL PESANAN
-- =========================================================

CREATE TABLE pesanan (
    id_pesanan SERIAL PRIMARY KEY,
    tgl_masuk DATE NOT NULL,
    status VARCHAR(20) NOT NULL,

    id_pegawai INTEGER NOT NULL,
    id_customer INTEGER NOT NULL,
    id_pembayaran INTEGER NOT NULL,

    CONSTRAINT fk_pesanan_pegawai FOREIGN KEY (id_pegawai) REFERENCES pegawai(id_pegawai),
    CONSTRAINT fk_pesanan_customer FOREIGN KEY (id_customer) REFERENCES customer(id_customer),
    CONSTRAINT fk_pesanan_pembayaranFOREIGN KEY (id_pembayaran) REFERENCES pembayaran(id_pembayaran)
);


-- =========================================================
-- 7. TABEL DETAIL PESANAN
-- =========================================================

CREATE TABLE detail_pesanan (
    id_detail_pesanan SERIAL PRIMARY KEY,
    merk VARCHAR(30) NOT NULL,
    warna VARCHAR(20) NOT NULL,
    note VARCHAR(100),
    subtotal DECIMAL(10,2) NOT NULL,

    id_layanan INTEGER NOT NULL,
    id_pesanan INTEGER NOT NULL,

    CONSTRAINT fk_detail_layananFOREIGN KEY (id_layanan) REFERENCES layanan(id_layanan),
    CONSTRAINT fk_detail_pesanan FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE
);


-- =========================================================
-- DATA DUMMY
-- =========================================================

INSERT INTO pegawai
(nama, role, username, password)
VALUES
(
    'Super Admin',
    'superadmin',
    'admin',
    '$2y$12$4r7yJPa3qU0vs8Oa9bzjWuuAAhoTq350RCwclJeNweW6FSVYCHYYy'
),
(
    'Budi Operator',
    'karyawan',
    'budi',
    '$2y$12$E8VHd/KTb.k3fTFHt1j9lOAIenqQEJ8v6X5nh9ja1bmJH4fqbzbTa'
),
(
    'Andi Operator',
    'karyawan',
    'andi',
    '$2y$12$E8VHd/KTb.k3fTFHt1j9lOAIenqQEJ8v6X5nh9ja1bmJH4fqbzbTa'
);

INSERT INTO customer
(nama, no_hp)
VALUES
('Rizky Pratama', '081234567890'),
('Dinda Maharani', '082234567891'),
('Fajar Ramadhan', '083134567892'),
('Nadia Putri', '084134567893'),
('Arif Setiawan', '085234567894'),
('Salsa Amelia', '086234567895'),
('Bagas Saputra', '087234567896'),
('Citra Lestari', '088234567897');

INSERT INTO jenis_barang
(nama_jenis)
VALUES
('Sepatu'),
('Tas'),
('Helm');

INSERT INTO layanan
(nama_layanan, harga, estimasi_hari, id_jenis)
VALUES
('Deep Cleaning Sepatu', 35000, 3, 1),
('Fast Cleaning Sepatu', 25000, 2, 1),
('Deep Cleaning Tas', 45000, 4, 2),
('Cleaning Tas Premium', 60000, 5, 2),
('Cleaning Helm', 30000, 2, 3),
('Deep Cleaning Helm', 40000, 3, 3);

INSERT INTO pembayaran
(total, metode, tgl_bayar)
VALUES
(35000, 'Cash', '2026-09-13'),
(25000, 'QRIS', '2026-09-13'),
(45000, 'Transfer', '2026-09-14'),
(60000, 'QRIS', '2026-09-15'),
(30000, 'Cash', '2026-09-16'),
(40000, 'Transfer', '2026-09-17'),
(35000, 'QRIS', '2026-09-18'),
(60000, 'Cash', '2026-09-19');

INSERT INTO pesanan
(tgl_masuk, status, id_pegawai, id_customer, id_pembayaran)
VALUES
('2026-09-13', 'Selesai', 2, 1, 1),
('2026-09-13', 'Selesai', 2, 2, 2),
('2026-09-14', 'Diproses', 3, 3, 3),
('2026-09-15', 'Menunggu', 2, 4, 4),
('2026-09-16', 'Selesai', 3, 5, 5),
('2026-09-17', 'Dicuci', 2, 6, 6),
('2026-09-18', 'Diproses', 3, 7, 7),
('2026-09-19', 'Menunggu', 2, 8, 8);

INSERT INTO detail_pesanan
(merk, warna, note, subtotal, id_layanan, id_pesanan)
VALUES
(
    'Nike Air Force 1',
    'Putih',
    'Noda pada bagian depan',
    35000,
    1,
    1
),
(
    'Adidas Superstar',
    'Hitam',
    'Bersihkan bagian sol',
    25000,
    2,
    2
),
(
    'Coach',
    'Coklat',
    'Bersihkan bagian luar tas',
    45000,
    3,
    3
),
(
    'Eiger',
    'Hitam',
    'Tas terkena noda minyak',
    60000,
    4,
    4
),
(
    'Cargloss',
    'Merah',
    'Bersihkan bagian dalam',
    30000,
    5,
    5
),
(
    'INK',
    'Hitam',
    'Bersihkan visor',
    40000,
    6,
    6
),
(
    'Vans Old Skool',
    'Hitam Putih',
    'Noda pada bagian samping',
    35000,
    1,
    7
),
(
    'Nike Dunk',
    'Putih Biru',
    'Deep cleaning',
    60000,
    4,
    8
);