<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Form Kelas
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-xl mx-auto px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">
        <?= isset($classData) ? 'Form Edit Kelas' : 'Form Tambah Kelas' ?>
    </h2>

    <?php if (session('errors')) : ?>
        <?= validation_list_errors('my_list') ?>
    <?php endif; ?>

    <form action="<?= isset($classData) ? site_url('admin/kelas/' . $classData['id']) : site_url('admin/kelas') ?>" method="post">
        <?= csrf_field() ?>
        
        <?php if (isset($classData)) : ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="mb-6">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Kelas</label>
            <input type="text" id="name" name="name" value="<?= old('name', $classData['name'] ?? '') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5" placeholder="Contoh: Kelas 1A" required>
        </div>
        
        <div class="mb-6">
            <label for="academic_year_id" class="block mb-2 text-sm font-medium text-gray-900">Tahun Ajaran</label>
            <select id="academic_year_id" name="academic_year_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5">
                <option value="">-- Pilih Tahun Ajaran --</option>
                <?php if (!empty($academicYears)) : ?>
                    <?php 
                    $selectedYear = old('academic_year_id', $classData['academic_year_id'] ?? '');
                    foreach ($academicYears as $year) : ?>
                        <option value="<?= $year['id'] ?>" <?= $selectedYear == $year['id'] ? 'selected' : '' ?>>
                            <?= esc($year['year']) ?> (<?= esc($year['status']) ?>)
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="<?= site_url('admin/kelas') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 focus:outline-none focus:shadow-outline-gray">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <?= isset($classData) ? 'Simpan Perubahan' : 'Simpan' ?>
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>  