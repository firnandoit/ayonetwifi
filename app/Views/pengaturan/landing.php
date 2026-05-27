<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc($title) ?>
            </h1>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Konfigurasi Teks Landing Page</h3>
            </div>
            <div class="card-body">
                <form action="<?= base_url('pengaturan/landing/save') ?>" method="POST">
                    <?= csrf_field() ?>

                    <?php foreach ($settings as $setting): ?>
                        <div class="mb-5">
                            <label class="form-label fw-bold"><?= esc($setting['description']) ?></label>
                            
                            <?php if ($setting['setting_type'] == 'textarea'): ?>
                                <textarea name="<?= esc($setting['setting_key']) ?>" class="form-control form-control-solid" rows="3"><?= esc($setting['setting_value']) ?></textarea>
                            <?php elseif ($setting['setting_type'] == 'color'): ?>
                                <input type="color" name="<?= esc($setting['setting_key']) ?>" class="form-control form-control-color w-100" style="height: 50px;" value="<?= esc($setting['setting_value']) ?>">
                            <?php else: ?>
                                <input type="text" name="<?= esc($setting['setting_key']) ?>" class="form-control form-control-solid" value="<?= esc($setting['setting_value']) ?>">
                            <?php endif; ?>
                            
                            <div class="text-muted fs-7 mt-1">Key: <code><?= esc($setting['setting_key']) ?></code></div>
                        </div>
                    <?php endforeach; ?>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-outline ki-save-2 fs-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<!--end::Content-->
<?= $this->endSection() ?>
