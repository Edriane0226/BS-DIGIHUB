<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Reports</h1>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary btn-sm" onclick="refreshReports()">
            <i class="bi bi-arrow-clockwise"></i> Refresh
        </button>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-download"></i> Export Reports
            </button>
            <ul class="dropdown-menu">
                <li><h6 class="dropdown-header">Inventory Reports</h6></li>
                <li><a class="dropdown-item" href="<?= site_url('/reports/export/inventory') ?>">
                    <i class="bi bi-file-earmark-text"></i> Complete Inventory Report
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><h6 class="dropdown-header">Sales Reports</h6></li>
                <li><a class="dropdown-item" href="<?= site_url('/reports/export/sales') ?>">
                    <i class="bi bi-graph-up"></i> Complete Sales Report
                    </a>
                </li>
                <li><a class="dropdown-item" href="#" onclick="exportDailySales(); return false;">
                    <i class="bi bi-calendar-day"></i> Daily Sales Report
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><h6 class="dropdown-header">Alerts & Notifications</h6></li>
                <li>
                    <a class="dropdown-item" href="#" onclick="sendStockAlerts(); return false;">
                    <i class="bi bi-bell"></i> Send Stock Alerts
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" onclick="sendDailySalesReport(); return false;">
                    <i class="bi bi-envelope"></i> Send Daily Sales Email
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Quick Stats Dashboard -->
<div class="row mb-5">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-gradient bg-primary text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="h2 mb-0" id="totalProducts">-</div>
                    <div class="small">Total Products</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-gradient bg-info text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="h2 mb-0" id="totalValue">₱-</div>
                    <div class="small">Inventory Value</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-gradient bg-success text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="h2 mb-0" id="totalSales">₱-</div>
                    <div class="small">Total Sales</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-gradient bg-info text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="h2 mb-0" id="totalProfit">₱-</div>
                    <div class="small">Total Profit</div>
                </div>
            </div>
        </div>
    </div>
</div>
    
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-black">
                <h5 class="card-title mb-0">
                     Inventory Alerts & Notifications
                </h5>
            </div>
            <div class="card-body">
                <div id="inventoryAlerts" class="list-group list-group-flush">
                    <div class="text-center py-4">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                        <span class="ms-2">Loading inventory alerts...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshReports() {
    location.reload();
}

// Load dashboard data
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
    loadInventoryAlerts();
});

