<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Manajemen Kenaikan Kelas & Kelulusan
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Manajemen Kenaikan Kelas & Kelulusan</h2>

    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form action="<?= site_url('admin/kenaikan-kelas') ?>" method="get">
            <p class="text-md text-gray-600 mb-4">Pilih tahun ajaran (lama) untuk memindahkan siswa ke tahun ajaran aktif.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="from_year_id" class="block text-sm font-medium text-gray-700 mb-1">Dari Tahun Ajaran (Tidak Aktif)</label>
                    <select name="from_year_id" id="from_year_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">-- Pilih Tahun Ajaran Sumber --</option>
                        <?php foreach ($inactive_years as $year) : ?>
                            <option value="<?= $year['id'] ?>" <?= ($selected_from_year == $year['id']) ? 'selected' : '' ?>><?= esc($year['year']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ke Tahun Ajaran (Aktif)</label>
                    <input type="text" value="<?= esc($active_year['year'] ?? 'Tidak ada tahun ajaran aktif') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-green-100" readonly>
                </div>
                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200">Tampilkan Data</button>
                </div>
            </div>
        </form>
    </div>

    <?php if (!empty($source_classes) && !empty($active_year)) : ?>
    <form action="<?= site_url('admin/kenaikan-kelas/proses') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="from_year_id" value="<?= esc($selected_from_year) ?>">
        <input type="hidden" name="to_year_id" value="<?= esc($active_year['id']) ?>">

        <?php foreach ($source_classes as $class) : ?>
            <?php 
                // Helper untuk mendapatkan tingkat kelas dari nama
                preg_match('/(\d+)/', $class['name'], $matches);
                $source_level = $matches[1] ?? 0;
            ?>
            <?php if (!empty($class['students'])) : ?>
            <div class="class-card bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-200 pb-3 mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Daftar Siswa Kelas: <?= esc($class['name']) ?></h3>
                    <div class="w-full sm:w-auto mt-3 sm:mt-0">
                        <label for="bulk-action-<?= $class['id'] ?>" class="block text-sm font-medium text-gray-700 mb-1">Aksi Massal:</label>
                        <select id="bulk-action-<?= $class['id'] ?>" class="bulk-action-select w-full sm:w-64 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Belum diatur --</option>
                            <optgroup label="Pindahkan ke Kelas">
                                <?php foreach ($destination_classes as $dest_class) : ?>
                                    <option value="<?= $dest_class['id'] ?>"><?= esc($dest_class['name']) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                            <?php if ($source_level == 6): // Hanya tampilkan opsi lulus untuk kelas 6 ?>
                            <optgroup label="Aksi Lain">
                                <option value="lulus">Luluskan Semua</option>
                            </optgroup>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs font-semibold text-gray-500 uppercase border-b">
                                <th class="py-3 px-4">Nama Siswa</th>
                                <th class="py-3 px-4">Aksi Individual</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($class['students'] as $student) : ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        <p class="font-semibold text-gray-800"><?= esc($student['full_name']) ?></p>
                                        <p class="text-xs text-gray-500">NIS: <?= esc($student['nis']) ?></p>
                                    </td>
                                    <td class="py-3 px-4">
                                        <select name="actions[<?= $student['id'] ?>]" class="student-action-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">-- Belum diatur --</option>
                                            <optgroup label="Pindahkan ke Kelas">
                                                <?php foreach ($destination_classes as $dest_class) : 
                                                    preg_match('/(\d+)/', $dest_class['name'], $dest_matches);
                                                    $dest_level = $dest_matches[1] ?? 0;
                                                ?>
                                                    <option value="<?= $dest_class['id'] ?>">
                                                        <?= esc($dest_class['name']) ?>
                                                        <?php if ($source_level == $dest_level): ?>
                                                            (Tinggal Kelas)
                                                        <?php endif; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <?php if ($source_level == 6): ?>
                                            <optgroup label="Aksi Lain">
                                                <option value="lulus">Lulus</option>
                                            </optgroup>
                                            <?php endif; ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
        
        <div class="flex justify-end mt-6">
            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition duration-200" onclick="return confirm('PERINGATAN: Aksi ini tidak dapat dibatalkan! Pastikan semua data sudah benar sebelum melanjutkan. Lanjutkan?');">
                Proses Kenaikan Kelas & Kelulusan
            </button>
        </div>
    </form>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('.bulk-action-select').on('change', function() {
        const selectedValue = $(this).val();
        $(this).closest('.class-card').find('.student-action-select').val(selectedValue);
    });
});
</script>
<?= $this->endSection() ?>