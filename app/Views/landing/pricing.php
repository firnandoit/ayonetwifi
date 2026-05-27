<!--begin::Pricing Section-->
<div class="mt-sm-n20">
    <!--begin::Curve top-->
    <div class="landing-curve landing-dark-color">
        <svg viewBox="15 -1 1470 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 48C4.93573 47.6644 8.85984 47.3311 12.7725 47H1489.16C1493.1 47.3311 1497.04 47.6644 1501 48V47H1489.16C914.668 -1.34764 587.282 -1.61174 12.7725 47H1V48Z" fill="currentColor"></path>
        </svg>
    </div>
    <!--end::Curve top-->

    <div class="py-20 landing-dark-bg">
        <div class="container">
            <div class="d-flex flex-column container pt-lg-20">
                <!--begin::Heading-->
                <div class="mb-13 text-center">
                    <h1 class="fs-2hx fw-bold text-white mb-5" id="pricing">
                        <?= esc($settings['landing_pricing_title'] ?? 'Paket Internet Super Cepat') ?>
                    </h1>
                    <div class="text-gray-400 fw-semibold fs-5">
                        <?= esc($settings['landing_pricing_subtitle'] ?? 'Pilih paket bulanan sesuai kebutuhan Anda tanpa biaya tersembunyi.') ?>
                    </div>
                </div>

                <!--begin::Pricing Wrapper-->
                <div class="text-center" id="kt_pricing">
                    <style>
                        /* --- Container Utama untuk Efek Stack --- */
                        .pricing-stack-container {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            perspective: 1000px;
                            padding: 50px 0;
                            min-height: 600px;
                        }

                        /* Gaya Dasar Kartu */
                        .pricing-card {
                            background-color: #ffffff;
                            border-radius: 2.5rem !important;
                            border: 1px solid #f1f1f1;
                            transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
                            position: relative;
                            width: 350px; /* Lebar tetap agar efek stack rapi */
                            margin: 0 -80px; /* Saling bertumpuk sedikit */
                            z-index: 1;
                            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                        }

                        /* --- Pengaturan Posisi Awal (Stacking) --- */
                        /* Kartu Pertama (Kiri) */
                        .pricing-card-wrapper:nth-child(1) .pricing-card {
                            transform: rotate(-10deg) translateX(-30px) translateY(20px);
                            z-index: 5;
                        }

                        /* Kartu Kedua (Tengah - Populer) */
                        .pricing-card-wrapper:nth-child(2) .pricing-card {
                            transform: scale(1.05); /* Sedikit lebih besar */
                            z-index: 10; /* Berada paling depan */
                            border: 2.5px solid #ffffff;
                            box-shadow: 0 20px 50px rgba(0, 158, 247, 0.15) !important;
                        }

                        /* Kartu Ketiga (Kanan) */
                        .pricing-card-wrapper:nth-child(3) .pricing-card {
                            transform: rotate(10deg) translateX(30px) translateY(20px);
                            z-index: 5;
                        }

                        /* --- Efek Saat Hover (Fans Out) --- */
                        .pricing-stack-container:hover .pricing-card-wrapper:nth-child(1) .pricing-card {
                            transform: rotate(-5deg) translateX(-150px) translateY(0);
                        }
                        
                        .pricing-stack-container:hover .pricing-card-wrapper:nth-child(2) .pricing-card {
                            transform: scale(1.1) translateY(-30px);
                        }

                        .pricing-stack-container:hover .pricing-card-wrapper:nth-child(3) .pricing-card {
                            transform: rotate(5deg) translateX(150px) translateY(0);
                        }

                        /* Individual Hover (saat kursor di atas kartu spesifik) */
                        .pricing-card:hover {
                            z-index: 20 !important;
                            box-shadow: 0 30px 60px rgba(0,0,0,0.2) !important;
                        }

                        /* Gaya Badge, Divider & Harga */
                        .badge-populer {
                            position: absolute;
                            top: 25px;
                            right: 25px;
                            background-color: #e1f0ff;
                            color: #009ef7;
                            padding: 8px 20px;
                            border-radius: 50px;
                            font-weight: 700;
                            font-size: 0.8rem;
                        }

                        .price-divider {
                            border-top: 1px solid #eff2f5;
                            padding-top: 2rem;
                            margin-top: 1.5rem;
                            width: 100%;
                        }

                        .btn-pilih-paket {
                            background-color: #009ef7 !important;
                            border-radius: 18px !important;
                            padding: 18px 25px !important;
                            font-weight: 800;
                            box-shadow: 0 4px 15px rgba(0, 158, 247, 0.2);
                        }

                        /* Responsive Mobile: Kembali ke baris atau scroll horizontal */
                        @media (max-width: 991.98px) {
                            .pricing-stack-container {
                                flex-wrap: nowrap;
                                overflow-x: auto;
                                justify-content: flex-start;
                                padding: 20px;
                                min-height: auto;
                            }
                            .pricing-card {
                                margin: 0 10px;
                                transform: none !important;
                                width: 300px;
                            }
                            .pricing-stack-container:hover .pricing-card {
                                transform: none !important;
                            }
                        }
                    </style>

                    <div class="pricing-stack-container">
                        <?php if (isset($packages) && count($packages) > 0): ?>
                            <?php foreach ($packages as $index => $pkg): 
                                $isPopular = ($pkg['name'] == 'Paket Standar' || $index == 1); 
                            ?>
                                <div class="pricing-card-wrapper">
                                    <div class="pricing-card py-15 px-10">
                                        <?php if ($isPopular): ?>
                                            <span class="badge-populer">Populer</span>
                                        <?php endif; ?>

                                        <!-- Heading -->
                                        <div class="mb-7 text-center">
                                            <h1 class="text-gray-900 mb-3 fw-boldest fs-1"><?= esc($pkg['name']) ?></h1>
                                            <div class="text-gray-400 fw-semibold mb-2 fs-6 px-2">
                                                <?= esc($pkg['description']) ?>
                                            </div>

                                            <div class="price-divider">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <span class="fs-5 fw-bold text-gray-500 me-1">Rp</span>
                                                    <span class="fs-4x fw-bold text-gray-900 lh-1"><?= number_format($pkg['price'], 0, ',', '.') ?></span>
                                                    <span class="fs-7 text-gray-400 ms-2 text-start"> /<br>Bulan</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Fitur -->
                                        <div class="w-100 mb-10 text-start">
                                            <div class="d-flex flex-stack mb-5">
                                                <span class="fw-semibold fs-6 text-gray-700">Kecepatan: <?= esc($pkg['speed']) ?></span>
                                                <i class="ki-duotone ki-check-circle fs-1 text-success"><span class="path1"></span><span class="path2"></span></i>
                                            </div>
                                            <div class="d-flex flex-stack mb-5">
                                                <span class="fw-semibold fs-6 text-gray-700">Unlimited Kuota</span>
                                                <i class="ki-duotone ki-check-circle fs-1 text-success"><span class="path1"></span><span class="path2"></span></i>
                                            </div>
                                            <div class="d-flex flex-stack">
                                                <span class="fw-semibold fs-6 text-gray-700">Layanan Prioritas</span>
                                                <i class="ki-duotone ki-check-circle fs-1 text-success"><span class="path1"></span><span class="path2"></span></i>
                                            </div>
                                        </div>

                                        <!-- CTA -->
                                        <?php 
                                            $waNumber = esc($settings['company_whatsapp'] ?? '62882008632993');
                                            $waPilihPaket = "https://wa.me/{$waNumber}?text=" . urlencode("Halo Ayonet, saya tertarik untuk berlangganan paket " . $pkg['name'] . ".");
                                        ?>
                                        <a href="<?= $waPilihPaket ?>" target="_blank" class="btn btn-primary btn-pilih-paket w-100">
                                            Pilih Paket
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Curve bottom-->
    <div class="landing-curve landing-dark-color">
        <svg viewBox="15 12 1470 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 11C3.93573 11.3356 7.85984 11.6689 11.7725 12H1488.16C1492.1 11.6689 1496.04 11.3356 1500 11V12H1488.16C913.668 60.3476 586.282 60.6117 11.7725 12H0V11Z" fill="currentColor"></path>
        </svg>
    </div>
</div>