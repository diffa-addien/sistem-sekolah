<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Form Kegiatan Siswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-xl mx-auto px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">
        <?= isset($activity) ? 'Form Edit Kegiatan' : 'Form Catat Kegiatan' ?>
    </h2>

    <?php if (session('errors')) : ?>
        <?= validation_list_errors('my_list') ?>
    <?php endif; ?>

    <form action="<?= isset($activity) ? site_url('admin/kegiatan/' . $activity['id']) : site_url('admin/kegiatan') ?>" method="post">
        <?= csrf_field() ?>
        <?php if (isset($activity)) : ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="mb-4">
            <label for="student_id" class="block mb-2 text-sm font-medium text-gray-700">Siswa</label>
            <select name="student_id" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300" required>
                <option value="">-- Pilih Siswa --</option>
                <?php $selectedStudent = old('student_id', $activity['student_id'] ?? ''); ?>
                <?php foreach ($students as $student) : ?>
                    <option value="<?= $student['id'] ?>" <?= $selectedStudent == $student['id'] ? 'selected' : '' ?>><?= esc($student['full_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label for="activity_name_id" class="block mb-2 text-sm font-medium text-gray-700">Jenis Kegiatan</label>
            <select name="activity_name_id" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300" required>
                <option value="">-- Pilih Kegiatan --</option>
                <?php $selectedActivity = old('activity_name_id', $activity['activity_name_id'] ?? ''); ?>
                <?php foreach ($activity_names as $item) : ?>
                    <option value="<?= $item['id'] ?>" <?= $selectedActivity == $item['id'] ? 'selected' : '' ?>><?= esc($item['name']) ?> (<?= esc($item['type']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label for="activity_date" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Kegiatan</label>
            <input type="date" name="activity_date" value="<?= old('activity_date', $activity['activity_date'] ?? date('Y-m-d')) ?>" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Deskripsi / Catatan</label>
            <textarea name="description" rows="4" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300"><?= old('description', $activity['description'] ?? '') ?></textarea>
        </div>

        <div class="flex justify-end space-x-2 mt-6 border-t pt-4">
            <a href="<?= site_url('admin/kegiatan') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700"><?= isset($activity) ? 'Simpan Perubahan' : 'Simpan' ?></button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>