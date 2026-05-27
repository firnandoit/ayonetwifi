<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDiscountToBills extends Migration
{
    public function up()
    {
        $this->forge->addColumn('bills', [
            'discount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
                'null'       => true,
                'after'      => 'paid_amount', // Add it after paid_amount
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('bills', 'discount');
    }
}
