<!doctype html>
<!--
Author: Keenthemes
Product Name: MetronicProduct Version: 8.2.9
Purchase: https://1.envato.market/Vm7VRE
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
  <title>
    <?= esc($settings['site_name'] ?? 'Humanika WiFi') ?> - Solusi Internet Cepat, Stabil, dan Tanpa Kuota
  </title>
  <meta charset="utf-8" />
  <meta
    name="description"
    content="Humanika WiFi menyediakan layanan internet super cepat, stabil, dan tanpa kuota untuk kebutuhan rumah maupun bisnis Anda. Nikmati koneksi andal setiap saat." />
  <meta
    name="keywords"
    content="wifi, humanika, internet cepat, wifi rumah, internet tanpa kuota, provider internet lokal, pasang wifi" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta property="og:locale" content="id_ID" />
  <meta property="og:type" content="website" />
  <meta
    property="og:title"
    content="<?= esc($settings['site_name'] ?? 'Humanika WiFi') ?> - Internet Super Cepat" />
  <meta property="og:url" content="<?= base_url() ?>" />
  <meta property="og:site_name" content="<?= esc($settings['site_name'] ?? 'Humanika WiFi') ?>" />
  <link rel="canonical" href="<?= base_url() ?>" />
  <link rel="shortcut icon" href="<?= base_url('assets/') ?>media/logos/favicon.ico " />
  <!--begin::Fonts(mandatory for all pages)-->
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <!--end::Fonts-->
  <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
  <link
    href="<?= base_url('assets/') ?>plugins/global/plugins.bundle.css"
    rel="stylesheet"
    type="text/css" />
  <link href="<?= base_url('assets/') ?>css/style.bundle.css" rel="stylesheet" type="text/css" />
  <style>
    body {
      font-family: 'Outfit', sans-serif !important;
    }

    <?php
    $primaryColor = $settings['landing_primary_color'] ?? '#009ef7';
    $bgColor = $settings['landing_bg_color'] ?? '#1e1e2d';
    ?> :root,
    [data-bs-theme="light"] {
      --bs-primary: <?= esc($primaryColor) ?> !important;
      --bs-primary-active: <?= esc($primaryColor) ?> !important;
      --bs-primary-light: <?= esc($primaryColor) ?>20 !important;
      /* 20 is for alpha transparency */
      --bs-primary-inverse: #ffffff !important;

      --bs-success: #50CD89 !important;
      --bs-success-active: #47BE7D !important;
      --bs-success-light: #E8FFF3 !important;
      --bs-success-inverse: #ffffff !important;
    }

    [data-bs-theme="dark"] {
      --bs-primary: <?= esc($primaryColor) ?> !important;
      --bs-primary-active: <?= esc($primaryColor) ?> !important;
      --bs-primary-light: <?= esc($primaryColor) ?>20 !important;
      --bs-primary-inverse: #ffffff !important;

      --bs-success: #50CD89 !important;
      --bs-success-active: #47BE7D !important;
      --bs-success-light: #273B4A !important;
      --bs-success-inverse: #ffffff !important;
    }

    .landing-dark-bg {
      background-color: <?= esc($bgColor) ?> !important;
    }

    .landing-dark-border {
      border-color: rgba(255, 255, 255, 0.15) !important;
    }

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
  <!--end::Global Stylesheets Bundle-->
  <script>
    // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
  </script>
</head>
<!--end::Head-->
<!--begin::Body-->

