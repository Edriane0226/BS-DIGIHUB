<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\ShelfLocationModel;
use App\Models\ProductCompatibilityModel;
use App\Models\CarModel as CarModelModel;

class Products extends BaseController
{
    protected $productModel;
    protected $categoryModel;
    protected $shelfLocationModel;
    protected $compatModel;
    protected $carModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->shelfLocationModel = new ShelfLocationModel();
        $this->compatModel = new ProductCompatibilityModel();
        $this->carModel = new CarModelModel();
    }

    public function index()
    {
        $query = $this->productModel->withCategoryAndShelf();
        
        // Handle filters
        $filters = [
            'search' => $this->request->getGet('search'),
            'category' => $this->request->getGet('category'),
            'type' => $this->request->getGet('type'),
            'stock' => $this->request->getGet('stock')
        ];
        
        // Apply search filter
        if (!empty($filters['search'])) {
            $query->groupStart()
                  ->like('products.product_name', $filters['search'])
                  ->orLike('products.ean13', $filters['search'])
                  ->groupEnd();
        }
        
        // Apply category filter
        if (!empty($filters['category'])) {
            $query->where('products.category_id', $filters['category']);
        }
        
        // Apply type filter
        if (!empty($filters['type'])) {
            $query->where('products.product_type', $filters['type']);
        }
        
        // Apply stock status filter
        if (!empty($filters['stock'])) {
            switch ($filters['stock']) {
                case 'in-stock':
                    $query->where('products.quantity >', 5);
                    break;
                case 'low-stock':
                    $query->where('products.quantity <=', 5)
                          ->where('products.quantity >', 0);
                    break;
                case 'out-of-stock':
                    $query->where('products.quantity', 0);
                    break;
            }
        }
        
        $products = $query->paginate(10);
        $pager = $query->pager;
        
        // Calculate statistics using fresh model instances
        $statsModel = new \App\Models\ProductModel();
        $stats = [
            'total' => $statsModel->countAllResults(false),
            'in_stock' => (new \App\Models\ProductModel())->where('quantity >', 5)->countAllResults(false),
            'low_stock' => (new \App\Models\ProductModel())->where('quantity <=', 5)->where('quantity >', 0)->countAllResults(false),
            'out_of_stock' => (new \App\Models\ProductModel())->where('quantity', 0)->countAllResults(false)
        ];
        
        $data = [
            'title' => 'Products',
            'breadcrumbs' => [
                'Home' => base_url(),
                'Products' => null
            ],
            'products' => $products,
            'pager' => $pager,
            'categories' => $this->categoryModel->findAll(),
            'filters' => $filters,
            'stats' => $stats
        ];
        
        return view('products/index', $data);
    }

    public function stockIn()
    {
        $data = [
            'title' => 'Stock In',
            'breadcrumbs' => [
                'Home' => base_url(),
                'Products' => base_url('products'),
                'Stock In' => null
            ],
            'categories' => $this->categoryModel->findAll(),
            'shelf_locations' => $this->shelfLocationModel->getForDropdown(),
            'car_models' => $this->carModel->findAll()
        ];
        
        return view('products/stock_in', $data);
    }

    public function stockOut()
    {
        $data = [
            'title' => 'Stock Out',
            'breadcrumbs' => [
                'Home' => base_url(),
                'Products' => base_url('products'),
                'Stock Out' => null
            ]
        ];
        
        return view('products/stock_out', $data);
    }

    public function processStockIn()
    {
        $post = $this->request->getPost();
        $barcode = $post['barcode'] ?? null;
        
        // Log the incoming data for debugging
        log_message('debug', 'Stock-in data received: ' . json_encode($post));
        
        if (!$barcode) {
            return redirect()->back()->with('error', 'Barcode is required');
        }
        
        // Handle new category creation if selected
        $categoryId = null;
        if (!empty($post['category_option'])) {
            if ($post['category_option'] === 'new') {
                // Validate new category fields
                if (empty($post['new_category_name'])) {
                    return redirect()->back()->withInput()->with('error', 'Category name is required for new category');
                }
                
                // Create new category
                $newCategoryData = [
                    'category_name' => $post['new_category_name']
                ];
                
                $categoryId = $this->categoryModel->insert($newCategoryData);
                if (!$categoryId) {
                    return redirect()->back()->withInput()->with('error', 'Failed to create new category');
                }
            } elseif ($post['category_option'] === 'existing') {
                // Use existing selected category - validate it exists
                if (!empty($post['category_id'])) {
                    $existingCategory = $this->categoryModel->find($post['category_id']);
                    if ($existingCategory) {
                        $categoryId = $post['category_id'];
                    } else {
                        return redirect()->back()->withInput()->with('error', 'Selected category does not exist');
                    }
                }
            }
            // If 'none' option is selected, categoryId remains null
        }
        
        // Handle new car model creation if selected
        $carModelIds = [];
        if (!empty($post['compatibility_option']) && $post['compatibility_option'] === 'new') {
            // Validate new car model fields
            if (empty($post['new_car_brand']) || empty($post['new_car_model']) || empty($post['new_car_year_start'])) {
                return redirect()->back()->withInput()->with('error', 'Brand, model, and start year are required for new car model');
            }
            
            // Create new car model
            $newCarData = [
                'brand' => $post['new_car_brand'],
                'model' => $post['new_car_model'], 
                'year_start' => (int)$post['new_car_year_start'],
                'year_end' => !empty($post['new_car_year_end']) ? (int)$post['new_car_year_end'] : null
            ];
            
            $newCarId = $this->carModel->insert($newCarData);
            if ($newCarId) {
                $carModelIds[] = $newCarId;
            }
        } else {
            // Use existing selected car models
            $carModelIds = (array)($post['car_model_id'] ?? []);
        }
        
        // Check if product exists by EAN-13/barcode
        $product = $this->productModel->where('ean13', $barcode)
                                      ->first();
        
        if ($product) {
            // Existing product - update quantity and category if provided
            $updateData = ['quantity' => $product['quantity'] + (int)$post['quantity']];
            
            // Only update category if user explicitly chose to change it
            if (!empty($post['category_option'])) {
                if ($post['category_option'] === 'existing' && !empty($post['category_id'])) {
                    // Validate the category exists
                    $categoryExists = $this->categoryModel->find($post['category_id']);
                    if ($categoryExists) {
                        $updateData['category_id'] = $post['category_id'];
                    } else {
                        return redirect()->back()->withInput()->with('error', 'Selected category does not exist');
                    }
                } elseif ($post['category_option'] === 'none') {
                    $updateData['category_id'] = null;
                } elseif ($post['category_option'] === 'new' && $categoryId) {
                    $updateData['category_id'] = $categoryId;
                }
                // If category_option is 'existing' but category_id is empty, don't update category
            }
            
            // Update shelf location if provided
            if (isset($post['shelf_location_id'])) {
                $updateData['shelf_location_id'] = !empty($post['shelf_location_id']) ? $post['shelf_location_id'] : null;
            }
            
            $this->productModel->update($product['id'], $updateData);
            
            // Handle car compatibility for existing product
            if (!empty($carModelIds)) {
                foreach ($carModelIds as $carModelId) {
                    // Avoid duplicates
                    $exists = $this->compatModel->where(['product_id' => $product['id'], 'car_model_id' => $carModelId])->first();
                    if (!$exists) {
                        $this->compatModel->insert(['product_id' => $product['id'], 'car_model_id' => $carModelId]);
                    }
                }
            }
            
            // Log stock in
            $stockInModel = model('App\\Models\\StockInModel');
            $stockInData = [
                'product_id' => $product['id'],
                'quantity' => (int)$post['quantity'],
                'date_received' => date('Y-m-d H:i:s')
            ];
            
            // Handle cost tracking and price comparison
            if (isset($post['cost_per_unit']) && !empty($post['cost_per_unit'])) {
                $costPerUnit = (float)$post['cost_per_unit'];
                
                // Check if cost is greater than current selling price
                if ($costPerUnit > $product['price']) {
                    // Check if user wants to update the price
                    if (isset($post['update_price']) && $post['update_price'] === '1' && isset($post['new_price']) && !empty($post['new_price'])) {
                        $newPrice = (float)$post['new_price'];
                        if ($newPrice >= $costPerUnit) {
                            $updateData['price'] = $newPrice;
                            $this->productModel->update($product['id'], $updateData);
                        }
                    } elseif (!isset($post['skip_price_warning']) || $post['skip_price_warning'] !== '1') {
                        // Return with warning if no price update decision was made and user hasn't chosen to skip
                        $session = session();
                        $session->setFlashdata('price_warning', [
                            'product_name' => $product['product_name'],
                            'current_price' => $product['price'],
                            'cost_per_unit' => $costPerUnit,
                            'barcode' => $barcode,
                            'quantity' => $post['quantity'],
                            'other_data' => $post
                        ]);
                        return redirect()->back()->withInput();
                    }
                }
                
                $productCostModel = model('App\\Models\\ProductCostModel');
                $costId = $productCostModel->setCostForProduct(
                    $product['id'], 
                    $costPerUnit,
                    date('Y-m-d')
                );
                
                // Get the ID of the cost record (setCostForProduct returns insert ID for new records)
                if (!is_numeric($costId)) {
                    // If it was an update, get the latest cost record
                    $latestCost = $productCostModel->getLatestCostForProduct($product['id']);
                    $stockInData['product_cost_id'] = $latestCost['id'];
                } else {
                    $stockInData['product_cost_id'] = $costId;
                }
            }
            
            $stockInModel->insert($stockInData);
            
            // Build success message
            $message = 'Stock updated for ' . $product['product_name'];
            if (!empty($post['category_option']) && $post['category_option'] === 'new') {
                $message .= '. New category "' . $post['new_category_name'] . '" created and assigned.';
            }
            if (isset($post['shelf_location_id']) && !empty($post['shelf_location_id'])) {
                // Get shelf location name for message
                $shelfLocation = $this->shelfLocationModel->find($post['shelf_location_id']);
                if ($shelfLocation) {
                    $message .= '. Shelf location updated to "' . $shelfLocation['loc_descrip'] . '".';
                }
            }
            
            return redirect()->to('/products/stock-in')->with('success', $message);
        } else {
            // New product - create it
            $rules = [
                'product_name' => 'required|min_length[3]',
                'price' => 'required|decimal',
                'product_type' => 'required',
                'quantity' => 'required|integer|greater_than[0]'
            ];
            
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            
            // Additional validation: cost should be less than price for new products
            if (isset($post['cost_per_unit']) && !empty($post['cost_per_unit'])) {
                $costPerUnit = (float)$post['cost_per_unit'];
                $price = (float)$post['price'];
                
                if ($costPerUnit >= $price) {
                    return redirect()->back()->withInput()->with('error', 
                        'Cost per unit (₱' . number_format($costPerUnit, 2) . ') must be less than selling price (₱' . number_format($price, 2) . ')');
                }
            }
            
            $productId = $this->productModel->insert([
                'product_name' => $post['product_name'],
                'ean13' => $barcode, // Add the EAN-13 barcode
                'category_id' => $categoryId,
                'shelf_location_id' => $post['shelf_location_id'] ?? null,
                'product_type' => $post['product_type'],
                'price' => $post['price'],
                'quantity' => $post['quantity'],
            ]);
            
            // Handle car compatibility for new product
            if (!empty($carModelIds)) {
                foreach ($carModelIds as $carModelId) {
                    $this->compatModel->insert(['product_id' => $productId, 'car_model_id' => $carModelId]);
                }
            }
            
            // Log stock in
            $stockInModel = model('App\\Models\\StockInModel');
            $stockInData = [
                'product_id' => $productId,
                'quantity' => (int)$post['quantity'],
                'date_received' => date('Y-m-d H:i:s')
            ];
            
            // Handle cost tracking
            if (isset($post['cost_per_unit']) && !empty($post['cost_per_unit'])) {
                $costPerUnit = (float)$post['cost_per_unit'];
                
                $productCostModel = model('App\\Models\\ProductCostModel');
                $costId = $productCostModel->setCostForProduct(
                    $productId, 
                    $costPerUnit,
                    date('Y-m-d')
                );
                
                // For new products, this will always be a new cost record
                $stockInData['product_cost_id'] = $costId;
            }
            
            $stockInModel->insert($stockInData);
            
            // Build success message for new product
            $message = 'New product created and stock added';
            if (!empty($post['category_option']) && $post['category_option'] === 'new') {
                $message .= '. New category "' . $post['new_category_name'] . '" created and assigned.';
            }
            
            return redirect()->to('/products/stock-in')->with('success', $message);
        }
    }

    public function processStockOut()
    {
        $post = $this->request->getPost();
        $barcode = $post['barcode'] ?? null;
        $quantity = (int)($post['quantity'] ?? 0);
        $reason = $post['reason'] ?? 'Other';
        
        if (!$barcode) {
            return redirect()->back()->with('error', 'Barcode is required');
        }
        
        if ($quantity <= 0) {
            return redirect()->back()->with('error', 'Quantity must be greater than 0');
        }
        
        $product = $this->productModel->where('ean13', $barcode)
                                      ->orWhere('ean13', $barcode)
                                      ->first();
        
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found with this barcode');
        }
        
        if ($product['quantity'] < $quantity) {
            return redirect()->back()->with('error', 'Insufficient stock. Available: ' . $product['quantity']);
        }
        
        // Update product quantity
        $newQuantity = $product['quantity'] - $quantity;
        $this->productModel->update($product['id'], ['quantity' => $newQuantity]);
        
        // Log stock out
        $stockOutModel = model('App\\Models\\StockOutModel');
        $stockOutModel->insert([
            'product_id' => $product['id'],
            'quantity' => $quantity,
            'date_out' => date('Y-m-d H:i:s'),
            'reason' => $reason
        ]);
        
        // If reason is "Sale", also create a sales record
        if ($reason === 'Sale') {
            $totalAmount = $product['price'] * $quantity;
            
            $salesModel = model('App\\Models\\SalesModel');
            $salesModel->insert([
                'product_id' => $product['id'],
                'quantity' => $quantity,
                'total_amount' => $totalAmount,
                'date_sold' => date('Y-m-d H:i:s')
            ]);
            
            return redirect()->to('/products/stock-out')->with('success', 
                'Sale recorded for ' . $product['product_name'] . '. Quantity sold: ' . $quantity . 
                ', Amount: ₱' . number_format($totalAmount, 2) . '. Remaining stock: ' . $newQuantity);
        }
        
        return redirect()->to('/products/stock-out')->with('success', 
            'Stock removed for ' . $product['product_name'] . '. Reason: ' . $reason . '. Remaining: ' . $newQuantity);
    }

    public function getProductByBarcode()
    {
        // Add CORS headers
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Content-Type: application/json');
        
        $barcode = $this->request->getGet('barcode');
        
        // Debug logging
        log_message('debug', 'getProductByBarcode called with barcode: ' . $barcode);
        
        if (!$barcode) {
            log_message('debug', 'No barcode provided');
            return $this->response->setJSON(['success' => false, 'message' => 'Barcode is required']);
        }
        
        try {
            $product = $this->productModel->select('products.*, categories.category_name, shelf_locations.loc_descrip as shelf_location')
                                           ->join('categories', 'categories.id = products.category_id', 'left')
                                           ->join('shelf_locations', 'shelf_locations.id = products.shelf_location_id', 'left')
                                           ->where('products.ean13', $barcode)
                                           ->first();
            
            log_message('debug', 'Product lookup result: ' . json_encode($product));
            
            if ($product) {
                // Get car compatibility for this product
                $compatibilities = $this->compatModel->select('car_models.*')
                                                    ->join('car_models', 'car_models.id = product_compatibility.car_model_id')
                                                    ->where('product_compatibility.product_id', $product['id'])
                                                    ->findAll();
                
                $product['compatibilities'] = $compatibilities;
                
                return $this->response->setJSON(['success' => true, 'product' => $product]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Product not found']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in getProductByBarcode: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Database error']);
        }
    }

    public function edit($id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Product not found');
        }
        
        $data = [
            'title' => 'Edit Product',
            'breadcrumbs' => [
                'Home' => base_url(),
                'Products' => base_url('products'),
                'Edit Product' => null
            ],
            'product' => $product,
            'categories' => $this->categoryModel->findAll(),
            'shelf_locations' => $this->shelfLocationModel->getForDropdown(),
            'compatibilities' => $this->compatModel->getCompatibilityForProduct($id),
            'car_models' => $this->carModel->findAll()
        ];
        
        return view('products/edit', $data);
    }

    public function update($id)
    {
        $post = $this->request->getPost();
        $this->productModel->update($id, [
            'product_name' => $post['product_name'],
            'ean13' => $post['ean13'] ?? null,
            'category_id' => $post['category_id'] ?? null,
            'shelf_location_id' => $post['shelf_location_id'] ?? null,
            'product_type' => $post['product_type'],
            'price' => $post['price'],
            'quantity' => $post['quantity'] ?? 0,
        ]);

        // handle compatibility additions
        if (!empty($post['car_model_id'])) {
            foreach ((array)$post['car_model_id'] as $carModelId) {
                // avoid duplicates: use findBy combination
                $exists = $this->compatModel->where(['product_id' => $id, 'car_model_id' => $carModelId])->first();
                if (!$exists) {
                    $this->compatModel->insert(['product_id' => $id, 'car_model_id' => $carModelId]);
                }
            }
        }

        return redirect()->to('/products')->with('success', 'Product updated');
    }

    public function delete($id)
    {
        $this->productModel->delete($id);
        return redirect()->to('/products')->with('success', 'Product deleted');
    }

    public function getProductDetails($id)
    {
        // Set proper headers for JSON response
        $this->response->setContentType('application/json');
        
        try {
            // Log the request for debugging
            log_message('info', 'getProductDetails called with ID: ' . $id);
            
            // Validate ID is numeric
            if (!is_numeric($id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid product ID'
                ]);
            }
            
            // First get the basic product
            $product = $this->productModel->find($id);
            
            if (!$product) {
                log_message('warning', 'Product not found with ID: ' . $id);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Product not found'
                ]);
            }

            log_message('info', 'Product found: ' . json_encode($product));

            // Get category name if category_id exists
            if (!empty($product['category_id'])) {
                $category = $this->categoryModel->find($product['category_id']);
                $product['category_name'] = $category ? $category['category_name'] : null;
                log_message('info', 'Category found: ' . ($category ? $category['category_name'] : 'None'));
            } else {
                $product['category_name'] = null;
            }

            // Get shelf location if shelf_location_id exists
            if (!empty($product['shelf_location_id'])) {
                $shelfLocation = $this->shelfLocationModel->find($product['shelf_location_id']);
                if ($shelfLocation) {
                    $product['shelf_id'] = $shelfLocation['shelf_id'];
                    $product['loc_descrip'] = $shelfLocation['loc_descrip'];
                    log_message('info', 'Shelf location found: ' . $shelfLocation['shelf_id']);
                } else {
                    $product['shelf_id'] = null;
                    $product['loc_descrip'] = null;
                    log_message('info', 'No shelf location found');
                }
            } else {
                $product['shelf_id'] = null;
                $product['loc_descrip'] = null;
                log_message('info', 'No shelf_location_id');
            }

            log_message('info', 'Returning product data: ' . json_encode($product));

            return $this->response->setJSON([
                'success' => true,
                'product' => $product
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getProductDetails: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error retrieving product details: ' . $e->getMessage()
            ]);
        }
    }
}
