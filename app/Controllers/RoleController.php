<?php

namespace App\Controllers;

use App\Models\RolePermissionModel;
use CodeIgniter\Controller;

class RoleController extends BaseController
{
    // 1. Tampilkan Halaman Utama Tabel Hak Akses
    public function index()
    {
        $roleModel = new RolePermissionModel();

        // Mengambil daftar role dan menu yang mereka miliki (untuk tampilan tabel)
        $data['roles'] = $roleModel->getRolesWithMenus();

        // Ambil semua daftar menu untuk ditampilkan di modal checkbox
        $data['all_menus'] = $roleModel->getAllMenus();
        
        $data['title'] = "Manajemen Hak Akses";
        
        return view('role/index', $data); // Ganti sesuai folder view Anda
    }

    // 2. Fungsi untuk Modal: Ambil menu apa saja yang sudah dicentang
    public function ambil_akses($role)
    {
        $model = new RolePermissionModel();
        // Ambil ID menu saja agar mudah dicocokkan di JavaScript
        $data = $model->where('role', $role)->findAll();
        return $this->response->setJSON($data);
    }

    // 3. Fungsi untuk Simpan: Update centang menu di database
    public function simpan()
    {
        $model = new RolePermissionModel();
        $role = $this->request->getPost('role_name');
        $menuIds = $this->request->getPost('menu_ids');

        $status = $model->updateRolePermissions($role, $menuIds);

        if ($status === FALSE) {
            return $this->response->setJSON(['status' => false, 'pesan' => 'Gagal memperbarui hak akses']);
        }

        return $this->response->setJSON(['status' => true, 'pesan' => 'Hak akses berhasil diperbarui']);
    }
}