<?php
$page_title = 'Kelola Karyawan - KickDropShoe';
$current_page = 'karyawan';

require_once __DIR__ . '/../layouts/header.php';

$dataPegawai = $dataPegawai ?? [];
$totalPegawai = $totalPegawai ?? count($dataPegawai);
$totalSuperadmin = $totalSuperadmin ?? count(array_filter($dataPegawai, fn($p) => ($p['role'] ?? '') === 'superadmin'));
$currentUserId = (int) ($_SESSION['id_pegawai'] ?? 0);
$oldInput = $oldInput ?? [];

$namaLama = htmlspecialchars($oldInput['nama'] ?? '', ENT_QUOTES, 'UTF-8');
$unameLama = htmlspecialchars($oldInput['uname'] ?? '', ENT_QUOTES, 'UTF-8');
$emailLama = htmlspecialchars($oldInput['email'] ?? '', ENT_QUOTES, 'UTF-8');
$roleLama = $oldInput['role'] ?? 'karyawan';
$csrfToken = htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8');
?>

<div class="flex h-screen overflow-hidden bg-brand-slateBg">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <?php
        $header_title = 'Kelola Karyawan';
        require_once __DIR__ . '/../layouts/topbar.php';
        ?>

        <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6 pb-36 lg:pb-8">

            <!-- RINGKASAN METRIK -->
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

            <!-- KONTROL BAR -->
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

            <!-- DAFTAR KARYAWAN -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 tracking-tight">Daftar Karyawan</h3>
                    <span id="karyawan-counter" class="text-xs font-bold text-slate-400"><?= count($dataPegawai) ?> Karyawan</span>
                </div>

                <!-- DESKTOP TABLE -->
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
                                    $nama = htmlspecialchars($p['nama'] ?? '-', ENT_QUOTES, 'UTF-8');
                                    $uname = htmlspecialchars($p['uname'] ?? '-', ENT_QUOTES, 'UTF-8');
                                    $email = htmlspecialchars($p['email'] ?? '-', ENT_QUOTES, 'UTF-8');
                                    $role = htmlspecialchars($p['role'] ?? 'karyawan', ENT_QUOTES, 'UTF-8');
                                    $id = (int) ($p['id_pegawai'] ?? 0);
                                    $isMe = ($id === $currentUserId);
                                    ?>

                                    <tr class="karyawan-item hover:bg-slate-50/60 transition" data-nama="<?= strtolower($nama) ?>" data-uname="<?= strtolower($uname) ?>" data-email="<?= strtolower($email) ?>" data-role="<?= strtolower($role) ?>">
                                        <td class="py-4 px-6 truncate">
                                            <div class="flex items-center gap-2.5">
                                                <span class="font-extrabold text-slate-900 text-sm truncate"><?= $nama ?></span>
                                                <?php if ($isMe): ?>
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 shrink-0">Anda</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>

                                        <td class="py-4 px-6 font-semibold text-slate-600 truncate"><?= $uname ?></td>

                                        <td class="py-4 px-6 font-medium text-slate-500 truncate">
                                            <?= $email !== '-' ? $email : '<span class="text-slate-300">—</span>' ?>
                                        </td>

                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <?php if ($role === 'superadmin'): ?>
                                                <span class="inline-block px-2.5 py-1 bg-slate-900 text-brand-yellow rounded-md text-[11px] font-extrabold">Super Admin</span>
                                            <?php else: ?>
                                                <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-[11px] font-extrabold">Karyawan</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="py-4 px-6 text-right whitespace-nowrap">
                                            <div class="inline-flex items-center justify-end gap-2 w-full">
                                                <button type="button" onclick='bukaModalEdit(<?= json_encode($p, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' class="w-16 py-1.5 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition">Edit</button>

                                                <?php if (!$isMe): ?>
                                                    <button type="button" onclick='bukaModalHapus(<?= $id ?>, <?= json_encode($p['nama'] ?? '-', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' class="w-16 py-1.5 text-center bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs rounded-lg transition">Hapus</button>
                                                <?php else: ?>
                                                    <span class="w-16 py-1.5 text-center text-xs font-semibold text-slate-400 bg-slate-50 border border-slate-100 rounded-lg inline-block select-none">Aktif</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="no-karyawan-row">
                                    <td colspan="5" class="py-12 text-center text-slate-400 font-medium">Belum ada data karyawan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE CARD LIST -->
                <div class="md:hidden divide-y divide-slate-100" id="karyawan-card-list">
                    <?php foreach ($dataPegawai as $p): ?>
                        <?php
                        $nama = htmlspecialchars($p['nama'] ?? '-', ENT_QUOTES, 'UTF-8');
                        $uname = htmlspecialchars($p['uname'] ?? '-', ENT_QUOTES, 'UTF-8');
                        $email = htmlspecialchars($p['email'] ?? '-', ENT_QUOTES, 'UTF-8');
                        $role = htmlspecialchars($p['role'] ?? 'karyawan', ENT_QUOTES, 'UTF-8');
                        $id = (int) ($p['id_pegawai'] ?? 0);
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

                            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-50">
                                <button type="button" onclick='bukaModalEdit(<?= json_encode($p, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' class="w-20 py-1.5 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition">Edit</button>

                                <?php if (!$isMe): ?>
                                    <button type="button" onclick='bukaModalHapus(<?= $id ?>, <?= json_encode($p['nama'] ?? '-', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' class="w-16 py-1.5 text-center bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs rounded-lg transition">Hapus</button>
                                <?php else: ?>
                                    <span class="w-20 py-1.5 text-center text-xs font-semibold text-slate-400 bg-slate-50 border border-slate-100 rounded-lg inline-block select-none">Aktif</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div id="empty-search-message" class="hidden py-12 text-center text-slate-400 font-medium text-xs">Tidak ditemukan karyawan yang sesuai.</div>


                <?php if ($totalData > 0): ?>
                    <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-xs font-semibold text-slate-400">
                            Menampilkan
                            <span class="text-slate-700"><?= $offset + 1 ?></span>
                            -
                            <span class="text-slate-700"><?= $offset + count($dataPegawai) ?></span>
                            dari
                            <span class="text-slate-700"><?= $totalData ?></span>
                            karyawan
                        </p>

                        <div class="flex items-center gap-1.5 shrink-0">
                            <?php if ($halamanAktif > 1): ?>
                                <a href="?halaman=<?= $halamanAktif - 1 ?>"
                                    class="px-3 py-2 rounded-lg border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition whitespace-nowrap shrink-0">
                                    <i class="fas fa-chevron-left text-[10px] mr-1"></i>
                                    Sebelumnya
                                </a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>
                                <a href="?halaman=<?= $i ?>"
                                    class="w-9 h-9 shrink-0 flex items-center justify-center rounded-lg text-xs font-bold transition
            <?= $i === $halamanAktif
                                    ? 'bg-slate-900 text-brand-yellow'
                                    : 'border border-slate-200 text-slate-600 hover:bg-slate-50' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($halamanAktif < $totalHalaman): ?>
                                <a href="?halaman=<?= $halamanAktif + 1 ?>"
                                    class="px-3 py-2 rounded-lg border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition whitespace-nowrap shrink-0">
                                    Selanjutnya
                                    <i class="fas fa-chevron-right text-[10px] ml-1"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </main>
