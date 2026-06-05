<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminIdToBills extends Migration
{
    public function up()
    {
        $fields = [
            'admin_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'customer_id' // Menempatkan kolom admin_id setelah customer_id
            ],
        ];
        $this->forge->addColumn('bills', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('bills', 'admin_id');
    }
}
