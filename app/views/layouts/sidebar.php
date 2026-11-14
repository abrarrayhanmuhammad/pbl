<?php
/**
 * Sidebar Component - KickDropShoe MIS (Mobile Responsive Off-Canvas & Desktop Fixed)
 */
$current_page = $current_page ?? '';
$user_role = $user_role ?? ($_SESSION['role'] ?? 'superadmin'); // 'OWNER' or 'KARYAWAN'
$user_name = $user_name ?? ($_SESSION['nama'] ?? ($user_role === 'karyawan' ? 'Bagas (Karyawan)' : 'Super Admin'));
$user_initials = $user_initials ?? ($_SESSION['user_initials'] ?? ($user_role === 'karyawan' ? 'BK' : 'SA'));
$is_print_hidden = !empty($is_invoice) ? 'print:hidden' : '';

// $dashboard_link = ($user_role === 'karyawan') ? 'dashboard_karyawan.php' : 'dashboard_owner.php';
?>
<!-- Mobile Backdrop Overlay -->
<div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 hidden lg:hidden transition-opacity"></div>

<!-- Sidebar Navigation -->
<aside id="main-sidebar" class="fixed lg:static top-0 left-0 w-[280px] bg-brand-navy text-slate-300 flex flex-col justify-between shadow-2xl lg:shadow-xl shrink-0 z-40 h-screen transition-transform duration-300 -translate-x-full lg:translate-x-0 <?= $is_print_hidden ?>">
    <div class="overflow-y-auto">
        <!-- Brand Header with Close Button on Mobile -->
        <div class="p-6 border-b border-slate-800/60 flex items-center justify-between">
            <div class="flex items-center gap-3">
            <img src="<?= BASE_URL ?>/assets/logo.png" alt="Logo" class="w-10 h-10 rounded-full shadow-md border-2 border-slate-900 object-cover bg-white">
                <div>
                    <h1 class="text-base font-extrabold tracking-tight text-white leading-none">KICKDROPSHOE</h1>
                    <p class="text-[10px] text-brand-yellow font-bold uppercase tracking-widest mt-1"><?= htmlspecialchars($user_role) ?></p>
                </div>
            </div>
            <button type="button" onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="p-4 space-y-2">
            <!-- Dashboard Link -->
            <?php if ($current_page === 'dashboard'): ?>
                <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-800 text-white font-extrabold transition-all">
                    <i class="fas fa-pie-chart w-5"></i> Dashboard
                </a>
            <?php else: ?>
                <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white font-bold transition-all">
                    <i class="fas fa-pie-chart w-5"></i> Dashboard
                </a>
            <?php endif; ?>

            <!-- Laporan (Hanya OWNER) -->
            <?php if ($user_role === 'superadmin'): ?>
                <?php if ($current_page === 'laporan'): ?>
                    <a href="/laporan" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-800 text-white font-extrabold transition-all">
                        <i class="fas fa-chart-line w-5"></i> Laporan Pendapatan
                    </a>
                <?php else: ?>
                    <a href="/laporan" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white font-bold transition-all">
                        <i class="fas fa-chart-line w-5"></i> Laporan Pendapatan
                    </a>
                <?php endif; ?>
            <?php endif; ?>

             <!-- Log Aktivitas (Khusus Superadmin / Owner) -->
            <?php if ($user_role === 'superadmin'): ?>
                <?php if ($current_page === 'log'): ?>
                    <a href="/log" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-800 text-white font-extrabold transition-all">
                        <i class="fas fa-history w-5"></i> Log Aktivitas
                    </a>
                <?php else: ?>
                    <a href="/log" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white font-bold transition-all">
                        <i class="fas fa-history w-5"></i> Log Aktivitas
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Tambah / Kelola Karyawan (Khusus Superadmin / Owner) -->
            <?php if ($user_role === 'superadmin'): ?>
                <?php if ($current_page === 'karyawan'): ?>
                    <a href="/karyawan" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-800 text-white font-extrabold transition-all">
                        <i class="fas fa-user-plus w-5"></i> Tambah Karyawan
                    </a>
                <?php else: ?>
                    <a href="/tambah/karyawan" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white font-bold transition-all">
                        <i class="fas fa-user-plus w-5"></i> Tambah Karyawan
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Daftar Pesanan -->
            <?php if ($current_page === 'pesanan'): ?>
                <a href="/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-800 text-white font-extrabold transition-all">
                    <i class="fas fa-list-ul w-5"></i> Daftar Pesanan
                </a>
            <?php else: ?>
                <a href="/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white font-bold transition-all">
                    <i class="fas fa-list-ul w-5"></i> Daftar Pesanan
                </a>
            <?php endif; ?>

            <!-- Tambah Pesanan  -->
             <?php if ($current_page === 'tambah_pesanan'): ?>
                <a href="/pesanan/tambah" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-800 text-white font-extrabold transition-all">
                <i class="fas fa-plus w-5"></i> Tambah Pesanan
            </a>
            <?php else: ?>
                <a href="/pesanan/tambah" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white font-bold transition-all">
                    <i class="fas fa-plus w-5"></i> Tambah Pesanan
                </a>
            <?php endif; ?>

            <!-- Profil Akun -->
            <?php if ($current_page === 'akun'): ?>
                <a href="/akun" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-800 text-white font-extrabold transition-all">
                    <i class="fas fa-user w-5"></i> Profil Akun
                </a>
            <?php else: ?>
                <a href="/akun" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white font-bold transition-all">
                    <i class="fas fa-user w-5"></i> Profil Akun
                </a>
            <?php endif; ?>
        </nav>
    </div>

    <!-- User Profile Pill at Bottom -->
    <div class="p-4 border-t border-slate-800/60 m-4 mb-28 lg:mb-4 bg-slate-800/60 rounded-2xl shrink-0 shadow-lg">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 bg-brand-yellow text-slate-900 font-extrabold rounded-full flex items-center justify-center text-xs shadow-sm">
                <?= htmlspecialchars($user_initials) ?>
            </div>
           <div>
                <p class="text-xs font-extrabold text-white leading-tight"><?= htmlspecialchars($user_name) ?></p>
                <p class="text-[10px] text-emerald-400 font-semibold flex items-center gap-1.5 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Online
                </p>
            </div>
        </div>
        <a href="/logout" class="flex items-center justify-center gap-2 w-full py-2.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 rounded-xl text-xs font-extrabold transition-all border border-red-500/30">
            <i class="fas fa-sign-out-alt"></i> Keluar
        </a>
    </div>
