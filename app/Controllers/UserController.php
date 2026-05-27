<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;


class UserController extends BaseController
{
    use ResponseTrait;

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    private function checkOwner()
    {
        if (session()->get('role') !== 'owner') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Menampilkan Halaman Utama Manajemen User
     */
    public function index()
    {
        $this->checkOwner();
        return view('user/index', [
            'title' => 'Manajemen Pengguna'
        ]);
    }

    /**
     * API untuk memuat data ke DataTables (JSON)
     */
    public function list()
    {
        $this->checkOwner();
        $data = $this->userModel->orderBy('id', 'DESC')->findAll();
        return $this->response->setJSON(['data' => $data]);
    }

    /**
     * Ambil detail satu user untuk Edit (JSON)
     */
    public function show($id = null)
    {
        $this->checkOwner();
        $user = $this->userModel->find($id);

        if ($user) {
            // Kita hapus password dari response agar tidak terlihat di browser konsol
            unset($user['password']);
            return $this->respond($user);
        }

        return $this->failNotFound('User tidak ditemukan');
    }

    /**
     * Simpan data (Tambah & Update)
     */
    public function save()
    {
        helper('log');
        $this->checkOwner();
        $id = $this->request->getPost('id');

        // Aturan Validasi
        $rules = [
            'name'     => 'required|min_length[3]',
            'username' => "required|alpha_numeric|min_length[3]|is_unique[users.username,id,{$id}]",
            'role'     => 'required'
        ];

        // Jika Tambah Baru (ID Kosong), Password Wajib Diisi
        if (empty($id)) {
            $rules['password'] = 'required|min_length[5]';
        }

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        // Siapkan Data
        $data = [
            'name'     => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'role'     => $this->request->getPost('role'),
        ];

        // Jika password diisi (saat tambah atau saat ganti password waktu edit)
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = $password;
            // Catatan: Hashing password dilakukan otomatis oleh UserModel (beforeInsert/Update)
        }

        // Eksekusi Simpan/Update
        if ($this->userModel->save(array_merge(['id' => $id], $data))) {
            if (empty($id)) {
        save_log("Tambah User: " . $data['username'], "User", "Create");
    } else {
        save_log("Update User: " . $data['username'], "User", "Update");
    }
            return $this->respondCreated([
                'status'  => true,
                'message' => empty($id) ? 'User baru berhasil ditambahkan' : 'Data user berhasil diperbarui'
            ]);
        }

        return $this->fail('Gagal menyimpan data');
    }

    /**
     * Menghapus User
     */
    public function delete($id = null)
    {
        helper('log');
        $this->checkOwner();
        // Proteksi: Jangan biarkan user menghapus dirinya sendiri yang sedang login
        if ($id == session()->get('user_id')) {
            return $this->fail('Anda tidak dapat menghapus akun Anda sendiri saat sedang digunakan.');
        }

        // Cek data sebelum hapus
        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->failNotFound('Data tidak ditemukan.');
        }

        // Proteksi: Owner pertama biasanya tidak boleh dihapus melalui web
        if ($user['role'] == 'owner' && $id == 1) {
            return $this->fail('Akun Owner utama tidak dapat dihapus.');
        }

        if ($this->userModel->delete($id)) {
             save_log("Delete User: " . $user['username'], "User", "Delete");
            return $this->respondDeleted([
                'status'  => true,
                'message' => 'Akun pengguna berhasil dihapus'
            ]);
        }

        return $this->fail('Gagal menghapus data');
    }
}
