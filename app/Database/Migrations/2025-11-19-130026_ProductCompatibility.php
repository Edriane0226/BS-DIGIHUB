<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProductCompatibility extends Migration
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
            'car_model_id' => [
                'type' => 'INT',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('car_model_id', 'car_models', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['product_id', 'car_model_id']);
        $this->forge->createTable('product_compatibility');
    }

    public function down()
    {
        $this->forge->dropTable('product_compatibility');
    }
}
