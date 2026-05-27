<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $settingModel = new \App\Models\SettingModel();
        
        // Auto-insert setting jika belum ada
        if (!$settingModel->where('setting_key', 'landing_bg_image')->first()) {
            $settingModel->insert([
                'setting_key' => 'landing_bg_image',
                'setting_value' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?q=80&w=2070&auto=format&fit=crop', // Gambar tema WiFi/Jaringan
                'setting_type' => 'text',
                'description' => 'URL Background Image Landing Page (Tema WiFi/Jaringan)'
            ]);
        }
        
        $settings = $settingModel->getSettingsArray();

        $packageModel = new \App\Models\PackageModel();
        // Hanya muncul 3 saja yaitu dengan id 1, 2, 3
        $packages = $packageModel->whereIn('id', [1, 2, 3])->findAll();
        
        return view('landing', [
            'settings' => $settings,
            'packages' => $packages
        ]);
    }

    public function page($slug)
    {
        $settingModel = new \App\Models\SettingModel();
        $settings = $settingModel->getSettingsArray();
        
        $viewMap = [
            'tanya-jawab'       => ['view' => 'pages/tanya_jawab', 'title' => 'Tanya Jawab (FAQ)'],
            'syarat-ketentuan'  => ['view' => 'pages/syarat_ketentuan', 'title' => 'Syarat & Ketentuan'],
            'kebijakan-privasi' => ['view' => 'pages/kebijakan_privasi', 'title' => 'Kebijakan Privasi'],
            'area-cakupan'      => ['view' => 'pages/area_cakupan', 'title' => 'Area Cakupan'],
            'pusat-bantuan'     => ['view' => 'pages/pusat_bantuan', 'title' => 'Pusat Bantuan'],
            'tentang-kami'      => ['view' => 'pages/tentang_kami', 'title' => 'Tentang Kami'],
            'bantuan'           => ['view' => 'pages/bantuan', 'title' => 'Bantuan'],
        ];

        if (!array_key_exists($slug, $viewMap)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        return view($viewMap[$slug]['view'], [
            'settings' => $settings,
            'title'    => $viewMap[$slug]['title']
        ]);
    }
}
