<?php
$page_title = 'Kelola Karyawan - KickDropShoe';
$current_page = 'karyawan';
require_once __DIR__ . '/../layouts/header.php';

if (empty($dataPegawai)) {
    $dataPegawai = [
        [
            'id_pegawai' => 1,
            'nama' => 'Super Admin',
            'uname' => 'admin',
            'email' => 'admin@kickdropshoe.com',
            'role' => 'superadmin'
        ],
        [
            'id_pegawai' => 2,
            'nama' => 'Bagas (Karyawan)',
            'uname' => 'bagas',
            'email' => 'bagas@kickdropshoe.com',
            'role' => 'karyawan'
        ],
        [
            'id_pegawai' => 3,
            'nama' => 'Karyawan Demo',
            'uname' => 'demo',
            'email' => 'demo@kickdropshoe.com',
            'role' => 'karyawan'
        ]
    ];
}
$totalPegawai = $totalPegawai ?? count($dataPegawai);
$totalSuperadmin = $totalSuperadmin ?? count(array_filter($dataPegawai, fn($p) => ($p['role'] ?? '') === 'superadmin'));
$currentUserId = (int) ($_SESSION['id_pegawai'] ?? 1);
?>
<div class="flex h-screen overflow-hidden bg-brand-slateBg">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <?php
        $header_title = 'Kelola Karyawan';
        require_once __DIR__ . '/../layouts/topbar.php';
        ?>

        <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6 pb-36 lg:pb-8">

            <!-- Alert Notifikasi Status -->
            <?php if (isset($_GET['status'])): ?>
                <?php
                $statusList = [
                    'tambah_berhasil'     => ['type' => 'success', 'msg' => 'Karyawan baru berhasil ditambahkan.'],
                    'update_berhasil'     => ['type' => 'success', 'msg' => 'Data karyawan berhasil diperbarui.'],
                    'hapus_berhasil'      => ['type' => 'success', 'msg' => 'Karyawan berhasil dihapus.'],
                    'hapus_diri_sendiri'  => ['type' => 'error',   'msg' => 'Tidak dapat menghapus akun Anda sendiri.'],
                    'kosong'              => ['type' => 'error',   'msg' => 'Nama lengkap, username, dan password wajib diisi.'],
                    'password_pendek'     => ['type' => 'error',   'msg' => 'Password minimal 8 karakter.'],
                    'password_beda'       => ['type' => 'error',   'msg' => 'Konfirmasi password tidak cocok.'],
                    'username_terpakai'   => ['type' => 'error',   'msg' => 'Username sudah digunakan, silakan pilih yang lain.'],
                    'email_invalid'       => ['type' => 'error',   'msg' => 'Format email tidak valid.'],
                    'email_terpakai'      => ['type' => 'error',   'msg' => 'Email sudah terdaftar.'],
                    'gagal'               => ['type' => 'error',   'msg' => 'Terjadi kesalahan, silakan coba lagi.']
                ];
                $alert = $statusList[$_GET['status']] ?? null;
                ?>
                <?php if ($alert): ?>
                    <div id="alert-box" class="p-4 rounded-xl flex items-center justify-between transition-all <?= $alert['type'] === 'success' ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : 'bg-red-50 border border-red-200 text-red-800' ?>">
                        <p class="text-xs sm:text-sm font-semibold"><?= htmlspecialchars($alert['msg']) ?></p>
                        <button type="button" onclick="document.getElementById('alert-box').remove()" class="text-slate-400 hover:text-slate-600 p-1">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Ringkasan Metrik (Minimalis, Tanpa Simbol/Ikon, Tanpa Tulisan di Bawah Angka) -->
            <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:gap-6">
                <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm">
                    <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">TOTAL KARYAWAN</p>
                    <h3 class="text-2xl sm:text-3xl lg:text-5xl font-black text-slate-900"><?= (int) $totalPegawai ?></h3>
                </div>
                <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-5 sm:p-6 lg:p-8 shadow-sm">
                    <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">SUPER ADMIN</p>
                    <h3 class="text-2xl sm:text-3xl lg:text-5xl font-black text-slate-900"><?= (int) $totalSuperadmin ?></h3>
                </div>
            </div>

            <!-- Kontrol Bar: Pencarian, Filter Role & Tombol Tambah -->
            <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
                <div class="flex flex-1 gap-2.5 max-w-xl">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                        <input type="text" id="karyawan-search-input" onkeyup="filterKaryawan()" class="w-full bg-white border border-slate-200 rounded-xl pl-9 pr-4 py-2.5 text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400 shadow-sm transition" placeholder="Cari nama, username, atau email...">
                    </div>
                    <select id="karyawan-role-filter" onchange="filterKaryawan()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-slate-400 shadow-sm cursor-pointer shrink-0">
                        <option value="all">Semua Peran</option>
                        <option value="karyawan">Karyawan</option>
                        <option value="superadmin">Super Admin</option>
                    </select>
                </div>

                <button type="button" onclick="bukaModalTambah()" class="px-5 py-2.5 bg-brand-yellow hover:bg-brand-yellowHover text-slate-900 font-extrabold text-xs rounded-xl transition shadow-sm border border-slate-900/10 flex items-center justify-center gap-2 shrink-0">
                    <i class="fas fa-plus text-xs"></i>
                    <span>Tambah Karyawan</span>
                </button>
            </div>

            <!-- Kontainer Data Karyawan -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <!-- Header Card -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 tracking-tight">Daftar Karyawan</h3>
                    <span id="karyawan-counter" class="text-xs font-bold text-slate-400">
                        <?= count($dataPegawai) ?> Karyawan
                    </span>
                </div>

                <!-- Desktop Table View (Tabel Rapi, Aksi Sejajar di Kanan) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse table-fixed">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider bg-slate-50/50">
                                <th class="py-3.5 px-6 w-2/5">Karyawan</th>
                                <th class="py-3.5 px-6 w-1/5">Username</th>
                                <th class="py-3.5 px-6 w-1/5">Email</th>
                                <th class="py-3.5 px-6 w-1/6">Peran</th>
                                <th class="py-3.5 px-6 w-44 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="karyawan-table-body" class="divide-y divide-slate-100 text-xs">
                            <?php if (!empty($dataPegawai)): ?>
                                <?php foreach ($dataPegawai as $p): ?>
                                    <?php
                                    $nama = htmlspecialchars($p['nama'] ?? '-');
                                    $uname = htmlspecialchars($p['uname'] ?? '-');
                                    $email = htmlspecialchars($p['email'] ?? '-');
                                    $role = htmlspecialchars($p['role'] ?? 'karyawan');
                                    $id = (int) $p['id_pegawai'];
                                    $isMe = ($id === $currentUserId);
                                    ?>
                                    <tr class="karyawan-item hover:bg-slate-50/60 transition" data-nama="<?= strtolower($nama) ?>" data-uname="<?= strtolower($uname) ?>" data-email="<?= strtolower($email) ?>" data-role="<?= strtolower($role) ?>">
                                        <!-- Nama Karyawan -->
                                        <td class="py-4 px-6 truncate">
                                            <div class="flex items-center gap-2.5">
                                                <span class="font-extrabold text-slate-900 text-sm truncate"><?= $nama ?></span>
                                                <?php if ($isMe): ?>
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 shrink-0">Anda</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <!-- Username -->
                                        <td class="py-4 px-6 font-semibold text-slate-600 truncate">
                                            <?= $uname ?>
                                        </td>
                                        <!-- Email -->
                                        <td class="py-4 px-6 font-medium text-slate-500 truncate">
                                            <?= $email !== '-' ? $email : '<span class="text-slate-300">—</span>' ?>
                                        </td>
                                        <!-- Peran -->
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <?php if ($role === 'superadmin'): ?>
                                                <span class="inline-block px-2.5 py-1 bg-slate-900 text-brand-yellow rounded-md text-[11px] font-extrabold">
                                                    Super Admin
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-[11px] font-extrabold">
                                                    Karyawan
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <!-- Aksi (Sejajar Rapi di Kanan) -->
                                        <td class="py-4 px-6 text-right whitespace-nowrap">
                                            <div class="inline-flex items-center justify-end gap-2 w-full">
                                                <button type="button" onclick='bukaModalEdit(<?= json_encode($p, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' class="w-16 py-1.5 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition">
                                                    Edit
                                                </button>
                                                <?php if (!$isMe): ?>
                                                    <button type="button" onclick="bukaModalHapus(<?= $id ?>, '<?= addslashes($nama) ?>')" class="w-16 py-1.5 text-center bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs rounded-lg transition">
                                                        Hapus
                                                    </button>
                                                <?php else: ?>
                                                    <span class="w-16 py-1.5 text-center text-xs font-semibold text-slate-400 bg-slate-50 border border-slate-100 rounded-lg inline-block select-none">
                                                        Aktif
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="no-karyawan-row">
                                    <td colspan="5" class="py-12 text-center text-slate-400 font-medium">
                                        Belum ada data karyawan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card List View (Rapi, Sejajar, Mudah Disentuh) -->
                <div class="md:hidden divide-y divide-slate-100" id="karyawan-card-list">
                    <?php if (!empty($dataPegawai)): ?>
                        <?php foreach ($dataPegawai as $p): ?>
                            <?php
                            $nama = htmlspecialchars($p['nama'] ?? '-');
                            $uname = htmlspecialchars($p['uname'] ?? '-');
                            $email = htmlspecialchars($p['email'] ?? '-');
                            $role = htmlspecialchars($p['role'] ?? 'karyawan');
                            $id = (int) $p['id_pegawai'];
                            $isMe = ($id === $currentUserId);
                            ?>
                            <div class="karyawan-item p-4 space-y-3" data-nama="<?= strtolower($nama) ?>" data-uname="<?= strtolower($uname) ?>" data-email="<?= strtolower($email) ?>" data-role="<?= strtolower($role) ?>">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-extrabold text-slate-900 text-sm"><?= $nama ?></h4>
                                            <?php if ($isMe): ?>
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-600">Anda</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-xs font-semibold text-slate-500 mt-0.5"><?= $uname ?></p>
                                        <?php if ($email !== '-'): ?>
                                            <p class="text-[11px] text-slate-400 mt-0.5"><?= $email ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($role === 'superadmin'): ?>
                                        <span class="px-2.5 py-1 bg-slate-900 text-brand-yellow rounded-md text-[10px] font-extrabold shrink-0">Admin</span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-[10px] font-extrabold shrink-0">Karyawan</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Aksi Sejajar di Mobile -->
                                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-50">
                                    <button type="button" onclick='bukaModalEdit(<?= json_encode($p, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' class="w-20 py-1.5 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition">
                                        Edit
                                    </button>
                                    <?php if (!$isMe): ?>
                                        <button type="button" onclick="bukaModalHapus(<?= $id ?>, '<?= addslashes($nama) ?>')" class="w-20 py-1.5 text-center bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs rounded-lg transition">
                                            Hapus
                                        </button>
                                    <?php else: ?>
                                        <span class="w-20 py-1.5 text-center text-xs font-semibold text-slate-400 bg-slate-50 border border-slate-100 rounded-lg inline-block select-none">
                                            Aktif
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Pesan Pencarian Kosong -->
                <div id="empty-search-message" class="hidden py-12 text-center text-slate-400 font-medium text-xs">
                    Tidak ditemukan karyawan yang sesuai.
                </div>
            </div>

        </div>
    </main>
