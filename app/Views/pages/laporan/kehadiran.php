<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Rekap Kehadiran Siswa<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
    $bulanIndonesia = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
?>
<div class="container mx-auto px-4 py-6 overflow-hidden" x-data="attendanceModal()">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Laporan Rekap Kehadiran</h2>

    <div class="mb-6 p-4 bg-white rounded-2xl border border-gray-300 shadow-sm">
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="class_id" class="block text-sm font-medium">Kelas</label>
                <select name="class_id" id="class_id" class="block w-full mt-1 py-2 px-3 text-sm rounded-lg border-gray-300" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($classes as $class): ?><option value="<?= $class['id'] ?>" <?= ($selected_class_id == $class['id']) ? 'selected' : '' ?>><?= esc($class['name']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="month" class="block text-sm font-medium">Bulan</label>
                <select name="month" id="month" class="block w-full mt-1 py-2 px-3 text-sm rounded-lg border-gray-300">
                    <?php for ($m = 1; $m <= 12; $m++): ?><option value="<?= $m ?>" <?= $selected_month == $m ? 'selected' : '' ?>> <?= $bulanIndonesia[$m] ?></option><?php endfor; ?>
                </select>
            </div>
            <div>
                <label for="year" class="block text-sm font-medium">Tahun</label>
                <select name="year" id="year" class="block w-full mt-1 py-2 px-3 text-sm rounded-lg border-gray-300">
                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?><option value="<?= $y ?>" <?= $selected_year == $y ? 'selected' : '' ?>><?= $y ?></option><?php endfor; ?>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-lg hover:bg-sky-700">Tampilkan</button>
        </form>
    </div>

    <div class="w-full overflow-hidden rounded-2xl border border-gray-300">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold text-center text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3 text-left sticky left-0 bg-gray-100 z-10 min-w-[200px]">Nama Siswa</th>
                        <?php foreach ($dateHeaders as $date): ?>
                            <th class="px-3 py-3 min-w-[60px]">
                                <?php $month_num = date('n', strtotime($date)); echo date('d', strtotime($date)) . ' ' . substr($bulanIndonesia[$month_num], 0, 3); ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    <?php if (!empty($reportData)): foreach ($reportData as $nis => $data): ?>
                        <tr class="text-gray-700">
                            <td class="px-4 py-3 text-sm font-semibold sticky left-0 bg-white z-10">
                                <?php $detail_data = $detailedAttendance[$data['student_id']] ?? ['records' => [], 'summary' => ['hadir'=>0, 'sakit'=>0, 'izin'=>0]]; ?>
                                <button type="button" 
                                        @click="open('<?= esc($data['full_name']) ?>', <?= $data['student_id'] ?>, '<?= htmlspecialchars(json_encode($detail_data), ENT_QUOTES) ?>', '<?= esc($active_year['year'] ?? 'Tahun Ajaran Aktif') ?>')" 
                                        class="text-left hover:underline text-sky-600">
                                    <?= esc($data['full_name']) ?>
                                </button>
                            </td>
                            <?php foreach ($dateHeaders as $date): ?>
                                <?php $status = $data['attendances'][$date] ?? 'Alfa'; $badge_color = ($status == 'Hadir') ? 'bg-green-100 text-green-700' : (($status == 'Sakit') ? 'bg-yellow-100 text-yellow-700' : (($status == 'Izin') ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700')); ?>
                                <td class="px-2 py-3 text-center"><span class="px-2 py-1 text-xs font-bold leading-tight rounded-full <?= $badge_color ?>"><?= substr($status, 0, 1) ?></span></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; elseif ($selected_class_id): ?>
                        <tr><td colspan="<?= count($dateHeaders) + 1 ?>" class="text-center py-8 text-gray-500">Tidak ada data kehadiran untuk ditampilkan.</td></tr>
                    <?php else: ?>
                        <tr><td colspan="1" class="text-center py-8 text-gray-500">Silakan pilih kelas untuk menampilkan laporan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div x-show="isOpen" @keydown.escape.window="close()" class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
        <div @click.away="close()" class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6">
            <div class="flex justify-between items-start border-b pb-3">
                <div>
                    <h3 class="text-lg font-semibold" x-text="`Detail Kehadiran: ${studentName}`"></h3>
                    <p class="text-sm text-gray-500" x-text="`Tahun Ajaran ${activeYearName}`"></p>
                </div>
                <div class="flex items-center space-x-2 flex-shrink-0">
                    <a :href="'<?= site_url('admin/laporan/siswa/') ?>' + studentId" class="px-3 py-1 text-xs font-medium text-white bg-sky-600 rounded-md hover:bg-sky-700">Laporan Lengkap</a>
                    <button @click="close()" class="p-2 -mr-2 rounded-full hover:bg-gray-100">&times;</button>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 my-4 text-center">
                <div class="p-2 bg-green-50 rounded-lg"><p class="text-2xl font-bold text-green-600" x-text="summary.hadir"></p><p class="text-xs text-gray-500">Hadir</p></div>
                <div class="p-2 bg-yellow-50 rounded-lg"><p class="text-2xl font-bold text-yellow-600" x-text="summary.sakit"></p><p class="text-xs text-gray-500">Sakit</p></div>
                <div class="p-2 bg-blue-50 rounded-lg"><p class="text-2xl font-bold text-blue-600" x-text="summary.izin"></p><p class="text-xs text-gray-500">Izin</p></div>
            </div>
            <div class="mt-4 max-h-64 overflow-y-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 sticky top-0"><tr class="border-b"><th class="px-4 py-2">Tanggal</th><th class="px-4 py-2">Status</th><th class="px-4 py-2">Jam Masuk</th><th class="px-4 py-2">Jam Pulang</th></tr></thead>
                    <tbody class="divide-y">
                        <template x-for="att in attendanceData">
                            <tr class="border-b"><td class="px-4 py-2" x-text="att.formatted_date"></td><td class="px-4 py-2" x-text="att.status"></td><td class="px-4 py-2" x-text="att.check_in_time || '-'"></td><td class="px-4 py-2" x-text="att.check_out_time || '-'"></td></tr>
                        </template>
                        <template x-if="attendanceData.length === 0">
                            <tr><td colspan="4" class="text-center text-gray-500 py-8">Tidak ada catatan kehadiran pada tahun ajaran ini.</td></tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function attendanceModal() {
    return {
        isOpen: false,
        studentName: '',
        studentId: null,
        activeYearName: '',
        attendanceData: [],
        summary: { hadir: 0, sakit: 0, izin: 0 },
        open(studentName, studentId, detailJson, activeYearName) {
            this.studentName = studentName;
            this.studentId = studentId;
            this.activeYearName = activeYearName;
            
            // Perbaikan: Parse JSON sekali saja
            let detailData = JSON.parse(detailJson.replace(/&quot;/g, '"'));
            
            this.summary = detailData.summary; 
            
            this.attendanceData = detailData.records.map(item => ({
                ...item,
                formatted_date: new Date(item.attendance_date).toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long' }),
                check_in_time: item.check_in_time ? item.check_in_time.substring(0, 5) : null,
                check_out_time: item.check_out_time ? item.check_out_time.substring(0, 5) : null
            }));
            this.isOpen = true;
        },
        close() {
            this.isOpen = false;
        }
    }
}
</script>
<?= $this->endSection() ?>