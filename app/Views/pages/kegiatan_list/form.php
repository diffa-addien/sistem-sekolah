<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Form Nama Kegiatan<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-xl mx-auto px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">
        <?= isset($activityName) ? 'Form Edit Nama Kegiatan' : 'Form Tambah Nama Kegiatan' ?>
    </h2>

    <?php if (session('errors')): ?>
        <div class="px-4 py-3 mb-4 text-red-800 bg-red-200 border border-red-500 rounded-lg" role="alert">
            <strong class="font-bold">Terdapat kesalahan validasi:</strong>
            <ul class="mt-2 list-disc list-inside">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <form
        action="<?= isset($activityName) ? site_url('admin/nama-kegiatan/' . $activityName['id']) : site_url('admin/nama-kegiatan') ?>"
        method="post">
        <?= csrf_field() ?>
        <?php if (isset($activityName)): ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>

        <div class="mb-4">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nama Kegiatan</label>
            <input type="text" name="name" value="<?= old('name', $activityName['name'] ?? '') ?>"
                class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300"
                required>
        </div>
        <div class="mb-4">
            <label for="type" class="block mb-2 text-sm font-medium text-gray-700">Tipe</label>
            <?php $selectedType = old('type', $activityName['type'] ?? 'Sekolah'); ?>
            <select name="type" id="type_selector"
                class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300"
                required>
                <option value="Sekolah" <?= $selectedType == 'Sekolah' ? 'selected' : '' ?>>Sekolah (Terjadwal)</option>
                <option value="Rumah" <?= $selectedType == 'Rumah' ? 'selected' : '' ?>>Rumah (Tidak Terjadwal)</option>
                <option value="Masuk" <?= $selectedType == 'Masuk' ? 'selected' : '' ?>>Presensi Masuk</option>
                <option value="Pulang" <?= $selectedType == 'Pulang' ? 'selected' : '' ?>>Presensi Pulang</option>
            </select>
        </div>
        <div id="schedule-fields" class="hidden">
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="start_time" class="block mb-2 text-sm font-medium text-gray-700">Waktu Mulai</label>
                    <?php $startTime = old('start_time', $activityName['start_time'] ?? ''); ?>
                    <input type="time" name="start_time"
                        value="<?= !empty($startTime) ? substr($startTime, 0, 5) : '' ?>"
                        class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300">
                </div>
                <div class="mb-4">
                    <label for="end_time" class="block mb-2 text-sm font-medium text-gray-700">Waktu Selesai</label>
                    <?php $endTime = old('end_time', $activityName['end_time'] ?? ''); ?>
                    <input type="time" name="end_time" value="<?= !empty($endTime) ? substr($endTime, 0, 5) : '' ?>"
                        class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300">
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-2 mt-6 border-t pt-4">
            <a href="<?= site_url('admin/nama-kegiatan') ?>"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Batal</a>
            <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700"><?= isset($activityName) ? 'Simpan Perubahan' : 'Simpan' ?></button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        const typeSelector = $('#type_selector');
        const scheduleFields = $('#schedule-fields');

        function toggleScheduleFields() {
            const selectedType = typeSelector.val();
            // Tampilkan jika tipe BUKAN 'Rumah'
            if (selectedType !== 'Rumah') {
                scheduleFields.show();
            } else {
                scheduleFields.hide();
            }
        }

        // Jalankan saat halaman dimuat
        toggleScheduleFields();

        // Jalankan saat pilihan berubah
        typeSelector.on('change', toggleScheduleFields);
    });
</script>
<?= $this->endSection() ?>