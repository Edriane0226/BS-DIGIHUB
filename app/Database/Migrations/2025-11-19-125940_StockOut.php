<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StockOut extends Migration
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
            'date_out' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'reason' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('product_id', 'products', 'id', '', 'CASCADE');
        $this->forge->createTable('stock_out');
    }

    public function down()
    {
        $this->forge->dropTable('stock_out');
    }
}
