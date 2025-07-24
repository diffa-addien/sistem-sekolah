<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Form Tahun Ajaran<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-xl mx-auto px-6 py-8 bg-white rounded-2xl border border-gray-300 shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6">
        <?= isset($academicYear) ? 'Form Edit Tahun Ajaran' : 'Form Tambah Tahun Ajaran' ?>
    </h2>

    <?php if (session('errors')) : ?>
        <div class="px-4 py-3 mb-4 text-red-800 bg-red-100 border border-red-400 rounded-lg">
            <strong class="font-bold">Terdapat kesalahan:</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= isset($academicYear) ? site_url('admin/tahun-ajaran/' . $academicYear['id']) : site_url('admin/tahun-ajaran') ?>" method="post">
        <?= csrf_field() ?>
        <?php if (isset($academicYear)) : ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>

        <div class="mb-4">
            <label for="year" class="block mb-2 text-sm font-medium text-gray-900">Tahun Ajaran</label>
            <input type="text" name="year" value="<?= old('year', $academicYear['year'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Contoh: 2026/2027" required>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Mulai</label>
                <input type="date" name="start_date" value="<?= old('start_date', $academicYear['start_date'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
            </div>
            <div>
                <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Selesai</label>
                <input type="date" name="end_date" value="<?= old('end_date', $academicYear['end_date'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
            </div>
        </div>

        <div class="mb-6">
            <label class="block mb-2 text-sm font-medium text-gray-900">Status</label>
            <?php $statusValue = old('status', $academicYear['status'] ?? 'Tidak Aktif'); ?>
            <div class="flex items-center space-x-6">
                <div class="flex items-center"><input type="radio" value="Aktif" name="status" class="w-4 h-4 text-sky-600" <?= $statusValue == 'Aktif' ? 'checked' : '' ?>><label class="ml-2 text-sm">Aktif</label></div>
                <div class="flex items-center"><input type="radio" value="Tidak Aktif" name="status" class="w-4 h-4 text-sky-600" <?= $statusValue == 'Tidak Aktif' ? 'checked' : '' ?>><label class="ml-2 text-sm">Tidak Aktif</label></div>
            </div>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="<?= site_url('admin/tahun-ajaran') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-lg hover:bg-sky-700">Simpan</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>