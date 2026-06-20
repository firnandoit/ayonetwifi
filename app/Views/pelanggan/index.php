<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span class="path2"></span></i>
                <input type="text" id="searchTable" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Pelanggan">
            </div>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-primary" onclick="addCustomer()">
                <i class="ki-duotone ki-plus fs-2"></i> Tambah Pelanggan
            </button>
        </div>
    </div>

    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed table-row-gray-300 table-hover fs-6 gy-5" id="kt_customers_table">
            <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">Nama Pelanggan</th>
                    <th class="min-w-100px">No. HP</th>
                    <th class="min-w-100px">Paket</th>
                    <th class="min-w-150px">Alamat</th>
                    <th class="min-w-100px">Status</th>
                    <th class="min-w-100px">Tgl Pasang</th>
                    <th class="text-end min-w-70px">Aksi</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600"></tbody>
        </table>
    </div>
</div>

<!-- Modal Add/Edit -->
<div class="modal fade" id="kt_modal_customer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form id="formCustomer" autocomplete="off">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="customer_id">

                <div class="modal-header">
                    <h2 class="fw-bold" id="modal_title">Tambah Pelanggan</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>

                <div class="modal-body py-10 px-lg-17">
                    <!-- Dropdown Hubungkan Akun User -->
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Hubungkan ke Akun User</label>
                        <select class="form-select form-select-solid" name="user_id" data-control="select2" data-dropdown-parent="#kt_modal_customer" data-placeholder="Pilih Akun (Opsional)..." data-allow-clear="true">
                            <option></option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= $u['name'] ?> (<?= $u['username'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <div class="text-muted fs-7 mt-2">Gunakan ini jika pelanggan sudah memiliki akun di sistem.</div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Pelanggan</label>
                        <input type="text" class="form-control form-control-solid" name="name" required />
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">No. HP</label>
                        <input type="text" class="form-control form-control-solid" name="phone" required />
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Alamat</label>
                        <textarea class="form-control form-control-solid" name="address" rows="3" required></textarea>
                    </div>

                    <div class="row g-9 mb-7">
                        <div class="col-md-6">
                            <label class="required fs-6 fw-semibold mb-2">Paket</label>
                            <select class="form-select form-select-solid" name="package_id" required>
                                <option value="">Pilih Paket...</option>
                                <?php foreach ($packages as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="required fs-6 fw-semibold mb-2">Tgl Pasang</label>
                            <input type="date" class="form-control form-control-solid" name="installation_date" required />
                        </div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Status Berlangganan</label>
                        <select class="form-select form-select-solid" name="status" required>
                            <option value="aktif">Aktif (Internet ON)</option>
                            <option value="isolir">Isolir (Internet Mati - Menunggak)</option>
                            <option value="putus">Putus (Berhenti Berlangganan)</option>
                        </select>
                        <div class="text-muted fs-7 mt-2">Pilih 'Isolir' untuk mematikan akses internet pelanggan secara manual.</div>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">IP Address Perangkat</label>
                        <input type="text" class="form-control form-control-solid" name="ip_address" placeholder="Contoh: 192.168.0.50" required />
                        <div class="text-muted fs-7 mt-2">Masukkan IP target perangkat customer untuk simulasi isolir otomatis.</div>
                    </div>
                </div>

                <div class="modal-footer flex-center">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSimpan" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    var table;
    jQuery(document).ready(function($) {

        if ($('#kt_customers_table').length) {
            table = $('#kt_customers_table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '<?= base_url('pelanggan/list') ?>',
                    dataSrc: 'data'
                },

                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'package_name',
                        render: function(data) {
                            return `<span class="badge badge-bold badge-light-info">${data || '-'}</span>`;
                        }
                    },
                    {
                        data: 'address'
                    },
                    // Di dalam columns DataTable
                    {
                        data: 'status',
                        render: function(data) {
                            if (data === 'aktif') {
                                return '<span class="badge badge-light-success fw-bold">AKTIF</span>';
                            } else if (data === 'isolir') {
                                return '<span class="badge badge-light-warning fw-bold">ISOLIR</span>';
                            } else {
                                return '<span class="badge badge-light-danger fw-bold">PUTUS</span>';
                            }
                        }
                    },
                    {
                        data: 'installation_date'
                    },
                    {
                        data: 'id',
                        className: 'text-end',
                        render: function(data) {
                            return `
                                <button type="button" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" onclick="editCustomer(${data})">
                                    <i class="ki-outline ki-pencil fs-2"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-bg-light btn-active-color-danger" onclick="deleteCustomer(${data})">
                                    <i class="ki-outline ki-trash fs-2"></i>
                                </button>`;
                        }
                    }
                ]
            });
            // --- TAMBAHKAN KODE INI AGAR FILTER BERJALAN ---
            $('#searchTable').on('keyup', function() {
                table.search(this.value).draw();
            });
        }

        $('#formCustomer').on('submit', function(e) {
            e.preventDefault();
            const btn = $('#btnSimpan');
            btn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: '<?= base_url('pelanggan/save') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    Swal.fire('Berhasil', res.message, 'success');
                    $('#kt_modal_customer').modal('hide');

                    // UX Improvement: Stay on current page if editing, go to page 1 if adding new
                    if ($('#customer_id').val()) {
                        table.ajax.reload(null, false);
                    } else {
                        table.ajax.reload();
                    }
                },
                error: function(err) {
                    Swal.fire('Error', 'Gagal menyimpan data', 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Simpan');
                }
            });
        });
    });

    function addCustomer() {
        jQuery('#formCustomer')[0].reset();
        jQuery('#customer_id').val('');
        // Reset Select2 Akun User
        jQuery('[name="user_id"]').val(null).trigger('change');
        jQuery('#modal_title').text('Tambah Pelanggan');
        jQuery('#kt_modal_customer').modal('show');
    }

    function editCustomer(id) {
        $.get('<?= base_url('pelanggan/show/') ?>' + id, function(res) {
            $('#customer_id').val(res.id);
            $('[name="name"]').val(res.name);
            $('[name="phone"]').val(res.phone);
            $('[name="address"]').val(res.address);
            $('[name="package_id"]').val(res.package_id);
            $('[name="user_id"]').val(res.user_id).trigger('change');
            $('[name="installation_date"]').val(res.installation_date);
            // Isi kotak input ip_address berdasarkan data dari database
            $('[name="ip_address"]').val(res.ip_address);

            // Pilih status sesuai data di database
            $('[name="status"]').val(res.status).trigger('change');

            $('#modal_title').text('Edit Pelanggan');
            $('#kt_modal_customer').modal('show');
        });
    }

    function deleteCustomer(id) {
        Swal.fire({
            title: 'Hapus data?',
            text: "Data pelanggan akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                // --- PERBAIKAN DI SINI: MEMBACA TOKEN SECARA DINAMIS DARI INPUT HIDDEN VIEW ---
                var csrfName = '<?= csrf_token() ?>';
                var csrfHash = $('input[name="' + csrfName + '"]').val() || '<?= csrf_hash() ?>';

                var dataPost = {
                    [csrfName]: csrfHash
                };

                $.post('<?= base_url('pelanggan/delete/') ?>' + id, dataPost, function(res) {
                    if (res.status) {
                        Swal.fire('Terhapus', res.message, 'success');
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire('Gagal', 'Terjadi kesalahan sistem', 'error');
                    }
                }).fail(function() {
                    Swal.fire('Error', 'Gagal menghubungi server', 'error');
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>