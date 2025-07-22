<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Pilih Siswa untuk Laporan Kegiatan<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="max-w-xl mx-auto">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Laporan Kegiatan Siswa</h2>
    <div class="p-4 bg-white rounded-lg shadow-md space-y-4">
        <div>
            <label for="year_selector" class="block mb-2 text-sm font-medium">1. Pilih Tahun Ajaran</label>
            <select id="year_selector" class="block w-full mt-1 text-sm rounded-lg border-gray-300">
                <option value="">-- Silakan Pilih --</option>
                <?php foreach ($academic_years as $year): ?>
                    <option value="<?= $year['id'] ?>"><?= esc($year['year']) ?></option>
                <?php endforeach; ?>
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
        <button id="show_report_btn"
            class="w-full px-4 py-2 text-sm text-white bg-purple-600 rounded-lg disabled:bg-purple-300"
            disabled>Tampilkan Laporan</button>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        $('#year_selector').on('change', function () {
            const yearId = $(this).val();
            $('#class_selector_container, #student_selector_container').addClass('hidden');
            $('#show_report_btn').prop('disabled', true);
            if (!yearId) return;

            $.getJSON(`<?= site_url('admin/api/classes-by-year/') ?>${yearId}`, function (classes) {
                const classSelector = $('#class_selector');
                classSelector.empty().append('<option value="">-- Pilih Kelas --</option>');
                classes.forEach(cls => {
                    classSelector.append(`<option value="${cls.id}">${cls.name}</option>`);
                });
                $('#class_selector_container').removeClass('hidden');
            });
        });

        $('#class_selector').on('change', function () {
            const classId = $(this).val();
            $('#student_selector_container').addClass('hidden');
            $('#show_report_btn').prop('disabled', true);
            if (!classId) return;

            $.getJSON(`<?= site_url('admin/api/students-by-class/') ?>${classId}`, function (students) {
                const studentSelector = $('#student_selector');
                studentSelector.empty().append('<option value="">-- Pilih Siswa --</option>');
                students.forEach(std => {
                    studentSelector.append(`<option value="${std.id}">${std.full_name}</option>`);
                });
                $('#student_selector_container').removeClass('hidden');
            });
        });

        $('#student_selector').on('change', function () {
            $('#show_report_btn').prop('disabled', !$(this).val());
        });

        $('#show_report_btn').on('click', function () {
            const studentId = $('#student_selector').val();
            if (studentId) {
                const academicYearId = $('#year_selector').val();
                window.location.href = `<?= site_url('admin/laporan/siswa/') ?>${studentId}`;
            }
        });
    });
</script>
<?= $this->endSection() ?>