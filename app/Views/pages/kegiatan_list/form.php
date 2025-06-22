<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Form Nama Kegiatan
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-xl mx-auto px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">
        <?= isset($activityName) ? 'Form Edit Nama Kegiatan' : 'Form Tambah Nama Kegiatan' ?>
    </h2>

    <?php if (session('errors')) : ?>
        <?= validation_list_errors('my_list') ?>
    <?php endif; ?>

    <form action="<?= isset($activityName) ? site_url('admin/nama-kegiatan/' . $activityName['id']) : site_url('admin/nama-kegiatan') ?>" method="post">
        <?= csrf_field() ?>
        <?php if (isset($activityName)) : ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="mb-4">
            <label for="name" class="block mb-2 text-sm font-medium">Nama Kegiatan</label>
            <input type="text" name="name" value="<?= old('name', $activityName['name'] ?? '') ?>" class="input-field" required>
        </div>

        <div class="mb-6">
            <label class="block mb-2 text-sm font-medium">Tipe</label>
            <?php $type = old('type', $activityName['type'] ?? 'Sekolah'); ?>
            <div class="flex items-center space-x-6">
                <div class="flex items-center">
                    <input type="radio" value="Sekolah" name="type" class="radio-field" <?= $type == 'Sekolah' ? 'checked' : '' ?> required>
                    <label class="ms-2 text-sm font-medium">Sekolah</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" value="Rumah" name="type" class="radio-field" <?= $type == 'Rumah' ? 'checked' : '' ?>>
                    <label class="ms-2 text-sm font-medium">Rumah</label>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-2 mt-6 border-t pt-4">
            <a href="<?= site_url('admin/nama-kegiatan') ?>" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary"><?= isset($activityName) ? 'Simpan Perubahan' : 'Simpan' ?></button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>