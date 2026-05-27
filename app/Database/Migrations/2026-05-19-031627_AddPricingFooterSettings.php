<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPricingFooterSettings extends Migration
{
    public function up()
    {
        $data = [
            [
                'setting_key'   => 'landing_pricing_title',
                'setting_value' => 'Clear Pricing Makes it Easy',
                'setting_type'  => 'text',
                'description'   => 'Judul di bagian Pricing (Harga)',
            ],
            [
                'setting_key'   => 'landing_pricing_subtitle',
                'setting_value' => 'Save thousands to millions of bucks by using single tool for different amazing and outstanding cool and great useful admin',
                'setting_type'  => 'textarea',
                'description'   => 'Deskripsi di bawah judul Pricing',
            ],
            [
                'setting_key'   => 'landing_footer_contact_title',
                'setting_value' => 'Would you need a Custom License?',
                'setting_type'  => 'text',
                'description'   => 'Judul blok kontak 1 (Footer)',
            ],
            [
                'setting_key'   => 'landing_footer_contact_text',
                'setting_value' => 'Email us to support@keenthemes.com',
                'setting_type'  => 'text',
                'description'   => 'Teks/Link blok kontak 1 (Footer)',
            ],
            [
                'setting_key'   => 'landing_footer_project_title',
                'setting_value' => 'How About a Custom Project?',
                'setting_type'  => 'text',
                'description'   => 'Judul blok proyek 2 (Footer)',
            ],
            [
                'setting_key'   => 'landing_footer_project_text',
                'setting_value' => 'Use Our Custom Development Service. Click to Get a Quote',
                'setting_type'  => 'text',
                'description'   => 'Teks/Link blok proyek 2 (Footer)',
            ],
            [
                'setting_key'   => 'landing_footer_copyright',
                'setting_value' => '&copy; 2024 Keenthemes Inc.',
                'setting_type'  => 'text',
                'description'   => 'Teks Copyright di bagian paling bawah',
            ],
        ];

        $this->db->table('settings')->insertBatch($data);
    }

    public function down()
    {
        $keys = [
            'landing_pricing_title',
            'landing_pricing_subtitle',
            'landing_footer_contact_title',
            'landing_footer_contact_text',
            'landing_footer_project_title',
            'landing_footer_project_text',
            'landing_footer_copyright'
        ];
        $this->db->table('settings')->whereIn('setting_key', $keys)->delete();
    }
}
