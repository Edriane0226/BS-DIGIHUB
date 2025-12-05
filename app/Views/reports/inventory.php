<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Inventory Report</h1>
        <p class="text-muted">Comprehensive inventory analysis and stock management insights</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('/reports') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Reports
        </a>
        <a href="<?= site_url('/reports/export/inventory') ?>" class="btn btn-success">
            <i class="bi bi-download"></i> Export CSV
        </a>
    </div>
</div>

<!-- Summary Statistics -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="bi bi-box-seam display-4 mb-2"></i>
                <h3><?= number_format($stats['total_products']) ?></h3>
                <p class="mb-0">Total Products</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="bi bi-cash-coin display-4 mb-2"></i>
                <h3>$<?= number_format($stats['total_value'], 2) ?></h3>
                <p class="mb-0">Total Value</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="bi bi-boxes display-4 mb-2"></i>
                <h3><?= number_format($stats['total_quantity']) ?></h3>
                <p class="mb-0">Total Quantity</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="bi bi-exclamation-triangle display-4 mb-2"></i>
                <h3><?= $stats['out_of_stock'] + $stats['low_stock'] ?></h3>
                <p class="mb-0">Needs Attention</p>
            </div>
        </div>
    </div>
</div>

<!-- Stock Status Breakdown -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-check-circle text-success"></i> In Stock
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="display-6 text-success"><?= $stats['in_stock'] ?></div>
                <p class="text-muted mb-0">Products available</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-exclamation-triangle text-warning"></i> Low Stock
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="display-6 text-warning"><?= $stats['low_stock'] ?></div>
                <p class="text-muted mb-0">Need reordering</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-x-circle text-danger"></i> Out of Stock
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="display-6 text-danger"><?= $stats['out_of_stock'] ?></div>
                <p class="text-muted mb-0">Urgent restock</p>
            </div>
        </div>
    </div>
</div>

<!-- Category Breakdown -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pie-chart"></i> Inventory by Category
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Products Count</th>
                                <th>Total Quantity</th>
                                <th>Total Value</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categoryStats as $category => $stat): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-primary"><?= esc($category) ?></span>
                                </td>
                                <td><?= number_format($stat['count']) ?></td>
                                <td><?= number_format($stat['quantity']) ?></td>
                                <td class="text-success">$<?= number_format($stat['value'], 2) ?></td>
                                <td>
                                    <?php $percentage = $stats['total_value'] > 0 ? ($stat['value'] / $stats['total_value']) * 100 : 0; ?>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                            <div class="progress-bar" style="width: <?= $percentage ?>%"></div>
                                        </div>
                                        <span class="small"><?= number_format($percentage, 1) ?>%</span>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Out Summary by Reason -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up"></i> Stock Movements - Outbound Summary
                </h5>
            </div>
            <div class="card-body">
                <?php if (isset($stockOuts) && !empty($stockOuts)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Reason</th>
                                    <th>Total Quantity</th>
                                    <th>Transactions</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalStockOut = array_sum(array_column($stockOuts, 'total_quantity'));
                                foreach ($stockOuts as $stockOut): 
                                ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-<?= $stockOut['reason'] === 'Sale' ? 'success' : ($stockOut['reason'] === 'Damage' ? 'danger' : 'secondary') ?>">
                                            <?= esc($stockOut['reason']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?= number_format($stockOut['total_quantity']) ?></strong> units
                                    </td>
                                    <td>
                                        <?= number_format($stockOut['transaction_count']) ?> transactions
                                    </td>
                                    <td>
                                        <?php $percentage = $totalStockOut > 0 ? ($stockOut['total_quantity'] / $totalStockOut) * 100 : 0; ?>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                <div class="progress-bar bg-<?= $stockOut['reason'] === 'Sale' ? 'success' : ($stockOut['reason'] === 'Damage' ? 'danger' : 'secondary') ?>" 
                                                     style="width: <?= $percentage ?>%"></div>
                                            </div>
                                            <span class="small"><?= number_format($percentage, 1) ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th>Total</th>
                                    <th><strong><?= number_format($totalStockOut) ?></strong> units</th>
                                    <th><?= number_format(array_sum(array_column($stockOuts, 'transaction_count'))) ?> transactions</th>
                                    <th>100%</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle"></i> No stock out transactions recorded yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Top Products by Value -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-trophy"></i> Top 10 Products by Inventory Value
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Rank</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Total Value</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topProducts as $index => $product): ?>
                            <tr>
                                <td>
                                    <span class="badge <?= $index < 3 ? 'bg-warning' : 'bg-secondary' ?>">
                                        <?= $index + 1 ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 35px; height: 35px;">
                                                <i class="bi bi-box text-muted"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= esc($product['product_name']) ?></div>
                                            <div class="small text-muted">EAN-13: <?= esc($product['ean13']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?= esc($product['category_name'] ?? 'N/A') ?></span>
                                </td>
                                <td class="text-success fw-semibold">$<?= number_format($product['price'], 2) ?></td>
                                <td><?= number_format($product['quantity']) ?></td>
                                <td class="fw-semibold">$<?= number_format($product['price'] * $product['quantity'], 2) ?></td>
                                <td>
                                    <?php if ($product['quantity'] == 0): ?>
                                        <span class="badge bg-danger">Out of Stock</span>
                                    <?php elseif ($product['quantity'] <= 5): ?>
                                        <span class="badge bg-warning">Low Stock</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">In Stock</span>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Items -->
<div class="row">
    <div class="col-md-6">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="bi bi-exclamation-triangle"></i> Immediate Actions Required
                </h6>
            </div>
            <div class="card-body">
                <?php
                $urgentItems = array_filter($products, fn($p) => $p['quantity'] <= 5);
                usort($urgentItems, fn($a, $b) => $a['quantity'] <=> $b['quantity']);
                $urgentItems = array_slice($urgentItems, 0, 5);
                ?>
                
                <?php if (!empty($urgentItems)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($urgentItems as $item): ?>
                        <div class="list-group-item px-0 py-2 border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold small"><?= esc($item['product_name']) ?></div>
                                    <div class="text-muted small">EAN-13: <?= esc($item['ean13']) ?></div>
                                </div>
                                <div class="text-end">
                                    <?php if ($item['quantity'] == 0): ?>
                                        <span class="badge bg-danger">OUT</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning"><?= $item['quantity'] ?></span>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-3">
                        <i class="bi bi-check-circle text-success display-6"></i>
                        <p class="mt-2 mb-0">No urgent actions required</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb"></i> Recommendations
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-primary me-2"></i>
                        Set reorder points for products with frequent stockouts
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-primary me-2"></i>
                        Consider bulk ordering for high-value products
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-primary me-2"></i>
                        Review slow-moving inventory in low-performing categories
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-primary me-2"></i>
                        Implement automated alerts for low stock items
                    </li>
                    <li class="mb-0">
                        <i class="bi bi-arrow-right text-primary me-2"></i>
                        Schedule regular inventory audits
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>