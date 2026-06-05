<?php

namespace App\Models;

use CodeIgniter\Model;

class BillModel extends Model
{
    protected $table            = 'bills';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = ['customer_id', 'admin_id', 'month', 'year', 'amount', 'status', 'paid_amount', 'discount', 'order_id', 'snap_token', 'payment_type', 'payment_attempts', 'expiry_at'];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // --- PERBAIKAN: AKTIFKAN TIMESTAMPS ---
    // Karena di Migration ada created_at dan updated_at
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

     /**
     * FUNGSI DIPERBARUI: Mendukung filter Status (termasuk Partial), Bulan, dan Tahun
     */
    public function getAllBills($status = 'all', $month = null, $year = null)
    {
        $builder = $this->db->table($this->table . ' b')
            ->select('b.id, b.month, b.year, b.amount, b.paid_amount, b.discount, b.status, b.updated_at, c.name as customer_name, c.phone, p.name as package_name')
            ->join('customers c', 'c.id = b.customer_id')
            ->join('packages p', 'p.id = c.package_id');

        // 1. Filter Berdasarkan Status
        if ($status === 'paid') {
            $builder->where('b.status', 'paid');
        } 
        else if ($status === 'unpaid') {
            // PERBAIKAN: Menu 'Belum Bayar' akan menampilkan status 'unpaid' DAN 'partial' (cicilan)
            $builder->whereIn('b.status', ['unpaid', 'partial']);
        } 
        else if ($status === 'overdue') {
            $currentMonth = (int)date('n');
            $currentYear  = (int)date('Y');

            // PERBAIKAN: Tunggakan adalah yang belum lunas (unpaid/partial) di bulan-bulan lalu
            $builder->whereIn('b.status', ['unpaid', 'partial'])
                ->groupStart()
                    ->where('b.year <', $currentYear)
                    ->orGroupStart()
                        ->where('b.year', $currentYear)
                        ->where('b.month <', $currentMonth)
                    ->groupEnd()
                ->groupEnd();
        }

        // 2. Filter Berdasarkan Bulan (Jika ada)
        if (!empty($month)) {
            $builder->where('b.month', $month);
        }

        // 3. Filter Berdasarkan Tahun (Jika ada)
        if (!empty($year)) {
            $builder->where('b.year', $year);
        }

        return $builder->orderBy('b.year', 'DESC')
            ->orderBy('b.month', 'DESC')
            ->get()->getResultArray();
    }

    /**
     * Fungsi untuk mengambil daftar pelanggan yang belum bayar/belum lunas di BULAN BERJALAN
     * Digunakan untuk widget Dashboard
     */
    public function getPenunggak()
    {
        return $this->db->table($this->table . ' b')
            ->select('c.name as customer_name, c.phone, p.name as package_name, b.amount, b.paid_amount, b.status')
            ->join('customers c', 'c.id = b.customer_id')
            ->join('packages p', 'p.id = c.package_id')
            ->whereIn('b.status', ['unpaid', 'partial']) // Tampilkan juga yang baru cicil
            ->where('b.month', date('n'))
            ->where('b.year', date('Y'))
            ->get()->getResultArray();
    }

    /**
     * Fungsi untuk mengambil daftar pembayaran terbaru (Lunas atau Partial)
     * Difilter berdasarkan admin jika $adminId diberikan
     */
    public function getRecentPayments($adminId = null)
    {
        $builder = $this->db->table($this->table . ' b')
            ->select('c.name as customer_name, c.phone, p.name as package_name, b.amount, b.paid_amount, b.status, b.updated_at')
            ->join('customers c', 'c.id = b.customer_id')
            ->join('packages p', 'p.id = c.package_id')
            ->whereIn('b.status', ['paid', 'partial'])
            ->orderBy('b.updated_at', 'DESC');

        if (!empty($adminId)) {
            $builder->where('b.admin_id', $adminId);
        }

        return $builder->limit(5)->get()->getResultArray();
    }
    public function getLaporanPemasukan($tglMulai, $tglSelesai, $adminId = null)
    {
        $builder = $this->db->table($this->table . ' b')
            ->select('b.updated_at as tanggal, c.name as customer_name, c.phone, p.name as package_name, b.amount, b.paid_amount, b.status, u.name as admin_name')
            ->join('customers c', 'c.id = b.customer_id')
            ->join('packages p', 'p.id = c.package_id')
            ->join('users u', 'u.id = b.admin_id', 'left')
            ->whereIn('b.status', ['paid', 'partial'])
            ->where('b.updated_at >=', $tglMulai . ' 00:00:00')
            ->where('b.updated_at <=', $tglSelesai . ' 23:59:59')
            ->orderBy('b.updated_at', 'ASC');

        if (!empty($adminId)) {
            $builder->where('b.admin_id', $adminId);
        }

        return $builder->get()->getResultArray();
    }

    public function getLaporanTunggakan($bulan = null, $tahun = null)
    {
        $currentMonth = (int)date('n');
        $currentYear  = (int)date('Y');

        $b = $bulan ?? $currentMonth;
        $t = $tahun ?? $currentYear;

        $builder = $this->db->table($this->table . ' b')
            ->select('b.month, b.year, c.name as customer_name, c.phone, p.name as package_name, b.amount, b.paid_amount, b.status')
            ->join('customers c', 'c.id = b.customer_id')
            ->join('packages p', 'p.id = c.package_id')
            ->whereIn('b.status', ['unpaid', 'partial'])
            ->where('b.month', $b)
            ->where('b.year', $t)
            ->orderBy('c.name', 'ASC');

        return $builder->get()->getResultArray();
    }
}
