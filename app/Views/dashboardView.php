<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<!-- Row Pertama: Statistik Angka -->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">

    <!-- Kartu Belum Bayar -->
    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
        <div class="card card-flush h-md-50 mb-5 mb-xl-10" style="background-color: #F1416C;">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <!-- Tampilkan variabel total_penunggak -->
                    <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2"><?= $total_penunggak ?></span>
                    <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Pelanggan Belum Bayar</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0">
                <a href="<?= base_url('tagihan/belum-bayar') ?>" class="btn btn-sm btn-light-danger fw-bold">Lihat Semua</a>
            </div>
        </div>
    </div>

    <!-- Kartu Sudah Lunas -->
    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
        <div class="card card-flush h-md-50 mb-5 mb-xl-10" style="background-color: #50CD89;">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <!-- Tampilkan variabel total_lunas -->
                    <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2"><?= $total_lunas ?></span>
                    <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Pembayaran Lunas (Mei)</span>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Row Kedua: Ringkasan Tabel Penunggak -->
<div class="card card-flush mt-5">
    <div class="card-header pt-7">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">Ringkasan Penunggak Terbaru</span>
            <span class="text-muted mt-1 fw-semibold fs-7">Ada <?= $total_penunggak ?> orang yang belum bayar bulan ini.</span>
        </h3>
    </div>
    <div class="card-body">
        <table class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th>Nama Pelanggan</th>
                    <th>Paket</th>
                    <th>Tagihan</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
                <?php foreach ($list_penunggak as $row): ?>
                    <tr>
                        <td><?= $row['nama_lengkap'] ?></td>
                        <td><?= $row['nama_paket'] ?></td>
                        <td class="text-danger fw-bold">Rp <?= number_format($row['jumlah_bayar'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($list_penunggak)): ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">Bagus! Semua tagihan bulan ini sudah lunas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>