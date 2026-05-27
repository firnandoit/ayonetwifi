<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row g-5 g-xxl-8">
    <div class="col-xl-4">
        <!-- Profile Card -->
        <div class="card mb-5 mb-xl-8">
            <div class="card-body pt-15">
                <div class="d-flex flex-center flex-column mb-5">
                    <div class="symbol symbol-100px symbol-circle mb-7">
                        <!-- Custom Initial Avatar -->
                        <div class="symbol-label fs-1 fw-bold text-primary bg-light-primary">
                            <?= strtoupper(substr(esc($user['name']), 0, 1)) ?>
                        </div>
                    </div>
                    <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">
                        <?= esc($user['name']) ?>
                    </a>
                    <div class="fs-5 fw-semibold text-muted mb-6">
                        <?= esc(ucfirst($user['role'])) ?>
                    </div>
                </div>

                <div class="d-flex flex-stack fs-4 py-3">
                    <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_customer_view_details" role="button" aria-expanded="false" aria-controls="kt_customer_view_details">
                        Detail Akun
                        <span class="ms-2 rotate-180">
                            <i class="ki-duotone ki-down fs-3"></i>
                        </span>
                    </div>
                </div>

                <div class="separator separator-dashed my-3"></div>

                <div id="kt_customer_view_details" class="collapse show">
                    <div class="pb-5 fs-6">
                        <div class="fw-bold mt-5">Username</div>
                        <div class="text-gray-600"><?= esc($user['username']) ?></div>
                        
                        <div class="fw-bold mt-5">Terdaftar Sejak</div>
                        <div class="text-gray-600"><?= date('d M Y', strtotime($user['created_at'])) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <!-- Change Password Card -->
        <div class="card mb-5 mb-xl-8">
            <div class="card-header border-0 cursor-pointer" role="button">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Ubah Password</h3>
                </div>
            </div>
            
            <div class="collapse show">
                <form id="formUbahPassword" class="form">
                    <?= csrf_field() ?>
                    <div class="card-body border-top p-9">
                        
                        <?php if(session()->get('role') !== 'owner'): ?>
                        <!-- Old Password -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Password Lama</label>
                            <div class="col-lg-8 fv-row">
                                <input type="password" name="old_password" class="form-control form-control-lg form-control-solid" placeholder="Masukkan password lama" />
                                <div class="invalid-feedback" id="error_old_password"></div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- New Password -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Password Baru</label>
                            <div class="col-lg-8 fv-row">
                                <input type="password" name="new_password" class="form-control form-control-lg form-control-solid" placeholder="Minimal 5 karakter" />
                                <div class="invalid-feedback" id="error_new_password"></div>
                            </div>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Konfirmasi Password Baru</label>
                            <div class="col-lg-8 fv-row">
                                <input type="password" name="confirm_password" class="form-control form-control-lg form-control-solid" placeholder="Ketik ulang password baru" />
                                <div class="invalid-feedback" id="error_confirm_password"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="reset" class="btn btn-light btn-active-light-primary me-2">Reset</button>
                        <button type="submit" class="btn btn-primary" id="btnSimpanPassword">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    $(document).ready(function() {
        $('#formUbahPassword').on('submit', function(e) {
            e.preventDefault();
            
            // Clear errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            const btn = $('#btnSimpanPassword');
            btn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: '<?= base_url('profile/update-password') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    Swal.fire('Berhasil', res.message, 'success');
                    $('#formUbahPassword')[0].reset();
                },
                error: function(err) {
                    if (err.status === 400 && err.responseJSON && err.responseJSON.messages) {
                        let errors = err.responseJSON.messages;
                        for (let field in errors) {
                            $(`[name="${field}"]`).addClass('is-invalid');
                            $(`#error_${field}`).text(errors[field]);
                        }
                    } else if(err.responseJSON && err.responseJSON.message) {
                        let errors = err.responseJSON.message;
                        if(typeof errors === 'object') {
                             for (let field in errors) {
                                $(`[name="${field}"]`).addClass('is-invalid');
                                $(`#error_${field}`).text(errors[field]);
                            }
                        } else {
                            Swal.fire('Gagal', errors, 'error');
                        }
                    } else {
                        Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).text('Simpan Perubahan');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
