<?php

namespace App\Controllers;

use App\Models\BillModel;
use App\Models\CustomerModel;
use CodeIgniter\API\ResponseTrait;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends BaseController
{
    use ResponseTrait;

    protected $billModel;
    protected $customerModel;

    public function __construct()
    {
        $this->billModel = new BillModel();
        $this->customerModel = new CustomerModel();

        // Konfigurasi Midtrans
        Config::$serverKey = 'Mid-server-LjMKIlOW2NQtSHsAtnSDqeOj'; // Server Key Sandbox Anda
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Step 1: Mendapatkan Snap Token (Popup Pembayaran)
     */
    public function getSnapToken($billId)
    {
        $bill = $this->billModel->select('bills.*, customers.name, customers.phone')
            ->join('customers', 'customers.id = bills.customer_id')
            ->find($billId);

        if (!$bill) {
            return $this->failNotFound('Data tagihan tidak ditemukan.');
        }

        $expiryAt = is_string($bill['expiry_at']) ? $bill['expiry_at'] : '2000-01-01 00:00:00';
        $attempts = (int)($bill['payment_attempts'] ?? 0);
        $now      = time();

        $maxAttempts = 3;
        $expiryDuration = 10; // 10 Menit

        $expiryTimestamp = strtotime($expiryAt);

        // Logika Pembatasan 3 Kali
        if ($attempts >= $maxAttempts && $expiryTimestamp > $now) {
            $diff = $expiryTimestamp - $now;
            $menit = ceil($diff / 60);
            return $this->fail("Batas pembuatan kode pembayaran tercapai. Silakan gunakan kode sebelumnya atau tunggu $menit menit lagi.");
        }

        if ($bill['status'] === 'paid') {
            return $this->fail("Tagihan ini sudah lunas.");
        }

        // Gunakan token lama jika masih valid
        if (!empty($bill['snap_token']) && $bill['status'] === 'pending' && $expiryTimestamp > $now) {
            return $this->respond(['token' => $bill['snap_token']]);
        }

        // Request Baru (Tanpa enabled_payments agar VA muncul semua)
        $orderId = 'AYONET-' . $bill['id'] . '-' . time();
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $bill['amount'],
            ],
            'customer_details' => [
                'first_name' => $bill['name'],
                'phone'      => $bill['phone'],
            ],
            'expiry' => [
                'start_time' => date("Y-m-d H:i:s O"),
                'unit'       => 'minute',
                'duration'   => $expiryDuration
            ],
            // enabled_payments dihapus agar mengikuti dashboard Midtrans
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $newAttempts = ($expiryTimestamp < $now) ? 1 : ($attempts + 1);
            $newExpiry   = date('Y-m-d H:i:s', strtotime("+$expiryDuration minutes"));

            $this->billModel->update($billId, [
                'order_id'         => $orderId,
                'snap_token'       => $snapToken,
                'status'           => 'pending',
                'payment_attempts' => $newAttempts,
                'expiry_at'        => $newExpiry
            ]);

            return $this->respond(['token' => $snapToken]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Step 2: WEBHOOK / NOTIFICATION (Auto Approve)
     */
    public function notification()
    {
        try {
            $jsonRaw = $this->request->getJSON(true);

            if (empty($jsonRaw)) {
                return $this->respond(['status' => 'empty'], 200);
            }

            // Catat log untuk debug (Cek di writable/logs/...)
            log_message('error', 'Midtrans Notification: ' . json_encode($jsonRaw));

            $transaction = $jsonRaw['transaction_status'];
            $type        = $jsonRaw['payment_type'];
            $orderId     = $jsonRaw['order_id'];
            $fraud       = $jsonRaw['fraud_status'] ?? null;

            $db = \Config\Database::connect();

            // Logika Penentuan Status
            $statusUpdate = 'pending';
            if ($transaction == 'settlement' || $transaction == 'capture') {
                if ($transaction == 'capture' && $type == 'credit_card' && $fraud == 'challenge') {
                    $statusUpdate = 'pending';
                } else {
                    $statusUpdate = 'paid'; // Berhasil
                }
            } elseif ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
                $statusUpdate = 'unpaid'; // Gagal/Hangus
            }

            // Cari Bill ID dari Order ID (AYONET-ID-TIME)
            $parts = explode('-', $orderId);
            $billId = $parts[1] ?? null;

            if ($billId) {
                // Update Database
                $db->table('bills')->where('id', $billId)->update([
                    'status'       => $statusUpdate,
                    'payment_type' => $type
                ]);

                return $this->respond(['status' => 'success', 'message' => 'Status updated to ' . $statusUpdate], 200);
            }

            return $this->respond(['status' => 'error', 'message' => 'Bill ID not found'], 200);
        } catch (\Exception $e) {
            log_message('error', 'Notification Error: ' . $e->getMessage());
            return $this->respond(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
