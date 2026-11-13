<?php
$page_title = 'Dashboard Owner - KickDropShoe';
$current_page = 'dashboard';
require_once __DIR__ . '/../layouts/header.php';
?>
<div class="flex h-screen overflow-hidden bg-brand-slateBg">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    
    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <?php 
        $header_title = 'Dashboard Owner';
        require_once __DIR__ . '/../layouts/topbar.php'; 
        ?>

        <!-- Container utama dengan responsif mobile padding dan desktop p-8 -->
        <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-4 sm:space-y-6 pb-36 lg:pb-8">
            
            <!-- Mobile Quick Actions (Clean Tokopedia / Blibli Style) -->
            <div class="grid grid-cols-2 gap-3 lg:hidden">
                <a href="/tambah/karyawan" class="bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-3.5 shadow-sm active:scale-[0.98] transition-all flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-800 flex items-center justify-center text-sm shrink-0 font-bold">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-extrabold text-slate-900 truncate">Karyawan</p>
                        <p class="text-[10px] font-semibold text-slate-400 truncate">Kelola & tambah staf</p>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-300 shrink-0"></i>
                </a>
                <a href="/laporan" class="bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-3.5 shadow-sm active:scale-[0.98] transition-all flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-800 flex items-center justify-center text-sm shrink-0 font-bold">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-extrabold text-slate-900 truncate">Laporan</p>
                        <p class="text-[10px] font-semibold text-slate-400 truncate">Keuangan & omset</p>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-300 shrink-0"></i>
                </a>
            </div>

            <!-- Top Row: 3 Metric Cards (Interaktif di HP ala Tokopedia / Duolingo) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                <div onclick="bukaSheetMetrik('total')" class="col-span-1 bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm cursor-pointer lg:cursor-default active:scale-[0.97] transition-all hover:border-slate-300 group">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider group-hover:text-slate-600 transition-colors">TOTAL PESANAN</p>
                        <i class="fas fa-info-circle text-xs text-slate-300 lg:hidden group-hover:text-slate-500"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl lg:text-5xl font-black text-slate-900"><?= (int) $ringkasan['totalPesanan'] ?></h3>
                </div>
                <div onclick="bukaSheetMetrik('aktif')" class="col-span-1 bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm cursor-pointer lg:cursor-default active:scale-[0.97] transition-all hover:border-slate-300 group">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider group-hover:text-slate-600 transition-colors">AKTIF</p>
                        <i class="fas fa-spinner text-xs text-emerald-400 lg:hidden group-hover:text-emerald-600"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl lg:text-5xl font-black text-emerald-500"><?= (int) $ringkasan['pesananAktif'] ?></h3>
                </div>
                <div onclick="bukaSheetMetrik('pendapatan')" class="col-span-2 sm:col-span-1 bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm cursor-pointer lg:cursor-default active:scale-[0.97] transition-all hover:border-slate-300 group">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider group-hover:text-slate-600 transition-colors">PENDAPATAN</p>
                        <i class="fas fa-wallet text-xs text-slate-300 lg:hidden group-hover:text-slate-500"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl lg:text-5xl font-black text-slate-900">
                    <?php
                    $pendapatan = (int) $ringkasan['pendapatan'];
                    echo $pendapatan >= 1000000
                        ? 'Rp ' . rtrim(rtrim(number_format($pendapatan / 1000000, 1), '0'), '.') . 'M'
                        : 'Rp ' . number_format($pendapatan, 0, ',', '.');
                    ?>
                    </h3>
                </div>
            </div>

            <!-- Bottom Row: Grafik (Kiri 60%) & Pesanan Perlu Perhatian (Kanan 40%) di Desktop | Di HP Bertumpuk Rapi -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 lg:gap-6">
                
                <!-- Diagram Batang Mingguan -->
                <div class="col-span-1 lg:col-span-3 bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-4 sm:p-6 lg:p-8 shadow-sm flex flex-col justify-between">
                    <div>
                        <!-- Judul Grafik & Badge -->
                        <div class="flex justify-between items-center mb-5 sm:mb-6">
                            <h3 class="text-sm font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-brand-yellow inline-block"></span>
                                Pesanan Mingguan
                            </h3>
                            <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full">Pekan Ini</span>
                        </div>

                        <!-- Area Grafik: Sumbu Y Terpisah di Kiri + Batang di Kanan (Tidak Tabrakan di HP) -->
                        <div class="flex gap-2 sm:gap-3 items-end h-52 sm:h-56 pb-2">
                            <!-- Kolom Sumbu Y Khusus di Kiri -->
                            <div class="flex flex-col justify-between h-full text-[10px] font-bold text-slate-400 shrink-0 select-none text-right w-5 sm:w-6 pb-6">
                                <?php for ($i = 5; $i >= 0; $i--): ?>
                                    <span><?= $skalaMaks / 5 * $i ?></span>
                                <?php endfor; ?>
                            </div>

                            <!-- Area Batang & Garis Putus-putus -->
                            <div class="relative flex-1 h-full">
                                <!-- Garis Grid Putus-putus Melintang Sejajar Sumbu Y -->
                                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none pb-6">
                                    <div class="border-b border-dashed border-slate-100 w-full"></div>
                                    <div class="border-b border-dashed border-slate-100 w-full"></div>
                                    <div class="border-b border-dashed border-slate-100 w-full"></div>
                                    <div class="border-b border-dashed border-slate-100 w-full"></div>
                                    <div class="border-b border-dashed border-slate-100 w-full"></div>
                                    <div class="border-b border-slate-200 w-full"></div>
                                </div>

                                <!-- 7 Batang Mingguan dengan Persentase Tinggi Presisi -->
                                <div class="relative z-10 h-full flex items-end justify-between px-1.5 sm:px-4 pb-6">
                                    
                                    <?php foreach ($dataMingguan as $baris):
                                        $jumlah  = (int) $baris['jumlahPesanan'];
                                        $tinggi  = round($jumlah / $skalaMaks * 100);
                                        $nomor   = (int) date('N', strtotime($baris['tanggal']));
                                        $omset   = 'Rp ' . number_format((int) $baris['omset'], 0, ',', '.');
                                        $rincian = (int) $baris['jumlahLunas'] . ' Lunas • ' . ($jumlah - (int) $baris['jumlahLunas']) . ' Belum Bayar';
                                    ?>
                                    <div class="flex flex-col items-center justify-end h-full group cursor-pointer relative"
                                         onclick="pilihBatang(this, <?= htmlspecialchars(json_encode($namaHari[$nomor]), ENT_QUOTES) ?>, <?= $jumlah ?>, <?= htmlspecialchars(json_encode($omset), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($rincian), ENT_QUOTES) ?>)">
                                        <span class="text-[10px] font-extrabold text-slate-600 mb-1 transition-colors group-hover:text-amber-600"><?= $jumlah ?></span>
                                        <div class="chart-bar w-4 sm:w-6 lg:w-8 bg-brand-yellow hover:bg-amber-400 border border-yellow-400 rounded-t-md sm:rounded-t-lg transition-all shadow-sm active:scale-95" style="height: <?= $tinggi ?>%;"></div>
                                        <span class="text-[10px] font-bold text-slate-400 absolute -bottom-5"><?= substr($namaHari[$nomor], 0, 3) ?></span>
                                    </div>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Metrik Sejajar (3 Kolom) -->
                    <div class="mt-6 pt-4 border-t border-slate-100 grid grid-cols-3 gap-1 sm:gap-2 text-center">
                        <div class="border-r border-slate-100 px-1">
                            <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Masuk</span>
                            <span class="text-xs sm:text-sm font-black text-slate-900 mt-0.5 block"><?= $totalMasuk ?> Pesanan</span>
                        </div>
                        <div class="border-r border-slate-100 px-1">
                            <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Rata-Rata</span>
                            <span class="text-xs sm:text-sm font-black text-slate-900 mt-0.5 block"><?= $rataRata ?> / Hari</span>
                        </div>
                        <div class="px-1">
                            <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tersibuk</span>
                            <span class="text-xs sm:text-sm font-black text-slate-900 mt-0.5 block"><?= $hariTersibuk ?><?= $maksHarian > 0 ? " ($maksHarian)" : '' ?></span>
                        </div>
                    </div>
                </div>

                <!-- Pesanan Perlu Perhatian (100% Desain Awal Asli) -->
                <div class="col-span-1 lg:col-span-2 bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm">
                    <h3 class="text-sm font-extrabold text-slate-800 tracking-tight mb-4 sm:mb-6">Pesanan Perlu Perhatian</h3>
                    <div class="space-y-3 sm:space-y-4">
                            <?php if (empty($perluPerhatian)): ?>
                                <p class="text-xs font-semibold text-slate-400">Tidak ada pesanan mendesak.</p>
                            <?php endif; ?>
                            <?php foreach ($perluPerhatian as $item):
                                $sisa = (int) $item['sisaHari'];
                                $teksSisa = $sisa < 0 ? 'Terlambat ' . abs($sisa) . ' Hari' : 'Sisa ' . $sisa . ' Hari';
                            ?>
                            <div class="flex justify-between items-center p-4 sm:p-5 bg-slate-50/80 rounded-2xl border border-slate-100 hover:border-slate-200 transition">
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-sm"><?= htmlspecialchars($item['kodePesanan']) ?> – <?= htmlspecialchars($item['namaCustomer']) ?></h4>
                                    <p class="text-xs font-semibold mt-0.5 <?= $sisa < 0 ? 'text-red-500' : 'text-slate-400' ?>">
                                        <?= htmlspecialchars($item['namaTreatment']) ?> (<?= $teksSisa ?>)
                                    </p>
                                </div>
                                <a href="/pesanan/detail?id=<?= (int) $item['idPesanan'] ?>" class="text-xs font-extrabold text-yellow-700 hover:underline">Cek</a>
                            </div>
                            <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Interactive Mobile Bottom Sheet (Tokopedia / Shopee / Duolingo Style) -->
