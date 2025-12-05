<?php
namespace App\Models;

use CodeIgniter\Model;

class StockOutModel extends Model
{
    protected $table = 'stock_out';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_id', 'quantity', 'date_out', 'reason'];
    
    public function getStockOutWithProduct($limit = null)
    {
        $builder = $this->select('stock_out.*, products.product_name, products.ean13')
                        ->join('products', 'products.id = stock_out.product_id')
                        ->orderBy('stock_out.date_out', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }
}