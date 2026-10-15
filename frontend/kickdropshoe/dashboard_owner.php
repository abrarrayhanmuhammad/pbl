<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['user_role'] = 'OWNER';
$_SESSION['user_name'] = 'Super Admin';
$_SESSION['user_initials'] = 'SA';

$user_role = 'OWNER';
$user_name = 'Super Admin';
$user_initials = 'SA';
$page_title = 'Dashboard Owner - KickDropShoe';
$current_page = 'dashboard';
require_once 'includes/header.php';
?>
<div class="flex h-screen overflow-hidden bg-brand-slateBg">
    <?php require_once 'includes/sidebar.php'; ?>
    
    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <?php 
        $header_title = 'Dashboard Owner';
        require 'includes/topbar.php'; 
        ?>

        <!-- Container utama dengan responsif mobile padding dan desktop p-8 -->
        <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-4 sm:space-y-6">
            
            <!-- Mobile Quick Actions Grid (Hanya Tampil di Mobile) -->
            <div class="grid grid-cols-2 gap-3 lg:hidden">
                <a href="laporan.php" class="p-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl shadow-sm flex items-center gap-3 transition">
                    <div class="w-9 h-9 rounded-xl bg-brand-yellow text-slate-900 flex items-center justify-center text-sm shrink-0 font-extrabold">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black leading-tight">Laporan Keuangan</p>
                        <p class="text-[10px] font-semibold text-slate-400 mt-0.5">Analisis omset</p>
                    </div>
                </a>
                <a href="pesanan.php" class="p-3.5 bg-white border border-slate-200 text-slate-800 rounded-2xl shadow-sm flex items-center gap-3 transition">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-sm text-slate-600 shrink-0">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black leading-tight">Daftar Pesanan</p>
                        <p class="text-[10px] font-semibold text-slate-400 mt-0.5">Semua transaksi</p>
                    </div>
                </a>
            </div>

            <!-- Top Row: 3 Metric Cards (Mobile 2 Kolom, Desktop 3 Kolom) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                <div class="col-span-1 bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm">
                    <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">TOTAL PESANAN</p>
                    <h3 class="text-2xl sm:text-3xl lg:text-5xl font-black text-slate-900">148</h3>
                </div>
                <div class="col-span-1 bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm">
                    <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">AKTIF</p>
                    <h3 class="text-2xl sm:text-3xl lg:text-5xl font-black text-emerald-500">32</h3>
                </div>
                <div class="col-span-2 sm:col-span-1 bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm">
                    <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">PENDAPATAN</p>
                    <h3 class="text-2xl sm:text-3xl lg:text-5xl font-black text-slate-900">Rp 4.5M</h3>
                </div>
            </div>

            <!-- Bottom Row: Grafik & Pesanan Perlu Perhatian (Mobile 1 Kolom, Desktop 5 Kolom) -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 lg:gap-6">
                <!-- Diagram Batang -->
                <div class="col-span-1 lg:col-span-3 bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm flex flex-col justify-between">
                    <div class="flex gap-4 items-end h-56">
                        <!-- Angka Sumbu Y di Samping Grafik -->
                        <div class="flex flex-col justify-between h-full pb-4 text-[10px] font-bold text-slate-400 shrink-0 select-none">
                            <span>50</span>
                            <span>40</span>
                            <span>30</span>
                            <span>20</span>
                            <span>10</span>
                            <span>0</span>
                        </div>

                        <!-- Batang Grafik dengan Stroke Tipis dan Angka di Atasnya -->
                        <div class="flex-1 h-full flex items-end justify-between px-3 sm:px-4 pb-4 border-b border-l border-slate-200 relative">
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-[10px] font-bold text-slate-600">28</span>
                                <div class="w-6 sm:w-8 bg-brand-yellow border border-yellow-400 rounded-t-lg h-28"></div>
                                <span class="text-[10px] font-bold text-slate-500 absolute -bottom-6">Mon</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-[10px] font-bold text-slate-600">40</span>
                                <div class="w-6 sm:w-8 bg-brand-yellow border border-yellow-400 rounded-t-lg h-40"></div>
                                <span class="text-[10px] font-bold text-slate-500 absolute -bottom-6">Tue</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-[10px] font-bold text-slate-600">48</span>
                                <div class="w-6 sm:w-8 bg-brand-yellow border border-yellow-400 rounded-t-lg h-48"></div>
                                <span class="text-[10px] font-bold text-slate-500 absolute -bottom-6">Wed</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-[10px] font-bold text-slate-600">36</span>
                                <div class="w-6 sm:w-8 bg-brand-yellow border border-yellow-400 rounded-t-lg h-36"></div>
                                <span class="text-[10px] font-bold text-slate-500 absolute -bottom-6">Thu</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-[10px] font-bold text-slate-600">20</span>
                                <div class="w-6 sm:w-8 bg-brand-yellow border border-yellow-400 rounded-t-lg h-20"></div>
                                <span class="text-[10px] font-bold text-slate-500 absolute -bottom-6">Fri</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-[10px] font-bold text-slate-600">44</span>
                                <div class="w-6 sm:w-8 bg-brand-yellow border border-yellow-400 rounded-t-lg h-44"></div>
                                <span class="text-[10px] font-bold text-slate-500 absolute -bottom-6">Sat</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-[10px] font-bold text-slate-600">24</span>
                                <div class="w-6 sm:w-8 bg-brand-yellow border border-yellow-400 rounded-t-lg h-24"></div>
                                <span class="text-[10px] font-bold text-slate-500 absolute -bottom-6">Sun</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pesanan Perlu Perhatian -->
                <div class="col-span-1 lg:col-span-2 bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm">
                    <h3 class="text-sm font-extrabold text-slate-800 tracking-tight mb-4 sm:mb-6">Pesanan Perlu Perhatian</h3>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex justify-between items-center p-4 sm:p-5 bg-slate-50/80 rounded-2xl border border-slate-100 hover:border-slate-200 transition">
                            <div>
                                <h4 class="font-extrabold text-slate-900 text-sm">#0881 – Budi</h4>
                                <p class="text-xs font-semibold text-slate-400 mt-0.5">Express (Sisa 1 Hari)</p>
                            </div>
                            <a href="pesanan.php" class="text-xs font-extrabold text-yellow-700 hover:underline">Cek</a>
                        </div>
                        <div class="flex justify-between items-center p-4 sm:p-5 bg-slate-50/80 rounded-2xl border border-slate-100 hover:border-slate-200 transition">
                            <div>
                                <h4 class="font-extrabold text-slate-900 text-sm">#0872 – Andi</h4>
                                <p class="text-xs font-semibold text-slate-400 mt-0.5">Prioritas (Sisa 2 Hari)</p>
                            </div>
                            <a href="pesanan.php" class="text-xs font-extrabold text-yellow-700 hover:underline">Cek</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require_once 'includes/footer.php'; ?>
