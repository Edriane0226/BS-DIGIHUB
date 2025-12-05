<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\CarModel as CarModelModel;
use App\Models\StockInModel;
use App\Models\StockOutModel;
use App\Models\SalesModel;
use App\Libraries\StockAlertService;

class Reports extends BaseController
{
    protected $productModel;
    protected $categoryModel;
    protected $carModel;
    protected $stockInModel;
    protected $stockOutModel;
    protected $salesModel;
    protected $stockAlertService;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->carModel = new CarModelModel();
        $this->stockInModel = new StockInModel();
        $this->stockOutModel = new StockOutModel();
        $this->salesModel = new SalesModel();
        $this->stockAlertService = new StockAlertService();
    }

    public function index()
    {
        $data = [
            'title' => 'Reports & Analytics',
            'breadcrumbs' => [
                'Home' => base_url(),
                'Reports' => null
            ]
        ];
        
        return view('reports/index', $data);
    }

    public function inventory()
    {
        // Get inventory data
        $products = $this->productModel->select('products.*, categories.category_name')
                                      ->join('categories', 'categories.id = products.category_id', 'left')
                                      ->findAll();

        // Get stock out data with reasons
        $stockOuts = $this->stockOutModel->select('reason, SUM(quantity) as total_quantity, COUNT(*) as transaction_count')
                                          ->groupBy('reason')
                                          ->findAll();

        // Calculate total sales and damage
        $totalSales = $this->stockOutModel->selectSum('quantity', 'total_quantity')
                                          ->where('reason !=', 'damage')
                                          ->first()['total_quantity'] ?? 0;
        
        $totalDamage = $this->stockOutModel->selectSum('quantity', 'total_quantity')
                                           ->where('reason', 'damage')
                                           ->first()['total_quantity'] ?? 0;
        
        $totalStockIn = $this->stockInModel->selectSum('quantity', 'total_quantity')
                                           ->first()['total_quantity'] ?? 0;

        // Calculate inventory statistics
        $stats = [
            'total_products' => count($products),
            'total_value' => array_sum(array_map(fn($p) => $p['price'] * $p['quantity'], $products)),
            'total_quantity' => array_sum(array_column($products, 'quantity')),
            'out_of_stock' => count(array_filter($products, fn($p) => $p['quantity'] == 0)),
            'low_stock' => count(array_filter($products, fn($p) => $p['quantity'] > 0 && $p['quantity'] <= 5)),
            'in_stock' => count(array_filter($products, fn($p) => $p['quantity'] > 5)),
            'total_sales' => $totalSales,
            'total_damage' => $totalDamage,
            'total_stock_in' => $totalStockIn
        ];

        // Category breakdown
        $categoryStats = [];
        foreach ($products as $product) {
            $category = $product['category_name'] ?? 'Uncategorized';
            if (!isset($categoryStats[$category])) {
                $categoryStats[$category] = [
                    'count' => 0,
                    'value' => 0,
                    'quantity' => 0
                ];
            }
            $categoryStats[$category]['count']++;
            $categoryStats[$category]['value'] += $product['price'] * $product['quantity'];
            $categoryStats[$category]['quantity'] += $product['quantity'];
        }

        // Top products by value
        usort($products, fn($a, $b) => ($b['price'] * $b['quantity']) <=> ($a['price'] * $a['quantity']));
        $topProducts = array_slice($products, 0, 10);

        $data = [
            'title' => 'Inventory Report',
            'breadcrumbs' => [
                'Home' => base_url(),
                'Reports' => base_url('reports'),
                'Inventory Report' => null
            ],
            'stats' => $stats,
            'categoryStats' => $categoryStats,
            'topProducts' => $topProducts,
            'products' => $products,
            'stockOuts' => $stockOuts
        ];
        
        return view('reports/inventory', $data);
    }

    public function export($type = 'inventory')
    {
        helper('download');
        
        switch ($type) {
            case 'inventory':
                return $this->exportInventory();
            case 'sales':
                return $this->exportSales();
            case 'daily-sales':
                $date = $this->request->getGet('date') ?? date('Y-m-d');
                return $this->exportDailySales($date);
            default:
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Export type not found');
        }
    }

    private function exportInventory()
    {
        $products = $this->productModel->select('products.*, categories.category_name, shelf_locations.shelf_id, shelf_locations.loc_descrip')
                                      ->join('categories', 'categories.id = products.category_id', 'left')
                                      ->join('shelf_locations', 'shelf_locations.id = products.shelf_location_id', 'left')
                                      ->findAll();

        // Calculate enhanced statistics
        $totalValue = 0;
        $totalQuantity = array_sum(array_column($products, 'quantity'));
        
        // Calculate total value with cost information
        foreach ($products as &$product) {
            // Get latest cost for each product
            $latestCost = $this->getLatestProductCost($product['id']);
            $product['latest_cost'] = $latestCost;
            $product['profit_per_unit'] = $product['price'] - $latestCost;
            $product['total_value'] = $product['price'] * $product['quantity'];
            $product['total_cost'] = $latestCost * $product['quantity'];
            $product['potential_profit'] = $product['profit_per_unit'] * $product['quantity'];
            
            $totalValue += $product['total_value'];
        }
        
        $totalSales = $this->stockOutModel->selectSum('quantity', 'total_quantity')
                                          ->where('reason', 'Sale')
                                          ->first()['total_quantity'] ?? 0;
        
        $totalDamage = $this->stockOutModel->selectSum('quantity', 'total_quantity')
                                           ->whereIn('reason', ['Damage', 'Loss'])
                                           ->first()['total_quantity'] ?? 0;
        
        $totalStockIn = $this->stockInModel->selectSum('quantity', 'total_quantity')
                                           ->first()['total_quantity'] ?? 0;

        // Calculate total profit from sales
        $totalProfit = $this->calculateTotalProfit();
        
        // Start enhanced CSV with comprehensive summary
        $csv = "BS DIGIHUB - COMPREHENSIVE INVENTORY REPORT\n";
        $csv .= "Generated: " . date('Y-m-d H:i:s') . "\n";
        $csv .= "Report Period: All Time\n\n";
        
        $csv .= "EXECUTIVE SUMMARY\n";
        $csv .= "Total Products," . count($products) . "\n";
        $csv .= "Total Inventory Value," . $totalValue . "\n";
        $csv .= "Total Cost Value," . array_sum(array_column($products, 'total_cost')) . "\n";
        $csv .= "Potential Profit," . array_sum(array_column($products, 'potential_profit')) . "\n";
        $csv .= "Realized Profit (Sales)," . $totalProfit . "\n";
        $csv .= "Total Current Stock," . $totalQuantity . "\n";
        $csv .= "Total Stock In (Historical)," . $totalStockIn . "\n";
        $csv .= "Total Sales (Historical)," . $totalSales . "\n";
        $csv .= "Total Damage/Loss," . $totalDamage . "\n\n";
        
        // Stock status summary
        $outOfStock = count(array_filter($products, fn($p) => $p['quantity'] == 0));
        $lowStock = count(array_filter($products, fn($p) => $p['quantity'] > 0 && $p['quantity'] <= 5));
        $inStock = count($products) - $outOfStock - $lowStock;
        
        $csv .= "STOCK STATUS SUMMARY\n";
        $csv .= "In Stock," . $inStock . "\n";
        $csv .= "Low Stock (≤5)," . $lowStock . "\n";
        $csv .= "Out of Stock," . $outOfStock . "\n\n";

        return $this->response->download('inventory_report_' . date('Y-m-d') . '.csv', $csv);
    }
    
    /**
     * Get latest cost for a product
     */
    private function getLatestProductCost($productId)
    {
        $db = \Config\Database::connect();
        
        $query = "SELECT pc.cost_per_unit 
                  FROM product_costs pc
                  WHERE pc.product_id = ?
                  ORDER BY pc.effective_date DESC, pc.created_at DESC
                  LIMIT 1";
        
        $result = $db->query($query, [$productId])->getRowArray();

        
        return $result['cost_per_unit'] ?? 0;
    }
    
    /**
     * Export all sales data
     */
    private function exportSales()
    {
        $sales = $this->salesModel->select('sales.*, products.product_name, products.ean13, categories.category_name')
                                 ->join('products', 'products.id = sales.product_id')
                                 ->join('categories', 'categories.id = products.category_id', 'left')
                                 ->orderBy('sales.date_sold', 'DESC')
                                 ->findAll();

        // Calculate totals
        $totalRevenue = array_sum(array_column($sales, 'total_amount'));
        $totalQuantity = array_sum(array_column($sales, 'quantity'));
        $totalTransactions = count($sales);

        $csv = "BS DIGIHUB - COMPLETE SALES REPORT\n";
        $csv .= "Generated: " . date('Y-m-d H:i:s') . "\n";
        $csv .= "Period: All Time\n\n";
        
        $csv .= "SUMMARY\n";
        $csv .= "Total Revenue," . number_format($totalRevenue, 2) . "\n";
        $csv .= "Total Items Sold," . number_format($totalQuantity) . "\n";
        $csv .= "Total Transactions," . number_format($totalTransactions) . "\n";
        $csv .= "Average Transaction Value," . number_format($totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0, 2) . "\n\n";
        
        $csv .= "DETAILED SALES\n";
        $csv .= "Date,Time,Product Name,EAN-13,Category,Quantity,Unit Price,Total Amount\n";
        
        foreach ($sales as $sale) {
            $unitPrice = $sale['quantity'] > 0 ? $sale['total_amount'] / $sale['quantity'] : 0;
            $csv .= sprintf(
                '"%s","%s","%s",="%s","%s",%s,%s,%s' . "\n",
                date('Y-m-d', strtotime($sale['date_sold'])),
                date('H:i:s', strtotime($sale['date_sold'])),
                $sale['product_name'],
                $sale['ean13'],
                $sale['category_name'] ?? 'Uncategorized',
                $sale['quantity'],
                number_format($unitPrice, 2),
                number_format($sale['total_amount'], 2)
            );
        }

        return $this->response->download('sales_report_' . date('Y-m-d') . '.csv', $csv);
    }
    
    /**
     * Export daily sales report
     */
    private function exportDailySales($date)
    {
        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            throw new \InvalidArgumentException('Invalid date format');
        }
        
        $sales = $this->salesModel->select('sales.*, products.product_name, products.ean13, categories.category_name')
                                 ->join('products', 'products.id = sales.product_id')
                                 ->join('categories', 'categories.id = products.category_id', 'left')
                                 ->where('DATE(sales.date_sold)', $date)
                                 ->orderBy('sales.date_sold', 'ASC')
                                 ->findAll();

        // Calculate totals
        $totalRevenue = array_sum(array_column($sales, 'total_amount'));
        $totalQuantity = array_sum(array_column($sales, 'quantity'));
        $totalTransactions = count($sales);
        
        // Calculate profit
        $totalProfit = $this->calculateDailyProfit($date);

        $csv = "BS DIGIHUB - DAILY SALES REPORT\n";
        $csv .= "Date: " . date('F j, Y', strtotime($date)) . "\n";
        $csv .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";
        
        $csv .= "SUMMARY\n";
        $csv .= "Total Revenue," . number_format($totalRevenue, 2) . "\n";
        $csv .= "Total Items Sold," . number_format($totalQuantity) . "\n";
        $csv .= "Total Transactions," . number_format($totalTransactions) . "\n";
        $csv .= "Estimated Profit," . number_format($totalProfit, 2) . "\n";
        $csv .= "Average Transaction Value," . number_format($totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0, 2) . "\n\n";
        
        if (empty($sales)) {
            $csv .= "No sales recorded for this date.\n";
        } else {
            $csv .= "SALES DETAILS\n";
            $csv .= "Time,Product Name,EAN-13,Category,Quantity,Unit Price,Total Amount\n";
            
            foreach ($sales as $sale) {
                $unitPrice = $sale['quantity'] > 0 ? $sale['total_amount'] / $sale['quantity'] : 0;
                $csv .= sprintf(
                    '"%s","%s",="%s","%s",%s,%s,%s' . "\n",
                    date('H:i:s', strtotime($sale['date_sold'])),
                    $sale['product_name'],
                    $sale['ean13'],
                    $sale['category_name'] ?? 'Uncategorized',
                    $sale['quantity'],
                    number_format($unitPrice, 2),
                    number_format($sale['total_amount'], 2)
                );
            }
        }

        return $this->response->download('daily_sales_report_' . $date . '.csv', $csv);
    }
    
    /**
     * Calculate daily profit for a specific date
     */
    private function calculateDailyProfit($date)
    {
        $db = \Config\Database::connect();
        
        $query = "SELECT 
                    SUM(s.total_amount) as revenue,
                    SUM(s.quantity * COALESCE(pc.cost_per_unit, 0)) as cost
                  FROM sales s
                  LEFT JOIN products p ON p.id = s.product_id
                  LEFT JOIN stock_in si ON si.product_id = s.product_id
                  LEFT JOIN product_costs pc ON pc.id = si.product_cost_id
                  WHERE DATE(s.date_sold) = ?
                  GROUP BY DATE(s.date_sold)";
        
        $result = $db->query($query, [$date])->getRowArray();
        
        if ($result) {
            return $result['revenue'] - $result['cost'];
        }
        
        return 0;
    }
    
    /**
     * Generate daily sales report data
     */
    private function generateDailySalesData($date)
    {
        // Get sales for the specified date
        $sales = $this->salesModel->select('sales.*, products.product_name, products.ean13, categories.category_name')
                                 ->join('products', 'products.id = sales.product_id')
                                 ->join('categories', 'categories.id = products.category_id', 'left')
                                 ->where('DATE(sales.date_sold)', $date)
                                 ->findAll();
        
        // Calculate totals
        $totalSales = array_sum(array_column($sales, 'total_amount'));
        $totalQuantity = array_sum(array_column($sales, 'quantity'));
        $totalTransactions = count($sales);
        
        // Get stock movements for the date
        $stockIn = $this->stockInModel->select('stock_in.*, products.product_name')
                                     ->join('products', 'products.id = stock_in.product_id')
                                     ->where('DATE(stock_in.date_received)', $date)
                                     ->findAll();
        
        $stockOut = $this->stockOutModel->select('stock_out.*, products.product_name')
                                       ->join('products', 'products.id = stock_out.product_id')
                                       ->where('DATE(stock_out.date_out)', $date)
                                       ->where('stock_out.reason !=', 'Sale')
                                       ->findAll();
        
        // Calculate profit (simplified - using average cost method)
        $totalProfit = $this->calculateDailyProfit($date);
        
        return [
            'date' => $date,
            'sales' => $sales,
            'totals' => [
                'revenue' => $totalSales,
                'quantity' => $totalQuantity,
                'transactions' => $totalTransactions,
                'profit' => $totalProfit
            ],
            'stock_in' => $stockIn,
            'stock_out' => $stockOut
        ];
    }
    
    public function sendStockAlerts()
    {
        try {
            // Get email from POST data
            $requestBody = $this->request->getJSON(true);
            $customEmail = $requestBody['email'] ?? null;
            
            // If custom email provided, send to that specific email
            if ($customEmail && filter_var($customEmail, FILTER_VALIDATE_EMAIL)) {
                $result = $this->stockAlertService->checkAndSendAlerts([$customEmail]);
            } else {
                // Use default email configuration
                $result = $this->stockAlertService->checkAndSendAlerts();
            }
            
            if (!empty($result['alerts_sent'])) {
                $message = 'Stock alerts sent successfully! ';
                $message .= 'Out of stock: ' . $result['out_of_stock_count'] . ', ';
                $message .= 'Low stock: ' . $result['low_stock_count'];
                
                if ($customEmail) {
                    $message = 'Stock alerts sent successfully to ' . $customEmail . '! ';
                    $message .= 'Out of stock: ' . $result['out_of_stock_count'] . ', ';
                    $message .= 'Low stock: ' . $result['low_stock_count'];
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => $message,
                    'data' => $result
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'No alerts needed or alerts already sent today',
                    'data' => $result
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Manual stock alert failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to send stock alerts: ' . $e->getMessage()
            ]);
        }
    }
    
    public function sendDailySalesReport()
    {
        try {
            // Get date parameter or default to yesterday
            $date = $this->request->getPost('date') ?? date('Y-m-d', strtotime('-1 day'));
            $email = $this->request->getPost('email');
            
            // Validate email
            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Valid email address is required'
                ]);
            }
            
            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid date format. Use YYYY-MM-DD format'
                ]);
            }
            
            // Generate report data
            $reportData = $this->generateDailySalesData($date);
            
            // Load MailerService
            $mailerService = new \App\Libraries\MailerService();
            
            // Send the daily sales report
            $success = $mailerService->sendDailySalesReport($date, $reportData, [$email]);
            
            if ($success) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Daily sales report sent successfully to ' . $email,
                    'data' => [
                        'date' => $date,
                        'recipient' => $email,
                        'total_revenue' => $reportData['totals']['revenue'],
                        'total_transactions' => $reportData['totals']['transactions']
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to send daily sales report. Please check email configuration.'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Daily sales report failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to send daily sales report: ' . $e->getMessage()
            ]);
        }
    }
    
    public function getStockStats()
    {
        try {
            $stats = $this->stockAlertService->getStockStatistics();
            return $this->response->setJSON([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to get stock statistics'
            ]);
        }
    }
    
    public function dashboardData()
    {
        try {
            // Get total products count
            $totalProducts = $this->productModel->countAllResults();
            
            // Get total inventory value (current stock * selling price)
            $products = $this->productModel->findAll();
            $totalValue = array_sum(array_map(fn($p) => $p['price'] * $p['quantity'], $products));
            
            // Get total sales amount
            $totalSales = $this->salesModel->getTotalSales();
            
            // Calculate total profit (sales revenue - cost of goods sold)
            $totalProfit = $this->calculateTotalProfit();
            
            return $this->response->setJSON([
                'success' => true,
                'totalProducts' => $totalProducts,
                'totalValue' => $totalValue,
                'totalSales' => $totalSales,
                'totalProfit' => $totalProfit
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Dashboard data error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load dashboard data'
            ]);
        }
    }
    
    private function calculateTotalProfit()
    {
        $db = \Config\Database::connect();
        
        // Calculate profit as: (sales.total_amount) - (sales.quantity * average_cost_per_unit)
        // We'll calculate average cost per unit from product_costs linked through stock_in records
        $query = "
            SELECT 
                s.id as sale_id,
                s.product_id,
                s.quantity as sold_quantity,
                s.total_amount as revenue,
                (
                    SELECT COALESCE(AVG(pc.cost_per_unit), 0) 
                    FROM stock_in si 
                    LEFT JOIN product_costs pc ON pc.id = si.product_cost_id
                    WHERE si.product_id = s.product_id 
                    AND pc.cost_per_unit IS NOT NULL 
                    AND pc.cost_per_unit > 0
                ) as avg_cost_per_unit
            FROM sales s
        ";
        
        $results = $db->query($query)->getResult();
        
        $totalProfit = 0;
        foreach ($results as $row) {
            $costOfGoodsSold = $row->sold_quantity * $row->avg_cost_per_unit;
            $profit = $row->revenue - $costOfGoodsSold;
            $totalProfit += $profit;
        }
        
        return round($totalProfit, 2);
    }
}