<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Form Kegiatan Siswa<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-xl mx-auto px-6 py-8 bg-white rounded-2xl border border-gray-300 shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6">
        <?= isset($activity) ? 'Form Edit Kegiatan' : 'Form Catat Kegiatan' ?>
    </h2>

    <?php if (session('errors')): ?>
        <div class="px-4 py-3 mb-4 text-red-800 bg-red-100 border border-red-400 rounded-lg">
            <strong class="font-bold">Terdapat kesalahan:</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= isset($activity) ? site_url('admin/kegiatan/' . $activity['id']) : site_url('admin/kegiatan') ?>" method="post">
        <?= csrf_field() ?>
        <?php if (isset($activity)) : ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>

        <?php if (session()->get('role') === 'Admin'): ?>
        <div class="mb-4">
            <label for="class_filter" class="block mb-2 text-sm font-medium text-gray-700">Filter Siswa per Kelas</label>
            <select id="class_filter" class="block w-full mt-1 text-sm rounded-lg border-gray-300">
                <option value="">-- Tampilkan Semua Siswa Aktif --</option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?= $class['id'] ?>"><?= esc($class['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <div class="mb-4">
            <label for="student_id" class="block mb-2 text-sm font-medium text-gray-700">Siswa</label>
            <select name="student_id" id="student_id" class="block w-full mt-1 text-sm rounded-lg border-gray-300" required>
                <option value="">-- Pilih Siswa --</option>
                <?php $selectedStudent = old('student_id', $activity['student_id'] ?? ''); ?>
                <?php foreach ($students as $student) : ?>
                    <option value="<?= $student['id'] ?>" data-class-id="<?= $student['class_id'] ?? '' ?>" <?= $selectedStudent == $student['id'] ? 'selected' : '' ?>><?= esc($student['full_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-4">
            <label for="activity_name_id" class="block mb-2 text-sm font-medium text-gray-700">Jenis Kegiatan</label>
            <select name="activity_name_id" class="block w-full mt-1 text-sm rounded-lg border-gray-300" required>
                <option value="">-- Pilih Kegiatan --</option>
                <?php $selectedActivity = old('activity_name_id', $activity['activity_name_id'] ?? ''); ?>
                <?php foreach ($activity_names as $item) : ?>
                    <option value="<?= $item['id'] ?>" <?= $selectedActivity == $item['id'] ? 'selected' : '' ?>><?= esc($item['name']) ?> (<?= esc($item['type']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label for="activity_date" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Kegiatan</label>
            <input type="date" name="activity_date" value="<?= old('activity_date', $activity['activity_date'] ?? date('Y-m-d')) ?>" class="block w-full mt-1 text-sm rounded-lg border-gray-300" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Deskripsi / Catatan</label>
            <textarea name="description" rows="4" class="block w-full mt-1 text-sm rounded-lg border-gray-300"><?= old('description', $activity['description'] ?? '') ?></textarea>
        </div>

        <div class="flex justify-end space-x-2 mt-6 border-t pt-4">
            <a href="<?= site_url('admin/kegiatan') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-lg hover:bg-sky-700"><?= isset($activity) ? 'Simpan Perubahan' : 'Simpan' ?></button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Fungsi ini hanya relevan untuk Admin
    <?php if (session()->get('role') === 'Admin'): ?>
        const classFilter = $('#class_filter');
        const studentSelect = $('#student_id');
        // Simpan semua opsi siswa dalam bentuk HTML mentah saat pertama kali halaman dimuat
        const originalStudentOptions = studentSelect.html();

        classFilter.on('change', function() {
            const selectedClassId = $(this).val();
            // Kembalikan ke daftar siswa lengkap
            studentSelect.html(originalStudentOptions);
            studentSelect.val(''); // Reset pilihan siswa

            if (selectedClassId) {
                // Sembunyikan opsi yang tidak cocok, kecuali opsi default
                studentSelect.find('option').each(function() {
                    const option = $(this);
                    if (option.val() && option.data('class-id') != selectedClassId) {
                        option.hide();
                    } else {
                        option.show();
                    }
                });
            }
        });
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>