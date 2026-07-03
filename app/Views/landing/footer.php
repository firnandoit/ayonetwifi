      <!--begin::Footer Section-->
      <div class="mb-0">
        <!--begin::Curve top-->
        <div class="landing-curve landing-dark-color">
          <svg
            viewBox="15 -1 1470 48"
            fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
              d="M1 48C4.93573 47.6644 8.85984 47.3311 12.7725 47H1489.16C1493.1 47.3311 1497.04 47.6644 1501 48V47H1489.16C914.668 -1.34764 587.282 -1.61174 12.7725 47H1V48Z"
              fill="currentColor"></path>
          </svg>
        </div>
        <!--end::Curve top-->
        <!--begin::Wrapper-->
        <div class="landing-dark-bg pt-20">
          <!--begin::Container-->
          <div class="container">
            <!--begin::Row-->
            <div class="row py-10 py-lg-20">
              <!--begin::Col-->
              <div class="col-lg-6 pe-lg-16 mb-10 mb-lg-0">
                <!--begin::Block-->
                <div class="rounded landing-dark-border p-9 mb-10">
                  <!--begin::Title-->
                  <h2 class="text-white"><?= esc($settings['landing_footer_contact_title'] ?? 'Butuh Bantuan atau Ada Pertanyaan?') ?></h2>
                  <!--end::Title-->
                  <!--begin::Text-->
                  <span class="fw-normal fs-4 text-gray-400"><?= esc($settings['landing_footer_contact_text'] ?? 'Hubungi tim support kami kapan saja via email atau telepon.') ?></span>
                  <!--end::Text-->
                </div>
                <!--end::Block-->
                <!--begin::Block-->
                <div class="rounded landing-dark-border p-9">
                  <!--begin::Title-->
                  <h2 class="text-white"><?= esc($settings['landing_footer_project_title'] ?? 'Tertarik Menjadi Mitra Kami?') ?></h2>
                  <!--end::Title-->
                  <!--begin::Text-->
                  <span class="fw-normal fs-4 text-gray-400"><?= esc($settings['landing_footer_project_text'] ?? 'Bergabung menjadi reseller dan dapatkan keuntungan lebih dari layanan kami.') ?></span>
                  <!--end::Text-->
                </div>
                <!--end::Block-->
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-lg-6 ps-lg-16">
                <!--begin::Navs-->
                <div class="d-flex justify-content-center">
                  <!--begin::Links-->
                  <div class="d-flex fw-semibold flex-column me-20">
                    <!--begin::Subtitle-->
                    <h4 class="fw-bold text-gray-500 mb-6">
                      Informasi Layanan
                    </h4>
                    <!--end::Subtitle-->
                    <?php
                    $waNumber = esc($settings['company_whatsapp'] ?? '6281234567890');
                    $waBase = "https://wa.me/{$waNumber}?text=";
                    ?>
                    <!--begin::Link-->
                    <a
                      href="<?= base_url('page/tanya-jawab') ?>"
                      class="text-white opacity-50 text-hover-primary fs-5 mb-6">Tanya Jawab</a>
                    <!--end::Link-->
                    <!--begin::Link-->
                    <a
                      href="<?= base_url('page/syarat-ketentuan') ?>"
                      class="text-white opacity-50 text-hover-primary fs-5 mb-6">Syarat & Ketentuan</a>
                    <!--end::Link-->
                    <!--begin::Link-->
                    <a
                      href="<?= base_url('page/kebijakan-privasi') ?>"
                      class="text-white opacity-50 text-hover-primary fs-5 mb-6">Kebijakan Privasi</a>
                    <!--end::Link-->
                    <!--begin::Link-->
                    <a
                      href="<?= base_url('page/area-cakupan') ?>"
                      class="text-white opacity-50 text-hover-primary fs-5 mb-6">Area Cakupan</a>
                    <!--end::Link-->
                    <!--begin::Link-->
                    <a
                      href="<?= base_url('page/pusat-bantuan') ?>"
                      class="text-white opacity-50 text-hover-primary fs-5 mb-6">Pusat Bantuan</a>
                    <!--end::Link-->
                    <!--begin::Link-->
                    <a
                      href="#"
                      class="text-white opacity-50 text-hover-primary fs-5">Blog</a>
                    <!--end::Link-->
                  </div>
                  <!--end::Links-->
                  <!--begin::Links-->
                  <div class="d-flex fw-semibold flex-column ms-lg-20">
                    <!--begin::Subtitle-->
                    <h4 class="fw-bold text-gray-500 mb-6">Tetap Terhubung</h4>
                    <!--end::Subtitle-->
                    <!--begin::Link-->
                    <a href="<?= esc($settings['social_facebook'] ?? '#') ?>" class="mb-6">
                      <img
                        src="<?= base_url('assets/') ?>media/svg/brand-logos/facebook-4.svg"
                        class="h-20px me-2"
                        alt="" />
                      <span
                        class="text-white opacity-50 text-hover-primary fs-5 mb-6">Facebook</span>
                    </a>
                    <!--end::Link-->
                    <!--begin::Link-->
                    <a href="<?= esc($settings['social_twitter'] ?? '#') ?>" class="mb-6">
                      <img
                        src="<?= base_url('assets/') ?>media/svg/brand-logos/twitter.svg"
                        class="h-20px me-2"
                        alt="" />
                      <span
                        class="text-white opacity-50 text-hover-primary fs-5 mb-6">Twitter</span>
                    </a>
                    <!--end::Link-->
                    <!--begin::Link-->
                    <a href="<?= esc($settings['social_instagram'] ?? '#') ?>" class="mb-6">
                      <img
                        src="<?= base_url('assets/') ?>media/svg/brand-logos/instagram-2-1.svg"
                        class="h-20px me-2"
                        alt="" />
                      <span
                        class="text-white opacity-50 text-hover-primary fs-5 mb-6">Instagram</span>
                    </a>
                    <!--end::Link-->
                  </div>
                  <!--end::Links-->
                </div>
                <!--end::Navs-->
              </div>
              <!--end::Col-->
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
          <!--begin::Separator-->
          <div class="landing-dark-separator"></div>
          <!--end::Separator-->
          <!--begin::Container-->
          <div class="container">
            <!--begin::Wrapper-->
            <div
              class="d-flex flex-column flex-md-row flex-stack py-7 py-lg-10">
              <!--begin::Copyright-->
              <div class="d-flex align-items-center order-2 order-md-1">
                <!--begin::Logo-->
                <a href="<?= base_url() ?>">
                  <img
                    alt="Logo"
                    src="<?= base_url('uploads/logo/logo-depan.png') ?>"
                    class="h-25px h-md-30px" />
                </a>
                <!--end::Logo image-->
                <!--begin::Logo image-->
                <span
                  class="mx-5 fs-6 fw-semibold text-gray-600 pt-1"><?= $settings['landing_footer_copyright'] ?? '&copy; ' . date('Y') . ' Humanika WiFi. All rights reserved.' ?></span>
                <!--end::Logo image-->
              </div>
              <!--end::Copyright-->
              <!--begin::Menu-->
              <?php
              if (!isset($waBase)) {
                $waNumber = esc($settings['company_whatsapp'] ?? '6281234567890');
                $waBase = "https://wa.me/{$waNumber}?text=";
              }
              ?>
              <ul
                class="menu menu-gray-600 menu-hover-primary fw-semibold fs-6 fs-md-5 order-1 mb-5 mb-md-0">
                <li class="menu-item">
                  <a
                    href="<?= base_url('page/tentang-kami') ?>"
                    class="menu-link px-2">Tentang Kami</a>
                </li>
                <li class="menu-item mx-5">
                  <a
                    href="<?= base_url('page/bantuan') ?>"
                    class="menu-link px-2">Bantuan</a>
                </li>
                <li class="menu-item">
                  <a href="<?= $waBase . urlencode('Halo Humanika, saya ingin Daftar berlangganan WiFi.') ?>" target="_blank" class="menu-link px-2">Daftar</a>
                </li>
              </ul>
              <!--end::Menu-->
            </div>
            <!--end::Wrapper-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::Wrapper-->
      </div>
      <!--end::Footer Section-->