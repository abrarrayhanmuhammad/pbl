<?php
$page_title = 'Pratinjau Invoice ' . $pesanan['kode_pesanan'] . '- KickDropShoe';
$current_page = 'invoice';
$is_invoice = true;
$body_class = 'font-sans antialiased bg-brand-slateBg print:bg-white';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar (Hilang hanya saat dicetak) -->
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-y-auto print:overflow-visible">
        <!-- Header (Hilang hanya saat dicetak) -->
        <?php
            $header_title = 'Invoice';
            $header_subtitle = $pesanan['kode_pesanan'];
            require_once __DIR__ . '/../layouts/topbar.php';
        ?>

        <div class="p-4 md:p-8 flex flex-col items-center gap-6 print:p-0">
            <!-- Tombol Aksi (Hilang hanya saat dicetak) -->
            <div class="w-full max-w-4xl flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 print:hidden">
                <a href="/pesanan" class="w-full sm:w-auto justify-center px-5 py-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-900 rounded-xl text-sm font-bold transition flex items-center gap-2 shadow-sm">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Daftar Pesanan
                </a>

                <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2.5 w-full sm:w-auto">
                    <button type="button" onclick="window.print()" class="justify-center px-3 md:px-4 py-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-900 rounded-xl text-xs md:text-sm font-bold transition flex items-center gap-2 shadow-sm">
                        <i class="fas fa-file-pdf"></i>
                        Unduh PDF
                    </button>

                    <button type="button" onclick="window.print()" class="justify-center px-3 md:px-4 py-3 bg-brand-yellow hover:bg-brand-yellowHover text-slate-900 rounded-xl text-xs md:text-sm font-bold transition flex items-center gap-2 shadow-sm">
                        <i class="fas fa-print"></i>
                        Cetak Sekarang
                    </button>

                    <button type="button" onclick="unduhInvoiceGambar()" class="justify-center px-3 md:px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs md:text-sm font-bold transition flex items-center gap-2 shadow-sm">
                        <i class="fas fa-image"></i>
                        Unduh PNG
                    </button>

                    <button type="button" onclick="kirimInvoiceWhatsApp()" class="justify-center px-3 md:px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl text-xs md:text-sm font-bold transition flex items-center gap-2 shadow-sm">
                        <i class="fab fa-whatsapp"></i>
                        Kirim Invoice
                    </button>
                </div>
            </div>

            <!-- KERTAS INVOICE (Yang akan tercetak) -->
            <div id="invoice-capture" class="w-full max-w-4xl bg-white shadow-xl border border-slate-200 print:shadow-none print:border-none print:max-w-full rounded-2xl md:rounded-3xl overflow-hidden">
                <!-- Garis Kuning Atas -->
                <div class="h-2 w-full bg-brand-yellow"></div>

                <div class="p-6 sm:p-10 space-y-6 sm:space-y-8">
                    <!-- Header Invoice: Di HP bertumpuk rapi, di Desktop tetap berdampingan persis aslinya -->
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                        <!-- Bagian Kiri (Logo & Alamat) -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-3 mb-2 sm:mb-4">
                                <img src="/assets/logo.png" alt="Logo" class="w-10 h-10 rounded-full shadow-md border-1.5 border-slate-900 object-cover bg-white">
                                <h2 class="text-2xl font-black text-slate-900">KickDropShoe</h2>
                            </div>
                            <p class="text-sm font-medium text-slate-500">Jl. Klayatan GG II NO 19, Malang</p>
                            <p class="text-sm font-medium text-slate-500">Telp 0851-7958-3250</p>
                        </div>

                        <!-- Bagian Kanan (Nomor Invoice) -->
                        <div class="text-left sm:text-right space-y-1 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-0 border-slate-100">
                            <p class="text-lg font-black text-slate-900">NOTA TRANSAKSI</p>
                            <h1 class="text-3xl font-black text-slate-900"><?= htmlspecialchars($pesanan['kode_pesanan']) ?></h1>
                            <p class="text-sm font-medium text-slate-500 mt-1 sm:mt-2">Tanggal: <?= date('d-m-Y', strtotime($pesanan['tanggal_masuk'])) ?></p>
                        </div>
                    </div>

                    <!-- Info Pelanggan: Di HP bertumpuk rapi, di Desktop berdampingan persis aslinya -->
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                        <div class="space-y-1">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">PELANGGAN</p>
                            <p class="text-lg font-black text-slate-900"><?= htmlspecialchars($pesanan['nama_customer']) ?></p>
                            <p class="text-sm font-medium text-slate-500">No WhatsApp: <?= htmlspecialchars($pesanan['no_hp']) ?></p>
                            <p class="text-sm font-medium text-slate-500">Drop Point: <?= htmlspecialchars($pesanan['alamat_lokasi']) ?></p>
                        </div>
                        <div class="text-left sm:text-right space-y-2 w-full sm:w-auto">
                            <?php if ($pesanan['status_bayar'] === 'Belum Bayar'): ?>
                                <div class="inline-block px-3 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-bold mb-1"><?= htmlspecialchars($pesanan['status_bayar']) ?> - <?= htmlspecialchars($pesanan['metode_bayar']) ?></div>
                            <?php else: ?>
                                <div class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-md text-xs font-bold mb-1"><?= htmlspecialchars($pesanan['status_bayar']) ?> - <?= htmlspecialchars($pesanan['metode_bayar']) ?></div>
                            <?php endif; ?>
                            <p class="text-sm font-medium text-slate-500">Status: <?= htmlspecialchars($pesanan['status']) ?></p>
                            <p class="text-sm font-medium text-slate-500">Penanggung Jawab: <?= htmlspecialchars($pesanan['nama_pegawai'] ?? '-') ?></p>
                            <p class="text-sm font-medium text-slate-500">Tanggal Selesai: <?= ($pesanan['tanggal_selesai'] === '' ? htmlspecialchars($pesanan['tanggal_selesai']) : 'Belum Selesai') ?></p>
                        </div>
                    </div>
                    <!-- Tabel Pesanan -->
                    <div class="w-full overflow-x-auto">
                        <div class="min-w-[500px]">
                            <div class="bg-brand-darkCard text-white px-5 py-4 flex font-bold text-sm rounded-t-xl">
                                <div class="flex-1">Deskripsi / Barang</div>
                                <div class="w-20 text-center">Qty</div>
                                <div class="w-32 text-right">Harga</div>
                                <div class="w-32 text-right">Subtotal</div>
                            </div>

                            <!-- Item 1 -->
                            <?php foreach ($barang as $item): ?>
                                <div class="px-5 py-4 border-b border-slate-200 flex text-sm">
                                    <div class="flex-1 space-y-1">
                                        <p class="font-extrabold text-slate-900"><?= htmlspecialchars($item['nama_barang']) ?> • <?= htmlspecialchars($item['warna']) ?></p>
                                        <p class="text-xs font-medium text-slate-500"><?= htmlspecialchars($item['nama_layanan']) ?>: Rp <?= htmlspecialchars(number_format(($item['harga_satuan'] ?? 0), 0, ',', '.')) ?>
                                        <?php foreach ($treatmentByDetail[$item['id_detail_pesanan']] as $treatment): ?>
                                            <br><?= htmlspecialchars($treatment['nama_treatment']) ?>: Rp <?= htmlspecialchars(number_format(($treatment['harga'] ?? 0), 0, ',', '.')) ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="w-20 text-center font-medium text-slate-700">1</div>
                                    <div class="w-32 text-right font-medium text-slate-700">Rp <?= htmlspecialchars(number_format(($item['subtotal'] ?? 0), 0, ',', '.')) ?></div>
                                    <div class="w-32 text-right font-extrabold text-slate-900">Rp <?= htmlspecialchars(number_format(($item['subtotal'] ?? 0), 0, ',', '.')) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Total Akhir -->
                        <div class="bg-slate-50 p-5 rounded-xl flex justify-between items-center border border-slate-100">
                            <p class="text-base font-bold text-slate-700">Total Akhir</p>
                            <p class="text-3xl font-black text-slate-900">Rp <?= htmlspecialchars(number_format(($pesanan['total_bayar'] ?? 0), 0, ',', '.')) ?></p>
                        </div>

                        <!-- Ketentuan & Footer -->
                        <div class="space-y-6 pt-4">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">KETENTUAN</p>
                                <p class="text-xs font-medium text-slate-700">* Barang yang sudah diambil tidak dapat dikembalikan.</p>
                            </div>
                            <hr class="border-slate-200">
                            <p class="text-center font-bold text-slate-900">Terima kasih atas kepercayaan Anda!</p>
                        </div>
                    </div>
                </div>

                <!-- <p class="text-xs font-medium text-slate-400 print:hidden mt-2">Tombol dan navigasi tidak termasuk dalam hasil cetak.</p> -->
            </div>
    </main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>