</div>

<!-- MODAL TAMBAH KARYAWAN -->
<div id="modal-tambah" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl p-6 sm:p-7 max-w-md w-full shadow-xl space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Tambah Karyawan</h3>
            <button type="button" onclick="tutupModalTambah()" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <form method="POST" action="/karyawan/simpan" class="space-y-3.5">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama" required value="<?= $namaLama ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400" placeholder="Masukkan nama lengkap">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Username</label>
                    <input type="text" name="uname" required value="<?= $unameLama ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400" placeholder="Username">
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Peran</label>
                    <select name="role" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-slate-400 cursor-pointer">
                        <option value="karyawan" <?= $roleLama === 'karyawan' ? 'selected' : '' ?>>Karyawan</option>
                        <option value="superadmin" <?= $roleLama === 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Email</label>
                <input type="email" name="email" value="<?= $emailLama ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400" placeholder="nama@email.com">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Password</label>
                    <input type="password" name="password" required minlength="8" autocomplete="new-password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400" placeholder="Min. 8 karakter">
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Ulangi Password</label>
                    <input type="password" name="password_konfirmasi" required minlength="8" autocomplete="new-password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400" placeholder="Konfirmasi">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="tutupModalTambah()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT KARYAWAN -->
<div id="modal-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl p-6 sm:p-7 max-w-md w-full shadow-xl space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Edit Karyawan</h3>
            <button type="button" onclick="tutupModalEdit()" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <form method="POST" action="/karyawan/update" class="space-y-3.5">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
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
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Password Baru <span class="text-slate-400 font-normal lowercase">(kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" id="edit-password" minlength="8" autocomplete="new-password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400" placeholder="Masukkan password baru jika ingin diganti">
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Konfirmasi Password Baru <span class="text-slate-400 font-normal lowercase">(kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password_konfirmasi" id="edit-password-konfirmasi" minlength="8" autocomplete="new-password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400" placeholder="Konfirmasi password baru">
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="tutupModalEdit()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl transition shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- FORM HAPUS GLOBAL -->
<form id="form-hapus-karyawan" method="POST" action="/karyawan/hapus" class="hidden">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
    <input type="hidden" name="id_pegawai" id="hapus-id-pegawai">
</form>

<!-- NOTIFIKASI SWEETALERT -->
<?php
$pesanSuccess = $_SESSION['success'] ?? null;
$pesanError = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>

<script>
    const pesanSuccess = <?= json_encode($pesanSuccess, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const pesanError = <?= json_encode($pesanError, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const bukaModalTambahOtomatis = <?= !empty($bukaModalTambah) ? 'true' : 'false' ?>;

    document.addEventListener('DOMContentLoaded', async function() {
        if (pesanSuccess) {
            await Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: pesanSuccess,
                confirmButtonText: 'OK',
                confirmButtonColor: '#0B1325',
                timer: 3000,
                timerProgressBar: true
            });
        }

        if (pesanError) {
            await Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: pesanError,
                confirmButtonText: 'OK',
                confirmButtonColor: '#0B1325',
                timer: 3000,
                timerProgressBar: true
            });
        }

        if (bukaModalTambahOtomatis) {
            bukaModalTambah();
        }
    });
