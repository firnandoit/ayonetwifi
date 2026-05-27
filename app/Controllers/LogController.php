<?php

namespace App\Controllers;

use App\Models\LogModel;

class LogController extends BaseController
{
    public function index()
    {
        // Proteksi: Hanya Owner yang boleh masuk
        if (session()->get('role') !== 'owner') {
            return redirect()->to('/dashboard')->with('error', 'Hanya Owner yang diizinkan!');
        }

        $logModel = new LogModel();
        $data = [
            'title' => 'Log Aktivitas Sistem',
            'logs'  => $logModel->getLogs()
        ];

        return view('logs/index', $data);
    }
}