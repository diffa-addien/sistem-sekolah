<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Form User
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-xl mx-auto px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">
        <?= isset($user) ? 'Form Edit User' : 'Form Tambah User' ?>
    </h2>

    <?php if (session('errors')) : ?>
        <?= validation_list_errors('my_list') ?>
    <?php endif; ?>

    <form action="<?= isset($user) ? site_url('admin/user/' . $user['id']) : site_url('admin/user') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if (isset($user)) : ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="mb-4">
            <label for="name" class="block mb-2 text-sm font-medium">Nama Lengkap</label>
            <input type="text" name="name" value="<?= old('name', $user['name'] ?? '') ?>" class="input-field" required>
        </div>
        <div class="mb-4">
            <label for="username" class="block mb-2 text-sm font-medium">Username</label>
            <input type="text" name="username" value="<?= old('username', $user['username'] ?? '') ?>" class="input-field" required>
        </div>
        <div class="mb-4">
            <label for="role" class="block mb-2 text-sm font-medium">Role</label>
            <?php $selectedRole = old('role', $user['role'] ?? ''); ?>
            <select name="role" class="input-field" required>
                <option value="">-- Pilih Role --</option>
                <option value="Admin" <?= $selectedRole == 'Admin' ? 'selected' : '' ?>>Admin</option>
                <option value="Guru" <?= $selectedRole == 'Guru' ? 'selected' : '' ?>>Guru</option>
                <option value="Wali Murid" <?= $selectedRole == 'Wali Murid' ? 'selected' : '' ?>>Wali Murid</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="password" class="block mb-2 text-sm font-medium">Password</label>
            <input type="password" name="password" class="input-field" placeholder="<?= isset($user) ? 'Kosongkan jika tidak ingin mengubah' : '' ?>">
            <?php if(isset($user)): ?><p class="mt-1 text-xs text-gray-500">Minimal 8 karakter.</p><?php endif; ?>
        </div>
        <div class="mb-4">
            <label for="pass_confirm" class="block mb-2 text-sm font-medium">Konfirmasi Password</label>
            <input type="password" name="pass_confirm" class="input-field">
        </div>
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium" for="photo">Foto</label>
            <?php if(isset($user) && $user['photo']): ?>
                <img class="h-16 w-16 object-cover rounded-full mb-2" src="<?= base_url('uploads/photos/' . $user['photo']) ?>" alt="Current Photo">
            <?php endif; ?>
            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" name="photo" type="file" accept="image/png, image/jpeg, image/jpg">
            <p class="mt-1 text-xs text-gray-500">PNG, JPG atau JPEG (MAX. 1MB).</p>
        </div>

        <div class="flex justify-end space-x-2 mt-6 border-t pt-4">
            <a href="<?= site_url('admin/user') ?>" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary"><?= isset($user) ? 'Simpan Perubahan' : 'Simpan' ?></button>
        </div>
    </form>
</div>

<style> .input-field{display:block;width:100%;padding:0.625rem;font-size:0.875rem;color:#111827;background-color:#F9FAFB;border:1px solid #D1D5DB;border-radius:0.5rem}.input-field:focus{--tw-ring-color:#9333ea;border-color:#9333ea;box-shadow:var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color)}.radio-field{width:1rem;height:1rem;color:#9333ea;background-color:#F3F4F6;border-color:#D1D5DB}.btn-primary{padding-left:1rem;padding-right:1rem;padding-top:0.5rem;padding-bottom:0.5rem;font-size:0.875rem;font-weight:500;color:white;background-color:#9333ea;border-radius:0.5rem}.btn-primary:hover{background-color:#7e22ce}.btn-secondary{padding-left:1rem;padding-right:1rem;padding-top:0.5rem;padding-bottom:0.5rem;font-size:0.875rem;font-weight:500;color:#1f2937;background-color:#E5E7EB;border-radius:0.5rem}.btn-secondary:hover{background-color:#D1D5DB} </style>
<?= $this->endSection() ?>