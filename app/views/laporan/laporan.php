<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_role = $_SESSION['role'] ?? 'superadmin';

// Proteksi Hak Akses: Laporan hanya untuk OWNER
if ($user_role !== 'superadmin') {
    header('Location: dashboard_karyawan.php');
    exit;
}

$page_title = 'Laporan & Keuangan - KickDropShoe';
$current_page = 'laporan';
require_once __DIR__ . '/../layouts/header.php';

// Variabel dari LaporanController (fallback kalau kosong)
$statistik          = $statistik          ?? ['total_pesanan' => 0, 'total_barang' => 0, 'total_pendapatan' => 0];
$rincianPembayaran  = $rincianPembayaran  ?? [];

// Helper: pilih icon & warna berdasarkan metode
function metodeStyle(string $metode): array {
    $m = strtolower(trim($metode));
    if (str_contains($m, 'cash') || str_contains($m, 'tunai')) {
        return ['icon' => 'fa-money-bill-wave', 'bg' => 'bg-blue-50',   'text' => 'text-blue-600',   'label' => 'Cash (Tunai)'];
    }
    if (str_contains($m, 'qris') || str_contains($m, 'qr')) {
        return ['icon' => 'fa-qrcode',          'bg' => 'bg-teal-50',   'text' => 'text-teal-600',   'label' => 'QRIS'];
    }
    if (str_contains($m, 'transfer') || str_contains($m, 'bank')) {
        return ['icon' => 'fa-credit-card',     'bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'label' => 'Transfer'];
    }
    return ['icon' => 'fa-wallet', 'bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'label' => ucwords($metode)];
}
?>
<div class="flex h-screen overflow-hidden bg-brand-slateBg">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <?php
        $header_title = 'Laporan & Keuangan';
        require_once __DIR__ . '/../layouts/topbar.php';
        ?>

        <div class="p-4 md:p-8 max-w-7xl mx-auto w-full space-y-6 pb-36 lg:pb-8">

            <!-- ============ FILTER TANGGAL ============ -->
            <form method="GET" action="/laporan"
                  class="bg-white rounded-3xl border border-slate-200 p-4 md:p-6 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-stretch md:items-center">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 md:gap-4">
                    <div class="relative">
                        <input type="date" name="start_date"
                               value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-600 outline-none">
                    </div>
                    <span class="text-xs font-semibold text-slate-400 text-center sm:text-left">sampai</span>
                    <div class="relative">
                        <input type="date" name="end_date"
                               value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-600 outline-none">
                    </div>
                    <button type="submit"
                            class="px-6 py-3 bg-brand-yellow hover:bg-brand-yellowHover text-slate-900 text-xs font-extrabold rounded-xl transition shadow-md shadow-brand-yellow/20">
                        Filter Data
                    </button>
                    <?php if (!empty($_GET['start_date']) || !empty($_GET['end_date'])): ?>
                        <a href="/laporan"
                           class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-extrabold rounded-xl transition text-center">
                            Reset
                        </a>
                    <?php endif; ?>
                </div>
                <button type="button"
                        class="px-6 py-3 bg-brand-darkCard hover:bg-slate-800 text-white text-xs font-extrabold rounded-xl transition shadow-md flex items-center justify-center gap-2">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </form>

            <!-- ============ CARD STATISTIK ============ -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                <div class="bg-brand-darkCard text-white rounded-3xl p-8 shadow-xl">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">PESANAN SELESAI</p>
                    <h3 class="text-3xl font-black text-brand-yellow">
                        <?= number_format($statistik['total_pesanan'], 0, ',', '.') ?>
                    </h3>
                </div>
                <div class="bg-brand-darkCard text-white rounded-3xl p-8 shadow-xl">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">BARANG DICUCI</p>
                    <h3 class="text-3xl font-black text-brand-yellow">
                        <?= number_format($statistik['total_barang'], 0, ',', '.') ?>
                    </h3>
                </div>
                <div class="bg-brand-darkCard text-white rounded-3xl p-8 shadow-xl">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">TOTAL PENDAPATAN</p>
                    <h3 class="text-3xl font-black text-emerald-400">
                        Rp <?= number_format($statistik['total_pendapatan'], 0, ',', '.') ?>
                    </h3>
                </div>
            </div>

            <!-- ============ RINCIAN PER METODE ============ -->
            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-6">
                <h3 class="text-sm font-extrabold text-slate-800 tracking-tight border-b border-slate-100 pb-4">
                    Rincian Transaksi Berdasarkan Metode Pembayaran
                </h3>

                <div class="space-y-4">
                    <?php if (!empty($rincianPembayaran)): ?>
                        <?php foreach ($rincianPembayaran as $row):
                            $style = metodeStyle($row['metode']);
                        ?>
                            <div class="p-5 border border-slate-200 rounded-2xl flex justify-between items-center">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 <?= $style['bg'] ?> <?= $style['text'] ?> rounded-xl flex items-center justify-center text-lg shadow-sm">
                                        <i class="fas <?= $style['icon'] ?>"></i>
                                    </div>
                                    <h4 class="font-extrabold text-slate-900 text-sm"><?= htmlspecialchars($style['label']) ?></h4>
                                </div>
                                <span class="font-black text-slate-900 text-base">
                                    Rp <?= number_format($row['total'], 0, ',', '.') ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-8 text-center text-slate-400 text-sm">
                            <i class="fas fa-inbox text-3xl mb-3 block"></i>
                            Belum ada transaksi lunas pada periode ini.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>