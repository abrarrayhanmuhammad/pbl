<?php
$page_title = 'Login - KickDropShoe';
$body_class = 'bg-brand-navy min-h-screen flex items-center justify-center p-4 font-sans antialiased';
require_once __DIR__ . '/../layouts/header.php';
?>
    <div class="bg-white rounded-3xl w-full max-w-md p-10 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2.5 bg-brand-yellow"></div>
        <div class="text-center mb-8 pt-2">
            <img src="/assets/logo.png" alt="Logo KickDropShoe" class="w-24 h-24 mx-auto mb-4 object-contain mix-blend-multiply">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">KICKDROPSHOE</h1>
            <p class="text-xs text-slate-500 font-medium mt-1">Sistem Informasi Manajemen</p>
        </div>

        <form id="login-form" method="POST" action="/login/process" class="space-y-5">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <div>
                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">Username</label>
                <input type="text" name="username" id="username" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-yellow" placeholder="Masukan username..." required>
            </div>
            <div class="relative">
                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" id="password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-yellow" placeholder="Masukan password..."  required>
                <button type="button" id="togglePassword" class="absolute right-4 top-10 text-slate-400 hover:text-slate-600 focus:outline-none">
                    <i class="fas fa-eye"></i>
                </button>

            <!-- forgot password -zahra -->
                <div class="flex justify-end mt-2">
                    <button type="button" onclick="document.getElementById('modal-forgot').classList.remove('hidden')" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    Forgot Password?
                </button>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="success-message">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            </div>
            <button type="submit" class="w-full py-4 bg-brand-yellow hover:bg-brand-yellowHover text-slate-900 font-extrabold text-sm rounded-xl transition-all shadow-lg shadow-brand-yellow/30 mt-4 tracking-wide border border-slate-900/10">
                Login
            </button>
        </form>
    </div>

        <!-- Modal Forgot Password (Email Only) -->
    <div id="modal-forgot" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full shadow-2xl relative space-y-4">
            <div class="text-center space-y-1">
                <div class="w-12 h-12 bg-yellow-100 text-yellow-800 rounded-2xl flex items-center justify-center mx-auto text-xl shadow-sm border border-yellow-200">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3 class="text-lg font-black text-slate-900">Forgot Password</h3>
                <p class="text-xs text-slate-500">Masukkan email Anda untuk menerima tautan reset kata sandi.</p>
            </div>
            <form method="POST" action="/forgot-password" class="space-y-3">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Email Address</label>
                    <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-brand-yellow" placeholder="nama@email.com" required>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('modal-forgot').classList.add('hidden')" class="w-1/2 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-xs rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="w-1/2 py-3 bg-brand-yellow hover:bg-brand-yellowHover text-slate-900 font-extrabold text-xs rounded-xl transition shadow-md">
                        Send Link
                    </button>
                </div>
            </form>
        </div>
    </div>

    

    <script src="/assets/js/main.js"></script>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        if (togglePassword && password) {
            togglePassword.addEventListener('click', function (e) {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
        }
    </script>
</body>
</html>
