<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Laporan Siswa: <?= esc($student['full_name']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6" x-data="{ tab: 'kehadiran' }">
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <div class="flex items-center space-x-4">
            <img class="h-16 w-16 object-cover rounded-full" src="<?= base_url('uploads/photos/' . ($student['photo'] ?? 'default.png')) ?>">
            <div>
                <h2 class="text-2xl font-semibold text-gray-700">Laporan Siswa</h2>
                <p class="text-lg text-gray-800 font-bold"><?= esc($student['full_name']) ?></p>
            </div>
        </div>
        <div class="w-full sm:w-72">
            <form method="get" id="filter-form">
                <label for="enrollment_id" class="block text-sm font-medium">Tampilkan Laporan Untuk</label>
                <select name="enrollment_id" id="enrollment_id" onchange="this.form.submit()" class="block w-full mt-1 text-sm rounded-lg border-gray-300">
                    <?php if (empty($enrollment_history)): ?>
                        <option>Tidak ada riwayat kelas</option>
                    <?php else: foreach ($enrollment_history as $enroll): ?>
                        <option value="<?= $enroll['id'] ?>" <?= $selected_enrollment_id == $enroll['id'] ? 'selected' : '' ?>>
                            Kelas <?= esc($enroll['class_name']) ?> (T/A <?= esc($enroll['academic_year']) ?>)
                        </option>
                    <?php endforeach; endif; ?>
                </select>
            </form>
        </div>
    </div>

    <div class="mb-4 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button @click="tab = 'kehadiran'" :class="tab === 'kehadiran' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Laporan Kehadiran</button>
            <button @click="tab = 'kegiatan'" :class="tab === 'kegiatan' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Laporan Kegiatan</button>
        </nav>
    </div>

    <div>
        <div x-show="tab === 'kehadiran'">
             <div class="w-full overflow-hidden rounded-lg shadow-xs">
                <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead><tr class="text-xs font-semibold text-left uppercase border-b bg-gray-50"><th class="px-4 py-3">Tanggal</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Jam Masuk</th><th class="px-4 py-3">Jam Pulang</th></tr></thead>
                        <tbody class="bg-white divide-y">
                            <?php if(!empty($attendances)): foreach($attendances as $att): ?>
                            <tr><td class="px-4 py-3 text-sm"><?= date('d M Y', strtotime($att['attendance_date'])) ?></td><td class="px-4 py-3 text-sm"><?= esc($att['status']) ?></td><td class="px-4 py-3 text-sm"><?= $att['check_in_time'] ? date('H:i', strtotime($att['check_in_time'])) : '-' ?></td><td class="px-4 py-3 text-sm"><?= $att['check_out_time'] ? date('H:i', strtotime($att['check_out_time'])) : '-' ?></td></tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data kehadiran.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div x-show="tab === 'kegiatan'" style="display: none;">
            <div class="space-y-4">
                <?php if(!empty($activities_by_day)): foreach($activities_by_day as $date => $activities): ?>
                    <div class="bg-white rounded-lg shadow-xs">
                        <div class="p-3 bg-gray-50 border-b rounded-t-lg">
                            <p class="font-semibold text-gray-700"><?= date('l, d F Y', strtotime($date)) ?></p>
                        </div>
                        <ul class="divide-y">
                            <?php foreach($activities as $act): ?>
                                <li class="px-4 py-3 text-sm text-gray-800"><?= esc($act['activity_name']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; else: ?>
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <p class="text-gray-500">Tidak ada data kegiatan yang tercatat.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>