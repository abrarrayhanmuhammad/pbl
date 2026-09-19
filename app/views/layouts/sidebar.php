<aside class="sidebar">

    <div class="brand">
        KICKDROPSHOE
    </div>

    <div class="sidebar-line"></div>

    <nav class="menu">

        <a href="/dashboard" class="menu-link active">
            Dashboard
        </a>

        <a href="/pesanan" class="menu-link">
            Pesanan
        </a>

        <a href="/pesanan/tambah" class="menu-link">
            Tambah Pesanan
        </a>

        <?php if ($_SESSION['role'] === 'superadmin'): ?>

            <a href="/laporan" class="menu-link">
                Laporan
            </a>

            <a href="/akun" class="menu-link">
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