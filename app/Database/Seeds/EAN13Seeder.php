<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EAN13Seeder extends Seeder
{
    public function run()
    {
        // EAN-13 barcodes for automotive products (starting with 867 for automotive parts)
        $ean13Updates = [
            1  => '8670001234567', // Premium Oil Filter
            2  => '8670002345678', // Air Filter Element
            3  => '8670003456789', // Spark Plug Set
            4  => '8670004567890', // Engine Gasket Kit
            5  => '8670005678901', // Timing Belt
            6  => '8670006789012', // Ceramic Brake Pads
            7  => '8670007890123', // Brake Rotors Rear
            8  => '8670008901234', // Brake Caliper
            9  => '8670009012345', // DOT 3 Brake Fluid
            10 => '8670010123456', // Front Strut Assembly
            11 => '8670011234567', // Shock Absorber
            12 => '8670012345678', // Ball Joint
            13 => '8670013456789', // Tie Rod End
            14 => '8670014567890', // Control Arm
            15 => '8670015678901', // CV Joint
            16 => '8670016789012', // Wheel Bearing
            17 => '8670017890123', // Radiator
            18 => '8670018901234', // Water Pump
            19 => '8670019012345', // Thermostat
            20 => '8670020123456', // Cooling Fan
            21 => '8670021234567', // Alternator
            22 => '8670022345678', // Starter Motor
            23 => '8670023456789', // Battery
            24 => '8670024567890', // Ignition Coil
            25 => '8670025678901', // Fuel Pump
            26 => '8670026789012', // Fuel Filter
            27 => '8670027890123', // Oxygen Sensor
            28 => '8670028901234', // MAF Sensor
            29 => '8670029012345', // Catalytic Converter
            30 => '8670030123456', // Muffler
            31 => '8670031234567', // Exhaust Pipe
            32 => '8670032345678', // Headlight Assembly
            33 => '8670033456789', // Tail Light Assembly
            34 => '8670034567890', // Turn Signal Bulb
            35 => '8670035678901', // Brake Light Bulb
            36 => '8670036789012', // Side Mirror
            37 => '8670037890123', // Windshield Wiper Blade
            38 => '8670038901234', // Cabin Air Filter
            39 => '8670039012345', // Floor Mat Set
            40 => '8670040123456', // Seat Cover Set
            41 => '8670041234567', // Steering Wheel Cover
            42 => '8670042345678'  // Car Organizer
        ];

        foreach ($ean13Updates as $productId => $ean13) {
            $this->db->table('products')
                     ->where('id', $productId)
                     ->update(['ean13' => $ean13]);
        }

        echo "✓ Updated " . count($ean13Updates) . " products with EAN-13 barcodes\n";
    }
}
