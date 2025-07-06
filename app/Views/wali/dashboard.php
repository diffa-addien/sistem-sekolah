<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Dashboard Wali Murid
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="w-full max-w-7xl mx-auto">
  <h2 class="text-2xl font-semibold text-gray-700 mb-4">
    Selamat Datang, <?= esc(session()->get('name')) ?>!
  </h2>

  <div class="mb-6 bg-white p-4 rounded-lg shadow-md flex items-center space-x-4">
    <img class="h-20 w-20 object-cover rounded-full" src="<?= base_url('uploads/photos/' . ($student['photo'] ?? 'default.png')) ?>" alt="Foto <?= esc($student['full_name']) ?>">
    <div>
      <p class="text-xl font-bold text-gray-800"><?= esc($student['full_name']) ?></p>
      <p class="text-sm text-gray-600">NIS: <?= esc($student['nis']) ?></p>
      <p class="text-sm text-gray-600">Kelas: <?= esc($student['class_name'] ?? 'Belum ada kelas') ?></p>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <a href="<?= site_url('wali/kegiatan-harian') ?>" class="block p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
      <div class="flex items-center">
        <div class="p-3 bg-purple-100 rounded-full">
          <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
          </svg>
        </div>
        <div class="ml-4">
          <p class="text-lg font-semibold text-gray-700">Laporan Kegiatan Harian</p>
          <p class="text-sm text-gray-500">Isi checklist kegiatan harian anak</p>
        </div>
      </div>
    </a>

    <div class="block p-6 bg-gray-100 border-2 border-dashed rounded-lg text-center">
      <p class="text-gray-500">Menu Laporan Kehadiran (segera hadir)</p>
    </div>
  </div>
</div>
<?= $this->endSection() ?>