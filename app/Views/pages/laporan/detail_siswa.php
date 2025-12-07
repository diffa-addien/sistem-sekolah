<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Laporan Siswa: <?= esc($student['full_name']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6" x-data="{ tab: 'kehadiran' }">
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <div class="flex items-center space-x-4">
            <!-- <img class="h-16 w-16 object-cover rounded-full"
                src="<?= base_url('uploads/photos/' . ($student['photo'] ?? 'default.png')) ?>"> -->
            <div class="relative h-20 w-20">
                <img class="object-cover h-20 w-20 rounded-full shadow-md"
                    src="<?= base_url('uploads/photos/' . $student['photo']) ?>" alt="Foto Profil" loading="lazy"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div
                    class="hidden absolute inset-0 flex items-center justify-center rounded-full bg-sky-900 text-white text-4xl font-semibold">
                    <?= esc(strtoupper(substr($student['full_name'], 0, 1))) ?>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-semibold text-gray-700">Laporan Siswa</h2>
                <p class="text-lg text-gray-800 font-bold"><?= esc($student['full_name']) ?></p>
            </div>
        </div>
        <div class="w-full sm:w-72">
            <form method="get" id="filter-form">
                <label for="enrollment_id" class="block text-sm font-medium">Tampilkan Laporan Untuk</label>
                <select name="enrollment_id" id="enrollment_id" onchange="this.form.submit()"
                    class="block w-full mt-1 text-sm rounded-lg border-gray-300">
                    <?php if (empty($enrollment_history)): ?>
                        <option>Tidak ada riwayat kelas</option>
                    <?php else:
                        foreach ($enrollment_history as $enroll): ?>
                            <option value="<?= $enroll['id'] ?>" <?= $selected_enrollment_id == $enroll['id'] ? 'selected' : '' ?>>
                                Kelas <?= esc($enroll['class_name']) ?> (T/A <?= esc($enroll['academic_year']) ?>)
                            </option>
                        <?php endforeach; endif; ?>
                </select>
            </form>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6" x-data="{ tab: 'kehadiran' }">
        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="p-4 bg-white rounded-lg shadow-xs text-center">
                <p class="text-3xl font-semibold text-green-600"><?= esc($summary['hadir']) ?></p>
                <p class="text-sm text-gray-500">Hari Hadir</p>
            </div>
            <div class="p-4 bg-white rounded-lg shadow-xs text-center">
                <p class="text-3xl font-semibold text-yellow-600"><?= esc($summary['sakit']) ?></p>
                <p class="text-sm text-gray-500">Hari Sakit</p>
            </div>
            <div class="p-4 bg-white rounded-lg shadow-xs text-center">
                <p class="text-3xl font-semibold text-blue-600"><?= esc($summary['izin']) ?></p>
                <p class="text-sm text-gray-500">Hari Izin</p>
            </div>
            <div class="p-4 bg-white rounded-lg shadow-xs text-center">
                <p class="text-3xl font-semibold text-purple-600"><?= esc($summary['kegiatan']) ?></p>
                <p class="text-sm text-gray-500">Total Kegiatan</p>
            </div>
        </div>
        <div class="mb-6 p-3 bg-blue-50 border border-blue-200 rounded-lg text-center text-sm text-blue-700">
            Sebagai perbandingan, rekor hari aktif tercatat tertinggi di angkatan ini adalah
            <strong><?= esc($summary['hari_aktif_tertinggi']) ?> hari</strong>.
        </div>


        <div class="mb-4 border-b border-gray-200">
        </div>

        <div>
        </div>
    </div>

    <div class="mb-4 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button @click="tab = 'kehadiran'"
                :class="tab === 'kehadiran' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Laporan Kehadiran</button>
            <button @click="tab = 'kegiatan'"
                :class="tab === 'kegiatan' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Laporan Kegiatan</button>
        </nav>
    </div>

    <div>
        <div x-show="tab === 'kehadiran'">
            <div class="w-full overflow-hidden rounded-lg shadow-xs">
                <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold text-left uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Jam Masuk</th>
                                <th class="px-4 py-3">Jam Pulang</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            <?php if (!empty($attendances)):
                                foreach ($attendances as $att): ?>
                                    <tr>
                                        <td class="px-4 py-3 text-sm"><?= date('d M Y', strtotime($att['attendance_date'])) ?>
                                        </td>
                                        <td class="px-4 py-3 text-sm"><?= esc($att['status']) ?></td>
                                        <td class="px-4 py-3 text-sm">
                                            <?= $att['check_in_time'] ? date('H:i', strtotime($att['check_in_time'])) : '-' ?>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <?= $att['check_out_time'] ? date('H:i', strtotime($att['check_out_time'])) : '-' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data kehadiran.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div x-show="tab === 'kegiatan'" style="display: none;">
            <div class="space-y-4">
                <?php if (!empty($activities_by_day)):
                    foreach ($activities_by_day as $date => $activities): ?>
                        <div class="bg-white rounded-lg shadow-xs">
                            <div class="p-3 bg-gray-50 border-b rounded-t-lg">
                                <p class="font-semibold text-gray-700"><?= date('l, d F Y', strtotime($date)) ?></p>
                            </div>
                            <ul class="divide-y">
                                <?php foreach ($activities as $act): ?>
                                    <li class="px-4 py-3 text-sm text-gray-800">
                                        <?= esc($act['activity_name']) ?>
                                        <span class="text-xs text-gray-500 ml-2">
                                            <?= $act['created_at'] ? date('H:i', strtotime($act['created_at'])) : '' ?>
                                        </span>
                                    </li>
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