<?php
$page_title = 'Edit Pesanan #0881 - KickDropShoe';
$current_page = 'pesanan';
require_once __DIR__ . '/../layouts/header.php';
?>
<div class="flex h-screen overflow-hidden bg-brand-slateBg">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-y-auto relative pb-28">
        <header class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 md:py-5 flex justify-between items-center sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <!-- Mobile Hamburger Button -->
                <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition focus:outline-none" aria-label="Menu">
                    <i class="fas fa-bars text-base"></i>
                </button>
                <div>
                    <h2 class="text-lg md:text-xl font-extrabold text-slate-900 tracking-tight leading-tight">Edit Pesanan <?= htmlspecialchars($pesanan['kode_pesanan']) ?></h2>
                    <p class="text-xs font-semibold text-slate-400 mt-0.5 hidden sm:block">Perbarui data pelanggan, barang, dan pengerjaan pesanan.</p>
                </div>
            </div>
            <div class="text-xs font-bold text-slate-600 bg-slate-100 px-3 md:px-4 py-1.5 md:py-2 rounded-full border border-slate-200 shrink-0">
                Total: <span class="font-extrabold text-slate-900 grand-total-val">Rp <?= htmlspecialchars(number_format((float) ($pesanan['total_bayar'] ?? 0), 0, ',', '.')) ?></span>
            </div>
        </header>

        <div class="p-4 md:p-8 max-w-6xl mx-auto w-full space-y-6">
            <div>
                <a href="/pesanan" class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 hover:text-slate-900">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <form action="">

            </form>
            <div class="bg-white rounded-3xl border border-slate-200 p-6 md:p-8 shadow-sm space-y-5">
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-user-circle text-slate-400"></i> Informasi Pelanggan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-5">
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">NAMA PELANGGAN</label>
                        <input type="text" name="nama_customer" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none" value="<?= htmlspecialchars($pesanan['nama_customer']) ?>" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">NO WHATSAPP</label>
                        <input type="text" name="no_wa" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none" value="<?= htmlspecialchars($pesanan['no_hp']) ?>" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">DROP POINT</label>
                        <select name="drop_point" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none" required>
                            <?php foreach ($dropPoints as $item): ?>
                                <option value="<?= $item['id_drop_point'] ?>" <?= (int) $item['id_drop_point'] === (int) $pesanan['id_drop_point'] ? 'selected' : '' ?>><?= htmlspecialchars($item['alamat_lokasi']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 md:gap-4 pt-2">
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">TANGGAL MASUK</label>
                        <input name="tgl_masuk" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none" value="<?= htmlspecialchars(date('d-m-Y', strtotime($pesanan['tanggal_masuk']))) ?>" readonly required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">TANGGAL SELESAI</label>
                        <input name="tgl_selesai" type="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none" placeholder="dd/mm/yyyy">
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">KODE PESANAN</label>
                        <input type="text" name="kode_pesanan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none" value="<?= htmlspecialchars($pesanan['kode_pesanan']) ?>" readonly required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">STATUS PENGERJAAN</label>
                        <select class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-xs font-semibold text-slate-800 outline-none" required>
                            <option class="bg-slate-900 text-white" value="Menunggu" <?= $pesanan['status'] === 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                            <option class="bg-slate-900 text-white" value="Diproses" <?= $pesanan['status'] === 'Diproses' ? 'selected' : '' ?>>Sedang Diproses</option>
                            <option class="bg-slate-900 text-white" value="Selesai" <?= $pesanan['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai Diproses</option>
                            <option class="bg-slate-900 text-white" value="Diambil" <?= $pesanan['status'] === 'Diambil' ? 'selected' : '' ?>>Diambil</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">STATUS BAYAR</label>
                        <select class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-xs font-semibold text-slate-800 outline-none" required>
                            <option value="Belum Bayar" <?= $pesanan['metode_bayar'] === 'Belum Bayar' ? 'selected' : '' ?>>Belum Bayar – <?= htmlspecialchars($pesanan['metode_bayar']) ?></option>
                            <option value="LUNAS" <?= $pesanan['metode_bayar'] === 'LUNAS' ? 'selected' : '' ?>>LUNAS – <?= htmlspecialchars($pesanan['metode_bayar']) ?></option>
                        </select>
                    </div>
                </div>
            </div>

            <?php foreach ($barang as $index => $item): ?>
                <input type="hidden" name="id_detail_pesanan[<?= $item['id_detail_pesanan'] ?>]" value="<?= $item['id_detail_pesanan'] ?>">
                <div id="item-container-<?= $item['id_detail_pesanan'] ?>" data-item-id="<?= $item['id_detail_pesanan'] ?>" data-selected-category="<?= htmlspecialchars($item['kategori']) ?>" data-selected-service="<?= $item['id_layanan'] ?>" data-previous-category="<?= htmlspecialchars($item['kategori']) ?>" class="item-dark-container bg-brand-darkCard text-white rounded-3xl p-6 md:p-8 shadow-xl space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                        <h3 class="text-sm font-extrabold flex items-center gap-3">
                            <span class="bg-brand-yellow text-slate-900 w-6 h-6 rounded-md flex items-center justify-center text-xs font-black"><?= $index + 1 ?></span> Detail Barang
                        </h3>
                        <button type="button" onclick="deleteItemCard(<?= $item['id_detail_pesanan'] ?>)" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-slate-400 rounded-lg text-xs font-semibold transition">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">NAMA BARANG</label>
                                    <input type="text" class="w-full bg-brand-darkInput border border-slate-700 rounded-xl px-4 py-3 text-xs font-medium text-white outline-none" value="<?= htmlspecialchars($item['nama_barang']) ?>" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">WARNA</label>
                                    <input type="text" class="w-full bg-brand-darkInput border border-slate-700 rounded-xl px-4 py-3 text-xs font-medium text-white outline-none" value="<?= htmlspecialchars($item['warna'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="bg-slate-900/60 p-5 rounded-2xl border border-slate-800 space-y-3">
                                <label class="block text-[10px] font-extrabold text-brand-yellow uppercase">KATEGORI & LAYANAN UTAMA</label>
                                <div class="category-buttons grid grid-cols-2 sm:grid-cols-4 gap-2">
                                </div>

                                <p class="category-caption text-[9px] text-slate-500 font-semibold">Deep Clean Shoes Care</p>
                                <select name="layanan_id[<?= $item['id_detail_pesanan'] ?>]" id="service-select-<?= $item['id_detail_pesanan'] ?>" class="service-select w-full bg-brand-darkInput border border-slate-700 rounded-xl px-3 py-2.5 text-xs text-white font-semibold" required>
                                    <option value="">Pilih Layanan</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-4 flex flex-col justify-between">
                            <div class="bg-slate-900/80 p-5 rounded-2xl border border-slate-800 space-y-4">
                                <label class="block text-[10px] font-extrabold text-brand-yellow uppercase tracking-wider">EXTRA TREATMENT</label>

                                <div class="grid grid-cols-3 gap-2 speed-container">
                                </div>

                                <div class="space-y-2 pt-1 extra-treatment-container">
                                </div>
                            </div>

                            <div class="bg-black/60 p-4 rounded-xl border border-slate-800 flex justify-between items-center">
                                <span class="text-xs font-bold text-slate-400">Subtotal Barang Ini:</span>
                                <span id="subtotal-val-<?= $item['id_detail_pesanan'] ?>" class="subtotal-val text-lg font-black text-brand-yellow">Rp <?= number_format((float) $item['subtotal'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 sm:gap-6 pt-3 border-t border-slate-800 items-stretch">
                        <div class="flex flex-col">
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-2 h-7 sm:h-auto flex items-end leading-tight">FOTO BEFORE (SEBELUM)</label>
                            <div class="upload-box-trigger border-2 border-dashed border-slate-700 rounded-2xl p-3 sm:p-4 h-28 sm:h-32 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-slate-800/40 transition">
                                <?php if (!empty($item['foto_before'])): ?>
                                    <img src="/<?= htmlspecialchars($item['foto_before']) ?>" alt="Foto Before" class="w-full h-full object-cover rounded-xl">
                                <?php else: ?>
                                    <i class="fas fa-upload text-slate-400 text-base sm:text-lg mb-1"></i>
                                    <span class="text-[11px] sm:text-xs font-bold text-white">Upload Foto</span>
                                    <span class="text-[9px] sm:text-[10px] text-slate-500 mt-0.5">Maks. 5 MB</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-2 h-7 sm:h-auto flex items-end leading-tight">CATATAN TAMBAHAN</label>
                            <textarea class="w-full bg-brand-darkInput border border-slate-700 rounded-2xl p-3 sm:p-4 text-xs font-medium text-white outline-none h-28 sm:h-32 resize-none" placeholder="–"><?= htmlspecialchars($item['catatan'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <button type="button" class="add-item-btn-trigger w-full py-4 border-2 border-dashed border-slate-300 rounded-3xl text-slate-500 font-extrabold text-xs hover:bg-white hover:border-slate-400 hover:text-slate-800 transition flex items-center justify-center gap-2">
                <i class="fas fa-plus-circle"></i> Tambah Barang Cuci / Repair
            </button>

            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-4">
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Dokumentasi Foto Sebelum & Sesudah</h3>
                <p class="text-xs font-bold text-slate-700 mb-2">Foto Before / Sebelum Dicuci</p>
                <div class="grid <?= count($barang) === 1 ? 'grid-cols-1' : 'grid-cols-2' ?> gap-6">
                    <?php foreach ($barang as $item): ?>
                        <div>
                            <p class="text-xs font-extrabold text-slate-800 mb-1">
                                <?= htmlspecialchars($item['nama_barang']) ?>
                            </p>

                            <div class="w-full h-44 bg-slate-200 rounded-2xl overflow-hidden border border-slate-200">
                                <?php if (!empty($item['foto_before'])): ?>
                                    <img src="/<?= htmlspecialchars($item['foto_before']) ?>" alt="Before" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-xs text-slate-400">
                                        Foto belum tersedia
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <p class="text-xs font-bold text-slate-700 mb-2">Foto After / Sesudah Dicuci</p>
                <div class="grid <?= count($barang) === 1 ? 'grid-cols-1' : 'grid-cols-2' ?> gap-6 mt-6">
                    <?php foreach ($barang as $item): ?>
                        <div>
                            <p class="text-xs font-extrabold text-slate-800 mb-1">
                                <?= htmlspecialchars($item['nama_barang']) ?>
                            </p>

                            <div class="upload-box-trigger w-full h-44 border-2 border-dashed border-slate-300 rounded-2xl flex flex-col items-center justify-center text-center p-4 hover:bg-slate-50 cursor-pointer">
                                <i class="fas fa-upload text-slate-400 text-xl mb-2"></i>
                                <span class="text-xs font-extrabold text-slate-700">Pilih Foto After</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">JPG atau PNG • Maks. 5 MB</span>
                            </div>

                            <p class="text-[10px] text-slate-400 mt-1">
                                Belum diunggah • Unggah hasil pengerjaan sebelum mengirim dokumentasi.
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="fixed bottom-0 right-0 w-full lg:w-[calc(100%-280px)] bg-white border-t border-slate-200 px-4 md:px-8 py-3.5 md:py-4 flex flex-col sm:flex-row justify-between items-center gap-3 z-30 shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
            <div class="w-full sm:w-auto flex justify-between sm:block">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Total Tagihan Keseluruhan</p>
                <h3 class="text-2xl md:text-3xl font-black text-slate-900 grand-total-val">Rp <?= htmlspecialchars(number_format((float) ($pesanan['total_bayar'] ?? 0), 0, ',', '.')) ?></h3>
            </div>
            <div class="flex items-center gap-2 md:gap-4 w-full sm:w-auto justify-end">
                <div class="flex items-center gap-1.5 md:gap-2">
                    <span class="text-xs font-bold text-slate-500 hidden sm:inline">Metode pembayaran</span>
                    <select class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 md:px-3 py-2 text-xs font-extrabold text-slate-800 outline-none">
                        <option value="QRIS" <?= $pesanan['metode_bayar'] === 'QRIS' ? 'selected' : '' ?>>QRIS</option>
                        <option value="Cash" <?= $pesanan['metode_bayar'] === 'Cash' ? 'selected' : '' ?>>Cash</option>
                        <option value="Kartu" <?= $pesanan['metode_bayar'] === 'Kartu' ? 'selected' : '' ?>>Kartu</option>
                    </select>
                </div>
                <a href="pesanan.php" class="px-4 md:px-6 py-2.5 md:py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold rounded-xl text-xs transition inline-flex items-center">Batal</a>
                <button type="button" onclick="alert('Perubahan Berhasil Disimpan!')" class="flex-1 sm:flex-none justify-center px-4 md:px-6 py-2.5 md:py-3 bg-brand-yellow hover:bg-brand-yellowHover text-slate-900 font-extrabold rounded-xl text-xs transition shadow-lg shadow-brand-yellow/30">Simpan Perubahan</button>
            </div>
        </div>
    </main>
</div>
<script>
    window.PRICELIST_DATA = <?= json_encode(
                                array_reduce($getAllLayanan, function ($result, $item) {
                                    $kategori = $item['kategori'];

                                    if ($kategori === 'Package') {
                                        return $result;
                                    }

                                    if (!isset($result[$kategori])) {
                                        $result[$kategori] = [];
                                    }

                                    $result[$kategori][] = [
                                        'id' => (int) $item['id_layanan'],
                                        'name' => $item['nama_layanan'],
                                        'price' => (float) $item['harga_min'],
                                        'estimasi_min' => (int) $item['estimasi_min_hari'],
                                        'estimasi_max' => (int) $item['estimasi_max_hari'],
                                        'variasi' => $item['variasi']
                                    ];

                                    return $result;
                                }, [
                                    'Package' => array_map(function ($paket) {
                                        return [
                                            'id' => (int) $paket['id_paket'],
                                            'name' => $paket['nama_paket'],
                                            'price' => (float) $paket['harga'],
                                            'estimasi_min' => (int) $paket['estimasi_min_hari'],
                                            'estimasi_max' => (int) $paket['estimasi_max_hari'],
                                            'variasi' => null
                                        ];
                                    }, $getAllPaket)
                                ])
                            ) ?>;

    window.LAYANAN_DATA = <?= json_encode(
                                array_values(array_unique(array_column($layanan, 'kategori')))
                            ) ?>;

    window.TREATMENT_KECEPATAN = <?= json_encode(
                                        $treatmentKecepatan
                                    ) ?>;

    window.TREATMENT_TAMBAHAN = <?= json_encode(
                                    $treatmentTambahan
                                ) ?>;

    window.EDIT_BARANG_DATA = <?= json_encode(
                                    array_map(function ($item) {
                                        return [
                                            'id_detail_pesanan' => (int) $item['id_detail_pesanan'],
                                            'id_layanan' => (int) $item['id_layanan'],
                                            'nama_barang' => $item['nama_barang'],
                                            'warna' => $item['warna'] ?? '',
                                            'merk' => $item['merk'] ?? '',
                                            'subtotal' => (float) $item['subtotal'],
                                            'harga_satuan' => (float) $item['harga_satuan'],
                                            'treatments' => $item['treatments'] ?? []
                                        ];
                                    }, $barang)
                                ) ?>;

    window.TREATMENT_SELECTED = <?= json_encode(array_reduce($barang, function ($result, $item) {
                                    $idDetail = $item['id_detail_pesanan'];
                                    $result[$idDetail] = [
                                        'speed_id' => null,
                                        'extra_ids' => []
                                    ];

                                    foreach ($item['treatments'] ?? [] as $treatment) {
                                        $idTreatment = (string) $treatment['id_treatment_tambahan'];
                                        $nama = $treatment['nama_treatment'];

                                        if (in_array($nama, ['Express', 'Prioritas'], true)) {
                                            $result[$idDetail]['speed_id'] = $idTreatment;
                                        } else {
                                            $result[$idDetail]['extra_ids'][] = $idTreatment;
                                        }
                                    }

                                    return $result;
                                }, [])) ?>;
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>