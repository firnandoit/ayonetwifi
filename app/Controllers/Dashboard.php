<?php

namespace App\Controllers;

use App\Models\BillModel;
use App\Models\CustomerModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Ambil data default menggunakan logic yang sama dengan filter
        $data = $this->_getDashboardData(date('Y-m-01'), date('Y-m-d'));
        
        $data['title'] = 'Dashboard WiFi';
        $data['user']  = [
            'name' => $session->get('name'),
            'role' => $session->get('role')
        ];

        return view('dashboard/index', $data);
    }

    // Method khusus untuk menangani request AJAX
    public function filter()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/dashboard');
        }

        $tglMulai = $this->request->getGet('tgl_mulai');
        $tglSelesai = $this->request->getGet('tgl_selesai');

        $data = $this->_getDashboardData($tglMulai, $tglSelesai);

        // Kirim respon dalam format JSON
        return $this->response->setJSON([
            'uang_masuk_formatted'    => 'Rp ' . number_format($data['uang_masuk'], 0, ',', '.'),
            'total_pelanggan'         => number_format($data['total_pelanggan'], 0, ',', '.'),
            'pelanggan_nonaktif'      => number_format($data['pelanggan_nonaktif'], 0, ',', '.'),
            'total_penunggak'         => number_format($data['total_penunggak'], 0, ',', '.'),
            'total_mutasi_formatted'  => 'Rp ' . number_format($data['filter']['total_mutasi'], 0, ',', '.'),
            'list_terbaru'            => $data['list_terbaru']
        ]);
    }

    /**
     * Helper untuk mengambil data dashboard agar logic tidak double
     */
    private function _getDashboardData($tglMulai, $tglSelesai)
    {
        $billModel = new BillModel();
        $customerModel = new CustomerModel();

        $bulanIni = date('m');
        $tahunIni = date('Y');
        
        $adminId = session()->get('role') !== 'owner' ? session()->get('id') : null;

        // Logic Mutasi (Berdasarkan Filter Tanggal)
        $builderMutasi = $billModel->selectSum('paid_amount')
            ->where('updated_at >=', $tglMulai . ' 00:00:00')
            ->where('updated_at <=', $tglSelesai . ' 23:59:59');
        if ($adminId) $builderMutasi->where('admin_id', $adminId);
        
        $queryMutasi = $builderMutasi->get()->getRow();
        $totalMutasi = $queryMutasi->paid_amount ?? 0;

        // Logic Statistik Utama (Tetap Bulan Ini)
        $builderUangMasuk = $billModel->selectSum('paid_amount')
            ->where('month', $bulanIni)
            ->where('year', $tahunIni);
        if ($adminId) $builderUangMasuk->where('admin_id', $adminId);
        
        $queryUangMasuk = $builderUangMasuk->get()->getRow();
        $uangMasuk = $queryUangMasuk->paid_amount ?? 0;

        return [
            'uang_masuk'         => $uangMasuk,
            'total_pelanggan'    => $customerModel->where('status', 'aktif')->countAllResults(),
            'pelanggan_nonaktif' => $customerModel->whereIn('status', ['isolir', 'putus'])->countAllResults(),
            'total_penunggak'    => $billModel->where(['status' => 'unpaid', 'month' => $bulanIni, 'year' => $tahunIni])->countAllResults(),
            'list_terbaru'       => $billModel->getRecentPayments($adminId),
            'filter' => [
                'tgl_mulai'    => $tglMulai,
                'tgl_selesai'  => $tglSelesai,
                'total_mutasi' => $totalMutasi
            ]
        ];
    }
}