<?php

namespace App\Controllers;

use App\Models\BillModel;
use App\Models\CustomerModel;
use CodeIgniter\API\ResponseTrait;

class TagihanController extends BaseController
{
    use ResponseTrait;
    /** @var \App\Models\BillModel */
    protected $billModel;
    protected $customerModel;

    

    public function __construct()
    {
        $this->billModel = new BillModel();
        $this->customerModel = new CustomerModel();

        helper('log');
    }

    /**
     * Menampilkan Halaman Utama Tagihan
     * Filter 'all' untuk semua, 'unpaid' untuk belum bayar, 'paid' untuk lunas
     */
    public function index()
    {
        $status = $this->request->getGet('status') ?? 'all';

        return view('tagihan/index', [
            'title'  => 'Semua Tagihan',
            'status' => 'all'
        ]);
    }

    // Halaman Khusus Belum Bayar
    public function belumBayar()
    {
        return view('tagihan/index', [
            'title'  => 'Daftar Tagihan Belum Bayar',
            'status' => 'unpaid'
        ]);
    }

    // Halaman Khusus Lunas
    public function lunas()
    {
        return view('tagihan/index', [
            'title'  => 'Daftar Tagihan Lunas',
            'status' => 'paid'
        ]);
    }

    /**
     * API untuk memuat data ke DataTables (JSON)
     * Memanggil fungsi getAllBills dari BillModel
     */
    public function list()
    {
        // Tangkap filter dari request DataTable
        $status = $this->request->getGet('status') ?? 'all';
        $month  = $this->request->getGet('month');
        $year   = $this->request->getGet('year');

        // Panggil model dengan parameter tambahan
        $data = $this->billModel->getAllBills($status, $month, $year);

        return $this->response->setJSON(['data' => $data]);
    }
    

    public function generate()
    {
        $bulan = date('n'); // Format 1-12
        $tahun = date('Y'); // Format 2024

        // 1. Ambil semua pelanggan aktif beserta harga paketnya
          $pelangganAktif = $this->customerModel->select('customers.id, packages.price')
        ->join('packages', 'packages.id = customers.package_id')
        ->where('customers.status', 'aktif') // Pastikan ini 'aktif'
        ->findAll();

        if (empty($pelangganAktif)) {
            return $this->fail('Tidak ada pelanggan aktif untuk dibuatkan tagihan.');
        }

        $count = 0;
        foreach ($pelangganAktif as $p) {
            // 2. Cek apakah sudah ada tagihan untuk pelanggan ini di bulan & tahun ini (agar tidak ganda)
            $cek = $this->billModel->where([
                'customer_id' => $p['id'],
                'month'       => $bulan,
                'year'        => $tahun
            ])->first();

            // 3. Jika belum ada tagihan, maka buat baru
            if (!$cek) {
                $this->billModel->insert([
                    'customer_id' => $p['id'],
                    'month'       => $bulan,
                    'year'        => $tahun,
                    'amount'      => $p['price'],
                    'status'      => 'unpaid'
                ]);
                $count++;
            }
        }

        if ($count > 0) {
            save_log("Membuat $count tagihan otomatis untuk periode bulan $bulan/$tahun", "Tagihan", "Create");
        }

        return $this->respond([
            'status'  => true,
            'message' => "Berhasil membuat $count tagihan baru untuk periode " . date('F Y')
        ]);
    }

    /**
     * Konfirmasi pembayaran tagihan
     */
    public function bayar($id = null)
{
    if (!$id) return $this->fail('ID Tagihan tidak ditemukan');

    // Ambil nominal input dari Swal.fire
    $jumlahBayarInput = $this->request->getPost('jumlah_bayar');
    $diskonInput = $this->request->getPost('diskon') ?? 0; // Ambil nilai diskon
    
    // Pastikan input ada
    if ($jumlahBayarInput === null) {
        return $this->fail('Input pembayaran tidak diterima');
    }

    $bill = $this->billModel->find($id);
    if (!$bill) return $this->failNotFound('Data tidak ditemukan');

    // Konversi semua ke float agar aman
    $totalTagihan  = (float)$bill['amount'];
    $sudahDibayar  = (float)($bill['paid_amount'] ?? 0);
    $bayarSekarang = (float)$jumlahBayarInput;
    $diskon        = (float)$diskonInput;
    
    // Server-side Validation: Pastikan total uang yang masuk + diskon tidak melebihi sisa tagihan
    $sisaTagihan = $totalTagihan - $sudahDibayar;
    if ($bayarSekarang > ($sisaTagihan - $diskon)) {
        return $this->fail('Uang masuk + Diskon melebihi sisa tagihan (' . number_format($sisaTagihan) . ')');
    }

    // Total baru yang masuk ke database
    $totalSetelahBayar = $sudahDibayar + $bayarSekarang;
    
    // Total nilai untuk pengecekan status (Total Bayar + Diskon)
    $totalNilai = $totalSetelahBayar + $diskon;

    // Logika Status
    if ($totalNilai >= $totalTagihan) {
        $status = 'paid';
        // Cegah kelebihan bayar masuk ke dashboard jika user salah ketik
        if ($totalSetelahBayar > ($totalTagihan - $diskon)) {
            $totalSetelahBayar = $totalTagihan - $diskon;
        }
    } else {
        $status = 'partial'; // Cicil
    }

    // Update ke Database
    $update = $this->billModel->update($id, [
        'paid_amount' => $totalSetelahBayar,
        'discount'    => $diskon,
        'status'      => $status,
        'updated_at'  => date('Y-m-d H:i:s')
    ]);

    if ($update) {
        save_log("Update Bayar ID $id: Masuk Rp" . number_format($bayarSekarang) . " Diskon Rp" . number_format($diskon) . " (Status: $status)", "Tagihan", "Update");
        return $this->respond([
            'status'  => true,
            'message' => 'Pembayaran berhasil disimpan',
        ]);
    }

    return $this->fail('Gagal update database');
}

    public function rollback($id = null)
    {
        if (!$id) return $this->fail('ID tidak ditemukan');

       $this->billModel->update($id, [
        'status' => 'unpaid',
        'paid_amount' => 0,
        'discount' => 0 
    ]);
        save_log("Melakukan rollback tagihan ID: $id", "Tagihan", "Rollback");

        return $this->respond(['status' => true, 'message' => 'Tagihan berhasil di-rollback ke Belum Bayar.']);
    }



    public function tunggakan()
    {
        return view('tagihan/index', [
            'title'  => 'Tunggakan Bulan Sebelumnya',
            'status' => 'overdue' // Status khusus untuk model
        ]);
    }

    /**
     * Menghapus tagihan
     */
    public function delete($id = null)
    {
        if ($this->billModel->delete($id)) {
            save_log("Menghapus data tagihan ID: $id secara permanen", "Tagihan", "Delete");
            return $this->respondDeleted([
                'status'  => true,
                'message' => 'Data tagihan berhasil dihapus.'
            ]);
        }

        return $this->fail('Gagal menghapus data.');
    }
}
