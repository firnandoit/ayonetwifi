<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?? 'Humanika WiFi' ?></title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

    <!-- Global Stylesheets Bundle -->
    <link href="<?= base_url('assets/plugins/global/plugins.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/css/style.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/plugins/custom/datatables/datatables.bundle.css') ?>" rel="stylesheet" type="text/css" />

    <style>
        :root,
        [data-bs-theme="light"] {
            --bs-primary: #009EF7 !important;
            --bs-primary-active: #0095E8 !important;
            --bs-primary-light: #F1FAFF !important;
            --bs-primary-inverse: #ffffff !important;

            --bs-success: #50CD89 !important;
            --bs-success-active: #47BE7D !important;
            --bs-success-light: #E8FFF3 !important;
            --bs-success-inverse: #ffffff !important;
        }

        [data-bs-theme="dark"] {
            --bs-primary: #009EF7 !important;
            --bs-primary-active: #0095E8 !important;
            --bs-primary-light: #21325B !important;
            --bs-primary-inverse: #ffffff !important;

            --bs-success: #50CD89 !important;
            --bs-success-active: #47BE7D !important;
            --bs-success-light: #273B4A !important;
            --bs-success-inverse: #ffffff !important;
        }

        body {
            font-family: 'Outfit', sans-serif !important;
        }

        #kt_header_menu .menu-link {
            color: #181c32 !important;
        }

        #kt_header_menu .menu-link.active {
            color: #009ef7 !important;
            background-color: rgba(0, 158, 247, 0.1) !important;
            border-radius: 8px;
        }

        /* --- STYLING TAMBAHAN UNTUK HEADER SESUAI GAMBAR --- */
        .search-container-custom {
            flex-grow: 1;
            max-width: 450px;
            margin-left: 2rem;
        }

        .search-input-custom {
            background-color: #F9F9F9 !important;
            border: 1px solid #E1E3EA !important;
            border-radius: 12px !important;
            padding: 0.75rem 1rem 0.75rem 3rem !important;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .user-profile-gradient {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(106, 17, 203, 0.2);
        }

        .notification-dot-custom {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 9px;
            height: 9px;
            background-color: #f1416c;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        /* --- CUSTOM PAGINATION STYLING --- */
        .pagination {
            gap: 0.25rem;
            margin-top: 1rem;
        }

        .pagination .page-item .page-link {
            border: none;
            border-radius: 8px;
            color: #5E6278;
            font-weight: 500;
            padding: 0.5rem 0.85rem;
            transition: all 0.3s ease;
            background-color: transparent;
        }

        .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
            background-color: var(--bs-primary-light);
            color: var(--bs-primary);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--bs-primary) !important;
            color: #ffffff !important;
            box-shadow: 0px 4px 10px rgba(0, 158, 247, 0.3);
        }

        .pagination .page-item.previous .page-link,
        .pagination .page-item.next .page-link {
            background-color: #F9F9F9;
        }

        .pagination .page-item.previous:not(.disabled) .page-link:hover,
        .pagination .page-item.next:not(.disabled) .page-link:hover {
            background-color: var(--bs-primary-light);
            color: var(--bs-primary);
        }

        /* --- MOBILE RESPONSIVE FIXES --- */
        @media (max-width: 991.98px) {

            /* 1. Fix bug dropdown menu muncul langsung hilang (Override JS Animation glitch) */
            #kt_header_menu .menu-item.show>.menu-sub {
                display: flex !important;
                position: static !important;
                box-shadow: none !important;
                background-color: #f9f9f9 !important;
                padding: 10px !important;
                margin-top: 5px !important;
                border-radius: 8px;
            }

            /* 2. Fix tabel agar bisa di-scroll horizontal tanpa berantakan */
            .table {
                display: block !important;
                width: 100% !important;
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
                white-space: nowrap !important;
            }

            /* 3. Fix Layout Datatables (Search & Pagination) */
            .dataTables_wrapper .row {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .dataTables_wrapper .row>div {
                width: 100% !important;
                text-align: left !important;
            }

            .dataTables_paginate {
                display: flex;
                justify-content: center;
                margin-top: 15px !important;
            }

            /* 4. Fix Card Header (Input Pencarian dan Tombol Tambah/Filter) */
            .card-header {
                flex-direction: column;
                align-items: flex-start !important;
                padding: 15px !important;
            }

            .card-title,
            .card-toolbar {
                width: 100%;
                margin: 5px 0 !important;
            }

            .card-title .d-flex {
                width: 100%;
            }

            .card-title input {
                width: 100% !important;
            }

            .card-toolbar .btn {
                width: 100%;
                justify-content: center;
                margin-top: 10px;
            }

            /* 5. Fix filter row di dashboard */
            form.row.g-3 {
                flex-direction: column;
            }

            form.row.g-3>div {
                width: 100% !important;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/eruda"></script>
    <script>
        eruda.init();
    </script>
</head>

<body id="kt_body" class="header-extended header-fixed header-tablet-and-mobile-fixed">

    <!--begin::Page loading-->
    <div class="page-loader flex-column bg-dark bg-opacity-25">
        <span class="spinner-border text-primary" role="status"></span>
        <span class="text-gray-800 fs-6 fw-semibold mt-5">Loading...</span>
    </div>
    <!--end::Page loading-->

    <!--begin::Theme mode setup-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup-->

    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">

                <!--begin::Header-->
                <div id="kt_header" class="header" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">

                    <!--begin::Header top-->
                    <div class="header-top-container container-xxl d-flex flex-grow-1 flex-stack">

                        <!--begin::Logo & Search-->
                        <div class="d-flex align-items-center flex-grow-1">
                            <!--begin::Mobile Toggle-->
                            <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show header menu">
                                <div class="btn btn-icon btn-active-color-primary w-30px h-30px w-md-40px h-md-40px" id="kt_header_menu_mobile_toggle">
                                    <i class="ki-outline ki-abstract-14 fs-1"></i>
                                </div>
                            </div>
                            <!--end::Mobile Toggle-->

                            <a href="<?= base_url() ?>">

                                <img
                                    alt="Logo"
                                    src="<?= base_url('uploads/logo/logo-depan.png') ?>"
                                    class="h-25px h-md-30px" />

                            </a>

                            <!--Search bar muncul di sini (Desktop Only)-->
                            <div class="search-container-custom d-none d-lg-block">
                                <div class="position-relative">
                                    <i class="ki-outline ki-magnifier fs-2 text-gray-500 position-absolute top-50 translate-middle-y ms-4"></i>
                                    <input type="text" class="form-control search-input-custom" placeholder="Cari pelanggan, paket, atau invoice..." />
                                </div>
                            </div>
                        </div>

                        <!--begin::Topbar-->
                        <div class="d-flex align-items-center">

                            <!--Theme mode (Bulan)-->
                            <div class="ms-3">
                                <a href="#" class="btn btn-icon btn-custom btn-active-light-primary w-40px h-40px" data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                    <i class="ki-outline ki-moon fs-1 text-gray-700"></i>
                                </a>
                                <!--Menu mode tetap ada (Light/Dark dropdown)-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-muted menu-active-bg menu-state-primary fw-semibold py-4 fs-base w-175px" data-kt-menu="true">
                                    <div class="menu-item px-3 my-0"><a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light"><span class="menu-icon"><i class="ki-duotone ki-night-day fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span></i></span><span class="menu-title">Light</span></a></div>
                                    <div class="menu-item px-3 my-0"><a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark"><span class="menu-icon"><i class="ki-duotone ki-moon fs-2"><span class="path1"></span><span class="path2"></span></i></span><span class="menu-title">Dark</span></a></div>
                                </div>
                            </div>

                            <!--Notifikasi (Lonceng)-->
                            <div class="ms-3 position-relative">
                                <div class="btn btn-icon btn-custom btn-active-light-primary w-40px h-40px">
                                    <i class="ki-outline ki-notification-on fs-1 text-gray-700"></i>
                                    <span class="notification-dot-custom"></span>
                                </div>
                            </div>

                            <!--User Profile (Gradient)-->
                            <div class="ms-3" id="kt_header_user_menu_toggle">
                                <div class="user-profile-gradient shadow-sm" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                    <i class="ki-outline ki-user fs-2 text-white"></i>
                                </div>
                                <!--User menu account dropdown (tetap sama)-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <div class="menu-content d-flex align-items-center px-3">
                                            <div class="d-flex flex-column">
                                                <div class="fw-bold d-flex align-items-center fs-5"><?= esc(session()->get('name')) ?></div>
                                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">Role: <?= esc(ucfirst(session()->get('role'))) ?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="menu-item px-5">
                                        <a href="<?= base_url('profile') ?>" class="menu-link px-5">Profil Saya</a>
                                    </div>
                                    <div class="separator my-2"></div>
                                    <div class="menu-item px-5"><a href="<?= base_url('logout') ?>" class="menu-link px-5 text-danger">Sign Out</a></div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!--end::Header top-->

                    <!--begin::Header bottom-->
                    <div class="d-flex align-items-stretch flex-stack h-lg-75px w-100 bg-white border-top" id="kt_header_nav">
                        <div class="container-xxl d-flex flex-stack h-100">
                            <div class="header-menu flex-column flex-lg-row align-items-stretch d-lg-flex" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                                <?= $this->include('layout/header_menu') ?>
                            </div>
                        </div>
                    </div>
                    <!--end::Header bottom-->
                </div>
                <!--end::Header-->

                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">

                    <!--begin::Toolbar-->
                    <div class="toolbar py-5 py-lg-7" id="kt_toolbar">
                        <!--begin::Container-->
                        <div class="container-xxl d-flex flex-stack flex-wrap">

                            <!--begin::Page title-->
                            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                <!-- Judul Halaman: fs-3 sudah pas, tapi kita pertegas warnanya -->
                                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                                    <?= esc($title) ?>
                                </h1>

                                <!-- Breadcrumb: Ukuran diperkecil ke fs-7 agar tidak balapan dengan judul -->
                                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-1">
                                    <!-- Link Home: Menggunakan warna hijau (text-success) sesuai gambar pertama Anda -->
                                    <li class="breadcrumb-item text-muted">
                                        <a href="<?= base_url('dashboard') ?>" class="text-success text-hover-primary">Home</a>
                                    </li>
                                    <!-- Item Aktif: Warna abu-abu yang lebih tegas (gray-600) -->
                                    <li class="breadcrumb-item text-gray-600">
                                        <?= esc($title) ?>
                                    </li>
                                </ul>
                            </div>
                            <!--end::Page title-->

                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Toolbar-->

                    <!--begin::Post-->
                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <!--begin::Container-->
                        <div id="kt_content_container" class="container-xxl">
                            <?= $this->renderSection('content') ?>
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Post-->

                </div>
                <!--end::Content-->

                <!--begin::Footer-->
                <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                    <div class="container-xxl d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted fw-semibold me-1">2026&copy;</span>
                            <a href="#" class="text-gray-800 text-hover-primary">Humanika WiFi</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!--begin::Javascript-->
    <script>
        var hostUrl = "<?= base_url('assets/') ?>";
    </script>
    <script src="<?= base_url('assets/plugins/global/plugins.bundle.js') ?>"></script>
    <script src="<?= base_url('assets/js/scripts.bundle.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/custom/datatables/datatables.bundle.js') ?>"></script>

    <?= $this->renderSection('js') ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            KTApp.hidePageLoading();
        });

        document.addEventListener("click", function(e) {
            var link = e.target.closest("a");
            if (link && link.href && link.href.startsWith(window.location.origin) && !link.target && !link.hasAttribute("data-kt-menu") && !link.hasAttribute("data-kt-element") && link.href !== window.location.href + "#" && !link.href.includes("#")) {
                KTApp.showPageLoading();
            }
        });
    </script>
</body>

</html>