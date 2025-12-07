<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Rekap Kehadiran <?= esc($student['full_name']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
  <div class="flex justify-between items-center mb-4">
    <div class="flex items-center space-x-4">
      <?php if (!empty($student['photo'])): ?>
        <div class="relative h-20 w-20">
          <img class="object-cover h-20 w-20 rounded-full shadow-md"
            src="<?= base_url('uploads/photos/' . $student['photo']) ?>" alt="Foto Profil" loading="lazy"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
          <div
            class="hidden absolute inset-0 flex items-center justify-center rounded-full bg-sky-900 text-white text-4xl font-semibold">
            <?= esc(strtoupper(substr($student['full_name'], 0, 1))) ?>
          </div>
        </div>
      <?php else: ?>
        <div
          class="flex items-center justify-center h-20 w-20 rounded-full bg-blue-500 text-white text-4xl font-semibold">
          <?= esc(substr($student['full_name'], 0, 1)) ?>
        </div>
      <?php endif; ?>
      <div>
        <h2 class="text-2xl font-semibold text-gray-700">Rekap Kehadiran</h2>
        <p class="text-lg text-gray-800 font-bold"><?= esc($student['full_name']) ?></p>
      </div>
    </div>
    <a href="<?= site_url('wali/dashboard') ?>"
      class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
      </svg>
      Kembali
    </a>
  </div>

  <div class="mb-6 p-4 bg-white rounded-lg shadow-xs">
    <form method="get" class="flex flex-wrap items-end gap-4">
      <div>
        <label for="month" class="block text-sm font-medium">Bulan</label>
        <select name="month" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50">
          <?php for ($m = 1; $m <= 12; $m++): ?>
            <option value="<?= $m ?>" <?= $selected_month == $m ? 'selected' : '' ?>>
              <?= date('F', mktime(0, 0, 0, $m, 10)) ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div>
        <label for="year" class="block text-sm font-medium">Tahun</label>
        <select name="year" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50">
          <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
            <option value="<?= $y ?>" <?= $selected_year == $y ? 'selected' : '' ?>><?= $y ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <button type="submit"
        class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">Tampilkan</button>
    </form>
  </div>

  <div class="bg-white rounded-lg shadow-md p-4">
    <?php
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selected_month, $selected_year);
    $firstDayOfMonth = date('w', strtotime("$selected_year-$selected_month-01"));
    $dayCounter = 1;
    $statusColors = [
      'Hadir' => 'bg-green-100 text-green-800',
      'Sakit' => 'bg-yellow-100 text-yellow-800',
      'Izin' => 'bg-blue-100 text-blue-800',
      'Alpa' => 'bg-red-100 text-red-800',
    ];
    ?>
    <div class="grid grid-cols-7 gap-2 text-center text-sm font-semibold text-gray-600 mb-2">
      <div>Min</div>
      <div>Sen</div>
      <div>Sel</div>
      <div>Rab</div>
      <div>Kam</div>
      <div>Jum</div>
      <div>Sab</div>
    </div>
    <div class="grid grid-cols-7 gap-2">
      <?php for ($i = 0; $i < $firstDayOfMonth; $i++): ?>
        <div></div>
      <?php endfor; ?>
      <?php while ($dayCounter <= $daysInMonth): ?>
        <?php
        $currentDate = "$selected_year-" . str_pad($selected_month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($dayCounter, 2, '0', STR_PAD_LEFT);
        $attendance_data = $attendances[$currentDate] ?? null;
        ?>
        <div
          class="p-2 border rounded-lg h-28 flex flex-col justify-between <?= $attendance_data ? $statusColors[$attendance_data['status']] ?? 'bg-gray-50' : 'bg-gray-50' ?>">
          <div class="font-bold text-gray-800"><?= $dayCounter ?></div>
          <?php if ($attendance_data): ?>
            <div class="text-xs text-center">
              <p class="font-semibold"><?= esc($attendance_data['status']) ?></p>
              <?php if ($attendance_data['check_in_time']): ?>
                <p>Masuk: <?= date('H:i', strtotime($attendance_data['check_in_time'])) ?></p>
              <?php endif; ?>
              <?php if ($attendance_data['check_out_time']): ?>
                <p>Pulang: <?= date('H:i', strtotime($attendance_data['check_out_time'])) ?></p>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
        <?php $dayCounter++; ?>
      <?php endwhile; ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>