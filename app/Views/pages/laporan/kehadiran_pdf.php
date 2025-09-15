<!DOCTYPE html>
<html lang="id">

<?php $config = config('App'); ?>

<head>
  <meta charset="UTF-8">
  <title>Laporan Kehadiran</title>
  <style>
    body {
      font-family: serif;
      margin: 0;
      padding: 0;
      font-size: 10px;
    }

    .header-table {
      width: 100%;
      border-bottom: 2px solid black;
      padding-bottom: 10px;
    }

    .header-table td {
      vertical-align: middle;
    }

    .logo {
      width: 80px;
    }

    .school-name {
      font-size: 18px;
      font-weight: bold;
    }

    .address {
      font-size: 10px;
    }

    .title {
      text-align: center;
      font-size: 14px;
      font-weight: bold;
      margin-top: 20px;
      margin-bottom: 20px;
    }

    .report-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .report-table th,
    .report-table td {
      border: 1px solid #ccc;
      padding: 5px;
      text-align: center;
    }

    .report-table th {
      background-color: #f2f2f2;
      font-weight: bold;
    }

    .student-name {
      text-align: left;
      min-width: 150px;
    }

    .badge {
      display: inline-block;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      color: white;
      font-weight: bold;
      font-size: 9px;
      line-height: 12px;
    }

    .hadir {
      background-color: #4CAF50;
    }

    .sakit {
      background-color: #FFC107;
    }

    .izin {
      background-color: #2196F3;
    }

    .alfa {
      background-color: #8f8f8fff;
    }
  </style>
</head>

<body>
  <table class="header-table">
    <tr>
      <td style="text-align: center;">
        <div class="school-name"><?= $config->appName ?></div>
        
      </td>
    </tr>
  </table>

  <div class="title">
    LAPORAN REKAP KEHADIRAN KELAS: <?= esc($selected_class['name'] ?? '') ?><br>
    Bulan <?= esc($bulanIndonesia[$selected_month]) ?> <?= esc($selected_year) ?>
  </div>

  <table class="report-table">
    <thead>
      <tr>
        <th class="student-name">Nama Siswa</th>
        <?php foreach ($dateHeaders as $date): ?>
          <th><?= date('d', strtotime($date)) ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($reportData)):
        foreach ($reportData as $nis => $data): ?>
          <tr>
            <td class="student-name"><?= esc($data['full_name']) ?></td>
            <?php foreach ($dateHeaders as $date): ?>
              <td>
                <?php $status = $data['attendances'][$date] ?? 'Alfa'; ?>
                <div class="badge <?= strtolower($status) ?>"><?= substr(($status == "Alfa" ? "-" : $status), 0, 1) ?></div>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; else: ?>
        <tr>
          <td colspan="<?= count($dateHeaders) + 1 ?>">Tidak ada data untuk ditampilkan.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

</body>

</html>