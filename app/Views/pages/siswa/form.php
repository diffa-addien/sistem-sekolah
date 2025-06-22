<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Form Siswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">
        <?= isset($student) ? 'Form Edit Siswa' : 'Form Tambah Siswa' ?>
    </h2>

    <?php if (session('errors')) : ?>
        <?= validation_list_errors('my_list') ?>
    <?php endif; ?>

    <form action="<?= isset($student) ? site_url('admin/siswa/' . $student['id']) : site_url('admin/siswa') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if (isset($student)) : ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <label for="nis" class="block mb-2 text-sm font-medium">NIS</label>
                    <input type="text" name="nis" value="<?= old('nis', $student['nis'] ?? '') ?>" class="input-field" required>
                </div>
                <div class="mb-4">
                    <label for="full_name" class="block mb-2 text-sm font-medium">Nama Lengkap</label>
                    <input type="text" name="full_name" value="<?= old('full_name', $student['full_name'] ?? '') ?>" class="input-field" required>
                </div>
                <div class="mb-4">
                    <label for="birth_date" class="block mb-2 text-sm font-medium">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="<?= old('birth_date', $student['birth_date'] ?? '') ?>" class="input-field" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium">Jenis Kelamin</label>
                    <?php $gender = old('gender', $student['gender'] ?? ''); ?>
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center">
                            <input type="radio" value="Laki-laki" name="gender" class="radio-field" <?= $gender == 'Laki-laki' ? 'checked' : '' ?> required>
                            <label class="ms-2 text-sm font-medium">Laki-laki</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" value="Perempuan" name="gender" class="radio-field" <?= $gender == 'Perempuan' ? 'checked' : '' ?>>
                            <label class="ms-2 text-sm font-medium">Perempuan</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <div class="mb-4">
                    <label for="class_id" class="block mb-2 text-sm font-medium">Kelas</label>
                    <select name="class_id" class="input-field" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php $selectedClass = old('class_id', $student['class_id'] ?? ''); ?>
                        <?php foreach ($classes as $class) : ?>
                            <option value="<?= $class['id'] ?>" <?= $selectedClass == $class['id'] ? 'selected' : '' ?>>
                                <?= esc($class['name']) ?> (<?= esc($class['academic_year']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="user_id" class="block mb-2 text-sm font-medium">Tautkan Akun Wali Murid (Opsional)</label>
                    <select name="user_id" class="input-field">
                        <option value="">-- Tidak Ditautkan --</option>
                        <?php $selectedParent = old('user_id', $student['user_id'] ?? ''); ?>
                        <?php foreach ($parents as $parent) : ?>
                            <option value="<?= $parent['id'] ?>" <?= $selectedParent == $parent['id'] ? 'selected' : '' ?>>
                                <?= esc($parent['name']) ?> (<?= esc($parent['username']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium" for="photo">Ganti Foto (Opsional)</label>
                    <div class="flex items-center space-x-4 mb-2">
                        <img class="h-16 w-16 object-cover rounded-full" src="<?= base_url('uploads/photos/' . ($student['photo'] ?? 'default.png')) ?>" alt="Current Photo">
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" name="photo" type="file" accept="image/png, image/jpeg, image/jpg">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengganti foto. MAX 1MB.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-2 mt-6 border-t pt-4">
            <a href="<?= site_url('admin/siswa') ?>" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary"><?= isset($student) ? 'Simpan Perubahan' : 'Simpan' ?></button>
        </div>
    </form>
</div>

<style> .input-field{display:block;width:100%;padding:0.625rem;font-size:0.875rem;color:#111827;background-color:#F9FAFB;border:1px solid #D1D5DB;border-radius:0.5rem}.input-field:focus{--tw-ring-color:#9333ea;border-color:#9333ea;box-shadow:var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color)}.radio-field{width:1rem;height:1rem;color:#9333ea;background-color:#F3F4F6;border-color:#D1D5DB}.btn-primary{padding-left:1rem;padding-right:1rem;padding-top:0.5rem;padding-bottom:0.5rem;font-size:0.875rem;font-weight:500;color:white;background-color:#9333ea;border-radius:0.5rem}.btn-primary:hover{background-color:#7e22ce}.btn-secondary{padding-left:1rem;padding-right:1rem;padding-top:0.5rem;padding-bottom:0.5rem;font-size:0.875rem;font-weight:500;color:#1f2937;background-color:#E5E7EB;border-radius:0.5rem}.btn-secondary:hover{background-color:#D1D5DB} </style>
<?= $this->endSection() ?>