<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <img src="<?php echo WEB_URL; ?>/assets/new_img/logo/logo.png" alt="logo">
            </div>
            <nav class="nav-menu">
                <ul class="menu-list">
                    <li class="active">
                        <a href="#">
                            <i class="fas fa-th-large"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Online Deposit</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-share"></i>
                            <span>Domestic Transfer</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-wifi"></i>
                            <span>Wire Transfer</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-credit-card"></i>
                            <span>Virtual Card</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-download"></i>
                            <span>Loan & Mortgages</span>
                        </a>
                    </li>
                    <li class="has-submenu">
                        <a href="#" class="submenu-toggle">
                            <i class="fas fa-credit-card"></i>
                            <span>All Transaction Logs</span>
                            <i class="fas fa-chevron-right submenu-arrow"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="#">Credit / Debit Transaction</a></li>
                            <li><a href="#">Wire Transaction</a></li>
                            <li><a href="#">Domestic Transaction</a></li>
                            <li><a href="#">Loan Transaction</a></li>
                            <li><a href="#">All Withdrawal</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Withdrawal</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Account Manager</span>
                        </a>
                    </li>
                    <li class="has-submenu">
                        <a href="#" class="submenu-toggle">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                            <i class="fas fa-chevron-right submenu-arrow"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="#">Profile</a></li>
                            <li><a href="#">Account</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <div class="logout">
                <a href="#"><i class="fas fa-sign-out-alt"></i> Log out</a>
            </div>
        </aside>