<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Form Tahun Ajaran
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-xl mx-auto px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">
        <?= isset($academicYear) ? 'Form Edit Tahun Ajaran' : 'Form Tambah Tahun Ajaran' ?>
    </h2>

    <?php if (session('errors')) : ?>
        <?= validation_list_errors('my_list') ?>
    <?php endif; ?>

    <form action="<?= isset($academicYear) ? site_url('admin/tahun-ajaran/' . $academicYear['id']) : site_url('admin/tahun-ajaran') ?>" method="post">
        <?= csrf_field() ?>

        <?php if (isset($academicYear)) : ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="mb-6">
            <label for="year" class="block mb-2 text-sm font-medium text-gray-900">Tahun Ajaran</label>
            <input type="text" id="year" name="year" value="<?= old('year', $academicYear['year'] ?? '') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5" placeholder="Contoh: 2026/2027" required>
        </div>

        <div class="mb-6">
            <label class="block mb-2 text-sm font-medium text-gray-900">Status</label>
            <div class="flex items-center space-x-6">
                <?php
                $statusValue = old('status', $academicYear['status'] ?? 'Tidak Aktif');
                ?>
                <div class="flex items-center">
                    <input id="status-aktif" type="radio" value="Aktif" name="status" class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500" <?= $statusValue == 'Aktif' ? 'checked' : '' ?>>
                    <label for="status-aktif" class="ms-2 text-sm font-medium text-gray-900">Aktif</label>
                </div>
                <div class="flex items-center">
                    <input id="status-tidak-aktif" type="radio" value="Tidak Aktif" name="status" class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500" <?= $statusValue == 'Tidak Aktif' ? 'checked' : '' ?>>
                    <label for="status-tidak-aktif" class="ms-2 text-sm font-medium text-gray-900">Tidak Aktif</label>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="<?= site_url('admin/tahun-ajaran') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 focus:outline-none focus:shadow-outline-gray">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>