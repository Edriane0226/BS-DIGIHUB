<?php
namespace App\Models;

use CodeIgniter\Model;

class SalesModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_id', 'quantity', 'total_amount', 'date_sold'];
    protected $useTimestamps = false;
    
    public function getSalesWithProduct($limit = null)
    {
        $builder = $this->select('sales.*, products.product_name, products.ean13, products.price')
                        ->join('products', 'products.id = sales.product_id')
                        ->orderBy('sales.date_sold', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }
    
    public function getTotalSalesByProduct($productId, $startDate = null, $endDate = null)
    {
        $builder = $this->selectSum('total_amount', 'total_sales')
                        ->selectSum('quantity', 'total_quantity')
                        ->where('product_id', $productId);
        
        if ($startDate) {
            $builder->where('date_sold >=', $startDate);
        }
        
        if ($endDate) {
            $builder->where('date_sold <=', $endDate);
        }
        
        return $builder->first();
    }
    
    public function getTotalSales($startDate = null, $endDate = null)
    {
        $builder = $this->selectSum('total_amount', 'total_sales');
        
        if ($startDate) {
            $builder->where('date_sold >=', $startDate);
        }
        
        if ($endDate) {
            $builder->where('date_sold <=', $endDate);
        }
        
        $result = $builder->first();
        return $result['total_sales'] ?? 0;
    }
}