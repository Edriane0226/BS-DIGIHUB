<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CarModelSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Toyota Models
            [
                'brand' => 'Toyota',
                'model' => 'Camry',
                'year_start' => 2018,
                'year_end' => 2024
            ],
            [
                'brand' => 'Toyota',
                'model' => 'Corolla',
                'year_start' => 2019,
                'year_end' => 2024
            ],
            [
                'brand' => 'Toyota',
                'model' => 'RAV4',
                'year_start' => 2019,
                'year_end' => 2024
            ],
            [
                'brand' => 'Toyota',
                'model' => 'Highlander',
                'year_start' => 2020,
                'year_end' => 2024
            ],
            [
                'brand' => 'Toyota',
                'model' => 'Prius',
                'year_start' => 2016,
                'year_end' => 2024
            ],
            
            // Honda Models
            [
                'brand' => 'Honda',
                'model' => 'Civic',
                'year_start' => 2016,
                'year_end' => 2024
            ],
            [
                'brand' => 'Honda',
                'model' => 'Accord',
                'year_start' => 2018,
                'year_end' => 2024
            ],
            [
                'brand' => 'Honda',
                'model' => 'CR-V',
                'year_start' => 2017,
                'year_end' => 2024
            ],
            [
                'brand' => 'Honda',
                'model' => 'Pilot',
                'year_start' => 2016,
                'year_end' => 2024
            ],
            [
                'brand' => 'Honda',
                'model' => 'HR-V',
                'year_start' => 2016,
                'year_end' => 2024
            ],
            
            // Nissan Models
            [
                'brand' => 'Nissan',
                'model' => 'Altima',
                'year_start' => 2019,
                'year_end' => 2024
            ],
            [
                'brand' => 'Nissan',
                'model' => 'Sentra',
                'year_start' => 2020,
                'year_end' => 2024
            ],
            [
                'brand' => 'Nissan',
                'model' => 'Rogue',
                'year_start' => 2021,
                'year_end' => 2024
            ],
            [
                'brand' => 'Nissan',
                'model' => 'Pathfinder',
                'year_start' => 2022,
                'year_end' => 2024
            ],
            [
                'brand' => 'Nissan',
                'model' => 'Murano',
                'year_start' => 2015,
                'year_end' => 2024
            ],
            
            // Ford Models
            [
                'brand' => 'Ford',
                'model' => 'F-150',
                'year_start' => 2015,
                'year_end' => 2024
            ],
            [
                'brand' => 'Ford',
                'model' => 'Escape',
                'year_start' => 2020,
                'year_end' => 2024
            ],
            [
                'brand' => 'Ford',
                'model' => 'Explorer',
                'year_start' => 2020,
                'year_end' => 2024
            ],
            [
                'brand' => 'Ford',
                'model' => 'Mustang',
                'year_start' => 2015,
                'year_end' => 2024
            ],
            [
                'brand' => 'Ford',
                'model' => 'Fusion',
                'year_start' => 2013,
                'year_end' => 2020
            ],
            
            // Chevrolet Models
            [
                'brand' => 'Chevrolet',
                'model' => 'Silverado',
                'year_start' => 2019,
                'year_end' => 2024
            ],
            [
                'brand' => 'Chevrolet',
                'model' => 'Equinox',
                'year_start' => 2018,
                'year_end' => 2024
            ],
            [
                'brand' => 'Chevrolet',
                'model' => 'Malibu',
                'year_start' => 2016,
                'year_end' => 2024
            ],
            [
                'brand' => 'Chevrolet',
                'model' => 'Tahoe',
                'year_start' => 2021,
                'year_end' => 2024
            ],
            [
                'brand' => 'Chevrolet',
                'model' => 'Cruze',
                'year_start' => 2016,
                'year_end' => 2019
            ],
            
            // BMW Models
            [
                'brand' => 'BMW',
                'model' => '3 Series',
                'year_start' => 2019,
                'year_end' => 2024
            ],
            [
                'brand' => 'BMW',
                'model' => 'X3',
                'year_start' => 2018,
                'year_end' => 2024
            ],
            [
                'brand' => 'BMW',
                'model' => 'X5',
                'year_start' => 2019,
                'year_end' => 2024
            ],
            [
                'brand' => 'BMW',
                'model' => '5 Series',
                'year_start' => 2017,
                'year_end' => 2024
            ],
            
            // Mercedes-Benz Models
            [
                'brand' => 'Mercedes-Benz',
                'model' => 'C-Class',
                'year_start' => 2015,
                'year_end' => 2024
            ],
            [
                'brand' => 'Mercedes-Benz',
                'model' => 'E-Class',
                'year_start' => 2017,
                'year_end' => 2024
            ],
            [
                'brand' => 'Mercedes-Benz',
                'model' => 'GLE',
                'year_start' => 2020,
                'year_end' => 2024
            ],
            [
                'brand' => 'Mercedes-Benz',
                'model' => 'GLC',
                'year_start' => 2016,
                'year_end' => 2024
            ],
            
            // Audi Models
            [
                'brand' => 'Audi',
                'model' => 'A4',
                'year_start' => 2017,
                'year_end' => 2024
            ],
            [
                'brand' => 'Audi',
                'model' => 'Q5',
                'year_start' => 2018,
                'year_end' => 2024
            ],
            [
                'brand' => 'Audi',
                'model' => 'A6',
                'year_start' => 2019,
                'year_end' => 2024
            ],
            
            // Hyundai Models
            [
                'brand' => 'Hyundai',
                'model' => 'Elantra',
                'year_start' => 2021,
                'year_end' => 2024
            ],
            [
                'brand' => 'Hyundai',
                'model' => 'Tucson',
                'year_start' => 2022,
                'year_end' => 2024
            ],
            [
                'brand' => 'Hyundai',
                'model' => 'Santa Fe',
                'year_start' => 2019,
                'year_end' => 2024
            ],
            
            // Kia Models
            [
                'brand' => 'Kia',
                'model' => 'Forte',
                'year_start' => 2019,
                'year_end' => 2024
            ],
            [
                'brand' => 'Kia',
                'model' => 'Sportage',
                'year_start' => 2023,
                'year_end' => 2024
            ],
            [
                'brand' => 'Kia',
                'model' => 'Sorento',
                'year_start' => 2021,
                'year_end' => 2024
            ]
        ];

        // Simple insert
        $this->db->table('car_models')->insertBatch($data);
    }
}