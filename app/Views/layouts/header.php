<?php
$currentPage = uri_string();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="<?= base_url() ?>">
            BS DIGIHUB
        </a>
        
        <!-- Toggle button for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === '' ? 'active' : '' ?>" href="<?= base_url() ?>">
                         Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains($currentPage, 'dashboard') ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
                         Dashboard
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= str_contains($currentPage, 'products') ? 'active' : '' ?>" 
                       href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                         Manage
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= base_url('products') ?>">
                             Products
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('products/stock-in') ?>">
                             Stock In
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('products/stock-out') ?>">
                             Stock Out
                        </a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains($currentPage, 'reports') ? 'active' : '' ?>" href="<?= base_url('reports') ?>">
                     Reports
                    </a>
                </li>
        </div>
    </div>
</nav>