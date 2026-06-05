<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tunggakan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; }
        .header p { margin: 5px 0 0 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary { margin-top: 20px; font-size: 14px; font-weight: bold; text-align: right; color: #d9534f; }
    </style>
</head>
<body>

    <?php 
    $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $namaBulan = $months[$bulan-1] ?? '-';
    ?>

    <div class="header">
        <h2>LAPORAN TUNGGAKAN WIFINET</h2>
        <p>Periode Tagihan: <?= $namaBulan ?> <?= $tahun ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pelanggan</th>
                <th>No HP</th>
                <th>Paket</th>
                <th class="text-right">Sisa Tagihan</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($laporan)): ?>
            <tr>
                <td colspan="6" class="text-center">Tidak ada data tunggakan.</td>
            </tr>
            <?php else: ?>
                <?php $no = 1; foreach($laporan as $row): 
                    $sisa = $row['amount'] - $row['paid_amount'];
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= esc($row['customer_name']) ?></td>
                    <td><?= esc($row['phone']) ?></td>
                    <td><?= esc($row['package_name']) ?></td>
                    <td class="text-right">Rp <?= number_format($sisa, 0, ',', '.') ?></td>
                    <td class="text-center"><?= $row['status'] == 'partial' ? 'Dicicil' : 'Belum Bayar' ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="summary">
        Total Nilai Tunggakan: Rp <?= number_format($totalTunggakan, 0, ',', '.') ?>
    </div>

</body>
</html>
