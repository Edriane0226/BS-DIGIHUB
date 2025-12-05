<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Sales Report - <?= date('F j, Y', strtotime($date)) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }
        .header .date {
            color: #666;
            font-size: 18px;
            margin-top: 5px;
        }
        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-card {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .summary-card.revenue {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        }
        .summary-card.profit {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #333;
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .summary-card .value {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #007bff;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .amount {
            color: #28a745;
            font-weight: bold;
        }
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>BS DIGIHUB</h1>
            <div class="date">Daily Sales Report - <?= date('F j, Y', strtotime($date)) ?></div>
        </div>

        <!-- Summary Cards -->
        <div class="summary">
            <div class="summary-card revenue">
                <h3>Total Revenue</h3>
                <p class="value">₱<?= number_format($totals['revenue'], 2) ?></p>
            </div>
            <div class="summary-card">
                <h3>Items Sold</h3>
                <p class="value"><?= number_format($totals['quantity']) ?></p>
            </div>
            <div class="summary-card">
                <h3>Transactions</h3>
                <p class="value"><?= number_format($totals['transactions']) ?></p>
            </div>
            <div class="summary-card profit">
                <h3>Estimated Profit</h3>
                <p class="value">₱<?= number_format($totals['profit'], 2) ?></p>
            </div>
        </div>

        <?php if ($totals['revenue'] == 0): ?>
            <div class="alert alert-info">
                <strong>No sales recorded for this date.</strong> 
                This might indicate a slow sales day or that the data hasn't been updated yet.
            </div>
        <?php endif; ?>

        <?php if (!empty($sales)): ?>
        <!-- Sales Details -->
        <div class="section">
            <h2>Sales Transactions</h2>
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sales as $sale): ?>
                    <tr>
                        <td><?= date('H:i:s', strtotime($sale['date_sold'])) ?></td>
                        <td>
                            <strong><?= esc($sale['product_name']) ?></strong>
                            <br><small><?= esc($sale['ean13']) ?></small>
                        </td>
                        <td><?= esc($sale['category_name'] ?? 'Uncategorized') ?></td>
                        <td><?= number_format($sale['quantity']) ?></td>
                        <td>₱<?= number_format($sale['total_amount'] / $sale['quantity'], 2) ?></td>
                        <td class="amount">₱<?= number_format($sale['total_amount'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <!-- Stock Movements -->
        <?php if (!empty($stock_in) || !empty($stock_out)): ?>
        <div class="section">
            <h2>Stock Movements</h2>
            
            <?php if (!empty($stock_in)): ?>
            <h3>Stock In</h3>
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stock_in as $item): ?>
                    <tr>
                        <td><?= date('H:i:s', strtotime($item['date_received'])) ?></td>
                        <td><?= esc($item['product_name']) ?></td>
                        <td><?= number_format($item['quantity']) ?></td>
                        <td><?= esc($item['remarks'] ?? 'N/A') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>

            <?php if (!empty($stock_out)): ?>
            <h3>Stock Out (Non-Sales)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stock_out as $item): ?>
                    <tr>
                        <td><?= date('H:i:s', strtotime($item['date_out'])) ?></td>
                        <td><?= esc($item['product_name']) ?></td>
                        <td><?= number_format($item['quantity']) ?></td>
                        <td><?= esc($item['reason']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if ($totals['revenue'] > 0): ?>
        <div class="alert alert-info">
            <strong>Performance Note:</strong> 
            <?php 
            $profitMargin = ($totals['profit'] / $totals['revenue']) * 100;
            if ($profitMargin > 30): ?>
                Excellent profit margin of <?= number_format($profitMargin, 1) ?>%! 
            <?php elseif ($profitMargin > 20): ?>
                Good profit margin of <?= number_format($profitMargin, 1) ?>%. 
            <?php elseif ($profitMargin > 10): ?>
                Moderate profit margin of <?= number_format($profitMargin, 1) ?>%. Consider reviewing pricing strategies.
            <?php else: ?>
                Low profit margin of <?= number_format($profitMargin, 1) ?>%. Review costs and pricing.
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="footer">
            <p>This report was automatically generated by BS DIGIHUB Inventory Management System</p>
            <p>Generated on <?= date('Y-m-d H:i:s') ?> | For support, contact your system administrator</p>
        </div>
    </div>
</body>
</html>