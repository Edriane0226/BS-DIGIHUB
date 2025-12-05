<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Edit Product</h1>
        <p class="text-muted">Update product information and settings</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('/products') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Products
        </a>
        <button class="btn btn-outline-danger" data-confirm="Are you sure you want to delete this product?">
            <i class="bi bi-trash"></i> Delete Product
        </button>
    </div>
</div>

<!-- Product Info Summary -->
<div class="row">
    <div class="col-lg-8">
        <!-- Edit Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-box-seam"></i> Product Information
                </h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= site_url('/products/update/' . $product['id']) ?>" id="productForm">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <!-- Basic Product Information -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="product_name" class="form-label required">Product Name</label>
                                <input type="text" 
                                       name="product_name" 
                                       id="product_name"
                                       class="form-control" 
                                       value="<?= old('product_name', $product['product_name']) ?>"
                                       placeholder="Enter product name"
                                       required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="ean13" class="form-label">EAN-13</label>
                                    <input type="text" 
                                           name="ean13" 
                                           id="ean13"
                                           class="form-control"
                                           value="<?= old('ean13', $product['ean13']) ?>"
                                           placeholder="Enter EAN-13 barcode">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label required">Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" 
                                               name="price" 
                                               id="price"
                                               class="form-control" 
                                               step="0.01"
                                               min="0"
                                               value="<?= old('price', $product['price']) ?>"
                                               placeholder="0.00"
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select name="category_id" id="category_id" class="form-select">
                                        <option value="">-- Select Category --</option>
                                        <?php if (isset($categories)): ?>
                                            <?php foreach($categories as $c): ?>
                                            <option value="<?= $c['id'] ?>" 
                                                    <?= $product['category_id'] == $c['id'] ? 'selected' : '' ?>>
                                                <?= esc($c['category_name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="shelf_location_id" class="form-label">Shelf Location</label>
                                    <select name="shelf_location_id" id="shelf_location_id" class="form-select">
                                        <option value="">-- Select Shelf Location --</option>
                                        <?php if (isset($shelf_locations)): ?>
                                            <?php foreach($shelf_locations as $id => $location): ?>
                                            <option value="<?= $id ?>" 
                                                    <?= $product['shelf_location_id'] == $id ? 'selected' : '' ?>>
                                                <?= esc($location) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="product_type" class="form-label required">Product Type</label>
                                    <select name="product_type" id="product_type" class="form-select" required>
                                        <option value="physical" <?= $product['product_type'] == 'physical' ? 'selected' : '' ?>>
                                            Physical Product
                                        </option>
                                        <option value="digital" <?= $product['product_type'] == 'digital' ? 'selected' : '' ?>>
                                            Digital Product
                                        </option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity in Stock</label>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity"
                                       class="form-control" 
                                       min="0"
                                       value="<?= old('quantity', $product['quantity']) ?>"
                                       placeholder="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= site_url('/products') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Car Model Compatibility Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-car-front"></i> Car Model Compatibility
                </h5>
            </div>
            <div class="card-body">
                <!-- Add Compatibility Form -->
                <form method="post" action="<?= site_url('/products/update/' . $product['id']) ?>" class="mb-4">
                    <?= csrf_field() ?>
                    
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label for="car_model_id" class="form-label">Add Car Model Compatibility</label>
                            <select name="car_model_id[]" id="car_model_id" class="form-select" multiple>
                                <?php if (isset($car_models)): ?>
                                    <?php foreach($car_models as $carModel): ?>
                                        <option value="<?= $carModel['id'] ?>">
                                            <?= esc($carModel['brand'] ?? '') ?> <?= esc($carModel['model'] ?? '') ?> 
                                            (<?= $carModel['year_start'] ?? '' ?>-<?= $carModel['year_end'] ?? 'Present' ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-plus-lg"></i> Add Compatibility
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-text">Hold Ctrl (Cmd on Mac) to select multiple car models at once</div>
                </form>
                
                <!-- Current Compatibilities -->
                <?php if (isset($compatibilities) && !empty($compatibilities)): ?>
                    <h6>Current Compatibilities:</h6>
                    <div class="row">
                        <?php foreach($compatibilities as $compat): ?>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                    <span>
                                        <strong><?= esc($compat['brand'] ?? '') ?> <?= esc($compat['model'] ?? '') ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= $compat['year_start'] ?? '' ?>-<?= $compat['year_end'] ?? 'Present' ?>
                                        </small>
                                    </span>
                                    <a href="<?= site_url('/compatibility/remove/' . $compat['id']) ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       data-confirm="Remove this compatibility?">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No car model compatibilities added yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Product Summary -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Product Summary</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Product Name</small>
                    <div class="fw-semibold"><?= esc($product['product_name']) ?></div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">EAN-13</small>
                    <div class="fw-semibold"><?= esc($product['ean13']) ?: 'Not set' ?></div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Shelf Location</small>
                    <div class="fw-semibold">
                        <?php
                        $shelfDisplay = 'Not assigned';
                        if (isset($shelf_locations) && !empty($product['shelf_location_id'])) {
                            $shelfDisplay = $shelf_locations[$product['shelf_location_id']] ?? 'Not assigned';
                        }
                        echo esc($shelfDisplay);
                        ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Price</small>
                    <div class="fw-semibold">₱<?= number_format($product['price'], 2) ?></div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Type</small>
                    <div class="fw-semibold">
                        <span class="badge bg-<?= $product['product_type'] == 'digital' ? 'info' : 'success' ?>">
                            <?= ucfirst($product['product_type']) ?>
                        </span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Stock Quantity</small>
                    <div class="fw-semibold">
                        <span class="badge bg-<?= $product['quantity'] <= 0 ? 'danger' : ($product['quantity'] <= 5 ? 'warning' : 'success') ?>">
                            <?= $product['quantity'] ?> units
                        </span>
                    </div>
                </div>
                
                <?php if (isset($compatibilities)): ?>
                <div class="mb-3">
                    <small class="text-muted">Compatible Models</small>
                    <div class="fw-semibold"><?= count($compatibilities) ?> models</div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirmation dialogs
    const confirmElements = document.querySelectorAll('[data-confirm]');
    confirmElements.forEach(function(element) {
        element.addEventListener('click', function(e) {
            if (!confirm(this.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });
});
</script>

<?= $this->endSection() ?>