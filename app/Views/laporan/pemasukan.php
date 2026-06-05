<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-3 mb-1">Laporan Pemasukan</span>
            <span class="text-muted mt-1 fw-semibold fs-7">Filter data pemasukan harian/bulanan</span>
        </h3>
        <div class="card-toolbar">
            <a href="<?= base_url('laporan/pemasukan/pdf?tgl_mulai=' . $tglMulai . '&tgl_selesai=' . $tglSelesai) ?>" target="_blank" class="btn btn-sm btn-danger">
                <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i>
                Export PDF
            </a>
        </div>
    </div>
    
    <div class="card-body py-3">
        <!-- Form Filter -->
        <form method="get" action="<?= base_url('laporan/pemasukan') ?>" class="row g-3 mb-8 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-bold">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" class="form-control form-control-solid" value="<?= $tglMulai ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" class="form-control form-control-solid" value="<?= $tglSelesai ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <!-- Summary -->
        <div class="alert bg-light-primary border border-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <i class="ki-duotone ki-wallet fs-2hx text-primary me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
            <div class="d-flex flex-column pe-0 pe-sm-10">
                <h4 class="fw-semibold">Total Pemasukan (<?= date('d M Y', strtotime($tglMulai)) ?> - <?= date('d M Y', strtotime($tglSelesai)) ?>)</h4>
                <span class="fs-1 fw-bolder text-gray-900">Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 min-w-150px rounded-start">Tanggal</th>
                        <th class="min-w-150px">Pelanggan</th>
                        <th class="min-w-120px">Paket</th>
                        <th class="min-w-100px text-end">Dibayar</th>
                        <th class="min-w-100px text-center">Status</th>
                        <?php if(session()->get('role') === 'owner'): ?>
                        <th class="min-w-120px rounded-end">Kasir</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($laporan)): ?>
                    <tr>
                        <td colspan="<?= session()->get('role') === 'owner' ? 6 : 5 ?>" class="text-center text-muted py-10">Tidak ada data pemasukan pada rentang tanggal tersebut.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($laporan as $row): ?>
                        <tr>
                            <td class="ps-4"><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold"><?= esc($row['customer_name']) ?></span>
                                    <span class="text-gray-500 fs-8"><?= esc($row['phone']) ?></span>
                                </div>
                            </td>
                            <td><?= esc($row['package_name']) ?></td>
                            <td class="text-end text-dark fw-bold">Rp <?= number_format($row['paid_amount'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <?php if($row['status'] == 'partial'): ?>
                                    <span class="badge badge-light-warning">Dicicil</span>
                                <?php else: ?>
                                    <span class="badge badge-light-success">Lunas</span>
                                <?php endif; ?>
                            </td>
                            <?php if(session()->get('role') === 'owner'): ?>
                            <td><?= esc($row['admin_name'] ?? 'Sistem') ?></td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
