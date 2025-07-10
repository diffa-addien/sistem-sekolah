<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Dashboard Wali Murid
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="w-full max-w-7xl mx-auto">
  <h2 class="text-2xl font-semibold text-gray-700 mb-4">
    Selamat Datang, <?= esc(session()->get('name')) ?>!
  </h2>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white p-4 py-8 rounded-lg shadow-md flex items-center space-x-4">
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
        <p class="text-xl font-bold text-gray-800"><?= esc($student['full_name']) ?></p>
        <p class="text-sm text-gray-600">NIS: <?= esc($student['nis']) ?></p>
        <p class="text-sm text-gray-600">Kelas: <?= esc($student['class_name'] ?? 'Belum ada kelas') ?></p>
      </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-md">
      <h3 class="text-md font-semibold text-gray-700 border-b pb-1 mb-3">Kehadiran Hari Ini</h3>
      <?php if ($todays_attendance):
        $status = $todays_attendance['status'];
        $color = 'bg-gray-100 text-gray-800';
        if ($status == 'Hadir')
          $color = 'bg-green-100 text-green-800';
        if ($status == 'Sakit')
          $color = 'bg-yellow-100 text-yellow-800';
        if ($status == 'Izin')
          $color = 'bg-blue-100 text-blue-800';
        ?>
        <div class="text-center">
          <span class="px-4 py-1 text-sm font-bold rounded-full <?= $color ?>"><?= esc($status) ?></span>
        </div>
        <div class="mt-4 text-sm text-center text-gray-600 space-y-1">
          <?php if ($todays_attendance['check_in_time']): ?>
            <p><strong>Masuk:</strong> Pukul <?= date('H:i', strtotime($todays_attendance['check_in_time'])) ?> WIB</p>
          <?php endif; ?>
          <?php if ($todays_attendance['check_out_time']): ?>
            <p><strong>Pulang:</strong> Pukul <?= date('H:i', strtotime($todays_attendance['check_out_time'])) ?> WIB</p>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="text-center p-4">
          <span class="px-4 py-1 text-sm font-bold rounded-full bg-yellow-100 text-yellow-800">Tanpa Keterangan</span>
          <p class="text-xs text-gray-500 mt-2">Belum ada data kehadiran tercatat hari ini.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

</div>

<a href="<?= site_url('wali/kegiatan-harian') ?>"
  class="block mb-6 p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
  <div class="flex items-center justify-between">

    <div class="ml-4">
      <p class="text-lg font-semibold text-gray-700">Ceklist Kegiatan Harian</p>
      <p class="text-sm text-gray-500">Isi checklist kegiatan harian anak</p>
    </div>
    <div class="p-3 bg-purple-100 rounded-full">
      <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
        </path>
      </svg>
    </div>
  </div>

</a>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

  <a href="<?= site_url('wali/laporan-kegiatan') ?>"
    class="block p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
    <div class="flex items-center">
      <div class="p-3 bg-blue-100 rounded-full">
        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
          </path>
        </svg>
      </div>
      <div class="ml-4">
        <p class="text-lg font-semibold text-gray-700">Lihat Laporan Kehadiran</p>
        <p class="text-sm text-gray-500">Lihat rekap semua Kehadiran anak</p>
      </div>
    </div>
  </a>

    <a href="<?= site_url('wali/laporan-kegiatan') ?>"
    class="block p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
    <div class="flex items-center">
      <div class="p-3 bg-blue-100 rounded-full">
        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
          </path>
        </svg>
      </div>
      <div class="ml-4">
        <p class="text-lg font-semibold text-gray-700">Lihat Laporan Kegiatan</p>
        <p class="text-sm text-gray-500">Lihat rekap semua kegiatan anak</p>
      </div>
    </div>
  </a>
</div>
</div>
<?= $this->endSection() ?>