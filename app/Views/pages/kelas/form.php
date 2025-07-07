<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Form Kelas<?= $this->endSection() ?>

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
        <?php if (isset($classData)) : ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>

        <div class="mb-4">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Kelas</label>
            <input type="text" name="name" value="<?= old('name', $classData['name'] ?? '') ?>" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300" required>
        </div>
        
        <div class="mb-4">
            <label for="academic_year_id" class="block mb-2 text-sm font-medium text-gray-900">Tahun Ajaran</label>
            <select name="academic_year_id" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300" required>
                <option value="">-- Pilih Tahun Ajaran --</option>
                <?php $selectedYear = old('academic_year_id', $classData['academic_year_id'] ?? ''); ?>
                <?php foreach ($academicYears as $year) : ?>
                    <option value="<?= $year['id'] ?>" <?= $selectedYear == $year['id'] ? 'selected' : '' ?>><?= esc($year['year']) ?> (<?= esc($year['status']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-6">
            <label for="teacher_id" class="block mb-2 text-sm font-medium text-gray-900">Wali Kelas (Opsional)</label>
            <select name="teacher_id" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300">
                <option value="">-- Tidak Ada Wali Kelas --</option>
                <?php $selectedTeacher = old('teacher_id', $classData['teacher_id'] ?? ''); ?>
                <?php foreach ($teachers as $teacher) : ?>
                    <option value="<?= $teacher['id'] ?>" <?= $selectedTeacher == $teacher['id'] ? 'selected' : '' ?>><?= esc($teacher['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex justify-end space-x-2 mt-6 border-t pt-4">
            <a href="<?= site_url('admin/kelas') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700"><?= isset($classData) ? 'Simpan Perubahan' : 'Simpan' ?></button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>