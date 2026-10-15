<?php

$title = 'Detail Pesanan - KickDropShoe';
$pageTitle = 'DETAIL PESANAN';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$status = strtolower($pesanan['status'] ?? 'menunggu');

/*
|--------------------------------------------------------------------------
| Data detail barang
|--------------------------------------------------------------------------
| Pastikan controller mengirim:
| $pesanan['detail_barang']
|
| detail_barang berisi banyak barang dalam satu pesanan.
|--------------------------------------------------------------------------
*/

$detailBarang = $pesanan['detail_barang'] ?? [];

/*
|--------------------------------------------------------------------------
| Hitung jumlah barang dan total
|--------------------------------------------------------------------------
*/

$jumlahBarang = count($detailBarang);

$totalPesanan = 0;

foreach ($detailBarang as $barang) {
    $totalPesanan += (float) ($barang['subtotal'] ?? 0);
}

/*
|--------------------------------------------------------------------------
| Estimasi pesanan
|--------------------------------------------------------------------------
| Mengambil estimasi terlama dari semua barang.
|--------------------------------------------------------------------------
*/

$estimasiMin = null;
$estimasiMax = null;

foreach ($detailBarang as $barang) {

    if (isset($barang['estimasi_min'])) {

        if (
            $estimasiMin === null ||
            $barang['estimasi_min'] > $estimasiMin
        ) {
            $estimasiMin = $barang['estimasi_min'];
        }
    }

    if (isset($barang['estimasi_max'])) {

        if (
            $estimasiMax === null ||
            $barang['estimasi_max'] > $estimasiMax
        ) {
            $estimasiMax = $barang['estimasi_max'];
        }
    }
}

/*
|--------------------------------------------------------------------------
| Format estimasi selesai
|--------------------------------------------------------------------------
*/

$estimasiSelesai = '-';

if (
    !empty($pesanan['tgl_masuk']) &&
    $estimasiMin !== null &&
    $estimasiMax !== null
) {

    $tglMasuk = new DateTime($pesanan['tgl_masuk']);

    $tglMin = clone $tglMasuk;
    $tglMax = clone $tglMasuk;

    $tglMin->modify('+' . (int) $estimasiMin . ' days');
    $tglMax->modify('+' . (int) $estimasiMax . ' days');

    if ($estimasiMin == $estimasiMax) {

        $estimasiSelesai = $tglMin->format('d-m-Y');

    } else {

        $estimasiSelesai =
            $tglMin->format('d-m-Y')
            . ' s/d '
            . $tglMax->format('d-m-Y');
    }
}

?>

