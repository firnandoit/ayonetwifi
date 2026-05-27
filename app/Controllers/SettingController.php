<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class SettingController extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    public function landing()
    {
        $data = [
            'title'    => 'CMS Landing Page',
            'settings' => $this->settingModel->findAll(),
        ];

        return view('pengaturan/landing', $data);
    }

    public function saveLanding()
    {
        $postData = $this->request->getPost();
        
        // Cek jika tidak ada data post
        if (empty($postData)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dikirim.');
        }

        foreach ($postData as $key => $value) {
            // Kita cari id dari setting_key
            $setting = $this->settingModel->where('setting_key', $key)->first();
            
            if ($setting) {
                $this->settingModel->update($setting['id'], [
                    'setting_value' => $value
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pengaturan Landing Page berhasil disimpan.');
    }
}
