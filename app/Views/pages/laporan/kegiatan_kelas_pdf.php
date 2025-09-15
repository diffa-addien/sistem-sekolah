<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kegiatan Kelas</title>
    <style>
        @page { margin: 20px; }
        body { font-family: serif; margin: 0; font-size: 10px; }
        .header-table { width: 100%; border-bottom: 2px solid black; padding-bottom: 10px; margin-bottom: 20px; }
        .school-name { font-size: 18px; font-weight: bold; }
        .address { font-size: 9px; }
        .title { text-align: center; font-size: 12px; font-weight: bold; margin-bottom: 15px; }
        
        .report-table { width: 100%; border-collapse: collapse; }
        .report-table th, .report-table td { border: 1px solid #ddd; padding: 4px; text-align: center; }
        .report-table th { background-color: #f2f2f2; font-weight: bold; }

        /* Style untuk baris nama siswa */
        .student-separator td {
            background-color: #e9e9e9;
            font-weight: bold;
            text-align: left;
            padding-left: 8px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <?php $config = config('App'); ?>
        <tr><td style="text-align: center;"><div class="school-name"><?= $config->appName ?></div>
        <!-- <div class="address">Alamat Sekolah Anda | Telp: (000) 000-0000</div></td></tr> -->
    </table>

    <div class="title">
        LAPORAN RANGKUMAN KEGIATAN KELAS: <?= esc($selected_class['name'] ?? '') ?><br>
        Bulan <?= esc($bulanIndonesia[$selected_month]) ?> <?= esc($selected_year) ?>
    </div>

    <table class="report-table">
        <thead>
          <tr><td colspan="<?= count($dateHeaders) ?>">Total Kegiatan per Tanggal</td></tr>
            <tr>
                <?php foreach ($dateHeaders as $date): ?>
                    <th><?= date('d', strtotime($date)) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($pivotedData)): foreach ($pivotedData as $student_id => $student): ?>
                <tr class="student-separator">
                    <td colspan="<?= count($dateHeaders) ?>">
                        <?= esc($student['full_name']) ?>
                    </td>
                </tr>
                <tr>
                    <?php foreach ($dateHeaders as $date): ?>
                        <td>
                            <?= count($student['daily_activities'][$date]['details'] ?? []) ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="<?= count($dateHeaders) ?>">Tidak ada data untuk ditampilkan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>