<div id="mobile-sheet-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300 flex items-end sm:items-center justify-center p-0 sm:p-4" onclick="tutupSheet(event)">
    <div id="mobile-sheet-panel" class="w-full sm:max-w-md bg-white rounded-t-[30px] sm:rounded-3xl shadow-2xl p-5 sm:p-6 transform translate-y-full sm:translate-y-4 transition-transform duration-300 ease-out" onclick="event.stopPropagation()">
        <!-- Pull Handle (Mobile) -->
        <div class="w-12 h-1.5 bg-slate-300 rounded-full mx-auto mb-4 sm:hidden cursor-pointer" onclick="tutupSheetDirect()"></div>
        
        <!-- Header -->
        <div class="flex items-start justify-between gap-3 mb-4">
            <div>
                <span id="sheet-tag" class="inline-block text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 mb-1">Info</span>
                <h3 id="sheet-title" class="text-lg font-black text-slate-900 leading-tight">Detail</h3>
                <p id="sheet-subtitle" class="text-xs font-semibold text-slate-400 mt-0.5">Informasi ringkas</p>
            </div>
            <button type="button" onclick="tutupSheetDirect()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-bold active:scale-90 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Content -->
        <div id="sheet-body" class="space-y-3 mb-5 max-h-[60vh] overflow-y-auto pr-0.5"></div>

        <!-- Action Buttons -->
        <div id="sheet-buttons" class="space-y-2"></div>
    </div>
