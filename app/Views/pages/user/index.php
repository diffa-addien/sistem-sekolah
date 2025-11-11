<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Manajemen Akun Pengguna<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-semibold text-gray-700">Manajemen Akun Pengguna</h2>
    <a href="<?= site_url('admin/user/new') ?>"
        class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        Tambah User
    </a>
</div>

<div class="mb-4 p-4 bg-white rounded-lg shadow-xs">
    <form action="<?= site_url('admin/user') ?>" method="get">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
            <div class="md:col-span-1">
                <label for="role" class="block text-sm font-medium text-gray-700">Filter Role</label>
                <select name="role" id="role" class="block w-full mt-1 p-3 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300">
                    <option value="">Semua Role</option>
                    <option value="Admin" <?= ($selected_role == 'Admin') ? 'selected' : '' ?>>Admin</option>
                    <option value="Guru" <?= ($selected_role == 'Guru') ? 'selected' : '' ?>>Guru</option>
                    <option value="Wali Murid" <?= ($selected_role == 'Wali Murid') ? 'selected' : '' ?>>Wali Murid</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700">Cari Pengguna</label>
                <div class="flex space-x-2 mt-1">
                    <input type="text" name="search" id="search" placeholder="Masukkan Nama atau Username..." value="<?= esc($search_keyword ?? '') ?>" class="block w-full px-3 text-sm rounded-lg border-gray-300 bg-gray-50 focus:border-purple-400 focus:ring focus:ring-purple-300">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">Cari</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="w-full overflow-hidden rounded-lg shadow-xs">
    <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Username</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                <?php foreach ($users as $user): ?>
                    <tr class="text-gray-700">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div class="relative hidden w-10 h-10 mr-3 rounded-full md:block">
                                    <?php if (!empty($user['photo']) && file_exists(FCPATH . 'uploads/photos/' . $user['photo'])): ?>
                                        <img class="object-cover w-full h-full rounded-full shadow-md"
                                            src="<?= base_url('uploads/photos/' . esc($user['photo'])) ?>"
                                            alt="Foto Profil"
                                            loading="lazy"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center rounded-full bg-purple-700 text-white text-sm font-semibold shadow-md">
                                            <?= esc(strtoupper(substr($user['name'], 0, 1))) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="hidden absolute inset-0 flex items-center justify-center rounded-full bg-purple-700 text-white text-sm font-semibold">
                                        <?= esc(strtoupper(substr($user['name'], 0, 1))) ?>
                                    </div>
                                </div>
                                <div>
                                    <p class="font-semibold"><?= esc($user['name']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm"><?= esc($user['username']) ?></td>
                        <td class="px-4 py-3 text-sm"><?= esc($user['role']) ?></td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center space-x-4">
                                <a href="<?= site_url('admin/user/' . $user['id'] . '/edit') ?>" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg" aria-label="Edit">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </a>
                                <form action="<?= site_url('admin/user/' . $user['id']) ?>" method="post" class="inline" onsubmit="return confirm('Apakah Anda yakin?');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg" aria-label="Delete">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 px-4 py-3">
        <?= $pager->links('users', 'tailwind') ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // DataTables dinonaktifkan untuk menggunakan paginasi dari server
</script>
<?= $this->endSection() ?>