<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\SalesModel;
use App\Models\ProductModel;
use App\Models\StockInModel;
use App\Models\StockOutModel;

class SendDailySalesReport extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Reports';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'reports:daily-sales';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Send daily sales report via email';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'reports:daily-sales [date] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'date' => 'Date for the report (YYYY-MM-DD format, defaults to yesterday)'
    ];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--email' => 'Email address to send the report to (can be used multiple times)',
        '--format' => 'Report format: html or csv (default: html)'
    ];

    protected $salesModel;
    protected $productModel;
    protected $stockInModel;
    protected $stockOutModel;

    public function __construct()
    {
        $this->salesModel = new SalesModel();
        $this->productModel = new ProductModel();
        $this->stockInModel = new StockInModel();
        $this->stockOutModel = new StockOutModel();
    }

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Starting daily sales report generation...', 'cyan');
        
        // Get date parameter or default to yesterday
        $date = $params[0] ?? date('Y-m-d', strtotime('-1 day'));
        
        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            CLI::error('Invalid date format. Use YYYY-MM-DD format.');
            return;
        }
        
        try {
            // Generate report data
            $reportData = $this->generateDailySalesData($date);
            
            // Get email addresses from options or use default
            $emails = $this->getEmailRecipients();
            
            if (empty($emails)) {
                CLI::error('No email recipients configured. Please set EMAIL_RECIPIENTS in .env or use --email option.');
                return;
            }
            
            // Get format option
            $format = CLI::getOption('format') ?? 'html';
            
            // Send emails
            foreach ($emails as $email) {
                $this->sendDailySalesReport($email, $reportData, $date, $format);
                CLI::write('Report sent to: ' . $email, 'green');
            }
            
            CLI::write('Daily sales report completed successfully!', 'green');
            
        } catch (\Exception $e) {
            CLI::error('Error generating report: ' . $e->getMessage());
            log_message('error', 'Daily sales report failed: ' . $e->getMessage());
        }
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
    
    /**
     * Calculate daily profit
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
     * Get email recipients from options or environment
     */
    private function getEmailRecipients()
    {
        $emails = [];
        
        // Get from command line options
        $emailOptions = CLI::getOptions()['email'] ?? [];
        if (!is_array($emailOptions)) {
            $emailOptions = [$emailOptions];
        }
        $emails = array_merge($emails, $emailOptions);
        
        // Get from environment variable
        $envEmails = env('EMAIL_RECIPIENTS');
        if ($envEmails) {
            $emails = array_merge($emails, explode(',', $envEmails));
        }
        
        // Clean and validate emails
        return array_filter(array_map('trim', $emails), function($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });
    }
    
    /**
     * Send the daily sales report email
     */
    private function sendDailySalesReport($email, $reportData, $date, $format)
    {
        // Load the MailerService manually
        $mailerService = new \App\Libraries\MailerService();
        
        // Use the dedicated method in MailerService
        $result = $mailerService->sendDailySalesReport($date, $reportData, [$email]);
        
        if (!$result) {
            CLI::error('Failed to send email to: ' . $email);
            return false;
        }
        
        return true;
    }
    
    /**
     * Generate CSV content for sales report
     */
    private function generateSalesCSV($data)
    {
        $csv = "BS DIGIHUB - DAILY SALES REPORT\n";
        $csv .= "Date: " . date('F j, Y', strtotime($data['date'])) . "\n\n";
        
        $csv .= "SUMMARY\n";
        $csv .= "Total Revenue,₱" . number_format($data['totals']['revenue'], 2) . "\n";
        $csv .= "Total Items Sold," . $data['totals']['quantity'] . "\n";
        $csv .= "Total Transactions," . $data['totals']['transactions'] . "\n";
        $csv .= "Estimated Profit,₱" . number_format($data['totals']['profit'], 2) . "\n\n";
        
        $csv .= "SALES DETAILS\n";
        $csv .= "Product Name,EAN-13,Category,Quantity,Unit Price,Total Amount,Time\n";
        
        foreach ($data['sales'] as $sale) {
            $csv .= sprintf(
                '"%s","%s","%s",%s,₱%s,₱%s,"%s"' . "\n",
                $sale['product_name'],
                $sale['ean13'],
                $sale['category_name'] ?? 'Uncategorized',
                $sale['quantity'],
                number_format($sale['total_amount'] / $sale['quantity'], 2),
                number_format($sale['total_amount'], 2),
                date('H:i:s', strtotime($sale['date_sold']))
            );
        }
        
        return $csv;
    }
}
