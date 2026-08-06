<?php


namespace App\Controllers;

use App\Models\OtpModel;
use App\Models\CustomerModel;
use App\Models\BillModel;
use App\Models\PackageModel;
use CodeIgniter\API\ResponseTrait;

class OtpController extends BaseController
{
    use ResponseTrait;

    // -------------------------------------------------------------------------
    // KONFIGURASI
    // -------------------------------------------------------------------------
    private int $otpLength = 6;
    private int $otpTtl = 300; // 5 menit

    // =========================================================================
    // 1. SEND OTP
    // =========================================================================
    public function sendOtp(): \CodeIgniter\HTTP\ResponseInterface
    {
        $json = $this->request->getJSON(true);
        $phone = $json['phone'] ?? $this->request->getVar('phone');

        if (empty($phone)) {
            return $this->fail('Parameter "phone" wajib diisi.', 400);
        }

        // --- NORMALISASI (Paksa jadi 628...) ---
        $phoneNormalized = $this->normalizePhone($phone);

        if (!$this->isValidPhone($phoneNormalized)) {
            return $this->respond(['status' => false, 'message' => 'Format nomor HP tidak valid.'], 422);
        }

        $customerModel = new CustomerModel();
        $pelanggan = $customerModel->getCustomerByPhone($phoneNormalized, $this->toLocalPhone($phoneNormalized));

        if (!$pelanggan) {
            return $this->respond(['status' => false, 'message' => "Nomor {$phoneNormalized} tidak terdaftar."], 404);
        }

        // --- GENERATE OTP ---
        $otp = str_pad((string)random_int(0, 999999), $this->otpLength, '0', STR_PAD_LEFT);

        // --- SIMPAN OTP (Selalu pakai format 628... agar sinkron) ---
        $otpModel = new OtpModel();
        $otpModel->createOtp($phoneNormalized, $otp, $this->otpTtl);

        $sent = $this->sendViaWhatsapp($phoneNormalized, $otp);

        return $this->respond([
            'status'  => true,
            'message' => $sent ? "OTP terkirim ke WhatsApp." : "OTP tersimpan (Mode Dev).",
        ]);
    }

    // =========================================================================
    // 2. VERIFY OTP
    // =========================================================================
    public function verifyOtp(): \CodeIgniter\HTTP\ResponseInterface
    {
        $json = $this->request->getJSON(true);
        $phone = $json['phone'] ?? $this->request->getVar('phone');
        $otp = $json['otp_code'] ?? $this->request->getVar('otp_code');

        if (empty($phone) || empty($otp)) {
            return $this->fail('Data tidak lengkap.', 400);
        }
        $phoneNormalized = $this->normalizePhone($phone);

        $otpModel = new OtpModel();
        $record = $otpModel->getActiveOtp($phoneNormalized);
        if (!$record || $record['otp_code'] !== (string)$otp) {
            return $this->respond(['status' => false, 'message' => 'Kode OTP salah atau sudah tidak berlaku.'], 404);
        }

        if (strtotime($record['expired_at']) < time()) {
            return $this->respond(['status' => false, 'message' => 'OTP kadaluwarsa.'], 410);
        }

        $otpModel->markAsUsed($record['id']);

        $customerModel = new CustomerModel();
        $pelanggan = $customerModel->getCustomerWithPackageByPhone($phoneNormalized, $this->toLocalPhone($phoneNormalized));

        if (!$pelanggan) {
            return $this->respond([
                'status'  => false,
                'message' => 'Verifikasi berhasil, tapi profil pelanggan tidak ditemukan.'
            ], 404);
        }
        $billModel = new BillModel();
        $tagihan = $billModel->getLatestBillsByCustomer($pelanggan['id'], 6);

        return $this->respond([
            'status'    => true,
            'message'   => 'Verifikasi berhasil!',
            'pelanggan' => [
                'name'       => $pelanggan['name'],
                'address'    => $pelanggan['address'] ?? 'Alamat belum diatur',
                'nama_paket' => $pelanggan['nama_paket'] ?? 'Tanpa Paket',
                'status'     => ucfirst($pelanggan['status'])
            ],
            'daftar_tagihan' => $tagihan
        ]);
    }

    // =========================================================================
    // 3. GET ALL PACKAGES
    // GET /api/get-packages
    // =========================================================================
    public function getPackages(): \CodeIgniter\HTTP\ResponseInterface
    {
        $packageModel = new PackageModel();

        // Mengambil semua data dari tabel packages
        $packages = $packageModel->select('name, speed, price, description')->findAll();

        if (empty($packages)) {
            return $this->respond([
                'status'  => false,
                'message' => 'Maaf, saat ini belum ada informasi paket yang tersedia.',
            ], 404);
        }

        return $this->respond([
            'status' => true,
            'data'   => $packages
        ]);
    }

    // =========================================================================
    // HELPERS (Private)
    // =========================================================================

    private function normalizePhone(string $phone): string
    {
        // 1. Hapus semua karakter non-digit (seperti +, -, spasi)
        $phone = preg_replace('/\D/', '', $phone);

        // 2. Jika awalan '0', ganti jadi '62'
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        // 3. Jika awalan langsung '8...', tambahkan '62'
        elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    private function toLocalPhone(string $phone): string
    {
        if (str_starts_with($phone, '62')) {
            return '0' . substr($phone, 2);
        }
        return $phone;
    }

    private function isValidPhone(string $phone): bool
    {
        // Setelah normalisasi, harus diawali 628 dan panjang 10-15 digit
        return (str_starts_with($phone, '628') && strlen($phone) >= 10 && strlen($phone) <= 15);
    }

    private function sendViaWhatsapp(string $phone, string $otp): bool
    {
        $token = env('FONNTE_TOKEN');
        if (empty($token)) return false;

        $pesan = "Kode OTP Anda: *{$otp}*.\nBerlaku 5 menit. Jangan berikan kode ini kepada siapa pun.";

        try {
            $client = \Config\Services::curlrequest();
            $response = $client->post('https://api.fonnte.com/send', [
                'headers'     => ['Authorization' => $token],
                'form_params' => [
                    'target'  => $phone,
                    'message' => $pesan,
                ],
            ]);
            $body = json_decode($response->getBody(), true);
            return isset($body['status']) && $body['status'] === true;
        } catch (\Throwable $e) {
            log_message('error', '[OTP] Error: ' . $e->getMessage());
            return false;
        }
    }
}
