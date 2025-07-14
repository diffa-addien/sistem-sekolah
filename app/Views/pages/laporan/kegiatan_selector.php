<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Pilih Siswa untuk Laporan Kegiatan<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="max-w-xl mx-auto">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Laporan Kegiatan Siswa</h2>
    <div class="p-4 bg-white rounded-lg shadow-md space-y-4">
        <div>
            <label for="class_selector" class="block mb-2 text-sm font-medium text-gray-700">1. Pilih Kelas Terlebih Dahulu</label>
            <select id="class_selector" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300">
                <option value="">-- Pilih Kelas --</option>
                <?php foreach($classes as $class): ?>
                    <option value="<?= $class['id'] ?>"><?= esc($class['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div id="student_selector_container" class="hidden">
            <label for="student_selector" class="block mb-2 text-sm font-medium text-gray-700">2. Pilih Siswa</label>
            <select id="student_selector" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300">
                </select>
        </div>

        <button id="show_report_btn" class="w-full px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 disabled:bg-purple-300" disabled>
            Tampilkan Laporan
        </button>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    const allStudents = <?= json_encode($all_students) ?>;
    const classSelector = $('#class_selector');
    const studentContainer = $('#student_selector_container');
    const studentSelector = $('#student_selector');
    const reportButton = $('#show_report_btn');

    classSelector.on('change', function() {
        const selectedClassId = $(this).val();
        
        // Kosongkan dan sembunyikan dropdown siswa
        studentSelector.empty().append('<option value="">-- Pilih Siswa --</option>');
        reportButton.prop('disabled', true);

        if (selectedClassId) {
            // Filter siswa yang sesuai dengan kelas yang dipilih
            const studentsInClass = allStudents.filter(student => student.class_id == selectedClassId);
            
            // Isi dropdown siswa dengan hasil filter
            studentsInClass.forEach(student => {
                studentSelector.append(`<option value="${student.id}">${student.full_name}</option>`);
            });

            // Tampilkan dropdown siswa
            studentContainer.removeClass('hidden');
        } else {
            studentContainer.addClass('hidden');
        }
    });

    studentSelector.on('change', function() {
        // Aktifkan tombol jika siswa sudah dipilih
        if ($(this).val()) {
            reportButton.prop('disabled', false);
        } else {
            reportButton.prop('disabled', true);
        }
    });

    reportButton.on('click', function() {
        const studentId = studentSelector.val();
        if (studentId) {
            window.location.href = `<?= site_url('admin/laporan/kegiatan/siswa/') ?>${studentId}`;
        }
    });
});
</script>
<?= $this->endSection() ?>