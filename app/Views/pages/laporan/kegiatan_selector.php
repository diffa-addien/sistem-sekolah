<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Pilih Siswa untuk Laporan<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="max-w-xl mx-auto">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Laporan Detail Siswa</h2>
    <div class="p-4 bg-white rounded-2xl border border-gray-300 shadow-sm space-y-4">
        <div>
            <label for="year_selector" class="block mb-2 text-sm font-medium">1. Pilih Tahun Ajaran</label>
            <select id="year_selector" class="block w-full mt-1 text-sm rounded-lg border-gray-300">
                <option value="">-- Silakan Pilih --</option>
                <?php foreach($academic_years as $year): ?><option value="<?= $year['id'] ?>"><?= esc($year['year']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div id="class_selector_container" class="hidden">
            <label for="class_selector" class="block mb-2 text-sm font-medium">2. Pilih Kelas</label>
            <select id="class_selector" class="block w-full mt-1 text-sm rounded-lg border-gray-300"></select>
        </div>
        <div id="student_selector_container" class="hidden">
            <label for="student_selector" class="block mb-2 text-sm font-medium">3. Pilih Siswa</label>
            <select id="student_selector" class="block w-full mt-1 text-sm rounded-lg border-gray-300"></select>
        </div>
        <button id="show_report_btn" class="w-full px-4 py-2 text-sm text-white bg-sky-600 rounded-lg hover:bg-sky-700 disabled:bg-sky-300" disabled>Tampilkan Laporan</button>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
    <?= $this->endSection() ?>