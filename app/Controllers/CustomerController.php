<?php

namespace App\Controllers;

use App\Models\BillModel;
use App\Models\CustomerModel;
use CodeIgniter\API\ResponseTrait;

class CustomerController extends BaseController
{
    use ResponseTrait;

    protected $billModel;
    protected $customerModel;

    public function __construct()
    {
        $this->billModel = new BillModel();
        $this->customerModel = new CustomerModel();
    }

    /**
     * Menampilkan halaman utama tagihan milik customer sendiri
     */
    public function index()
    {
        return view('customer/tagihan', [
            'title' => 'Tagihan Saya & Pembayaran'
        ]);
    }

    /**
     * API untuk memuat data tagihan khusus milik customer yang sedang login
     * Untuk ditampilkan di DataTables AJAX
     */
    public function listTagihan()
    {
        // 1. Ambil ID User dari session (ID akun yang sedang login)
        // Pastikan di AuthController Anda menyimpan session dengan nama 'id'
        $userId = session()->get('id');

        // 2. Cari data PELANGGAN yang terhubung dengan akun USER ini
        $customer = $this->customerModel->where('user_id', $userId)->first();

        // Jika user login tidak terhubung ke data pelanggan mana pun
        if (!$customer) {
            return $this->response->setJSON(['data' => []]);
        }

        // 3. Ambil semua tagihan berdasarkan customer_id tersebut
        $data = $this->billModel->where('customer_id', $customer['id'])
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->findAll();

        return $this->response->setJSON(['data' => $data]);
    }
}
