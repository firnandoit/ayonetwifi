<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class ProfileController extends BaseController
{
    use ResponseTrait;

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Menampilkan Halaman Profil
     */
    public function index()
    {
        $userId = session()->get('id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login kembali.');
        }

        return view('profile/index', [
            'title' => 'Profil Saya',
            'user'  => $user
        ]);
    }

    /**
     * Update Password Profil (Dengan Validasi Password Lama)
     */
    public function updatePassword()
    {
        $userId = session()->get('id');
        
        $userRole = session()->get('role');
        
        $rules = [
            'new_password' => 'required|min_length[5]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        // Jika bukan owner, wajib isi password lama
        if ($userRole !== 'owner') {
            $rules['old_password'] = 'required';
        }

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $newPassword = $this->request->getPost('new_password');
        $user = $this->userModel->find($userId);

        // Verifikasi password lama jika bukan owner
        if ($userRole !== 'owner') {
            $oldPassword = $this->request->getPost('old_password');
            if (!password_verify((string)$oldPassword, $user['password'])) {
                return $this->fail(['old_password' => 'Password lama salah.']);
            }
        }

        // Simpan password baru (akan di-hash otomatis di UserModel)
        if ($this->userModel->update($userId, ['password' => $newPassword])) {
            return $this->respondUpdated([
                'status' => true,
                'message' => 'Password berhasil diubah!'
            ]);
        }

        return $this->fail('Gagal mengubah password.');
    }
}
