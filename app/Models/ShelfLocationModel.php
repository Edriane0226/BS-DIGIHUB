<?php

namespace App\Models;

use CodeIgniter\Model;

class ShelfLocationModel extends Model
{
    protected $table      = 'shelf_locations';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['shelf_id', 'loc_descrip'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'shelf_id'     => 'required|max_length[50]|is_unique[shelf_locations.shelf_id,id,{id}]',
        'loc_descrip'  => 'required|max_length[255]',
    ];
    protected $validationMessages   = [
        'shelf_id' => [
            'required'   => 'Shelf ID is required.',
            'is_unique'  => 'This Shelf ID already exists.',
            'max_length' => 'Shelf ID cannot exceed 50 characters.'
        ],
        'loc_descrip' => [
            'required'   => 'Location description is required.',
            'max_length' => 'Location description cannot exceed 255 characters.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get all shelf locations formatted for dropdown
     */
    public function getForDropdown()
    {
        $shelves = $this->orderBy('shelf_id', 'ASC')->findAll();
        $options = [];
        
        foreach ($shelves as $shelf) {
            $options[$shelf['id']] = $shelf['shelf_id'] . ' - ' . $shelf['loc_descrip'];
        }
        
        return $options;
    }

    /**
     * Get shelf location with product count
     */
    public function getWithProductCount()
    {
        return $this->select('shelf_locations.*, COUNT(products.id) as product_count')
                    ->join('products', 'products.shelf_location_id = shelf_locations.id', 'left')
                    ->groupBy('shelf_locations.id')
                    ->orderBy('shelf_locations.shelf_id', 'ASC')
                    ->findAll();
    }
}