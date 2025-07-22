<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Manajemen Siswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-semibold text-gray-700">Manajemen Siswa</h2>
    <a href="<?= site_url('admin/siswa/new') ?>"
        class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        Tambah Siswa
    </a>
</div>

<div class="mb-4 p-4 bg-white rounded-lg shadow-xs">
    <form action="<?= site_url('admin/siswa') ?>" method="get">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div class="md:col-span-1">
                <label for="status" class="block text-sm font-medium text-gray-700">Filter Status</label>
                <select name="status" id="status"
                    class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300">

                    <option value="aktif" <?= ($selected_status == 'aktif') ? 'selected' : '' ?>>Aktif di T/A Sekarang
                    </option>
                    <!-- <option value="riwayat" <?= ($selected_status == 'riwayat') ? 'selected' : '' ?>>Memiliki Riwayat
                        (Lulus, Naik, dll)</option> -->
                    <option value="belum_terdaftar" <?= ($selected_status == 'belum_terdaftar') ? 'selected' : '' ?>>Belum
                        Terdaftar di T/A Aktif</option>
                    <option value="">Semua Siswa (Pernah Terdaftar)</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700">Cari Siswa</label>
                <div class="flex space-x-2 mt-1">
                    <input type="text" name="search" id="search" placeholder="Masukkan Nama atau NIS..."
                        value="<?= esc($search_keyword ?? '') ?>"
                        class="block w-full text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300">
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700">Cari</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="w-full overflow-hidden rounded-lg shadow-xs">
    <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Foto</th>
                    <th class="px-4 py-3">NIS</th>
                    <th class="px-4 py-3">Nama Siswa</th>
                    <th class="px-4 py-3">Kelas Saat Ini</th>
                    <th class="px-4 py-3 text-center">RFID</th>
                    <th class="px-4 py-3">Wali Murid</th>
                    <th class="px-4 py-3 sticky right-0 bg-gray-50 z-10">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                <?php foreach ($students as $key => $student): ?>
                    <tr class="text-gray-700">
                        <td class="px-4 py-3 text-sm">
                            <?= $pager->getDetails('students')['currentPage'] > 1 ? ($pager->getDetails('students')['perPage'] * ($pager->getDetails('students')['currentPage'] - 1)) + $key + 1 : $key + 1 ?>
                        </td>
                        <td class="px-4 py-3">
                            <div class="relative w-12 h-12">
                                <img class="object-cover w-full h-full rounded-full shadow-md"
                                    src="<?= base_url('uploads/photos/' . ($student['photo'] ?? 'default.png')) ?>"
                                    alt="Foto Profil" loading="lazy"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div
                                    class="hidden absolute inset-0 flex items-center justify-center rounded-full bg-sky-900 text-white text-sm font-semibold">
                                    <?= esc(strtoupper(substr($student['full_name'], 0, 1))) ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm"><?= esc($student['nis']) ?></td>
                        <td class="px-4 py-3 text-sm font-bold"><?= esc($student['full_name']) ?></td>
                        <td class="px-4 py-3 text-sm">
                            <b><?= esc($student['class_name'] ?? 'N/A') ?></b>
                            <p class="text-xs text-gray-500"><?= esc($student['tahun_kelas'] ?? 'Belum Terdaftar') ?></p>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <?php if (!empty($student['card_uid'])): ?>
                                <span class="inline-flex items-center p-1 text-white bg-green-500 rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center p-1 text-white bg-red-400 rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-sm"><?= esc($student['parent_name'] ?? 'Belum Ditautkan') ?></td>
                        <td class="px-4 py-3 text-sm sticky right-0 bg-white z-10">
                            <div class="flex items-center space-x-2">
                                <a href="<?= site_url('admin/siswa/' . $student['id'] . '/edit') ?>"
                                    class="w-8 h-8 inline-flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200"
                                    aria-label="Edit">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                        </path>
                                    </svg>
                                </a>
                                <form action="<?= site_url('admin/siswa/' . $student['id']) ?>" method="post" class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200"
                                        aria-label="Delete">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <?= $pager->links('students', 'tailwind') ?>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // DataTables dinonaktifkan untuk menggunakan paginasi dari server
</script>
<?= $this->endSection() ?>