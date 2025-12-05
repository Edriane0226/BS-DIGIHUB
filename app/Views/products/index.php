<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Products</h1>
        <p class="text-muted">Search Products</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary btn-sm" onclick="refreshProducts()">
            <i class="bi bi-arrow-clockwise"></i> Refresh
        </button>
        <a href="<?= site_url('/products/stock-in') ?>" class="btn btn-success">
            <i class="bi bi-box-arrow-in-right"></i> Stock In
        </a>
        <a href="<?= site_url('/products/stock-out') ?>" class="btn btn-danger">
            <i class="bi bi-box-arrow-right"></i> Stock Out
        </a>
    </div>
</div>

<!-- Products Summary Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="h4 mb-0"><?= $stats['total'] ?></div>
                    <div class="small">Total Products</div>
                </div>
                <div class="ms-3">
                    <i class="bi bi-box-seam display-6"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="h4 mb-0"><?= $stats['in_stock'] ?></div>
                    <div class="small">In Stock</div>
                </div>
                <div class="ms-3">
                    <i class="bi bi-check-circle display-6"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="h4 mb-0"><?= $stats['low_stock'] ?></div>
                    <div class="small">Low Stock</div>
                </div>
                <div class="ms-3">
                    <i class="bi bi-exclamation-triangle display-6"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="h4 mb-0"><?= $stats['out_of_stock'] ?></div>
                    <div class="small">Out of Stock</div>
                </div>
                <div class="ms-3">
                    <i class="bi bi-x-circle display-6"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small">Search Products</label>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search by name, Barcode..." 
                           id="searchInput" name="search" value="<?= isset($filters['search']) ? esc($filters['search']) : '' ?>">
                    <button class="btn btn-outline-secondary" type="button" onclick="performSearch()">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Category</label>
                <select class="form-select" id="categoryFilter" name="category">
                    <option value="">All Categories</option>
                    <?php if (isset($categories)): ?>
                        <?php foreach($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= isset($filters['category']) && $filters['category'] == $category['id'] ? 'selected' : '' ?>>
                                <?= esc($category['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Type</label>
                <select class="form-select" id="typeFilter" name="type">
                    <option value="">All Types</option>
                    <option value="digital" <?= isset($filters['type']) && $filters['type'] == 'digital' ? 'selected' : '' ?>>Digital</option>
                    <option value="physical" <?= isset($filters['type']) && $filters['type'] == 'physical' ? 'selected' : '' ?>>Physical</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Stock Status</label>
                <select class="form-select" id="stockFilter" name="stock">
                    <option value="">All Stock</option>
                    <option value="in-stock" <?= isset($filters['stock']) && $filters['stock'] == 'in-stock' ? 'selected' : '' ?>>In Stock</option>
                    <option value="low-stock" <?= isset($filters['stock']) && $filters['stock'] == 'low-stock' ? 'selected' : '' ?>>Low Stock</option>
                    <option value="out-of-stock" <?= isset($filters['stock']) && $filters['stock'] == 'out-of-stock' ? 'selected' : '' ?>>Out of Stock</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                    <i class="bi bi-x-circle"></i> Clear
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="card">
    <div class="card-header bg-black">
        <h5 class="card-title mb-0 ">
             Product Inventory
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($products)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="18%">Product Details</th>
                            <th width="12%">Category</th>
                            <th width="10%">Type</th>
                            <th width="12%">Barcode</th>
                            <th width="10%">Price</th>
                            <th width="8%">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $p): ?>
                        <tr class="product-row" data-product-id="<?= $p['id'] ?>" style="cursor: pointer;">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-semibold"><?= esc($p['product_name']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <?= esc($p['category_name'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= $p['product_type'] == 'digital' ? 'bg-success' : 'bg-warning' ?>">
                                    <i class="bi bi-<?= $p['product_type'] == 'digital' ? 'cloud' : 'box' ?>"></i>
                                    <?= ucfirst(esc($p['product_type'])) ?>
                                </span>
                            </td>
                            <td>
                                <code class="text-dark"><?= esc($p['ean13']) ?></code>
                            </td>
                            <td>
                                <span class="fw-semibold text-success">₱<?= number_format($p['price'], 2) ?></span>
                            </td>
                            <td>
                                <?php if ($p['quantity'] == 0): ?>
                                    <span class="badge bg-danger">Out of Stock</span>
                                <?php elseif ($p['quantity'] <= 5): ?>
                                    <span class="badge bg-warning"><?= $p['quantity'] ?> Low</span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?= $p['quantity'] ?></span>
                                <?php endif ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="card-footer bg-light border-top">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="text-muted small d-flex align-items-center">
                            <i class="bi bi-info-circle me-1"></i>
                            Showing <span class="fw-semibold mx-1"><?= count($products) ?></span> products
                            <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
                                <span class="badge bg-primary ms-2">
                                    Page <?= $pager->getCurrentPage() ?> of <?= $pager->getPageCount() ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
                            <div class="d-flex justify-content-end">
                                <?= $pager->links() ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Pagination Info -->
                <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="progress" style="height: 3px;">
                                <div class="progress-bar bg-primary" 
                                     style="width: <?= round(($pager->getCurrentPage() / $pager->getPageCount()) * 100) ?>%">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-box-seam display-1 text-muted"></i>
                <h4 class="mt-3">No Products Found</h4>
                <p class="text-muted">Start building your inventory by adding products.</p>
                <a href="<?= site_url('/products/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Your First Product
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Product Detail Modal -->
<div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="productDetailModalLabel">
                    Product Information
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="productDetailContent">
                    <!-- Content will be loaded here -->
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Loading product details...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentProductId = null;

function refreshProducts() {
    location.reload();
}

function clearFilters() {
    window.location.href = '<?= base_url('products') ?>';
}

function performSearch() {
    applyFilters();
}

function applyFilters() {
    const params = new URLSearchParams();
    
    const search = document.getElementById('searchInput').value.trim();
    const category = document.getElementById('categoryFilter').value;
    const type = document.getElementById('typeFilter').value;
    const stock = document.getElementById('stockFilter').value;
    
    if (search) params.set('search', search);
    if (category) params.set('category', category);
    if (type) params.set('type', type);
    if (stock) params.set('stock', stock);
    
    const url = '<?= base_url('products') ?>' + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
}

function editProduct(productId) {
    window.location.href = '<?= base_url('products/edit/') ?>' + productId;
}

function editCurrentProduct() {
    if (currentProductId) {
        editProduct(currentProductId);
    }
}

function stockInCurrentProduct() {
    if (currentProductId) {
        window.location.href = '<?= base_url('products/stock-in') ?>';
    }
}

function stockOutCurrentProduct() {
    if (currentProductId) {
        window.location.href = '<?= base_url('products/stock-out') ?>';
    }
}

function viewProductHistory() {
    if (currentProductId) {
        // Close current modal first
        const modal = bootstrap.Modal.getInstance(document.getElementById('productDetailModal'));
        if (modal) {
            modal.hide();
        }
        
        // Show alert for now (can be enhanced later)
        setTimeout(() => {
            alert('Product history feature coming soon!');
        }, 300);
    }
}

function showProductDetails(productId) {
    currentProductId = productId;
    const modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
    
    // Reset modal content
    document.getElementById('productDetailContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading product details...</p>
        </div>
    `;
    
    modal.show();
    
    // Try using window.location.origin to construct the URL
    const fetchUrl = `${window.location.origin}/DigiHub/products/getProductDetails/${productId}`;
    console.log('Fetching from URL:', fetchUrl);
    
    // Fetch product details with additional headers
    fetch(fetchUrl, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        credentials: 'same-origin'
    })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                displayProductDetails(data.product);
            } else {
                document.getElementById('productDetailContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Error loading product details: ${data.message || 'Unknown error'}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Fetch Error Details:', error);
            document.getElementById('productDetailContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Network Error:</strong> ${error.message}
                    <br><small>URL attempted: ${fetchUrl}</small>
                </div>
            `;
        });
}

function displayProductDetails(product) {
    console.log('Displaying product:', product);
    
    const shelfLocation = (product.shelf_id && product.loc_descrip)
        ? `<i class="bi bi-geo-alt-fill text-success me-1"></i><strong>${product.shelf_id}</strong> - ${product.loc_descrip}`
        : '<i class="bi bi-question-circle text-muted me-1"></i><em>Not assigned</em>';
    
    const stockBadge = product.quantity == 0 
        ? '<span class="badge bg-danger fs-6 px-3 py-2"><i class="bi bi-x-circle me-1"></i>Out of Stock</span>'
        : product.quantity <= 5 
        ? `<span class="badge bg-warning fs-6 px-3 py-2"><i class="bi bi-exclamation-triangle me-1"></i>${product.quantity} Low Stock</span>`
        : `<span class="badge bg-success fs-6 px-3 py-2">${product.quantity} In Stock</span>`;
    
    const productTypeIcon = product.product_type === 'digital' ? 'cloud-fill' : 'box-fill';
    const productTypeBadge = product.product_type === 'digital' ? 'bg-info' : 'bg-secondary';
    
    const totalValue = (parseFloat(product.price || 0) * parseInt(product.quantity || 0));
    
    const content = `
        <div class="container-fluid p-4">
            <!-- Product Header -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <h3 class="text-primary mb-2">
                        ${product.product_name || 'Unknown Product'}
                    </h3>
                    <p class="text-muted mb-3">
                        <span class="badge ${productTypeBadge} me-2">
                            <i class="bi bi-${product.product_type === 'digital' ? 'cloud' : 'box'} me-1"></i>
                            ${product.product_type ? product.product_type.charAt(0).toUpperCase() + product.product_type.slice(1) : 'N/A'} Product
                        </span>
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-hash me-1"></i>ID: ${product.id || 'N/A'}
                        </span>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="mb-2">${stockBadge}</div>
                    <div class="display-6 text-success fw-bold">₱${product.price ? parseFloat(product.price).toLocaleString('en-PH', {minimumFractionDigits: 2}) : '0.00'}</div>
                    <small class="text-muted">per unit</small>
                </div>
            </div>

            <!-- Product Information Cards -->
            <div class="row g-4">
                <!-- Basic Information -->
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-light border-0">
                            <h6 class="mb-0 text-primary">
                                <i class="bi bi-info-circle-fill me-2"></i>Basic Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-sm-12">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Product Name</label>
                                    <div class="border-bottom pb-2">${product.product_name || 'N/A'}</div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">EAN-13 Barcode</label>
                                    <div class="border-bottom pb-2">
                                        <code class="text-dark bg-light px-2 py-1 rounded">${product.ean13 || 'N/A'}</code>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Category</label>
                                    <div class="border-bottom pb-2">
                                        <span class="badge bg-info">${product.category_name || 'Uncategorized'}</span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Shelf Location</label>
                                    <div class="border-bottom pb-2">${shelfLocation}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock & Financial Information -->
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-light border-0">
                            <h6 class="mb-0 text-success">
                                <i class="bi bi-graph-up-arrow me-2"></i>Stock & Financial
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Current Stock</label>
                                    <div class="border-bottom pb-2">
                                        <span class="fs-4 fw-bold ${product.quantity == 0 ? 'text-danger' : product.quantity <= 5 ? 'text-warning' : 'text-success'}">
                                            ${product.quantity || 0}
                                        </span>
                                        <small class="text-muted ms-1">units</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Unit Price</label>
                                    <div class="border-bottom pb-2">
                                        <span class="fs-4 fw-bold text-success">
                                            ₱${product.price ? parseFloat(product.price).toLocaleString('en-PH', {minimumFractionDigits: 2}) : '0.00'}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Total Inventory Value</label>
                                    <div class="border-bottom pb-2">
                                        <span class="fs-3 fw-bold text-primary">
                                            ₱${totalValue.toLocaleString('en-PH', {minimumFractionDigits: 2})}
                                        </span>
                                        <small class="text-muted ms-2">(${product.quantity || 0} units × ₱${product.price ? parseFloat(product.price).toLocaleString('en-PH', {minimumFractionDigits: 2}) : '0.00'})</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-lightning-fill me-2"></i>Quick Actions
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-outline-success btn-sm" onclick="stockInCurrentProduct()">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>Add Stock
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="stockOutCurrentProduct()">
                                    <i class="bi bi-box-arrow-right me-1"></i>Remove Stock
                                </button>
                                <button class="btn btn-outline-warning btn-sm" onclick="editCurrentProduct()">
                                    <i class="bi bi-pencil me-1"></i>Edit Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('productDetailContent').innerHTML = content;
}

// Auto-apply filters when dropdowns change
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Add event listeners for filter changes
    document.getElementById('categoryFilter').addEventListener('change', applyFilters);
    document.getElementById('typeFilter').addEventListener('change', applyFilters);
    document.getElementById('stockFilter').addEventListener('change', applyFilters);
    
    // Search on Enter key
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
    
    // Add click event listeners to product rows
    document.querySelectorAll('.product-row').forEach(row => {
        row.addEventListener('click', function() {
            const productId = this.dataset.productId;
            showProductDetails(productId);
        });
        
        // Add hover effect
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Add hover animations to pagination links
    const pageLinks = document.querySelectorAll('.pagination .page-link');
    pageLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            if (!this.closest('.page-item').classList.contains('disabled') && 
                !this.closest('.page-item').classList.contains('active')) {
                this.style.transform = 'translateY(-1px)';
                this.style.boxShadow = '0 4px 8px rgba(0,123,255,0.2)';
                this.style.transition = 'all 0.2s ease';
            }
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
});
</script>

<?= $this->endSection() ?>