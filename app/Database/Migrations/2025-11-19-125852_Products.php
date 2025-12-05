<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Products extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'product_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'null'       => false,
            ],
            'category_id' => [
                'type' => 'INT',
                'null' => true,
            ],
            'shelf_location_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'product_type' => [
                'type'       => 'ENUM',
                'constraint' => ['digital', 'physical'],
                'null'       => false,
            ],
            'ean13' => [
                'type'       => 'VARCHAR',
                'constraint' => '13',
                'null'       => true,
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
            'quantity' => [
                'type'    => 'INT',
                'null'    => false,
                'default' => 0,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}
