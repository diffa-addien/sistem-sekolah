<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Input Kehadiran Siswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h2 class="text-2xl font-semibold text-gray-700 mb-4">Input Kehadiran Siswa</h2>

<div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
  <form action="<?= site_url('admin/kehadiran') ?>" method="get">
    <div class="flex flex-wrap items-end space-x-4">
      <div class="flex-1">
        <label for="class_id" class="block text-sm font-medium text-gray-700">Pilih Kelas</label>
        <select name="class_id" id="class_id" class="input-field mt-1" required>
          <option value="">-- Silakan Pilih --</option>
          <?php foreach ($classes as $class) : ?>
            <option value="<?= $class['id'] ?>" <?= ($selected_class_id == $class['id']) ? 'selected' : '' ?>>
              <?= esc($class['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="flex-1">
        <label for="date" class="block text-sm font-medium text-gray-700">Pilih Tanggal</label>
        <input type="date" name="date" id="date" value="<?= esc($selected_date) ?>" class="input-field mt-1" required>
      </div>
      <div>
        <button type="submit" class="btn-primary">Tampilkan Siswa</button>
      </div>
    </div>
  </form>
</div>

<?php if (!empty($students)) : ?>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
    <form action="<?= site_url('admin/kehadiran/simpan') ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="class_id" value="<?= esc($selected_class_id) ?>">
      <input type="hidden" name="date" value="<?= esc($selected_date) ?>">

      <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
          <table class="w-full whitespace-no-wrap">
            <thead>
              <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                <th class="px-4 py-3">Nama Siswa</th>
                <th class="px-4 py-3 text-center">Status Kehadiran</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y">
              <?php foreach ($students as $student) : ?>
                <?php
                // Cek status yang sudah tersimpan, jika tidak ada, defaultnya KOSONG
                $current_status = $attendance_records[$student['id']]['status'] ?? '';
                ?>
                <tr class="text-gray-700">
                  <td class="px-4 py-3">
                    <div class="flex items-center text-sm">
                      <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                        <img class="object-cover w-full h-full rounded-full" src="<?= base_url('uploads/photos/' . ($student['photo'] ?? 'default.png')) ?>" alt="" loading="lazy" />
                      </div>
                      <div>
                        <p class="font-semibold"><?= esc($student['full_name']) ?></p>
                        <p class="text-xs text-gray-600"><?= esc($student['nis']) ?></p>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-sm">
                    <div class="flex items-center justify-center space-x-4">
                      <?php $statuses = ['Hadir', 'Sakit', 'Izin']; // Pilihan Alfa dihapus 
                      ?>
                      <?php foreach ($statuses as $status) : ?>
                        <label class="inline-flex items-center">
                          <input type="radio" class="radio-field" name="status[<?= $student['id'] ?>]" value="<?= $status ?>" <?= ($status === $current_status) ? 'checked' : '' ?>>
                          <span class="ml-2"><?= $status ?></span>
                        </label>
                      <?php endforeach; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="flex justify-end mt-6">
        <button type="submit" class="btn-primary">Simpan / Update Kehadiran</button>
      </div>
    </form>
  </div>
<?php endif; ?>

<style>
  .input-field {
    display: block;
    width: 100%;
    padding: 0.625rem;
    font-size: 0.875rem;
    color: #111827;
    background-color: #F9FAFB;
    border: 1px solid #D1D5DB;
    border-radius: 0.5rem
  }

  .input-field:focus {
    --tw-ring-color: #9333ea;
    border-color: #9333ea;
    box-shadow: var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color)
  }

  .radio-field {
    width: 1rem;
    height: 1rem;
    color: #9333ea;
    background-color: #F3F4F6;
    border-color: #D1D5DB
  }

  .btn-primary {
    padding-left: 1rem;
    padding-right: 1rem;
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: white;
    background-color: #9333ea;
    border-radius: 0.5rem
  }

  .btn-primary:hover {
    background-color: #7e22ce
  }
</style>
<?= $this->endSection() ?>