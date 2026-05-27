<?php

namespace App\Controllers;

use App\Models\PackageModel;
use CodeIgniter\API\ResponseTrait;

class PackageController extends BaseController
{
    use ResponseTrait;
    protected $packageModel;

    public function __construct()
    {
        $this->packageModel = new PackageModel();
    }

    public function index()
    {
        return view('paket/index', [
            'title' => 'Manajemen Paket Internet'
        ]);
    }

    public function list()
    {
        return $this->response->setJSON([
            'data' => $this->packageModel->findAll()
        ]);
    }

    public function show($id = null)
    {
        $data = $this->packageModel->find($id);
        return $data ? $this->respond($data) : $this->failNotFound('Paket tidak ditemukan');
    }

    public function save()
    {   
        helper('log');
        $id = $this->request->getPost('id');
        $namaPaket = $this->request->getPost('name'); 

        $data = [
            'name'        => $namaPaket,
            'speed'       => $this->request->getPost('speed'),
            'price'       => $this->request->getPost('price'),
            'description' => $this->request->getPost('description'),
        ];

        $isUpdate = !empty($id);
        $aksiLabel = $isUpdate ? "Update" : "Tambah";

        if ($this->packageModel->save(array_merge(['id' => $id], $data))) {
            $currentId = $isUpdate ? $id : $this->packageModel->getInsertID();
            save_log("Menyimpan paket internet baru: $namaPaket", "Paket", $aksiLabel);
            return $this->respondCreated(['status' => true, 'message' => 'Paket berhasil disimpan']);
        }
        return $this->fail('Gagal menyimpan paket');
    }

   public function delete($id = null)
    {
        helper('log');

        // 1. Cari dulu data paketnya sebelum dihapus untuk mengambil namanya
        $paket = $this->packageModel->find($id);

        if (!$paket) {
            return $this->failNotFound('Paket tidak ditemukan atau sudah dihapus');
        }

        $namaPaket = $paket['name']; // Simpan nama paketnya

        // 2. Baru lakukan penghapusan
        if ($this->packageModel->delete($id)) {
            
            // 3. Catat log dengan Nama dan ID agar Owner jelas melihatnya
            save_log("Menghapus paket internet: $namaPaket secara permanen", "Paket", "Delete");

            return $this->respondDeleted(['status' => true, 'message' => 'Paket berhasil dihapus']);
        }

        return $this->fail('Gagal menghapus paket');
    }
}
