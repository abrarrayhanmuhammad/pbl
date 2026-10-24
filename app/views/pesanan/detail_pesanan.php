<?php
$page_title = 'Detail Pesanan ' . $pesanan['kode_pesanan'] . ' - KickDropShoe';
$current_page = 'detail_pesanan';
require_once __DIR__ . '/../layouts/header.php';
?>
<div class="flex h-screen overflow-hidden bg-brand-slateBg">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <?php
        $header_title = 'Detail Pesanan ' . htmlspecialchars($pesanan['kode_pesanan']);
        $header_subtitle = htmlspecialchars($pesanan['nama_customer']) . ' • ' . htmlspecialchars($pesanan['no_hp']);
        require_once __DIR__ . '/../layouts/topbar.php';
        ?>

        <div class="p-4 md:p-8 max-w-5xl mx-auto w-full space-y-6">
            <!-- Navigation Back & Quick Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <a href="/pesanan" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:text-slate-900 hover:bg-slate-50 transition shadow-sm">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
                </a>
                <div class="flex items-center gap-2.5 w-full sm:w-auto">
                    <a href="/pesanan/edit?id=<?= htmlspecialchars($pesanan['id_pesanan']) ?>" id="detail-edit-link" class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl text-xs font-extrabold transition flex items-center gap-2">
                        <i class="fas fa-edit"></i> Edit Pesanan
                    </a>
                    <?php if ($user_role === 'superadmin'): ?>
                        <button type="button" onclick="if(confirm('Yakin ingin menghapus pesanan ini?')) window.location.href='pesanan.php';" class="flex-1 sm:flex-none justify-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-xs font-extrabold transition flex items-center gap-2">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Customer Info & Status Pengerjaan Card -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
                <!-- Info Pelanggan -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-3.5 text-xs font-medium">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fas fa-user-circle text-slate-400 text-sm"></i> Informasi Pelanggan
                    </h3>
                    <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-slate-400">Nama Pelanggan:</span><span class="font-extrabold text-slate-900"><?= htmlspecialchars($pesanan['nama_customer']) ?></span></div>
                    <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-slate-400">No WhatsApp:</span><span class="font-bold text-slate-800"><?= htmlspecialchars($pesanan['no_hp']) ?></span></div>
                    <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-slate-400">Drop Point:</span><span class="font-bold text-slate-800"><?= htmlspecialchars($pesanan['alamat_lokasi']) ?></span></div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-400">Tanggal Masuk:</span>
                        <span class="font-bold text-slate-800">
                            <?= htmlspecialchars((new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Asia/Jakarta', IntlDateFormatter::GREGORIAN, 'dd MMMM yyyy'))->format(strtotime($pesanan['tanggal_masuk']))) ?>
                        </span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-400">Estimasi Selesai:</span>
                        <span class="font-bold text-slate-800">
                            <?= htmlspecialchars((new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Asia/Jakarta', IntlDateFormatter::GREGORIAN, 'dd MMMM yyyy'))->format(strtotime($pesanan['estimasi_selesai']))) ?>
                        </span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-400">Tanggal Selesai:</span>
                        <span class="font-bold text-slate-500">
                            <?= !empty($pesanan['tanggal_selesai'])
                                ? htmlspecialchars((new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Asia/Jakarta', IntlDateFormatter::GREGORIAN, 'dd MMMM yyyy'))->format(strtotime($pesanan['tanggal_selesai'])))
                                : 'Belum Selesai' ?>
                        </span>
                    </div>
                    <div class="flex justify-between py-1 items-center">
                        <span class="text-slate-400">Status Bayar:</span>
                        <?php if ($pesanan['status_bayar'] === 'Lunas'): ?>
                            <span class="font-extrabold text-emerald-600 bg-emerald-100 px-2.5 py-1 rounded-md text-[10px]"><?= htmlspecialchars($pesanan['status_bayar']) ?> - <?= htmlspecialchars($pesanan['metode_bayar']) ?></span>
                        <?php else: ?>
                            <span class="font-extrabold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-md text-[10px]"><?= htmlspecialchars($pesanan['status_bayar']) ?> - <?= htmlspecialchars($pesanan['metode_bayar']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Status Pengerjaan Card -->
                <div class="bg-brand-darkCard text-white p-6 rounded-3xl shadow-xl flex flex-col justify-center">
                    <h3 class="text-xs sm:text-sm font-bold text-slate-300 text-center mb-4">Status Pengerjaan</h3>
                    <div class="w-full">
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2 text-left">STATUS SAAT INI</label>
                        <div class="relative w-full">
                            <select id="detail-status-select" data-order-id="<?= (int) $pesanan['id_pesanan'] ?>" class=" w-full appearance-none bg-slate-800/80 border border-slate-700/80 text-white font-bold text-xs sm:text-sm rounded-xl px-4 py-3 pr-10 outline-none focus:border-brand-yellow text-left transition cursor-pointer">
                                <option class="bg-slate-900 text-white" value="Menunggu" <?= $pesanan['status'] === 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                <option class="bg-slate-900 text-white" value="Diproses" <?= $pesanan['status'] === 'Diproses' ? 'selected' : '' ?>>Sedang Diproses</option>
                                <option class="bg-slate-900 text-white" value="Selesai" <?= $pesanan['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai Diproses</option>
                                <option class="bg-slate-900 text-white" value="Diambil" <?= $pesanan['status'] === 'Diambil' ? 'selected' : '' ?>>Diambil</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>

                            <p id="status-save-message" class="mt-2 text-xs"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Barang -->
            <div class="bg-white p-4 sm:p-6 md:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                    Daftar Barang (<?= count($detailBarang) ?>)
                </h4>

                <div class="space-y-3">
                    <?php foreach ($detailBarang as $item): ?>
                        <div class="p-3 sm:p-4 border border-slate-200 rounded-2xl flex items-center gap-3 sm:gap-4 bg-white hover:border-slate-300 transition">

                            <div class="w-14 h-14 sm:w-16 sm:h-16 shrink-0 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 border border-slate-200 overflow-hidden">
                                <?php if (!empty($item['foto_before'])): ?>
                                    <img src="/<?= htmlspecialchars($item['foto_before']) ?>" alt="Foto barang" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-tshirt text-lg"></i>
                                <?php endif; ?>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h5 class="font-extrabold text-slate-900 text-sm">
                                        <?= htmlspecialchars($item['nama_barang']) ?>
                                    </h5>

                                    <?php if (!empty($item['warna'])): ?>
                                        <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                            <?= htmlspecialchars($item['warna']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="text-xs text-slate-500 space-y-0.5 mt-1 font-medium">
                                    <p class="leading-relaxed break-words">
                                        <?= htmlspecialchars($item['nama_layanan']) ?> (<?= htmlspecialchars(number_format(($item['harga_satuan'] ?? 0), 0, ',', '.')); ?>)
                                    </p>

                                    <?php foreach ($item['treatments'] as $treat): ?>
                                        <p class="leading-relaxed break-words">
                                            <?= htmlspecialchars($treat['nama_treatment']) ?> (<?= htmlspecialchars(number_format(($treat['harga'] ?? 0), 0, ',', '.')); ?>)
                                        </p>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="shrink-0 self-center text-right">
                                <span class="font-black text-slate-900 text-sm whitespace-nowrap">
                                    <?= htmlspecialchars(number_format(($item['subtotal'] ?? 0), 0, ',', '.')) ?>
                                </span>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-4">
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Dokumentasi Foto Sebelum & Sesudah</h3>
                <p class="text-xs font-bold text-slate-700 mb-2">Foto Before / Sebelum Dicuci</p>
                <div class="grid <?= count($detailBarang) === 1 ? 'grid-cols-1' : 'grid-cols-2' ?> gap-6">
                    <?php foreach ($detailBarang as $item): ?>
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
                <div class="grid <?= count($detailBarang) === 1 ? 'grid-cols-1' : 'grid-cols-2' ?> gap-6 mt-6">
                    <?php foreach ($detailBarang as $item): ?>
                        <div>
                            <p class="text-xs font-extrabold text-slate-800 mb-1">
                                <?= htmlspecialchars($item['nama_barang']) ?>
                            </p>

                            <div class="w-full h-44 bg-slate-200 rounded-2xl overflow-hidden border border-slate-200">
                                <?php if (!empty($item['foto_sesudah'])): ?>
                                    <img src="/<?= htmlspecialchars($item['foto_sesudah']) ?>" alt="After" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="upload-box-trigger w-full h-44 border-2 border-dashed border-slate-300 rounded-2xl flex flex-col items-center justify-center text-center p-4 hover:bg-slate-50 cursor-pointer">
                                        <p class="text-[10px] text-slate-400 mt-1">
                                            Belum diunggah
                                        </p>
                                    </div>

                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Footer Summary & Cetak Invoice -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="w-full sm:w-auto flex justify-between sm:block">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Total Tagihan Keseluruhan</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-900">Rp <?= htmlspecialchars(number_format(($pesanan['total_bayar'] ?? 0), 0, ',', '.')) ?></h3>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="/invoice?id= <?= htmlspecialchars($pesanan['id_pesanan']) ?>" class="w-full sm:w-auto text-center px-6 py-3.5 bg-brand-darkCard hover:bg-slate-800 text-white rounded-xl text-xs font-extrabold transition shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-print"></i> Cetak Invoice
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>
<div id="csrf-data" data-token="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></div>
<script>
    const statusSelect = document.getElementById('detail-status-select');
    const statusMessage = document.getElementById('status-save-message');

    if (statusSelect) {
        let statusSebelumnya = statusSelect.value;

        statusSelect.addEventListener('change', async function() {
            const statusBaru = this.value;
            const idPesanan = this.dataset.orderId;

            statusSelect.disabled = true;
            statusMessage.className = 'mt-2 text-xs text-yellow-400';

            try {
                const response = await fetch('/pesanan/updateStatus', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        id_pesanan: idPesanan,
                        status: statusBaru,
                        csrf_token: document.getElementById('csrf-data')?.dataset.token
                    })
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'Status gagal disimpan.');
                }

                statusSebelumnya = statusBaru;
                statusMessage.textContent = 'Status berhasil diperbarui.';
                statusMessage.className = 'mt-2 text-xs text-green-400';
            } catch (error) {
                statusSelect.value = statusSebelumnya;
                statusMessage.textContent = error.message || 'Terjadi kesalahan.';
                statusMessage.className = 'mt-2 text-xs text-red-400';
            } finally {
                statusSelect.disabled = false;
            }
        });
    }
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>