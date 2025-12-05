<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ShelfLocationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'shelf_id' => 'A1',
                'loc_descrip' => 'Aisle A - Shelf 1 (Engine Parts)',
            ],
            [
                'shelf_id' => 'A2',
                'loc_descrip' => 'Aisle A - Shelf 2 (Brake System)',
            ],
            [
                'shelf_id' => 'A3',
                'loc_descrip' => 'Aisle A - Shelf 3 (Suspension)',
            ],
            [
                'shelf_id' => 'B1',
                'loc_descrip' => 'Aisle B - Shelf 1 (Transmission)',
            ],
            [
                'shelf_id' => 'B2',
                'loc_descrip' => 'Aisle B - Shelf 2 (Electrical)',
            ],
            [
                'shelf_id' => 'B3',
                'loc_descrip' => 'Aisle B - Shelf 3 (Body Parts)',
            ],
            [
                'shelf_id' => 'C1',
                'loc_descrip' => 'Aisle C - Shelf 1 (Cooling System)',
            ],
            [
                'shelf_id' => 'C2',
                'loc_descrip' => 'Aisle C - Shelf 2 (Exhaust System)',
            ],
            [
                'shelf_id' => 'C3',
                'loc_descrip' => 'Aisle C - Shelf 3 (Fuel System)',
            ],
            [
                'shelf_id' => 'D1',
                'loc_descrip' => 'Aisle D - Shelf 1 (Lighting)',
            ],
            [
                'shelf_id' => 'D2',
                'loc_descrip' => 'Aisle D - Shelf 2 (Tires & Wheels)',
            ],
            [
                'shelf_id' => 'D3',
                'loc_descrip' => 'Aisle D - Shelf 3 (Air System)',
            ],
            [
                'shelf_id' => 'STOR-1',
                'loc_descrip' => 'Storage Room - High Value Items',
            ],
            [
                'shelf_id' => 'STOR-2',
                'loc_descrip' => 'Storage Room - Bulk Items',
            ],
            [
                'shelf_id' => 'CTR-1',
                'loc_descrip' => 'Counter Display - Fast Moving Items',
            ],
        ];

        $this->db->table('shelf_locations')->insertBatch($data);
    }
}
