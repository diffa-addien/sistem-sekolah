<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Laporan Kegiatan <?= esc($student['full_name']) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php $role = session()->get('role'); ?>
<style>
  .table-container {
    max-width: 100%;
    overflow-x: scroll;
  }

  .custom-table {
    width: 100%;
    /* min-width: 800px; Prevent excessive shrinking */
    table-layout: auto;
    /* Allow natural column sizing */
  }

  .custom-table th,
  .custom-table td {
    min-width: 50px;
    /* Adjust for better spacing */
    white-space: nowrap;
    /* Prevent text wrapping */
  }

  .custom-table th:first-child,
  .custom-table td:first-child {
    position: sticky;
    left: 0;
    background: #f9fafb;
    z-index: 10;
    width: 250px;
    /* Wider sticky column */
  }
</style>
<div class="container mx-auto px-4 py-6 overflow-x-auto">
  <div class="flex items-center space-x-4 mb-6">
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
      <div class="flex items-center justify-center h-20 w-20 rounded-full bg-blue-500 text-white text-4xl font-semibold">
        <?= esc(substr($student['full_name'], 0, 1)) ?>
      </div>
    <?php endif; ?>
    <div>
      <h2 class="text-2xl font-semibold text-gray-700">Laporan Rekap Kegiatan</h2>
      <p class="text-lg text-gray-800 font-bold"><?= esc($student['full_name']) ?></p>
    </div>
  </div>
  <div class="flex justify-end mb-4">
    <a href="<?= $role == "Wali Murid" ? base_url('wali/dashboard'): base_url('admin/laporan/kegiatan')?>"
      class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-200">
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
        </path>
      </svg>
      Kembali
    </a>
  </div>
  <div class="mb-4 p-4 bg-white rounded-lg shadow-xs">
    <form method="get" class="flex flex-wrap items-end gap-4">
      <div>
        <label for="start_date" class="block text-sm font-medium">Dari Tanggal</label>
        <input type="date" name="start_date" value="<?= esc($start_date) ?>"
          class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50">
      </div>
      <div>
        <label for="end_date" class="block text-sm font-medium">Sampai Tanggal</label>
        <input type="date" name="end_date" value="<?= esc($end_date) ?>"
          class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50">
      </div>
      <button type="submit"
        class="px-4 py-2 text-sm font-medium text-white bg-purple-600 border-transparent rounded-lg hover:bg-purple-700">Filter</button>
    </form>
  </div>
  <div class="table-container w-full rounded-lg shadow-xs">
    <div class="w-full overflow-x-auto">
      <table class="custom-table w-full whitespace-no-wrap">
        <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b bg-gray-50">
            <th class="px-4 py-3 text-left">Nama Kegiatan</th>
            <?php foreach ($dateHeaders as $date): ?>
              <th class="px-3 py-3"><?= date('d M', strtotime($date)) ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody class="bg-white divide-y">
          <?php if (!empty($activity_names)): ?>
            <?php foreach ($activity_names as $activity): ?>
              <tr class="text-gray-700">
                <td class="px-4 py-3 text-sm font-semibold"><?= esc($activity['name']) ?></td>
                <?php foreach ($dateHeaders as $date): ?>
                  <td class="px-3 py-3 text-center">
                    <?php if (isset($processed_records[$activity['id']][$date])): ?>
                      <span class="text-green-500 font-bold text-lg">✓</span>
                    <?php else: ?>
                      <span class="text-gray-300">-</span>
                    <?php endif; ?>
                  </td>
                <?php endforeach; ?>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="<?= count($dateHeaders) + 1 ?>" class="text-center py-4 text-gray-500">Tidak ada data kegiatan.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?= $this->endSection() ?>