<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Form Siswa<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-3xl mx-auto px-6 py-8 bg-white rounded-xl shadow-lg">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">
        <?= isset($student) ? 'Edit Siswa' : 'Tambah Siswa' ?>
    </h2>

    <?php if (session('errors')): ?>
        <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-lg">
            <?= validation_list_errors('my_list') ?>
        </div>
    <?php endif; ?>

    <form action="<?= isset($student) ? site_url('admin/siswa/' . $student['id']) : site_url('admin/siswa') ?>"
        method="post" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>
        <?php if (isset($student)): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <label for="nis" class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                    <input type="text" name="nis" value="<?= old('nis', $student['nis'] ?? '') ?>"
                        class="w-full p-3 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-all"
                        required>
                </div>
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="full_name" value="<?= old('full_name', $student['full_name'] ?? '') ?>"
                        class="w-full p-3 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-all"
                        required>
                </div>
                <div>
                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="<?= old('birth_date', $student['birth_date'] ?? '') ?>"
                        class="w-full p-3 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-all"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <?php $gender = old('gender', $student['gender'] ?? ''); ?>
                    <div class="flex space-x-6">
                        <label class="flex items-center">
                            <input type="radio" value="Laki-laki" name="gender"
                                class="w-4 h-4 text-blue-600 focus:ring-blue-500" <?= $gender == 'Laki-laki' ? 'checked' : '' ?> required>
                            <span class="ml-2 text-sm text-gray-700">Laki-laki</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" value="Perempuan" name="gender"
                                class="w-4 h-4 text-blue-600 focus:ring-blue-500" <?= $gender == 'Perempuan' ? 'checked' : '' ?>>
                            <span class="ml-2 text-sm text-gray-700">Perempuan</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select name="class_id"
                        class="w-full p-3 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-all"
                        required>
                        <option value="">Pilih Kelas</option>
                        <?php $selectedClass = old('class_id', $student['class_id'] ?? ''); ?>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['id'] ?>" <?= $selectedClass == $class['id'] ? 'selected' : '' ?>>
                                <?= esc($class['name']) ?> (<?= esc($class['academic_year']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Tautkan Akun Wali Murid
                        (Opsional)</label>
                    <select name="user_id"
                        class="w-full p-3 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-all">
                        <option value="">Tidak Ditautkan</option>
                        <?php $selectedParent = old('user_id', $student['user_id'] ?? ''); ?>
                        <?php foreach ($parents as $parent): ?>
                            <option value="<?= $parent['id'] ?>" <?= $selectedParent == $parent['id'] ? 'selected' : '' ?>>
                                <?= esc($parent['name']) ?> (<?= esc($parent['username']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <label for="card_uid" class="block text-sm font-medium text-gray-700 mb-1">UID Kartu RFID</label>
                    <div class="flex items-center space-x-3">
                        <input type="text" id="card_uid" name="card_uid"
                            value="<?= old('card_uid', $student['card_uid'] ?? '') ?>"
                            class="w-full p-3 text-sm border border-gray-200 rounded-lg bg-gray-100" readonly
                            placeholder="Tap kartu untuk scan...">
                        <button type="button" id="scan-rfid-btn"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-all">
                            Scan
                        </button>
                    </div>
                    <p id="scan-status" class="mt-2 text-xs text-gray-500">Klik "Scan" lalu tap kartu.</p>
                </div>
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                    <input type="file" name="photo" accept="image/*"
                        class="w-full p-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-8">
            <a href="<?= site_url('admin/siswa') ?>"
                class="px-5 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">Batal</a>
            <button type="submit"
                class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-all">
                <?= isset($student) ? 'Simpan Perubahan' : 'Simpan' ?>
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        const scanStatus = $('#scan-status');
        const uidInput = $('#card_uid');
        const scanButton = $('#scan-rfid-btn');

        scanButton.on('click', function () {
            scanStatus.text('Menunggu kartu...').removeClass('text-green-600 text-red-600');
            scanButton.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');

            let attempts = 0;
            const maxAttempts = 20;
            const scanStartTime = Math.floor(Date.now() / 1000);

            const pollingInterval = setInterval(function () {
                attempts++;
                $.ajax({
                    url: "<?= site_url('api/check-scan') ?>",
                    method: "GET",
                    dataType: "json",
                    success: function (response) {
                        // Cek jika UID baru dan timestampnya lebih baru dari waktu scan dimulai
                        if (response.status === 'success' && response.uid && response.timestamp > scanStartTime) {
                            clearInterval(pollingInterval);
                            uidInput.val(response.uid);
                            scanStatus.text('Sukses! UID ' + response.uid + ' ditemukan.').addClass('text-green-600');
                            scanButton.prop('disabled', false).removeClass('bg-gray-400');
                        } else if (response.status === 'error' && response.message === 'UID sudah terdaftar' && response.timestamp > scanStartTime) {
                            clearInterval(pollingInterval);
                            uidInput.val(''); // !! PERUBAHAN: Kosongkan input jika UID sudah terdaftar
                            scanStatus.text('Error: UID ini sudah terdaftar!').addClass('text-red-600');
                            scanButton.prop('disabled', false).removeClass('bg-gray-400');
                        }
                    },
                    error: function () {
                        console.error("Gagal menghubungi API.");
                    }
                });

                if (attempts >= maxAttempts) {
                    clearInterval(pollingInterval);
                    scanStatus.text('Waktu habis, coba lagi.').addClass('text-red-600');
                    scanButton.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                }
            }, 1000);
        });
    });
</script>
<?= $this->endSection() ?>