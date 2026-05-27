<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Seed Users
        $db->table('users')->insertBatch([
            [
                'name'       => 'Owner Ayonet',
                'username'   => 'owner',
                'password'   => password_hash('owner123', PASSWORD_DEFAULT),
                'role'       => 'owner',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Admin Kasir',
                'username'   => 'admin',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);

        // Seed Packages
        $db->table('packages')->insertBatch([
            [
                'name'        => 'Paket Hemat',
                'speed'       => '10 Mbps',
                'price'       => 150000,
                'description' => 'Paket hemat untuk keluarga kecil.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Paket Standar',
                'speed'       => '20 Mbps',
                'price'       => 200000,
                'description' => 'Paket standar untuk rumah tangga.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
