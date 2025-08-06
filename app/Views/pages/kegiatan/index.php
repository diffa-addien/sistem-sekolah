<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Manajemen Kegiatan Siswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 sm:px-8 py-8">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Manajemen Kegiatan Siswa</h1>
            <p class="mt-1 text-gray-500 dark:text-gray-400">Daftar semua catatan kegiatan yang tersimpan.</p>
        </div>
        <a href="<?= site_url('admin/kegiatan/new') ?>"
            class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                    clip-rule="evenodd" />
            </svg>
            Catat Kegiatan Baru
        </a>
    </div>

    <div class="rounded-lg overflow-hidden">
        <div class="hidden md:block">
            <table id="kegiatanTable" class="min-w-full leading-normal">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Siswa</th>
                        <th class="px-4 py-3">Kegiatan</th>
                        <th class="px-4 py-3">Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    <?php foreach ($activities as $item): ?>
                        <tr class="text-gray-700">
                            <td class="px-4 py-3 text-sm"><?= date('d M Y', strtotime($item['activity_date'])) ?></td>
                            <td class="px-4 py-3 text-sm font-semibold">
                                <a href="<?= site_url('admin/laporan/siswa/' . $item['student_id']) ?>"
                                    class="hover:underline text-sky-600">
                                    <?= esc($item['full_name']) ?>
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm"><?= esc($item['activity_name']) ?></td>
                            <td class="px-4 py-3 text-sm max-w-xs truncate" title="<?= esc($item['description']) ?>">
                                <?= esc($item['description']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="block md:hidden">
            <div class="px-4 py-4 space-y-4">
                <?php foreach ($activities as $item): ?>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md border dark:border-gray-700">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-lg text-gray-900 dark:text-gray-100"><?= esc($item['full_name']) ?>
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><?= esc($item['activity_name']) ?></p>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0 ml-2">
                                <?= date('d M Y', strtotime($item['activity_date'])) ?>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-gray-700 dark:text-gray-300">
                            <?= esc($item['description']) ?>
                        </p>
                        <div class="flex justify-end items-center mt-4 pt-4 border-t dark:border-gray-700 space-x-3">
                            <a href="<?= site_url('admin/kegiatan/' . $item['id'] . '/edit') ?>"
                                class="flex items-center px-3 py-1 text-sm font-medium text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-gray-700 rounded-full hover:bg-blue-200 dark:hover:bg-gray-600 focus:outline-none">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z">
                                    </path>
                                </svg>
                                Edit
                            </a>
                            <form action="<?= site_url('admin/kegiatan/' . $item['id']) ?>" method="post"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit"
                                    class="flex items-center px-3 py-1 text-sm font-medium text-red-600 dark:text-red-400 bg-red-100 dark:bg-gray-700 rounded-full hover:bg-red-200 dark:hover:bg-gray-600 focus:outline-none"
                                    aria-label="Delete">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Hanya inisialisasi DataTable pada layar desktop
    // DataTable tidak ideal untuk layout card di mobile
    $(document).ready(function () {
        if ($(window).width() > 768) { // 768px adalah breakpoint 'md' di Tailwind
            $('#kegiatanTable').DataTable({
                "order": [[0, "desc"]],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>