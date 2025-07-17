<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Rekap Kehadiran Siswa<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Laporan Rekap Kehadiran</h2>

    <div class="mb-6 p-4 bg-white rounded-lg shadow-xs">
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="class_id" class="block text-sm font-medium">Kelas</label>
                <select name="class_id" id="class_id" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['id'] ?>" <?= ($selected_class_id == $class['id']) ? 'selected' : '' ?>><?= esc($class['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="month" class="block text-sm font-medium">Bulan</label>
                <select name="month" id="month" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $selected_month == $m ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 10)) ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label for="year" class="block text-sm font-medium">Tahun</label>
                <select name="year" id="year" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50">
                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                        <option value="<?= $y ?>" <?= $selected_year == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">Tampilkan</button>
        </form>
    </div>

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3 text-left sticky left-0 bg-gray-100 z-10">Nama Siswa</th>
                        <?php foreach ($dateHeaders as $date) : ?>
                            <th class="px-3 py-3"><?= date('d', strtotime($date)) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    <?php if (!empty($reportData)): foreach ($reportData as $nis => $data) : ?>
                    <tr class="text-gray-700">
                        <td class="px-4 py-3 text-sm font-semibold sticky left-0 bg-white"><?= esc($data['full_name']) ?></td>
                        <?php foreach ($dateHeaders as $date) : ?>
                            <?php 
                                $status = $data['attendances'][$date] ?? 'Alfa';
                                $badge_color = 'bg-gray-200 text-gray-700'; // Default untuk strip
                                if ($status == 'Hadir') $badge_color = 'bg-green-100 text-green-700';
                                if ($status == 'Sakit') $badge_color = 'bg-yellow-100 text-yellow-700';
                                if ($status == 'Izin') $badge_color = 'bg-blue-100 text-blue-700';
                                if ($status == 'Alfa') $badge_color = 'bg-red-100 text-red-700';
                            ?>
                            <td class="px-2 py-3 text-center">
                                <span class="px-2 py-1 text-xs font-bold leading-tight rounded-full <?= $badge_color ?>">
                                    <?= substr($status, 0, 1) ?>
                                </span>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; elseif ($selected_class_id): ?>
                    <tr>
                        <td colspan="<?= count($dateHeaders) + 1 ?>" class="text-center py-4 text-gray-500">Tidak ada data kehadiran yang ditemukan untuk kelas dan periode yang dipilih.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>