<?php
$page_title = 'Pengaturan Akun - KickDropShoe';
$current_page = 'akun';
require_once __DIR__ . '/../layouts/header.php';
?>
<div class="flex h-screen overflow-hidden bg-brand-slateBg">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <?php
        $header_title = 'Pengaturan Akun';
        require_once __DIR__ . '/../layouts/topbar.php';
        ?>

        <div class="p-4 md:p-8 max-w-4xl mx-auto w-full pb-36 lg:pb-8">
            <div class="bg-white rounded-3xl border border-slate-200 p-6 md:p-8 shadow-sm space-y-6">
                
                <!-- Pengaturan Profil -->
                <div class="space-y-4 pb-6 border-b border-slate-100">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-wider">Pengaturan Profil</h3>
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-brand-yellow rounded-full flex items-center justify-center text-slate-900 font-black text-xl sm:text-2xl shadow-inner shrink-0">
                            <?= htmlspecialchars($user_initials) ?>
                        </div>
                        <div>
                            <h4 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight"><?= htmlspecialchars(strtoupper($user_name)) ?></h4>
                            <span class="inline-block mt-1 px-3 py-1 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-full text-[10px] font-black uppercase tracking-wider">
                                <?= htmlspecialchars($user_role) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <?php if ($user_role === 'superadmin'): ?>
                    <!-- Menu Manajemen Akun (Clean Duolingo Row Style) -->
                    <div class="space-y-3 pb-6 border-b border-slate-100">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-wider">Akses Manajemen</p>
                        <div class="border-2 border-slate-100 rounded-2xl divide-y divide-slate-100 bg-white overflow-hidden shadow-sm">
                            <a href="/karyawan" class="flex items-center justify-between p-4 hover:bg-slate-50/80 active:bg-slate-100 transition-all group">
                                <div class="flex items-center gap-3">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                                    <div>
                                        <span class="text-xs sm:text-sm font-extrabold text-slate-900 group-hover:text-amber-600 transition-colors">Kelola Karyawan</span>
                                        <p class="text-[11px] font-medium text-slate-400">Atur dan tambah akun staf operasional</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-slate-400 shrink-0">
                                    <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full group-hover:bg-amber-100 group-hover:text-amber-800 transition-colors">Buka</span>
                                    <i class="fas fa-chevron-right text-[10px]"></i>
                                </div>
                            </a>
                            <a href="/log" class="flex items-center justify-between p-4 hover:bg-slate-50/80 active:bg-slate-100 transition-all group">
                                <div class="flex items-center gap-3">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500 shrink-0"></span>
                                    <div>
                                        <span class="text-xs sm:text-sm font-extrabold text-slate-900 group-hover:text-amber-600 transition-colors">Log Aktivitas Sistem</span>
                                        <p class="text-[11px] font-medium text-slate-400">Rekam jejak audit operasional</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-slate-400 shrink-0">
                                    <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full group-hover:bg-blue-100 group-hover:text-blue-800 transition-colors">Buka</span>
                                    <i class="fas fa-chevron-right text-[10px]"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Ganti Password -->
                <div class="space-y-4">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-wider">Ganti Password</h3>
                    <form action="/akun/updatePassword" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                        <div class="space-y-4 max-w-lg">
                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 mb-2">Password Lama</label>
                                <input type="password" name="password_lama" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-xs font-medium text-slate-800 outline-none focus:ring-2 focus:ring-brand-yellow focus:bg-white transition" placeholder="Masukkan password lama..." required>
                            </div>
                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 mb-2">Password Baru</label>
                                <input type="password" name="password_baru" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-xs font-medium text-slate-800 outline-none focus:ring-2 focus:ring-brand-yellow focus:bg-white transition" placeholder="Masukkan password baru..." minlength="8" required>
                                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Password baru minimal 8 karakter.</p>
                            </div>
                            <button type="submit" class="px-6 py-3.5 bg-brand-darkCard hover:bg-slate-800 active:scale-95 text-white text-xs font-extrabold rounded-xl transition shadow-md">Simpan Perubahan</button>
                        </div>
                    </form>

                    <?php if (isset($_GET['status'])): ?>
                        <?php
                        $pesan = [
                            'kosong' => 'Password lama dan password baru wajib diisi.',
                            'password_pendek' => 'Password baru minimal 8 karakter.',
                            'password_salah' => 'Password lama yang kamu masukkan salah.',
                            'password_berhasil' => 'Password berhasil diperbarui.'
                        ];
                        ?>
                        <?php if (isset($pesan[$_GET['status']])): ?>
                            <div class="mt-4 p-3.5 rounded-xl bg-slate-100 text-xs font-semibold text-slate-700 border border-slate-200">
                                <?= htmlspecialchars($pesan[$_GET['status']]) ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
