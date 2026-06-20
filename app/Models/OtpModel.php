<?php

namespace App\Models;

use CodeIgniter\Model;

class OtpModel extends Model
{
    protected $table = 'otp_codes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['phone', 'otp_code', 'expired_at', 'is_used', 'created_at'];

    /**
     * Hapus OTP lama dan buat OTP baru untuk nomor tertentu
     */
    public function createOtp($phone, $otpCode, $otpTtl)
    {
        $this->where('phone', $phone)->delete();
        return $this->insert([
            'phone'      => $phone,
            'otp_code'   => $otpCode,
            'expired_at' => date('Y-m-d H:i:s', time() + $otpTtl),
            'is_used'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Cari OTP aktif berdasarkan nomor HP
     */
    public function getActiveOtp($phone)
    {
        return $this->where('phone', $phone)
            ->where('is_used', 0)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    /**
     * Tandai OTP sudah digunakan
     */
    public function markAsUsed($id)
    {
        return $this->update($id, ['is_used' => 1]);
    }
}
