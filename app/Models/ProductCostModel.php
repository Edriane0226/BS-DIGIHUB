<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductCostModel extends Model
{
    protected $table      = 'product_costs';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['product_id', 'cost_per_unit', 'effective_date'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'product_id'    => 'required|integer',
        'cost_per_unit' => 'required|decimal',
        'effective_date'=> 'required|valid_date',
    ];

    /**
     * Get the latest cost for a product
     */
    public function getLatestCostForProduct($productId)
    {
        return $this->where('product_id', $productId)
                    ->orderBy('effective_date', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->first();
    }

    /**
     * Get cost for a product on a specific date
     */
    public function getCostForProductOnDate($productId, $date)
    {
        return $this->where('product_id', $productId)
                    ->where('effective_date <=', $date)
                    ->orderBy('effective_date', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->first();
    }

    /**
     * Create or update cost for a product
     */
    public function setCostForProduct($productId, $costPerUnit, $effectiveDate = null)
    {
        if ($effectiveDate === null) {
            $effectiveDate = date('Y-m-d');
        }

        // Check if there's already a cost for this product on this date
        $existingCost = $this->where('product_id', $productId)
                             ->where('effective_date', $effectiveDate)
                             ->first();

        if ($existingCost) {
            return $this->update($existingCost['id'], [
                'cost_per_unit' => $costPerUnit
            ]);
        } else {
            return $this->insert([
                'product_id' => $productId,
                'cost_per_unit' => $costPerUnit,
                'effective_date' => $effectiveDate
            ]);
        }
    }
}