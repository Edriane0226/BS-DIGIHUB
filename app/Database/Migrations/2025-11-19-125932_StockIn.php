<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StockIn extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'product_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'quantity' => [
                'type' => 'INT',
                'null' => false,
            ],
            'product_cost_id' => [
                'type' => 'INT',
                'null' => true,
            ],
            'date_received' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('product_id', 'products', 'id', '', 'CASCADE');
        $this->forge->createTable('stock_in');
    }

    public function down()
    {
        $this->forge->dropTable('stock_in');
    }
}
