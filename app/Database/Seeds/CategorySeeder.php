<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['category_name' => 'Engine Parts'],
            ['category_name' => 'Brake System'],
            ['category_name' => 'Suspension'],
            ['category_name' => 'Transmission'],
            ['category_name' => 'Electrical'],
            ['category_name' => 'Body Parts'],
            ['category_name' => 'Cooling System'],
            ['category_name' => 'Exhaust System'],
            ['category_name' => 'Fuel System'],
            ['category_name' => 'Lighting'],
            ['category_name' => 'Tires & Wheels'],
            ['category_name' => 'Air System'],
            ['category_name' => 'Shabu'] // Add the category that was mentioned in the error
        ];

        // Check if categories already exist to avoid duplicates
        $existingCategories = $this->db->table('categories')->select('category_name')->get()->getResultArray();
        $existingNames = array_column($existingCategories, 'category_name');
        
        $dataToInsert = [];
        foreach ($data as $category) {
            if (!in_array($category['category_name'], $existingNames)) {
                $dataToInsert[] = $category;
            }
        }
        
        if (!empty($dataToInsert)) {
            $this->db->table('categories')->insertBatch($dataToInsert);
        }
    }
}