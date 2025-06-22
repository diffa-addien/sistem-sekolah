<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Form Kegiatan Siswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-xl mx-auto px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
  <h2 class="text-2xl font-semibold text-gray-700 mb-4">
    <?= isset($activity) ? 'Form Edit Kegiatan' : 'Form Catat Kegiatan' ?>
  </h2>

  <?php if (session('errors')) : ?>
    <?= validation_list_errors('my_list') ?>
  <?php endif; ?>

  <form action="<?= isset($activity) ? site_url('admin/kegiatan/' . $activity['id']) : site_url('admin/kegiatan') ?>" method="post">
    <?= csrf_field() ?>
    <?php if (isset($activity)) : ?>
      <input type="hidden" name="_method" value="PUT">
    <?php endif; ?>

    <div class="mb-4">
      <label for="student_id" class="block mb-2 text-sm font-medium">Siswa</label>
      <select name="student_id" class="input-field" required>
        <option value="">-- Pilih Siswa --</option>
        <?php $selected = old('student_id', $activity['student_id'] ?? ''); ?>
        <?php foreach ($students as $student) : ?>
          <option value="<?= $student['id'] ?>" <?= $selected == $student['id'] ? 'selected' : '' ?>><?= esc($student['full_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-4">
      <label for="activity_name_id" class="block mb-2 text-sm font-medium">Jenis Kegiatan</label>
      <select name="activity_name_id" class="input-field" required>
        <option value="">-- Pilih Kegiatan --</option>
        <?php $selected = old('activity_name_id', $activity['activity_name_id'] ?? ''); ?>
        <?php foreach ($activity_names as $item) : ?>
          <option value="<?= $item['id'] ?>" <?= $selected == $item['id'] ? 'selected' : '' ?>><?= esc($item['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-4">
      <label for="activity_date" class="block mb-2 text-sm font-medium">Tanggal Kegiatan</label>
      <input type="date" name="activity_date" value="<?= old('activity_date', $activity['activity_date'] ?? date('Y-m-d')) ?>" class="input-field" required>
    </div>
    <div class="mb-4">
      <label for="description" class="block mb-2 text-sm font-medium">Deskripsi / Catatan</label>
      <textarea name="description" rows="4" class="input-field" required><?= old('description', $activity['description'] ?? '') ?></textarea>
    </div>

    <div class="flex justify-end space-x-2 mt-6 border-t pt-4">
      <a href="<?= site_url('admin/kegiatan') ?>" class="btn-secondary">Batal</a>
      <button type="submit" class="btn-primary"><?= isset($activity) ? 'Simpan Perubahan' : 'Simpan' ?></button>
    </div>
  </form>
</div>
<style>
</style>
<?= $this->endSection() ?>