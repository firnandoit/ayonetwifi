<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'setting_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'setting_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'setting_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'text', // text, textarea
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('settings');

        // Seed initial data
        $data = [
            [
                'setting_key'   => 'landing_hero_title',
                'setting_value' => 'Build An Outstanding Solutions with The Best Theme Ever',
                'setting_type'  => 'text',
                'description'   => 'Judul utama di bagian atas landing page',
            ],
            [
                'setting_key'   => 'landing_hero_subtitle',
                'setting_value' => 'Save thousands to millions of bucks by using single tool for different amazing and great useful admin',
                'setting_type'  => 'textarea',
                'description'   => 'Teks deskripsi di bawah judul utama',
            ],
            [
                'setting_key'   => 'landing_about_title',
                'setting_value' => 'How it Works',
                'setting_type'  => 'text',
                'description'   => 'Judul bagian tentang/how it works',
            ],
            [
                'setting_key'   => 'landing_about_subtitle',
                'setting_value' => 'Save thousands to millions of bucks by using single tool for different amazing and great useful admin',
                'setting_type'  => 'textarea',
                'description'   => 'Deskripsi bagian tentang',
            ]
        ];
        
        $this->db->table('settings')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
