<?php
$page_title = 'Pratinjau Invoice ' . $pesanan['kode_pesanan'] . '- KickDropShoe';
$current_page = 'invoice';
$is_invoice = true;
$header_title = 'Invoice '. $pesanan['kode_pesanan'];
$body_class = 'font-sans antialiased bg-brand-slateBg print:bg-white';
require_once __DIR__ . '/../layouts/header.php';
?>
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar (Hilang hanya saat dicetak) -->
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-y-auto print:overflow-visible">
        <!-- Header (Hilang hanya saat dicetak) -->
         <?php require_once __DIR__ . '/../layouts/topbar.php';?>

        <div class="p-4 md:p-8 flex flex-col items-center gap-6 print:p-0">
            <!-- Tombol Aksi (Hilang hanya saat dicetak) -->
            <div class="w-full max-w-4xl flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 print:hidden">
                <a href="/pesanan" class="justify-center px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-900 rounded-xl text-sm font-extrabold transition flex items-center gap-2 shadow-sm">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
                </a>
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <button type="button" onclick="window.print()" class="flex-1 sm:flex-none justify-center px-4 md:px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-900 rounded-xl text-xs md:text-sm font-extrabold transition flex items-center gap-2 shadow-sm">
                        <i class="fas fa-file-pdf"></i> Unduh PDF
                    </button>
                    <button type="button" onclick="window.print()" class="flex-1 sm:flex-none justify-center px-4 md:px-5 py-2.5 bg-brand-yellow hover:bg-brand-yellowHover text-slate-900 rounded-xl text-xs md:text-sm font-extrabold transition flex items-center gap-2 shadow-sm">
                        <i class="fas fa-print"></i> Cetak Sekarang
                    </button>
                </div>
            </div>

            <!-- KERTAS INVOICE (Yang akan tercetak) -->
            <div class="w-full max-w-4xl bg-white shadow-xl border border-slate-200 print:shadow-none print:border-none print:max-w-full rounded-2xl md:rounded-3xl overflow-hidden">
                <!-- Garis Kuning Atas -->
                <div class="h-2 w-full bg-brand-yellow"></div>

                               <div class="p-6 sm:p-10 space-y-6 sm:space-y-8">
                    <!-- Header Invoice: Di HP bertumpuk rapi, di Desktop tetap berdampingan persis aslinya -->
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                        <!-- Bagian Kiri (Logo & Alamat) -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-3 mb-2 sm:mb-4">
                                <img src="/assets/logo.png" alt="Logo" class="w-10 h-10 rounded-full shadow-md border-1.5 border-slate-900 object-cover bg-white">
                                <h2 class="text-2xl font-black text-slate-900">KickDropShoe</h2>
                            </div>
                            <p class="text-sm font-medium text-slate-500">Jl. Klayatan GG II NO 19, Malang</p>
                            <p class="text-sm font-medium text-slate-500">Telp 0851-7958-3250</p>
                        </div>

                        <!-- Bagian Kanan (Nomor Invoice) -->
                        <div class="text-left sm:text-right space-y-1 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-0 border-slate-100">
                            <p class="text-lg font-black text-slate-900">NOTA TRANSAKSI</p>
                            <h1 class="text-3xl font-black text-slate-900"><?= htmlspecialchars($pesanan['kode_pesanan']) ?></h1>
                            <p class="text-sm font-medium text-slate-500 mt-1 sm:mt-2">Tanggal: <?= date('d-m-Y', strtotime($pesanan['tanggal_masuk'])) ?></p>
                        </div>
                    </div>

                    <!-- Info Pelanggan: Di HP bertumpuk rapi, di Desktop berdampingan persis aslinya -->
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                        <div class="space-y-1">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">PELANGGAN</p>
                            <p class="text-lg font-black text-slate-900"><?= htmlspecialchars($pesanan['nama_customer']) ?></p>
                            <p class="text-sm font-medium text-slate-500">No WhatsApp: <?= htmlspecialchars($pesanan['no_hp']) ?></p>
                            <p class="text-sm font-medium text-slate-500">Drop Point: <?= htmlspecialchars($pesanan['alamat_lokasi']) ?></p>
                        </div>
                        <div class="text-left sm:text-right space-y-2 w-full sm:w-auto">
                            <?php if ($pesanan['status_bayar'] === 'Belum Bayar'): ?>
                                <div class="inline-block px-3 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-bold mb-1"><?= htmlspecialchars($pesanan['status_bayar']) ?> - <?= htmlspecialchars($pesanan['metode_bayar']) ?></div>
                            <?php else: ?>
                                <div class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-md text-xs font-bold mb-1"><?= htmlspecialchars($pesanan['status_bayar']) ?> - <?= htmlspecialchars($pesanan['metode_bayar']) ?></div>
                            <?php endif; ?>
                            <p class="text-sm font-medium text-slate-500">Status: <?= htmlspecialchars($pesanan['status']) ?></p>
                            <p class="text-sm font-medium text-slate-500">Tanggal Selesai: <?= ($pesanan['tanggal_selesai'] === '' ? htmlspecialchars($pesanan['tanggal_selesai']) : 'Belum Selesai') ?></p>
                        </div>
                    </div>
                    <!-- Tabel Pesanan -->
                    <div class="w-full overflow-x-auto">
                        <div class="min-w-[500px]">
                            <div class="bg-brand-darkCard text-white px-5 py-4 flex font-bold text-sm rounded-t-xl">
                                <div class="flex-1">Deskripsi / Barang</div>
                                <div class="w-20 text-center">Qty</div>
                                <div class="w-32 text-right">Harga</div>
                                <div class="w-32 text-right">Subtotal</div>
                            </div>

                            <!-- Item 1 -->
                            <?php foreach ($barang as $item): ?>
                                <div class="px-5 py-4 border-b border-slate-200 flex text-sm">
                                    <div class="flex-1 space-y-1">
                                        <p class="font-extrabold text-slate-900"><?= htmlspecialchars($item['nama_barang']) ?> • <?= htmlspecialchars($item['warna']) ?></p>
                                        <p class="text-xs font-medium text-slate-500"><?= htmlspecialchars($item['nama_layanan']) ?>: Rp <?= htmlspecialchars(number_format(($item['harga_satuan'] ?? 0), 0, ',', '.')) ?><br>Unyellowing: Rp 30.000<br>Express (1-2 Hari): Rp 10.000</p>
                                    </div>
                                    <div class="w-20 text-center font-medium text-slate-700">1</div>
                                    <div class="w-32 text-right font-medium text-slate-700">Rp <?= htmlspecialchars(number_format(($item['harga_satuan'] ?? 0), 0, ',', '.')) ?></div>
                                    <div class="w-32 text-right font-extrabold text-slate-900">Rp <?= htmlspecialchars(number_format(($item['subtotal'] ?? 0), 0, ',', '.')) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Total Akhir -->
                        <div class="bg-slate-50 p-5 rounded-xl flex justify-between items-center border border-slate-100">
                            <p class="text-base font-bold text-slate-700">Total Akhir</p>
                            <p class="text-3xl font-black text-slate-900">Rp <?= htmlspecialchars(number_format(($pesanan['total_bayar'] ?? 0), 0, ',', '.')) ?></p>
                        </div>

                        <!-- Ketentuan & Footer -->
                        <div class="space-y-6 pt-4">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">KETENTUAN</p>
                                <p class="text-xs font-medium text-slate-700">* Barang yang sudah diambil tidak dapat dikembalikan.</p>
                            </div>
                            <hr class="border-slate-200">
                            <p class="text-center font-bold text-slate-900">Terima kasih atas kepercayaan Anda!</p>
                        </div>
                    </div>
                </div>

                <!-- <p class="text-xs font-medium text-slate-400 print:hidden mt-2">Tombol dan navigasi tidak termasuk dalam hasil cetak.</p> -->
            </div>
    </main>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>