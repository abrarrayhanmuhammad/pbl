<?php
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }
// $_SESSION['user_role'] = 'KARYAWAN';
// $_SESSION['user_name'] = 'Bagas (Karyawan)';
// $_SESSION['user_initials'] = 'BK';

// $user_role = 'KARYAWAN';
// $user_initials = 'BK';
$user_name = 'Bagas (Karyawan)';
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
                <h3 class="text-2xl sm:text-3xl lg:text-5xl font-black text-emerald-500">12</h3>
            </div>

            <!-- Card Pesanan Perlu Perhatian (Full Width, Desain Serasi dengan Owner) -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm">
                <h3 class="text-sm font-extrabold text-slate-800 tracking-tight mb-4 sm:mb-6">Pesanan Perlu Perhatian</h3>
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex justify-between items-center p-4 sm:p-5 bg-slate-50/80 rounded-2xl border border-slate-100 hover:border-slate-200 transition">
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm">#0881 – Budi</h4>
                            <p class="text-xs font-semibold text-slate-400 mt-0.5">Express (Sisa 1 Hari)</p>
                        </div>
                        <a href="/pesanan" class="text-xs font-extrabold text-yellow-700 hover:underline">Cek</a>
                    </div>
                    <div class="flex justify-between items-center p-4 sm:p-5 bg-slate-50/80 rounded-2xl border border-slate-100 hover:border-slate-200 transition">
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm">#0872 – Andi</h4>
                            <p class="text-xs font-semibold text-slate-400 mt-0.5">Prioritas (Sisa 2 Hari)</p>
                        </div>
                        <a href="/pesanan" class="text-xs font-extrabold text-yellow-700 hover:underline">Cek</a>
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
// Data Popup Pesanan
const orderPopupData = {
    '0881': {
        tag: '⚡ Express • Sisa 1 Hari',
        tagClass: 'bg-amber-100 text-amber-800',
        title: '#0881 – Budi Santoso',
        subtitle: '0812-3456-7890 • Drop Point Oro-oro Dowo',
        bodyHtml: `
            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
                <div class="flex justify-between items-center text-xs font-bold text-slate-700">
                    <span>Sepatu Nike Air Max (Putih)</span>
                    <span class="text-slate-900 font-extrabold">Rp 70.000</span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium">Basic & White Shoes + Unyellowing + Express</p>
            </div>
            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
                <div class="flex justify-between items-center text-xs font-bold text-slate-700">
                    <span>Tas Coach (Hitam)</span>
                    <span class="text-slate-900 font-extrabold">Rp 35.000</span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium">Deep Clean Tas M + Reguler</p>
            </div>
            <div class="flex justify-between items-center px-1 pt-1 text-xs">
                <span class="font-extrabold text-slate-500">Total Biaya:</span>
                <span class="font-black text-slate-900 text-base">Rp 105.000 <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full ml-1">LUNAS</span></span>
            </div>
        `,
        btnDetail: '/pesanan',
        waNumber: '628123456789',
        waMessage: 'Halo Kak Budi Santoso, update status pesanan sepatu Anda di KickDropShoe #0881 siap kami percepat pengerjaannya.'
    },
    '0872': {
        tag: '🔥 Prioritas • Sisa 2 Hari',
        tagClass: 'bg-blue-100 text-blue-800',
        title: '#0872 – Andi Wijaya',
        subtitle: '0856-1234-5678 • Drop Point Dieng',
        bodyHtml: `
            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
                <div class="flex justify-between items-center text-xs font-bold text-slate-700">
                    <span>Sepatu Converse Chuck Taylor (Hitam)</span>
                    <span class="text-slate-900 font-extrabold">Rp 55.000</span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium">Premium Deep Clean + Prioritas</p>
            </div>
            <div class="flex justify-between items-center px-1 pt-1 text-xs">
                <span class="font-extrabold text-slate-500">Total Biaya:</span>
                <span class="font-black text-slate-900 text-base">Rp 55.000 <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full ml-1">LUNAS</span></span>
            </div>
        `,
        btnDetail: '/pesanan',
        waNumber: '628561234567',
        waMessage: 'Halo Kak Andi, update pesanan KickDropShoe #0872 saat ini sedang tahap treatment pengeringan.'
    }
};

function bukaSheetPesanan(orderId) {
    const data = orderPopupData[orderId];
    if (!data) return;

    setSheetContent({
        tag: data.tag,
        tagClass: data.tagClass,
        title: data.title,
        subtitle: data.subtitle,
        bodyHtml: data.bodyHtml,
        buttonsHtml: `
            <a href="${data.btnDetail}" class="w-full py-3.5 px-4 bg-brand-yellow hover:bg-yellow-400 text-slate-900 font-extrabold rounded-2xl flex items-center justify-center gap-2 shadow-sm active:scale-[0.98] transition text-sm">
                <i class="fas fa-arrow-right"></i> Buka di Daftar Pesanan
            </a>
            <a href="https://wa.me/${data.waNumber}?text=${encodeURIComponent(data.waMessage)}" target="_blank" class="w-full py-3 px-4 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-extrabold rounded-2xl flex items-center justify-center gap-2 border border-emerald-200 active:scale-[0.98] transition text-xs">
                <i class="fab fa-whatsapp text-sm text-emerald-600"></i> Hubungi via WhatsApp
            </a>
        `
    });
    tampilSheet();
}

function bukaSheetMetrik(tipe) {
    if (window.innerWidth >= 1024) return;

    if (tipe === 'aktif') {
        setSheetContent({
            tag: 'Antrean Operasional Karyawan',
            tagClass: 'bg-emerald-100 text-emerald-800',
            title: '12 Pesanan Berjalan',
            subtitle: 'Antrean pengerjaan di workshop hari ini',
            bodyHtml: `
                <div class="space-y-2.5">
                    <div class="flex justify-between items-center text-xs font-bold p-3 bg-slate-50 rounded-xl">
                        <span class="flex items-center gap-2"><i class="fas fa-soap text-blue-500 w-4"></i> Cuci & Deep Clean</span>
                        <span class="font-extrabold text-slate-900">7 Pasang</span>
                    </div>
                    <div class="flex justify-between items-center text-xs font-bold p-3 bg-slate-50 rounded-xl">
                        <span class="flex items-center gap-2"><i class="fas fa-wind text-amber-500 w-4"></i> Pengeringan & Treatment</span>
                        <span class="font-extrabold text-slate-900">3 Pasang</span>
                    </div>
                    <div class="flex justify-between items-center text-xs font-bold p-3 bg-slate-50 rounded-xl">
                        <span class="flex items-center gap-2"><i class="fas fa-check-circle text-emerald-500 w-4"></i> Quality Control / Siap Diambil</span>
                        <span class="font-extrabold text-slate-900">2 Pasang</span>
                    </div>
                </div>
            `,
            buttonsHtml: `
                <a href="/pesanan" class="w-full py-3.5 px-4 bg-brand-yellow hover:bg-yellow-400 text-slate-900 font-extrabold rounded-2xl flex items-center justify-center gap-2 shadow-sm active:scale-[0.98] transition text-sm">
                    Cek Daftar Antrean Pesanan
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
