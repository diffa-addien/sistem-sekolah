<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Manajemen Kegiatan Siswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-semibold text-gray-700">Catatan Kegiatan Siswa</h2>
    <a href="<?= site_url('admin/kegiatan/new') ?>" class="btn-primary">Catat Kegiatan Baru</a>
</div>

<div class="w-full overflow-hidden rounded-lg shadow-xs">
    <div class="w-full overflow-x-auto">
        <table id="kegiatanTable" class="w-full whitespace-no-wrap">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Siswa</th>
                    <th class="px-4 py-3">Kegiatan</th>
                    <th class="px-4 py-3">Deskripsi</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                <?php foreach ($activities as $item) : ?>
                    <tr class="text-gray-700">
                        <td class="px-4 py-3 text-sm"><?= date('d M Y', strtotime($item['activity_date'])) ?></td>
                        <td class="px-4 py-3 text-sm font-semibold"><?= esc($item['full_name']) ?></td>
                        <td class="px-4 py-3 text-sm"><?= esc($item['activity_name']) ?></td>
                        <td class="px-4 py-3 text-sm max-w-xs truncate"><?= esc($item['description']) ?></td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center space-x-4">
                                <a href="<?= site_url('admin/kegiatan/' . $item['id'] . '/edit') ?>" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </a>
                                <form action="<?= site_url('admin/kegiatan/' . $item['id']) ?>" method="post" class="inline" onsubmit="return confirm('Apakah Anda yakin?');">
                                    <?= csrf_field() ?><input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"></path></svg>
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
<script> $(document).ready(function() { $('#kegiatanTable').DataTable({"order": [[0, "desc"]]}); }); </script>
<?= $this->endSection() ?>