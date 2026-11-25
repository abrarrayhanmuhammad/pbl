<?php
$page_title = 'Edit Pesanan ' .  $pesanan['kode_pesanan'] . '- KickDropShoe';
$current_page = 'edit_pesanan';
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

            <form action="/pesanan/simpanEdit" method="POST" id="form-pesanan" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <input type="hidden" name="id_pesanan" value="<?= (int) $pesanan['id_pesanan'] ?>">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 md:p-8 shadow-sm space-y-5">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-user-circle text-slate-400"></i> Informasi Pelanggan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-5">
                        <div class="relative" id="customer-edit-field">
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">NAMA PELANGGAN</label>
                            <div class="relative">
                                <input type="text" name="nama_customer" id="nama_customer" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 pr-10 text-xs font-semibold text-slate-800 outline-none focus:border-yellow-400" value="<?= htmlspecialchars($pesanan['nama_customer']) ?>" placeholder="Pilih atau masukkan nama pelanggan" autocomplete="off" required>
                                <button type="button" id="toggle-customer-edit-dropdown" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                            </div>
                            <div id="customer-edit-dropdown" class="hidden absolute z-50 top-full left-0 w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden">
                                <div class="max-h-52 overflow-y-auto">
                                    <?php foreach ($customer as $pelanggan): ?>
                                        <button type="button" class="customer-edit-option w-full text-left px-4 py-3 hover:bg-yellow-50 border-b border-slate-100 last:border-0 transition" data-nama="<?= htmlspecialchars($pelanggan['nama']) ?>" data-no-wa="<?= htmlspecialchars($pelanggan['no_hp']) ?>">
                                            <p class="text-xs font-bold text-slate-800"><?= htmlspecialchars($pelanggan['nama']) ?></p>
                                            <p class="text-[10px] text-slate-400 mt-1"><?= htmlspecialchars($pelanggan['no_hp']) ?></p>
                                        </button>
                                    <?php endforeach; ?>
                                    <div id="customer-edit-empty" class="hidden px-4 py-4 text-center text-xs text-slate-400">
                                        Pelanggan tidak ditemukan.<br>Silakan masukkan pelanggan baru.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">NO WHATSAPP</label>
                            <input type="text" name="no_wa" id="no_wa" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none focus:border-yellow-400" value="<?= htmlspecialchars($pesanan['no_hp']) ?>" placeholder="08..." required>
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
                            <input name="tgl_masuk" type="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none" value="<?= htmlspecialchars(date('Y-m-d', strtotime($pesanan['tanggal_masuk']))) ?>" readonly required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">TANGGAL SELESAI</label>
                            <input name="tgl_selesai" type="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none" placeholder="dd/mm/yyyy" value="<?= htmlspecialchars(date('Y-m-d', strtotime($pesanan['tanggal_selesai']))) ?>" <?= ($pesanan['status'] ?? '') !== 'Selesai' ? 'readonly' : '' ?>>
                            <p class="text-[10px] text-amber-600 font-semibold mt-1.5">
                                Tanggal selesai hanya dapat diubah jika status pesanan sudah Selesai.
                            </p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">KODE PESANAN</label>
                            <input type="text" name="kode_pesanan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none" value="<?= htmlspecialchars($pesanan['kode_pesanan']) ?>" readonly required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">STATUS PENGERJAAN</label>
                            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-xs font-semibold text-slate-800 outline-none" required>
                                <option class="bg-slate-900 text-white" value="Menunggu" <?= $pesanan['status'] === 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                <option class="bg-slate-900 text-white" value="Diproses" <?= $pesanan['status'] === 'Diproses' ? 'selected' : '' ?>>Sedang Diproses</option>
                                <option class="bg-slate-900 text-white" value="Selesai" <?= $pesanan['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai Diproses</option>
                                <option class="bg-slate-900 text-white" value="Diambil" <?= $pesanan['status'] === 'Diambil' ? 'selected' : '' ?>>Diambil</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">STATUS BAYAR</label>
                            <select name="status_bayar" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-xs font-semibold text-slate-800 outline-none" required>
                                <option value="Belum Bayar" <?= $pesanan['status_bayar'] === 'Belum Bayar' ? 'selected' : '' ?>>Belum Bayar – <?= htmlspecialchars($pesanan['metode_bayar']) ?></option>
                                <option value="Lunas" <?= $pesanan['status_bayar'] === 'Lunas' ? 'selected' : '' ?>>LUNAS – <?= htmlspecialchars($pesanan['metode_bayar']) ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <?php foreach ($barang as $index => $item): ?>
                    <input type="hidden" name="id_detail_pesanan[<?= $item['id_detail_pesanan'] ?>]" value="<?= $item['id_detail_pesanan'] ?>">
                    <div id="item-container-<?= $item['id_detail_pesanan'] ?>" data-item-id="<?= $item['id_detail_pesanan'] ?>" data-selected-category="<?= htmlspecialchars($item['kategori']) ?>" data-selected-service="<?= (int) ($item['id_layanan'] ?? $item['id_paket'] ?? 0) ?>" data-selected-type="<?= !empty($item['id_layanan']) ? 'layanan' : 'package' ?>" class="item-dark-container bg-brand-darkCard text-white rounded-3xl p-6 md:p-8 shadow-xl space-y-6">
                        <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                            <h3 class="text-sm font-extrabold flex items-center gap-3">
                                <span class="bg-brand-yellow text-slate-900 w-6 h-6 rounded-md flex items-center justify-center text-xs font-black"><?= $index + 1 ?></span> Detail Barang
                            </h3>
                            <button type="button" onclick="deleteEditItemCard(<?= $item['id_detail_pesanan'] ?>)" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-slate-400 rounded-lg text-xs font-semibold transition">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">NAMA BARANG</label>
                                        <input type="text" name="nama_barang[<?= $item['id_detail_pesanan'] ?>]" class="w-full bg-brand-darkInput border border-slate-700 rounded-xl px-4 py-3 text-xs font-medium text-white outline-none" value="<?= htmlspecialchars($item['nama_barang']) ?>" required>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1.5">WARNA</label>
                                        <input type="text" name="warna_barang[<?= $item['id_detail_pesanan'] ?>]" class="w-full bg-brand-darkInput border border-slate-700 rounded-xl px-4 py-3 text-xs font-medium text-white outline-none" value="<?= htmlspecialchars($item['warna'] ?? '') ?>" required>
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

                                    <input type="hidden" name="tipe_layanan[<?= $item['id_detail_pesanan'] ?>]" class="tipe-layanan" value="<?= !empty($item['id_layanan']) ? 'layanan' : 'package' ?>">
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
                                <textarea name="catatan[<?= $item['id_detail_pesanan'] ?>]" class="w-full bg-brand-darkInput border border-slate-700 rounded-2xl p-3 sm:p-4 text-xs font-medium text-white outline-none h-28 sm:h-32 resize-none" placeholder="–"><?= htmlspecialchars($item['catatan'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <button type="button" class="add-edit-item-btn-trigger w-full py-4 border-2 border-dashed border-slate-300 rounded-3xl text-slate-500 font-extrabold text-xs hover:bg-white hover:border-slate-400 hover:text-slate-800 transition flex items-center justify-center gap-2">
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
                            <div class="item-foto-after-container" data-item-id="<?= (int) $item['id_detail_pesanan'] ?>">
                                <p class="text-xs font-extrabold text-slate-800 mb-1">
                                    <?= htmlspecialchars($item['nama_barang']) ?>
                                </p>

                                <div data-photo-type="after">
                                    <?php if (!empty($item['foto_sesudah'])): ?>
                                        <div class="upload-box-trigger-after w-full h-44 bg-slate-200 rounded-2xl overflow-hidden border border-slate-200 cursor-pointer">
                                            <img src="/<?= htmlspecialchars($item['foto_sesudah']) ?>" alt="Before" class="w-full h-full object-cover">
                                        </div>
                                    <?php else: ?>
                                        <div class="upload-box-trigger-after w-full h-44 border-2 border-dashed border-slate-300 rounded-2xl flex flex-col items-center justify-center text-center p-4 hover:bg-slate-50 cursor-pointer">
                                            <i class="fas fa-upload text-slate-400 text-xl mb-2"></i>
                                            <span class="text-xs font-extrabold text-slate-700">Upload Foto After</span>
                                            <span class="text-[10px] text-slate-400 mt-0.5">JPG atau PNG • Maks. 5 MB</span>
                                        </div>
                                        <p class="text-[10px] text-slate-400 mt-1">
                                            Belum diunggah • Unggah hasil pengerjaan sebelum mengirim dokumentasi.
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div id="photo-source-sheet" class="fixed inset-0 z-[9998] hidden">
                        <div id="photo-source-backdrop" class="absolute inset-0 bg-black/60"></div>

                        <div id="photo-source-panel" class="absolute bottom-0 left-0 right-0 bg-slate-900 border-t border-slate-700 rounded-t-3xl p-5 pb-8 transition-transform duration-300 translate-y-full">
                            <div class="w-12 h-1.5 rounded-full bg-slate-600 mx-auto mb-5"></div>

                            <h3 class="text-white font-extrabold text-base">Upload Foto Before</h3>
                            <p class="text-slate-400 text-xs mt-1 mb-5">Pilih sumber foto</p>

                            <button type="button" id="photo-source-camera" class="w-full flex items-center gap-4 p-4 rounded-2xl border border-slate-700 bg-slate-800 text-left mb-3">
                                <i class="fas fa-camera text-yellow-400 text-xl w-8 text-center"></i>
                                <div>
                                    <p class="text-white text-sm font-bold">Buka Kamera</p>
                                    <p class="text-slate-400 text-xs mt-1">Ambil foto langsung</p>
                                </div>
                            </button>

                            <button type="button" id="photo-source-gallery" class="w-full flex items-center gap-4 p-4 rounded-2xl border border-slate-700 bg-slate-800 text-left">
                                <i class="fas fa-images text-blue-400 text-xl w-8 text-center"></i>
                                <div>
                                    <p class="text-white text-sm font-bold">Pilih dari Galeri</p>
                                    <p class="text-slate-400 text-xs mt-1">Pilih foto yang tersimpan</p>
                                </div>
                            </button>

                            <button type="button" id="photo-source-cancel" class="w-full mt-4 py-3 rounded-xl bg-slate-800 text-slate-300 text-sm font-bold">
                                Batal
                            </button>
                        </div>
                    </div>

                    <div id="photo-source-sheet-edit" class="fixed inset-0 z-[9999] hidden">
                        <div id="photo-source-backdrop-edit" class="absolute inset-0 bg-black/60"></div>
                        <div id="photo-source-panel-edit" class="absolute bottom-0 left-0 right-0 bg-slate-900 border-t border-slate-700 rounded-t-3xl p-5 pb-8 transition-transform duration-300 translate-y-full">
                            <div class="w-12 h-1.5 rounded-full bg-slate-600 mx-auto mb-5"></div>
                            <h3 class="text-white font-extrabold text-base">Upload Foto After</h3>
                            <p class="text-slate-400 text-xs mt-1 mb-5">Pilih sumber foto</p>
                            <button type="button" id="photo-source-camera-edit" class="w-full flex items-center gap-4 p-4 rounded-2xl border border-slate-700 bg-slate-800 text-left mb-3">
                                <i class="fas fa-camera text-yellow-400 text-xl w-8 text-center"></i>
                                <div>
                                    <p class="text-white text-sm font-bold">Buka Kamera</p>
                                    <p class="text-slate-400 text-xs mt-1">Ambil foto langsung</p>
                                </div>
                            </button>
                            <button type="button" id="photo-source-gallery-edit" class="w-full flex items-center gap-4 p-4 rounded-2xl border border-slate-700 bg-slate-800 text-left">
                                <i class="fas fa-images text-blue-400 text-xl w-8 text-center"></i>
                                <div>
                                    <p class="text-white text-sm font-bold">Pilih dari Galeri</p>
                                    <p class="text-slate-400 text-xs mt-1">Pilih foto yang tersimpan</p>
                                </div>
                            </button>
                            <button type="button" id="photo-source-cancel-edit" class="w-full mt-4 py-3 rounded-xl bg-slate-800 text-slate-300 text-sm font-bold">
                                Batal
                            </button>
                        </div>
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
                    <span class="bg-slate-100 border border-slate-200 rounded-xl px-2.5 md:px-3 py-2 text-xs font-extrabold text-slate-800">
                        <?= htmlspecialchars($pesanan['metode_bayar'] ?? '-') ?>
                    </span>
                </div>
                <a href="/pesanan" class="px-4 md:px-6 py-2.5 md:py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold rounded-xl text-xs transition inline-flex items-center">Batal</a>
                <button type="submit" class="flex-1 sm:flex-none justify-center px-4 md:px-6 py-2.5 md:py-3 bg-brand-yellow hover:bg-brand-yellowHover text-slate-900 font-extrabold rounded-xl text-xs transition shadow-lg shadow-brand-yellow/30 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> <span class="whitespace-nowrap">Simpan Perubahan</span>
                </button>
            </div>
        </div>
        </form>
    </main>
</div>
<script>
    window.PRICELIST_DATA = <?= json_encode(
                                array_reduce(
                                    $getAllLayanan,
                                    function ($result, $item) {
                                        $kategori = $item['kategori'];

                                        if ($kategori === 'Package') {
                                            return $result;
                                        }

                                        $result[$kategori][] = [
                                            'id' => (int) $item['id_layanan'],
                                            'tipe' => 'layanan',
                                            'name' => $item['nama_layanan'],
                                            'price' => (float) $item['harga_min'],
                                            'estimasi_min' => $item['estimasi_min_hari'],
                                            'estimasi_max' => $item['estimasi_max_hari'],
                                            'variasi' => $item['variasi']
                                        ];

                                        return $result;
                                    },
                                    [
                                        'Package' => array_map(
                                            function ($paket) {
                                                return [
                                                    'id' => (int) $paket['id_paket'],
                                                    'tipe' => 'package',
                                                    'name' => $paket['nama_paket'],
                                                    'price' => (float) $paket['harga'],
                                                    'estimasi_min' => $paket['estimasi_min_hari'],
                                                    'estimasi_max' => $paket['estimasi_max_hari'],
                                                    'variasi' => null
                                                ];
                                            },
                                            $getAllPaket
                                        )
                                    ]
                                )
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
                                        'extra_ids' => [],
                                        'extra_prices' => []
                                    ];

                                    foreach ($item['treatments'] ?? [] as $treatment) {
                                        $idTreatment = (string) $treatment['id_treatment_tambahan'];
                                        $nama = $treatment['nama_treatment'];

                                        if (in_array($nama, ['Express', 'Prioritas'], true)) {
                                            $result[$idDetail]['speed_id'] = $idTreatment;
                                        } else {
                                            $result[$idDetail]['extra_ids'][] = $idTreatment;
                                            $result[$idDetail]['extra_prices'][$idTreatment] = (int) $treatment['harga'];
                                        }
                                    }

                                    return $result;
                                }, [])) ?>;
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>