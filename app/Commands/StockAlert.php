<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\StockAlertService;

class StockAlert extends BaseCommand
{
    protected $group       = 'Inventory';
    protected $name        = 'inventory:alerts';
    protected $description = 'Check inventory levels and send stock alerts via email';

    protected $usage = 'inventory:alerts [options]';

    protected $options = [
        '--force' => 'Force send alerts even if already sent today',
        '--type'  => 'Specific alert type: low_stock, out_of_stock, summary',
        '--test'  => 'Test mode - show what would be sent without actually sending emails'
    ];

    public function run(array $params)
    {
        CLI::write('BS DIGIHUB - Stock Alert System', 'yellow');
        CLI::write('====================================', 'yellow');
        CLI::newLine();

        $stockAlertService = new StockAlertService();
        
        $isTest = CLI::getOption('test') !== null;
        $force = CLI::getOption('force') !== null;
        $alertType = CLI::getOption('type');

        if ($isTest) {
            CLI::write('🧪 Running in TEST MODE - No emails will be sent', 'cyan');
            CLI::newLine();
        }

        try {
            // Get current stock statistics
            $stats = $stockAlertService->getStockStatistics();
            
            CLI::write('📊 Current Inventory Status:', 'green');
            CLI::write("   Total Products: {$stats['total_products']}", 'white');
            CLI::write("   Out of Stock: {$stats['out_of_stock']}", $stats['out_of_stock'] > 0 ? 'red' : 'white');
            CLI::write("   Low Stock: {$stats['low_stock']}", $stats['low_stock'] > 0 ? 'yellow' : 'white');
            CLI::write("   In Stock: {$stats['in_stock']}", 'green');
            CLI::write("   Total Value: ₱" . number_format($stats['total_value'], 2), 'cyan');
            CLI::newLine();

            if ($stats['out_of_stock'] == 0 && $stats['low_stock'] == 0) {
                CLI::write('✅ All products are well stocked! No alerts needed.', 'green');
                return;
            }

            if (!$isTest) {
                // Send actual alerts
                $result = $stockAlertService->checkAndSendAlerts();
                
                if (!empty($result['alerts_sent'])) {
                    CLI::write('📧 Alerts sent successfully:', 'green');
                    foreach ($result['alerts_sent'] as $alert) {
                        CLI::write("   ✓ " . ucfirst(str_replace('_', ' ', $alert)) . " alert", 'green');
                    }
                } else {
                    CLI::write('ℹ️  No new alerts sent (may have been sent already today)', 'yellow');
                }
            } else {
                // Test mode - show what would be sent
                CLI::write('📧 Alerts that would be sent:', 'cyan');
                if ($stats['out_of_stock'] > 0) {
                    CLI::write("   • Out of Stock Alert ({$stats['out_of_stock']} products)", 'red');
                }
                if ($stats['low_stock'] > 0) {
                    CLI::write("   • Low Stock Alert ({$stats['low_stock']} products)", 'yellow');
                }
                CLI::write("   • Daily Summary Alert", 'blue');
            }

        } catch (\Exception $e) {
            CLI::write('❌ Error running stock alerts: ' . $e->getMessage(), 'red');
            CLI::write('Check logs for more details.', 'yellow');
        }

        CLI::newLine();
        CLI::write('Stock alert check completed!', 'green');
    }
}