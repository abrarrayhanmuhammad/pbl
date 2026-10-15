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
        <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-4 sm:space-y-6">
            
            <!-- Mobile Greeting & Shift Card (Hanya Tampil di Mobile) -->
            <div class="lg:hidden bg-gradient-to-r from-slate-900 to-brand-navy rounded-2xl p-4 text-white flex items-center justify-between shadow-md">
                <div>
                    <p class="text-[10px] font-extrabold text-brand-yellow uppercase tracking-widest">Shift Operasional</p>
                    <h3 class="text-base font-extrabold text-white mt-0.5">Bagas (Karyawan)</h3>
                  
                </div>
                <div class="w-10 h-10 rounded-full bg-brand-yellow text-slate-900 font-extrabold flex items-center justify-center text-xs shadow-sm shrink-0">
                    BK
                </div>
            </div>

            <!-- Mobile Quick Actions Grid (Hanya Tampil di Mobile) -->
            <div class="grid grid-cols-2 gap-3 lg:hidden">
                <a href="tambah_pesanan.php" class="p-3.5 bg-brand-yellow hover:bg-brand-yellowHover text-slate-900 rounded-2xl shadow-sm flex items-center gap-3 transition">
                    <div class="w-9 h-9 rounded-xl bg-slate-900/10 flex items-center justify-center text-sm shrink-0">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black leading-tight">Tambah Pesanan</p>
                        <p class="text-[10px] font-semibold text-slate-700 mt-0.5">Input order baru</p>
                    </div>
                </a>
                <a href="pesanan.php" class="p-3.5 bg-white border border-slate-200 text-slate-800 rounded-2xl shadow-sm flex items-center gap-3 transition">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-sm text-slate-600 shrink-0">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black leading-tight">Daftar Pesanan</p>
                        <p class="text-[10px] font-semibold text-slate-400 mt-0.5">Status & pengerjaan</p>
                    </div>
                </a>
            </div>

            <!-- Card Pesanan Aktif (Full Width) -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-1 sm:mb-2">PESANAN AKTIF</p>
                        <h3 class="text-4xl sm:text-5xl font-black text-emerald-500">12</h3>
                    </div>
                    <div class="lg:hidden">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-xs font-extrabold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Diproses
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card Pesanan Perlu Perhatian (Full Width) -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm">
                <h3 class="text-sm font-extrabold text-slate-800 tracking-tight mb-4 sm:mb-6">Pesanan Perlu Perhatian</h3>
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex justify-between items-center p-4 sm:p-5 bg-slate-50/80 rounded-2xl border border-slate-100 hover:border-slate-200 transition">
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm">#0881 – Budi</h4>
                            <p class="text-xs font-semibold text-slate-400 mt-0.5">Express (Sisa 1 Hari)</p>
                        </div>
                        <a href="pesanan.php" class="text-sm font-extrabold text-yellow-500 hover:underline">Cek</a>
                    </div>
                    <div class="flex justify-between items-center p-4 sm:p-5 bg-slate-50/80 rounded-2xl border border-slate-100 hover:border-slate-200 transition">
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm">#0872 – Andi</h4>
                            <p class="text-xs font-semibold text-slate-400 mt-0.5">Prioritas (Sisa 2 Hari)</p>
                        </div>
                        <a href="pesanan.php" class="text-sm font-extrabold text-yellow-500 hover:underline">Cek</a>
                    </div>
                </div>
            </div>
            
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>