</script>

<!-- JAVASCRIPT -->
<script>
    // FILTER PENCARIAN DAN ROLE
    function filterKaryawan() {
        const searchInput = document.getElementById('karyawan-search-input');
        const roleFilter = document.getElementById('karyawan-role-filter');
        const searchVal = searchInput.value.toLowerCase().trim();
        const roleVal = roleFilter.value.toLowerCase();
        const items = document.querySelectorAll('.karyawan-item');

        let visibleCount = 0;

        items.forEach(item => {
            const nama = item.getAttribute('data-nama') || '';
            const uname = item.getAttribute('data-uname') || '';
            const email = item.getAttribute('data-email') || '';
            const role = item.getAttribute('data-role') || '';

            const matchSearch = nama.includes(searchVal) || uname.includes(searchVal) || email.includes(searchVal);
            const matchRole = roleVal === 'all' || role === roleVal;

            if (matchSearch && matchRole) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        const counterElem = document.getElementById('karyawan-counter');
        if (counterElem) counterElem.innerText = visibleCount + ' Karyawan';

        const emptyMsg = document.getElementById('empty-search-message');
        if (emptyMsg) {
            if (visibleCount === 0 && items.length > 0) {
                emptyMsg.classList.remove('hidden');
            } else {
                emptyMsg.classList.add('hidden');
            }
        }
    }

    // MODAL TAMBAH
    function bukaModalTambah() {
        const modal = document.getElementById('modal-tambah');
        if (modal) modal.classList.remove('hidden');
    }

    function tutupModalTambah() {
        const modal = document.getElementById('modal-tambah');
        if (modal) modal.classList.add('hidden');
    }

    // MODAL EDIT
    function bukaModalEdit(pegawai) {
        document.getElementById('edit-id-pegawai').value = pegawai.id_pegawai || '';
        document.getElementById('edit-nama').value = pegawai.nama || '';
        document.getElementById('edit-uname').value = pegawai.uname || '';
        document.getElementById('edit-email').value = pegawai.email || '';
        document.getElementById('edit-role').value = pegawai.role || 'karyawan';
        document.getElementById('edit-password').value = '';
        document.getElementById('edit-password-konfirmasi').value = '';
        document.getElementById('modal-edit').classList.remove('hidden');
    }

    function tutupModalEdit() {
        document.getElementById('modal-edit').classList.add('hidden');
    }

    // MODAL HAPUS
    function bukaModalHapus(id, nama) {
        Swal.fire({
            title: 'Hapus Karyawan?',
            text: `Yakin ingin menghapus akun ${nama}? Data yang dihapus tidak dapat dikembalikan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapus-id-pegawai').value = id;
                document.getElementById('form-hapus-karyawan').submit();
            }
        });
    }

    // TUTUP MODAL DENGAN ESCAPE
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            tutupModalTambah();
            tutupModalEdit();
        }
    });

    // TUTUP MODAL JIKA KLIK AREA LUAR
    document.addEventListener('click', function(e) {
        const modalTambah = document.getElementById('modal-tambah');
        const modalEdit = document.getElementById('modal-edit');

        if (e.target === modalTambah) tutupModalTambah();
        if (e.target === modalEdit) tutupModalEdit();
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>