<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Manajemen Nama Kegiatan<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-semibold text-gray-700">Manajemen Nama Kegiatan</h2>
    <a href="<?= site_url('admin/nama-kegiatan/new') ?>" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Tambah Nama Kegiatan</a>
</div>
<div class="w-full overflow-hidden rounded-lg shadow-xs">
    <div class="w-full overflow-x-auto">
        <table id="myTable" class="w-full whitespace-no-wrap">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                    <th class="px-4 py-3">Nama Kegiatan</th>
                    <th class="px-4 py-3">Tipe</th>
                    <th class="px-4 py-3">Jadwal</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                <?php foreach ($activityNames as $item) : ?>
                    <tr class="text-gray-700">
                        <td class="px-4 py-3 text-sm font-semibold"><?= esc($item['name']) ?></td>
                        <td class="px-4 py-3 text-sm"><?= esc($item['type']) ?></td>
                        <td class="px-4 py-3 text-sm">
                            <?= $item['start_time'] ? date('H:i', strtotime($item['start_time'])) . ' - ' . date('H:i', strtotime($item['end_time'])) : '-' ?>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center space-x-4">
                                <a href="<?= site_url('admin/nama-kegiatan/' . $item['id'] . '/edit') ?>" aria-label="Edit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg focus:outline-none focus:shadow-outline-gray"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg></a>
                                <form action="<?= site_url('admin/nama-kegiatan/' . $item['id']) ?>" method="post" class="inline" onsubmit="return confirm('Apakah Anda yakin?');">
                                    <?= csrf_field() ?><input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg focus:outline-none focus:shadow-outline-gray" aria-label="Delete"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"></path></svg></button>
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
<?= $this->section('scripts') ?><script>$(document).ready(function() {$('#myTable').DataTable();});</script><?= $this->endSection() ?>