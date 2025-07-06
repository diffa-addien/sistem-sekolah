<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Laporan Aktivitas Siswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-1 md:px-4 py-6 max-w-4xl">
    <div class="flex flex-col sm:flex-row items-center sm:space-x-4 mb-6">
        <img class="h-16 w-16 object-cover bg-white shadow-md rounded-full" src="<?= base_url('Uploads/photos/' . ($student['photo'] ?? 'default.png')) ?>" alt="Foto profil">
        <div class="mt-4 sm:mt-0 text-center md:text-start">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-700">Aktivitas Siswa</h2>
            <p class="text-base sm:text-lg text-gray-800 font-bold"><?= esc($student['full_name']) ?></p>
        </div>
    </div>
    
    <div class="flex justify-between border-b mb-6 overflow-x-auto whitespace-nowrap scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
        <?php 
            $daysInIndonesian = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            for ($i = 6; $i >= 0; $i--) : 
                $date = date('Y-m-d', strtotime("-$i days"));
                $dayIndex = date('w', strtotime($date));
                $fullDayName = ($i == 0) ? 'Hari Ini' : (($i == 1) ? 'Kemarin' : $daysInIndonesian[$dayIndex]);
                $shortDayName = ($i == 0) ? 'Hari Ini' : (($i == 1) ? 'Kemarin' : substr($daysInIndonesian[$dayIndex], 0, 1));
                $isActive = (service('request')->getGet('date') ?? date('Y-m-d')) == $date;
        ?>
            <a href="?date=<?= $date ?>" class="flex-shrink-0 px-2 sm:px-4 py-2 text-center border-b-2 <?= $isActive ? 'border-purple-600 text-purple-700 font-semibold' : 'border-transparent text-gray-800 hover:text-gray-950 hover:border-gray-400' ?> transition-colors duration-200">
                <span class="block text-xs sm:text-base sm:hidden"><?= $shortDayName ?></span>
                <span class="hidden sm:block text-xs sm:text-base"><?= $fullDayName ?></span>
                <span class="text-xs"><?= date('d M', strtotime($date)) ?></span>
            </a>
        <?php endfor; ?>
    </div>

    <div class="space-y-4">
        <?php 
            $selectedDate = service('request')->getGet('date') ?? date('Y-m-d');
            foreach ($home_activities as $activity) : 
                $isChecked = isset($recorded_activities[$selectedDate][$activity['id']]);
        ?>
            <div class="p-4 bg-white rounded-lg shadow-sm transition-all duration-300 <?= $isChecked ? 'bg-green-50' : '' ?> hover:shadow-md">
                <div class="flex items-center justify-between">
                    <label for="activity-<?= $activity['id'] ?>" class="flex items-center cursor-pointer flex-grow">
                        <input id="activity-<?= $activity['id'] ?>" type="checkbox" 
                            class="h-5 w-5 sm:h-6 sm:w-6 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                            data-activity-id="<?= $activity['id'] ?>"
                            <?= $isChecked ? 'checked' : '' ?>>
                        <span class="ml-3 sm:ml-4 text-base sm:text-lg font-medium text-gray-800"><?= esc($activity['name']) ?></span>
                    </label>
                    <div class="text-sm font-semibold <?= $isChecked ? 'text-green-600' : 'text-gray-500' ?>">
                        <?= $isChecked ? 'Selesai' : 'Belum' ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    const notyf = new Notyf({ duration: 2000, position: { x: 'right', y: 'top' } });
    const studentId = <?= $student['id'] ?>;
    const selectedDate = '<?= $selectedDate ?>';
    
    function saveActivity(activityId, isChecked) {
        $.ajax({
            url: "<?= site_url('wali/kegiatan-harian/save') ?>",
            method: 'POST',
            data: {
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                student_id: studentId,
                activity_name_id: activityId,
                date: selectedDate,
                is_checked: isChecked
            },
            success: function(response) {
                if(response.status !== 'success') {
                    notyf.error('Gagal menyimpan.');
                }
            },
            error: function() {
                notyf.error('Terjadi kesalahan koneksi.');
            }
        });
    }

    $('input[type="checkbox"]').on('change', function() {
        const checkbox = $(this);
        const isChecked = checkbox.is(':checked');
        const activityId = checkbox.data('activity-id');
        const activityCard = checkbox.closest('.p-4');

        if (isChecked) {
            activityCard.addClass('bg-green-50');
            activityCard.find('.text-sm.font-semibold').text('Selesai').removeClass('text-gray-500').addClass('text-green-600');
            saveActivity(activityId, true);
        } else {
            if (confirm('Anda yakin ingin membatalkan kegiatan ini?')) {
                activityCard.removeClass('bg-green-50');
                activityCard.find('.text-sm.font-semibold').text('Belum').removeClass('text-green-600').addClass('text-gray-500');
                saveActivity(activityId, false);
            } else {
                checkbox.prop('checked', true);
            }
        }
    });
});
</script>
<?= $this->endSection() ?>