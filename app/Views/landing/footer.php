      <div class="mb-0">
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
        <div class="landing-dark-bg pt-20">
          <div class="container">
            <div class="row py-10 py-lg-20">
              <div class="col-lg-6 pe-lg-16 mb-10 mb-lg-0">
                <div class="rounded landing-dark-border p-9 mb-10">
                  <h2 class="text-white"><?= esc($settings['landing_footer_contact_title'] ?? 'Butuh Bantuan atau Ada Pertanyaan?') ?></h2>
                  <span class="fw-normal fs-4 text-gray-400"><?= esc($settings['landing_footer_contact_text'] ?? 'Hubungi tim support kami kapan saja via email atau telepon.') ?></span>
                </div>
                <div class="rounded landing-dark-border p-9">
                  <h2 class="text-white"><?= esc($settings['landing_footer_project_title'] ?? 'Tertarik Menjadi Mitra Kami?') ?></h2>
                  <span class="fw-normal fs-4 text-gray-400"><?= esc($settings['landing_footer_project_text'] ?? 'Bergabung menjadi reseller dan dapatkan keuntungan lebih dari layanan kami.') ?></span>
                </div>
              </div>

              <div class="col-lg-6 ps-lg-16">
                <div class="d-flex justify-content-center">
                  <div class="d-flex fw-semibold flex-column me-20">
                    <h4 class="fw-bold text-gray-500 mb-6">
                      Informasi Layanan
                    </h4>
                    <?php
                    $waNumber = esc($settings['company_whatsapp'] ?? '6281234567890');
                    $waBase = "https://wa.me/{$waNumber}?text=";
                    ?>
                    <a
                      href="<?= base_url('page/tanya-jawab') ?>"
                      class="text-white opacity-50 text-hover-primary fs-5 mb-6">Tanya Jawab</a>
                    <a
                      href="<?= base_url('page/syarat-ketentuan') ?>"
                      class="text-white opacity-50 text-hover-primary fs-5 mb-6">Syarat & Ketentuan</a>
                    <a
                      href="<?= base_url('page/kebijakan-privasi') ?>"
                      class="text-white opacity-50 text-hover-primary fs-5 mb-6">Kebijakan Privasi</a>

                    <a
                      href="<?= base_url('page/area-cakupan') ?>"
                      class="text-white opacity-50 text-hover-primary fs-5 mb-6">Area Cakupan</a>

                    <a
                      href="<?= base_url('page/pusat-bantuan') ?>"
                      class="text-white opacity-50 text-hover-primary fs-5 mb-6">Pusat Bantuan</a>

                    <a
                      href="#"
                      class="text-white opacity-50 text-hover-primary fs-5">Blog</a>

                  </div>

                  <div class="d-flex fw-semibold flex-column ms-lg-20">
                    <h4 class="fw-bold text-gray-500 mb-6">Tetap Terhubung</h4>
                    <a href="<?= esc($settings['social_facebook'] ?? '#') ?>" class="mb-6">
                      <img
                        src="<?= base_url('assets/') ?>media/svg/brand-logos/facebook-4.svg"
                        class="h-20px me-2"
                        alt="" />
                      <span
                        class="text-white opacity-50 text-hover-primary fs-5 mb-6">Facebook</span>
                    </a>

                    <a href="<?= esc($settings['social_twitter'] ?? '#') ?>" class="mb-6">
                      <img
                        src="<?= base_url('assets/') ?>media/svg/brand-logos/twitter.svg"
                        class="h-20px me-2"
                        alt="" />
                      <span
                        class="text-white opacity-50 text-hover-primary fs-5 mb-6">Twitter</span>
                    </a>

                    <a href="<?= esc($settings['social_instagram'] ?? '#') ?>" class="mb-6">
                      <img
                        src="<?= base_url('assets/') ?>media/svg/brand-logos/instagram-2-1.svg"
                        class="h-20px me-2"
                        alt="" />
                      <span
                        class="text-white opacity-50 text-hover-primary fs-5 mb-6">Instagram</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="landing-dark-separator"></div>
          <div class="container">
            <div
              class="d-flex flex-column flex-md-row flex-stack py-7 py-lg-10">
              <div class="d-flex align-items-center order-2 order-md-1">
                <a href="<?= base_url() ?>">
                  <img
                    alt="Logo"
                    src="<?= base_url('uploads/logo/logo-depan.png') ?>"
                    class="h-25px h-md-30px" />
                </a>
                <span
                  class="mx-5 fs-6 fw-semibold text-gray-600 pt-1"><?= $settings['landing_footer_copyright'] ?? '&copy; ' . date('Y') . ' Humanika WiFi. All rights reserved.' ?></span>
              </div>

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
            </div>
          </div>
        </div>
      </div>