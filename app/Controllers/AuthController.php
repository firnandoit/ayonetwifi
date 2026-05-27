<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

   public function processLogin()
    {
        $session = session();
        $model = new \App\Models\UserModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $data = $model->where('username', $username)->first();

        if ($data) {
            if (password_verify($password, $data['password'])) {
                
                // --- AMBIL DATA PERMISSION & MENU DARI DATABASE ---
                $db = \Config\Database::connect();
                $permissionsData = $db->table('role_permissions')
                    ->join('menus', 'menus.id_menu = role_permissions.id_menu')
                    ->where('role', $data['role'])
                    ->get()->getResultArray();

                // Ambil daftar slug saja (untuk proteksi filter)
                // Hasilnya: ['dashboard', 'pelanggan', 'customer', dll]
                $userPermissions = array_column($permissionsData, 'slug');

                // Ambil data menu lengkap (untuk tampilan sidebar/header)
                $userMenus = $permissionsData;

                $ses_data = [
                    'id'          => $data['id'],
                    'name'        => $data['name'],
                    'username'    => $data['username'],
                    'role'        => $data['role'],
                    'permissions' => $userPermissions, // Simpan daftar slug
                    'user_menus'  => $userMenus,       // Simpan data menu lengkap
                    'logged_in'   => TRUE
                ];
                
                $session->set($ses_data);

                if ($data['role'] === 'customer') {
                    return redirect()->to('/customer/tagihan');
                }
                return redirect()->to('/dashboard');

            } else {
                $session->setFlashdata('msg', 'Password salah.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'Username tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
