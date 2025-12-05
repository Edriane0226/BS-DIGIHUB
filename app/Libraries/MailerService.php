<?php

namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailerService
{
    private $mail;
    
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->setupSMTP();
    }
    
    private function setupSMTP()
    {
        try {
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host       = 'smtp.gmail.com';
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = 'edriane.bangonon26@gmail.com';
            $this->mail->Password   = 'vyjhyogubmahnhcd';
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = 587;
            
            // Default sender
            $this->mail->setFrom('edriane.bangonon26@gmail.com', 'BS DIGIHUB');
            
        } catch (Exception $e) {
            log_message('error', 'Mailer setup failed: ' . $e->getMessage());
        }
    }
    
    public function sendStockAlert($alertType = 'low_stock', $products = [], $recipients = [])
    {
        try {
            // Clear any previous recipients
            $this->mail->clearAddresses();
            
            // Add recipients
            foreach ($recipients as $email => $name) {
                if (is_numeric($email)) {
                    $this->mail->addAddress($name); // If array is indexed, treat value as email
                } else {
                    $this->mail->addAddress($email, $name);
                }
            }
            
            // Set email content based on alert type
            switch ($alertType) {
                case 'out_of_stock':
                    $this->setOutOfStockContent($products);
                    break;
                case 'low_stock':
                    $this->setLowStockContent($products);
                    break;
                case 'critical_stock':
                    $this->setCriticalStockContent($products);
                    break;
                default:
                    $this->setGeneralStockContent($products);
            }
            
            $this->mail->send();
            log_message('info', 'Stock alert email sent successfully');
            return true;
            
        } catch (Exception $e) {
            log_message('error', 'Stock alert email failed: ' . $e->getMessage());
            return false;
        }
    }
    
    private function setOutOfStockContent($products)
    {
        $this->mail->isHTML(true);
        $this->mail->Subject = ' Out of Stock Alert - BS DIGIHUB';
        
        $html = $this->getEmailHeader();
        $html .= '<h2 style="color: #212529; margin-bottom: 20px;">OUT OF STOCK ALERT</h2>';
        $html .= '<p style="font-size: 16px; margin-bottom: 20px;">The following products are completely out of stock and require immediate attention:</p>';
        
        $html .= '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
        $html .= '<thead><tr style="background-color: #ddd; color: white;">';
        $html .= '<th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Product Name</th>';
        $html .= '<th style="padding: 12px; text-align: left; border: 1px solid #ddd;">EAN-13</th>';
        $html .= '<th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Category</th>';
        $html .= '<th style="padding: 12px; text-align: center; border: 1px solid #ddd;">Stock</th>';
        $html .= '</tr></thead><tbody>';
        
        foreach ($products as $product) {
            $html .= '<tr style="border-bottom: 1px solid #ddd;">';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . htmlspecialchars($product['product_name']) . '</td>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . htmlspecialchars($product['ean13']) . '</td>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . htmlspecialchars($product['category_name'] ?? 'Uncategorized') . '</td>';
            $html .= '<td style="padding: 10px; text-align: center; border: 1px solid #ddd; color: #dc3545; font-weight: bold;">0</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table>';
        $html .= '<div style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;">';
        $html .= '<strong>Action Required:</strong> Please restock these items immediately to avoid sales disruption.';
        $html .= '</div>';
        $html .= $this->getEmailFooter();
        
        $this->mail->Body = $html;
    }
    
    private function setLowStockContent($products)
    {
        $this->mail->isHTML(true);
        $this->mail->Subject = 'Low Stock Alert - BS DIGIHUB';
        
        $html = $this->getEmailHeader();
        $html .= '<h2 style="color: #212529; margin-bottom: 20px;">LOW STOCK ALERT</h2>';
        
        $html .= '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
        $html .= '<thead><tr style="background-color: #ddd; color: #212529;">';
        $html .= '<th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Product Name</th>';
        $html .= '<th style="padding: 12px; text-align: left; border: 1px solid #ddd;">EAN-13</th>';
        $html .= '<th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Category</th>';
        $html .= '<th style="padding: 12px; text-align: center; border: 1px solid #ddd;">Current Stock</th>';
        $html .= '</tr></thead><tbody>';
        
        foreach ($products as $product) {
            $html .= '<tr style="border-bottom: 1px solid #ddd;">';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . htmlspecialchars($product['product_name']) . '</td>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . htmlspecialchars($product['ean13']) . '</td>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . htmlspecialchars($product['category_name'] ?? 'Uncategorized') . '</td>';
            $html .= '<td style="padding: 10px; text-align: center; border: 1px solid #ddd; color: #ffc107; font-weight: bold;">' . $product['quantity'] . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table>';
        $html .= '<div style="background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0;">';
        $html .= '<strong>Recommendation:</strong> Consider restocking these items soon to maintain optimal inventory levels.';
        $html .= '</div>';
        $html .= $this->getEmailFooter();
        
        $this->mail->Body = $html;
    }
    
    private function setCriticalStockContent($products)
    {
        $this->mail->isHTML(true);
        $this->mail->Subject = 'CRITICAL: Stock Alert Summary - BS DIGIHUB';
        
        $html = $this->getEmailHeader();
        $html .= '<h2 style="color: #ddd; margin-bottom: 20px;">CRITICAL STOCK SUMMARY</h2>';
        $html .= '<p style="font-size: 16px; margin-bottom: 20px;">Daily stock alert summary for your inventory:</p>';
        
        // Separate products by stock level
        $outOfStock = array_filter($products, fn($p) => $p['quantity'] == 0);
        $lowStock = array_filter($products, fn($p) => $p['quantity'] > 0 && $p['quantity'] <= 5);
        
        if (!empty($outOfStock)) {
            $html .= '<h3 style="color: #ddd;">Out of Stock (' . count($outOfStock) . ' items)</h3>';
            $html .= $this->buildProductTable($outOfStock, '#ddd');
        }
        
        if (!empty($lowStock)) {
            $html .= '<h3 style="color: #ddd; margin-top: 30px;">Low Stock (' . count($lowStock) . ' items)</h3>';
            $html .= $this->buildProductTable($lowStock, '#ddd');
        }
        
        $html .= $this->getEmailFooter();
        $this->mail->Body = $html;
    }
    
    private function setGeneralStockContent($products)
    {
        $this->mail->isHTML(true);
        $this->mail->Subject = 'Inventory Status Report - BS DIGIHUB';
        
        $html = $this->getEmailHeader();
        $html .= '<h2 style="color: #0d6efd; margin-bottom: 20px;">INVENTORY STATUS REPORT</h2>';
        $html .= '<p style="font-size: 16px; margin-bottom: 20px;">Current inventory status update:</p>';
        $html .= $this->buildProductTable($products, '#0d6efd');
        $html .= $this->getEmailFooter();
        
        $this->mail->Body = $html;
    }
    
    private function buildProductTable($products, $headerColor)
    {
        $html = '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
        $html .= '<thead><tr style="background-color: ' . $headerColor . '; color: white;">';
        $html .= '<th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Product Name</th>';
        $html .= '<th style="padding: 12px; text-align: left; border: 1px solid #ddd;">EAN-13</th>';
        $html .= '<th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Category</th>';
        $html .= '<th style="padding: 12px; text-align: center; border: 1px solid #ddd;">Stock</th>';
        $html .= '</tr></thead><tbody>';
        
        foreach ($products as $product) {
            $html .= '<tr style="border-bottom: 1px solid #ddd;">';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . htmlspecialchars($product['product_name']) . '</td>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . htmlspecialchars($product['ean13']) . '</td>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . htmlspecialchars($product['category_name'] ?? 'Uncategorized') . '</td>';
            $html .= '<td style="padding: 10px; text-align: center; border: 1px solid #ddd; color: #ddd; font-weight: bold;">' . $product['quantity'] . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table>';
        return $html;
    }
    
    private function getEmailHeader()
    {
        return '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
            <div style="background-color: #f8f9fa; color: black; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
                <h1 style="margin: 0; font-size: 24px;">BS DIGIHUB</h1>
                <p style="margin: 5px 0 0 0; font-size: 14px;">Inventory Management System</p>
            </div>
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 0 0 8px 8px;">
        ';
    }
    
    private function getEmailFooter()
    {
        return '
                <hr style="margin: 30px 0; border: 1px solid #ddd;">
                <div style="text-align: center; color: #6c757d; font-size: 12px;">
                    <p>This is an automated message do not reply</p>
                    <p>Generated on: ' . date('F j, Y \a\t g:i A') . '</p>
                </div>
            </div>
        </div>
        ';
    }
    
    public function sendCustomEmail($to, $subject, $body, $isHTML = true)
    {
        try {
            $this->mail->clearAddresses();
            
            if (is_array($to)) {
                foreach ($to as $email => $name) {
                    if (is_numeric($email)) {
                        $this->mail->addAddress($name);
                    } else {
                        $this->mail->addAddress($email, $name);
                    }
                }
            } else {
                $this->mail->addAddress($to);
            }
            
            $this->mail->isHTML($isHTML);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            
            $this->mail->send();
            return true;
            
        } catch (Exception $e) {
            log_message('error', 'Custom email failed: ' . $e->getMessage());
            return false;
        }
    }
    
    public function sendDailySalesReport($date, $reportData, $recipients = [])
    {
        try {
            // Clear any previous recipients
            $this->mail->clearAddresses();
            
            // Add recipients
            foreach ($recipients as $email => $name) {
                if (is_numeric($email)) {
                    $this->mail->addAddress($name); // If array is indexed, treat value as email
                } else {
                    $this->mail->addAddress($email, $name);
                }
            }
            
            // Set email content
            $this->setDailySalesContent($date, $reportData);
            
            $this->mail->send();
            log_message('info', 'Daily sales report email sent successfully for date: ' . $date);
            return true;
            
        } catch (Exception $e) {
            log_message('error', 'Daily sales report email failed: ' . $e->getMessage());
            return false;
        }
    }
    
    private function setDailySalesContent($date, $reportData)
    {
        $this->mail->isHTML(true);
        $this->mail->Subject = 'Daily Sales Report - ' . date('F j, Y', strtotime($date)) . ' - BS DIGIHUB';
        
        $html = $this->getSalesEmailHeader($date);
        
        // Sales Summary
        $html .= '<h2 style="color: #0d6efd; margin-bottom: 20px;">DAILY SALES SUMMARY</h2>';
        $html .= $this->buildSalesSummaryCards($reportData['totals']);
        
        // Sales Details Table
        if (!empty($reportData['sales'])) {
            $html .= '<h3 style="color: #212529; margin: 30px 0 15px 0;">Sales Transactions </h3>';
            $html .= $this->buildSalesTable($reportData['sales']);
        } else {
            $html .= '<div style="background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 20px; text-align: center; margin: 20px 0; border-radius: 5px;">';
            $html .= '<h3 style="color: #6c757d; margin: 0;">No Sales Recorded</h3>';
            $html .= '<p style="color: #6c757d; margin: 10px 0 0 0;">No sales transactions were recorded for this date.</p>';
            $html .= '</div>';
        }
        
        // Stock Movements (if any)
        if (!empty($reportData['stock_in']) || !empty($reportData['stock_out'])) {
            $html .= '<h3 style="color: #212529; margin: 30px 0 15px 0;">Stock Movements</h3>';
            
            if (!empty($reportData['stock_in'])) {
                $html .= '<h4 style="color: #28a745;">Stock In (' . count($reportData['stock_in']) . ' items)</h4>';
                $html .= $this->buildStockMovementTable($reportData['stock_in'], 'in');
            }
            
            if (!empty($reportData['stock_out'])) {
                $html .= '<h4 style="color: #dc3545; margin-top: 20px;">Stock Out - Non-Sales (' . count($reportData['stock_out']) . ' items)</h4>';
                $html .= $this->buildStockMovementTable($reportData['stock_out'], 'out');
            }
        }
        
        $html .= $this->getEmailFooter();
        
        $this->mail->Body = $html;
    }
    
    private function getSalesEmailHeader($date)
    {
        return '
        <div style="font-family: Arial, sans-serif; max-width: 700px; margin: 0 auto; padding: 20px;">
            <div style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; padding: 25px; text-align: center; border-radius: 8px 8px 0 0;">
                <h1 style="margin: 0; font-size: 28px;">BS DIGIHUB</h1>
                <p style="margin: 8px 0 0 0; font-size: 16px; opacity: 0.9;">Daily Sales Report</p>
                <p style="margin: 5px 0 0 0; font-size: 18px; font-weight: bold;">' . date('F j, Y', strtotime($date)) . '</p>
            </div>
            <div style="background-color: #ffffff; padding: 25px; border: 1px solid #e9ecef; border-radius: 0 0 8px 8px;">
        ';
    }
    
    private function buildSalesSummaryCards($totals)
    {
        $html = '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin: 20px 0;">';
        
        // Revenue Card
        $html .= '<div style="background: #dfdfdf; color: #333; padding: 20px; border-radius: 8px; text-align: center;">';
        $html .= '<h4 style="margin: 0 0 8px 0; font-size: 14px; opacity: 0.9;">Total Revenue</h4>';
        $html .= '<p style="margin: 0; font-size: 24px; font-weight: bold;">₱' . number_format($totals['revenue'], 2) . '</p>';
        $html .= '</div>';
        
        // Items Sold Card
        $html .= '<div style="background: #dfdfdf; color: #333; padding: 20px; border-radius: 8px; text-align: center;">';
        $html .= '<h4 style="margin: 0 0 8px 0; font-size: 14px; opacity: 0.9;">Items Sold</h4>';
        $html .= '<p style="margin: 0; font-size: 24px; font-weight: bold;">' . number_format($totals['quantity']) . '</p>';
        $html .= '</div>';
        
        // Transactions Card
        $html .= '<div style="background: #dfdfdf; color: #333; padding: 20px; border-radius: 8px; text-align: center;">';
        $html .= '<h4 style="margin: 0 0 8px 0; font-size: 14px; opacity: 0.9;">Transactions</h4>';
        $html .= '<p style="margin: 0; font-size: 24px; font-weight: bold;">' . number_format($totals['transactions']) . '</p>';
        $html .= '</div>';
        
        // Profit Card
        $html .= '<div style="background: #dfdfdf; color: #333; padding: 20px; border-radius: 8px; text-align: center;">';
        $html .= '<h4 style="margin: 0 0 8px 0; font-size: 14px; opacity: 0.8;">Est. Profit</h4>';
        $html .= '<p style="margin: 0; font-size: 24px; font-weight: bold;">₱' . number_format($totals['profit'], 2) . '</p>';
        $html .= '</div>';
        
        $html .= '</div>';
        return $html;
    }
    
    private function buildSalesTable($sales)
    {
        $html = '<table style="width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 14px;">';
        $html .= '<thead><tr style="background-color: #f8f9fa; color: #495057;">';
        $html .= '<th style="padding: 12px 8px; text-align: left; border: 1px solid #dee2e6;">Time</th>';
        $html .= '<th style="padding: 12px 8px; text-align: left; border: 1px solid #dee2e6;">Product</th>';
        $html .= '<th style="padding: 12px 8px; text-align: center; border: 1px solid #dee2e6;">Qty</th>';
        $html .= '<th style="padding: 12px 8px; text-align: right; border: 1px solid #dee2e6;">Amount</th>';
        $html .= '</tr></thead><tbody>';
        
        foreach ($sales as $sale) {
            $html .= '<tr style="border-bottom: 1px solid #dee2e6;">';
            $html .= '<td style="padding: 10px 8px; border: 1px solid #dee2e6;">' . date('H:i', strtotime($sale['date_sold'])) . '</td>';
            $html .= '<td style="padding: 10px 8px; border: 1px solid #dee2e6;">';
            $html .= '<strong>' . htmlspecialchars($sale['product_name']) . '</strong><br>';
            $html .= '<small style="color: #6c757d;">' . htmlspecialchars($sale['ean13']) . '</small>';
            $html .= '</td>';
            $html .= '<td style="padding: 10px 8px; text-align: center; border: 1px solid #dee2e6;">' . $sale['quantity'] . '</td>';
            $html .= '<td style="padding: 10px 8px; text-align: right; border: 1px solid #dee2e6; color: #28a745; font-weight: bold;">₱' . number_format($sale['total_amount'], 2) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table>';
        return $html;
    }
    
    private function buildStockMovementTable($movements, $type)
    {
        $html = '<table style="width: 100%; border-collapse: collapse; margin: 10px 0; font-size: 13px;">';
        $html .= '<thead><tr style="background-color: #f8f9fa;">';
        $html .= '<th style="padding: 8px; text-align: left; border: 1px solid #dee2e6;">Time</th>';
        $html .= '<th style="padding: 8px; text-align: left; border: 1px solid #dee2e6;">Product</th>';
        $html .= '<th style="padding: 8px; text-align: center; border: 1px solid #dee2e6;">Qty</th>';
        
        if ($type === 'out') {
            $html .= '<th style="padding: 8px; text-align: center; border: 1px solid #dee2e6;">Reason</th>';
        }
        
        $html .= '</tr></thead><tbody>';
        
        foreach ($movements as $movement) {
            $html .= '<tr>';
            
            if ($type === 'in') {
                $html .= '<td style="padding: 8px; border: 1px solid #dee2e6;">' . date('H:i', strtotime($movement['date_received'])) . '</td>';
            } else {
                $html .= '<td style="padding: 8px; border: 1px solid #dee2e6;">' . date('H:i', strtotime($movement['date_out'])) . '</td>';
            }
            
            $html .= '<td style="padding: 8px; border: 1px solid #dee2e6;">' . htmlspecialchars($movement['product_name']) . '</td>';
            $html .= '<td style="padding: 8px; text-align: center; border: 1px solid #dee2e6;">' . $movement['quantity'] . '</td>';
            
            if ($type === 'out') {
                $html .= '<td style="padding: 8px; text-align: center; border: 1px solid #dee2e6;">' . htmlspecialchars($movement['reason']) . '</td>';
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table>';
        return $html;
    }
}