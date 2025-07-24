<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Form Siswa<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto px-6 py-8 bg-white rounded-2xl border border-gray-300 shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6">
        <?= isset($student) ? 'Form Edit Siswa' : 'Form Tambah Siswa' ?>
    </h2>

    <?php if (session('errors')): ?>
        <div class="px-4 py-3 mb-4 text-red-800 bg-red-100 border border-red-400 rounded-lg">
            <strong class="font-bold">Terdapat kesalahan:</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= isset($student) ? site_url('admin/siswa/' . $student['id']) : site_url('admin/siswa') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if (isset($student)): ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <label for="nis" class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                    <input type="text" name="nis" value="<?= old('nis', $student['nis'] ?? '') ?>" class="block w-full text-sm rounded-lg border-gray-300" required>
                </div>
                <div class="mb-4">
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="full_name" value="<?= old('full_name', $student['full_name'] ?? '') ?>" class="block w-full text-sm rounded-lg border-gray-300" required>
                </div>
                <div class="mb-4">
                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="<?= old('birth_date', $student['birth_date'] ?? '') ?>" class="block w-full text-sm rounded-lg border-gray-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <?php $gender = old('gender', $student['gender'] ?? ''); ?>
                    <div class="flex items-center space-x-6 mt-2">
                        <label class="flex items-center"><input type="radio" value="Laki-laki" name="gender" class="w-4 h-4 text-sky-600" <?= $gender == 'Laki-laki' ? 'checked' : '' ?> required><span class="ml-2 text-sm">Laki-laki</span></label>
                        <label class="flex items-center"><input type="radio" value="Perempuan" name="gender" class="w-4 h-4 text-sky-600" <?= $gender == 'Perempuan' ? 'checked' : '' ?>><span class="ml-2 text-sm">Perempuan</span></label>
                    </div>
                </div>
            </div>
            
            <div>
                <div class="mb-4">
                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Daftarkan ke Kelas (di T/A Aktif)</label>
                    <select name="class_id" class="block w-full mt-1 text-sm rounded-lg border-gray-300" <?= empty($classes) ? 'disabled' : '' ?>>
                        <option value="">-- Pilih Kelas --</option>
                        <?php $selectedClass = old('class_id', $current_enrollment['class_id'] ?? ''); ?>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['id'] ?>" <?= $selectedClass == $class['id'] ? 'selected' : '' ?>><?= esc($class['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                     <?php if (empty($classes)): ?><p class="text-xs text-red-500 mt-1">Tidak ada kelas di T/A aktif.</p><?php endif; ?>
                </div>
                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Tautkan Akun Wali Murid</label>
                    <select name="user_id" class="block w-full mt-1 text-sm rounded-lg border-gray-300">
                        <option value="">-- Tidak Ditautkan --</option>
                        <?php $selectedParent = old('user_id', $student['user_id'] ?? ''); ?>
                        <?php foreach ($parents as $parent): ?>
                             <option value="<?= $parent['id'] ?>" <?= $selectedParent == $parent['id'] ? 'selected' : '' ?>><?= esc($parent['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                 <div class="mb-4 p-3 border rounded-lg bg-gray-50">
                    <label for="card_uid" class="block mb-2 text-sm font-medium text-gray-700">UID Kartu RFID</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" id="card_uid" name="card_uid" value="<?= old('card_uid', $student['card_uid'] ?? '') ?>" class="block w-full text-sm rounded-lg border-gray-300 bg-gray-200" readonly placeholder="UID akan terisi otomatis...">
                        <button type="button" id="scan-rfid-btn" class="px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Scan</button>
                    </div>
                    <p id="scan-status" class="mt-1 text-xs text-gray-500">Klik tombol "Scan" lalu tap kartu pada alat.</p>
                </div>
                 <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="photo">Foto</label>
                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" name="photo" type="file" accept="image/png, image/jpeg, image/jpg">
                </div>
            </div>
        </div>
        <div class="flex justify-end space-x-2 mt-6 border-t pt-4">
            <a href="<?= site_url('admin/siswa') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-lg hover:bg-sky-700">Simpan</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let pollingInterval;
    const scanStatus = $('#scan-status');
    const uidInput = $('#card_uid');
    const scanButton = $('#scan-rfid-btn');

    scanButton.on('click', function() {
        scanStatus.text('Menunggu kartu, silakan tap...').removeClass('text-green-600 text-red-600');
        scanButton.prop('disabled', true).addClass('bg-gray-400');
        
        let attempts = 0;
        const maxAttempts = 20; // Berhenti setelah 20 detik
        const scanStartTime = Math.floor(Date.now() / 1000);

        pollingInterval = setInterval(function() {
            attempts++;
            $.ajax({
                url: "<?= site_url('api/check-scan') ?>",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success' && response.uid && response.timestamp >= scanStartTime) {
                        clearInterval(pollingInterval);
                        uidInput.val(response.uid);
                        scanStatus.text('Sukses! UID ditemukan.').addClass('text-green-600');
                        scanButton.prop('disabled', false).removeClass('bg-gray-400');
                    } else if (response.status === 'error' && response.message === 'UID sudah terdaftar' && response.timestamp >= scanStartTime) {
                        clearInterval(pollingInterval);
                        scanStatus.text('Error: UID ini sudah terdaftar!').addClass('text-red-600');
                        scanButton.prop('disabled', false).removeClass('bg-gray-400');
                    }
                },
                error: function() { console.error("Gagal menghubungi API."); }
            });

            if (attempts >= maxAttempts) {
                clearInterval(pollingInterval);
                scanStatus.text('Waktu habis. Coba lagi.').addClass('text-red-600');
                scanButton.prop('disabled', false).removeClass('bg-gray-400');
            }
        }, 1000);
    });
});
</script>
<?= $this->endSection() ?>