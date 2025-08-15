<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Laporan Kegiatan per Kelas<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6 overflow-hidden" x-data="activityModal()">
  <h2 class="text-2xl font-semibold text-gray-700 mb-4">Laporan Kegiatan per Kelas</h2>

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
              <?= date('F', mktime(0, 0, 0, $m, 10)) ?>
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

  <!-- <div class="mb-6 p-4 bg-white rounded-2xl border border-gray-300 shadow-sm">
        <form method="get" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-2">
                <label for="class_id" class="block text-sm font-medium">Kelas</label>
                <select name="class_id" id="class_id" class="block w-full mt-1 text-sm rounded-lg border-gray-300" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($classes as $class): ?><option value="<?= $class['id'] ?>" <?= ($selected_class_id == $class['id']) ? 'selected' : '' ?>><?= esc($class['name']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="month" class="block text-sm font-medium">Bulan</label>
                <select name="month" id="month" class="block w-full mt-1 text-sm rounded-lg border-gray-300">
                    <?php for ($m = 1; $m <= 12; $m++): ?><option value="<?= $m ?>" <?= $selected_month == $m ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 10)) ?></option><?php endfor; ?>
                </select>
            </div>
            <div>
                <label for="year" class="block text-sm font-medium">Tahun</label>
                <select name="year" id="year" class="block w-full mt-1 text-sm rounded-lg border-gray-300">
                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?><option value="<?= $y ?>" <?= $selected_year == $y ? 'selected' : '' ?>><?= $y ?></option><?php endfor; ?>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-lg hover:bg-sky-700 h-10">Tampilkan</button>
        </form>
    </div> -->

  <div class="w-full overflow-hidden rounded-2xl border border-gray-300">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead>
          <tr class="text-xs font-semibold text-center text-gray-500 uppercase border-b bg-gray-50">
            <th class="px-4 py-3 text-left sticky left-0 bg-gray-100 z-10">Nama Siswa</th>
            <?php if (!empty($pivotedData)):
              foreach ($dateHeaders as $date): ?>
                <th class="px-2 py-3"><?= date('d M', strtotime($date)) ?></th>
              <?php endforeach; endif; ?>
          </tr>
        </thead>
        <tbody class="bg-white divide-y">
          <?php if (!empty($pivotedData)): foreach ($pivotedData as $student_id => $student): ?>
              <tr class="text-gray-700">
                <td class="px-4 py-3 text-sm font-semibold sticky left-0 bg-white">
                  <a href="<?= site_url('admin/laporan/siswa/' . $student_id) ?>" title="Lihat Laporan"
                    class="hover:underline text-sky-600"><?= esc($student['full_name']) ?>
                  </a>
                </td>
                <?php foreach ($dateHeaders as $date): ?>
                  <td class="px-2 py-3 text-center">
                    <?php if (isset($student['daily_activities'][$date])):
                      $day_data = $student['daily_activities'][$date];
                      ?>
                      <button
                        @click="open('<?= esc($student['full_name']) ?>', '<?= date('d M Y', strtotime($date)) ?>', '<?= htmlspecialchars(json_encode($day_data['details']), ENT_QUOTES) ?>')"
                        class="px-2 py-1 text-sm font-bold text-sky-700 bg-sky-100 rounded-md hover:bg-sky-200">
                        <?= $day_data['count'] ?>
                      </button>
                    <?php else: ?>
                      <span class="text-gray-300">0</span>
                    <?php endif; ?>
                  </td>
                <?php endforeach; ?>
              </tr>
            <?php endforeach; elseif ($selected_class_id): ?>
            <tr>
              <td colspan="<?= count($dateHeaders) + 1 ?>" class="text-center py-8 text-gray-500">Tidak ada data kegiatan
                untuk ditampilkan.</td>
            </tr>
          <?php else: ?>
            <tr>
              <td colspan="1" class="text-center py-8 text-gray-500">Silakan pilih kelas untuk menampilkan laporan.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div x-show="isOpen" @keydown.escape.window="close()"
    class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
    <div @click.away="close()" class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
      <div class="flex justify-between items-center border-b pb-3">
        <div>
          <h3 class="text-lg font-semibold" x-text="`Detail Kegiatan: ${studentName}`"></h3>
          <p class="text-sm text-gray-500" x-text="date"></p>
        </div>
        <button @click="close()" class="p-2 rounded-full hover:bg-gray-100 -mt-2 -mr-2">&times;</button>
      </div>
      <div class="mt-4 max-h-60 overflow-y-auto">
        <ul class="list-disc list-inside space-y-2 text-gray-700">
          <template x-for="activity in activities">
            <li x-text="activity"></li>
          </template>
        </ul>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  function activityModal() {
    return {
      isOpen: false,
      studentName: '',
      date: '',
      activities: [],
      open(studentName, date, activitiesJson) {
        this.studentName = studentName;
        this.date = date;
        // Perbaiki parsing JSON dari htmlspecialchars
        this.activities = JSON.parse(activitiesJson.replace(/&quot;/g, '"'));
        this.isOpen = true;
      },
      close() {
        this.isOpen = false;
      }
    }
  }
</script>
<?= $this->endSection() ?>