<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductCostsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'cost_per_unit' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'effective_date' => [
                'type' => 'DATE',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('product_costs');
    }

    public function down()
    {
        $this->forge->dropTable('product_costs');
    }
}
