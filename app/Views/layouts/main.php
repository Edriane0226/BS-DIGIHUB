<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) . ' - BS DIGIHUB' : 'BS DIGIHUB - Automotive Parts & Accessories' ?></title>
    <meta name="description" content="<?= isset($description) ? esc($description) : 'BS DIGIHUB - Your trusted partner for automotive parts and accessories' ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        main {
            flex: 1;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff !important;
        }
        
        .navbar-brand i {
            color: var(--primary-color);
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
        
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
        }
        
        .alert {
            border: none;
            border-radius: 0.5rem;
        }
        
        .breadcrumb {
            background-color: transparent;
            padding: 0;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: '›';
        }
        
        /* Animation for alerts */
        .alert {
            animation: slideDown 0.5s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
    
    <?= isset($additionalCSS) ? $additionalCSS : '' ?>
</head>
<body>
    <!-- Header -->
    <?= view('layouts/header') ?>
    
    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif ?>
            
            <?php if (session()->getFlashdata('warning')): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i>
                    <?= session()->getFlashdata('warning') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif ?>
            
            <?php if (session()->getFlashdata('info')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle"></i>
                    <?= session()->getFlashdata('info') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif ?>
            
            <!-- Price Warning Modal -->
            <?php if (session()->getFlashdata('price_warning')): ?>
                <?php $warning = session()->getFlashdata('price_warning'); ?>
                <div class="modal fade" id="priceWarningModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title">
                                    <i class="bi bi-exclamation-triangle"></i> Price Warning
                                </h5>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <strong>Cost is higher than selling price!</strong>
                                </div>
                                <p><strong>Product:</strong> <?= esc($warning['product_name']) ?></p>
                                <p><strong>Current Price:</strong> ₱<?= number_format($warning['current_price'], 2) ?></p>
                                <p><strong>Cost per Unit:</strong> ₱<?= number_format($warning['cost_per_unit'], 2) ?></p>
                                <p><strong>Loss per unit:</strong> <span class="text-danger">₱<?= number_format($warning['cost_per_unit'] - $warning['current_price'], 2) ?></span></p>
                                
                                <form method="post" action="<?= site_url('/products/stock-in') ?>" id="priceUpdateForm">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="barcode" value="<?= esc($warning['barcode']) ?>">
                                    <input type="hidden" name="quantity" value="<?= esc($warning['quantity']) ?>">
                                    <input type="hidden" name="cost_per_unit" value="<?= esc($warning['cost_per_unit']) ?>">
                                    <input type="hidden" name="update_price" value="1">
                                    
                                    <?php foreach ($warning['other_data'] as $key => $value): ?>
                                        <?php if (!in_array($key, ['barcode', 'quantity', 'cost_per_unit'])): ?>
                                            <?php if (is_array($value)): ?>
                                                <?php foreach ($value as $subValue): ?>
                                                    <input type="hidden" name="<?= esc($key) ?>[]" value="<?= esc($subValue) ?>">
                                                <?php endforeach ?>
                                            <?php else: ?>
                                                <input type="hidden" name="<?= esc($key) ?>" value="<?= esc($value) ?>">
                                            <?php endif ?>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                    
                                    <div class="mb-3">
                                        <label for="new_price" class="form-label">New Selling Price <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="new_price" 
                                                   name="new_price" 
                                                   step="0.01" 
                                                   min="<?= esc($warning['cost_per_unit']) ?>"
                                                   value="<?= esc(round($warning['cost_per_unit'] * 1.3, 2)) ?>"
                                                   required>
                                        </div>
                                        <div class="form-text">
                                            Suggested price (30% markup): ₱<?= number_format($warning['cost_per_unit'] * 1.3, 2) ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="proceedWithoutPriceUpdate()">
                                    Proceed Without Update
                                </button>
                                <button type="submit" form="priceUpdateForm" class="btn btn-primary">
                                    Update Price & Continue
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var modal = new bootstrap.Modal(document.getElementById('priceWarningModal'));
                        modal.show();
                    });
                    
                    function proceedWithoutPriceUpdate() {
                        // Create a form to proceed without price update
                        var form = document.createElement('form');
                        form.method = 'post';
                        form.action = '<?= site_url('/products/stock-in') ?>';
                        
                        // Add CSRF token
                        var csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '<?= csrf_token() ?>';
                        csrfInput.value = '<?= csrf_hash() ?>';
                        form.appendChild(csrfInput);
                        
                        // Add original form data
                        var barcodeInput = document.createElement('input');
                        barcodeInput.type = 'hidden';
                        barcodeInput.name = 'barcode';
                        barcodeInput.value = '<?= esc($warning['barcode']) ?>';
                        form.appendChild(barcodeInput);
                        
                        var quantityInput = document.createElement('input');
                        quantityInput.type = 'hidden';
                        quantityInput.name = 'quantity';
                        quantityInput.value = '<?= esc($warning['quantity']) ?>';
                        form.appendChild(quantityInput);
                        
                        var costInput = document.createElement('input');
                        costInput.type = 'hidden';
                        costInput.name = 'cost_per_unit';
                        costInput.value = '<?= esc($warning['cost_per_unit']) ?>';
                        form.appendChild(costInput);
                        
                        var skipInput = document.createElement('input');
                        skipInput.type = 'hidden';
                        skipInput.name = 'skip_price_warning';
                        skipInput.value = '1';
                        form.appendChild(skipInput);
                        
                        // Add other form data
                        <?php foreach ($warning['other_data'] as $key => $value): ?>
                            <?php if (!in_array($key, ['barcode', 'quantity', 'cost_per_unit'])): ?>
                                <?php if (is_array($value)): ?>
                                    <?php foreach ($value as $subValue): ?>
                                        var input = document.createElement('input');
                                        input.type = 'hidden';
                                        input.name = '<?= esc($key) ?>[]';
                                        input.value = '<?= esc($subValue) ?>';
                                        form.appendChild(input);
                                    <?php endforeach ?>
                                <?php else: ?>
                                    var input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = '<?= esc($key) ?>';
                                    input.value = '<?= esc($value) ?>';
                                    form.appendChild(input);
                                <?php endif ?>
                            <?php endif ?>
                        <?php endforeach ?>
                        
                        document.body.appendChild(form);
                        form.submit();
                    }
                </script>
            <?php endif ?>
            
            <!-- Breadcrumb -->
            <?php if (isset($breadcrumbs)): ?>
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <?php foreach ($breadcrumbs as $label => $url): ?>
                            <?php if ($url): ?>
                                <li class="breadcrumb-item">
                                    <a href="<?= $url ?>" class="text-decoration-none"><?= esc($label) ?></a>
                                </li>
                            <?php else: ?>
                                <li class="breadcrumb-item active" aria-current="page"><?= esc($label) ?></li>
                            <?php endif ?>
                        <?php endforeach ?>
                    </ol>
                </nav>
            <?php endif ?>
            
            <!-- Page Content -->
            <?= $this->renderSection('content') ?>
        </div>
    </main>
    
    <!-- Footer -->
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
        
        // Confirm dialogs
        document.addEventListener('click', function(e) {
            if (e.target.hasAttribute('data-confirm')) {
                if (!confirm(e.target.getAttribute('data-confirm'))) {
                    e.preventDefault();
                }
            }
        });
    </script>
    
    <?= isset($additionalJS) ? $additionalJS : '' ?>
</body>
</html>