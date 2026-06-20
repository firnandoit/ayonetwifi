<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;

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
        // Ambil keyword pencarian dari DataTables
        $search = $this->request->getVar('search')['value'] ?? null;

        // Panggil model
        $data = $this->customerModel->getDatatables($search);

        return $this->response->setJSON(['data' => $data]);
    }

    public function show($id = null)
    {
        if ($id === null) {
            return $this->fail('ID Pelanggan tidak valid');
        }

        $pelanggan = $this->customerModel->find($id);

        if ($pelanggan) {
            return $this->response->setJSON($pelanggan);
        }

        return $this->failNotFound('Data pelanggan tidak ditemukan');
    }

    public function save()
    {
        helper('log');
        $id = $this->request->getPost('id');
        $rules = [
            'name'        => 'required|min_length[3]',
            'phone'       => 'required',
            'address'     => 'required',
            'package_id'  => 'required',
            'status'      => 'required',
            'ip_address'  => 'required|valid_ip'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $payload = [
            'name'              => $this->request->getPost('name'),
            'phone'             => $this->request->getPost('phone'),
            'address'           => $this->request->getPost('address'),
            'package_id'        => $this->request->getPost('package_id'),
            'user_id'           => $this->request->getPost('user_id') ?: null,
            'installation_date' => $this->request->getPost('installation_date'),
            'status'            => $this->request->getPost('status'),
            'ip_address'        => $this->request->getPost('ip_address'),
        ];

        // Variabel penanda status sukses
        $isSuccess = false;

        if (!empty($id)) {
            // 1. Jalankan Update ke Database
            if ($this->customerModel->update($id, $payload)) {
                save_log("Update data pelanggan: " . $payload['name'] . " (Status: " . $payload['status'] . ")", "Pelanggan", "Update");
                $message = 'Data pelanggan berhasil diperbarui';
                $isSuccess = true;
            }
        } else {
            // 2. Jalankan Insert ke Database untuk pelanggan baru
            if ($this->customerModel->insert($payload)) {
                $id = $this->customerModel->getInsertID(); // Ambil ID yang baru saja dibuat
                save_log("Menambah pelanggan baru: " . $payload['name'], "Pelanggan", "Create");
                $message = 'Pelanggan baru berhasil ditambahkan';
                $isSuccess = true;
            }
        }

        // =============================================================
        // LOGIKA MIKROTIK REST API NATIVE (KHUSUS ROUTEROS V7)
        // =============================================================
        if ($isSuccess && !empty($payload['ip_address'])) {

            // Variabel IP Target Manajemen Mikrotik (Silakan ganti jika di lapangan/asli)
            $mikrotikIP       = '192.168.0.115';
            $mikrotikUser     = 'admin';
            $mikrotikPassword = 'admin123';

            // Menggunakan HTTP Client bawaan CodeIgniter 4 (bukan curl manual)
            $client = \Config\Services::curlrequest();
            $customComment = 'ISOLIR_' . $id . '_' . str_replace(' ', '_', $payload['name']);

            try {
                // JIKA STATUS DIUBAH KE ISOLIR (DROP INTERNET)
                if ($payload['status'] == 'isolir') {

                    // 1. Cek rule via HTTP GET
                    $urlGet = "http://{$mikrotikIP}:81/rest/ip/firewall/filter?comment=" . urlencode($customComment);
                    $responseGet = $client->request('GET', $urlGet, [
                        'auth'        => [$mikrotikUser, $mikrotikPassword],
                        'http_errors' => false
                    ]);

                    $checkExist = json_decode($responseGet->getBody(), true);

                    // 2. Gunakan PUT untuk ADD data baru
                    if (empty($checkExist)) {
                        $urlPut = "http://{$mikrotikIP}:81/rest/ip/firewall/filter";

                        $client->request('PUT', $urlPut, [ // UBAH POST MENJADI PUT
                            'auth' => [$mikrotikUser, $mikrotikPassword],
                            'json' => [ // Gunakan 'json' agar header otomatis benar
                                'chain'       => 'forward',
                                'src-address' => $payload['ip_address'],
                                'action'      => 'drop',
                                'comment'     => $customComment

                            ],
                            'http_errors' => false
                        ]);
                    }
                } else {
                    // JIKA STATUS AKTIF (HAPUS RULE)
                    $urlGet = "http://{$mikrotikIP}:81/rest/ip/firewall/filter?comment=" . urlencode($customComment) . "&.proplist=.id";
                    $responseGet = $client->request('GET', $urlGet, [
                        'auth'        => [$mikrotikUser, $mikrotikPassword],
                        'http_errors' => false
                    ]);

                    $findRule = json_decode($responseGet->getBody(), true);

                    if (!empty($findRule) && is_array($findRule)) {
                        foreach ($findRule as $rule) {
                            if (isset($rule['.id'])) {
                                $targetID = $rule['.id'];
                                // Pastikan ID seperti *1A tidak rusak karena double encoding
                                $urlDelete = "http://{$mikrotikIP}:81/rest/ip/firewall/filter/" . $targetID;

                                $client->request('DELETE', $urlDelete, [
                                    'auth'        => [$mikrotikUser, $mikrotikPassword],
                                    'http_errors' => false
                                ]);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'REST API Mikrotik Bermasalah: ' . $e->getMessage());
                // Jika MikroTik gagal, kita tetap kirim response sukses DB tapi ada info error
                return $this->respondCreated([
                    'status'  => true,
                    'message' => $message . ' (Sinkronisasi MikroTik Gagal: Hubungi Admin)'
                ]);
            }
        }

        // WAJIB ADA RETURN DI AKHIR AGAR FRONTEND MENERIMA RESPONSE SAAT SUKSES
        return $this->respondCreated([
            'status'  => true,
            'message' => $message
        ]);
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

    // ========================================================
    // COPAS BAGIAN BARU INI DI BAGIAN BAWAH CONTROLLER ANDA
    // ========================================================

    /**
     * API Publik untuk Dify AI Agent
     * Mencari status pelanggan berdasarkan nomor HP untuk chatbot landing page
     */
    public function cekByHp()
    {
        // 1. Ambil query string 'no_hp' yang dikirim dari node Permintaan HTTP Dify
        $noHp = $this->request->getGet('no_hp');

        if (!$noHp) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Nomor HP tidak boleh kosong.'
            ], 400);
        }

        // 2. Cari di database menggunakan properti customerModel bawaan controller ini
        $pelanggan = $this->customerModel->where('phone', $noHp)->first();

        // 3. Jika nomor HP terdaftar, kembalikan data dalam struktur JSON teratur
        if ($pelanggan) {
            return $this->respond([
                'status' => 'success',
                'data'   => [
                    'nama'   => $pelanggan['name'],
                    'alamat' => $pelanggan['address'],
                    'status' => $pelanggan['status'],  // Hasilnya dinamis: aktif/isolir
                ]
            ], 200);
        }

        // 4. Jika nomor HP tidak terdaftar di DB
        return $this->respond([
            'status'  => 'not_found',
            'message' => 'Nomor HP tidak terdaftar di sistem AYONET.'
        ], 404);
    }
}
