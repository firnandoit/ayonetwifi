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

        // Generate Math CAPTCHA with Words
        $words = [
            1 => 'satu', 2 => 'dua', 3 => 'tiga', 4 => 'empat', 5 => 'lima', 
            6 => 'enam', 7 => 'tujuh', 8 => 'delapan', 9 => 'sembilan', 10 => 'sepuluh'
        ];

        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        
        session()->set('captcha_answer', $num1 + $num2);

        $captcha_text = $words[$num1] . ' + ' . $words[$num2];
        
        // Buat distorsi efek menggunakan array char
        $captcha_chars = [];
        foreach(str_split($captcha_text) as $char) {
            if ($char == ' ') {
                $captcha_chars[] = ['char' => '&nbsp;', 'style' => ''];
                continue;
            }
            $rotate = rand(-20, 20);
            $size = rand(20, 28);
            $weight = rand(400, 900);
            $fonts = ['Arial', 'Courier New', 'Georgia', 'Times New Roman', 'Verdana', 'Trebuchet MS'];
            $font = $fonts[array_rand($fonts)];
            
            $style = "display:inline-block; transform:rotate({$rotate}deg); font-size:{$size}px; font-weight:{$weight}; font-family:'{$font}', sans-serif; color:#222; margin: 0 1px;";
            $captcha_chars[] = ['char' => $char, 'style' => $style];
        }

        return view('auth/login', ['captcha_chars' => $captcha_chars]);
    }

    public function processLogin()
    {
        $session = session();
        $model = new \App\Models\UserModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $captcha = $this->request->getVar('captcha');

        // Validasi CAPTCHA
        $captcha_answer = session()->get('captcha_answer');
        if ($captcha == '' || $captcha != $captcha_answer) {
            $session->setFlashdata('msg', 'Jawaban CAPTCHA salah.');
            return redirect()->to('/login');
        }

        $data = $model->where('username', $username)->first();

        if ($data) {
            if (password_verify($password, $data['password'])) {

                // --- AMBIL DATA PERMISSION & MENU DARI DATABASE ---
                $rolePermissionModel = new \App\Models\RolePermissionModel();
                $permissionsData = $rolePermissionModel->getMenusByRole($data['role']);

                // Ambil daftar slug saja (untuk proteksi filter)
                // Hasilnya: ['dashboard', 'pelanggan', 'customer', dll]
                $userPermissions = array_column($permissionsData, 'slug');

                // Ambil data menu lengkap (untuk tampilan sidebar/header)
                $userMenus = $permissionsData;

                // Regenerate session ID untuk mencegah Session Fixation
                session()->regenerate();

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
        return redirect()->to('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