</div>

<script>
function bukaSheetMetrik(tipe) {
    // Di desktop biarkan normal, di mobile buka interaktif
    if (window.innerWidth >= 1024) return;

    if (tipe === 'total') {
        setSheetContent({
            tag: 'Statistik Pesanan',
            tagClass: 'bg-slate-100 text-slate-800',
            title: <?= json_encode('Total ' . (int) $ringkasan['totalPesanan'] . ' Pesanan') ?>,
            subtitle: 'Rekapitulasi pesanan bulan berjalan',
            bodyHtml: ``,
            buttonsHtml: `
                <a href="/pesanan" class="w-full py-3.5 px-4 bg-brand-yellow hover:bg-yellow-400 text-slate-900 font-extrabold rounded-2xl flex items-center justify-center gap-2 shadow-sm active:scale-[0.98] transition text-sm">
                    Lihat Semua Pesanan
                </a>
            `
        });
    } else if (tipe === 'aktif') {
        setSheetContent({
            tag: 'Antrean Operasional',
            tagClass: 'bg-emerald-100 text-emerald-800',
            title: <?= json_encode((int) $ringkasan['pesananAktif'] . ' Pesanan Aktif') ?>,
            subtitle: 'Status sepatu di workshop saat ini',
            bodyHtml: ``,
            buttonsHtml: `
                <a href="/pesanan" class="w-full py-3.5 px-4 bg-brand-yellow hover:bg-yellow-400 text-slate-900 font-extrabold rounded-2xl flex items-center justify-center gap-2 shadow-sm active:scale-[0.98] transition text-sm">
                    Cek Antrean di Pesanan
                </a>
            `
        });
    } else if (tipe === 'pendapatan') {
        setSheetContent({
            tag: 'Laporan Keuangan',
            tagClass: 'bg-amber-100 text-amber-800',
            title: <?= json_encode('Omset Rp ' . number_format((int) $ringkasan['pendapatan'], 0, ',', '.')) ?>,
            subtitle: 'Total pendapatan terverifikasi bulan ini',
            bodyHtml: ``,
            buttonsHtml: `
                <a href="/laporan" class="w-full py-3.5 px-4 bg-brand-yellow hover:bg-yellow-400 text-slate-900 font-extrabold rounded-2xl flex items-center justify-center gap-2 shadow-sm active:scale-[0.98] transition text-sm">
                    Buka Laporan Keuangan
                </a>
            `
        });
    }

    tampilSheet();
}