<script>
    const dataInvoice = {
        kodePesanan: <?= json_encode($pesanan['kode_pesanan']) ?>,
        namaCustomer: <?= json_encode($pesanan['nama_customer']) ?>,
        noHp: <?= json_encode($pesanan['no_hp']) ?>
    };
    async function unduhInvoiceGambar() {
        const tombol = document.querySelector('[onclick="unduhInvoiceGambar()"]');
        const invoice = document.getElementById('invoice-capture');

        try {
            tombol.disabled = true;
            tombol.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membuat Gambar...';

            await document.fonts.ready;

            const gambar = invoice.querySelectorAll('img');

            await Promise.all(
                Array.from(gambar).map(img => {
                    if (img.complete) return Promise.resolve();

                    return new Promise(resolve => {
                        img.onload = resolve;
                        img.onerror = resolve;
                    });
                })
            );

            const lebar = Math.max(invoice.scrollWidth, 800);

            const canvas = await html2canvas(invoice, {
                scale: 2,
                backgroundColor: '#ffffff',
                useCORS: true,
                logging: false,
                width: lebar,
                height: invoice.scrollHeight,
                windowWidth: lebar,
                windowHeight: invoice.scrollHeight,
                onclone: clonedDocument => {
                    const clonedInvoice = clonedDocument.getElementById('invoice-capture');

                    clonedInvoice.style.width = lebar + 'px';
                    clonedInvoice.style.maxWidth = 'none';
                    clonedInvoice.style.height = 'auto';
                    clonedInvoice.style.overflow = 'visible';

                    clonedInvoice.querySelectorAll('.overflow-x-auto').forEach(element => {
                        element.style.overflow = 'visible';
                    });
                }
            });

            const link = document.createElement('a');
            link.download = 'Invoice-<?= htmlspecialchars($pesanan['kode_pesanan'], ENT_QUOTES, 'UTF-8') ?>-' + Date.now() + '-KickDropShoe.png';
            link.href = canvas.toDataURL('image/png');
            link.click();

            return true;
        } catch (error) {
            console.error('Gagal membuat invoice:', error);
            alert('Gagal membuat gambar invoice. Silakan coba kembali.');
            return false;
        } finally {
            tombol.disabled = false;
            tombol.innerHTML = '<i class="fas fa-image"></i> Unduh PNG';
        }

    }

    async function kirimInvoiceWhatsApp() {
        let nomor = String(dataInvoice.noHp || '').replace(/\D/g, '');

        if (nomor.startsWith('0')) {
            nomor = '62' + nomor.substring(1);
        } else if (nomor.startsWith('8')) {
            nomor = '62' + nomor;
        }

        if (nomor.length < 10) {
            alert('Nomor WhatsApp pelanggan tidak valid.');
            return;
        }

        const whatsapp = window.open('about:blank', '_blank');

        if (!whatsapp) {
            alert('Izinkan pop-up pada browser untuk membuka WhatsApp.');
            return;
        }

        const berhasil = await unduhInvoiceGambar();

        if (!berhasil) {
            whatsapp.close();
            return;
        }

        const pesan = [
            `Halo Kak ${dataInvoice.namaCustomer || 'Customer'}!`,
            '',
            'Berikut kami lampirkan invoice pesanan Anda dari *KickDropShoe*.',
            '',
            `Kode Pesanan: ${dataInvoice.kodePesanan}`,
            '',
            'Silakan periksa rincian transaksi pada invoice terlampir.',
            '',
            'Terima kasih telah mempercayakan perawatan barang Anda kepada *KickDropShoe*!',
            '',
            'Salam bersih,',
            '*Tim KickDropShoe*'
        ].join('\n');

        whatsapp.location.href = `https://wa.me/${nomor}?text=${encodeURIComponent(pesan)}`;

        Swal.fire({
            icon: 'success',
            title: 'Invoice Berhasil Dibuat',
            text: 'Invoice sudah diunduh. Silakan lampirkan file PNG tersebut pada chat WhatsApp sebelum mengirim pesan.',
            confirmButtonColor: '#16a34a'
        });
    }
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>