</div>

<!-- ========================================================= -->
<!-- MODAL 1: TAMBAH KARYAWAN (Desain Bersih & Elegan)          -->
<!-- ========================================================= -->
<div id="modal-tambah" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl p-6 sm:p-7 max-w-md w-full shadow-xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Tambah Karyawan</h3>
            <button type="button" onclick="tutupModalTambah()" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <form method="POST" action="/karyawan/simpan" class="space-y-3.5">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400" placeholder="Masukkan nama lengkap">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Username</label>
                    <input type="text" name="uname" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400" placeholder="Username">
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Peran</label>
                    <select name="role" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-slate-400 cursor-pointer">
                        <option value="karyawan">Karyawan</option>
                        <option value="superadmin">Super Admin</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Email (Opsional)</label>
                <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400" placeholder="nama@email.com">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Password</label>
                    <input type="password" name="password" required minlength="8" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400" placeholder="Min. 8 karakter">
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Ulangi Password</label>
                    <input type="password" name="password_konfirmasi" required minlength="8" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400" placeholder="Konfirmasi">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="tutupModalTambah()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl transition shadow-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL 2: EDIT KARYAWAN (Desain Bersih & Elegan)            -->
<!-- ========================================================= -->
<div id="modal-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl p-6 sm:p-7 max-w-md w-full shadow-xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Edit Karyawan</h3>
            <button type="button" onclick="tutupModalEdit()" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <form method="POST" action="/karyawan/update" class="space-y-3.5">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="id_pegawai" id="edit-id-pegawai" value="">

            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama" id="edit-nama" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:outline-none focus:border-slate-400">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Username</label>
                    <input type="text" name="uname" id="edit-uname" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:outline-none focus:border-slate-400">
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Peran</label>
                    <select name="role" id="edit-role" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-slate-400 cursor-pointer">
                        <option value="karyawan">Karyawan</option>
                        <option value="superadmin">Super Admin</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Email (Opsional)</label>
                <input type="email" name="email" id="edit-email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:outline-none focus:border-slate-400">
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">
                    Password Baru <span class="text-slate-400 font-normal lowercase">(kosongkan jika tidak diubah)</span>
                </label>
                <input type="password" name="password" id="edit-password" minlength="8" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400" placeholder="Masukkan password baru jika ingin diganti">
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="tutupModalEdit()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL 3: KONFIRMASI HAPUS                                  -->
<!-- ========================================================= -->
<div id="modal-hapus" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-xl space-y-4">
        <div>
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Hapus Karyawan</h3>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                Yakin ingin menghapus akun <span id="hapus-nama-display" class="font-extrabold text-slate-800"></span>? Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
        <form method="POST" action="/karyawan/hapus" class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="id_pegawai" id="hapus-id-pegawai" value="">
            <button type="button" onclick="tutupModalHapus()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 transition">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-extrabold text-xs rounded-xl transition shadow-sm">
                Hapus
            </button>
        </form>
    </div>
