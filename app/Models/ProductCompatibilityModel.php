<?php
namespace App\Models;


use CodeIgniter\Model;


class ProductCompatibilityModel extends Model
{
protected $table = 'product_compatibility';
protected $primaryKey = 'id';
protected $allowedFields = ['product_id','car_model_id'];


public function getCompatibilityForProduct($productId)
{
return $this->select('product_compatibility.*, car_models.brand, car_models.model as car_model, car_models.year_start, car_models.year_end')
->join('car_models', 'car_models.id = product_compatibility.car_model_id')
->where('product_compatibility.product_id', $productId)
->findAll();
}


public function getProductsForCarModel($carModelId)
{
return $this->select('product_compatibility.*, products.product_name, products.ean13')
->join('products', 'products.id = product_compatibility.product_id')
->where('product_compatibility.car_model_id', $carModelId)
->findAll();
}
}