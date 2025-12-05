<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Add Product</h1>
        <p class="text-muted">Add a new product to your inventory</p>
    </div>
    <a href="<?= site_url('/products') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Products
    </a>
</div>

<!-- Form Card -->
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-box-seam"></i> Product Information
                </h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= site_url('/products/store') ?>" id="productForm">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <!-- Basic Product Information -->
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label required">Product Name</label>
                                        <input type="text" 
                                               name="product_name" 
                                               id="product_name"
                                               class="form-control" 
                                               value="<?= old('product_name') ?>"
                                               placeholder="Enter product name"
                                               required>
                                        <div class="form-text">A clear, descriptive name for the product</div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="ean13" class="form-label required">EAN-13 Barcode</label>
                                            <input type="text" 
                                                   name="ean13" 
                                                   id="ean13"
                                                   class="form-control" 
                                                   value="<?= old('ean13') ?>"
                                                   placeholder="e.g., 1234567890123"
                                                   pattern="[0-9]{13}"
                                                   maxlength="13"
                                                   required>
                                            <div class="form-text">13-digit EAN barcode</div>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="price" class="form-label required">Price ($)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       name="price" 
                                                       id="price"
                                                       class="form-control" 
                                                       value="<?= old('price') ?>"
                                                       placeholder="0.00"
                                                       step="0.01"
                                                       min="0"
                                                       required>
                                            </div>
                                            <div class="form-text">Product selling price</div>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="quantity" class="form-label required">Quantity</label>
                                            <input type="number" 
                                                   name="quantity" 
                                                   id="quantity"
                                                   class="form-control" 
                                                   value="<?= old('quantity', 0) ?>"
                                                   placeholder="0"
                                                   min="0"
                                                   required>
                                            <div class="form-text">Current stock quantity</div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        
                        <!-- Product Classification -->
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Classification</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label required">Category</label>
                                        <select name="category_id" id="category_id" class="form-select" required>
                                            <option value="">-- Select Category --</option>
                                            <?php if (isset($categories)): ?>
                                                <?php foreach($categories as $c): ?>
                                                <option value="<?= $c['id'] ?>" <?= old('category_id') == $c['id'] ? 'selected' : '' ?>>
                                                    <?= esc($c['category_name']) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="form-text">Product category classification</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="product_type" class="form-label required">Product Type</label>
                                        <select name="product_type" id="product_type" class="form-select" required>
                                            <option value="">-- Select Type --</option>
                                            <option value="physical" <?= old('product_type') == 'physical' ? 'selected' : '' ?>>
                                                <i class="bi bi-box"></i> Physical Product
                                            </option>
                                            <option value="digital" <?= old('product_type') == 'digital' ? 'selected' : '' ?>>
                                                <i class="bi bi-cloud"></i> Digital Product
                                            </option>
                                        </select>
                                        <div class="form-text">Physical or digital product</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Product Status Preview -->
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Product Status</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <small class="text-muted">Stock Status:</small>
                                        <div id="stockStatus" class="fw-semibold">
                                            <span class="badge bg-secondary">Not Set</span>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Total Value:</small>
                                        <div id="totalValue" class="fw-semibold text-success">$0.00</div>
                                    </div>
                                    <div>
                                        <small class="text-muted">EAN-13 Validation:</small>
                                        <div id="ean13Validation" class="fw-semibold">
                                            <span class="badge bg-secondary">Not Set</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="<?= site_url('/products') ?>" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Help Section -->
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-info-circle text-info"></i> Tips for Adding Products
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="small mb-0">
                            <li>Use descriptive product names that customers can easily search for</li>
                            <li>EAN-13 barcodes should be unique 13-digit numbers</li>
                            <li>Include accurate product names and EAN-13 codes for easy identification</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="small mb-0">
                            <li>Set accurate pricing and keep inventory quantities updated</li>
                            <li>Choose the appropriate category for better organization</li>
                            <li>Physical products require shipping, digital products are downloadable</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.required::after {
    content: ' *';
    color: #dc3545;
}

#stockStatus .badge,
#ean13Validation .badge {
    transition: all 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('price');
    const ean13Input = document.getElementById('ean13');
    
    // Update stock status based on quantity
    function updateStockStatus() {
        const quantity = parseInt(quantityInput.value) || 0;
        const statusElement = document.getElementById('stockStatus');
        
        if (quantity === 0) {
            statusElement.innerHTML = '<span class="badge bg-danger">Out of Stock</span>';
        } else if (quantity <= 5) {
            statusElement.innerHTML = '<span class="badge bg-warning">Low Stock</span>';
        } else {
            statusElement.innerHTML = '<span class="badge bg-success">In Stock</span>';
        }
    }
    
    // Update total value
    function updateTotalValue() {
        const quantity = parseInt(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = quantity * price;
        
        document.getElementById('totalValue').textContent = '$' + total.toFixed(2);
    }
    
    // Validate EAN-13 format
    function validateEAN13() {
        const ean13 = ean13Input.value.trim();
        const validationElement = document.getElementById('ean13Validation');
        
        if (!ean13) {
            validationElement.innerHTML = '<span class="badge bg-secondary">Not Set</span>';
        } else if (ean13.length !== 13) {
            validationElement.innerHTML = '<span class="badge bg-danger">Must be 13 digits</span>';
        } else if (!/^[0-9]{13}$/.test(ean13)) {
            validationElement.innerHTML = '<span class="badge bg-warning">Numbers only</span>';
        } else {
            validationElement.innerHTML = '<span class="badge bg-success">Valid</span>';
        }
    }
    
    // Event listeners
    quantityInput.addEventListener('input', function() {
        updateStockStatus();
        updateTotalValue();
    });
    
    priceInput.addEventListener('input', updateTotalValue);
    ean13Input.addEventListener('input', validateEAN13);
    
    // Form validation
    document.getElementById('productForm').addEventListener('submit', function(e) {
        const ean13 = ean13Input.value.trim();
        if (ean13.length !== 13) {
            e.preventDefault();
            alert('EAN-13 must be exactly 13 digits');
            ean13Input.focus();
            return;
        }
        
        if (!/^[0-9]{13}$/.test(ean13)) {
            e.preventDefault();
            alert('EAN-13 can only contain numbers');
            ean13Input.focus();
            return;
        }
    });
    
    // Initialize
    updateStockStatus();
    updateTotalValue();
    validateEAN13();
});
</script>

<?= $this->endSection() ?>