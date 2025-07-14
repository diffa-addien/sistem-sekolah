<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Form User Akun<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto p-6 bg-white rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">
        <?= isset($user) ? 'Edit User' : 'Tambah User' ?>
    </h2>

    <?php if (session('errors')) : ?>
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            <?= validation_list_errors('my_list') ?>
        </div>
    <?php endif; ?>

    <form action="<?= isset($user) ? site_url('admin/user/' . $user['id']) : site_url('admin/user') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if (isset($user)) : ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>

        <div class="mb-5">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input type="text" name="name" value="<?= old('name', $user['name'] ?? '') ?>" class="w-full p-3 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50" required>
        </div>
        <div class="mb-5">
            <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" value="<?= old('username', $user['username'] ?? '') ?>" class="w-full p-3 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50" required>
        </div>
        <div class="mb-5">
            <label for="role" class="block mb-2 text-sm font-medium text-gray-700">Role</label>
            <?php $selectedRole = old('role', $user['role'] ?? ''); ?>
            <select name="role" class="w-full p-3 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50" required>
                <option value="">-- Pilih Role --</option>
                <option value="Admin" <?= $selectedRole == 'Admin' ? 'selected' : '' ?>>Admin</option>
                <option value="Guru" <?= $selectedRole == 'Guru' ? 'selected' : '' ?>>Guru</option>
                <option value="Wali Murid" <?= $selectedRole == 'Wali Murid' ? 'selected' : '' ?>>Wali Murid</option>
            </select>
        </div>
        <div class="mb-5">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
            <input type="password" autocomplete="new-password" name="password" class="w-full p-3 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50" placeholder="<?= isset($user) ? 'Kosongkan jika tidak ingin mengubah' : '' ?>">
            <?php if(isset($user)): ?><p class="mt-1 text-xs text-gray-500">Minimal 8 karakter.</p><?php endif; ?>
        </div>
        <div class="mb-5">
            <label for="pass_confirm" class="block mb-2 text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <input type="password" autocomplete="new-password" name="pass_confirm" class="w-full p-3 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
        </div>
        <div class="mb-5">
            <label class="block mb-2 text-sm font-medium text-gray-700" for="photo">Foto</label>
            <?php if(isset($user) && $user['photo']): ?>
                <img class="h-20 w-20 object-cover rounded-full mb-3 border border-gray-200" src="<?= base_url('Uploads/photos/' . $user['photo']) ?>" alt="Current Photo">
            <?php endif; ?>
            <input class="w-full text-sm text-gray-900 border border-gray-200 rounded-lg cursor-pointer bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" name="photo" type="file" accept="image/png, image/jpeg, image/jpg">
            <p class="mt-1 text-xs text-gray-500">PNG, JPG atau JPEG (MAX. 1MB).</p>
        </div>

        <div class="flex justify-end space-x-3 mt-6 border-t pt-5">
            <a href="<?= site_url('admin/user') ?>" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition"><?= isset($user) ? 'Simpan Perubahan' : 'Simpan' ?></button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>