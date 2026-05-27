<!doctype html>
<html lang="en">
  <head>
    <title><?= esc($title) ?> - Ayonet WiFi</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="<?= base_url('assets/') ?>media/logos/favicon.ico" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="<?= base_url('assets/') ?>plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/') ?>css/style.bundle.css" rel="stylesheet" type="text/css" />
    <style>
      body { font-family: 'Outfit', sans-serif !important; }
      <?php
      $primaryColor = $settings['landing_primary_color'] ?? '#009ef7';
      $bgColor = $settings['landing_bg_color'] ?? '#1e1e2d';
      ?>
      :root, [data-bs-theme="light"] {
        --bs-primary: <?= esc($primaryColor) ?> !important;
        --bs-primary-active: <?= esc($primaryColor) ?> !important;
        --bs-success: #50CD89 !important;
      }
      .landing-dark-bg { background-color: <?= esc($bgColor) ?> !important; }
      
      /* Fix hover colors */
      .text-hover-primary:hover {
        color: var(--bs-primary) !important;
      }
      
      /* Fix menu active colors */
      .menu-state-title-primary .menu-item .menu-link.active,
      .menu-state-title-primary .menu-item .menu-link:hover {
        color: var(--bs-primary) !important;
      }
    </style>
  </head>
  <body id="kt_body" class="bg-body position-relative">
    <div class="d-flex flex-column flex-root">
      
      <!--begin::Header Section-->
      <div class="mb-0" id="home">
        <div class="bgi-no-repeat bgi-size-cover bgi-position-center landing-dark-bg" 
             style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('<?= esc($settings['landing_bg_image'] ?? 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?q=80&w=2070&auto=format&fit=crop') ?>'); background-attachment: fixed;">
          <div class="landing-header" data-kt-sticky="true" data-kt-sticky-name="landing-header" data-kt-sticky-offset="{default: '100px', lg: '150px'}">
            <div class="container">
              <div class="d-flex align-items-center justify-content-between py-5">
                <a href="<?= base_url() ?>">
                  <img alt="Logo" src="<?= base_url('uploads/logo/ayonet2.png') ?>" class="h-25px h-lg-30px" />
                </a>
                <div class="d-lg-block">
                  <a href="<?= base_url() ?>" class="btn btn-primary">Kembali ke Beranda</a>
                </div>
              </div>
            </div>
          </div>
          <!-- Hero short -->
          <div class="d-flex flex-column flex-center w-100 min-h-200px px-9">
            <h1 class="text-white lh-base fw-bold fs-2x fs-lg-3x mb-5"><?= esc($title) ?></h1>
          </div>
        </div>
      </div>
      <!--end::Header Section-->

      <!--begin::Content-->
      <div class="container py-20">
        <div class="card shadow-sm">
          <div class="card-body p-10 fs-4 text-gray-700 lh-lg">
            <?= $this->renderSection('content') ?>
          </div>
        </div>
      </div>
      <!--end::Content-->

      <!-- FOOTER -->
      <?= $this->include('landing/footer') ?>
      
    </div>
    <script src="<?= base_url('assets/') ?>plugins/global/plugins.bundle.js"></script>
    <script src="<?= base_url('assets/') ?>js/scripts.bundle.js"></script>
  </body>
</html>
