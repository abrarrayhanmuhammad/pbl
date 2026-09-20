<?php
$currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>

<aside class="sidebar" id="sidebar">

    <div class="brand">
        KICKDROPSHOE
    </div>

    <div class="sidebar-line"></div>

    <nav class="menu">

        <a
            href="/dashboard"
            class="menu-link <?= $currentUrl === '/dashboard' ? 'active' : '' ?>"
        >
            Dashboard
        </a>

        <a
            href="/pesanan"
            class="menu-link <?= $currentUrl === '/pesanan' ? 'active' : '' ?>"
        >
            Pesanan
        </a>

        <a
            href="/pesanan/tambah"
            class="menu-link <?= $currentUrl === '/pesanan/tambah' ? 'active' : '' ?>"
        >
            Tambah Pesanan
        </a>

        <?php if ($_SESSION['role'] === 'superadmin'): ?>

            <a
                href="/laporan"
                class="menu-link <?= $currentUrl === '/laporan' ? 'active' : '' ?>"
            >
                Laporan
            </a>

            <a
                href="/akun"
                class="menu-link <?= $currentUrl === '/akun' ? 'active' : '' ?>"
            >
                Akun
            </a>

        <?php endif; ?>

    </nav>

    <div class="sidebar-bottom">

        <div class="operator">
            Login sebagai:

            <strong>
                <?= htmlspecialchars($_SESSION['nama']) ?>
            </strong>
        </div>

        <a href="/logout" class="logout">
            Logout
        </a>

    </div>

</aside>