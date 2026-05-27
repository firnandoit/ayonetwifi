<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- BEGIN: Baris Widget Atas -->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!-- Kolom Widget Upgrade Plan -->
    <div class="col-xxl-6">
        <!--begin::Engage widget 8-->
        <div class="card border-0 h-md-100" data-bs-theme="light" style="background: linear-gradient(112.14deg, #00D2FF 0%, #3A7BD5 100%)">
            <!--begin::Body-->
            <div class="card-body">
                <!--begin::Row-->
                <div class="row align-items-center h-100">
                    <!--begin::Col-->
                    <div class="col-7 ps-xl-13">
                        <!--begin::Title-->
                        <div class="text-white mb-6 pt-6">
                            <span class="fs-4 fw-semibold me-2 d-block lh-1 pb-2 opacity-75">Tingkatkan Pengalaman</span>
                            <span class="fs-2qx fw-bold">Upgrade Paket Internet</span>
                        </div>
                        <!--end::Title-->
                        <!--begin::Text-->
                        <span class="fw-semibold text-white fs-6 mb-8 d-block opacity-75">Dapatkan kecepatan lebih tinggi dan stabil untuk kebutuhan streaming serta gaming tanpa hambatan.</span>
                        <!--end::Text-->
                        <!--begin::Items-->
                        <div class="d-flex align-items-center flex-wrap d-grid gap-2 mb-10 mb-xl-20">
                            <!--begin::Item-->
                            <div class="d-flex align-items-center me-5 me-xl-13">
                                <!--begin::Symbol-->
                                <div class="symbol symbol-30px symbol-circle me-3">
                                    <span class="symbol-label" style="background: #35C7FF">
                                        <i class="ki-outline ki-abstract-41 fs-5 text-white"></i>
                                    </span>
                                </div>
                                <!--end::Symbol-->
                                <!--begin::Info-->
                                <div class="text-white">
                                    <span class="fw-semibold d-block fs-8 opacity-75">Kecepatan</span>
                                    <span class="fw-bold fs-7">Up to 100 Mbps</span>
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex align-items-center">
                                <!--begin::Symbol-->
                                <div class="symbol symbol-30px symbol-circle me-3">
                                    <span class="symbol-label" style="background: #35C7FF">
                                        <i class="ki-outline ki-abstract-26 fs-5 text-white"></i>
                                    </span>
                                </div>
                                <!--end::Symbol-->
                                <!--begin::Info-->
                                <div class="text-white">
                                    <span class="fw-semibold opacity-75 d-block fs-8">Kuota</span>
                                    <span class="fw-bold fs-7">Unlimited</span>
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::Item-->
                        </div>
                        <!--end::Items-->
                        <!--begin::Action-->
                        <div class="d-flex flex-column flex-sm-row d-grid gap-2">
                            <a href="#" class="btn btn-success flex-shrink-0 me-lg-2" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">Lihat Paket</a>
                            <a href="#" class="btn btn-primary flex-shrink-0" style="background: rgba(255, 255, 255, 0.2)">Bantuan</a>
                        </div>
                        <!--end::Action-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-5 pt-10">
                        <!--begin::Illustration-->
                        <div class="bgi-no-repeat bgi-size-contain bgi-position-x-end h-225px" style="background-image:url('<?= base_url('assets/media/svg/illustrations/easy/5.svg') ?>')"></div>
                        <!--end::Illustration-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Engage widget 8-->
    </div>
    
    <!-- Kolom Instruksi Pembayaran -->
    <div class="col-xxl-6">
        <div class="card border-0 h-md-100">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-dark">Informasi Pembayaran</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Panduan ringkas transaksi</span>
                </h3>
            </div>
            <div class="card-body">
                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
                    <i class="ki-outline ki-information-5 fs-2tx text-primary me-4"></i>
                    <div class="d-flex flex-stack flex-grow-1">
                        <div class="fw-semibold">
                            <h4 class="text-gray-900 fw-bold">Instruksi Pembayaran</h4>
                            <div class="fs-6 text-gray-700">
                                1. Klik tombol <b class="text-primary">Bayar Sekarang</b> untuk memunculkan kode QRIS.<br>
                                2. Pembayaran akan terverifikasi <b>Otomatis</b> oleh sistem dalam hitungan detik.<br>
                                3. Anda dapat memperbarui kode pembayaran maksimal <b class="text-danger">3 kali</b> jika kode sebelumnya kedaluwarsa.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Baris Widget Atas -->

