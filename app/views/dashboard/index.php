<?php

$title = 'Dashboard - KickDropShoe';
$pageTitle = 'DASHBOARD OWNER';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="main-content">
    <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

    <section class="statistics <?= $_SESSION['role'] === 'superadmin' ? 'admin-statistics' : 'karyawan-statistics' ?>">

        <div class="stat-card">
            <p>TOTAL PESANAN</p>
            <h2><?= $totalPesanan ?></h2>
        </div>

        <div class="stat-card">
            <p>AKTIF</p>
            <h2><?= $pesananAktif ?></h2>
        </div>

        <div class="stat-card">
            <p>SELESAI HARI INI</p>
            <h2><?= $selesaiHariIni ?></h2>
        </div>

        <?php if ($_SESSION['role'] === 'superadmin'): ?>

        <div class="stat-card">
            <p>PENDAPATAN</p>
            <h2>
                Rp <?= number_format($pendapatan, 0, ',', '.') ?>
            </h2>
        </div>

        <?php endif; ?>

    </section>


    <section class="dashboard-content <?= $_SESSION['role'] === 'superadmin' ? 'admin-dashboard-content' : 'karyawan-dashboard-content' ?>">

    <?php if ($_SESSION['role'] === 'superadmin'): ?>

        <div class="chart-container">

            <h3>Grafik Pesanan Mingguan</h3>

            <div class="chart-box">
                <div class="chart-placeholder">
                    Grafik
                </div>
            </div>

        </div>

    <?php endif; ?>


    <div class="orders-container">

        <h3>Pesanan Terbaru</h3>

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Barang</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>

                <?php if (empty($pesananTerbaru)): ?>

                    <tr>
                        <td colspan="4">
                            Belum ada pesanan.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($pesananTerbaru as $pesanan): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($pesanan['id']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($pesanan['customer']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($pesanan['barang']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($pesanan['bayar']) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</section>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>