<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Row Statistik Utama -->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    
    <!-- Uang Masuk (Pendapatan) -->
    <div class="col-md-6 col-lg-6 col-xl-3">
        <div class="card card-flush h-md-100">
            <div class="card-body d-flex justify-content-between align-items-start flex-column">
                <div class="m-0">
                    <i class="ki-duotone ki-wallet fs-2hx text-gray-600">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                    </i>
                </div>
                <div class="d-flex flex-column my-7">
                    <span id="stat-uang-masuk" class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">
                        Rp <?= number_format($uang_masuk, 0, ',', '.') ?>
                    </span>
                    <div class="m-0 mt-1">
                        <span class="fw-semibold fs-6 text-gray-500">Pendapatan Bulan Ini</span>
                    </div>
                </div>
                <span class="badge badge-light-success fs-base">
                    <i class="ki-duotone ki-arrow-up fs-5 text-succes ms-n1"><span class="path1"></span><span class="path2"></span></i>
                    Terbayar
                </span>
            </div>
        </div>
    </div>

    <!-- Pelanggan Aktif -->
    <div class="col-md-6 col-lg-6 col-xl-3">
        <div class="card card-flush h-md-100">
            <div class="card-body d-flex justify-content-between align-items-start flex-column">
                <div class="m-0">
                    <i class="ki-duotone ki-profile-user fs-2hx text-gray-600">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                    </i>
                </div>
                <div class="d-flex flex-column my-7">
                    <span id="stat-total-pelanggan" class="fw-semibold fs-3x text-gray-800 lh-1 ls-n2">
                        <?= number_format($total_pelanggan, 0, ',', '.') ?>
                    </span>
                    <div class="m-0 mt-1">
                        <span class="fw-semibold fs-6 text-gray-500">Pelanggan Aktif</span>
                    </div>
                </div>
                <span class="badge badge-light-info fs-base">Total User</span>
            </div>
        </div>
    </div>

    <!-- Pelanggan Non-Aktif -->
    <div class="col-md-6 col-lg-6 col-xl-3">
        <div class="card card-flush h-md-100">
            <div class="card-body d-flex justify-content-between align-items-start flex-column">
                <div class="m-0">
                    <i class="ki-duotone ki-profile-user fs-2hx text-gray-600">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                </div>
                <div class="d-flex flex-column my-7">
                    <span id="stat-pelanggan-nonaktif" class="fw-semibold fs-3x text-gray-800 lh-1 ls-n2">
                        <?= number_format($pelanggan_nonaktif, 0, ',', '.') ?>
                    </span>
                    <div class="m-0 mt-1">
                        <span class="fw-semibold fs-6 text-gray-500">Isolir / Putus</span>
                    </div>
                </div>
                <span class="badge badge-light-secondary fs-base text-gray-600">Off-Service</span>
            </div>
        </div>
    </div>

    <!-- Belum Bayar (Warna disesuaikan agar tidak merah mencolok) -->
    <div class="col-md-6 col-lg-6 col-xl-3">
        <div class="card card-flush h-md-100">
            <div class="card-body d-flex justify-content-between align-items-start flex-column">
                <div class="m-0">
                    <i class="ki-duotone ki-calendar-tick fs-2hx text-gray-600">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span>
                    </i>
                </div>
                <div class="d-flex flex-column my-7">
                    <span id="stat-total-penunggak" class="fw-semibold fs-3x text-gray-800 lh-1 ls-n2">
                        <?= number_format($total_penunggak, 0, ',', '.') ?>
                    </span>
                    <div class="m-0 mt-1">
                        <span class="fw-semibold fs-6 text-gray-500">Belum Lunas</span>
                    </div>
                </div>
                <span class="badge badge-light-warning fs-base text-warning">Pending</span>
            </div>
        </div>
    </div>
</div>

<!-- Fitur Filter Mutasi Pembayaran -->
<div class="card mb-10 shadow-sm border-0">
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-3 mb-1">Filter Mutasi Pembayaran</span>
            <span class="text-muted mt-1 fw-semibold fs-7">Rentang tanggal laporan</span>
        </h3>
    </div>
    <div class="card-body">
        <form id="filterForm" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-bold">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control form-control-solid" value="<?= $filter['tgl_mulai'] ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control form-control-solid" value="<?= $filter['tgl_selesai'] ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" id="btnFilter" class="btn btn-primary w-100">
                    <span class="indicator-label">Filter</span>
                </button>
            </div>
            <div class="col-md-4">
                <div class="bg-light rounded p-4 text-center border border-dashed border-gray-400">
                    <span class="fs-8 fw-bold text-gray-600 d-block text-uppercase">Total Terpilih</span>
                    <span id="total-mutasi-filter" class="fs-3 fw-bolder text-gray-900">Rp <?= number_format($filter['total_mutasi'], 0, ',', '.') ?></span>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Tagihan -->
