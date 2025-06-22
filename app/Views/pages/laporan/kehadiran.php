<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Laporan Kehadiran Siswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h2 class="text-2xl font-semibold text-gray-700 mb-4">Laporan Kehadiran Siswa</h2>

<div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
    <form action="<?= site_url('admin/laporan/kehadiran') ?>" method="get">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label for="class_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                <select name="class_id" id="class_id" class="input-field mt-1" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($classes as $class) : ?>
                        <option value="<?= $class['id'] ?>" <?= ($selected_class_id == $class['id']) ? 'selected' : '' ?>>
                            <?= esc($class['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" value="<?= esc($start_date) ?>" class="input-field mt-1" required>
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" value="<?= esc($end_date) ?>" class="input-field mt-1" required>
            </div>
            <div>
                <button type="submit" class="btn-primary w-full">Tampilkan Laporan</button>
            </div>
        </div>
    </form>
</div>

<?php if (!empty($reportData)) : ?>
<div class="w-full overflow-hidden rounded-lg shadow-xs">
    <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b bg-gray-50">
                    <th class="px-4 py-3 text-left sticky left-0 bg-gray-50 z-10">Nama Siswa</th>
                    <?php foreach ($dateHeaders as $date) : ?>
                        <th class="px-2 py-3"><?= date('d M', strtotime($date)) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
    <?php foreach ($reportData as $nis => $data) : ?>
        <tr class="text-gray-700">
            <td class="px-4 py-3 text-sm font-semibold sticky left-0 bg-white z-10"><?= esc($data['full_name']) ?></td>
            <?php foreach ($dateHeaders as $date) : ?>
                <?php 
                    // Jika data tidak ada, anggap sebagai 'Alfa'
                    $status = $data['attendances'][$date] ?? '-';
                    
                    // Logika pewarnaan badge (sekarang menyertakan 'Alfa' secara otomatis)
                    $badge_color = 'bg-gray-200 text-gray-700'; // Default
                    if ($status == 'Hadir') $badge_color = 'bg-green-100 text-green-700';
                    if ($status == 'Sakit') $badge_color = 'bg-blue-100 text-blue-700';
                    if ($status == 'Izin') $badge_color = 'bg-yellow-100 text-yellow-700';
                    if ($status == 'Alfa') $badge_color = 'bg-red-100 text-red-700';
                ?>
                <td class="px-2 py-3 text-center">
                    <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full <?= $badge_color ?>">
                        <?= substr($status, 0, 1) ?>
                    </span>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</tbody>
        </table>
    </div>
</div>
<?php elseif ($selected_class_id) : ?>
    <div class="px-4 py-3 mb-4 text-yellow-700 bg-yellow-100 border border-yellow-400 rounded-lg" role="alert">
        <strong class="font-bold">Informasi:</strong>
        <span class="block sm:inline">Tidak ada data kehadiran yang ditemukan untuk kelas dan rentang tanggal yang dipilih.</span>
    </div>
<?php endif; ?>

<style> .input-field{display:block;width:100%;padding:0.625rem;font-size:0.875rem;color:#111827;background-color:#F9FAFB;border:1px solid #D1D5DB;border-radius:0.5rem}.input-field:focus{--tw-ring-color:#9333ea;border-color:#9333ea;box-shadow:var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color)}.btn-primary{padding-left:1rem;padding-right:1rem;padding-top:0.625rem;padding-bottom:0.625rem;font-size:0.875rem;font-weight:500;color:white;background-color:#9333ea;border-radius:0.5rem}.btn-primary:hover{background-color:#7e22ce} </style>
<?= $this->endSection() ?>