<!-- Card Utama: Tabel Tagihan -->
<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <h2><i class="ki-outline ki-wallet fs-1 me-3"></i>Tagihan Saya & Pembayaran</h2>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed table-row-gray-300 table-hover fs-6 gy-5" id="table_my_bills">
            <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <th class="min-w-150px">Periode Tagihan</th>
                    <th class="min-w-100px">Total Bayar</th>
                    <th class="min-w-150px">Status</th>
                    <th class="text-end min-w-100px">Aksi</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600"></tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Script Midtrans Snap -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-nnpr8JpN1LmwxbpL"></script>

<script>
    var table;

    jQuery(document).ready(function($) {
        // Inisialisasi DataTable Tagihan Customer
        table = $('#table_my_bills').DataTable({
            processing: true,
            serverSide: false,
            ajax: '<?= base_url('customer/list-tagihan') ?>',
            columns: [{
                    data: 'month',
                    render: function(data, type, row) {
                        const namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                        return `<span class="text-gray-800 fw-bold fs-6">${namaBulan[parseInt(data)]} ${row.year}</span>`;
                    }
                },
                {
                    data: 'amount',
                    render: function(data) {
                        return '<span class="fw-bold text-gray-800">Rp ' + new Intl.NumberFormat('id-ID').format(data) + '</span>';
                    }
                },
                {
                    data: 'status',
                    render: function(data) {
                        if (data === 'paid') {
                            return '<span class="badge badge-light-success fw-bold px-4 py-3"><i class="ki-outline ki-check-circle fs-7 me-1 text-success"></i> LUNAS</span>';
                        } else if (data === 'pending') {
                            return '<span class="badge badge-light-warning fw-bold px-4 py-3"><i class="ki-outline ki-timer fs-7 me-1 text-warning"></i> MENUNGGU PEMBAYARAN</span>';
                        } else if (data === 'unpaid') {
                            return '<span class="badge badge-light-danger fw-bold px-4 py-3"><i class="ki-outline ki-cross-circle fs-7 me-1 text-danger"></i> KODE KEDALUWARSA</span>';
                        }
                        return '<span class="badge badge-light-danger fw-bold px-4 py-3">BELUM BAYAR</span>';
                    }
                },
                {
                    data: 'id',
                    className: 'text-end',
                    render: function(data, type, row) {
                        if (row.status === 'paid') {
                            return '<span class="text-success fw-bold"><i class="ki-outline ki-double-check fs-2 text-success me-1"></i> Transaksi Selesai</span>';
                        }

                        let btnClass = row.status === 'pending' ? 'btn-light-warning' : 'btn-primary';
                        let btnText = row.status === 'pending' ? 'Buka QRIS' : (row.status === 'unpaid' ? 'Perbarui Kode QRIS' : 'Bayar Sekarang');
                        let btnIcon = row.status === 'pending' ? 'ki-qr-code' : 'ki-wallet';

                        return `
                            <button class="btn btn-sm ${btnClass} fw-bold" onclick="bayarSekarang(${data})">
                                <i class="ki-outline ${btnIcon} fs-2"></i> ${btnText}
                            </button>
                        `;
                    }
                }
            ]
        });
    });

    function bayarSekarang(billId) {
        Swal.fire({
            title: 'Menyiapkan Pembayaran...',
            text: 'Harap tunggu sejenak',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '<?= base_url('payment/get-token/') ?>' + billId,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                Swal.close();
                window.snap.pay(res.token, {
                    onSuccess: function(result) {
                        Swal.fire('Berhasil!', 'Pembayaran telah diterima.', 'success');
                        table.ajax.reload();
                    },
                    onPending: function(result) {
                        Swal.fire('Informasi', 'Selesaikan pembayaran sebelum batas waktu.', 'info');
                        table.ajax.reload();
                    },
                    onError: function(result) {
                        Swal.fire('Gagal', 'Terjadi kesalahan pembayaran.', 'error');
                        table.ajax.reload();
                    },
                    onClose: function() {
                        table.ajax.reload();
                    }
                });
            },
            error: function(err) {
                Swal.close();
                let errorMsg = 'Gagal terhubung ke server pembayaran.';
                if (err.responseJSON && err.responseJSON.message) {
                    errorMsg = err.responseJSON.message;
                }
                Swal.fire({
                    title: 'Perhatian!',
                    text: errorMsg,
                    icon: 'warning',
                    confirmButtonText: 'Tutup',
                    customClass: { confirmButton: "btn btn-primary" }
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>