<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class PelangganController extends BaseController
{
    use ResponseTrait;
    protected $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    // Menampilkan halaman utama
    public function index()
    {
        $packageModel = new \App\Models\PackageModel();
        $userModel = new \App\Models\UserModel();
        $data = [
            'title'    => 'Manajemen Pelanggan',
            'packages' => $packageModel->findAll(),
            // Ambil user yang rolenya 'customer' untuk dihubungkan ke data pelanggan
            'users'    => $userModel->where('role', 'customer')->findAll()
        ];
        return view('pelanggan/index', $data);
    }

    // --- PERBAIKAN DI SINI ---
    public function list()
    {
        $data = $this->customerModel->select('customers.*, packages.name as package_name, packages.speed')
            ->join('packages', 'packages.id = customers.package_id', 'left')
            ->orderBy('customers.updated_at', 'DESC') // Agar yang baru diedit di atas
            ->findAll();

        return $this->response->setJSON(['data' => $data]);
    }

    public function save()
    {
        helper('log');
        $id = $this->request->getPost('id');
        $rules = [
            'name'              => 'required|min_length[3]',
            'phone'             => 'required',
            'address'           => 'required',
            'package_id'        => 'required',
            'status'            => 'required' // Tambahkan validasi status
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $payload = [
            'name'              => $this->request->getPost('name'),
            'phone'              => $this->request->getPost('phone'),
            'address'           => $this->request->getPost('address'),
            'package_id'        => $this->request->getPost('package_id'),
            'user_id'           => $this->request->getPost('user_id') ?: null,
            'installation_date' => $this->request->getPost('installation_date'),
            'status'            => $this->request->getPost('status'), // Ambil status dari input
        ];

        if (!empty($id)) {
            $this->customerModel->update($id, $payload);
            save_log("Update data pelanggan: " . $payload['name'] . " (Status: " . $payload['status'] . ")", "Pelanggan", "Update");
            $message = 'Data pelanggan berhasil diperbarui';
        } else {
            $this->customerModel->insert($payload);
            save_log("Menambah pelanggan baru: " . $payload['name'], "Pelanggan", "Create");
            $message = 'Pelanggan baru berhasil ditambahkan';
        }

        return $this->respondCreated(['status' => true, 'message' => $message]);
    }

    // Mengambil detail satu pelanggan (untuk Edit)
    public function show($id = null)
    {
        $data = $this->customerModel->find($id);
        if ($data) {
            return $this->respond($data);
        }
        return $this->failNotFound('Data pelanggan tidak ditemukan');
    }


    // Menghapus data
    public function delete($id = null)
    {
        helper('log');
        if ($this->customerModel->find($id)) {
            $this->customerModel->delete($id);
            save_log("Menghapus pelanggan: " . $id, "Pelanggan", "Delete");
            return $this->respondDeleted([
                'status'  => true,
                'message' => 'Data pelanggan berhasil dihapus'
            ]);
        }
        return $this->failNotFound('Data tidak ditemukan atau sudah dihapus');
    }
}
