<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-3 mb-1">Laporan Tunggakan</span>
            <span class="text-muted mt-1 fw-semibold fs-7">Data pelanggan yang belum melunasi tagihan</span>
        </h3>
        <div class="card-toolbar">
            <a href="<?= base_url('laporan/tunggakan/pdf?bulan=' . $bulan . '&tahun=' . $tahun) ?>" target="_blank" class="btn btn-sm btn-danger">
                <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i>
                Export PDF
            </a>
        </div>
    </div>
    
    <div class="card-body py-3">
        <!-- Form Filter -->
        <form method="get" action="<?= base_url('laporan/tunggakan') ?>" class="row g-3 mb-8 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-bold">Bulan</label>
                <select name="bulan" class="form-select form-select-solid">
                    <?php 
                    $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                    foreach($months as $idx => $m): 
                        $val = $idx + 1;
                    ?>
                        <option value="<?= $val ?>" <?= $val == $bulan ? 'selected' : '' ?>><?= $m ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Tahun</label>
                <select name="tahun" class="form-select form-select-solid">
                    <?php for($y = date('Y')-2; $y <= date('Y')+1; $y++): ?>
                        <option value="<?= $y ?>" <?= $y == $tahun ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <!-- Summary -->
        <div class="alert bg-light-danger border border-danger d-flex flex-column flex-sm-row p-5 mb-10">
            <i class="ki-duotone ki-calendar-remove fs-2hx text-danger me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
            <div class="d-flex flex-column pe-0 pe-sm-10">
                <h4 class="fw-semibold">Total Nilai Tunggakan (<?= $months[$bulan-1] ?> <?= $tahun ?>)</h4>
                <span class="fs-1 fw-bolder text-gray-900">Rp <?= number_format($totalTunggakan, 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 min-w-150px rounded-start">Pelanggan</th>
                        <th class="min-w-120px">No HP</th>
                        <th class="min-w-120px">Paket</th>
                        <th class="min-w-100px text-end">Sisa Tagihan</th>
                        <th class="min-w-100px text-center rounded-end">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($laporan)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-10">Tidak ada pelanggan yang menunggak di bulan ini.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($laporan as $row): 
                            $sisa = $row['amount'] - $row['paid_amount'];
                        ?>
                        <tr>
                            <td class="ps-4">
                                <span class="text-gray-800 fw-bold d-block fs-6"><?= esc($row['customer_name']) ?></span>
                            </td>
                            <td><?= esc($row['phone']) ?></td>
                            <td><?= esc($row['package_name']) ?></td>
                            <td class="text-end text-dark fw-bold">Rp <?= number_format($sisa, 0, ',', '.') ?></td>
                            <td class="text-center">
                                <?php if($row['status'] == 'partial'): ?>
                                    <span class="badge badge-light-warning">Dicicil</span>
                                <?php else: ?>
                                    <span class="badge badge-light-danger">Belum Bayar</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
