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

        <div class="p-4 md:p-8 max-w-4xl mx-auto w-full">
            <div class="bg-white rounded-3xl border border-slate-200 p-6 md:p-8 shadow-sm space-y-6">
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Pengaturan Profil</h3>
                <div class="flex items-center gap-6 pb-6 border-b border-slate-100">
                    <div class="w-20 h-20 bg-brand-yellow rounded-full flex items-center justify-center text-slate-900 font-black text-2xl shadow-inner">
                        <?= htmlspecialchars($user_initials) ?>
                    </div>
                    <div>
                        <h4 class="text-2xl font-black text-slate-900 tracking-tight"><?= htmlspecialchars(strtoupper($user_name)) ?></h4>
                        <span class="inline-block mt-1 px-3 py-1 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-full text-[10px] font-black uppercase tracking-wider">
                            <?= htmlspecialchars($user_role) ?>
                        </span>
                    </div>
                </div>
                <h1 class="block font-extrabold text-slate-400 uppercase tracking-wider mb-2">Ganti Password</h1>
                <form action="/akun/updatePassword" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                    <div class="space-y-4 max-w-lg">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">Password Lama</label>
                            <input type="password" name="password_lama" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-xs font-medium text-slate-800 outline-none focus:ring-2 focus:ring-brand-yellow" placeholder="Masukkan password lama..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">Password Baru</label>
                            <input type="password" name="password_baru" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-xs font-medium text-slate-800 outline-none focus:ring-2 focus:ring-brand-yellow" placeholder="Masukkan password baru..." minlength="8" required>
                            <p class="text-[10px] text-slate-400 mt-2">Password baru minimal 8 karakter.</p>
                        </div>
                        <button type="submit" class="px-6 py-3.5 bg-brand-darkCard hover:bg-slate-800 text-white text-xs font-extrabold rounded-xl transition shadow-md">Simpan Perubahan</button>
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
                        <div class="mb-4 p-3 rounded-xl bg-slate-100 text-xs font-semibold text-slate-700">
                            <?= htmlspecialchars($pesan[$_GET['status']]) ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>