</div>

<script>
// Filter Pencarian & Role Real-Time
function filterKaryawan() {
    const searchVal = document.getElementById('karyawan-search-input').value.toLowerCase().trim();
    const roleVal = document.getElementById('karyawan-role-filter').value.toLowerCase();
    
    const items = document.querySelectorAll('.karyawan-item');
    let visibleCount = 0;

    items.forEach(item => {
        const nama = item.getAttribute('data-nama') || '';
        const uname = item.getAttribute('data-uname') || '';
        const email = item.getAttribute('data-email') || '';
        const role = item.getAttribute('data-role') || '';

        const matchSearch = (nama.includes(searchVal) || uname.includes(searchVal) || email.includes(searchVal));
        const matchRole = (roleVal === 'all' || role === roleVal);

        if (matchSearch && matchRole) {
            item.style.display = '';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    const counterElem = document.getElementById('karyawan-counter');
    if (counterElem) {
        counterElem.innerText = visibleCount + ' Karyawan';
    }

    const emptyMsg = document.getElementById('empty-search-message');
    if (emptyMsg) {
        if (visibleCount === 0 && items.length > 0) {
            emptyMsg.classList.remove('hidden');
        } else {
            emptyMsg.classList.add('hidden');
        }
    }
}

// Modal Handlers
function bukaModalTambah() {
    document.getElementById('modal-tambah').classList.remove('hidden');
}
function tutupModalTambah() {
    document.getElementById('modal-tambah').classList.add('hidden');
}

function bukaModalEdit(pegawai) {
    document.getElementById('edit-id-pegawai').value = pegawai.id_pegawai || '';
    document.getElementById('edit-nama').value = pegawai.nama || '';
    document.getElementById('edit-uname').value = pegawai.uname || '';
    document.getElementById('edit-email').value = pegawai.email || '';
    document.getElementById('edit-role').value = pegawai.role || 'karyawan';
    document.getElementById('edit-password').value = '';
    document.getElementById('modal-edit').classList.remove('hidden');
}
function tutupModalEdit() {
    document.getElementById('modal-edit').classList.add('hidden');
}

function bukaModalHapus(id, nama) {
    document.getElementById('hapus-id-pegawai').value = id;
    document.getElementById('hapus-nama-display').innerText = '"' + nama + '"';
    document.getElementById('modal-hapus').classList.remove('hidden');
}
function tutupModalHapus() {
    document.getElementById('modal-hapus').classList.add('hidden');
}

// Tutup dengan Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        tutupModalTambah();
        tutupModalEdit();
        tutupModalHapus();
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
