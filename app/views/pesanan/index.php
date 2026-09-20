<?php

$title = 'Pesanan - KickDropShoe';
$pageTitle = 'DAFTAR PESANAN';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="main-content">
    <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

    <div class="page-action">
        <a href="/pesanan/tambah" class="btn-add">
            + Tambah Pesanan
        </a>
    </div>


    <form action="/pesanan" method="GET" class="filter-container">

        <div class="filter-group">

            <label for="customer">
                CARI CUSTOMER
            </label>

            <input
                type="text"
                id="customer"
                name="customer"
                placeholder="Ketik nama..."
                value="<?= htmlspecialchars($customer) ?>"
            >

        </div>


        <div class="filter-group">

            <label for="jenis">
                JENIS BARANG
            </label>

            <select id="jenis" name="jenis">

                <option value="">
                    Semua (Sepatu/Tas/Helm)
                </option>

                <?php foreach ($jenisBarang as $jenisItem): ?>

                    <option
                        value="<?= $jenisItem['id_jenis'] ?>"
                        <?= $jenis == $jenisItem['id_jenis'] ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($jenisItem['nama_jenis']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <div class="filter-group">

            <label for="status">
                STATUS
            </label>

            <select id="status" name="status">

                <option value="">
                    Semua Status
                </option>

                <option
                    value="Menunggu"
                    <?= $status === 'Menunggu' ? 'selected' : '' ?>
                >
                    Menunggu
                </option>

                <option
                    value="Dicuci"
                    <?= $status === 'Dicuci' ? 'selected' : '' ?>
                >
                    Dicuci
                </option>

                <option
                    value="Diproses"
                    <?= $status === 'Diproses' ? 'selected' : '' ?>
                >
                    Diproses
                </option>

                <option
                    value="Selesai"
                    <?= $status === 'Selesai' ? 'selected' : '' ?>
                >
                    Selesai
                </option>

            </select>

        </div>


        <div class="filter-button">

            <button type="submit">
                Filter
            </button>

        </div>

    </form>


    <!-- Tabel -->
    <div class="pesanan-table-container">

        <table class="pesanan-table">

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Jenis</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

            </thead>


            <tbody>

                <?php if (empty($pesanan)): ?>

                    <tr>

                        <td
                            colspan="6"
                            class="empty-data"
                        >
                            Tidak ada pesanan ditemukan.
                        </td>

                    </tr>

                <?php else: ?>

                    <?php foreach ($pesanan as $item): ?>

                        <tr>

                            <td>
                                #<?= str_pad(
                                    $item['id_pesanan'],
                                    4,
                                    '0',
                                    STR_PAD_LEFT
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $item['customer']
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $item['jenis']
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $item['layanan']
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $item['status']
                                ) ?>
                            </td>

                            <td>

                                <div class="action-links">

                                    <a
                                        href="/pesanan/edit?id=<?= $item['id_pesanan'] ?>"
                                    >
                                        Edit
                                    </a>

                                    <span>|</span>

                                    <a
                                        href="/pesanan/hapus?id=<?= $item['id_pesanan'] ?>"
                                        onclick="return confirm('Yakin ingin menghapus pesanan ini?')"
                                    >
                                        Hapus
                                    </a>

                                    <button
                                        type="button"
                                        class="detail-button"
                                        onclick="alert('Detail pesanan #<?= $item['id_pesanan'] ?>')"
                                    >
                                        Detail
                                    </button>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>

    </div>


    <!-- Pagination -->
    <div class="pagination-container">

        <div class="pagination-info">
            Menampilkan <?= $totalPesanan > 0 ? $offset + 1 : 0 ?> - <?= min($offset + $limit, $totalPesanan) ?> dari <?= $totalPesanan ?> pesanan
        </div>


        <?php if ($totalPages > 1): ?>

            <div class="pagination">

                <?php if ($page > 1): ?>

                    <a
                        href="/pesanan?customer=<?= urlencode($customer) ?>&jenis=<?= urlencode($jenis) ?>&status=<?= urlencode($status) ?>&page=<?= $page - 1 ?>"
                        class="page-button"
                    >
                        Sebelumnya
                    </a>

                <?php endif; ?>


                <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                    <a
                        href="/pesanan?customer=<?= urlencode($customer) ?>&jenis=<?= urlencode($jenis) ?>&status=<?= urlencode($status) ?>&page=<?= $i ?>"
                        class="page-number <?= $i === $page ? 'active' : '' ?>"
                    >
                        <?= $i ?>
                    </a>

                <?php endfor; ?>


                <?php if ($page < $totalPages): ?>

                    <a
                        href="/pesanan?customer=<?= urlencode($customer) ?>&jenis=<?= urlencode($jenis) ?>&status=<?= urlencode($status) ?>&page=<?= $page + 1 ?>"
                        class="page-button"
                    >
                        Selanjutnya
                    </a>

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>