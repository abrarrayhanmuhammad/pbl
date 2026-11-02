
<?php
$page_title = 'Reset Password - KickDropShoe';
$body_class = 'bg-brand-navy min-h-screen flex items-center justify-center p-4 font-sans antialiased';
require_once __DIR__ . '/../layouts/header.php';
?>
<div class="bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-2.5 bg-brand-yellow"></div>
    <div class="text-center mb-8 pt-2">
        <img src="/assets/logo.png" alt="Logo KickDropShoe" class="w-20 h-20 mx-auto mb-4 object-contain mix-blend-multiply">
        <h1 class="text-2xl font-extrabold text-slate-900">RESET PASSWORD</h1>
        <p class="text-xs text-slate-500 mt-2">Buat password baru untuk akun KickDropShoe.</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message mb-4">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="/reset-password/process" class="space-y-5">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <div>
            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">Password Baru</label>
            <input type="password" name="password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-yellow" placeholder="Minimal 8 karakter" minlength="8" required>
        </div>

        <div>
            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">Konfirmasi Password</label>
            <input type="password" name="confirm_password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-yellow" placeholder="Ulangi password baru" minlength="8" required>
        </div>

        <button type="submit" class="w-full py-4 bg-brand-yellow hover:bg-brand-yellowHover text-slate-900 font-extrabold text-sm rounded-xl transition-all shadow-lg tracking-wide">
            Simpan Password Baru
        </button>
    </form>
</div>
</body>
</html>
