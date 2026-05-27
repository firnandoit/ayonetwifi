<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1 me-5">
                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                <input type="text" id="searchTagihan" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Pelanggan...">
            </div>

            <!-- Filter Bulan & Tahun -->
            <div class="me-3">
                <select id="filterBulan" class="form-select form-select-solid w-150px" data-control="select2">
                    <option value="">Semua Bulan</option>
                    <?php 
                    $namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                    foreach ($namaBulan as $index => $bulan) : ?>
                        <option value="<?= $index + 1 ?>" <?= ($index + 1 == date('n')) ? 'selected' : '' ?>><?= $bulan ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="me-3">
                <select id="filterTahun" class="form-select form-select-solid w-120px" data-control="select2">
                    <?php for ($i = date('Y'); $i >= 2023; $i--) : ?>
                        <option value="<?= $i ?>" <?= ($i == date('Y')) ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <div class="card-toolbar">
            <button type="button" class="btn btn-primary" onclick="generateTagihan()">
                <i class="ki-outline ki-arrows-circle fs-2"></i> Buat Tagihan
            </button>
        </div>
    </div>

    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed table-row-gray-300 table-hover fs-6 gy-5" id="table_tagihan">
            <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <th class="min-w-150px">Pelanggan</th>
                    <th class="min-w-100px">Periode</th>
                    <th class="min-w-100px">Paket</th>
                    <th class="min-w-150px text-end">Rincian Bayar</th>
                    <th class="min-w-120px">Tgl Update</th> <!-- Kolom Kunci Pengurutan -->
                    <th class="min-w-100px text-center">Status</th>
                    <th class="text-end min-w-100px">Aksi</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600"></tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    var table;
    var statusFilter = '<?= $status ?? "all" ?>';

    jQuery(document).ready(function($) {
        table = $('#table_tagihan').DataTable({
            processing: true,
            serverSide: false,
            // LOGIKA UTAMA: Urutkan berdasarkan kolom ke-4 (indeks 4 = Tgl Update) secara Descending
            "order": [[4, "desc"]], 
            ajax: {
                url: '<?= base_url('tagihan/list') ?>',
                data: function(d) {
                    d.status = statusFilter;
                    d.month = $('#filterBulan').val();
                    d.year = $('#filterTahun').val();
                }
            },
            columns: [
                {
                    data: 'customer_name',
                    render: function(data, type, row) {
                        return `<div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold mb-1">${data}</span>
                                    <span class="text-muted fs-7">${row.phone}</span>
                                </div>`;
                    }
                },
                {
                    data: 'month',
                    render: function(data, type, row) {
                        const namaBulan = ["", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
                        return `<span class="text-gray-700">${namaBulan[parseInt(data)]} ${row.year}</span>`;
                    }
                },
                {
                    data: 'package_name',
                    render: (data) => `<span class="badge badge-light-primary fs-8">${data || '-'}</span>`
                },
                {
                    data: 'amount',
                    className: 'text-end',
                    render: function(data, type, row) {
                        let total = parseFloat(data);
                        let terbayar = parseFloat(row.paid_amount || 0);
                        let diskon = parseFloat(row.discount || 0);
                        let sisa = total - terbayar - diskon;

                        let html = `<div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bold">Rp ${new Intl.NumberFormat('id-ID').format(total)}</span>`;
                        
                        if (diskon > 0) {
                            html += `<span class="text-info fs-8 fw-bold">Diskon: Rp ${new Intl.NumberFormat('id-ID').format(diskon)}</span>`;
                        }

                        if (row.status === 'partial') {
                            html += `<span class="text-danger fs-8 fw-bold">Sisa: Rp ${new Intl.NumberFormat('id-ID').format(sisa)}</span>`;
                        } else if (row.status === 'paid') {
                            html += `<span class="text-success fs-9 italic">Lunas</span>`;
                        }
                        
                        html += `</div>`;
                        return html;
                    }
                },
                {
                    // KOLOM TANGGAL UPDATE (Urutan Teratas jika Baru diedit)
                    data: 'updated_at',
                    render: function(data, type, row) {
                        if (data && data !== '0000-00-00 00:00:00') {
                            let d = new Date(data);
                            // Tampilan Tanggal & Jam Menit
                            let date = d.toLocaleDateString('id-ID', {day:'2-digit', month:'short'});
                            let time = d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
                            
                            // Jika update terjadi dalam 24 jam terakhir, beri tanda (opsional)
                            let isNew = (new Date() - d) < (24 * 60 * 60 * 1000);
                            let color = isNew ? 'text-primary' : 'text-gray-600';

                            return `<div class="d-flex flex-column">
                                        <span class="${color} fw-bold fs-7">${date} ${d.getFullYear()}</span>
                                        <span class="text-muted fs-8">${time}</span>
                                    </div>`;
                        }
                        return `<span class="text-muted fs-7">-</span>`;
                    }
                },
                {
                    data: 'status',
                    className: 'text-center',
                    render: function(data) {
                        if (data === 'paid') return '<span class="badge badge-success fs-8 fw-bold">LUNAS</span>';
                        if (data === 'partial') return '<span class="badge badge-warning fs-8 fw-bold">DICICIL</span>';
                        return '<span class="badge badge-light-danger fs-8 fw-bold">UNPAID</span>';
                    }
                },
                {
                    data: 'id',
                    className: 'text-end',
                    render: function(data, type, row) {
                        let sisa = parseFloat(row.amount) - parseFloat(row.paid_amount || 0) - parseFloat(row.discount || 0);
                        let btnBayar = (row.status !== 'paid') ? 
                            `<button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px me-1" onclick="bayarTagihan(${data}, ${sisa})">
                                <i class="ki-outline ki-dollar fs-2"></i>
                            </button>` : '';
                        
                        let btnRollback = (row.status !== 'unpaid') ? 
                            `<button class="btn btn-sm btn-icon btn-bg-light btn-active-color-warning w-30px h-30px me-1" onclick="rollbackTagihan(${data})">
                                <i class="ki-outline ki-arrows-loop fs-2"></i>
                            </button>` : '';

                        return `
                            <div class="d-flex justify-content-end">
                                ${btnBayar}
                                ${btnRollback}
                                <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-danger w-30px h-30px" onclick="hapusTagihan(${data})">
                                    <i class="ki-outline ki-trash fs-2"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ]
        });

        $('#filterBulan, #filterTahun').on('change', function() { table.ajax.reload(); });
        $('#searchTagihan').keyup(function() { table.search($(this).val()).draw(); });
    });

    // Fungsi Bayar dengan Input Angka & Diskon
    function bayarTagihan(id, sisa) {
        Swal.fire({
            title: 'Input Pembayaran',
            html: `
                <div class="text-start mb-3">
                    <label class="form-label fw-bold text-danger">Sisa Tagihan: Rp ${new Intl.NumberFormat('id-ID').format(sisa)}</label>
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label">Diskon</label>
                    <input type="number" id="swal-input-diskon" class="form-control" value="0" oninput="let sisa = ${sisa}; let diskon = this.value || 0; document.getElementById('swal-input-bayar').value = sisa - diskon;">
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label">Jumlah Uang Masuk (Dibayar)</label>
                    <input type="number" id="swal-input-bayar" class="form-control" value="${sisa}">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            preConfirm: () => {
                let amount = document.getElementById('swal-input-bayar').value;
                let diskon = document.getElementById('swal-input-diskon').value;

                if (!amount || amount < 0) {
                    Swal.showValidationMessage('Nominal tidak valid!');
                    return false;
                }

                // Client-side validation: prevent overpayment
                if (parseFloat(amount) > (sisa - parseFloat(diskon))) {
                    Swal.showValidationMessage('Nominal bayar + diskon melebihi sisa tagihan!');
                    return false;
                }

                return $.post('<?= base_url('tagihan/bayar/') ?>' + id, {
                    "<?= csrf_token() ?>": "<?= csrf_hash() ?>",
                    "jumlah_bayar": amount,
                    "diskon": diskon
                }).catch((xhr) => {
                    // Menangkap error dari server (seperti 400 Bad Request dari $this->fail)
                    let errorMsg = 'Terjadi kesalahan sistem';
                    if(xhr.responseJSON && xhr.responseJSON.messages && xhr.responseJSON.messages.error) {
                        errorMsg = xhr.responseJSON.messages.error;
                    }
                    Swal.showValidationMessage(errorMsg);
                    throw new Error(errorMsg); // Prevent SweetAlert dari tertutup
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Berhasil!', result.value.message, 'success');
                table.ajax.reload(null, false);
            }
        });
    }

    function rollbackTagihan(id) {
        Swal.fire({
            title: 'Rollback Pembayaran?',
            text: "Status akan di-reset dan saldo titipan jadi 0.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Reset'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url('tagihan/rollback/') ?>' + id, { "<?= csrf_token() ?>": "<?= csrf_hash() ?>" }, function(res) {
                    table.ajax.reload(null, false);
                });
            }
        });
    }

    function generateTagihan() {
        $.post('<?= base_url('tagihan/generate') ?>', { "<?= csrf_token() ?>": "<?= csrf_hash() ?>" }, function(res) {
            Swal.fire('Berhasil!', res.message, 'success');
            table.ajax.reload();
        });
    }

    function hapusTagihan(id) {
        Swal.fire({
            title: 'Hapus?',
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url('tagihan/delete/') ?>' + id, { "<?= csrf_token() ?>": "<?= csrf_hash() ?>" }, function(res) {
                    table.ajax.reload(null, false);
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>