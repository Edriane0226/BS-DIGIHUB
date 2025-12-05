<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Dashboard Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Dashboard</h1>
        <p class="text-muted">DigiHub Inventory Management System</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary btn-sm" onclick="refreshDashboard()">
            <i class="bi bi-arrow-clockwise"></i> Refresh
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="h4 mb-0"><?= isset($stats['total_products']) ? $stats['total_products'] : '0' ?></div>
                    <div class="small">Total Products</div>
                </div>
                <div class="ms-3">
                    <i class="bi bi-box-seam display-6"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="h4 mb-0"><?= isset($stats['total_categories']) ? $stats['total_categories'] : '0' ?></div>
                    <div class="small">Categories</div>
                </div>
                <div class="ms-3">
                    <i class="bi bi-tags display-6"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-12 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="h4 mb-0"><?= isset($stats['low_stock_items']) ? $stats['low_stock_items'] : '0' ?></div>
                    <div class="small">Low Stock Alerts</div>
                </div>
                <div class="ms-3">
                    <i class="bi bi-exclamation-triangle display-6"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-black text-white">
                <h5 class="card-title mb-0">
                    Shorcuts
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <a href="<?= base_url('products/stock-in') ?>" class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3 text-decoration-none">
                            <i class="bi bi-box-arrow-in-right fs-2 mb-2"></i>
                            <span class="fw-bold">Stock In</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="<?= base_url('products/stock-out') ?>" class="btn btn-danger w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3 text-decoration-none">
                            <i class="bi bi-box-arrow-right fs-2 mb-2"></i>
                            <span class="fw-bold">Stock Out</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="<?= base_url('products') ?>" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3 text-decoration-none">
                            <i class="bi bi-box-seam fs-2 mb-2"></i>
                            <span class="fw-bold">Manage Products</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="<?= base_url('reports') ?>" class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3 text-decoration-none">
                            <i class="bi bi-graph-up fs-2 mb-2"></i>
                            <span class="fw-bold">View Reports</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & Overview -->
<div class="row">
    <!-- Recent Products -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-black text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                     Newly Added Products
                </h5>
                <a href="<?= base_url('products') ?>" class="btn btn-sm btn-outline-light">View All</a>
            </div>
            <div class="card-body">
                <?php if (isset($recent_products) && !empty($recent_products)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Stock Status</th>
                                    <th>Date Added</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_products as $product): ?>
                                    <tr>
                                        <td>
                                            <h6 class="mb-0"><?= esc($product['product_name']) ?></h6>
                                            <small class="text-muted">EAN-13: <?= esc($product['ean13'] ?? 'N/A') ?></small>
                                        </td>
                                        <td><?= esc($product['category_name'] ?? 'Uncategorized') ?></td>
                                        <td>
                                            <?php 
                                            $quantity = $product['quantity'] ?? 0;
                                            if ($quantity > 5): ?>
                                                <span class="badge bg-success">In Stock</span>
                                            <?php elseif ($quantity > 0): ?>
                                                <span class="badge bg-warning">Low Stock</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Out of Stock</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= date('M d, Y', strtotime($product['created_at'] ?? 'now')) ?></small>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-box display-1 text-muted"></i>
                        <h5 class="mt-3 mb-2">No products added yet</h5>
                        <p class="mb-3">Start by adding your first product to the inventory</p>
                        <a href="<?= base_url('products/create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add First Product
                        </a>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<script>
function refreshDashboard() {
    location.reload();
}
</script>

<?= $this->endSection() ?>
