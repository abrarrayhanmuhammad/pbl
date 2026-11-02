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
        <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-4 sm:space-y-6">
            
            <!-- Mobile Quick Actions Grid (Hanya Tampil di Mobile) -->
            <div class="grid grid-cols-2 gap-3 lg:hidden">
                <a href="/laporan" class="p-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl shadow-sm flex items-center gap-3 transition">
                    <div class="w-9 h-9 rounded-xl bg-brand-yellow text-slate-900 flex items-center justify-center text-sm shrink-0 font-extrabold">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black leading-tight">Laporan Keuangan</p>
                        <p class="text-[10px] font-semibold text-slate-400 mt-0.5">Analisis omset</p>
                    </div>
                </a>
                <a href="/pesanan" class="p-3.5 bg-white border border-slate-200 text-slate-800 rounded-2xl shadow-sm flex items-center gap-3 transition">
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
                                <span>50</span>
                                <span>40</span>
                                <span>30</span>
                                <span>20</span>
                                <span>10</span>
                                <span>0</span>
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
                                    
                                    <!-- Sen: 28 (56%) -->
                                    <div class="flex flex-col items-center justify-end h-full group cursor-pointer relative" onclick="pilihBatang(this)">
                                        <span class="text-[10px] font-extrabold text-slate-600 mb-1 transition-colors group-hover:text-amber-600">28</span>
                                        <div class="chart-bar w-4 sm:w-6 lg:w-8 bg-brand-yellow hover:bg-amber-400 border border-yellow-400 rounded-t-md sm:rounded-t-lg transition-all shadow-sm" style="height: 56%;"></div>
                                        <span class="text-[10px] font-bold text-slate-400 absolute -bottom-5">Sen</span>
                                    </div>

                                    <!-- Sel: 40 (80% TEPAT SEJAJAR GARIS 40) -->
                                    <div class="flex flex-col items-center justify-end h-full group cursor-pointer relative" onclick="pilihBatang(this)">
                                        <span class="text-[10px] font-extrabold text-slate-600 mb-1 transition-colors group-hover:text-amber-600">40</span>
                                        <div class="chart-bar w-4 sm:w-6 lg:w-8 bg-brand-yellow hover:bg-amber-400 border border-yellow-400 rounded-t-md sm:rounded-t-lg transition-all shadow-sm" style="height: 80%;"></div>
                                        <span class="text-[10px] font-bold text-slate-400 absolute -bottom-5">Sel</span>
                                    </div>

                                    <!-- Rab: 48 (96%) -->
                                    <div class="flex flex-col items-center justify-end h-full group cursor-pointer relative" onclick="pilihBatang(this)">
                                        <span class="text-[10px] font-extrabold text-slate-600 mb-1 transition-colors group-hover:text-amber-600">48</span>
                                        <div class="chart-bar w-4 sm:w-6 lg:w-8 bg-brand-yellow hover:bg-amber-400 border border-yellow-400 rounded-t-md sm:rounded-t-lg transition-all shadow-sm" style="height: 96%;"></div>
                                        <span class="text-[10px] font-bold text-slate-400 absolute -bottom-5">Rab</span>
                                    </div>

                                    <!-- Kam: 36 (72%) -->
                                    <div class="flex flex-col items-center justify-end h-full group cursor-pointer relative" onclick="pilihBatang(this)">
                                        <span class="text-[10px] font-extrabold text-slate-600 mb-1 transition-colors group-hover:text-amber-600">36</span>
                                        <div class="chart-bar w-4 sm:w-6 lg:w-8 bg-brand-yellow hover:bg-amber-400 border border-yellow-400 rounded-t-md sm:rounded-t-lg transition-all shadow-sm" style="height: 72%;"></div>
                                        <span class="text-[10px] font-bold text-slate-400 absolute -bottom-5">Kam</span>
                                    </div>

                                    <!-- Jum: 20 (40% TEPAT SEJAJAR GARIS 20) -->
                                    <div class="flex flex-col items-center justify-end h-full group cursor-pointer relative" onclick="pilihBatang(this)">
                                        <span class="text-[10px] font-extrabold text-slate-600 mb-1 transition-colors group-hover:text-amber-600">20</span>
                                        <div class="chart-bar w-4 sm:w-6 lg:w-8 bg-brand-yellow hover:bg-amber-400 border border-yellow-400 rounded-t-md sm:rounded-t-lg transition-all shadow-sm" style="height: 40%;"></div>
                                        <span class="text-[10px] font-bold text-slate-400 absolute -bottom-5">Jum</span>
                                    </div>

                                    <!-- Sab: 44 (88%) -->
                                    <div class="flex flex-col items-center justify-end h-full group cursor-pointer relative" onclick="pilihBatang(this)">
                                        <span class="text-[10px] font-extrabold text-slate-600 mb-1 transition-colors group-hover:text-amber-600">44</span>
                                        <div class="chart-bar w-4 sm:w-6 lg:w-8 bg-brand-yellow hover:bg-amber-400 border border-yellow-400 rounded-t-md sm:rounded-t-lg transition-all shadow-sm" style="height: 88%;"></div>
                                        <span class="text-[10px] font-bold text-slate-400 absolute -bottom-5">Sab</span>
                                    </div>

                                    <!-- Min: 24 (48%) -->
                                    <div class="flex flex-col items-center justify-end h-full group cursor-pointer relative" onclick="pilihBatang(this)">
                                        <span class="text-[10px] font-extrabold text-slate-600 mb-1 transition-colors group-hover:text-amber-600">24</span>
                                        <div class="chart-bar w-4 sm:w-6 lg:w-8 bg-brand-yellow hover:bg-amber-400 border border-yellow-400 rounded-t-md sm:rounded-t-lg transition-all shadow-sm" style="height: 48%;"></div>
                                        <span class="text-[10px] font-bold text-slate-400 absolute -bottom-5">Min</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Metrik Sejajar (3 Kolom) -->
                    <div class="mt-6 pt-4 border-t border-slate-100 grid grid-cols-3 gap-1 sm:gap-2 text-center">
                        <div class="border-r border-slate-100 px-1">
                            <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Masuk</span>
                            <span class="text-xs sm:text-sm font-black text-slate-900 mt-0.5 block">240 Pesanan</span>
                        </div>
                        <div class="border-r border-slate-100 px-1">
                            <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Rata-Rata</span>
                            <span class="text-xs sm:text-sm font-black text-slate-900 mt-0.5 block">34 / Hari</span>
                        </div>
                        <div class="px-1">
                            <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tersibuk</span>
                            <span class="text-xs sm:text-sm font-black text-slate-900 mt-0.5 block">Rabu (48)</span>
                        </div>
                    </div>
                </div>

                                <!-- Pesanan Perlu Perhatian (100% Desain Awal Asli) -->
                <div class="col-span-1 lg:col-span-2 bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm">
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
        </div>
    </main>
</div>

<script>
function pilihBatang(element) {
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
}
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>