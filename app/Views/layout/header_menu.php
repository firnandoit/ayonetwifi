<?php
helper('akses'); // Muat helper yang dibuat tadi
$uri = service('uri');
$s1  = $uri->getTotalSegments() > 0 ? $uri->getSegment(1) : '';
?>

<div class="menu menu-rounded menu-column menu-lg-row fw-semibold fs-6" id="kt_header_menu" data-kt-menu="true">

    <!-- Dashboard -->
    <?php if (has_access('dashboard')): ?>
    <div class="menu-item me-0 me-lg-2">
        <a href="<?= base_url('dashboard') ?>" class="menu-link py-3 <?= ($s1 == '' || $s1 == 'dashboard') ? 'active' : '' ?>">
            <span class="menu-title">Dashboard</span>
        </a>
    </div>
    <?php endif; ?>

    <!-- Data TAGIHAN SAYA -->
    <?php if (has_access('customer')): ?>
    <div class="menu-item me-0 me-lg-2">
        <a href="<?= base_url('customer') ?>" class="menu-link py-3 <?= ($s1 == 'customer') ? 'active' : '' ?>">
            <span class="menu-title">Tagihan Saya</span>
        </a>
    </div>
    <?php endif; ?>

    <!-- Data Pelanggan -->
    <?php if (has_access('pelanggan')): ?>
    <div class="menu-item me-0 me-lg-2">
        <a href="<?= base_url('pelanggan') ?>" class="menu-link py-3 <?= ($s1 == 'pelanggan') ? 'active' : '' ?>">
            <span class="menu-title">Data Pelanggan</span>
        </a>
    </div>
    <?php endif; ?>


    <!-- Tagihan -->
    <?php if (has_access('tagihan')): ?>
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion <?= ($s1 == 'tagihan') ? 'here show' : '' ?> me-0 me-lg-2">
        <span class="menu-link py-3 <?= ($s1 == 'tagihan') ? 'active' : '' ?>">
            <span class="menu-title">Tagihan</span>
            <span class="menu-arrow d-lg-none"></span>
        </span>
        <div class="menu-sub menu-sub-lg-dropdown menu-column menu-rounded menu-gray-800 fw-semibold py-4 fs-6 w-225px">
            <!-- Isi submenu tetap sama -->
            <div class="menu-item px-3"><a href="<?= base_url('tagihan/belum-bayar') ?>" class="menu-link px-3">Belum Bayar</a></div>
            <div class="menu-item px-3"><a href="<?= base_url('tagihan/lunas') ?>" class="menu-link px-3">Sudah Lunas</a></div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Laporan -->
    <?php if (has_access('laporan')): ?>
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion <?= ($s1 == 'laporan') ? 'here show' : '' ?> me-0 me-lg-2">
        <span class="menu-link py-3 <?= ($s1 == 'laporan') ? 'active' : '' ?>">
            <span class="menu-title">Laporan</span>
            <span class="menu-arrow d-lg-none"></span>
        </span>
        <div class="menu-sub menu-sub-lg-dropdown menu-column menu-rounded menu-gray-800 fw-semibold py-4 fs-6 w-200px">
            <div class="menu-item px-3"><a href="<?= base_url('laporan/pemasukan') ?>" class="menu-link px-3">Pemasukan</a></div>
            <div class="menu-item px-3"><a href="<?= base_url('laporan/tunggakan') ?>" class="menu-link px-3">Tunggakan</a></div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Pengaturan (Bisa mencakup User, Paket, dan Hak Akses) -->
    <?php if (has_access('user') || has_access('paket') || has_access('role')): ?>
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion <?= (in_array($s1, ['paket', 'user', 'role', 'pengaturan'])) ? 'here show' : '' ?> me-0 me-lg-2">
        <span class="menu-link py-3">
            <span class="menu-title">Pengaturan</span>
            <span class="menu-arrow d-lg-none"></span>
        </span>
        <div class="menu-sub menu-sub-lg-dropdown menu-column menu-rounded menu-gray-800 fw-semibold py-4 fs-6 w-200px">
            <?php if (has_access('paket')): ?>
            <div class="menu-item px-3"><a href="<?= base_url('paket') ?>" class="menu-link px-3">Paket WiFi</a></div>
            <?php endif; ?>
            
            <?php if (has_access('user')): ?>
            <div class="menu-item px-3"><a href="<?= base_url('user') ?>" class="menu-link px-3">Manajemen User</a></div>
            <?php endif; ?>

            <?php if (has_access('role')): ?>
            <div class="menu-item px-3"><a href="<?= base_url('role') ?>" class="menu-link px-3">Hak Akses Menu</a></div>
            <?php endif; ?>

            <?php if (has_access('logs')): ?>
                <div class="menu-item px-3">
                    <a href="<?= base_url('logs') ?>" class="menu-link px-3">Logs Aktivitas</a>
                </div>
            <?php endif; ?>

            <?php if (has_access('pengaturan')): ?>
                <div class="menu-item px-3">
                    <a href="<?= base_url('pengaturan/landing') ?>" class="menu-link px-3">CMS Landing Page</a>
                </div>
            <?php endif; ?>

            
        </div>
    </div>
    <?php endif; ?>

</div>