<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Rekap Kehadiran Siswa<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6 overflow-hidden">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Laporan Kehadiran per Kelas</h2>

    <div class="mb-6 p-4 bg-white rounded-2xl border border-gray-300 shadow-sm">
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="class_id" class="block text-sm font-medium">Kelas</label>
                <select name="class_id" id="class_id" class="block w-full mt-1 py-2 px-3 text-sm rounded-lg border-gray-300" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['id'] ?>" <?= ($selected_class_id == $class['id']) ? 'selected' : '' ?>><?= esc($class['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="month" class="block text-sm font-medium">Bulan</label>
                <select name="month" id="month" class="block w-full mt-1 py-2 px-3 text-sm rounded-lg border-gray-300">
                    <?php for ($m = 1; $m <= 12; $m++): ?><option value="<?= $m ?>" <?= $selected_month == $m ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 10)) ?></option><?php endfor; ?>
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
                        <th class="px-4 py-3 text-left sticky left-0 bg-gray-100 z-10">Nama Siswa</th>
                        <?php foreach ($dateHeaders as $date): ?>
                            <th class="px-3 py-3 min-w-[60px]"><?= date('d M', strtotime($date)) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    <?php if (!empty($reportData)): foreach ($reportData as $nis => $data): ?>
                            <tr class="text-gray-700">
                                <td class="px-4 py-3 text-sm font-semibold sticky left-0 bg-white z-10">
                                    <a href="<?= site_url('admin/laporan/siswa/' . $data['student_id']) ?>" title="Lihat Laporan Detail" class="hover:underline text-sky-600">
                                        <?= esc($data['full_name']) ?>
                                    </a>
                                </td>
                                <?php foreach ($dateHeaders as $date): ?>
                                    <?php $status = $data['attendances'][$date] ?? 'Alfa';
                                    $badge_color = ($status == 'Hadir') ? 'bg-green-100 text-green-700' : (($status == 'Sakit') ? 'bg-yellow-100 text-yellow-700' : (($status == 'Izin') ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700')); ?>
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
</div>
<?= $this->endSection() ?>