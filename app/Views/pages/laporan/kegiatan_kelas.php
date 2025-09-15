<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Laporan Rangkuman Kegiatan per Kelas<?= $this->endSection() ?>

<?php
$bulanIndonesia = [
  1 => 'Januari',
  2 => 'Februari',
  3 => 'Maret',
  4 => 'April',
  5 => 'Mei',
  6 => 'Juni',
  7 => 'Juli',
  8 => 'Agustus',
  9 => 'September',
  10 => 'Oktober',
  11 => 'November',
  12 => 'Desember'
];
?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6 overflow-hidden" x-data="activityReport()">
  <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
    <h2 class="text-2xl font-semibold text-gray-700">Laporan Rangkuman Kegiatan per Kelas</h2>
    <?php if ($selected_class_id): ?>
      <a href="<?= current_url() . '?' . http_build_query(request()->getGet()) . '&export=pdf' ?>" target="_blank"
        class="px-4 py-2 text-sm font-medium text-white bg-lime-600 rounded-lg hover:bg-lime-700">
        Cetak ke PDF
      </a>
    <?php endif; ?>
  </div>

  <div class="mb-6 p-4 bg-white rounded-2xl border border-gray-300 shadow-sm">
    <form method="get" class="flex flex-wrap items-end gap-4">
      <div>
        <label for="class_id" class="block text-sm font-medium">Kelas</label>
        <select name="class_id" id="class_id" class="block w-full mt-1 py-2 px-3 text-sm rounded-lg border-gray-300"
          required>
          <option value="">-- Pilih Kelas --</option>
          <?php foreach ($classes as $class): ?>
            <option value="<?= $class['id'] ?>" <?= ($selected_class_id == $class['id']) ? 'selected' : '' ?>>
              <?= esc($class['name']) ?>
            </option><?php endforeach; ?>
        </select>
      </div>
      <div>
        <label for="month" class="block text-sm font-medium">Bulan</label>
        <select name="month" id="month" class="block w-full mt-1 py-2 px-3 text-sm rounded-lg border-gray-300">
          <?php for ($m = 1; $m <= 12; $m++): ?>
            <option value="<?= $m ?>" <?= $selected_month == $m ? 'selected' : '' ?>>
              <?= $bulanIndonesia[$m] ?>
            </option><?php endfor; ?>
        </select>
      </div>
      <div>
        <label for="year" class="block text-sm font-medium">Tahun</label>
        <select name="year" id="year" class="block w-full mt-1 py-2 px-3 text-sm rounded-lg border-gray-300">
          <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
            <option value="<?= $y ?>" <?= $selected_year == $y ? 'selected' : '' ?>><?= $y ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <button type="submit"
        class="px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-lg hover:bg-sky-700">Tampilkan</button>
    </form>
  </div>

  <div class="w-full overflow-hidden rounded-2xl border border-gray-300">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead>
          <tr class="text-xs font-semibold text-center text-gray-500 uppercase border-b bg-gray-50">
            <th class="px-4 py-3 text-left sticky left-0 bg-gray-100 z-10">Nama Siswa</th>
            <?php foreach ($dateHeaders as $date): ?>
              <th class="px-2 py-3"><?= date('d M', strtotime($date)) ?></th><?php endforeach; ?>
          </tr>
        </thead>
        <tbody class="bg-white divide-y">
          <?php if (!empty($pivotedData)):
            foreach ($pivotedData as $student_id => $student): ?>
              <tr class="text-gray-700">
                <td class="px-4 py-3 text-sm font-semibold sticky left-0 bg-white">
                  <button type="button"
                    @click="openYearly('<?= esc($student['full_name']) ?>', <?= $student_id ?>, '<?= htmlspecialchars(json_encode($yearlySummary[$student_id] ?? []), ENT_QUOTES) ?>')"
                    class="text-left hover:underline text-sky-600">
                    <?= esc($student['full_name']) ?>
                  </button>
                </td>
                <?php foreach ($dateHeaders as $date): ?>
                  <td class="px-2 py-3 text-center">
                    <?php if (isset($student['daily_activities'][$date])):
                      $day_data = $student['daily_activities'][$date]; ?>
                      <button
                        @click="openDaily('<?= esc($student['full_name']) ?>', '<?= date('l, d M Y', strtotime($date)) ?>', '<?= htmlspecialchars(json_encode($day_data['details']), ENT_QUOTES) ?>')"
                        class="px-2 py-1 text-sm font-bold text-sky-700 bg-sky-100 rounded-md hover:bg-sky-200">
                        <?= $day_data['count'] ?>
                      </button>
                    <?php else: ?> <span class="text-gray-300">0</span> <?php endif; ?>
                  </td>
                <?php endforeach; ?>
              </tr>
            <?php endforeach; else: ?>
            <tr>
              <td colspan="1" class="text-center py-4 text-gray-500">Silakan pilih kelas untuk menampilkan laporan.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div x-show="isDailyOpen" @keydown.escape.window="isDailyOpen = false"
    class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
    <div @click.away="isDailyOpen = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
      <h3 class="text-lg font-semibold" x-text="modalTitle"></h3>
      <p class="text-sm text-gray-500 mb-4" x-text="modalSubtitle"></p>
      <div class="max-h-60 overflow-y-auto">
        <ul class="list-disc list-inside space-y-2 text-gray-700"><template x-for="item in modalItems">
            <li x-text="item"></li>
          </template></ul>
      </div>
    </div>
  </div>

  <div x-show="isYearlyOpen" @keydown.escape.window="isYearlyOpen = false"
    class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
    <div @click.away="isYearlyOpen = false" class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6">
      <div class="flex justify-between items-start border-b pb-3">
        <div>
          <h3 class="text-lg font-semibold" x-text="modalTitle"></h3>
          <p class="text-sm text-gray-500" x-text="modalSubtitle"></p>
        </div>
        <div class="flex items-center space-x-2 flex-shrink-0">
          <a :href="'<?= site_url('admin/laporan/siswa/') ?>' + studentId"
            class="px-3 py-1 text-xs font-medium text-white bg-sky-600 rounded-md hover:bg-sky-700">Laporan Lengkap</a>
          <button @click="isYearlyOpen = false" class="p-2 -mr-2 rounded-full hover:bg-gray-100">&times;</button>
        </div>

      </div>
      <div class="my-4">
        <p class="text-center"><strong x-text="yearlyTotal" class="text-2xl font-bold text-purple-600"></strong> <span
            class="text-gray-600">Total Kegiatan Tercatat</span></p>
      </div>
      <div class="max-h-80 overflow-y-auto">
        <table class="w-full text-sm text-left">
          <thead class="bg-gray-50 sticky top-0">
            <tr>
              <th class="px-4 py-2">Tanggal</th>
              <th class="px-4 py-2">Kegiatan</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <template x-for="[date, activities] in Object.entries(yearlyItems)">
              <tr>
                <td class="px-4 py-2 font-semibold" x-text="formatYearlyDate(date)"></td>
                <td class="px-4 py-2" x-text="activities.join(', ')"></td>
              </tr>
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
  function activityReport() {
    return {
      isDailyOpen: false, isYearlyOpen: false,
      modalTitle: '', modalSubtitle: '',
      modalItems: [], yearlyItems: {}, yearlyTotal: 0, studentId: null,
      formatYearlyDate(dateStr) {
        return new Date(dateStr).toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long' });
      },
      openDaily(studentName, date, activitiesJson) {
        this.modalTitle = `Detail Kegiatan: ${studentName}`;
        this.modalSubtitle = date;
        this.modalItems = JSON.parse(activitiesJson.replace(/&quot;/g, '"'));
        this.isDailyOpen = true;
      },
      openYearly(studentName, studentId, summaryJson) {
        this.modalTitle = `Rangkuman Kegiatan: ${studentName}`;
        this.modalSubtitle = 'Tahun Ajaran <?= esc($active_year['year'] ?? '') ?>';
        this.studentId = studentId;
        let summaryData = JSON.parse(summaryJson.replace(/&quot;/g, '"'));
        this.yearlyItems = summaryData.activities_by_date || {};
        this.yearlyTotal = summaryData.total_count || 0;
        this.isYearlyOpen = true;
      }
    }
  }
</script>
<?= $this->endSection() ?>