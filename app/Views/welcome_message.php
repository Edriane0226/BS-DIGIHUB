<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<div class="row align-items-center py-5">
    <div class="col-lg-6">
        <div class="mb-4">
            <h1 class="display-4 fw-bold mb-3">Welcome to <span class="text-primary">BS DIGIHUB</span></h1>
            <p class="lead text-muted mb-4">Your trusted digital partner for automotive parts and accessories management. Streamline your inventory, manage compatibility, and grow your business with our comprehensive platform.</p>

            <div class="d-flex gap-3 mb-4">
                <a href="<?= base_url('dashboard') ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-speedometer2"></i> Go to Dashboard
                </a>

                <a href="#features" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-info-circle"></i> Learn More
                </a>
            </div>

            <div class="d-flex align-items-center text-muted">
                <i class="bi bi-shield-check text-success me-2"></i>
                <small>Secure, reliable, and built for automotive professionals</small>
            </div>
        </div>
    </div>

    <div class="col-lg-6 text-center">
        <div class="position-relative">
            <div class="bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center"
                 style="width: 300px; height: 300px;">
                <i class="bi bi-shop text-white" style="font-size: 6rem;"></i>
            </div>

            <div class="position-absolute top-0 start-0 translate-middle">
                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white"
                     style="width: 80px; height: 80px;">
                    <i class="bi bi-car-front" style="font-size: 2rem;"></i>
                </div>
            </div>

            <div class="position-absolute bottom-0 end-0 translate-middle">
                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center text-white"
                     style="width: 80px; height: 80px;">
                    <i class="bi bi-gear-fill" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section id="features" class="py-5">
    <div class="text-center mb-5">
        <h2 class="h3 fw-bold mb-3">Powerful Features for Your Business</h2>
        <p class="text-muted">Everything you need to manage your automotive parts business efficiently</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3"
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                    <h5 class="card-title">Product Management</h5>
                    <p class="card-text text-muted">Efficiently organize and manage your entire automotive parts inventory with detailed categorization and specifications.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3"
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                    <h5 class="card-title">Stock Management</h5>
                    <p class="card-text text-muted">Efficiently manage stock in and stock out operations with barcode scanning and real-time inventory tracking.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="bg-info bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3"
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-graph-up fs-4"></i>
                    </div>
                    <h5 class="card-title">Analytics & Reports</h5>
                    <p class="card-text text-muted">Track performance with comprehensive analytics and generate detailed reports to make informed business decisions.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="bg-danger bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3"
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <h5 class="card-title">Secure Platform</h5>
                    <p class="card-text text-muted">Your data is protected with industry-standard security measures and regular backups for peace of mind.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="bg-secondary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3"
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-lightning fs-4"></i>
                    </div>
                    <h5 class="card-title">Fast & Reliable</h5>
                    <p class="card-text text-muted">Built on modern technology stack ensuring fast performance and reliable uptime for your business operations.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Start Section -->
<section class="py-5 bg-light rounded">
    <div class="text-center mb-4">
        <h3 class="fw-bold mb-3">Ready to Get Started?</h3>
        <p class="text-muted">Jump right in and start managing your automotive business today</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="row g-3">
                <div class="col-md-4 text-center">
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-primary w-100 py-3">
                        <i class="bi bi-speedometer2 display-6 d-block mb-2"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="col-md-4 text-center">
                    <a href="<?= base_url('products/stock-in') ?>" class="btn btn-success w-100 py-3">
                        <i class="bi bi-box-arrow-in-down display-6 d-block mb-2"></i>
                        <span>Stock In</span>
                    </a>
                </div>

                <div class="col-md-4 text-center">
                    <a href="<?= base_url('products') ?>" class="btn btn-info w-100 py-3">
                        <i class="bi bi-box-seam display-6 d-block mb-2"></i>
                        <span>Products</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5">
    <div class="row text-center g-4">
        <div class="col-md-3">
            <div class="p-3">
                <div class="display-4 fw-bold text-primary mb-2">100%</div>
                <div class="text-muted">Secure</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-3">
                <div class="display-4 fw-bold text-success mb-2">24/7</div>
                <div class="text-muted">Available</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-3">
                <div class="display-4 fw-bold text-info mb-2">Fast</div>
                <div class="text-muted">Performance</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-3">
                <div class="display-4 fw-bold text-warning mb-2">Easy</div>
                <div class="text-muted">To Use</div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