</aside>

<?php if ($current_page !== 'tambah_pesanan' && $current_page !== 'edit_pesanan'): ?>
<!-- Mobile Bottom Navigation Bar (Modern Gojek / Duolingo Hybrid Style) -->
<nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-200/90 z-30 px-3 pt-2 pb-3.5 flex items-center justify-between shadow-[0_-4px_25px_rgba(0,0,0,0.05)] <?= $is_print_hidden ?>">
    
    <!-- 1. Beranda -->
    <a href="/dashboard" class="flex flex-col items-center justify-center flex-1 py-1 relative active:scale-90 transition-all <?= $current_page === 'dashboard' ? 'text-slate-900 font-black' : 'text-slate-400 hover:text-slate-600 font-semibold' ?>">
        <i class="fas fa-house text-base mb-0.5 <?= $current_page === 'dashboard' ? 'text-slate-900' : 'text-slate-400' ?>"></i>
        <span class="text-[10px] tracking-tight">Beranda</span>
        <?php if ($current_page === 'dashboard'): ?>
            <span class="w-1.5 h-1.5 rounded-full bg-brand-yellow mt-0.5 shadow-sm"></span>
        <?php endif; ?>
    </a>

    <!-- 2. Pesanan -->
    <a href="/pesanan" class="flex flex-col items-center justify-center flex-1 py-1 relative active:scale-90 transition-all <?= ($current_page === 'pesanan') ? 'text-slate-900 font-black' : 'text-slate-400 hover:text-slate-600 font-semibold' ?>">
        <i class="fas fa-receipt text-base mb-0.5 <?= $current_page === 'pesanan' ? 'text-slate-900' : 'text-slate-400' ?>"></i>
        <span class="text-[10px] tracking-tight">Pesanan</span>
        <?php if ($current_page === 'pesanan'): ?>
            <span class="w-1.5 h-1.5 rounded-full bg-brand-yellow mt-0.5 shadow-sm"></span>
        <?php endif; ?>
    </a>

    <!-- 3. TAMBAH PESANAN (Center Action - Modern Duolingo Squircle with Bold Chunky Plus) -->
    <a href="/pesanan/tambah" class="flex flex-col items-center justify-center flex-1 py-1 relative active:scale-90 transition-all <?= $current_page === 'tambah_pesanan' ? 'text-slate-900 font-black' : 'text-slate-700 font-bold' ?>">
        <div class="w-10 h-10 rounded-2xl bg-brand-yellow text-slate-900 flex items-center justify-center border-b-2 border-amber-500 shadow-sm active:border-b-0 active:translate-y-0.5 transition-all">
            <svg class="w-5 h-5 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
        </div>
        <span class="text-[10px] tracking-tight mt-0.5 font-black text-slate-900">Tambah</span>
    </a>

    <?php if ($user_role === 'superadmin'): ?>
        <!-- 4. Laporan (Super Admin) -->
        <a href="/laporan" class="flex flex-col items-center justify-center flex-1 py-1 relative active:scale-90 transition-all <?= $current_page === 'laporan' ? 'text-slate-900 font-black' : 'text-slate-400 hover:text-slate-600 font-semibold' ?>">
            <i class="fas fa-chart-line text-base mb-0.5 <?= $current_page === 'laporan' ? 'text-slate-900' : 'text-slate-400' ?>"></i>
            <span class="text-[10px] tracking-tight">Laporan</span>
            <?php if ($current_page === 'laporan'): ?>
                <span class="w-1.5 h-1.5 rounded-full bg-brand-yellow mt-0.5 shadow-sm"></span>
            <?php endif; ?>
        </a>
    <?php endif; ?>

    <!-- 5. Akun -->
    <a href="/akun" class="flex flex-col items-center justify-center flex-1 py-1 relative active:scale-90 transition-all <?= $current_page === 'akun' ? 'text-slate-900 font-black' : 'text-slate-400 hover:text-slate-600 font-semibold' ?>">
        <i class="fas fa-user text-base mb-0.5 <?= $current_page === 'akun' ? 'text-slate-900' : 'text-slate-400' ?>"></i>
        <span class="text-[10px] tracking-tight">Akun</span>
        <?php if ($current_page === 'akun'): ?>
            <span class="w-1.5 h-1.5 rounded-full bg-brand-yellow mt-0.5 shadow-sm"></span>
        <?php endif; ?>
    </a>
</nav>
<?php endif; ?>
