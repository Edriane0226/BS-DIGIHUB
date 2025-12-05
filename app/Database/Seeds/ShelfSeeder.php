<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ShelfSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'shelf_id'    => 'SHELF001',
                'loc_descrip' => 'First Floor - Aisle 1',
            ],
            [
                'shelf_id'    => 'SHELF002',
                'loc_descrip' => 'First Floor - Aisle 2',
            ],
            [
                'shelf_id'    => 'SHELF003',
                'loc_descrip' => 'Second Floor - Aisle 1',
            ],
        ];
    }
}