async function loadDashboardData() {
    try {
        // Fetch actual dashboard data from backend
        const response = await fetch('<?= site_url('/reports/dashboard-data') ?>');
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('totalProducts').textContent = data.totalProducts || '0';
            document.getElementById('totalValue').textContent = '₱' + (data.totalValue ? Number(data.totalValue).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00');
            document.getElementById('totalSales').textContent = '₱' + (data.totalSales ? Number(data.totalSales).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00');
            document.getElementById('totalProfit').textContent = '₱' + (data.totalProfit ? Number(data.totalProfit).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00');
        } else {
            // Fallback to mock data
            document.getElementById('totalProducts').textContent = '127';
            document.getElementById('totalValue').textContent = '₱45,230';
            document.getElementById('totalSales').textContent = '₱128,450';
            document.getElementById('totalProfit').textContent = '₱38,200';
        }
        
    } catch (error) {
        console.error('Error loading dashboard data:', error);
        // Fallback to mock data on error
        document.getElementById('totalProducts').textContent = '127';
        document.getElementById('totalValue').textContent = '₱45,230';
        document.getElementById('totalSales').textContent = '₱128,450';
        document.getElementById('totalProfit').textContent = '₱38,200';
    }
}

function loadInventoryAlerts() {
    setTimeout(() => {
        const alertsContainer = document.getElementById('inventoryAlerts');
        alertsContainer.innerHTML = `
            <div class="list-group-item d-flex justify-content-between align-items-center border-0">
                <div>
                    <i class="bi bi-exclamation-circle text-danger me-2"></i>
                    <strong>5 items</strong> are out of stock
                </div>
                <span class="badge bg-danger">Critical</span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center border-0">
                <div>
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    <strong>12 items</strong> have low stock
                </div>
                <span class="badge bg-warning">Warning</span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center border-0">
                <div>
                    <i class="bi bi-info-circle text-info me-2"></i>
                    <strong>110 items</strong> are well stocked
                </div>
                <span class="badge bg-success">Good</span>
            </div>
            <div class="list-group-item border-0 pt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted" id="lastAlertTime">Last alert sent: 2 hours ago</small>
                    <div id="alertLoadingIndicator" style="display: none;">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Sending...</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }, 800);
}

async function sendStockAlerts() {
    // Show the stock alert modal
    showStockAlertModal();
}

function updateLastAlertTime() {
    const lastAlertElement = document.getElementById('lastAlertTime');
    const now = new Date();
    lastAlertElement.textContent = 'Last alert sent: Just now';
    
    // Start updating the time periodically
    let minutes = 0;
    const intervalId = setInterval(() => {
        minutes++;
        if (minutes === 1) {
            lastAlertElement.textContent = 'Last alert sent: 1 minute ago';
        } else if (minutes < 60) {
            lastAlertElement.textContent = `Last alert sent: ${minutes} minutes ago`;
        } else if (minutes === 60) {
            lastAlertElement.textContent = 'Last alert sent: 1 hour ago';
        } else {
            const hours = Math.floor(minutes / 60);
            const remainingMinutes = minutes % 60;
            if (remainingMinutes === 0) {
                lastAlertElement.textContent = `Last alert sent: ${hours} hour${hours > 1 ? 's' : ''} ago`;
            } else {
                lastAlertElement.textContent = `Last alert sent: ${hours} hour${hours > 1 ? 's' : ''} ${remainingMinutes} minute${remainingMinutes > 1 ? 's' : ''} ago`;
            }
        }
    }, 60000); // Update every minute
}

function showAlert(type, message) {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
    alertDiv.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Add to page
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

function exportDailySales() {
    // Show date picker modal
    const today = new Date().toISOString().split('T')[0];
    const yesterday = new Date(Date.now() - 86400000).toISOString().split('T')[0];
    
    const dateInput = prompt(`Enter date for daily sales report (YYYY-MM-DD):`, yesterday);
    
    if (dateInput && dateInput.match(/^\d{4}-\d{2}-\d{2}$/)) {
        window.location.href = `<?= site_url('/reports/export/daily-sales') ?>?date=${dateInput}`;
    } else if (dateInput) {
        showAlert('error', 'Invalid date format. Please use YYYY-MM-DD format.');
    }
}

async function sendDailySalesReport() {
    // Show the daily sales report modal
    showDailySalesReportModal();
}

// Function to show the daily sales report modal
function showDailySalesReportModal() {
    const modal = document.getElementById('dailySalesReportModal');
    if (modal) {
        // Set default values
        const emailInput = document.getElementById('reportEmail');
        const dateInput = document.getElementById('reportDate');
        
        // Set default email
        emailInput.value = 'edriane.bangonon26@gmail.com';
        
        // Set default date to yesterday
        const yesterday = new Date(Date.now() - 86400000);
        dateInput.value = yesterday.toISOString().split('T')[0];
        
        // Show modal
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }
}

// Function to actually send the report
async function sendDailySalesReportNow() {
    const email = document.getElementById('reportEmail').value;
    const date = document.getElementById('reportDate').value;
    
    if (!email) {
        showAlert('error', 'Please enter an email address.');
        return;
    }
    
    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        showAlert('error', 'Please enter a valid email address.');
        return;
    }
    
    if (!date) {
        showAlert('error', 'Please select a date.');
        return;
    }
    
    if (!date.match(/^\d{4}-\d{2}-\d{2}$/)) {
        showAlert('error', 'Invalid date format. Please use YYYY-MM-DD format.');
        return;
    }
    
    // Hide modal
    const modal = document.getElementById('dailySalesReportModal');
    const bsModal = bootstrap.Modal.getInstance(modal);
    if (bsModal) {
        bsModal.hide();
    }
    
    // Show loading
    const loadingIndicator = document.createElement('div');
    loadingIndicator.innerHTML = '<div class="alert alert-info"><i class="bi bi-hourglass-split"></i> Sending daily sales report...</div>';
    loadingIndicator.style.position = 'fixed';
    loadingIndicator.style.top = '20px';
    loadingIndicator.style.right = '20px';
    loadingIndicator.style.zIndex = '1060';
    document.body.appendChild(loadingIndicator);
    
    try {
        const formData = new FormData();
        formData.append('email', email);
        formData.append('date', date);
        
        const response = await fetch('<?= site_url('/reports/sendDailySalesReport') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('success', result.message || `Daily sales report sent successfully to ${email}`);
        } else {
            showAlert('error', result.message || 'Failed to send daily sales report');
        }
        
    } catch (error) {
        console.error('Error sending daily sales report:', error);
        showAlert('error', 'Failed to send daily sales report. Please try again.');
    } finally {
        loadingIndicator.remove();
    }
}

// Function to show the stock alert modal
function showStockAlertModal() {
    const modal = document.getElementById('stockAlertModal');
    if (modal) {
        // Set default email
        const emailInput = document.getElementById('alertEmail');
        emailInput.value = 'edriane.bangonon26@gmail.com';
        
        // Show modal
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }
}

// Function to actually send the stock alerts
async function sendStockAlertsNow() {
    const email = document.getElementById('alertEmail').value;
    
    if (!email) {
        showAlert('error', 'Please enter an email address.');
        return;
    }
    
    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        showAlert('error', 'Please enter a valid email address.');
        return;
    }
    
    // Hide modal
    const modal = document.getElementById('stockAlertModal');
    const bsModal = bootstrap.Modal.getInstance(modal);
    if (bsModal) {
        bsModal.hide();
    }
    
    // Show loading
    const loadingIndicator = document.createElement('div');
    loadingIndicator.innerHTML = '<div class="alert alert-info"><i class="bi bi-hourglass-split"></i> Sending stock alerts...</div>';
    loadingIndicator.style.position = 'fixed';
    loadingIndicator.style.top = '20px';
    loadingIndicator.style.right = '20px';
    loadingIndicator.style.zIndex = '1060';
    document.body.appendChild(loadingIndicator);
    
    try {
        const response = await fetch('<?= site_url('/reports/send-alerts') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                email: email
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('success', result.message || `Stock alerts sent successfully to ${email}`);
            updateLastAlertTime();
        } else {
            showAlert('error', result.message || 'Failed to send stock alerts');
        }
        
    } catch (error) {
        console.error('Error sending stock alerts:', error);
        showAlert('error', 'Failed to send stock alerts. Please try again.');
    } finally {
        loadingIndicator.remove();
    }
}
</script>

<!-- Stock Alert Modal -->
<div class="modal fade" id="stockAlertModal" tabindex="-1" aria-labelledby="stockAlertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stockAlertModalLabel">
                    <i class="bi bi-bell"></i> Send Stock Alert
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="alertEmail" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="alertEmail" placeholder="Enter email address" required>
                        <div class="form-text">The stock alert report will be sent to this email address.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendStockAlertsNow()">
                    <i class="bi bi-send"></i> Send Alert
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Daily Sales Report Modal -->
<div class="modal fade" id="dailySalesReportModal" tabindex="-1" aria-labelledby="dailySalesReportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dailySalesReportModalLabel">
                    <i class="bi bi-calendar-day"></i> Send Daily Sales Report
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="reportEmail" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="reportEmail" placeholder="Enter email address" required>
                        <div class="form-text">The daily sales report will be sent to this email address.</div>
                    </div>
                    <div class="mb-3">
                        <label for="reportDate" class="form-label">Report Date</label>
                        <input type="date" class="form-control" id="reportDate" required>
                        <div class="form-text">Select the date for which you want to generate the sales report.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendDailySalesReportNow()">
                    <i class="bi bi-send"></i> Send Report
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>