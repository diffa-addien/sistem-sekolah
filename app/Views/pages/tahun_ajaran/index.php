<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Manajemen Tahun Ajaran
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-4">
  <h2 class="text-2xl font-semibold text-gray-700">Manajemen Tahun Ajaran</h2>
  <a href="<?= site_url('admin/tahun-ajaran/new') ?>"
    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
    Tambah Tahun Ajaran
  </a>
</div>

<div class="w-full overflow-hidden rounded-lg shadow-xs">
  <div class="w-full overflow-x-auto">
    <table id="myTable" class="w-full whitespace-no-wrap">
      <thead>
        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
          <th class="px-4 py-3">No</th>
          <th class="px-4 py-3">Tahun Ajaran</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Aksi</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y">
        <?php foreach ($academicYears as $key => $item): ?>
          <tr class="text-gray-700">
            <td class="px-4 py-3 text-sm">
              <?= $key + 1 ?>
            </td>
            <td class="px-4 py-3 text-sm">
              <?= esc($item['year']) ?>
            </td>
            <td class="px-4 py-3 text-xs">
              <?php if ($item['status'] == 'Aktif'): ?>
                <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
                  Aktif
                </span>
              <?php else: ?>
                <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full">
                  Tidak Aktif
                </span>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3 text-sm">
              <div class="flex items-center space-x-3">
                <a href="<?= site_url('admin/tahun-ajaran/' . $item['id'] . '/edit') ?>"
                  class="group relative p-2 text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 rounded-full transition-all duration-200 ease-in-out focus:ring-2 focus:ring-blue-300 focus:outline-none"
                  aria-label="Edit" title="Edit Data">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  <span
                    class="absolute hidden group-hover:block -top-10 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2">
                    Edit
                  </span>
                </a>

                <form action="<?= site_url('admin/tahun-ajaran/' . $item['id']) ?>" method="post" class="inline"
                  onsubmit="return confirm('Yakin hapus data ini?');">
                  <?= csrf_field() ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit"
                    class="group relative p-2 text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 rounded-full transition-all duration-200 ease-in-out focus:ring-2 focus:ring-red-300 focus:outline-none"
                    aria-label="Delete" title="Hapus Data">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span class="absolute hidden group-hover:block -top-10 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2">
                      Hapus
                    </span>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  $(document).ready(function () {
    $('#myTable').DataTable();
  });
</script>
<?= $this->endSection() ?>