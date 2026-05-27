<?php

namespace App\Controllers;

use App\Models\RolePermissionModel;
use CodeIgniter\Controller;

class RoleController extends BaseController
{
    // 1. Tampilkan Halaman Utama Tabel Hak Akses
    public function index()
    {
        $db = \Config\Database::connect();

        // Mengambil daftar role dan menu yang mereka miliki (untuk tampilan tabel)
        // Kita menggunakan Query Builder agar lebih mudah menggabungkan datanya
        $data['roles'] = $db->query("
            SELECT 
                r.role_name as role, 
                GROUP_CONCAT(m.nama_menu SEPARATOR ', ') as menu_diizinkan
            FROM (SELECT 'owner' as role_name UNION SELECT 'admin' UNION SELECT 'customer') r
            LEFT JOIN role_permissions rp ON r.role_name = rp.role
            LEFT JOIN menus m ON rp.id_menu = m.id_menu
            GROUP BY r.role_name
        ")->getResultArray();

        // Ambil semua daftar menu untuk ditampilkan di modal checkbox
        $data['all_menus'] = $db->table('menus')->get()->getResultArray();
        
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

        // Gunakan transaksi agar jika gagal, data tidak rusak
        $db = \Config\Database::connect();
        $db->transStart();

        // Hapus semua akses lama untuk role tersebut
        $model->where('role', $role)->delete();

        // Masukkan yang baru jika ada menu yang dipilih
        if (!empty($menuIds)) {
            foreach ($menuIds as $id_menu) {
                $model->insert([
                    'role'    => $role,
                    'id_menu' => $id_menu
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return $this->response->setJSON(['status' => false, 'pesan' => 'Gagal memperbarui hak akses']);
        }

        return $this->response->setJSON(['status' => true, 'pesan' => 'Hak akses berhasil diperbarui']);
    }
}