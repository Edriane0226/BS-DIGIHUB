<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProductKeys extends Migration
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
            'activation_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'is_used' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null'    => false,
            ],
            'date_used' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('activation_key');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('digital_product_keys');
    }

    public function down()
    {
        $this->forge->dropTable('digital_product_keys');
    }
}
