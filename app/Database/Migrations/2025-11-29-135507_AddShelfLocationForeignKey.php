<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddShelfLocationForeignKey extends Migration
{
    public function up()
    {
        // Add foreign key constraint for shelf_location_id in products table
        $this->db->query('ALTER TABLE products ADD CONSTRAINT products_shelf_location_id_foreign FOREIGN KEY (shelf_location_id) REFERENCES shelf_locations(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        // Drop the foreign key constraint
        $this->db->query('ALTER TABLE products DROP FOREIGN KEY products_shelf_location_id_foreign');
    }
}