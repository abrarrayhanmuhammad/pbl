<?php
$page_title = 'Dashboard Operasional - KickDropShoe';
$current_page = 'dashboard';
require_once __DIR__ . '/../layouts/header.php';
?>
<div class="flex h-screen overflow-hidden bg-brand-slateBg">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    
    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <?php 
        $header_title = 'Dashboard Operasional';
        require_once __DIR__ . '/../layouts/topbar.php'; 
        ?>

        <!-- Container utama dengan responsif mobile padding dan desktop p-8 -->
        <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-4 sm:space-y-6 pb-36 lg:pb-8">
            
            <!-- Mobile Quick Actions (Clean Tokopedia / Blibli Style) -->
            <div class="grid grid-cols-2 gap-3 lg:hidden">
                <a href="/pesanan/tambah" class="bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-3.5 shadow-sm active:scale-[0.98] transition-all flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-800 flex items-center justify-center text-sm shrink-0 font-bold">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-black text-slate-900 truncate">Tambah</p>
                        <p class="text-[10px] font-semibold text-slate-400 truncate">Input order baru</p>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-300 shrink-0"></i>
                </a>
                <a href="/pesanan" class="bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-3.5 shadow-sm active:scale-[0.98] transition-all flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-800 flex items-center justify-center text-sm shrink-0 font-bold">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-black text-slate-900 truncate">Pesanan</p>
                        <p class="text-[10px] font-semibold text-slate-400 truncate">Semua transaksi</p>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-300 shrink-0"></i>
                </a>
            </div>

            <!-- Card Pesanan Aktif (Interaktif di HP ala Tokopedia / Duolingo) -->
            <div onclick="bukaSheetMetrik('aktif')" class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm cursor-pointer lg:cursor-default active:scale-[0.98] transition-all hover:border-slate-300 group">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider group-hover:text-slate-600 transition-colors">PESANAN AKTIF</p>
                    <i class="fas fa-spinner text-xs text-emerald-400 lg:hidden group-hover:text-emerald-600"></i>
                </div>
                <h3 class="text-2xl sm:text-3xl lg:text-5xl font-black text-emerald-500">
                    <?= htmlspecialchars($pesananAktifCount) ?>
                </h3>
            </div>

            <!-- Card Pesanan Perlu Perhatian (Full Width, Desain Serasi dengan Owner) -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm">
                <h3 class="text-sm font-extrabold text-slate-800 tracking-tight mb-4 sm:mb-6">Pesanan Perlu Perhatian</h3>
                <div class="space-y-3 sm:space-y-4">
                    <div class="space-y-3 sm:space-y-4">
                        <?php if (empty($perluPerhatian)): ?>
                            <p class="text-sm font-semibold text-slate-400 p-4 bg-slate-50 rounded-2xl text-center">Aman, tidak ada pesanan mendesak.</p>
                        <?php else: ?>
                            <?php foreach ($perluPerhatian as $p): ?>
                                <div class="flex justify-between items-center p-4 sm:p-5 bg-slate-50/80 rounded-2xl border border-slate-100 hover:border-slate-200 transition">
                                    <div>
                                        <h4 class="font-extrabold text-slate-900 text-sm">
                                            <?= htmlspecialchars($p['kodePesanan']) ?> – <?= htmlspecialchars($p['namaCustomer']) ?>
                                        </h4>
                                        <p class="text-xs font-semibold text-slate-400 mt-0.5">
                                            <?= htmlspecialchars($p['namaTreatment']) ?> (Sisa <?= htmlspecialchars($p['sisaHari']) ?> Hari)
                                        </p>
                                    </div>
                                    <a href="/pesanan" class="text-xs font-extrabold text-yellow-700 hover:underline">Cek</a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
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

// Fungsi ini bisa dihapus atau dibiarkan kosong sementara jika belum dipakai
function bukaSheetPesanan(orderId) {
    // Nanti kita isi kalau fitur klik list pesanan sudah siap
    console.log("Klik pesanan: " + orderId);
}

function bukaSheetMetrik(tipe) {
    if (window.innerWidth >= 1024) return;

    if (tipe === 'aktif') {
        // Ambil data dari PHP dan jadikan objek JS
        const totalAktif = <?= json_encode($pesananAktifCount ?? 0) ?>; 
        
        setSheetContent({
            tag: 'Antrean Operasional Karyawan',
            tagClass: 'bg-emerald-100 text-emerald-800',
            title: totalAktif + ' Pesanan Berjalan',
            subtitle: 'Antrean pengerjaan di workshop hari ini',
            bodyHtml: `
                <div class="space-y-2.5">
                    <!-- Sementara kita kosongkan detail per statusnya, atau tampilkan total -->
                    <p class="text-sm text-center font-semibold text-slate-500">Total ada ${totalAktif} pesanan yang sedang dikerjakan.</p>
                </div>
            `,
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
