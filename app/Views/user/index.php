<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <!--begin::Card header-->
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                <input type="text" id="searchUser" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Pengguna">
            </div>
            <!--end::Search-->
        </div>

        

        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" onclick="addUser()">
                    <i class="ki-outline ki-plus fs-2"></i> Tambah User
                </button>
            </div>
        </div>
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed table-row-gray-300 table-hover fs-6 gy-5" id="table_user">
            <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <th class="min-w-150px">Nama Lengkap</th>
                    <th class="min-w-125px">Username</th>
                    <th class="min-w-125px">Role</th>
                    <th class="min-w-125px">Tgl Bergabung</th>
                    <th class="text-end min-w-100px">Aksi</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600"></tbody>
        </table>
    </div>
    <!--end::Card body-->
</div>

<!--begin::Modal - Add/Edit User-->
<div class="modal fade" id="modal_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form id="formUser" autocomplete="off">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="user_id">

                <div class="modal-header">
                    <h2 class="fw-bold" id="modal_title">Tambah User</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>

                <div class="modal-body py-10 px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Lengkap</label>
                        <input type="text" class="form-control form-control-solid" name="name" placeholder="Nama lengkap user" required />
                    </div>

                    <div class="row g-9 mb-7">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Username</label>
                            <input type="text" class="form-control form-control-solid" name="username" placeholder="Username untuk login" required />
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Role</label>
                            <select class="form-select form-select-solid" name="role" required>
                                <option value="">Pilih Role...</option>
                                <option value="admin">Admin</option>
                                <option value="customer">Customer</option>
                                <option value="owner">Owner</option>
                            </select>
                        </div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2" id="label_password">Password</label>
                        <input type="password" class="form-control form-control-solid" name="password" id="password_input" placeholder="Minimal 5 karakter" />
                        <div class="text-muted fs-7 mt-2" id="password_note" style="display:none;">
                            *Kosongkan password jika tidak ingin mengubah password lama.
                        </div>
                    </div>
                </div>

                <div class="modal-footer flex-center">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSimpanUser" class="btn btn-primary">
                        <span class="indicator-label">Simpan Data</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Modal-->

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    var table;

    jQuery(document).ready(function($) {
        // Initialize DataTable
        table = $('#table_user').DataTable({
            processing: true,
            ajax: '<?= base_url('user/list') ?>',
            columns: [{
                    data: 'name'
                },
                {
                    data: 'username'
                },
                {
                    data: 'role',
                    render: function(data) {
                        let color = 'primary';
                        if (data === 'owner') color = 'danger';
                        if (data === 'customer') color = 'success';
                        return `<span class="badge badge-light-${color} fw-bold text-uppercase">${data}</span>`;
                    }
                },
                {
                    data: 'created_at',
                    render: function(data) {
                        return window.moment ? moment(data).format('DD MMM YYYY') : data;
                    }
                },
                {
                    data: 'id',
                    className: 'text-end',
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-sm btn-light-primary btn-icon me-2" onclick="editUser(${data})" title="Edit">
                                    <i class="ki-outline ki-pencil fs-2"></i>
                                </button>
                                <button class="btn btn-sm btn-light-danger btn-icon" onclick="deleteUser(${data})" title="Hapus">
                                    <i class="ki-outline ki-trash fs-2"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ]
        });

        // Search handler
        $('#searchUser').keyup(function() {
            table.search($(this).val()).draw();
        });

        // AJAX Save handler
        $('#formUser').on('submit', function(e) {
            e.preventDefault();
            let btn = $('#btnSimpanUser');

            $.ajax({
                url: '<?= base_url('user/save') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function() {
                    btn.attr('disabled', true).text('Menyimpan...');
                },
                success: function(res) {
                    Swal.fire('Berhasil!', res.message, 'success');
                    $('#modal_user').modal('hide');
                    if ($('#user_id').val()) {
                        table.ajax.reload(null, false);
                    } else {
                        table.ajax.reload();
                    }
                },
                error: function(err) {
                    let msg = err.responseJSON ? JSON.stringify(err.responseJSON.message) : 'Gagal menyimpan data';
                    Swal.fire('Error', msg, 'error');
                },
                complete: function() {
                    btn.attr('disabled', false).text('Simpan Data');
                }
            });
        });
    });

    // Fungsi Tambah User
    function addUser() {
        $('#formUser')[0].reset();
        $('#user_id').val('');
        $('#modal_title').text('Tambah Pengguna Baru');

        // Saat tambah baru, password WAJIB diisi
        $('#label_password').addClass('required');
        $('#password_input').attr('required', true);
        $('#password_note').hide();

        $('#modal_user').modal('show');
    }

    // Fungsi Edit User
    function editUser(id) {
        $.get('<?= base_url('user/show/') ?>' + id, function(res) {
            $('#user_id').val(res.id);
            $('[name="name"]').val(res.name);
            $('[name="username"]').val(res.username);
            $('[name="role"]').val(res.role);

            $('#modal_title').text('Edit Pengguna');

            // Saat edit, password TIDAK WAJIB diisi
            $('#label_password').removeClass('required');
            $('#password_input').attr('required', false);
            $('#password_note').show();

            $('#modal_user').modal('show');
        });
    }

    // Fungsi Hapus User
    function deleteUser(id) {
        Swal.fire({
            title: 'Hapus Pengguna?',
            text: "User ini tidak akan bisa login lagi setelah dihapus.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('user/delete/') ?>' + id,
                    type: 'POST',
                    data: {
                        "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                    },
                    success: function(res) {
                        Swal.fire('Terhapus!', res.message, 'success');
                        table.ajax.reload(null, false);
                    },
                    error: function(err) {
                        let msg = err.responseJSON ? err.responseJSON.message : 'Gagal menghapus user';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>