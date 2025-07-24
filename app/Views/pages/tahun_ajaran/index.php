<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Manajemen Tahun Ajaran<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-4">
  <h2 class="text-2xl font-semibold text-gray-700">Manajemen Tahun Ajaran</h2>
  <a href="<?= site_url('admin/tahun-ajaran/new') ?>"
    class="px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-lg hover:bg-sky-700">
    Tambah T.A. Baru
  </a>
</div>

<div class="mb-6 p-4 bg-white rounded-2xl border border-gray-300 shadow-sm">
  <form action="<?= site_url('admin/tahun-ajaran') ?>" method="get">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
      <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Filter Status</label>
        <select name="status" id="status" class="px-4 py-2 ring-1 block w-full mt-1 text-sm rounded-lg border-gray-300">
          <option value="">Semua Status</option>
          <option value="Aktif" <?= ($selected_status == 'Aktif') ? 'selected' : '' ?>>Aktif</option>
          <option value="Tidak Aktif" <?= ($selected_status == 'Tidak Aktif') ? 'selected' : '' ?>>Tidak Aktif</option>
        </select>
      </div>
      <div>
        <label for="search" class="block text-sm font-medium text-gray-700">Cari Tahun Ajaran</label>
        <div class="flex space-x-2 mt-1">
          <input type="text" name="search" id="search" placeholder="Contoh: 2025/2026"
            value="<?= esc($search_keyword ?? '') ?>" class="block ring-1 w-full px-4 text-sm rounded-lg border-gray-300">
          <button type="submit"
            class="px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-lg hover:bg-sky-700">Cari</button>
        </div>
      </div>
    </div>
  </form>
</div>

<div class="w-full overflow-hidden rounded-2xl border border-gray-300">
  <div class="w-full overflow-x-auto">
    <table class="w-full whitespace-no-wrap">
      <thead>
        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
          <th class="px-4 py-3">Tahun Ajaran</th>
          <th class="px-4 py-3">Tanggal Mulai</th>
          <th class="px-4 py-3">Tanggal Selesai</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Aksi</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y">
        <?php foreach ($academicYears as $item): ?>
          <tr class="text-gray-700">
            <td class="px-4 py-3 text-sm font-semibold"><?= esc($item['year']) ?></td>
            <td class="px-4 py-3 text-sm"><?= date('d M Y', strtotime($item['start_date'])) ?></td>
            <td class="px-4 py-3 text-sm"><?= date('d M Y', strtotime($item['end_date'])) ?></td>
            <td class="px-4 py-3 text-xs">
              <?php if ($item['status'] == 'Aktif'): ?>
                <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Aktif</span>
              <?php else: ?>
                <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full">Tidak
                  Aktif</span>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3 text-sm">
              <div class="flex items-center space-x-4">
                <a href="<?= site_url('admin/tahun-ajaran/' . $item['id'] . '/edit') ?>" aria-label="Edit"
                  class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg"><svg
                    class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                    </path>
                  </svg></a>
                <form action="<?= site_url('admin/tahun-ajaran/' . $item['id']) ?>" method="post" class="inline"
                  onsubmit="return confirm('Apakah Anda yakin?');">
                  <?= csrf_field() ?><input type="hidden" name="_method" value="DELETE">
                  <button type="submit"
                    class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg"
                    aria-label="Delete"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z"
                        clip-rule="evenodd"></path>
                    </svg></button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="p-4"><?= $pager->links('academic_years', 'tailwind') ?></div>
</div>
<?= $this->endSection() ?>