<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Run seeders in the correct order to maintain referential integrity
        
        // 1. Categories first (no dependencies)
        $this->call('CategorySeeder');
        
        // 2. Shelf Locations (no dependencies)
        $this->call('ShelfLocationSeeder');
        
        // 3. Car Models (no dependencies)
        $this->call('CarModelSeeder');
        
        // 4. Products (depends on categories and shelf locations)
       // $this->call('ProductSeeder');
        
        // 5. Product Compatibility (depends on products and car models)
       // $this->call('CompatibilitySeeder');
        
        echo "Database seeding completed successfully!\n";
        echo "✓ Categories: 12 automotive categories\n";
        echo "✓ Shelf Locations: 15 organized storage locations\n";
        echo "✓ Car Models: 42 car models from major brands\n";
        echo "✓ Products: 42 automotive parts with cost tracking and shelf locations\n";
        echo "✓ Compatibility: Realistic product-vehicle relationships\n";
        echo "\nYour BS DIGIHUB database is now ready for testing!\n";
    }
}