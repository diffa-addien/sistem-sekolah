<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Input Kehadiran Siswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h2 class="text-2xl font-semibold text-gray-700 mb-4">Input Kehadiran Siswa</h2>

<div class="px-4 py-3 mb-8 bg-white rounded-2xl border border-gray-300 shadow-sm">
  <form action="<?= site_url('admin/kehadiran') ?>" method="get">
    <div class="flex flex-wrap items-end gap-4">
      <?php if(!$is_teacher): ?>
      <div class="flex-1">
        <label for="class_id" class="block text-sm font-medium text-gray-700">Pilih Kelas</label>
        <select name="class_id" id="class_id" class="block w-full mt-1 text-sm rounded-lg border-gray-300" required>
          <option value="">-- Silakan Pilih --</option>
          <?php foreach ($classes as $class) : ?>
            <option value="<?= $class['id'] ?>" <?= ($selected_class_id == $class['id']) ? 'selected' : '' ?>><?= esc($class['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>
      <div class="flex-1">
        <label for="date" class="block text-sm font-medium text-gray-700">Pilih Tanggal</label>
        <input type="date" name="date" id="date" value="<?= esc($selected_date) ?>" class="block w-full mt-1 text-sm rounded-lg border-gray-300" required>
      </div>
      <div>
        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-lg hover:bg-sky-700">Tampilkan Siswa</button>
      </div>
    </div>
  </form>
</div>

<?php if (!empty($students)) : ?>
  <div class="px-4 py-3 mb-8 bg-white rounded-2xl border border-gray-300 shadow-sm">
    <form action="<?= site_url('admin/kehadiran/simpan') ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="class_id" value="<?= esc($selected_class_id) ?>">
      <input type="hidden" name="date" value="<?= esc($selected_date) ?>">

      <div class="w-full overflow-hidden rounded-lg">
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
                <?php $current_status = $attendance_records[$student['id']]['status'] ?? ''; ?>
                <tr class="text-gray-700">
                  <td class="px-4 py-3">
                    <div class="flex items-center text-sm">
                      <div class="relative w-10 h-10 mr-3 rounded-full flex-shrink-0">
                        <img class="object-cover w-full h-full rounded-full" 
                             src="<?= base_url('uploads/photos/' . ($student['photo'] ?? 'default.png')) ?>" 
                             alt="Foto Profil" loading="lazy"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="hidden absolute inset-0 flex items-center justify-center rounded-full bg-sky-900 text-white text-lg font-semibold">
                            <?= esc(strtoupper(substr($student['full_name'], 0, 1))) ?>
                        </div>
                      </div>
                      <div>
                        <p class="font-semibold"><?= esc($student['full_name']) ?></p>
                        <p class="text-xs text-gray-600"><?= esc($student['nis']) ?></p>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-sm">
                    <div class="flex items-center justify-center space-x-2 sm:space-x-4">
                      <?php $statuses = ['Hadir', 'Sakit', 'Izin']; ?>
                      <?php foreach ($statuses as $status) : ?>
                        <label class="inline-flex items-center">
                          <input type="radio" class="w-4 h-4 text-sky-600" name="status[<?= $student['id'] ?>]" value="<?= $status ?>" <?= ($status === $current_status) ? 'checked' : '' ?>>
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
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-lg hover:bg-sky-700">Simpan / Update Kehadiran</button>
      </div>
    </form>
  </div>
<?php elseif($selected_class_id): ?>
  <div class="px-4 py-3 text-center bg-white rounded-2xl border border-gray-300 shadow-sm">
    <p class="text-gray-600">Tidak ada siswa yang terdaftar di kelas ini pada tahun ajaran aktif.</p>
  </div>
<?php endif; ?>

<?= $this->endSection() ?>