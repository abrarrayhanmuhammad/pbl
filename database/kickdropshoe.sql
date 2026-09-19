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
-- DATA DUMMY LOGIN
-- =========================================================

INSERT INTO pegawai
(nama, role, username, password) VALUES
('Super Admin', 'superadmin', 'admin', '$2y$12$4r7yJPa3qU0vs8Oa9bzjWuuAAhoTq350RCwclJeNweW6FSVYCHYYy'),
('Karyawan KickDropShoe', 'karyawan', 'karyawan',  '$2y$12$E8VHd/KTb.k3fTFHt1j9lOAIenqQEJ8v6X5nh9ja1bmJH4fqbzbTa');