<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

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

        $db = \Config\Database::connect();
        $pelanggan = $db->table('customers')
            ->where('phone', $phoneNormalized)
            ->orWhere('phone', $this->toLocalPhone($phoneNormalized))
            ->get()
            ->getRowArray();

        if (!$pelanggan) {
            return $this->respond(['status' => false, 'message' => "Nomor {$phoneNormalized} tidak terdaftar."], 404);
        }

        // --- GENERATE OTP ---
        $otp = str_pad((string)random_int(0, 999999), $this->otpLength, '0', STR_PAD_LEFT);

        // --- SIMPAN OTP (Selalu pakai format 628... agar sinkron) ---
        $db->table('otp_codes')->where('phone', $phoneNormalized)->delete();
        $db->table('otp_codes')->insert([
            'phone'      => $phoneNormalized,
            'otp_code'   => $otp,
            'expired_at' => date('Y-m-d H:i:s', time() + $this->otpTtl),
            'is_used'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

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
        // 1. Ambil input dari Dify
        $json = $this->request->getJSON(true);
        $phone = $json['phone'] ?? $this->request->getVar('phone');
        $otp = $json['otp_code'] ?? $this->request->getVar('otp_code');

        if (empty($phone) || empty($otp)) {
            return $this->fail('Data tidak lengkap.', 400);
        }

        // 2. Normalisasi nomor HP agar sinkron (628...)
        $phoneNormalized = $this->normalizePhone($phone);

        $db = \Config\Database::connect();

        // 3. Cari Kode OTP di database
        $record = $db->table('otp_codes')
            ->where('phone', $phoneNormalized)
            ->where('is_used', 0)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getRowArray();

        // Cek validitas OTP
        if (!$record || $record['otp_code'] !== (string)$otp) {
            return $this->respond(['status' => false, 'message' => 'Kode OTP salah atau sudah tidak berlaku.'], 404);
        }

        if (strtotime($record['expired_at']) < time()) {
            return $this->respond(['status' => false, 'message' => 'OTP kadaluwarsa.'], 410);
        }

        // 4. Tandai OTP sudah digunakan
        $db->table('otp_codes')->where('id', $record['id'])->update(['is_used' => 1]);

        // 5. Ambil data profil pelanggan
        $pelanggan = $db->table('customers')
            ->select('customers.id, customers.name, customers.address, customers.status, packages.name as nama_paket')
            ->join('packages', 'packages.id = customers.package_id', 'left')
            ->where('customers.phone', $phoneNormalized)
            ->orWhere('customers.phone', $this->toLocalPhone($phoneNormalized))
            ->get()
            ->getRowArray();

        if (!$pelanggan) {
            return $this->respond([
                'status'  => false,
                'message' => 'Verifikasi berhasil, tapi profil pelanggan tidak ditemukan.'
            ], 404);
        }

        // 6. AMBIL DATA DARI TABEL BILLS (6 bulan terakhir)
        // Pastikan di tabel 'bills' Anda memiliki kolom 'customer_id'
        $tagihan = $db->table('bills')
            ->where('customer_id', $pelanggan['id'])
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->limit(6)
            ->get()
            ->getResultArray();

        // 7. HASIL AKHIR (Dikirim ke Dify)
        return $this->respond([
            'status'    => true,
            'message'   => 'Verifikasi berhasil!',
            'pelanggan' => [
                'name'       => $pelanggan['name'],
                'address'    => $pelanggan['address'] ?? 'Alamat belum diatur',
                'nama_paket' => $pelanggan['nama_paket'] ?? 'Tanpa Paket',
                'status'     => ucfirst($pelanggan['status'])
            ],
            // Data tagihan ini yang akan dibaca AI untuk menjawab pertanyaan "Sudah bayar?"
            'daftar_tagihan' => $tagihan
        ]);
    }

    // =========================================================================
    // 3. GET ALL PACKAGES
    // GET /api/get-packages
    // =========================================================================
    public function getPackages(): \CodeIgniter\HTTP\ResponseInterface
    {
        $db = \Config\Database::connect();

        // Mengambil semua data dari tabel packages
        $packages = $db->table('packages')
            ->select('name, speed, price, description')
            ->get()
            ->getResultArray();

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