<div class="main-content">

    <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

    <div class="detail-pesanan-page">

        <!-- ===================================================== -->
        <!-- HEADER DETAIL -->
        <!-- ===================================================== -->

        <div class="detail-pesanan-top">

            <div>
                <p>
                    #<?= str_pad(
                        $pesanan['id_pesanan'],
                        4,
                        '0',
                        STR_PAD_LEFT
                    ) ?>
                </p>
            </div>

        </div>


        <!-- ===================================================== -->
        <!-- INFORMASI PESANAN -->
        <!-- ===================================================== -->

        <section class="detail-section">

            <div class="detail-section-title">
                INFORMASI PESANAN
            </div>

            <div class="detail-info-grid">

                <div class="detail-info-item">
                    <span>ID PESANAN</span>

                    <strong>
                        #<?= str_pad(
                            $pesanan['id_pesanan'],
                            4,
                            '0',
                            STR_PAD_LEFT
                        ) ?>
                    </strong>
                </div>


                <div class="detail-info-item">
                    <span>TANGGAL MASUK</span>

                    <strong>
                        <?= !empty($pesanan['tgl_masuk'])
                            ? date(
                                'd-m-Y',
                                strtotime($pesanan['tgl_masuk'])
                            )
                            : '-'
                        ?>
                    </strong>
                </div>


                <div class="detail-info-item">
                    <span>KODE CUSTOMER</span>

                    <strong>
                        <?= htmlspecialchars(
                            $pesanan['kode_customer'] ?? '-'
                        ) ?>
                    </strong>
                </div>


                <div class="detail-info-item">
                    <span>NAMA CUSTOMER</span>

                    <strong>
                        <?= htmlspecialchars(
                            $pesanan['customer'] ?? '-'
                        ) ?>
                    </strong>
                </div>


                <div class="detail-info-item">
                    <span>NO. HP</span>

                    <strong>
                        <?= htmlspecialchars(
                            $pesanan['no_hp'] ?? '-'
                        ) ?>
                    </strong>
                </div>


                <div class="detail-info-item">
                    <span>JUMLAH BARANG</span>

                    <strong>
                        <?= $jumlahBarang ?> Barang
                    </strong>
                </div>


                <div class="detail-info-item">
                    <span>ESTIMASI SELESAI</span>

                    <strong>
                        <?= $estimasiSelesai ?>
                    </strong>
                </div>

            </div>

        </section>


        <!-- ===================================================== -->
        <!-- STATUS PESANAN -->
        <!-- ===================================================== -->

        <section class="detail-section">

            <div class="detail-section-title">
                STATUS PENGERJAAN
            </div>

            <div class="detail-status-wrapper">

                <div class="detail-status-step">

                    <div class="detail-status-icon
                        <?= $status === 'menunggu' ||
                           in_array(
                               $status,
                               ['dicuci', 'diproses', 'selesai']
                           )
                            ? 'active'
                            : ''
                        ?>">
                        <?= in_array(
                            $status,
                            ['menunggu', 'dicuci', 'diproses', 'selesai']
                        ) ? '✓' : '1' ?>
                    </div>

                    <span>Menunggu</span>

                </div>


                <div class="detail-status-line
                    <?= in_array(
                        $status,
                        ['dicuci', 'diproses', 'selesai']
                    ) ? 'active' : '' ?>">
                </div>


                <div class="detail-status-step">

                    <div class="detail-status-icon
                        <?= in_array(
                            $status,
                            ['dicuci', 'diproses', 'selesai']
                        ) ? 'active' : '' ?>">
                        <?= in_array(
                            $status,
                            ['dicuci', 'diproses', 'selesai']
                        ) ? '✓' : '2' ?>
                    </div>

                    <span>Dicuci</span>

                </div>


                <div class="detail-status-line
                    <?= in_array(
                        $status,
                        ['diproses', 'selesai']
                    ) ? 'active' : '' ?>">
                </div>


                <div class="detail-status-step">

                    <div class="detail-status-icon
                        <?= in_array(
                            $status,
                            ['diproses', 'selesai']
                        ) ? 'active' : '' ?>">
                        <?= in_array(
                            $status,
                            ['diproses', 'selesai']
                        ) ? '✓' : '3' ?>
                    </div>

                    <span>Diproses</span>

                </div>


                <div class="detail-status-line
                    <?= $status === 'selesai' ? 'active' : '' ?>">
                </div>


                <div class="detail-status-step">

                    <div class="detail-status-icon
                        <?= $status === 'selesai'
                            ? 'active'
                            : ''
                        ?>">
                        <?= $status === 'selesai'
                            ? '✓'
                            : '4'
                        ?>
                    </div>

                    <span>Selesai</span>

                </div>

            </div>


            <div class="detail-current-status">

                Status saat ini:

                <strong>
                    <?= htmlspecialchars(
                        $pesanan['status'] ?? '-'
                    ) ?>
                </strong>

            </div>

        </section>


        <!-- ===================================================== -->
        <!-- DAFTAR BARANG -->
        <!-- ===================================================== -->

        <section class="detail-section">

            <div class="detail-section-heading">

                <div class="detail-section-title no-border">
                    DAFTAR BARANG
                </div>

                <span class="detail-item-count">
                    <?= $jumlahBarang ?> Barang
                </span>

            </div>


            <?php if (empty($detailBarang)): ?>

                <div class="detail-empty">

                    Belum ada barang pada pesanan ini.

                </div>

            <?php else: ?>


                <div class="detail-items">

                    <?php foreach ($detailBarang as $index => $barang): ?>

                        <?php

                        $fotoBefore = !empty($barang['foto_before'])
                            ? '/' . ltrim(
                                $barang['foto_before'],
                                '/'
                            )
                            : null;

                        $fotoAfter = !empty($barang['foto_after'])
                            ? '/' . ltrim(
                                $barang['foto_after'],
                                '/'
                            )
                            : null;

                        $hargaLayanan =
                            (float) (
                                $barang['harga_layanan'] ?? 0
                            );

                        $hargaTreatment =
                            (float) (
                                $barang['harga_treatment'] ?? 0
                            );

                        $subtotal =
                            (float) (
                                $barang['subtotal']
                                ?? (
                                    $hargaLayanan
                                    + $hargaTreatment
                                )
                            );

                        ?>

                        <article class="detail-item-card">


                            <!-- ================================= -->
                            <!-- HEADER BARANG -->
                            <!-- ================================= -->

                            <div class="detail-item-header">

                                <div>

                                    <span class="detail-item-number">
                                        BARANG #<?= $index + 1 ?>
                                    </span>

                                    <h3>
                                        <?= htmlspecialchars(
                                            $barang['merk'] ?? 'Barang'
                                        ) ?>
                                    </h3>

                                </div>

                                <div class="detail-item-price">

                                    Rp <?= number_format(
                                        $subtotal,
                                        0,
                                        ',',
                                        '.'
                                    ) ?>

                                </div>

                            </div>


                            <!-- ================================= -->
                            <!-- INFORMASI BARANG -->
                            <!-- ================================= -->

                            <div class="detail-item-body">

                                <div class="detail-item-information">

                                    <div class="detail-info-row">
                                        <span>Jenis Barang</span>

                                        <strong>
                                            <?= htmlspecialchars(
                                                $barang['jenis']
                                                ?? '-'
                                            ) ?>
                                        </strong>
                                    </div>


                                    <div class="detail-info-row">
                                        <span>Merk</span>

                                        <strong>
                                            <?= !empty(
                                                $barang['merk']
                                            )
                                                ? htmlspecialchars(
                                                    $barang['merk']
                                                )
                                                : '-'
                                            ?>
                                        </strong>
                                    </div>


                                    <div class="detail-info-row">
                                        <span>Warna</span>

                                        <strong>
                                            <?= !empty(
                                                $barang['warna']
                                            )
                                                ? htmlspecialchars(
                                                    $barang['warna']
                                                )
                                                : '-'
                                            ?>
                                        </strong>
                                    </div>


                                    <div class="detail-info-row">
                                        <span>Variasi</span>

                                        <strong>
                                            <?= !empty(
                                                $barang['variasi']
                                            )
                                                ? htmlspecialchars(
                                                    $barang['variasi']
                                                )
                                                : '-'
                                            ?>
                                        </strong>
                                    </div>


                                    <div class="detail-info-row">
                                        <span>Layanan</span>

                                        <strong>
                                            <?= htmlspecialchars(
                                                $barang['layanan']
                                                ?? '-'
                                            ) ?>
                                        </strong>
                                    </div>


                                    <div class="detail-info-row">
                                        <span>Treatment</span>

                                        <strong>
                                            <?= !empty(
                                                $barang['treatment']
                                            )
                                                ? htmlspecialchars(
                                                    $barang['treatment']
                                                )
                                                : '-'
                                            ?>
                                        </strong>
                                    </div>


                                    <div class="detail-info-row">
                                        <span>Harga Layanan</span>

                                        <strong>
                                            Rp <?= number_format(
                                                $hargaLayanan,
                                                0,
                                                ',',
                                                '.'
                                            ) ?>
                                        </strong>
                                    </div>


                                    <div class="detail-info-row">
                                        <span>Harga Treatment</span>

                                        <strong>
                                            Rp <?= number_format(
                                                $hargaTreatment,
                                                0,
                                                ',',
                                                '.'
                                            ) ?>
                                        </strong>
                                    </div>


                                    <div class="detail-info-row">
                                        <span>Estimasi</span>

                                        <strong>

                                            <?php

                                            $estimasiBarang = '-';

                                            if (
                                                isset(
                                                    $barang['estimasi_min']
                                                ) &&
                                                isset(
                                                    $barang['estimasi_max']
                                                )
                                            ) {

                                                if (
                                                    $barang['estimasi_min']
                                                    ==
                                                    $barang['estimasi_max']
                                                ) {

                                                    $estimasiBarang =
                                                        $barang['estimasi_min']
                                                        . ' hari';

                                                } else {

                                                    $estimasiBarang =
                                                        $barang['estimasi_min']
                                                        . '–'
                                                        . $barang['estimasi_max']
                                                        . ' hari';
                                                }
                                            }

                                            ?>

                                            <?= $estimasiBarang ?>

                                        </strong>
                                    </div>

                                </div>


                                <!-- ================================= -->
                                <!-- FOTO -->
                                <!-- ================================= -->

                                <div class="detail-item-photos">

                                    <div class="detail-photo">

                                        <div class="detail-photo-title">
                                            FOTO SEBELUM
                                        </div>

                                        <?php if ($fotoBefore): ?>

                                            <img
                                                src="<?= htmlspecialchars(
                                                    $fotoBefore
                                                ) ?>"
                                                alt="Foto sebelum"
                                            >

                                        <?php else: ?>

                                            <div class="detail-photo-empty">
                                                Belum ada foto
                                            </div>

                                        <?php endif; ?>

                                    </div>


                                    <div class="detail-photo">

                                        <div class="detail-photo-title">
                                            FOTO SESUDAH
                                        </div>

                                        <?php if ($fotoAfter): ?>

                                            <img
                                                src="<?= htmlspecialchars(
                                                    $fotoAfter
                                                ) ?>"
                                                alt="Foto sesudah"
                                            >

                                        <?php else: ?>

                                            <div class="detail-photo-empty">
                                                Belum ada foto
                                            </div>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </div>


                            <!-- ================================= -->
                            <!-- CATATAN BARANG -->
                            <!-- ================================= -->

                            <div class="detail-item-note">

                                <span>Catatan Barang</span>

                                <p>
                                    <?= !empty(
                                        $barang['catatan']
                                    )
                                        ? htmlspecialchars(
                                            $barang['catatan']
                                        )
                                        : 'Tidak ada catatan.'
                                    ?>
                                </p>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </section>


        <!-- ===================================================== -->
        <!-- CATATAN PESANAN -->
        <!-- ===================================================== -->

        <section class="detail-section">

            <div class="detail-section-title">
                CATATAN PESANAN
            </div>

            <div class="detail-order-note">

                <?= !empty($pesanan['catatan_pesanan'])
                    ? htmlspecialchars(
                        $pesanan['catatan_pesanan']
                    )
                    : 'Tidak ada catatan pesanan.'
                ?>

            </div>

        </section>


        <!-- ===================================================== -->
        <!-- INFORMASI PEMBAYARAN -->
        <!-- ===================================================== -->

        <section class="detail-section">

            <div class="detail-section-title">
                INFORMASI PEMBAYARAN
            </div>

            <div class="detail-payment-grid">

                <div class="detail-payment-item">

                    <span>Jumlah Barang</span>

                    <strong>
                        <?= $jumlahBarang ?> Barang
                    </strong>

                </div>


                <div class="detail-payment-item">

                    <span>Total Pesanan</span>

                    <strong class="detail-total">

                        Rp <?= number_format(
                            $totalPesanan,
                            0,
                            ',',
                            '.'
                        ) ?>

                    </strong>

                </div>


                <div class="detail-payment-item">

                    <span>Metode</span>

                    <strong>
                        <?= !empty($pesanan['metode'])
                            ? htmlspecialchars(
                                $pesanan['metode']
                            )
                            : '-'
                        ?>
                    </strong>

                </div>


                <div class="detail-payment-item">

                    <span>Status Pembayaran</span>

                    <strong>
                        <?= !empty(
                            $pesanan['status_pembayaran']
                        )
                            ? htmlspecialchars(
                                $pesanan['status_pembayaran']
                            )
                            : 'Belum Dibayar'
                        ?>
                    </strong>

                </div>

            </div>

        </section>


        <!-- ===================================================== -->
        <!-- FOOTER ACTION -->
        <!-- ===================================================== -->

        <div class="detail-pesanan-footer">

            <a
                href="/pesanan"
                class="detail-btn detail-btn-secondary"
            >
                Kembali
            </a>

            <a
                href="/pesanan/edit?id=<?= $pesanan['id_pesanan'] ?>"
                class="detail-btn detail-btn-primary"
            >
                Edit Pesanan
            </a>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>