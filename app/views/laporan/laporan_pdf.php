<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan - KickDropShoe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            @page { margin: 1cm; }
            body { background: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 p-4 sm:p-8">

    <!-- BAGIAN HEADER (Kop Surat) -->
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-8 sm:p-10 print:shadow-none print:border-none print:p-0 mb-6 print:mb-4 relative overflow-hidden">
        <!-- Aksen garis kuning kiri -->
        <div class="absolute top-0 left-0 w-2 h-full bg-brand-yellow print:hidden" style="background-color: #facc15;"></div>
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-6 mb-6">
            <div class="flex items-center gap-4">
                <img src="/assets/logo.png" alt="Logo KickDropShoe" class="w-14 h-14 rounded-full border border-slate-200 shadow-sm object-cover bg-white">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">KickDropShoe</h1>
                    <p class="text-xs font-medium text-slate-500 mt-1">Jl. Klayatan GG II NO 19, Malang • 0851-7958-3250</p>
                </div>
            </div>
            <div class="text-left sm:text-right">
                <h2 class="text-xl font-black text-slate-900 uppercase tracking-widest text-slate-400">Laporan Keuangan</h2>
                <p class="text-sm font-bold text-slate-800 mt-1">
                    <?php if (!empty($_GET['start_date']) && !empty($_GET['end_date'])): ?>
                        Periode: <?= date('d/m/Y', strtotime($_GET['start_date'])) ?> - <?= date('d/m/Y', strtotime($_GET['end_date'])) ?>
                    <?php else: ?>
                        Periode: Semua Data
                    <?php endif; ?>
                </p>
                <p class="text-xs font-medium text-slate-500 mt-1">Dicetak pada: <?= date('d/m/Y H:i') ?> WIB</p>
            </div>
        </div>

        <!-- BAGIAN STATISTIK CARD -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <!-- Card 1 -->
            <div class="bg-brand-darkCard rounded-xl p-5 text-white" style="background-color: #1e293b;">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Pesanan Selesai</p>
                <h3 class="text-2xl font-black text-brand-yellow" style="color: #facc15;">
                    <?= number_format($statistik['total_pesanan'], 0, ',', '.') ?>
                </h3>
            </div>
            <!-- Card 2 -->
            <div class="bg-brand-darkCard rounded-xl p-5 text-white" style="background-color: #1e293b;">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Barang Dicuci</p>
                <h3 class="text-2xl font-black text-brand-yellow" style="color: #facc15;">
                    <?= number_format($statistik['total_barang'], 0, ',', '.') ?>
                </h3>
            </div>
            <!-- Card 3 -->
            <div class="bg-brand-darkCard rounded-xl p-5 text-white" style="background-color: #1e293b;">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Total Pendapatan</p>
                <h3 class="text-2xl font-black text-emerald-400" style="color: #34d399;">
                    Rp <?= number_format($statistik['total_pendapatan'], 0, ',', '.') ?>
                </h3>
            </div>
        </div>

        <!-- BAGIAN RINCIAN TABEL -->
        <div>
            <h3 class="text-sm font-extrabold text-slate-800 tracking-tight mb-4">Rincian Transaksi Berdasarkan Metode Pembayaran</h3>
            
            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-600 uppercase tracking-wider">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4">Metode Pembayaran</th>
                            <th class="py-3 px-4 text-right">Total Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        <?php if (!empty($rincianPembayaran)): ?>
                            <?php foreach ($rincianPembayaran as $i => $row): ?>
                                <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50/50">
                                    <td class="py-3 px-4 text-center font-medium text-slate-500"><?= $i + 1 ?></td>
                                    <td class="py-3 px-4 font-bold"><?= htmlspecialchars($row['metode']) ?></td>
                                    <td class="py-3 px-4 text-right font-black">Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <!-- Baris Total -->
                            <tr class="bg-slate-50 font-bold text-slate-900 border-t-2 border-slate-200">
                                <td colspan="2" class="py-4 px-4 text-right text-xs uppercase tracking-wider">Total Pendapatan</td>
                                <td class="py-4 px-4 text-right text-base font-black">Rp <?= number_format($statistik['total_pendapatan'], 0, ',', '.') ?></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="py-8 px-4 text-center text-slate-400 italic">
                                    Belum ada data transaksi lunas pada periode ini.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- BAGIAN FOOTER -->
        <div class="mt-10 pt-6 border-t border-slate-100 text-center">
            <p class="text-xs font-medium text-slate-400">
                Laporan ini dibuat secara otomatis oleh sistem KickDropShoe.<br>
                &copy; <?= date('Y') ?> KickDropShoe. All rights reserved.
            </p>
        </div>
    </div>

    <!-- TOMBOL AKSI (Hanya muncul di layar, hilang saat di-print) -->
    <div class="max-w-4xl mx-auto flex justify-center gap-3 no-print mb-10">
        <button onclick="window.print()" class="px-6 py-3 bg-brand-yellow hover:bg-yellow-400 text-slate-900 text-sm font-extrabold rounded-xl transition shadow-md flex items-center gap-2" style="background-color: #facc15;">
            <i class="fas fa-print"></i> Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" class="px-6 py-3 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-extrabold rounded-xl transition shadow-sm flex items-center gap-2">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>

    <script>
        // Opsional: Otomatis buka dialog print saat halaman selesai dimuat
        window.addEventListener('load', function () {
            // Delay sedikit agar font & gambar selesai loading
            setTimeout(function () {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>