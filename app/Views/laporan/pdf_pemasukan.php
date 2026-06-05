<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemasukan</title>
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
        .summary { margin-top: 20px; font-size: 14px; font-weight: bold; text-align: right; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN PEMASUKAN WIFINET</h2>
        <p>Periode: <?= date('d M Y', strtotime($tglMulai)) ?> s/d <?= date('d M Y', strtotime($tglSelesai)) ?></p>
        <?php if($adminName && session()->get('role') !== 'owner'): ?>
            <p>Kasir: <?= esc($adminName) ?></p>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>No HP</th>
                <th>Paket</th>
                <th class="text-right">Nominal Bayar</th>
                <?php if(session()->get('role') === 'owner'): ?>
                <th>Kasir</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($laporan)): ?>
            <tr>
                <td colspan="<?= session()->get('role') === 'owner' ? 7 : 6 ?>" class="text-center">Tidak ada data pemasukan.</td>
            </tr>
            <?php else: ?>
                <?php $no = 1; foreach($laporan as $row): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                    <td><?= esc($row['customer_name']) ?></td>
                    <td><?= esc($row['phone']) ?></td>
                    <td><?= esc($row['package_name']) ?></td>
                    <td class="text-right">Rp <?= number_format($row['paid_amount'], 0, ',', '.') ?></td>
                    <?php if(session()->get('role') === 'owner'): ?>
                    <td><?= esc($row['admin_name'] ?? 'Sistem') ?></td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="summary">
        Total Pemasukan: Rp <?= number_format($totalPemasukan, 0, ',', '.') ?>
    </div>

</body>
</html>
