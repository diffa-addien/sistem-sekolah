<?= $this->extend('layouts/template') ?>
<?= $this->section('title') ?>Rangkuman Kegiatan Harian<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6" x-data="activityModal()">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold text-gray-700">Rangkuman Kegiatan Harian</h2>
        <a href="<?= site_url('admin/kegiatan/new') ?>" class="px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-lg hover:bg-sky-700">Catat Kegiatan Manual</a>
    </div>

    <div class="mb-6 p-4 bg-white rounded-2xl border border-gray-300 shadow-sm">
        <form action="<?= site_url('admin/kegiatan') ?>" method="get">
            <div class="flex flex-wrap items-end gap-4">
                <?php if(!$is_teacher): ?>
                <div class="flex-1">
                    <label for="class_id" class="block text-sm font-medium text-gray-700">Pilih Kelas</label>
                    <select name="class_id" id="class_id" class="block w-full mt-1 text-sm rounded-lg border-gray-300" required>
                        <option value="">-- Silakan Pilih --</option>
                        <?php foreach ($classes as $class) : ?>
                            <option value="<?= $class['id'] ?>" <?= ($selected_class_id == $class['id']) ? 'selected' : '' ?>><?= esc($class['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="flex-1">
                    <label for="date" class="block text-sm font-medium text-gray-700">Pilih Tanggal</label>
                    <input type="date" name="date" id="date" value="<?= esc($selected_date) ?>" class="block w-full mt-1 text-sm rounded-lg border-gray-300" required>
                </div>
                <div>
                    <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-lg hover:bg-sky-700">Tampilkan</button>
                </div>
            </div>
        </form>
    </div>

    <div class="w-full overflow-hidden rounded-2xl border border-gray-300">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Siswa</th>
                        <th class="px-4 py-3 text-center">Jumlah Kegiatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    <?php if (!empty($students)): foreach ($students as $student) : ?>
                        <tr class="text-gray-700">
                            <td class="px-4 py-3">
                                <div class="flex items-center text-sm">
                                    <div class="relative w-10 h-10 mr-3 rounded-full flex-shrink-0">
                                        <img class="object-cover w-full h-full rounded-full" src="<?= base_url('uploads/photos/' . ($student['photo'] ?? 'default.png')) ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="hidden absolute inset-0 flex items-center justify-center rounded-full bg-sky-900 text-white text-lg font-semibold"><?= esc(strtoupper(substr($student['full_name'], 0, 1))) ?></div>
                                    </div>
                                    <div>
                                        <p class="font-semibold"><?= esc($student['full_name']) ?></p>
                                        <p class="text-xs text-gray-600">NIS: <?= esc($student['nis']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <?php 
                                    $current_activity = $activityData[$student['id']] ?? ['count' => 0, 'details' => []];
                                ?>
                                <?php if($current_activity['count'] > 0): ?>
                                    <button @click="open('<?= esc($student['full_name']) ?>', '<?= date('d M Y', strtotime($selected_date)) ?>', '<?= htmlspecialchars(json_encode($current_activity['details']), ENT_QUOTES) ?>')"
                                        class="px-3 py-1 text-sm font-semibold text-sky-700 bg-sky-100 rounded-full hover:bg-sky-200">
                                        <?= $current_activity['count'] ?> Kegiatan
                                    </button>
                                <?php else: ?>
                                    <span class="text-gray-400">0</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; elseif ($selected_class_id): ?>
                        <tr><td colspan="2" class="text-center py-4 text-gray-500">Tidak ada data untuk ditampilkan.</td></tr>
                    <?php else: ?>
                        <tr><td colspan="2" class="text-center py-4 text-gray-500">Silakan pilih kelas dan tanggal untuk melihat rangkuman.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div x-show="isOpen" @keydown.escape.window="close()" class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
        <div @click.away="close()" class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center border-b pb-3">
                <div>
                    <h3 class="text-lg font-semibold" x-text="`Detail Kegiatan: ${studentName}`"></h3>
                    <p class="text-sm text-gray-500" x-text="date"></p>
                </div>
                <button @click="close()" class="p-2 rounded-full hover:bg-gray-100 -mt-2 -mr-2">&times;</button>
            </div>
            <div class="mt-4 max-h-60 overflow-y-auto">
                <ul class="list-disc list-inside space-y-2 text-gray-700">
                    <template x-for="activity in activities">
                        <li x-text="activity"></li>
                    </template>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function activityModal() {
    return {
        isOpen: false, studentName: '', date: '', activities: [],
        open(studentName, date, activitiesJson) {
            this.studentName = studentName; this.date = date;
            const parsed = JSON.parse(activitiesJson.replace(/&quot;/g, '"'));
            this.activities = parsed.map(item => {
                if (typeof item === 'string') return item;
                let timeStr = '';
                if (item.time) {
                    const d = new Date(item.time.replace(' ', 'T'));
                    if (!isNaN(d.getTime())) {
                        timeStr = ' (' + d.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}).replace('.', ':') + ')';
                    }
                }
                return item.name + timeStr;
            });
            this.isOpen = true;
        },
        close() { this.isOpen = false; }
    }
}
</script>
<?= $this->endSection() ?>