function pilihBatang(element, hari, total, omset, rincian) {
    const bar = element.querySelector('.chart-bar');
    const sedangOren = bar && bar.classList.contains('bg-amber-500');

    document.querySelectorAll('.chart-bar').forEach(function(b) {
        b.classList.remove('bg-amber-500', 'border-amber-600', 'shadow-md');
        b.classList.add('bg-brand-yellow', 'border-yellow-400');
    });

    if (bar && !sedangOren) {
        bar.classList.remove('bg-brand-yellow', 'border-yellow-400');
        bar.classList.add('bg-amber-500', 'border-amber-600', 'shadow-md');
    }

    // Khusus layar mobile, buka sheet interaktif detail hari
    if (window.innerWidth < 1024 && hari) {
        setSheetContent({
            tag: 'Grafik Harian',
            tagClass: 'bg-amber-100 text-amber-800',
            title: `${hari} • ${total} Pesanan`,
            subtitle: `Estimasi Omset: ${omset}`,
            bodyHtml: `
                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 space-y-1.5">
                    <p class="text-xs font-extrabold text-slate-700">Rincian Paket Layanan:</p>
                    <p class="text-xs font-medium text-slate-500">${rincian}</p>
                </div>
            `,
            buttonsHtml: `
                <a href="/pesanan" class="w-full py-3.5 px-4 bg-brand-yellow hover:bg-yellow-400 text-slate-900 font-extrabold rounded-2xl flex items-center justify-center gap-2 shadow-sm active:scale-[0.98] transition text-sm">
                    Lihat Transaksi
                </a>
            `
        });
        tampilSheet();
    }
}

function setSheetContent(cfg) {
    const tag = document.getElementById('sheet-tag');
    tag.textContent = cfg.tag || 'Info';
    tag.className = 'inline-block text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full mb-1 ' + (cfg.tagClass || 'bg-slate-100 text-slate-700');

    document.getElementById('sheet-title').textContent = cfg.title || '';
    document.getElementById('sheet-subtitle').textContent = cfg.subtitle || '';
    document.getElementById('sheet-body').innerHTML = cfg.bodyHtml || '';
    document.getElementById('sheet-buttons').innerHTML = cfg.buttonsHtml || '';
}

function tampilSheet() {
    const backdrop = document.getElementById('mobile-sheet-backdrop');
    const panel = document.getElementById('mobile-sheet-panel');
    if (!backdrop || !panel) return;

    backdrop.classList.remove('hidden');
    // Force reflow
    void backdrop.offsetWidth;
    backdrop.classList.remove('opacity-0');
    panel.classList.remove('translate-y-full', 'sm:translate-y-4');
    panel.classList.add('translate-y-0');
}

function tutupSheet(e) {
    if (e && e.target !== e.currentTarget) return;
    tutupSheetDirect();
}

function tutupSheetDirect() {
    const backdrop = document.getElementById('mobile-sheet-backdrop');
    const panel = document.getElementById('mobile-sheet-panel');
    if (!backdrop || !panel) return;

    backdrop.classList.add('opacity-0');
    panel.classList.remove('translate-y-0');
    panel.classList.add('translate-y-full', 'sm:translate-y-4');

    setTimeout(() => {
        backdrop.classList.add('hidden');
    }, 280);
}
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
