-- =========================================================
-- MIGRASI REVISI (jalankan SEKALI di database yang sudah berisi data)
-- 1. Dark Shoes + Basic & White Shoes + Leather/Suede/Bludru
--    digabung jadi "Deep Clean Shoes" Rp30.000
-- 2. Kolom harga_manual untuk treatment yang harganya diisi kasir (Noda Berat)
-- Harga di pesanan lama TIDAK berubah (harga_satuan sudah tersimpan per baris).
-- =========================================================

BEGIN;

-- Revisi 1: pakai baris 'Basic & White Shoes' sebagai Deep Clean Shoes
UPDATE layanan
SET
    nama_layanan = 'Deep Clean Shoes',
    harga_min = 30000,
    harga_max = 30000,
    keterangan = 'Mencuci seluruh bagian sepatu (Dark, Basic, White, Leather/Suede/Bludru)'
WHERE layanan.nama_layanan = 'Basic & White Shoes'
    AND layanan.kategori = 'Deep Clean Shoes Care';

-- Pesanan lama yang memakai Dark Shoes / Leather dialihkan ke Deep Clean Shoes
UPDATE detail_pesanan
SET id_layanan = (
    SELECT layanan.id_layanan
    FROM layanan
    WHERE layanan.nama_layanan = 'Deep Clean Shoes'
)
WHERE detail_pesanan.id_layanan IN (
    SELECT layanan.id_layanan
    FROM layanan
    WHERE layanan.nama_layanan IN ('Dark Shoes', 'Leather/Suede/Bludru')
);

-- Relasi paket yang memakai Dark Shoes dialihkan juga
UPDATE paket_layanan
SET id_layanan = (
    SELECT layanan.id_layanan
    FROM layanan
    WHERE layanan.nama_layanan = 'Deep Clean Shoes'
)
WHERE paket_layanan.id_layanan IN (
    SELECT layanan.id_layanan
    FROM layanan
    WHERE layanan.nama_layanan IN ('Dark Shoes', 'Leather/Suede/Bludru')
);

DELETE FROM layanan
WHERE layanan.nama_layanan IN ('Dark Shoes', 'Leather/Suede/Bludru');

-- Revisi 2: harga Noda Berat diisi kasir
ALTER TABLE treatment_tambahan
ADD COLUMN IF NOT EXISTS harga_manual BOOLEAN NOT NULL DEFAULT FALSE;

UPDATE treatment_tambahan
SET harga_manual = TRUE
WHERE treatment_tambahan.nama_treatment = 'Noda Berat';

COMMIT;