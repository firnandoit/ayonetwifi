      <?php /** @var array $settings */ ?>
      <!--begin::Header Section-->
      <div class="mb-0" id="home">
        <!--begin::Wrapper-->
        <div
          class="bgi-no-repeat bgi-size-cover bgi-position-center landing-dark-bg"
          style="
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('<?= esc($settings['landing_bg_image'] ?? 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?q=80&w=2070&auto=format&fit=crop') ?>');
            background-attachment: fixed;
          ">
          <!--begin::Header-->
          <div
            class="landing-header"
            data-kt-sticky="true"
            data-kt-sticky-name="landing-header"
            data-kt-sticky-offset="{default: '200px', lg: '300px'}">
            <!--begin::Container-->
            <div class="container">
              <!--begin::Wrapper-->
              <div class="d-flex align-items-center justify-content-between">
                <!--begin::Logo-->
                <div class="d-flex align-items-center flex-equal">
                  <!--begin::Mobile menu toggle-->
                  <button
                    class="btn btn-icon btn-active-color-primary me-3 d-flex d-lg-none"
                    id="kt_landing_menu_toggle">
                    <i class="ki-duotone ki-abstract-14 fs-2hx">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </button>
                  <!--end::Mobile menu toggle-->
                  <!--begin::Logo image-->
                  <a href="<?= base_url() ?>">
                    <img
                      alt="Logo"
                      src="<?= base_url('uploads/logo/ayonet3.png') ?>"
                      class="logo-default h-25px h-lg-30px" />
                    <img
                      alt="Logo"
                      src="<?= base_url('uploads/logo/ayonet2.png') ?>"
                      class="logo-sticky h-25px h-lg-30px" />
                  </a>
                  <!--end::Logo image-->
                </div>
                <!--end::Logo-->
                <!--begin::Menu wrapper-->
                <div class="d-lg-block" id="kt_header_nav_wrapper">
                  <div
                    class="d-lg-block p-5 p-lg-0"
                    data-kt-drawer="true"
                    data-kt-drawer-name="landing-menu"
                    data-kt-drawer-activate="{default: true, lg: false}"
                    data-kt-drawer-overlay="true"
                    data-kt-drawer-width="200px"
                    data-kt-drawer-direction="start"
                    data-kt-drawer-toggle="#kt_landing_menu_toggle"
                    data-kt-swapper="true"
                    data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav_wrapper'}">
                    <!--begin::Menu-->
                    <div
                      class="menu menu-column flex-nowrap menu-rounded menu-lg-row menu-title-gray-600 menu-state-title-primary nav nav-flush fs-5 fw-semibold"
                      id="kt_landing_menu">
                      <!--begin::Menu item-->
                      <div class="menu-item">
                        <!--begin::Menu link-->
                        <a
                          class="menu-link nav-link active py-3 px-4 px-xxl-6"
                          href="#kt_body"
                          data-kt-scroll-toggle="true"
                          data-kt-drawer-dismiss="true">Beranda</a>
                        <!--end::Menu link-->
                      </div>
                      <!--end::Menu item-->
                      <!--begin::Menu item-->
                      <div class="menu-item">
                        <!--begin::Menu link-->
                        <a
                          class="menu-link nav-link py-3 px-4 px-xxl-6"
                          href="#how-it-works"
                          data-kt-scroll-toggle="true"
                          data-kt-drawer-dismiss="true">Cara Kerja</a>
                        <!--end::Menu link-->
                      </div>
                      <!--end::Menu item-->
                      <!--begin::Menu item-->
                      <div class="menu-item">
                        <!--begin::Menu link-->
                        <a
                          class="menu-link nav-link py-3 px-4 px-xxl-6"
                          href="#achievements"
                          data-kt-scroll-toggle="true"
                          data-kt-drawer-dismiss="true">Keunggulan</a>
                        <!--end::Menu link-->
                      </div>
                      <!--end::Menu item-->

                      <!--begin::Menu item-->
                      <div class="menu-item">
                        <!--begin::Menu link-->
                        <a
                          class="menu-link nav-link py-3 px-4 px-xxl-6"
                          href="#portfolio"
                          data-kt-scroll-toggle="true"
                          data-kt-drawer-dismiss="true">Layanan</a>
                        <!--end::Menu link-->
                      </div>
                      <!--end::Menu item-->
                      <!--begin::Menu item-->
                      <div class="menu-item">
                        <!--begin::Menu link-->
                        <a
                          class="menu-link nav-link py-3 px-4 px-xxl-6"
                          href="#pricing"
                          data-kt-scroll-toggle="true"
                          data-kt-drawer-dismiss="true">Harga</a>
                        <!--end::Menu link-->
                      </div>
                      <!--end::Menu item-->
                    </div>
                    <!--end::Menu-->
                  </div>
                </div>
                <!--end::Menu wrapper-->
                <!--begin::Toolbar-->
                <div class="flex-equal text-end ms-1">
                  <a
                    href="<?= base_url('login') ?>"
                    class="btn btn-primary">Masuk</a>
                </div>
                <!--end::Toolbar-->
              </div>
              <!--end::Wrapper-->
            </div>
            <!--end::Container-->
          </div>
          <!--end::Header-->
          <!--begin::Landing hero-->
          <div
            class="d-flex flex-column flex-center w-100 min-h-350px min-h-lg-500px px-9">
            <!--begin::Heading-->
            <div class="text-center mb-5 mb-lg-10 py-10 py-lg-20">
              <!--begin::Title-->
              <h1 class="text-white lh-base fw-bold fs-2x fs-lg-3x mb-15">
                <?= esc($settings['landing_hero_title'] ?? 'Solusi Internet Cepat, Stabil, dan Tanpa Kuota') ?>
              </h1>
              <div class="fw-semibold fs-4 text-gray-400 mb-10">
                <?= esc($settings['landing_hero_subtitle'] ?? 'Nikmati pengalaman browsing, streaming, dan gaming tanpa lag dengan jaringan andal dari Ayonet WiFi.') ?>
              </div>
              <!--end::Title-->
              <!--begin::Action-->
              <a href="#pricing" class="btn btn-primary">Berlangganan Sekarang</a>
              <!--end::Action-->
            </div>
            <!--end::Heading-->
            <!--begin::Clients-->
            <div class="d-flex flex-center flex-wrap position-relative px-5">
              <!--begin::Client-->
              <div
                class="d-flex flex-center m-3 m-md-6"
                data-bs-toggle="tooltip"
                title="Fujifilm">
                <img
                  src="<?= base_url('assets/') ?>media/svg/brand-logos/fujifilm.svg"
                  class="mh-30px mh-lg-40px"
                  alt="" />
              </div>
              <!--end::Client-->
              <!--begin::Client-->
              <div
                class="d-flex flex-center m-3 m-md-6"
                data-bs-toggle="tooltip"
                title="Vodafone">
                <img
                  src="<?= base_url('assets/') ?>media/svg/brand-logos/vodafone.svg"
                  class="mh-30px mh-lg-40px"
                  alt="" />
              </div>
              <!--end::Client-->
              <!--begin::Client-->
              <div
                class="d-flex flex-center m-3 m-md-6"
                data-bs-toggle="tooltip"
                title="KPMG International">
                <img
                  src="<?= base_url('assets/') ?>media/svg/brand-logos/kpmg.svg"
                  class="mh-30px mh-lg-40px"
                  alt="" />
              </div>
              <!--end::Client-->
              <!--begin::Client-->
              <div
                class="d-flex flex-center m-3 m-md-6"
                data-bs-toggle="tooltip"
                title="Nasa">
                <img
                  src="<?= base_url('assets/') ?>media/svg/brand-logos/nasa.svg"
                  class="mh-30px mh-lg-40px"
                  alt="" />
              </div>
              <!--end::Client-->
              <!--begin::Client-->
              <div
                class="d-flex flex-center m-3 m-md-6"
                data-bs-toggle="tooltip"
                title="Aspnetzero">
                <img
                  src="<?= base_url('assets/') ?>media/svg/brand-logos/aspnetzero.svg"
                  class="mh-30px mh-lg-40px"
                  alt="" />
              </div>
              <!--end::Client-->
              <!--begin::Client-->
              <div
                class="d-flex flex-center m-3 m-md-6"
                data-bs-toggle="tooltip"
                title="AON - Empower Results">
                <img
                  src="<?= base_url('assets/') ?>media/svg/brand-logos/aon.svg"
                  class="mh-30px mh-lg-40px"
                  alt="" />
              </div>
              <!--end::Client-->
              <!--begin::Client-->
              <div
                class="d-flex flex-center m-3 m-md-6"
                data-bs-toggle="tooltip"
                title="Hewlett-Packard">
                <img
                  src="<?= base_url('assets/') ?>media/svg/brand-logos/hp-3.svg"
                  class="mh-30px mh-lg-40px"
                  alt="" />
              </div>
              <!--end::Client-->
              <!--begin::Client-->
              <div
                class="d-flex flex-center m-3 m-md-6"
                data-bs-toggle="tooltip"
                title="Truman">
                <img
                  src="<?= base_url('assets/') ?>media/svg/brand-logos/truman.svg"
                  class="mh-30px mh-lg-40px"
                  alt="" />
              </div>
              <!--end::Client-->
            </div>
            <!--end::Clients-->
          </div>
          <!--end::Landing hero-->
        </div>
        <!--end::Wrapper-->
        <!--begin::Curve bottom-->
        <div class="landing-curve landing-dark-color mb-10 mb-lg-20">
          <svg
            viewBox="15 12 1470 48"
            fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
              d="M0 11C3.93573 11.3356 7.85984 11.6689 11.7725 12H1488.16C1492.1 11.6689 1496.04 11.3356 1500 11V12H1488.16C913.668 60.3476 586.282 60.6117 11.7725 12H0V11Z"
              fill="currentColor"></path>
          </svg>
        </div>
        <!--end::Curve bottom-->
      </div>
      <!--end::Header Section-->