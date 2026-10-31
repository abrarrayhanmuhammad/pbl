<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$page_title = 'Tambah Pesanan Baru - KickDropShoe';
$current_page = 'tambah_pesanan';
require_once __DIR__ . '/../layouts/header.php';
?>
<div class="flex h-screen overflow-hidden bg-brand-slateBg">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-y-auto relative pb-28">
        <header class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 md:py-5 flex justify-between items-center sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <!-- Mobile Hamburger Button -->
                <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition focus:outline-none" aria-label="Menu">
                    <i class="fas fa-bars text-base"></i>
                </button>
                <div>
                    <h2 class="text-lg md:text-xl font-extrabold text-slate-900 tracking-tight leading-tight">Tambah Pesanan Baru</h2>
                </div>
            </div>
            <div class="text-xs font-bold text-slate-600 bg-slate-100 px-3 md:px-4 py-1.5 md:py-2 rounded-full border border-slate-200 shrink-0">
                Total: <span class="font-extrabold text-slate-900 grand-total-val">Rp 0</span>
            </div>
        </header>


        <form action="/pesanan/simpan" method="post" id="form-pesanan" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <div class="p-4 md:p-8 max-w-6xl mx-auto w-full space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 md:p-8 shadow-sm space-y-5">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-user-circle text-slate-400"></i> Informasi Pelanggan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                        <div class="relative" id="customer-field">
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">
                                NAMA PELANGGAN
                            </label>

                            <div class="relative">
                                <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 pr-10 text-xs font-semibold text-slate-800 outline-none focus:border-yellow-400" placeholder="Pilih atau masukkan nama pelanggan" autocomplete="off" required>

                                <button type="button"  id="toggle-customer-dropdown" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                            </div>

                            <div id="customer-dropdown" class="hidden absolute z-50 top-full left-0 w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden">
                                <div class="max-h-52 overflow-y-auto" id="customer-list">
                                    <?php foreach ($customer as $pelanggan): ?>
                                        <button type="button"  class="customer-option w-full text-left px-4 py-3 hover:bg-yellow-50 border-b border-slate-100 last:border-0 transition" data-nama="<?= htmlspecialchars($pelanggan['nama']) ?>" data-no-wa="<?= htmlspecialchars($pelanggan['no_hp']) ?>">
                                            <p class="text-xs font-bold text-slate-800">
                                                <?= htmlspecialchars($pelanggan['nama']) ?>
                                            </p>
                                            <p class="text-[10px] text-slate-400 mt-1">
                                                <?= htmlspecialchars($pelanggan['no_hp']) ?>
                                            </p>
                                        </button>
                                    <?php endforeach; ?>

                                    <div id="customer-empty"  class="hidden px-4 py-4 text-center text-xs text-slate-400">
                                        Pelanggan tidak ditemukan.
                                        <br>
                                        Silakan masukkan pelanggan baru.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">
                                NO WHATSAPP
                            </label>
                            <input type="text" name="no_wa" id="no_wa" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none" placeholder="08..." required>
                        </div>

                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">
                                DROP POINT
                            </label>
                            <select name="drop_point"  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none">
                                <?php foreach ($dropPoint as $item): ?>
                                    <option value="<?= $item['id_drop_point'] ?>">
                                        <?= htmlspecialchars($item['alamat_lokasi']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">
                                TANGGAL MASUK
                            </label>
                            <div class="relative">
                                <input name="tgl_masuk"  type="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none" placeholder="dd/mm/yyyy" required>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5 pt-2">
                        <!-- <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">TANGGAL SELESAI</label>
                            <div class="relative">
                                <input name="tgl_selesai" type="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none" placeholder="dd/mm/yyyy">
                                <i class="far fa-calendar absolute right-3.5 top-3.5 text-slate-400 text-xs"></i>
                            </div>
                            <p class="text-[9px] text-slate-400 font-medium mt-1">Diisi saat pengerjaan selesai</p>
                        </div> -->
                    </div>
                </div>

                <!-- Dynamic Add Item Button Trigger -->
                <button type="button" class="add-item-btn-trigger w-full py-4 border-2 border-dashed border-slate-300 rounded-3xl text-slate-500 font-extrabold text-xs hover:bg-white hover:border-slate-400 hover:text-slate-800 transition flex items-center justify-center gap-2">
                    <i class="fas fa-plus-circle"></i> Tambah Barang Cuci / Repair
                </button>
            </div>

            <div class="fixed bottom-0 right-0 w-full lg:w-[calc(100%-280px)] bg-white border-t border-slate-200 px-4 md:px-8 py-3.5 md:py-4 flex flex-col sm:flex-row justify-between items-center gap-3 z-30 shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
                <div class="w-full sm:w-auto flex justify-between sm:block">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Total Tagihan Keseluruhan</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-900 grand-total-val">Rp 0</h3>
                </div>
                <div class="flex items-center gap-2 md:gap-4 w-full sm:w-auto justify-end">
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <span class="text-xs font-bold text-slate-500 hidden sm:inline">Metode pembayaran</span>
                        <select name="metode_pembayaran" class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 md:px-3 py-2 text-xs font-extrabold text-slate-800 outline-none">
                            <option value="Cash" selected>Cash</option>
                            <option value="Transfer">Transfer</option>
                            <option value="QRIS">QRIS</option>
                        </select>
                    </div>
                    <button type="button" id="btn-batal" class="px-4 md:px-6 py-2.5 md:py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold rounded-xl text-xs transition">Batal</button>
                    <button type="submit" class="flex-1 sm:flex-none justify-center px-4 md:px-6 py-2.5 md:py-3 bg-brand-yellow hover:bg-brand-yellowHover text-slate-900 font-extrabold rounded-xl text-xs transition shadow-lg shadow-brand-yellow/30 flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> <span class="whitespace-nowrap">Simpan Pesanan</span>
                    </button>
                </div>
            </div>
        </form>
    </main>
</div>
<script>
    window.PRICELIST_DATA = <?= json_encode(
                                array_reduce($getAllLayanan, function ($result, $item) {
                                    $kategori = $item['kategori'];

                                    if ($kategori === 'Package') {
                                        return $result;
                                    }

                                    if (!isset($result[$kategori])) {
                                        $result[$kategori] = [];
                                    }

                                    $result[$kategori][] = [
                                        'id' => (int) $item['id_layanan'],
                                        'tipe' => 'layanan',
                                        'name' => $item['nama_layanan'],
                                        'price' => (float) $item['harga_min'],
                                        'estimasi_min' => $item['estimasi_min_hari'],
                                        'estimasi_max' => $item['estimasi_max_hari'],
                                        'variasi' => $item['variasi']
                                    ];

                                    return $result;
                                }, [
                                    'Package' => array_map(function ($paket) {
                                        return [
                                            'id' => (int) $paket['id_paket'],
                                            'tipe' => 'package',
                                            'name' => $paket['nama_paket'],
                                            'price' => (float) $paket['harga'],
                                            'estimasi_min' => $paket['estimasi_min_hari'],
                                            'estimasi_max' => $paket['estimasi_max_hari']
                                        ];
                                    }, $getAllPaket)
                                ])
                            ); ?>;

    window.LAYANAN_DATA = <?= json_encode(array_values(array_unique(array_column($layanan, 'kategori')))) ?>;
    window.TREATMENT_KECEPATAN = <?= json_encode($treatmentKecepatan) ?>;
    window.TREATMENT_TAMBAHAN = <?= json_encode($treatmentTambahan) ?>;

    document.getElementById('btn-batal').addEventListener('click', function() {
        Swal.fire({
            title: 'Batalkan Pesanan?',
            text: 'Data yang sudah kamu isi akan hilang jika belum disimpan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Lanjut Mengisi',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/pesanan';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const inputNama = document.getElementById('nama_pelanggan');
        const inputWa = document.getElementById('no_wa');
        const daftarPelanggan = document.getElementById('daftar-pelanggan');

        inputNama.addEventListener('change', function() {
            const pilihan = Array.from(daftarPelanggan.options).find(option => {
                return option.value === this.value.trim();
            });

            if (pilihan) {
                this.value = pilihan.dataset.nama;
                inputWa.value = pilihan.dataset.noWa;
            }
        });
    });
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>