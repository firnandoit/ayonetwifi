<?php

use App\Models\LogModel;

if (!function_exists('save_log')) {
    function save_log($description, $module, $action)
    {
        $logModel = new LogModel();
        $session = session();

        // Mengembalikan hasil insert
        return $logModel->insert([
            'user_id'     => $session->get('id'), // Mengambil ID dari session login
            'description' => $description,
            'module'      => $module,
            'action'      => $action,
            'ip_address'  => service('request')->getIPAddress(),
        ]);
    }
}