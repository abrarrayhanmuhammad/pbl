<?php
$page_title = 'Aktivitas Pesanan - KickDropShoe';
$current_page = 'aktivitas';
require_once __DIR__ . '/../layouts/header.php';

$badgeMap = [
    'Pesanan'     => 'bg-blue-50 text-blue-700 border-blue-200',
];
$namaBulan = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$roleMap = [
    'superadmin' => 'Owner',
    'karyawan'   => 'Karyawan',
];
$zonaJakarta = new DateTimeZone('Asia/Jakarta');

$system_logs = array_map(function ($row) use ($badgeMap, $namaBulan, $roleMap, $zonaJakarta) {
    $dt = new DateTime($row['createdAt']);
    $dt->setTimezone($zonaJakarta);
    return [
        'time'     => $dt->format('d') . ' ' . $namaBulan[(int) $dt->format('n')] . ' ' . $dt->format('Y, H:i') . ' WIB',
        'user'     => $row['namaPegawai'] ?? 'Sistem',
        'role'     => $roleMap[$row['rolePegawai']] ?? '-',
        'category' => $row['kategori'],
        'badge'    => $badgeMap[$row['kategori']] ?? 'bg-slate-100 text-slate-700 border-slate-200',
        'ref'      => $row['referensi'] ?? '-',
        'action'   => $row['aksi'],
        'desc'     => $row['keterangan'] ?? '',
        'ip'       => $row['ipAddress'] ?? '-',
        'kodePesanan'       => $row['kodePesanan'] ?? '-',
    ];
}, $logs ?? []);

?>
<div class="flex h-screen overflow-hidden bg-brand-slateBg">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <?php
        $header_title = 'Aktivitas Pesanan ' .  $system_logs[0]['kodePesanan'];
        require_once __DIR__ . '/../layouts/topbar.php';
        ?>

        <div class="p-4 sm:p-8 max-w-7xl mx-auto w-full space-y-6 pb-36 lg:pb-8">
            <!-- Header Ringkasan & Filter -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">Riwayat Operasional</h3>
                        <p class="text-xs text-slate-500 font-medium">Catatan rekam jejak aktivitas staf.</p>
                    </div>
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold border border-slate-200">
                        Total <?= count($system_logs) ?> Entri Tercatat
                    </span>
                </div>
            </div>

            <!-- Tabel Log -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-400 font-extrabold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="p-4 pl-6">Waktu</th>
                                <th class="p-4">Pengguna</th>
                                <th class="p-4">Kategori</th>
                                <th class="p-4">Aksi & Referensi</th>
                                <th class="p-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="log-tbody" class="divide-y divide-slate-100 font-medium">
                            <?php if (empty($system_logs)): ?>
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-slate-400 font-bold">Belum ada aktivitas tercatat.</td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($system_logs as $log): ?>
                                <tr class="log-row hover:bg-slate-50/70 transition" data-category="<?= htmlspecialchars($log['category']) ?>">
                                    <td class="p-4 pl-6 font-bold text-slate-800 whitespace-nowrap"><?= htmlspecialchars($log['time']) ?></td>
                                    <td class="p-4">
                                        <p class="font-extrabold text-slate-900 leading-tight"><?= htmlspecialchars($log['user']) ?></p>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase"><?= htmlspecialchars($log['role']) ?></span>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold border <?= $log['badge'] ?>">
                                            <?= htmlspecialchars($log['category']) ?>
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <p class="font-bold text-slate-800"><?= htmlspecialchars($log['action']) ?></p>
                                        <span class="font-mono text-[10px] text-slate-500 font-semibold"><?= htmlspecialchars($log['ref']) ?></span>
                                    </td>
                                    <td class="p-4 text-slate-600 max-w-xs text-xs"><?= htmlspecialchars($log['desc']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
let currentCategory = 'Semua';

function setCategoryFilter(category, btn) {
    currentCategory = category;
    document.querySelectorAll('.log-filter-btn').forEach(b => {
        b.className = 'log-filter-btn px-4 py-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl text-xs font-bold shrink-0';
    });
    btn.className = 'log-filter-btn px-4 py-2 bg-brand-darkCard text-white rounded-xl text-xs font-extrabold shadow-sm shrink-0';
    filterLogs();
}

function filterLogs() {
    const keyword = document.getElementById('log-search').value.toLowerCase();
    const rows = document.querySelectorAll('.log-row');

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        const category = row.dataset.category;
        const matchCategory = currentCategory === 'Semua' || category === currentCategory;
        const matchSearch = text.includes(keyword);

        row.style.display = (matchCategory && matchSearch) ? '' : 'none';
    });
}
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

