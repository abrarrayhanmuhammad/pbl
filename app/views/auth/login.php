<?php
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// // Handle Logout Action
// if (isset($_GET['action']) && $_GET['action'] === 'logout') {
//     $_SESSION = [];
//     if (ini_get("session.use_cookies")) {
//         $params = session_get_cookie_params();
//         setcookie(session_name(), '', time() - 42000,
//             $params["path"], $params["domain"],
//             $params["secure"], $params["httponly"]
//         );
//     }
//     session_destroy();
//     header('Location: login.php');
//     exit;
// }

// // Handle Login Form POST
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $username = strtolower(trim($_POST['username'] ?? ''));
//     if (strpos($username, 'karyawan') !== false || strpos($username, 'bagas') !== false) {
//         $_SESSION['user_role'] = 'KARYAWAN';
//         $_SESSION['user_name'] = 'Bagas (Karyawan)';
//         $_SESSION['user_initials'] = 'BK';
//         header('Location: dashboard_karyawan.php');
//         exit;
//     } else {
//         $_SESSION['user_role'] = 'OWNER';
//         $_SESSION['user_name'] = 'Super Admin';
//         $_SESSION['user_initials'] = 'SA';
//         header('Location: dashboard_owner.php');
//         exit;
//     }
// }

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
                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">Username / Email</label>
                <input type="text" name="username" id="username" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-yellow" value="admin@kickdrop.com" required>
            </div>
            <div class="relative">
                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" id="password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-yellow" value="password123" required>
                <button type="button" id="togglePassword" class="absolute right-4 top-10 text-slate-400 hover:text-slate-600 focus:outline-none">
                    <i class="fas fa-eye"></i>
                </button>

            <!-- lupa password -zahra -->
                <div class="flex justify-end mt-2">
                    <a href="/forgot-password" class="text-xs" font bold text-slate-500 hover:text-slate-800 transition-colors">
                Lupa Password?
            </a>
                </div>

            </div>
            <button type="submit" class="w-full py-4 bg-brand-yellow hover:bg-brand-yellowHover text-slate-900 font-extrabold text-sm rounded-xl transition-all shadow-lg shadow-brand-yellow/30 mt-4 tracking-wide border border-slate-900/10">
                Login
            </button>
        </form>
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
