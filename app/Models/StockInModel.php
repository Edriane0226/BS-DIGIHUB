<?php
namespace App\Models;

use CodeIgniter\Model;

class StockInModel extends Model
{
    protected $table = 'stock_in';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_id', 'quantity', 'product_cost_id', 'date_received', 'remarks'];
    
    public function getStockInWithProduct($limit = null)
    {
        $builder = $this->select('stock_in.*, products.product_name, products.ean13, pc.cost_per_unit')
                        ->join('products', 'products.id = stock_in.product_id')
                        ->join('product_costs pc', 'pc.id = stock_in.product_cost_id', 'left')
                        ->orderBy('stock_in.date_received', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }
    
    public function getTotalCostByProduct($productId, $startDate = null, $endDate = null)
    {
        $builder = $this->select('SUM(stock_in.quantity * pc.cost_per_unit) as total_cost')
                        ->join('product_costs pc', 'pc.id = stock_in.product_cost_id', 'left')
                        ->where('stock_in.product_id', $productId);
        
        if ($startDate) {
            $builder->where('date_received >=', $startDate);
        }
        
        if ($endDate) {
            $builder->where('date_received <=', $endDate);
        }
        
        $result = $builder->first();
        return $result['total_cost'] ?? 0;
    }
    
    /**
     * Get stock in records with cost information
     */
    public function getStockInWithCost($productId = null)
    {
        $builder = $this->select('stock_in.*, products.product_name, pc.cost_per_unit, pc.effective_date')
                        ->join('products', 'products.id = stock_in.product_id')
                        ->join('product_costs pc', 'pc.id = stock_in.product_cost_id', 'left')
                        ->orderBy('stock_in.date_received', 'DESC');
        
        if ($productId) {
            $builder->where('stock_in.product_id', $productId);
        }
        
        return $builder->findAll();
    }
}