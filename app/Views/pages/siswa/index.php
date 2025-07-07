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

<div class="w-full overflow-hidden rounded-lg shadow-xs">
    <div class="w-full overflow-x-auto">
        <table id="siswaTable" class="w-full whitespace-no-wrap">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Foto</th>
                    <th class="px-4 py-3">NIS</th>
                    <th class="px-4 py-3">Nama Siswa</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Wali Murid</th>
                    <th class="px-4 py-3 sticky right-0 bg-white z-10">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                <?php foreach ($students as $key => $student): ?>
                    <tr class="text-gray-700">
                        <td class="px-4 py-3 text-sm"><?= $key + 1 ?></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div class="relative hidden w-12 h-12 mr-3 rounded-full md:block">

                                    <div class="relative w-full h-full">
                                        <img class="object-cover w-full h-full rounded-full shadow-md"
                                            src="<?= base_url('uploads/photos/' . $student['photo']) ?>" alt="Foto Profil"
                                            loading="lazy"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div
                                            class="hidden absolute inset-0 flex items-center justify-center rounded-full bg-sky-900 text-white text-sm font-semibold">
                                            <?= esc(strtoupper(substr($student['full_name'], 0, 1))) ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm"><?= esc($student['nis']) ?></td>
                        <td class="px-4 py-3 text-sm font-bold"><?= esc($student['full_name']) ?></td>
                        <td class="px-4 py-3 text-sm">
                            <?= "<b>" . esc($student['class_name']) . "</b> (" . esc($student['tahun_kelas']) . ")" ?>
                        </td>
                        <td class="px-4 py-3 text-sm"><?= esc($student['parent_name'] ?? 'Belum Ditautkan') ?></td>
                        <td class="px-4 py-3 text-sm sticky right-0 bg-white z-10">
                            <div class="flex items-center space-x-4">
                                <a href="<?= site_url('admin/siswa/' . $student['id'] . '/edit') ?>"
                                    class="w-8 h-8 inline-flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-800 text-blue-600 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-700"
                                    aria-label="Edit">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
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
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-full bg-red-100 dark:bg-red-800 text-red-600 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-700"
                                        aria-label="Delete">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
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
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        $('#siswaTable').DataTable({
            scrollX: true,
            autoWidth: false,
            // fixedHeader: true,
            deferRender: true,
            initComplete: function () {
                this.api().columns.adjust();
                setTimeout(() => this.api().columns.adjust().draw(), 300);
            }
        });
    });
</script>
<?= $this->endSection() ?>