<div class="card card-flush shadow-sm">
    <div class="card-header pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">Riwayat Pembayaran Terbaru</span>
            <span class="text-gray-500 mt-1 fw-semibold fs-7">Pembayaran yang baru saja diproses</span>
        </h3>
    </div>
    <div class="card-body pt-5">
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-150px">Pelanggan</th>
                        <th class="min-w-120px">No. HP</th>
                        <th class="min-w-150px text-end">Nominal Bayar</th>
                        <th class="min-w-100px text-end">Status</th>
                    </tr>
                </thead>
                <tbody id="list-terbaru-body">
                    <?php if(empty($list_terbaru)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-10">Data tidak ditemukan.</td></tr>
                    <?php else: ?>
                        <?php foreach($list_terbaru as $p): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40px me-3">
                                        <div class="symbol-label bg-light-success text-success fw-bold">
                                            <?= strtoupper(substr($p['customer_name'] ?? 'P', 0, 1)) ?>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start flex-column">
                                        <span class="text-gray-800 fw-bold fs-6"><?= esc($p['customer_name']) ?></span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-gray-600 fw-semibold"><?= esc($p['phone']) ?></span></td>
                            <td class="text-end fw-bold">
                                <span class="text-gray-800">Rp <?= number_format($p['paid_amount'], 0, ',', '.') ?></span>
                            </td>
                            <td class="text-end">
                                <?php if($p['status'] === 'partial'): ?>
                                    <span class="badge badge-light-warning text-warning fw-bold">DICICIL</span>
                                <?php else: ?>
                                    <span class="badge badge-light-success text-success fw-bold">LUNAS</span>
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

<!-- AJAX SCRIPT (Tetap sama agar fungsionalitas tidak berubah) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#btnFilter');
        btn.prop('disabled', true).text('Loading...');

        $.ajax({
            url: "<?= base_url('dashboard/filter') ?>",
            type: "GET",
            data: {
                tgl_mulai: $('#tgl_mulai').val(),
                tgl_selesai: $('#tgl_selesai').val()
            },
            dataType: "JSON",
            success: function(response) {
                $('#stat-uang-masuk').text(response.uang_masuk_formatted);
                $('#stat-total-pelanggan').text(response.total_pelanggan);
                $('#stat-pelanggan-nonaktif').text(response.pelanggan_nonaktif);
                $('#stat-total-penunggak').text(response.total_penunggak);
                $('#total-mutasi-filter').text(response.total_mutasi_formatted);

                let tableHtml = '';
                if (!response.list_terbaru || response.list_terbaru.length === 0) {
                    tableHtml = '<tr><td colspan="4" class="text-center text-muted py-10">Tidak ada data.</td></tr>';
                } else {
                    response.list_terbaru.forEach(function(p) {
                        let name = p.customer_name ? p.customer_name : 'Unknown';
                        let phone = p.phone ? p.phone : '-';
                        let dibayar = parseFloat(p.paid_amount || 0);
                        let initial = name.charAt(0).toUpperCase();

                        let statusBadge = p.status === 'partial' ? 
                            '<span class="badge badge-light-warning fw-bold px-4 py-3">DICICIL</span>' : 
                            '<span class="badge badge-light-success fw-bold px-4 py-3">LUNAS</span>';

                        tableHtml += `
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-45px me-5">
                                        <div class="symbol-label bg-light-success text-success fw-bold">${initial}</div>
                                    </div>
                                    <div class="d-flex justify-content-start flex-column">
                                        <a href="#" class="text-dark fw-bold text-hover-primary fs-6">${name}</a>
                                    </div>
                                </div>
                            </td>
                            <td>${phone}</td>
                            <td class="text-end fw-bold">
                                <span class="text-dark">Rp ${dibayar.toLocaleString('id-ID')}</span>
                            </td>
                            <td class="text-end">${statusBadge}</td>
                        </tr>`;
                    });
                }
                $('#list-terbaru-body').html(tableHtml);
            },
           error: function() {
                alert('Gagal mengambil data.');
            },
            complete: function() {
                btn.prop('disabled', false).text('Filter');
            }
        });
    });
});
</script>

<?= $this->endSection() ?>