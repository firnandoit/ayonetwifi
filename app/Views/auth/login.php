<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <title>Login - Ayonet WiFi</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--begin::Global Stylesheets Bundle-->
    <link href="<?= base_url('assets/plugins/global/plugins.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/css/style.bundle.css') ?>" rel="stylesheet" type="text/css" />

    <style>
        /* Desain Latar Belakang & Pembagi Diagonal */
        .auth-visual-side {
            background: linear-gradient(135deg, #009ef7 0%, #003da7 100%);
            position: relative;
            /* Membuat potongan miring pada desktop */
            clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%); 
            z-index: 1;
        }

        /* Penyesuaian Mobile: Potongan miring di bawah */
        @media (max-width: 991.98px) {
            .auth-visual-side {
                clip-path: polygon(0 0, 100% 0, 100% 85%, 0% 100%);
                height: 250px;
                width: 100% !important;
            }
            .auth-form-side {
                margin-top: -40px; /* Membuat form agak naik menimpa visual */
                z-index: 2;
                background: white;
                border-radius: 30px 30px 0 0;
            }
        }

        /* Sosmed Icons Bulat Sempurna */
        .social-circle {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #f5f8fa;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        .social-circle:hover {
            background-color: #eef3f7;
            transform: scale(1.1);
        }
        .social-circle img {
            width: 20px;
            height: 20px;
        }

        /* Input Modern */
        .form-control-solid {
            background-color: #f9f9f9 !important;
            border: 1px solid #f1f1f1 !important;
        }
        .form-control-solid:focus {
            background-color: #ffffff !important;
            border-color: #009ef7 !important;
        }
    </style>
</head>
<!--end::Head-->

<!--begin::Body-->
<body id="kt_body" class="auth-bg">
    <div class="d-flex flex-column flex-root">
        <!--begin::Authentication-->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            
            <!--begin::Aside (Area Biru / Visual)-->
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 auth-visual-side flex-center p-10">
                <div class="d-flex flex-column flex-center text-center">
                    <!-- Icon WiFi Besar -->
                    <div class="mb-5">
                        <a href="<?= base_url() ?>">
                            <img
                                alt="Logo"
                                src="<?= base_url('uploads/logo/ayonet2.png') ?>"
                                class="h-40px h-md-70px" />
                        </a>
                    </div>
                    <h1 class="text-white fs-2qx fw-bolder mb-3">AYONET NETWORK</h1>
                    <p class="text-white fs-5 fw-semibold opacity-75">Manajemen billing wifi <br>cepat, aman, dan tanpa kendala.</p>
                </div>
            </div>
            <!--end::Aside-->

            <!--begin::Body (Area Form)-->
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 auth-form-side">
                <div class="d-flex flex-column flex-center flex-row-fluid p-10">
                    <!--begin::Wrapper-->
                    <div class="w-lg-450px w-100">
                        
                        <!-- Tombol Kembali -->
                        <div class="mb-10">
                            <a href="<?= base_url('/') ?>" class="btn btn-icon btn-sm btn-light-success rounded-circle me-3">
                                <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <span class="text-gray-400 fw-bold">Kembali</span>
                        </div>

                        <!--begin::Form-->
                        <form class="form w-100" id="kt_sign_in_form" action="<?= base_url('login') ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="text-start mb-10">
                                <h1 class="text-gray-900 mb-3 fs-3x">Sign In</h1>
                                <div class="text-gray-500 fw-semibold fs-6">Gunakan akun billing Anda</div>
                            </div>

                            <?php if (session()->getFlashdata('msg')): ?>
                                <div class="alert alert-dismissible bg-light-danger d-flex align-items-center p-5 mb-10">
                                    <div class="d-flex flex-column">
                                        <span class="text-danger fw-bold"><?= session()->getFlashdata('msg') ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!--begin::Input group-->
                            <div class="fv-row mb-8">
                                <input type="text" placeholder="Username" name="username" class="form-control form-control-lg form-control-solid rounded-3" required />
                            </div>

                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <input type="password" placeholder="Password" name="password" class="form-control form-control-lg form-control-solid rounded-3" required />
                            </div>

                            <!--begin::Wrapper-->
                            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-10">
                                <div></div>
                                <a href="#" class="link-success">Lupa Password?</a>
                            </div>

                            <!--begin::Actions-->
                            <div class="d-flex flex-stack">
                                <!--Button Login-->
                                <button type="submit" id="kt_sign_in_submit" class="btn btn-success px-10 py-4 fw-bold">
                                    <span class="indicator-label">Sign In</span>
                                    <span class="indicator-progress">Please wait... 
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>

                                <!--Social Login (Sesuai Gambar User)-->
                                <div class="d-flex align-items-center">
                                    <div class="text-gray-400 fw-bold me-3">Or</div>
                                    
                                    <a href="#" class="social-circle me-2">
                                        <img alt="Logo" src="<?= base_url('assets/media/svg/brand-logos/google-icon.svg') ?>" />
                                    </a>
                                    
                                    <a href="#" class="social-circle">
                                        <img alt="Logo" src="<?= base_url('assets/media/svg/brand-logos/facebook-3.svg') ?>" />
                                    </a>
                                </div>
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->

                        <div class="mt-20 text-start">
                            <span class="text-gray-400 fs-7 fw-bold">Belum punya akun? <a href="#" class="link-success fw-bolder">Daftar Sekarang</a></span>
                        </div>
                    </div>
                    <!--end::Wrapper-->
                </div>
            </div>
            <!--end::Body-->
        </div>
    </div>

    <!--begin::Javascript-->
    <script>var hostUrl = "<?= base_url('assets/') ?>";</script>
    <script src="<?= base_url('assets/plugins/global/plugins.bundle.js') ?>"></script>
    <script src="<?= base_url('assets/js/scripts.bundle.js') ?>"></script>
</body>
</html>