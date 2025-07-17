<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Laporan Kegiatan <?= esc($student['full_name']) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php $role = session()->get('role'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <div class="flex items-center space-x-4">
            <img class="h-16 w-16 object-cover rounded-full shadow-md" src="<?= base_url('uploads/photos/' . ($student['photo'] ?? 'default.png')) ?>">
            <div>
                <h2 class="text-2xl font-semibold text-gray-700">Laporan Rekap Kegiatan</h2>
                <p class="text-lg text-gray-800 font-bold"><?= esc($student['full_name']) ?></p>
            </div>
        </div>
        <a href="<?= $role == "Wali Murid" ? site_url('wali/dashboard') : site_url('admin/laporan/kegiatan') ?>" class="flex-shrink-0 flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-200">
             <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="mb-6 p-4 bg-white rounded-lg shadow-xs">
        <form method="get" id="filter-form">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="filter_class_id" class="text-sm font-medium">Filter Kelas (Opsional)</label>
                    <select name="filter_class_id" id="filter_class_id" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50">
                        <option value="">-- Semua Kelas dalam Rentang --</option>
                        <?php foreach($enrollment_history as $enroll): ?>
                            <option value="<?= $enroll['class_id'] ?>" <?= ($selected_class_id == $enroll['class_id']) ? 'selected' : '' ?>>
                                Kelas <?= esc($enroll['class_name']) ?> (T/A <?= esc($enroll['academic_year']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="md:col-span-2">
                     <label class="text-sm font-medium">Filter Rentang Tanggal</label>
                    <div class="flex items-center gap-4 mt-1">
                        <input type="date" name="start_date" value="<?= esc($start_date) ?>" class="block w-full text-sm rounded-lg border-gray-300 bg-gray-50">
                        <span class="text-gray-500">s/d</span>
                        <input type="date" name="end_date" value="<?= esc($end_date) ?>" class="block w-full text-sm rounded-lg border-gray-300 bg-gray-50">
                    </div>
                </div>
            </div>
            <div class="mt-4 text-right">
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">Terapkan Filter</button>
            </div>
        </form>
    </div>

    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Tidak perlu JavaScript lagi untuk logika filter ini
</script>
<?= $this->endSection() ?>