<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Manajemen Akun Saya<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-300 p-6 sm:p-8">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6">
            Pengaturan Akun Saya
        </h2>

        <form action="<?= site_url('akun/update') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-700">Role</label>
                <input type="text" value="<?= esc($user['role']) ?>" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-100 py-3 px-4 text-gray-600" readonly>
            </div>
            <div class="mb-6">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" value="<?= old('name', $user['name'] ?? '') ?>" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 py-3 px-4 focus:border-purple-500 focus:ring focus:ring-purple-200 transition-all duration-200" required>
            </div>
            <div class="mb-6">
                <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" value="<?= old('username', $user['username'] ?? '') ?>" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 py-3 px-4 focus:border-purple-500 focus:ring focus:ring-purple-200 transition-all duration-200" required>
            </div>
            <hr class="my-8 border-gray-200">
            <p class="text-sm text-gray-600 mb-4">Ubah Password (kosongkan jika tidak ingin mengubah)</p>
            <div class="mb-6">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password Baru</label>
                <input type="password" name="password" autocomplete="new-password" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 py-3 px-4 focus:border-purple-500 focus:ring focus:ring-purple-200 transition-all duration-200">
            </div>
            <div class="mb-6">
                <label for="pass_confirm" class="block mb-2 text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                <input type="password" name="pass_confirm" autocomplete="new-password" class="block w-full mt-1 text-sm rounded-lg border-gray-300 bg-gray-50 py-3 px-4 focus:border-purple-500 focus:ring focus:ring-purple-200 transition-all duration-200">
            </div>
            <hr class="my-8 border-gray-200">
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-700" for="photo">Ganti Foto</label>
                <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 mb-2">
                    <img class="h-16 w-16 object-cover rounded-full" src="<?= base_url('Uploads/photos/' . ($user['photo'] ?? 'default.png')) ?>" alt="Current Photo">
                    <input class="block w-full sm:w-auto text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 py-3 px-4 focus:outline-none focus:border-purple-500 transition-all duration-200" name="photo" type="file" accept="image/png, image/jpeg, image/jpg">
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <button type="submit" class="px-6 py-3 text-sm font-medium leading-5 text-white transition-colors duration-200 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-300">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>