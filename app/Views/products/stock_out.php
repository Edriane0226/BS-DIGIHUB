<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Stock Out</h1>
        <p class="text-muted">Remove items from inventory with barcode scanning</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('/products') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Products
        </a>
        <a href="<?= site_url('/products/stock-in') ?>" class="btn btn-outline-success">
            <i class="bi bi-box-arrow-in-right"></i> Stock In
        </a>
    </div>
</div>

<!-- Barcode Scanner Section -->
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="bi bi-upc-scan"></i> Remove Stock
                </h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= site_url('/products/stock-out') ?>" id="stockOutForm">
                    <?= csrf_field() ?>
                    
                    <div class="row align-items-end mb-3">
                        <div class="col-md-8">
                            <label for="barcode" class="form-label required">Barcode/EAN-13</label>
                            <input type="text" 
                                   name="barcode" 
                                   id="barcode"
                                   class="form-control form-control-lg" 
                                   placeholder="Scan or enter barcode or EAN-13"
                                   required
                                   autofocus>
                        </div>
                        <div class="col-md-4">
                            <button type="button" id="lookupBtn" class="btn btn-info w-100">
                                <i class="bi bi-search"></i> Lookup Product
                            </button>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label for="quantity" class="form-label required">Quantity to Remove</label>
                            <input type="number" 
                                   name="quantity" 
                                   id="quantity"
                                   class="form-control" 
                                   min="1"
                                   value="1"
                                   required>
                        </div>
                        <div class="col-md-6">
                            <label for="reason" class="form-label">Reason</label>
                            <select name="reason" id="reason" class="form-select">
                                <option value="Sale">Sale</option>
                                <option value="Damage">Damaged/Defective</option>
                                <option value="Return">Return to Supplier</option>
                                <option value="Loss">Lost/Stolen</option>
                                <option value="Transfer">Transfer</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Product Details Section -->
                    <div id="productDetailsSection" class="mt-4" style="display: none;">
                        <hr>
                        <div id="productInfo" class="alert alert-info alert-permanent">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Product:</strong> <span id="productName"></span><br>
                                    <strong>Category:</strong> <span id="productCategory"></span><br>
                                    <strong>EAN-13:</strong> <span id="productEAN13"></span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Current Stock:</strong> <span id="productStock" class="badge bg-info"></span><br>
                                    <strong>Price:</strong> ₱<span id="productPrice"></span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    After removal: <strong><span id="remainingStock"></span> units</strong>
                                </small>
                            </div>
                        </div>
                        
                        <div id="errorMessage" class="alert alert-danger" style="display: none;">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                        <button type="submit" id="submitBtn" class="btn btn-danger" disabled>
                            <i class="bi bi-dash-lg"></i> Remove Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Instructions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Instructions</h6>
            </div>
            <div class="card-body">
                <ol class="mb-0">
                    <li><strong>Scan or enter</strong> the product barcode or EAN-13</li>
                    <li><strong>Click lookup</strong> to verify product</li>
                    <li><strong>Enter quantity</strong> to remove</li>
                    <li><strong>Select reason</strong> for removal</li>
                    <li><strong>Click "Remove Stock"</strong> to complete</li>
                </ol>
                
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-exclamation-triangle"></i> 
                    <strong>Warning:</strong> Stock removal cannot be undone. Please verify quantities before proceeding.
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Today's Activity</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">Items Removed</span>
                    <span class="badge bg-danger" id="itemsRemoved">0</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">Transactions</span>
                    <span class="badge bg-info" id="transactionCount">0</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Last Scan</span>
                    <small class="text-muted" id="lastScan">--:--</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.getElementById('barcode');
    const lookupBtn = document.getElementById('lookupBtn');
    const quantityInput = document.getElementById('quantity');
    const productDetailsSection = document.getElementById('productDetailsSection');
    const productInfo = document.getElementById('productInfo');
    const errorMessage = document.getElementById('errorMessage');
    const submitBtn = document.getElementById('submitBtn');
    
    let currentProduct = null;
    
    // Initialize quick stats
    let todayStats = {
        itemsRemoved: parseInt(localStorage.getItem('itemsRemovedToday') || '0'),
        transactions: parseInt(localStorage.getItem('transactionsToday') || '0'),
        lastScan: localStorage.getItem('lastStockOutScan') || '--:--'
    };
    
    updateQuickStats();
    
    // Auto-lookup on Enter key
    barcodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            lookupProduct();
        }
    });
    
    // Lookup button click
    lookupBtn.addEventListener('click', lookupProduct);
    
    // Form submission tracking
    document.getElementById('stockOutForm').addEventListener('submit', function(e) {
        const quantity = document.getElementById('quantity').value;
        if (quantity && parseInt(quantity) > 0 && currentProduct) {
            // Update stats when form is submitted (will be processed after page reload)
            localStorage.setItem('pendingItemsRemove', quantity);
        }
    });
    
    // Check for pending stats update after page reload
    const pendingRemove = localStorage.getItem('pendingItemsRemove');
    if (pendingRemove) {
        updateRemovalStats(pendingRemove);
        localStorage.removeItem('pendingItemsRemove');
    }
    
    // Update remaining stock when quantity changes
    quantityInput.addEventListener('input', updateRemainingStock);
    
    function lookupProduct() {
        const barcode = barcodeInput.value.trim();
        if (!barcode) {
            alert('Please enter a barcode/EAN-13');
            return;
        }
        
        // Show loading state
        lookupBtn.disabled = true;
        lookupBtn.innerHTML = '<i class="spinner-border spinner-border-sm"></i> Looking up...';
        
        const url = `<?= base_url('index.php/products/barcode') ?>?barcode=${encodeURIComponent(barcode)}`;
        console.log('Lookup URL:', url);
        
        // Make AJAX call to lookup product
        fetch(url)
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                productDetailsSection.style.display = 'block';
                errorMessage.style.display = 'none';
                
                if (data.success) {
                    currentProduct = data.product;
                    showProductInfo(data.product);
                    submitBtn.disabled = false;
                    
                    // Update stats for successful lookup
                    updateLookupStats();
                } else {
                    showError('Product not found with this barcode');
                    currentProduct = null;
                    submitBtn.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Error looking up product: ' + error.message);
                currentProduct = null;
                submitBtn.disabled = true;
            })
            .finally(() => {
                lookupBtn.disabled = false;
                lookupBtn.innerHTML = '<i class="bi bi-search"></i> Lookup Product';
            });
    }
    
    function showProductInfo(product) {
        productInfo.style.display = 'block';
        
        document.getElementById('productName').textContent = product.product_name;
        document.getElementById('productCategory').textContent = product.category_name || 'Uncategorized';
        document.getElementById('productEAN13').textContent = product.ean13 || 'Not set';
        document.getElementById('productStock').textContent = product.quantity + ' units';
        document.getElementById('productPrice').textContent = parseFloat(product.price).toFixed(2);
        
        updateRemainingStock();
    }
    
    function updateRemainingStock() {
        if (!currentProduct) return;
        
        const removeQty = parseInt(quantityInput.value) || 0;
        const remaining = currentProduct.quantity - removeQty;
        const remainingElement = document.getElementById('remainingStock');
        
        if (remainingElement) {
            remainingElement.textContent = Math.max(0, remaining);
            
            // Update styling based on remaining stock
            if (remaining < 0) {
                remainingElement.style.color = 'red';
                quantityInput.classList.add('is-invalid');
                submitBtn.disabled = true;
            } else if (remaining === 0) {
                remainingElement.style.color = 'orange';
                quantityInput.classList.remove('is-invalid');
                submitBtn.disabled = false;
            } else {
                remainingElement.style.color = 'green';
                quantityInput.classList.remove('is-invalid');
                submitBtn.disabled = false;
            }
        }
    }
    
    function showError(message) {
        productInfo.style.display = 'none';
        errorMessage.style.display = 'block';
        errorMessage.innerHTML = '<i class="bi bi-exclamation-triangle"></i> ' + message;
    }
    
    // Reset form function
    window.resetForm = function() {
        // Reset only the input fields, keep product information visible
        document.getElementById('barcode').value = '';
        document.getElementById('quantity').value = '1';
        document.getElementById('reason').value = '';
        
        // Hide error message but keep product info visible
        errorMessage.style.display = 'none';
        submitBtn.disabled = currentProduct ? false : true;
        quantityInput.classList.remove('is-invalid');
        
        // Keep product details visible if they were shown
        // currentProduct and productInfo remain as they are
        
        barcodeInput.focus();
    };
    
    function updateQuickStats() {
        document.getElementById('itemsRemoved').textContent = todayStats.itemsRemoved;
        document.getElementById('transactionCount').textContent = todayStats.transactions;
        document.getElementById('lastScan').textContent = todayStats.lastScan;
    }
    
    function updateLookupStats() {
        const now = new Date();
        todayStats.lastScan = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: false
        });
        
        // Save to localStorage
        localStorage.setItem('lastStockOutScan', todayStats.lastScan);
        
        updateQuickStats();
    }
    
    function updateRemovalStats(quantity) {
        todayStats.itemsRemoved += parseInt(quantity);
        todayStats.transactions++;
        
        // Save to localStorage
        localStorage.setItem('itemsRemovedToday', todayStats.itemsRemoved);
        localStorage.setItem('transactionsToday', todayStats.transactions);
        
        updateQuickStats();
    }
});
</script>

<?= $this->endSection() ?>