<body
  id="kt_body"
  data-bs-spy="scroll"
  data-bs-target="#kt_landing_menu"
  class="bg-body position-relative">
  <!--begin::Theme mode setup on page load-->
  <script>
    var defaultThemeMode = "light";
    var themeMode;
    if (document.documentElement) {
      if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
        themeMode =
          document.documentElement.getAttribute("data-bs-theme-mode");
      } else {
        if (localStorage.getItem("data-bs-theme") !== null) {
          themeMode = localStorage.getItem("data-bs-theme");
        } else {
          themeMode = defaultThemeMode;
        }
      }
      if (themeMode === "system") {
        themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ?
          "dark" :
          "light";
      }
      document.documentElement.setAttribute("data-bs-theme", themeMode);
    }
  </script>
  <!--end::Theme mode setup on page load-->
  <!--begin::Main-->
  <!--begin::Root-->
  <div class="d-flex flex-column flex-root">
    <?= $this->include('landing/header') ?>
    <?= $this->include('landing/how_it_works') ?>
    <?= $this->include('landing/statistics') ?>
    <?= $this->include('landing/projects') ?>
    <?= $this->include('landing/pricing') ?>
    <?= $this->include('landing/testimonials') ?>
    <?= $this->include('landing/footer') ?>
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
      <i class="ki-duotone ki-arrow-up">
        <span class="path1"></span>
        <span class="path2"></span>
      </i>
    </div>
    <!--end::Scrolltop-->
  </div>
  <!--end::Root-->
  <!--end::Main-->
  <!--begin::Scrolltop-->
  <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
    <i class="ki-duotone ki-arrow-up">
      <span class="path1"></span>
      <span class="path2"></span>
    </i>
  </div>
  <!--end::Scrolltop-->
  <!--begin::Javascript-->
  <script>
    var hostUrl = "assets/";
  </script>
  <!--begin::Global Javascript Bundle(mandatory for all pages)-->
  <script src="<?= base_url('assets/') ?>plugins/global/plugins.bundle.js"></script>
  <script src="<?= base_url('assets/') ?>js/scripts.bundle.js"></script>
  <!--end::Global Javascript Bundle-->
  <!--begin::Vendors Javascript(used for this page only)-->
  <script src="<?= base_url('assets/') ?>plugins/custom/fslightbox/fslightbox.bundle.js"></script>
  <script src="<?= base_url('assets/') ?>plugins/custom/typedjs/typedjs.bundle.js"></script>
  <!--end::Vendors Javascript-->
  <!--begin::Custom Javascript(used for this page only)-->
  <script src="<?= base_url('assets/') ?>js/custom/landing.js"></script>
  <script src="<?= base_url('assets/') ?>js/custom/pages/pricing/general.js"></script>
  <!--end::Custom Javascript-->
  <!--end::Javascript-->

  <!--begin::Udify Chat Widget Custom-->
  <style>
    #dify-widget-container {
      position: fixed;
      bottom: 30px;
      right: 30px;
      z-index: 99999;
      font-family: 'Outfit', sans-serif;
    }

    #dify-chat-window {
      display: none;
      width: 380px;
      height: 580px;
      background: white;
      border-radius: 16px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      overflow: hidden;
      margin-bottom: 16px;
      border: 1px solid rgba(0, 0, 0, 0.1);
    }

    /* Animasi Pulse untuk tombol chat */
    @keyframes pulse-chat {
      0% {
        box-shadow: 0 0 0 0 var(--bs-primary-light);
      }

      70% {
        box-shadow: 0 0 0 15px transparent;
      }

      100% {
        box-shadow: 0 0 0 0 transparent;
      }
    }

    #dify-widget-container button {
      animation: pulse-chat 2s infinite;
    }

    #dify-widget-container button:hover {
      animation: none;
      transform: translateY(-2px);
    }

    /* Responsive styling untuk mobile */
    @media (max-width: 768px) {
      #dify-widget-container {
        bottom: 20px;
        right: 20px;
        left: 20px;
        /* Opsional: Membuat posisi ke tengah/full width container */
      }

      #dify-chat-window {
        width: 100%;
        height: calc(100vh - 120px);
        /* Menyesuaikan tinggi layar mobile */
        max-height: 600px;
      }

      #dify-widget-container button {
        padding: 12px 20px !important;
        font-size: 14px !important;
        margin-left: auto;
        /* Agar tombol tetap di kanan di layar mobile jika full width */
      }
    }
  </style>

  <div id="dify-widget-container">
    <div id="dify-chat-window">
      <iframe id="dify-iframe" data-src="https://udify.app/chat/LfHjOUSQypqTZcBl" style="width: 100%; height: 100%; border: none;" allow="microphone"></iframe>
    </div>

    <button onclick="toggleDifyChat()" style="float: right; background-color: var(--bs-primary); color: #ffffff; border: none; padding: 14px 24px; border-radius: 30px; cursor: pointer; font-size: 15px; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15); display: flex; align-items: center; gap: 8px; transition: all 0.2s ease;">
      <span id="dify-icon">💬</span> <span id="dify-text">Tanya Humanika WiFi</span>
    </button>
  </div>

  <script>
    function toggleDifyChat() {
      var chatWin = document.getElementById('dify-chat-window');
      var iframe = document.getElementById('dify-iframe');
      var btnText = document.getElementById('dify-text');
      var btnIcon = document.getElementById('dify-icon');

      // Lazy load iframe: link baru dimuat saat user pertama kali klik tombol
      if (!iframe.src) {
        iframe.src = iframe.getAttribute('data-src');
      }

      if (chatWin.style.display === 'none' || chatWin.style.display === '') {
        chatWin.style.display = 'block';
        btnText.innerText = 'Tutup';
        btnIcon.innerText = '✖';
      } else {
        chatWin.style.display = 'none';
        btnText.innerText = 'Tanya Humanika WiFi';
        btnIcon.innerText = '💬';
      }
    }
  </script>
  <!--end::Udify Chat Widget Custom-->
</body>
<!--end::Body-->

</html>