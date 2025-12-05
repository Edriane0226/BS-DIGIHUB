<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Stock In</h1>
        <p class="text-muted">Scan barcode or add new products to inventory</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('/products') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Products
        </a>
        <a href="<?= site_url('/products/stock-out') ?>" class="btn btn-outline-danger">
            <i class="bi bi-box-arrow-right"></i> Stock Out
        </a>
    </div>
</div>

<!-- Barcode Scanner Section -->
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-upc-scan"></i> Barcode Scanner
                </h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= site_url('/products/stock-in') ?>" id="stockInForm">
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
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="quantity" class="form-label required">Quantity to Add</label>
                            <input type="number" 
                                   name="quantity" 
                                   id="quantity"
                                   class="form-control" 
                                   min="1"
                                   value="1"
                                   required>
                        </div>
                        <div class="col-md-6">
                            <label for="cost_per_unit" class="form-label required">Cost per Unit</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" 
                                       name="cost_per_unit" 
                                       id="cost_per_unit"
                                       class="form-control" 
                                       step="0.01"
                                       min="0.01"
                                       placeholder="0.00"
                                       required>
                            </div>
                            <div class="form-text">
                                <strong>Required:</strong> Track cost for inventory valuation and profit calculations
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shelf Location Section (always available) -->
                    <div class="mt-3 mb-3">
                        <hr>
                        <h6 class="text-secondary">
                         Shelf Location
                        </h6>
                        <p class="small text-muted">Assign or update the shelf location for this product</p>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <label for="shelf_location_id" class="form-label required">Shelf Location</label>
                                <select name="shelf_location_id" id="shelf_location_id" class="form-select" required>
                                    <option value="">-- Select Shelf Location --</option>
                                    <?php if (isset($shelf_locations)): ?>
                                        <?php foreach($shelf_locations as $id => $location): ?>
                                        <option value="<?= $id ?>">
                                            <?= esc($location) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="form-text">
                                    <strong>Required:</strong> For existing products, this will update the current shelf location. 
                                    For new products, this will assign the initial location.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Car Compatibility Section (always available) -->
                    <div class="mt-4">
                        <hr>
                        <h6 class="text-info">Car Compatibility (Optional)</h6>
                        <p class="small text-muted">Select compatible car models for this product</p>
                        
                        <!-- Radio buttons for compatibility options -->
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="compatibility_option" id="existingCarsOption" value="existing" checked>
                                <label class="form-check-label" for="existingCarsOption">
                                    Select Existing Cars
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="compatibility_option" id="newCarOption" value="new">
                                <label class="form-check-label" for="newCarOption">
                                    Add New Car Model
                                </label>
                            </div>
                        </div>
                        
                        <!-- Existing car models selection -->
                        <div id="existingCarsSection">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="car_model_id" class="form-label">Compatible Car Models</label>
                                    <select name="car_model_id[]" id="car_model_id" class="form-select" multiple>
                                        <?php if (isset($car_models)): ?>
                                            <?php foreach($car_models as $cm): ?>
                                            <option value="<?= $cm['id'] ?>">
                                                <?= esc($cm['brand']) ?> <?= esc($cm['model']) ?> (<?= esc($cm['year_start']) ?>-<?= esc($cm['year_end']) ?>)
                                            </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <div class="form-text">Hold Ctrl/Cmd to select multiple car models</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- New car model form -->
                        <div id="newCarSection" style="display: none;">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">Add New Car Model</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="new_car_brand" class="form-label required">Brand</label>
                                            <input type="text" 
                                                   name="new_car_brand" 
                                                   id="new_car_brand"
                                                   class="form-control" 
                                                   placeholder="e.g., Toyota, Honda, BMW">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="new_car_model" class="form-label required">Model</label>
                                            <input type="text" 
                                                   name="new_car_model" 
                                                   id="new_car_model"
                                                   class="form-control" 
                                                   placeholder="e.g., Camry, Civic, X3">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="new_car_year_start" class="form-label required">Year Start</label>
                                            <input type="number" 
                                                   name="new_car_year_start" 
                                                   id="new_car_year_start"
                                                   class="form-control" 
                                                   min="1900"
                                                   max="<?= date('Y') + 5 ?>"
                                                   placeholder="<?= date('Y') - 10 ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="new_car_year_end" class="form-label">Year End</label>
                                            <input type="number" 
                                                   name="new_car_year_end" 
                                                   id="new_car_year_end"
                                                   class="form-control" 
                                                   min="1900"
                                                   max="<?= date('Y') + 10 ?>"
                                                   placeholder="<?= date('Y') ?> (leave empty for current)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Product Details Section (hidden initially) -->
                    <div id="productDetailsSection" class="mt-4" style="display: none;">
                        <hr>
                        <h6 class="text-success">Product Found</h6>
                        <div id="existingProductInfo" class="alert alert-success alert-permanent" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Product:</strong> <span id="existingProductName"></span><br>
                                    <strong>Category:</strong> <span id="existingProductCategory"></span><br>
                                    <strong>EAN-13:</strong> <span id="existingProductEAN13"></span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Current Stock:</strong> <span id="existingProductStock" class="badge bg-info"></span><br>
                                    <strong>Price:</strong> ₱<span id="existingProductPrice"></span><br>
                                    <strong>Shelf Location:</strong> <span id="existingProductShelf"></span>
                                </div>
                            </div>
                            <div class="row mt-2" id="compatibilityRow" style="display: none;">
                                <div class="col-md-12">
                                    <strong>Compatible Cars:</strong> 
                                    <div id="existingCompatibilities" class="mt-1">
                                        <!-- Compatibility badges will be inserted here -->
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="alert alert-light mb-0">
                                        <i class="bi bi-info-circle me-1"></i>
                                        <small>You can update the shelf location below when adding stock to this existing product.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- New Product Form (shown when product not found) -->
                        <div id="newProductForm" style="display: none;">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> Product not found. Please fill in the details to create a new product.
                            </div>
                            
                            <!-- Display the EAN-13 that will be saved -->
                            <div class="alert alert-info mb-3">
                                <i class="bi bi-upc-scan me-1"></i>
                                <strong>EAN-13 Barcode:</strong> <span id="display_ean13" class="text-monospace"></span>
                            </div>
                            
                            <!-- Hidden field to store the scanned barcode -->
                            <input type="hidden" name="ean13" id="hidden_ean13" value="">
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="product_name" class="form-label required">Product Name</label>
                                    <input type="text" 
                                           name="product_name" 
                                           id="product_name"
                                           class="form-control" 
                                           placeholder="Enter product name">
                                </div>
                            </div>
                            
                            <!-- Category Selection Section -->
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Product Category</label>
                                    
                                    <!-- Category Options Radio Buttons -->
                                    <div class="mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="category_option" id="existingCategoryOption" value="existing" checked>
                                            <label class="form-check-label" for="existingCategoryOption">
                                                Select Existing Category
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="category_option" id="newCategoryOption" value="new">
                                            <label class="form-check-label" for="newCategoryOption">
                                                Add New Category
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="category_option" id="noCategoryOption" value="none">
                                            <label class="form-check-label" for="noCategoryOption">
                                                No Category
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- Existing Category Selection -->
                                    <div id="existingCategorySection">
                                        <label for="category_id" class="form-label">Select Category</label>
                                        <select name="category_id" id="category_id" class="form-select">
                                            <option value="">-- Select Category --</option>
                                            <?php if (isset($categories)): ?>
                                                <?php foreach($categories as $c): ?>
                                                <option value="<?= $c['id'] ?>">
                                                    <?= esc($c['category_name']) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    
                                    <!-- New Category Form -->
                                    <div id="newCategorySection" style="display: none;">
                                        <div class="card border-warning">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="mb-0">Add New Category</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label for="new_category_name" class="form-label required">Category Name</label>
                                                        <input type="text" 
                                                               name="new_category_name" 
                                                               id="new_category_name"
                                                               class="form-control" 
                                                               placeholder="e.g., Engine Parts, Electronics, Accessories">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- No Category Info -->
                                    <div id="noCategorySection" style="display: none;">
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle"></i> This product will be added without a category. You can assign a category later from the product list.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="product_type" class="form-label required">Product Type</label>
                                    <select name="product_type" id="product_type" class="form-select">
                                        <option value="">-- Select Type --</option>
                                        <option value="physical">Physical Product</option>
                                        <option value="digital">Digital Product</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="price" class="form-label required">Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" 
                                               name="price" 
                                               id="price"
                                               class="form-control" 
                                               step="0.01"
                                               min="0.01"
                                               placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-lg"></i> Add Stock
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
                    <li><strong>Click lookup</strong> to check if product exists</li>
                    <li><strong>Enter quantity and cost</strong> per unit (optional)</li>
                    <li><strong>Select shelf location</strong> if needed</li>
                    <li><strong>Fill product details</strong> if it's a new item</li>
                    <li><strong>Click "Add Stock"</strong> to complete</li>
                </ol>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Quick Stats</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">Products Scanned Today</span>
                    <span class="badge bg-primary" id="productsScannedToday">0</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">Total Items Added</span>
                    <span class="badge bg-success" id="totalItemsAdded">0</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Last Scan Time</span>
                    <small class="text-muted" id="lastScanTime">--:--</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.getElementById('barcode');
    const lookupBtn = document.getElementById('lookupBtn');
    const productDetailsSection = document.getElementById('productDetailsSection');
    const existingProductInfo = document.getElementById('existingProductInfo');
    const newProductForm = document.getElementById('newProductForm');
    
    // Check if all required elements exist
    if (!barcodeInput || !lookupBtn || !productDetailsSection || !existingProductInfo || !newProductForm) {
        console.error('Some required elements are missing from the DOM');
        return;
    }
    
    // Initialize quick stats
    let todayStats = {
        productsScanned: parseInt(localStorage.getItem('productsScannedToday') || '0'),
        itemsAdded: parseInt(localStorage.getItem('itemsAddedToday') || '0'),
        lastScanTime: localStorage.getItem('lastScanTime') || '--:--'
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
    
    // Car compatibility radio button handlers
    const existingCarsOption = document.getElementById('existingCarsOption');
    const newCarOption = document.getElementById('newCarOption');
    const existingCarsSection = document.getElementById('existingCarsSection');
    const newCarSection = document.getElementById('newCarSection');
    
    // Category radio button handlers
    const existingCategoryOption = document.getElementById('existingCategoryOption');
    const newCategoryOption = document.getElementById('newCategoryOption');
    const noCategoryOption = document.getElementById('noCategoryOption');
    const existingCategorySection = document.getElementById('existingCategorySection');
    const newCategorySection = document.getElementById('newCategorySection');
    const noCategorySection = document.getElementById('noCategorySection');
    
    existingCarsOption.addEventListener('change', function() {
        if (this.checked) {
            existingCarsSection.style.display = 'block';
            newCarSection.style.display = 'none';
            clearNewCarFields();
            setNewCarFieldsRequired(false);
        }
    });
    
    newCarOption.addEventListener('change', function() {
        if (this.checked) {
            existingCarsSection.style.display = 'none';
            newCarSection.style.display = 'block';
            clearExistingCarSelection();
            setNewCarFieldsRequired(true);
        }
    });
    
    // Category option handlers
    existingCategoryOption.addEventListener('change', function() {
        if (this.checked) {
            existingCategorySection.style.display = 'block';
            newCategorySection.style.display = 'none';
            noCategorySection.style.display = 'none';
            clearNewCategoryFields();
            setNewCategoryFieldsRequired(false);
        }
    });
    
    newCategoryOption.addEventListener('change', function() {
        if (this.checked) {
            existingCategorySection.style.display = 'none';
            newCategorySection.style.display = 'block';
            noCategorySection.style.display = 'none';
            clearExistingCategorySelection();
            setNewCategoryFieldsRequired(true);
        }
    });
    
    noCategoryOption.addEventListener('change', function() {
        if (this.checked) {
            existingCategorySection.style.display = 'none';
            newCategorySection.style.display = 'none';
            noCategorySection.style.display = 'block';
            clearExistingCategorySelection();
            clearNewCategoryFields();
            setNewCategoryFieldsRequired(false);
        }
    });
    
    // Helper functions for car compatibility
    function clearNewCarFields() {
        document.getElementById('new_car_brand').value = '';
        document.getElementById('new_car_model').value = '';
        document.getElementById('new_car_year_start').value = '';
        document.getElementById('new_car_year_end').value = '';
    }
    
    function clearExistingCarSelection() {
        const carSelect = document.getElementById('car_model_id');
        for (let i = 0; i < carSelect.options.length; i++) {
            carSelect.options[i].selected = false;
        }
    }
    
    function setNewCarFieldsRequired(required) {
        document.getElementById('new_car_brand').required = required;
        document.getElementById('new_car_model').required = required;
        document.getElementById('new_car_year_start').required = required;
    }
    
    // Helper functions for category management
    function clearNewCategoryFields() {
        const newCategoryName = document.getElementById('new_category_name');
        if (newCategoryName) newCategoryName.value = '';
    }
    
    function clearExistingCategorySelection() {
        const categorySelect = document.getElementById('category_id');
        if (categorySelect) categorySelect.value = '';
    }
    
    function setNewCategoryFieldsRequired(required) {
        const newCategoryName = document.getElementById('new_category_name');
        if (newCategoryName) newCategoryName.required = required;
    }
    
    // Form submission tracking
    document.getElementById('stockInForm').addEventListener('submit', function(e) {
        const quantity = document.getElementById('quantity').value;
        
        // Validate category fields if "Add New Category" is selected
        const newCategoryOption = document.getElementById('newCategoryOption');
        if (newCategoryOption && newCategoryOption.checked) {
            const newCategoryName = document.getElementById('new_category_name');
            if (!newCategoryName || !newCategoryName.value.trim()) {
                e.preventDefault();
                alert('Category name is required when adding a new category.');
                if (newCategoryName) newCategoryName.focus();
                return false;
            }
        }
        
        if (quantity && parseInt(quantity) > 0) {
            // Update stats when form is submitted (will be processed after page reload)
            localStorage.setItem('pendingItemsAdd', quantity);
        }
    });
    
    // Check for pending stats update after page reload
    const pendingAdd = localStorage.getItem('pendingItemsAdd');
    if (pendingAdd) {
        updateItemsAddedStats(pendingAdd);
        localStorage.removeItem('pendingItemsAdd');
    }
    
    function lookupProduct() {
        const barcode = barcodeInput.value.trim();
        console.log('lookupProduct called with barcode:', barcode);
        
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
                console.log('Response ok:', response.ok);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data received:', data);
                productDetailsSection.style.display = 'block';
                
                if (data.success) {
                    console.log('Product found, calling showExistingProduct');
                    showExistingProduct(data.product);
                    
                    // Update stats for successful lookup
                    updateLookupStats();
                } else {
                    console.log('Product not found, showing new product form');
                    showNewProductForm();
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                console.error('Error details:', error.message);
                alert('Error looking up product: ' + error.message);
            })
            .finally(() => {
                console.log('Finally block - resetting button');
                lookupBtn.disabled = false;
                lookupBtn.innerHTML = '<i class="bi bi-search"></i> Lookup Product';
            });
    }
    
    function showExistingProduct(product) {
        console.log('showExistingProduct called with:', product);
        
        if (!existingProductInfo) {
            console.error('existingProductInfo element not found');
            return;
        }
        
        existingProductInfo.style.display = 'block';
        newProductForm.style.display = 'none';
        
        const nameEl = document.getElementById('existingProductName');
        const categoryEl = document.getElementById('existingProductCategory');
        const ean13El = document.getElementById('existingProductEAN13');
        const stockEl = document.getElementById('existingProductStock');
        const priceEl = document.getElementById('existingProductPrice');
        const shelfEl = document.getElementById('existingProductShelf');
        const compatibilityRow = document.getElementById('compatibilityRow');
        const compatibilitiesEl = document.getElementById('existingCompatibilities');
        const shelfLocationSelect = document.getElementById('shelf_location_id');
        
        console.log('Elements found:', {nameEl, categoryEl, ean13El, stockEl, priceEl, shelfEl, compatibilityRow, compatibilitiesEl});
        
        if (nameEl) nameEl.textContent = product.product_name;
        if (categoryEl) categoryEl.textContent = product.category_name || 'Uncategorized';
        if (ean13El) ean13El.textContent = product.ean13 || 'Not set';
        if (stockEl) stockEl.textContent = product.quantity + ' units';
        if (priceEl) priceEl.textContent = parseFloat(product.price).toFixed(2);
        if (shelfEl) shelfEl.textContent = product.shelf_location || 'Not assigned';
        
        // Pre-populate shelf location dropdown if product has one assigned
        if (shelfLocationSelect && product.shelf_location_id) {
            shelfLocationSelect.value = product.shelf_location_id;
        }
        
        // Display existing compatibilities
        if (product.compatibilities && product.compatibilities.length > 0) {
            let compatibilityHtml = '';
            product.compatibilities.forEach(function(car) {
                compatibilityHtml += `<span class="badge bg-secondary me-1 mb-1">${car.brand} ${car.model} (${car.year_start}-${car.year_end})</span>`;
            });
            
            if (compatibilitiesEl) {
                compatibilitiesEl.innerHTML = compatibilityHtml;
            }
            if (compatibilityRow) {
                compatibilityRow.style.display = 'block';
            }
        } else {
            if (compatibilityRow) {
                compatibilityRow.style.display = 'none';
            }
        }
        
        console.log('Product info updated successfully');
    }
    
    function showNewProductForm() {
        existingProductInfo.style.display = 'none';
        newProductForm.style.display = 'block';
        
        // Populate the hidden EAN-13 field and display with the scanned barcode
        const barcodeValue = barcodeInput.value.trim();
        const hiddenEan13 = document.getElementById('hidden_ean13');
        const displayEan13 = document.getElementById('display_ean13');
        
        if (hiddenEan13 && barcodeValue) {
            hiddenEan13.value = barcodeValue;
            console.log('Set hidden EAN-13 field to:', barcodeValue);
        }
        
        if (displayEan13 && barcodeValue) {
            displayEan13.textContent = barcodeValue;
            console.log('Set display EAN-13 to:', barcodeValue);
        }
        
        // Make required fields required
        document.getElementById('product_name').required = true;
        document.getElementById('product_type').required = true;
        document.getElementById('price').required = true;
    }
    
    // Reset form function
    window.resetForm = function() {
        // Reset only the input fields, keep product information visible
        document.getElementById('barcode').value = '';
        document.getElementById('quantity').value = '1';
        
        // Reset cost per unit field
        const costField = document.getElementById('cost_per_unit');
        if (costField) costField.value = '';
        
        // Reset shelf location dropdown
        const shelfLocationField = document.getElementById('shelf_location_id');
        if (shelfLocationField) shelfLocationField.value = '';
        
        // Reset new product form fields if they exist
        const productNameField = document.getElementById('product_name');
        const categoryField = document.getElementById('category_id');
        const productTypeField = document.getElementById('product_type');
        const priceField = document.getElementById('price');
        
        if (productNameField) productNameField.value = '';
        if (categoryField) categoryField.value = '';
        if (productTypeField) productTypeField.value = '';
        if (priceField) priceField.value = '';
        
        // Reset car compatibility options
        document.getElementById('existingCarsOption').checked = true;
        document.getElementById('newCarOption').checked = false;
        existingCarsSection.style.display = 'block';
        newCarSection.style.display = 'none';
        clearNewCarFields();
        clearExistingCarSelection();
        setNewCarFieldsRequired(false);
        
        // Reset category options
        const existingCategoryOption = document.getElementById('existingCategoryOption');
        const newCategoryOption = document.getElementById('newCategoryOption');
        const noCategoryOption = document.getElementById('noCategoryOption');
        
        if (existingCategoryOption) {
            existingCategoryOption.checked = true;
            newCategoryOption.checked = false;
            noCategoryOption.checked = false;
            
            // Show/hide appropriate sections
            existingCategorySection.style.display = 'block';
            newCategorySection.style.display = 'none';
            noCategorySection.style.display = 'none';
            
            clearNewCategoryFields();
            clearExistingCategorySelection();
            setNewCategoryFieldsRequired(false);
        }
        
        // Remove required attributes for new product fields
        if (productNameField) productNameField.required = false;
        if (productTypeField) productTypeField.required = false;
        if (priceField) priceField.required = false;
        
        // Keep product details visible if they were shown
        // Only hide new product form
        newProductForm.style.display = 'none';
        
        barcodeInput.focus();
    };
    
    function updateQuickStats() {
        document.getElementById('productsScannedToday').textContent = todayStats.productsScanned;
        document.getElementById('totalItemsAdded').textContent = todayStats.itemsAdded;
        document.getElementById('lastScanTime').textContent = todayStats.lastScanTime;
    }
    
    function updateLookupStats() {
        todayStats.productsScanned++;
        const now = new Date();
        todayStats.lastScanTime = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: false
        });
        
        // Save to localStorage
        localStorage.setItem('productsScannedToday', todayStats.productsScanned);
        localStorage.setItem('lastScanTime', todayStats.lastScanTime);
        
        updateQuickStats();
    }
    
    function updateItemsAddedStats(quantity) {
        todayStats.itemsAdded += parseInt(quantity);
        
        // Save to localStorage
        localStorage.setItem('itemsAddedToday', todayStats.itemsAdded);
        
        updateQuickStats();
    }
});
</script>

<?= $this->endSection() ?>