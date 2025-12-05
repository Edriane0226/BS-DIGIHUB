<?php
// Simple test file to check barcode lookup
require_once 'vendor/autoload.php';

$app = require_once FCPATH . '../app/Config/Paths.php';
$app = \CodeIgniter\Config\Services::codeigniter();

// Simulate request
$_GET['barcode'] = 'EOF-001';

$productModel = new \App\Models\ProductModel();
$categoryModel = new \App\Models\CategoryModel();

echo "Testing barcode lookup for: EOF-001\n";

$product = $productModel->select('products.*, categories.category_name')
                        ->join('categories', 'categories.id = products.category_id', 'left')
                        ->where('products.sku', 'EOF-001')
                        ->first();

if ($product) {
    echo "Product found:\n";
    print_r($product);
} else {
    echo "Product not found\n";
}