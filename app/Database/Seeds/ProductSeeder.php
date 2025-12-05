<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Engine Parts (Category 1)
            [
                'product_name' => 'Premium Oil Filter',
                'category_id' => 1,
                'product_type' => 'filter',
                'ean13' => '1234567890123',
                'price' => 15.99,
                'cost_per_unit' => 8.50,
                'quantity' => 150,
                'shelf_location_id' => 1  // A1
            ],
            [
                'product_name' => 'Air Filter Element',
                'category_id' => 1,
                'product_type' => 'filter',
                'ean13' => '1234567890124',
                'price' => 22.50,
                'cost_per_unit' => 12.75,
                'quantity' => 120,
                'shelf_location_id' => 1  // A1
            ],
            [
                'product_name' => 'Spark Plug Set',
                'category_id' => 1,
                'product_type' => 'ignition',
                'ean13' => '1234567890125',
                'price' => 89.99,
                'cost_per_unit' => 52.00,
                'quantity' => 75,
                'shelf_location_id' => 1  // A1
            ],
            [
                'product_name' => 'Engine Gasket Kit',
                'category_id' => 1,
                'product_type' => 'gasket',
                'ean13' => '1234567890126',
                'price' => 125.00,
                'cost_per_unit' => 75.00,
                'quantity' => 45,
                'shelf_location_id' => 1  // A1
            ],
            [
                'product_name' => 'Timing Belt',
                'category_id' => 1,
                'product_type' => 'belt',
                'ean13' => '1234567890127',
                'price' => 68.75,
                'cost_per_unit' => 38.50,
                'quantity' => 85,
                'shelf_location_id' => 1  // A1
            ],

            // Brake System (Category 2)
            [
                'product_name' => 'Ceramic Brake Pads Front',
                'category_id' => 2,
                'product_type' => 'pads',
                'ean13' => '1234567890128',
                'price' => 55.99,
                'cost_per_unit' => 32.00,
                'quantity' => 100,
                'shelf_location_id' => 2  // A2
            ],
            [
                'product_name' => 'Brake Rotors Rear',
                'category_id' => 2,
                'product_type' => 'rotors',
                'ean13' => '1234567890129',
                'price' => 89.50,
                'cost_per_unit' => 55.00,
                'quantity' => 60,
                'shelf_location_id' => 2  // A2
            ],
            [
                'product_name' => 'Brake Caliper',
                'category_id' => 2,
                'product_type' => 'caliper',
                'ean13' => '1234567890130',
                'price' => 145.00,
                'cost_per_unit' => 89.00,
                'quantity' => 35,
                'shelf_location_id' => 2  // A2
            ],
            [
                'product_name' => 'DOT 3 Brake Fluid',
                'category_id' => 2,
                'product_type' => 'fluid',
                'ean13' => '1234567890131',
                'price' => 12.99,
                'cost_per_unit' => 7.50,
                'quantity' => 200,
                'shelf_location_id' => 2  // A2
            ],

            // Suspension (Category 3)
            [
                'product_name' => 'Front Strut Assembly',
                'category_id' => 3,
                'product_type' => 'strut',
                'ean13' => '1234567890132',
                'price' => 165.00,
                'cost_per_unit' => 98.00,
                'quantity' => 40,
                'shelf_location_id' => 3  // A3
            ],
            [
                'product_name' => 'Rear Shock Absorber',
                'category_id' => 3,
                'product_type' => 'shock',
                'ean13' => '1234567890133',
                'price' => 78.99,
                'cost_per_unit' => 45.00,
                'quantity' => 70,
                'shelf_location_id' => 3  // A3
            ],
            [
                'product_name' => 'Sway Bar Link',
                'category_id' => 3,
                'product_type' => 'link',
                'ean13' => '1234567890134',
                'price' => 25.50,
                'cost_per_unit' => 14.00,
                'quantity' => 120,
                'shelf_location_id' => 3  // A3
            ],
            [
                'product_name' => 'Coil Spring Set',
                'category_id' => 3,
                'product_type' => 'spring',
                'ean13' => '1234567890135',
                'price' => 195.00,
                'cost_per_unit' => 125.00,
                'quantity' => 25,
                'shelf_location_id' => 3  // A3
            ],

            // Transmission (Category 4)
            [
                'product_name' => 'Transmission Filter',
                'category_id' => 4,
                'product_type' => 'filter',
                'ean13' => '1234567890136',
                'price' => 32.99,
                'cost_per_unit' => 18.50,
                'quantity' => 90,
                'shelf_location_id' => 4  // B1
            ],
            [
                'product_name' => 'ATF Transmission Fluid',
                'category_id' => 4,
                'product_type' => 'fluid',
                'ean13' => '1234567890137',
                'price' => 18.75,
                'cost_per_unit' => 11.00,
                'quantity' => 180,
                'shelf_location_id' => 4  // B1
            ],
            [
                'product_name' => 'Clutch Kit',
                'category_id' => 4,
                'product_type' => 'clutch',
                'ean13' => '1234567890138',
                'price' => 285.00,
                'cost_per_unit' => 175.00,
                'quantity' => 20,
                'shelf_location_id' => 4  // B1
            ],

            // Electrical (Category 5)
            [
                'product_name' => 'Car Battery 12V',
                'category_id' => 5,
                'product_type' => 'battery',
                'ean13' => '1234567890139',
                'price' => 125.99,
                'cost_per_unit' => 78.00,
                'quantity' => 50,
                'shelf_location_id' => 5  // B2
            ],
            [
                'product_name' => 'Alternator',
                'category_id' => 5,
                'product_type' => 'alternator',
                'ean13' => '1234567890140',
                'price' => 220.00,
                'cost_per_unit' => 135.00,
                'quantity' => 30,
                'shelf_location_id' => 5  // B2
            ],
            [
                'product_name' => 'Starter Motor',
                'category_id' => 5,
                'product_type' => 'starter',
                'ean13' => '1234567890141',
                'price' => 185.50,
                'cost_per_unit' => 115.00,
                'quantity' => 35,
                'shelf_location_id' => 5  // B2
            ],
            [
                'product_name' => 'Ignition Coil',
                'category_id' => 5,
                'product_type' => 'coil',
                'ean13' => '1234567890142',
                'price' => 95.00,
                'cost_per_unit' => 58.00,
                'quantity' => 65,
                'shelf_location_id' => 5  // B2
            ],

            // Body Parts (Category 6)
            [
                'product_name' => 'Side Mirror Assembly',
                'category_id' => 6,
                'product_type' => 'mirror',
                'ean13' => '1234567890143',
                'price' => 85.99,
                'cost_per_unit' => 52.00,
                'quantity' => 45,
                'shelf_location_id' => 6  // B3
            ],
            [
                'product_name' => 'Door Handle',
                'category_id' => 6,
                'product_type' => 'handle',
                'ean13' => '1234567890144',
                'price' => 35.50,
                'cost_per_unit' => 21.00,
                'quantity' => 80,
                'shelf_location_id' => 6  // B3
            ],
            [
                'product_name' => 'Bumper Cover',
                'category_id' => 6,
                'product_type' => 'cover',
                'ean13' => '1234567890145',
                'price' => 165.00,
                'cost_per_unit' => 98.00,
                'quantity' => 25,
                'shelf_location_id' => 6  // B3
            ],

            // Cooling System (Category 7)
            [
                'product_name' => 'Radiator',
                'category_id' => 7,
                'product_type' => 'radiator',
                'ean13' => '1234567890146',
                'price' => 195.00,
                'cost_per_unit' => 125.00,
                'quantity' => 30,
                'shelf_location_id' => 7  // C1
            ],
            [
                'product_name' => 'Water Pump',
                'category_id' => 7,
                'product_type' => 'pump',
                'ean13' => '1234567890147',
                'price' => 89.99,
                'cost_per_unit' => 55.00,
                'quantity' => 55,
                'shelf_location_id' => 7  // C1
            ],
            [
                'product_name' => 'Thermostat',
                'category_id' => 7,
                'product_type' => 'thermostat',
                'ean13' => '1234567890148',
                'price' => 22.50,
                'cost_per_unit' => 12.50,
                'quantity' => 100,
                'shelf_location_id' => 7  // C1
            ],
            [
                'product_name' => 'Cooling Fan',
                'category_id' => 7,
                'product_type' => 'fan',
                'ean13' => '1234567890149',
                'price' => 125.00,
                'cost_per_unit' => 78.00,
                'quantity' => 40,
                'shelf_location_id' => 7  // C1
            ],

            // Exhaust System (Category 8)
            [
                'product_name' => 'Catalytic Converter',
                'category_id' => 8,
                'product_type' => 'converter',
                'ean13' => '1234567890150',
                'price' => 285.00,
                'cost_per_unit' => 185.00,
                'quantity' => 20,
                'shelf_location_id' => 8  // C2
            ],
            [
                'product_name' => 'Muffler',
                'category_id' => 8,
                'product_type' => 'muffler',
                'ean13' => '1234567890151',
                'price' => 65.99,
                'cost_per_unit' => 38.00,
                'quantity' => 50,
                'shelf_location_id' => 8  // C2
            ],
            [
                'product_name' => 'Exhaust Pipe',
                'category_id' => 8,
                'product_type' => 'pipe',
                'ean13' => '1234567890152',
                'price' => 45.50,
                'cost_per_unit' => 26.00,
                'quantity' => 70,
                'shelf_location_id' => 8  // C2
            ],

            // Fuel System (Category 9)
            [
                'product_name' => 'Fuel Pump',
                'category_id' => 9,
                'product_type' => 'pump',
                'ean13' => '1234567890153',
                'price' => 155.00,
                'cost_per_unit' => 95.00,
                'quantity' => 35,
                'shelf_location_id' => 9  // C3
            ],
            [
                'product_name' => 'Fuel Filter',
                'category_id' => 9,
                'product_type' => 'filter',
                'ean13' => '1234567890154',
                'price' => 28.99,
                'cost_per_unit' => 16.50,
                'quantity' => 90,
                'shelf_location_id' => 9  // C3
            ],
            [
                'product_name' => 'Fuel Injector',
                'category_id' => 9,
                'product_type' => 'injector',
                'ean13' => '1234567890155',
                'price' => 75.00,
                'cost_per_unit' => 45.00,
                'quantity' => 60,
                'shelf_location_id' => 9  // C3
            ],

            // Lighting (Category 10)
            [
                'product_name' => 'LED Headlight Bulb',
                'category_id' => 10,
                'product_type' => 'bulb',
                'ean13' => '1234567890156',
                'price' => 45.99,
                'cost_per_unit' => 26.00,
                'quantity' => 80,
                'shelf_location_id' => 10  // D1
            ],
            [
                'product_name' => 'Tail Light Assembly',
                'category_id' => 10,
                'product_type' => 'assembly',
                'ean13' => '1234567890157',
                'price' => 95.00,
                'cost_per_unit' => 58.00,
                'quantity' => 40,
                'shelf_location_id' => 10  // D1
            ],
            [
                'product_name' => 'Turn Signal Bulb',
                'category_id' => 10,
                'product_type' => 'bulb',
                'ean13' => '1234567890158',
                'price' => 8.99,
                'cost_per_unit' => 4.50,
                'quantity' => 150,
                'shelf_location_id' => 10  // D1
            ],

            // Tires & Wheels (Category 11)
            [
                'product_name' => 'All-Season Tire',
                'category_id' => 11,
                'product_type' => 'tire',
                'ean13' => '1234567890159',
                'price' => 135.00,
                'cost_per_unit' => 85.00,
                'quantity' => 60,
                'shelf_location_id' => 11  // D2
            ],
            [
                'product_name' => 'Alloy Wheel',
                'category_id' => 11,
                'product_type' => 'wheel',
                'ean13' => '1234567890160',
                'price' => 185.00,
                'cost_per_unit' => 118.00,
                'quantity' => 32,
                'shelf_location_id' => 11  // D2
            ],
            [
                'product_name' => 'Wheel Hub Assembly',
                'category_id' => 11,
                'product_type' => 'hub',
                'ean13' => '1234567890161',
                'price' => 125.00,
                'cost_per_unit' => 75.00,
                'quantity' => 45,
                'shelf_location_id' => 11  // D2
            ],

            // Air System (Category 12)
            [
                'product_name' => 'Cabin Air Filter',
                'category_id' => 12,
                'product_type' => 'filter',
                'ean13' => '1234567890162',
                'price' => 18.99,
                'cost_per_unit' => 10.50,
                'quantity' => 110,
                'shelf_location_id' => 12  // D3
            ],
            [
                'product_name' => 'Air Intake Hose',
                'category_id' => 12,
                'product_type' => 'hose',
                'ean13' => '1234567890163',
                'price' => 35.50,
                'cost_per_unit' => 20.00,
                'quantity' => 85,
                'shelf_location_id' => 12  // D3
            ],
            [
                'product_name' => 'Mass Air Flow Sensor',
                'category_id' => 12,
                'product_type' => 'sensor',
                'ean13' => '1234567890164',
                'price' => 165.00,
                'cost_per_unit' => 105.00,
                'quantity' => 30,
                'shelf_location_id' => 12  // D3
            ]
        ];

        // Simple insert
        $this->db->table('products')->insertBatch($data);
    }
}
