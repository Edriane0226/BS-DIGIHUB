<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CompatibilitySeeder extends Seeder
{
    public function run()
    {
        // Create realistic compatibility relationships
        $data = [
            // Oil Filter compatible with multiple Toyota models
            ['product_id' => 1, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 1, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 1, 'car_model_id' => 3], // Toyota RAV4
            ['product_id' => 1, 'car_model_id' => 4], // Toyota Highlander
            
            // Air Filter compatible with Honda models
            ['product_id' => 2, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 2, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 2, 'car_model_id' => 8], // Honda CR-V
            
            // Spark Plug Set for various models
            ['product_id' => 3, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 3, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 3, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 3, 'car_model_id' => 23], // Chevrolet Malibu
            
            // Engine Gasket Kit for specific models
            ['product_id' => 4, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 4, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 4, 'car_model_id' => 25], // Chevrolet Cruze
            
            // Timing Belt compatibility
            ['product_id' => 5, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 5, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 5, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 5, 'car_model_id' => 8], // Honda CR-V
            
            // Brake Pads Front - multiple compatibility
            ['product_id' => 6, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 6, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 6, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 6, 'car_model_id' => 23], // Chevrolet Malibu
            ['product_id' => 6, 'car_model_id' => 26], // BMW 3 Series
            
            // Brake Rotors Rear
            ['product_id' => 7, 'car_model_id' => 3], // Toyota RAV4
            ['product_id' => 7, 'car_model_id' => 8], // Honda CR-V
            ['product_id' => 7, 'car_model_id' => 13], // Nissan Rogue
            ['product_id' => 7, 'car_model_id' => 22], // Chevrolet Equinox
            
            // Brake Caliper
            ['product_id' => 8, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 8, 'car_model_id' => 12], // Nissan Sentra
            ['product_id' => 8, 'car_model_id' => 25], // Chevrolet Cruze
            
            // DOT 3 Brake Fluid - Universal compatibility
            ['product_id' => 9, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 9, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 9, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 9, 'car_model_id' => 16], // Ford F-150
            ['product_id' => 9, 'car_model_id' => 21], // Chevrolet Silverado
            ['product_id' => 9, 'car_model_id' => 26], // BMW 3 Series
            ['product_id' => 9, 'car_model_id' => 30], // Mercedes-Benz C-Class
            
            // Front Strut Assembly
            ['product_id' => 10, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 10, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 10, 'car_model_id' => 12], // Nissan Sentra
            
            // Rear Shock Absorber
            ['product_id' => 11, 'car_model_id' => 3], // Toyota RAV4
            ['product_id' => 11, 'car_model_id' => 8], // Honda CR-V
            ['product_id' => 11, 'car_model_id' => 13], // Nissan Rogue
            ['product_id' => 11, 'car_model_id' => 17], // Ford Escape
            
            // Sway Bar Link - Common part
            ['product_id' => 12, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 12, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 12, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 12, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 12, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 12, 'car_model_id' => 23], // Chevrolet Malibu
            
            // Coil Spring Set
            ['product_id' => 13, 'car_model_id' => 4], // Toyota Highlander
            ['product_id' => 13, 'car_model_id' => 9], // Honda Pilot
            ['product_id' => 13, 'car_model_id' => 18], // Ford Explorer
            ['product_id' => 13, 'car_model_id' => 24], // Chevrolet Tahoe
            
            // Transmission Filter
            ['product_id' => 14, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 14, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 14, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 14, 'car_model_id' => 20], // Ford Fusion
            
            // ATF Transmission Fluid - Universal
            ['product_id' => 15, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 15, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 15, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 15, 'car_model_id' => 20], // Ford Fusion
            ['product_id' => 15, 'car_model_id' => 23], // Chevrolet Malibu
            ['product_id' => 15, 'car_model_id' => 26], // BMW 3 Series
            ['product_id' => 15, 'car_model_id' => 30], // Mercedes-Benz C-Class
            
            // Clutch Kit - Manual transmission cars
            ['product_id' => 16, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 16, 'car_model_id' => 19], // Ford Mustang
            ['product_id' => 16, 'car_model_id' => 26], // BMW 3 Series
            
            // Car Battery - Universal
            ['product_id' => 17, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 17, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 17, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 17, 'car_model_id' => 16], // Ford F-150
            ['product_id' => 17, 'car_model_id' => 21], // Chevrolet Silverado
            ['product_id' => 17, 'car_model_id' => 26], // BMW 3 Series
            ['product_id' => 17, 'car_model_id' => 30], // Mercedes-Benz C-Class
            ['product_id' => 17, 'car_model_id' => 34], // Audi A4
            
            // Alternator
            ['product_id' => 18, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 18, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 18, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 18, 'car_model_id' => 23], // Chevrolet Malibu
            
            // Starter Motor
            ['product_id' => 19, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 19, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 19, 'car_model_id' => 12], // Nissan Sentra
            ['product_id' => 19, 'car_model_id' => 25], // Chevrolet Cruze
            
            // Ignition Coil
            ['product_id' => 20, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 20, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 20, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 20, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 20, 'car_model_id' => 11], // Nissan Altima
            
            // Side Mirror Assembly
            ['product_id' => 21, 'car_model_id' => 3], // Toyota RAV4
            ['product_id' => 21, 'car_model_id' => 8], // Honda CR-V
            ['product_id' => 21, 'car_model_id' => 13], // Nissan Rogue
            ['product_id' => 21, 'car_model_id' => 17], // Ford Escape
            
            // Door Handle
            ['product_id' => 22, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 22, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 22, 'car_model_id' => 11], // Nissan Altima
            
            // Bumper Cover
            ['product_id' => 23, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 23, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 23, 'car_model_id' => 12], // Nissan Sentra
            
            // Radiator
            ['product_id' => 24, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 24, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 24, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 24, 'car_model_id' => 23], // Chevrolet Malibu
            
            // Water Pump
            ['product_id' => 25, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 25, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 25, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 25, 'car_model_id' => 7], // Honda Accord
            
            // Thermostat - Universal fit
            ['product_id' => 26, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 26, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 26, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 26, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 26, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 26, 'car_model_id' => 12], // Nissan Sentra
            ['product_id' => 26, 'car_model_id' => 23], // Chevrolet Malibu
            ['product_id' => 26, 'car_model_id' => 25], // Chevrolet Cruze
            
            // Cooling Fan
            ['product_id' => 27, 'car_model_id' => 3], // Toyota RAV4
            ['product_id' => 27, 'car_model_id' => 8], // Honda CR-V
            ['product_id' => 27, 'car_model_id' => 13], // Nissan Rogue
            ['product_id' => 27, 'car_model_id' => 22], // Chevrolet Equinox
            
            // Catalytic Converter
            ['product_id' => 28, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 28, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 28, 'car_model_id' => 11], // Nissan Altima
            
            // Muffler
            ['product_id' => 29, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 29, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 29, 'car_model_id' => 12], // Nissan Sentra
            ['product_id' => 29, 'car_model_id' => 25], // Chevrolet Cruze
            
            // Exhaust Pipe
            ['product_id' => 30, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 30, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 30, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 30, 'car_model_id' => 7], // Honda Accord
            
            // Fuel Pump
            ['product_id' => 31, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 31, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 31, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 31, 'car_model_id' => 23], // Chevrolet Malibu
            
            // Fuel Filter - Universal
            ['product_id' => 32, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 32, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 32, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 32, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 32, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 32, 'car_model_id' => 12], // Nissan Sentra
            ['product_id' => 32, 'car_model_id' => 23], // Chevrolet Malibu
            ['product_id' => 32, 'car_model_id' => 25], // Chevrolet Cruze
            
            // Fuel Injector
            ['product_id' => 33, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 33, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 33, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 33, 'car_model_id' => 26], // BMW 3 Series
            
            // LED Headlight Bulb - Universal
            ['product_id' => 34, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 34, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 34, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 34, 'car_model_id' => 16], // Ford F-150
            ['product_id' => 34, 'car_model_id' => 21], // Chevrolet Silverado
            ['product_id' => 34, 'car_model_id' => 26], // BMW 3 Series
            
            // Tail Light Assembly
            ['product_id' => 35, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 35, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 35, 'car_model_id' => 12], // Nissan Sentra
            
            // Turn Signal Bulb - Universal
            ['product_id' => 36, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 36, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 36, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 36, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 36, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 36, 'car_model_id' => 12], // Nissan Sentra
            ['product_id' => 36, 'car_model_id' => 16], // Ford F-150
            ['product_id' => 36, 'car_model_id' => 21], // Chevrolet Silverado
            ['product_id' => 36, 'car_model_id' => 23], // Chevrolet Malibu
            ['product_id' => 36, 'car_model_id' => 25], // Chevrolet Cruze
            
            // All-Season Tire
            ['product_id' => 37, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 37, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 37, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 37, 'car_model_id' => 23], // Chevrolet Malibu
            
            // Alloy Wheel
            ['product_id' => 38, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 38, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 38, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 38, 'car_model_id' => 23], // Chevrolet Malibu
            
            // Wheel Hub Assembly
            ['product_id' => 39, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 39, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 39, 'car_model_id' => 12], // Nissan Sentra
            ['product_id' => 39, 'car_model_id' => 25], // Chevrolet Cruze
            
            // Cabin Air Filter - Universal
            ['product_id' => 40, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 40, 'car_model_id' => 2], // Toyota Corolla
            ['product_id' => 40, 'car_model_id' => 3], // Toyota RAV4
            ['product_id' => 40, 'car_model_id' => 6], // Honda Civic
            ['product_id' => 40, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 40, 'car_model_id' => 8], // Honda CR-V
            ['product_id' => 40, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 40, 'car_model_id' => 12], // Nissan Sentra
            ['product_id' => 40, 'car_model_id' => 13], // Nissan Rogue
            ['product_id' => 40, 'car_model_id' => 17], // Ford Escape
            ['product_id' => 40, 'car_model_id' => 20], // Ford Fusion
            ['product_id' => 40, 'car_model_id' => 22], // Chevrolet Equinox
            ['product_id' => 40, 'car_model_id' => 23], // Chevrolet Malibu
            ['product_id' => 40, 'car_model_id' => 25], // Chevrolet Cruze
            
            // Air Intake Hose
            ['product_id' => 41, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 41, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 41, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 41, 'car_model_id' => 23], // Chevrolet Malibu
            
            // Mass Air Flow Sensor
            ['product_id' => 42, 'car_model_id' => 1], // Toyota Camry
            ['product_id' => 42, 'car_model_id' => 7], // Honda Accord
            ['product_id' => 42, 'car_model_id' => 11], // Nissan Altima
            ['product_id' => 42, 'car_model_id' => 26], // BMW 3 Series
            ['product_id' => 42, 'car_model_id' => 30]  // Mercedes-Benz C-Class
        ];

        // Simple insert
        $this->db->table('product_compatibility')->insertBatch($data);
    }
}