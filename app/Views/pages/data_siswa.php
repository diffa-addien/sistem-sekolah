<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>
Data Siswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mx-auto">
  <div class="bg-white rounded-lg shadow-lg">
    <div class="bg-blue-600 text-white p-4 rounded-t-lg">
      <h3 class="text-center text-2xl font-bold">Data Siswa</h3>
    </div>

    <div class="p-4">
      <?php if (session()->getFlashdata('message')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
          <span class="block sm:inline"><?= session()->getFlashdata('message'); ?></span>
        </div>
      <?php elseif (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
          <span class="block sm:inline"><?= session()->getFlashdata('error'); ?></span>
        </div>
      <?php endif; ?>

      <div class="flex justify-between items-center mb-4">
        <a href="<?= site_url('/tambah-siswa'); ?>" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out">
          + Tambah Siswa
        </a>
        <input type="text" id="searchInput" class="shadow appearance-none border rounded w-1/4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Search...">
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
          <thead class="bg-gray-800 text-white">
            <tr>
              <th class="text-left py-3 px-4 uppercase font-semibold text-sm">#</th>
              <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama</th>
              <th class="text-left py-3 px-4 uppercase font-semibold text-sm">NIS</th>
              <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kelas</th>
              <th class="text-left py-3 px-4 uppercase font-semibold text-sm">UID</th>
              <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
            </tr>
          </thead>
          <tbody id="tableBody" class="text-gray-700">
            <tr class="hover:bg-gray-100 border-b border-gray-200">
                <td class="py-3 px-4">1</td>
                <td class="py-3 px-4">Budi Santoso</td>
                <td class="py-3 px-4">12345</td>
                <td class="py-3 px-4">XII RPL 1</td>
                <td class="py-3 px-4">AB-CD-EF-12</td>
                <td class="py-3 px-4">
                    <button class="text-blue-500 hover:text-blue-700">Edit</button>
                    <button class="text-red-500 hover:text-red-700 ml-2">Hapus</button>
                </td>
            </tr>
            <tr class="bg-gray-50 hover:bg-gray-100 border-b border-gray-200">
                <td class="py-3 px-4">2</td>
                <td class="py-3 px-4">Ani Yudhoyono</td>
                <td class="py-3 px-4">12346</td>
                <td class="py-3 px-4">XII RPL 1</td>
                <td class="py-3 px-4">34-56-78-AB</td>
                 <td class="py-3 px-4">
                    <button class="text-blue-500 hover:text-blue-700">Edit</button>
                    <button class="text-red-500 hover:text-red-700 ml-2">Hapus</button>
                </td>
            </tr>
            </tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
      <div class="flex justify-between items-center pb-3 border-b">
        <h5 class="text-xl font-bold text-gray-900">Edit Data Siswa</h5>
        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-bs-dismiss="modal" onclick="document.getElementById('editModal').classList.add('hidden')">
           <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
      </div>
      <div class="mt-4">
        <form id="editSiswaForm">
          <input type="hidden" id="edit_id" name="id">
          <div class="mb-4">
            <label for="edit_nama" class="block text-gray-700 text-sm font-bold mb-2">Nama</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit_nama" name="nama" required>
          </div>
          <div class="mb-4">
            <label for="edit_nis" class="block text-gray-700 text-sm font-bold mb-2">NIS</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit_nis" name="nis" required>
          </div>
          <div class="mb-4">
            <label for="edit_kelas" class="block text-gray-700 text-sm font-bold mb-2">Kelas</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit_kelas" name="kelas" required>
          </div>
          <div class="mb-4">
            <label for="edit_uid" class="block text-gray-700 text-sm font-bold mb-2">UID</label>
            <div class="flex">
              <input type="text" class="shadow appearance-none border rounded-l w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit_uid" name="uid" required>
              <button type="button" id="scanUidBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r">
                Scan Kartu Baru
              </button>
            </div>
             <div id="uidMessage" class="text-red-500 text-xs italic mt-1"></div>
          </div>
          <div class="flex items-center justify-end mt-6">
            <button type="button" id="cancelEditBtn" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2" onclick="document.getElementById('editModal').classList.add('hidden')">
              Batal
            </button>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>