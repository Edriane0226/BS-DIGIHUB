<?php

namespace App\Libraries;

use App\Models\ProductModel;
use App\Libraries\MailerService;

class StockAlertService
{
    private $productModel;
    private $mailer;
    private $alertSettings;
    
    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->mailer = new MailerService();
        
        // Default alert settings - can be moved to database later
        $this->alertSettings = [
            'low_stock_threshold' => 5,
            'critical_stock_threshold' => 0,
            'recipients' => [
                'edriane.bangonon26@gmail.com' => 'Owner'
            ],
            'alert_frequency' => 'daily', // daily, weekly, immediate
            'send_low_stock' => true,
            'send_out_of_stock' => true,
            'send_daily_summary' => true
        ];
    }
    
    public function checkAndSendAlerts()
    {
        $products = $this->getAllProductsWithCategories();
        
        $outOfStockProducts = $this->getOutOfStockProducts($products);
        $lowStockProducts = $this->getLowStockProducts($products);
        
        $alertsSent = [];
        
        // Send out of stock alerts (high priority)
        if (!empty($outOfStockProducts) && $this->alertSettings['send_out_of_stock']) {
            if ($this->mailer->sendStockAlert('out_of_stock', $outOfStockProducts, $this->alertSettings['recipients'])) {
                $alertsSent[] = 'out_of_stock';
                $this->logAlert('out_of_stock', count($outOfStockProducts));
            }
        }
        
        // Send low stock alerts
        if (!empty($lowStockProducts) && $this->alertSettings['send_low_stock']) {
            if ($this->mailer->sendStockAlert('low_stock', $lowStockProducts, $this->alertSettings['recipients'])) {
                $alertsSent[] = 'low_stock';
                $this->logAlert('low_stock', count($lowStockProducts));
            }
        }
        
        // Send daily summary if enabled
        if ($this->alertSettings['send_daily_summary'] && $this->shouldSendDailySummary()) {
            $allAlertProducts = array_merge($outOfStockProducts, $lowStockProducts);
            if (!empty($allAlertProducts)) {
                if ($this->mailer->sendStockAlert('critical_stock', $allAlertProducts, $this->alertSettings['recipients'])) {
                    $alertsSent[] = 'daily_summary';
                    $this->logAlert('daily_summary', count($allAlertProducts));
                }
            }
        }
        
        return [
            'alerts_sent' => $alertsSent,
            'out_of_stock_count' => count($outOfStockProducts),
            'low_stock_count' => count($lowStockProducts)
        ];
    }
    
    public function sendImmediateAlert($productId)
    {
        $product = $this->productModel->select('products.*, categories.category_name')
                                     ->join('categories', 'categories.id = products.category_id', 'left')
                                     ->find($productId);
        
        if (!$product) {
            return false;
        }
        
        $alertType = $product['quantity'] == 0 ? 'out_of_stock' : 'low_stock';
        
        return $this->mailer->sendStockAlert($alertType, [$product], $this->alertSettings['recipients']);
    }
    
    private function getAllProductsWithCategories()
    {
        return $this->productModel->select('products.*, categories.category_name')
                                  ->join('categories', 'categories.id = products.category_id', 'left')
                                  ->findAll();
    }
    
    private function getOutOfStockProducts($products)
    {
        return array_filter($products, function($product) {
            return $product['quantity'] == 0;
        });
    }
    
    private function getLowStockProducts($products)
    {
        return array_filter($products, function($product) {
            return $product['quantity'] > 0 && $product['quantity'] <= $this->alertSettings['low_stock_threshold'];
        });
    }
    
    private function shouldSendDailySummary()
    {
        // Check if daily summary was already sent today
        $lastSent = cache()->get('last_daily_summary_sent');
        $today = date('Y-m-d');
        
        return $lastSent !== $today;
    }
    
    private function logAlert($alertType, $productCount)
    {
        log_message('info', "Stock alert sent - Type: {$alertType}, Products: {$productCount}");
        
        // Update cache for daily summary tracking
        if ($alertType === 'daily_summary') {
            cache()->save('last_daily_summary_sent', date('Y-m-d'), 86400); // 24 hours
        }
        
        // You can also save to database for alert history tracking
        // $this->saveAlertHistory($alertType, $productCount);
    }
    
    public function getAlertSettings()
    {
        return $this->alertSettings;
    }
    
    public function updateAlertSettings($newSettings)
    {
        $this->alertSettings = array_merge($this->alertSettings, $newSettings);
        // In a real application, you might save this to database
        return true;
    }
    
    public function getStockStatistics()
    {
        $products = $this->getAllProductsWithCategories();
        
        return [
            'total_products' => count($products),
            'out_of_stock' => count($this->getOutOfStockProducts($products)),
            'low_stock' => count($this->getLowStockProducts($products)),
            'in_stock' => count(array_filter($products, fn($p) => $p['quantity'] > $this->alertSettings['low_stock_threshold'])),
            'total_value' => array_sum(array_map(fn($p) => $p['price'] * $p['quantity'], $products))
        ];
    }
}