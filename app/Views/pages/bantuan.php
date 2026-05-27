<?= $this->extend('layout/page_layout') ?>

<?= $this->section('content') ?>
<h2>Bantuan</h2>
<p>Halaman bantuan ini menyediakan panduan cepat mengenai layanan dan pemecahan masalah koneksi yang mungkin Anda hadapi.</p>

<div class="mt-10 text-gray-600">
    <h4 class="mb-5">Koneksi Lambat atau Putus?</h4>
    <ol>
        <li class="mb-3">Coba restart ulang (matikan dan hidupkan kembali) router WiFi Anda.</li>
        <li class="mb-3">Pastikan kabel fiber optic (jika menggunakan fiber) tidak tertekuk terlalu tajam atau terputus.</li>
        <li class="mb-3">Jika masalah belum teratasi, silakan melapor kepada tim teknisi kami.</li>
    </ol>
</div>
<?= $this->endSection() ?>
