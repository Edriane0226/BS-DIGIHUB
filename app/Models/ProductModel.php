<?php
namespace App\Models;


use CodeIgniter\Model;


class ProductModel extends Model
{
protected $table = 'products';
protected $primaryKey = 'id';
protected $allowedFields = ['product_name','category_id','shelf_location_id','product_type','ean13','price','quantity'];


public function withCategory()
{
return $this->select('products.*, categories.category_name')->join('categories', 'categories.id = products.category_id', 'left');
}

public function withCategoryAndShelf()
{
return $this->select('products.*, categories.category_name, shelf_locations.shelf_id, shelf_locations.loc_descrip')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->join('shelf_locations', 'shelf_locations.id = products.shelf_location_id', 'left');
}
}