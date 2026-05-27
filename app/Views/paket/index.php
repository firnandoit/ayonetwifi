<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    /* Styling agar nominal harga terlihat tegas */
    .text-amount {
        color: #181c32;
        font-weight: 700;
    }
    /* Efek hover pada baris tabel */
    #table_paket tbody tr:hover {
        background-color: rgba(0, 158, 247, 0.02);
        transition: all 0.2s ease;
    }
</style>

<div class="card card-flush">
    <!--begin::Card header-->
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                <input type="text" id="searchPaket" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Nama Paket...">
            </div>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-primary" onclick="addPackage()">
                <i class="ki-outline ki-plus fs-2"></i> Tambah Paket
            </button>
        </div>
    </div>

    <!--begin::Card body-->
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed table-row-gray-300 table-hover fs-6 gy-5" id="table_paket">
            <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <th class="min-w-150px">Nama Paket</th>
                    <th class="min-w-100px">Kecepatan</th>
                    <th class="min-w-125px text-end">Harga Bulanan</th>
                    <th class="min-w-200px">Deskripsi</th>
                    <th class="text-end min-w-100px">Aksi</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600"></tbody>
        </table>
    </div>
</div>

<!-- Modal Add/Edit -->
<div class="modal fade" id="modal_paket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded">
            <form id="formPaket">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="paket_id">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <div class="mb-13 text-center">
                        <h1 class="mb-3" id="modal_title">Tambah Paket</h1>
                        <div class="text-muted fw-semibold fs-5">Silakan isi detail paket layanan internet Anda.</div>
                    </div>
                    
                    <div class="d-flex flex-column mb-8 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                            <span class="required">Nama Paket</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="name" placeholder="Contoh: Paket Home 1" required />
                    </div>

                    <div class="row g-9 mb-8">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-bold mb-2">Kecepatan</label>
                            <input type="text" class="form-control form-control-solid" name="speed" placeholder="Contoh: 10 Mbps" required />
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-bold mb-2">Harga (Rp)</label>
                            <input type="number" class="form-control form-control-solid" name="price" placeholder="150000" required />
                        </div>
                    </div>

                    <div class="d-flex flex-column mb-8">
                        <label class="fs-6 fw-bold mb-2">Deskripsi Layanan</label>
                        <textarea class="form-control form-control-solid" name="description" rows="3" placeholder="Jelaskan detail paket..."></textarea>
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnSimpan" class="btn btn-primary">
                            <span class="indicator-label">Simpan Perubahan</span>
                        </button>
                    </div>
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
        table = $('#table_paket').DataTable({
            processing: true,
            ajax: '<?= base_url('paket/list') ?>',
            columns: [
                {
                    data: 'name',
                    render: function(data) {
                        return `<span class="text-gray-800 fw-bold">${data}</span>`;
                    }
                },
                {
                    data: 'speed',
                    render: function(data) {
                        return `<span class="badge badge-light-primary fw-bold fs-7 px-3 py-2">${data}</span>`;
                    }
                },
                {
                    data: 'price',
                    className: 'text-end',
                    render: function(data) {
                        let formatted = new Intl.NumberFormat('id-ID').format(data);
                        return `<span class="text-amount">Rp ${formatted}</span>`;
                    }
                },
                {
                    data: 'description',
                    render: function(data) {
                        return `<span class="text-muted">${data || '-'}</span>`;
                    }
                },
                {
                    data: 'id',
                    className: 'text-end',
                    render: function(data) {
                        return `
                            <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" onclick="editPackage(${data})" title="Edit Paket">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                            <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-danger" onclick="deletePackage(${data})" title="Hapus Paket">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </button>`;
                    }
                }
            ]
        });

        // Filter Pencarian
        $('#searchPaket').keyup(function() {
            table.search($(this).val()).draw();
        });

        // Form Submit
        $('#formPaket').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '<?= base_url('paket/save') ?>',
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    Swal.fire({
                        text: res.message,
                        icon: 'success',
                        buttonsStyling: false,
                        confirmButtonText: "OK",
                        customClass: { confirmButton: "btn btn-primary" }
                    });
                    $('#modal_paket').modal('hide');
                    if ($('#paket_id').val()) {
                        table.ajax.reload(null, false);
                    } else {
                        table.ajax.reload();
                    }
                }
            });
        });
    });

    function addPackage() {
        $('#formPaket')[0].reset();
        $('#paket_id').val('');
        $('#modal_title').text('Tambah Paket Baru');
        $('#modal_paket').modal('show');
    }

    function editPackage(id) {
        $.get('<?= base_url('paket/show/') ?>' + id, function(res) {
            $('#paket_id').val(res.id);
            $('[name="name"]').val(res.name);
            $('[name="speed"]').val(res.speed);
            $('[name="price"]').val(res.price);
            $('[name="description"]').val(res.description);
            $('#modal_title').text('Edit Paket');
            $('#modal_paket').modal('show');
        });
    }

    function deletePackage(id) {
        Swal.fire({
            title: 'Hapus Paket?',
            text: "Data paket yang sudah dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-light"
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url('paket/delete/') ?>' + id, {
                    "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                }, function(res) {
                    Swal.fire({ text: 'Paket berhasil dihapus.', icon: 'success', buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-primary' } });
                    table.ajax.reload(null, false);
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>