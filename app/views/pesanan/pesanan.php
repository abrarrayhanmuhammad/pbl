<?php
$page_title = 'Kelola Daftar Pesanan - KickDropShoe';
$current_page = 'pesanan';
require_once __DIR__ . '/../layouts/header.php';
?>
<div class="flex h-screen overflow-hidden bg-brand-slateBg">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <?php
        $header_title = 'Kelola Daftar Pesanan';
        require_once __DIR__ . '/../layouts/topbar.php';
        ?>

        <!-- Desktop: Split Master-Detail layout (100% Persis Desktop Asli) | Mobile: List View -->
        <div class="p-4 lg:p-8 flex flex-col lg:flex-row gap-6 lg:h-[calc(100vh-81px)] lg:overflow-hidden">
            <!-- Left Panel: Daftar Pesanan -->
            <div class="w-full lg:w-[380px] flex flex-col gap-4 shrink-0 lg:h-full lg:overflow-hidden">
                <div class="space-y-3 shrink-0">
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                        <input type="text" id="order-search-input" class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-yellow" placeholder="Cari Kode Pesanan / Nama Customer...">
                    </div>
                    <div class="flex gap-2">
                        <select id="order-status-select" class="flex-1 bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 outline-none">
                            <option>Semua Status</option>
                            <option>Menunggu</option>
                            <option>Diproses</option>
                            <option>Selesai</option>
                        </select>
                        <button class="w-10 h-10 bg-brand-darkCard text-white rounded-xl flex items-center justify-center text-xs font-bold shadow-sm">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>

                <div class="flex-1 lg:overflow-y-auto space-y-3 lg:pr-1">
                    <?php if (!empty($dataPesanan)): ?>
                        <?php foreach ($dataPesanan as $pesanan): ?>
                            <?php
                            $status = $pesanan['status'] ?? '';
                            $statusClass = match ($status) {
                                'Menunggu'  => 'bg-slate-100 text-slate-600',
                                'Diproses'  => 'bg-yellow-100 text-yellow-800',
                                'Selesai'   => 'bg-emerald-100 text-emerald-700',
                                'Diambil'   => 'bg-emerald-100 text-emerald-700'
                            };
                            ?>
                            <div class="order-card-item p-5 rounded-2xl border-2 border-slate-200 bg-white shadow-sm cursor-pointer relative transition-all" data-id="<?= (int) $pesanan['id_pesanan'] ?>">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-extrabold text-slate-900 text-sm">
                                        <?= htmlspecialchars($pesanan['kode_pesanan'] ?? '-') ?> – <?= htmlspecialchars($pesanan['nama_customer'] ?? '-') ?>
                                    </h4>

                                    <span class="px-2.5 py-1 <?= $statusClass ?> rounded-md text-[10px] font-extrabold">
                                        <?= htmlspecialchars($status) ?>
                                    </span>
                                </div>

                                <p class="text-xs font-medium text-slate-500">
                                    <?= htmlspecialchars($pesanan['jumlah_barang'] ?? '0') ?> Barang
                                    •
                                    <?= htmlspecialchars($pesanan['nama_layanan'] ?? '-') ?>
                                </p>

                                <div class="flex justify-between items-center mt-2">
                                    <p class="text-sm font-black text-slate-900">
                                        Rp <?= number_format((float) ($pesanan['total_bayar'] ?? 0), 0, ',', '.') ?>
                                    </p>

                                    <a href="/pesanan/detail?id=<?= (int) $pesanan['id_pesanan'] ?>" class="lg:hidden text-xs font-extrabold text-slate-700 flex items-center gap-1">
                                        Detail <i class="fas fa-chevron-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-5 rounded-2xl border border-slate-200 text-center text-sm text-slate-500">
                            Belum ada data pesanan.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Panel: Desktop Master-Detail View (100% Persis Desain Asli Dekstop) -->
            <div class="hidden lg:flex flex-1 bg-white rounded-3xl border border-slate-200 shadow-sm flex-col h-full overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center shrink-0">
                    <div>
                        <h3 id="detail-title" class="text-xl font-extrabold text-slate-900 tracking-tight detail-title">Detail Pesanan</h3>
                        <p id="detail-subtitle" class="text-xs font-semibold text-slate-500 mt-1 detail-subtitle">Pilih pesanan</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a id="detail-edit-link" class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl text-xs font-extrabold transition flex items-center gap-2">
                            <i class="fas fa-edit"></i> Edit Pesanan
                        </a>
                        <?php if ($user_role === 'superadmin'): ?>
                            <?php
                                $pesananAda = !empty($pesanan) && !empty($pesanan['id_pesanan']);
                            ?>
                            <form action="/pesanan/hapus" method="POST" id="form-hapus-pesanan">
                                <input type="hidden" name="id_pesanan" value="<?= $pesananAda ? (int) $pesanan['id_pesanan'] : '' ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

                                <button type="submit" class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-xs font-extrabold transition flex items-center gap-2" <?= !$pesananAda ? 'disabled' : '' ?>>
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
           <!-- Merapihkan -->   
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-slate-50/80 p-5 rounded-2xl border border-slate-100 space-y-3 text-xs font-medium">
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-slate-400 shrink-0 whitespace-nowrap">Tanggal Masuk:</span>
                                <span id="detail-date-in" class="font-bold text-slate-800 text-right detail-date-in">-</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-slate-400 shrink-0 whitespace-nowrap">Status Bayar:</span>
                                <span class="detail-status-pay text-right"><span id="detail-status-pay" class="detail-status-pay font-bold">-</span></span>
                            </div>
                            <div class="flex justify-between items-start gap-2">
                                <span class="text-slate-400 shrink-0 whitespace-nowrap">Drop Point:</span>
                                <span id="detail-drop-point" class="font-bold text-slate-800 detail-drop-point text-[11px] text-right leading-tight">-</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-slate-400 shrink-0 whitespace-nowrap">Estimasi Selesai:</span>
                                <span id="detail-estimasi" class="font-bold text-slate-500 text-right detail-date-out">-</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-slate-400 shrink-0 whitespace-nowrap">Tanggal Selesai:</span>
                                <span id="detail-date-out" class="font-bold text-slate-500 text-right detail-date-out">-</span>
                            </div>
                        </div>

                        <div class="bg-brand-darkCard text-white p-5 rounded-2xl flex flex-col justify-center">
                            <h3 class="text-xs sm:text-sm font-bold text-slate-300 text-center mb-4">Status Pengerjaan</h3>
                            <div class="w-full">
                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2 text-left">STATUS SAAT INI</label>
                                <div class="relative w-full">
                                    <select id="detail-status-select" class="w-full appearance-none bg-slate-800/80 border border-slate-700/80 text-white font-bold text-xs sm:text-sm rounded-xl px-4 py-3 pr-10 outline-none focus:border-brand-yellow text-left transition" disabled>
                                        <option class="bg-slate-900 text-white" value="Menunggu">Menunggu</option>
                                        <option class="bg-slate-900 text-white" value="Diproses">Sedang Diproses</option>
                                        <option class="bg-slate-900 text-white" value="Selesai">Selesai Diproses</option>
                                        <option class="bg-slate-900 text-white" value="Diambil">Diambil</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 id="detail-items-count" class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-3 detail-items-count">Daftar Barang (0 Item)</h4>
                        <div id="detail-items-container" class="space-y-3 detail-items-container">

                        </div>
                    </div>


                    <div>
                        <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-3">Dokumentasi Foto Sebelum & Sesudah</h4>

                        <div class="mb-5">
                            <p class="text-xs font-bold text-slate-700 mb-3">Foto Before / Sebelum Dicuci</p>

                            <div id="detail-photos-before" class="grid gap-4">
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-slate-700 mb-3">Foto After / Sesudah Dicuci</p>

                            <div id="detail-photos-after" class="grid gap-4">
                            </div>
                        </div>

                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-2xl flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand-yellow/20 text-yellow-800 flex items-center justify-center text-sm font-bold">
                                    <i class="fab fa-whatsapp"></i>
                                </div>

                                <div>
                                    <h5 class="text-xs font-extrabold text-slate-900">Kirim Dokumentasi via WhatsApp</h5>
                                    <p id="detail-whatsapp-customer" class="text-[10px] text-slate-500 font-medium">-</p>
                                    <p class="text-[10px] text-yellow-800 font-medium mt-0.5">Foto After wajib tersedia sebelum dokumentasi penyelesaian dikirim.</p>
                                </div>
                            </div>

                            <button id="btn-kirim-dokumentasi" type="button" disabled class="px-4 py-2 bg-slate-200 text-slate-400 rounded-xl text-xs font-bold cursor-not-allowed flex items-center gap-2">
                                <i class="fas fa-paper-plane"></i> Kirim Foto After
                            </button>
                        </div>
                    </div>

                </div>

                <div class="p-6 border-t border-slate-100 flex justify-between items-center shrink-0 bg-white">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Keseluruhan</p>
                        <h3 id="detail-total" class="text-2xl font-black text-slate-900 detail-total">Rp 0</h3>
                    </div>
                    <a id="detail-invoice-link" class="px-6 py-3.5 bg-brand-darkCard hover:bg-slate-800 text-white rounded-xl text-xs font-extrabold transition shadow-lg flex items-center gap-2">
                        <i class="fas fa-print"></i> Cetak Invoice
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
    document.getElementById('form-hapus-pesanan').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;

        Swal.fire({
            title: 'Hapus Pesanan?',
            text: 'Data pesanan yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>