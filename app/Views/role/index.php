<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold">Manajemen Hak Akses Menu Per Role</h3>
        </div>
    </div>
    
    <div class="card-body py-4">
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 min-w-50px rounded-start">No</th>
                        <th class="min-w-150px">Nama Role</th>
                        <th class="min-w-300px">Menu Diizinkan</th>
                        <th class="min-w-100px text-end rounded-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($roles as $r) : ?>
                        <tr>
                            <td class="ps-4"><?= $no++ ?></td>
                            <td>
                                <span class="badge badge-light-success fw-bold px-4 py-3 text-uppercase"><?= $r['role'] ?></span>
                            </td>
                            <td>
                                <div class="text-gray-600 fw-semibold fs-7">
                                    <?= (!empty($r['menu_diizinkan'])) ? $r['menu_diizinkan'] : '<span class="text-muted italic">Belum ada menu diizinkan</span>' ?>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" 
                                    onclick="editAkses('<?= $r['role'] ?>')" title="Atur Hak Akses">
                                    <i class="ki-outline ki-pencil fs-2"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Akses -->
<div class="modal fade" id="modalAkses" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="formAkses" class="form w-100">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Level Akses: <span id="txt_role" class="text-primary text-uppercase"></span></h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>

                <div class="modal-body py-10 px-lg-17">
                    <input type="hidden" name="role_name" id="inp_role">

                    <div class="fv-row">
                        <label class="fs-5 fw-bold mb-5 d-block">Pilih Menu yang Diizinkan:</label>
                        <div class="row g-7">
                            <?php foreach ($all_menus as $m) : ?>
                                <div class="col-md-6">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input h-25px w-25px chk-menu" type="checkbox" 
                                            name="menu_ids[]" value="<?= $m['id_menu'] ?>" id="m_<?= $m['id_menu'] ?>">
                                        <label class="form-check-label fw-semibold fs-6 text-gray-800" for="m_<?= $m['id_menu'] ?>">
                                            <?= $m['nama_menu'] ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="modal-footer flex-center">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">
                        <span class="indicator-label">Simpan Perubahan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    function editAkses(role) {
        $('#txt_role').text(role);
        $('#inp_role').val(role);
        $('.chk-menu').prop('checked', false); // Reset semua centang

        // Ambil data menu yang sudah diizinkan via AJAX
        $.get("<?= base_url('role/ambil_akses') ?>/" + role, function(res) {
            res.forEach(item => {
                $(`#m_${item.id_menu}`).prop('checked', true);
            });
            $('#modalAkses').modal('show');
        });
    }

    $('#formAkses').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#btnSimpan');
        btn.attr('disabled', true).text('Menyimpan...');

        $.ajax({
            url: "<?= base_url('role/simpan') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(res) {
                if (res.status) {
                    Swal.fire('Berhasil!', res.pesan, 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Gagal!', res.pesan, 'error');
                    btn.attr('disabled', false).text('Simpan Perubahan');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Gagal menghubungi server', 'error');
                btn.attr('disabled', false).text('Simpan Perubahan');
            }
        });
    });
</script>
<?= $this->endSection() ?>