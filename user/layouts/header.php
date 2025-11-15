<?php
require dirname(__DIR__, 2) . '/include/vendor/autoload.php';
ob_start();
// Import required files and initialize session
require_once('../session.php');
require_once("../include/loginFunction.php");
require_once("../include/userClass.php");
require_once("../include/twilioController.php");



// Session timeout check
if(isset($_SESSION["name"])) {
    if(isset($_SESSION['last_login_timestamp']) && (time() - $_SESSION['last_login_timestamp']) > 900) {
        header("location:logout.php");
        exit;
    }
    $_SESSION['last_login_timestamp'] = time();
}

// Redirect if not logged in
if(empty($_SESSION['acct_no'])) {
    header("location:../login.php");
    exit;
}

// Get settings
$sql = "SELECT * FROM settings WHERE id ='1'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$page = $stmt->fetch(PDO::FETCH_ASSOC);

$title = $page['url_name'];
$pageTitle = $title;
$testApi = NULL;
$url_email = $page['url_email'];
$livechat = $page['livechat'];
$trans_limit_min = $page['trans_limit_min'];
$trans_limit_max = $page['trans_limit_max'];

// Get user information
$viesConn = "SELECT * FROM users WHERE acct_no = :acct_no";
$stmt = $conn->prepare($viesConn);
$stmt->execute([
    ':acct_no' => $_SESSION['acct_no']
]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$user_id = $row['id'];
$acct_stat = $row['acct_status'];

// Get audit logs
$sql = "SELECT * FROM audit_logs ORDER BY datenow DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$logs = $stmt->fetch(PDO::FETCH_ASSOC);

$device = $logs['device'];
$ipAddress = $logs['ipAddress'];
$datenow = $logs['datenow'];

// Get temp transactions
$sql = "SELECT * FROM temp_trans WHERE acct_id =:acct_id ORDER BY wire_id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'acct_id' => $user_id
]);
$temp_trans = $stmt->fetch(PDO::FETCH_ASSOC);

$limitRemain = $row['limit_remain'];
$acct_balance = $row['acct_balance'];
$avail_balance = $row['avail_balance'];

$fullName = $row['firstname']." ".$row['lastname'];
$email = $row['acct_email'];

// Check if user has card
$sql2 = "SELECT * FROM card WHERE user_id=:user_id";
$cardstmt = $conn->prepare($sql2);
$cardstmt->execute([
    'user_id' => $user_id
]);
$cardCheck = $cardstmt->fetch(PDO::FETCH_ASSOC);

$userStatus = userStatus($row);

// Initialize classes
$title = new pageTitle();
$email_message = new message();
$sendMail = new emailMessage();
$sendSms = new twilioController();

// Set currency symbol
if ($row['acct_currency'] === 'USD') {
    $currency = "$";
} elseif ($row['acct_currency'] === 'Euro') {
    $currency = "€";
} elseif ($row['acct_currency'] === 'Yuan') {
    $currency = "¥";
} elseif ($row['acct_currency'] === 'GBP') {
    $currency = "£";
} elseif ($row['acct_currency'] === 'CAD') {
    $currency = "¢";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$pageName?> - <?=$pageTitle ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="/assets/images/logo/favicon.png" />
    <style>
        /* Mobile Header Styles */
        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: #1a1a1a;
            z-index: 1001;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
        }
        
        .mobile-logo img {
            height: 35px;
        }
        
        .mobile-menu-toggle {
            background: none;
            border: none;
            width: 30px;
            height: 25px;
            position: relative;
            cursor: pointer;
            flex-direction: column;
            justify-content: space-between;
            display: flex;
        }
        
        .mobile-menu-toggle span {
            display: block;
            height: 3px;
            width: 100%;
            background: #afff1a;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .mobile-menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }
        
        .mobile-menu-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }
        
        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        
        .mobile-overlay.active {
            display: block;
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .mobile-header {
                display: flex;
            }
            
            .container {
                padding-top: 60px;
            }
            
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                height: 100vh;
                width: 280px;
                z-index: 1000;
                transition: left 0.3s ease;
                overflow-y: auto;
            }
            
            .sidebar.show-mobile {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Submenu styles */
        .has-submenu .submenu {
            display: none;
            padding-left: 20px;
        }
        
        .has-submenu.open .submenu {
            display: block;
        }
        
        .submenu-arrow {
            transition: transform 0.3s ease;
            margin-left: auto;
        }
        
        .has-submenu.open .submenu-arrow {
            transform: rotate(90deg);
        }
        
        .submenu li a {
            padding: 8px 15px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .submenu li a:hover {
            background-color: rgba(175, 255, 26, 0.1);
            color: #afff1a;
        }
    </style>
    <script src="script.js"></script>
</head>
<body>
    <div class="container">
        <!-- Mobile Header -->
        <div class="mobile-header">
            <div class="mobile-logo">
                <img src="/assets/images/logo/logo.png" alt="logo">
            </div>
            <button class="mobile-menu-toggle" id="mobile-menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
        
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobile-overlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="logo">
                <img src="/assets/images/logo/logo.png" alt="logo">
            </div>
            <nav class="nav-menu">
                <ul class="menu-list">
                    <li class="<?php active('dashboard.php');?>">
                        <a href="../user/dashboard.php">
                            <i class="fas fa-th-large"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="<?php active('deposit.php');?>">
                        <a href="../user/deposit.php">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Online Deposit</span>
                        </a>
                    </li>
                    <li class="<?php active('domestic-transfer.php');?>">
                        <a href="../user/domestic-transfer.php">
                            <i class="fas fa-share"></i>
                            <span>Domestic Transfer</span>
                        </a>
                    </li>
                    <li class="<?php active('wire-transfer.php');?>">
                        <a href="../user/wire-transfer.php">
                            <i class="fas fa-wifi"></i>
                            <span>Wire Transfer</span>
                        </a>
                    </li>
                    <li class="<?php active('card.php');?>">
                        <a href="../user/card.php">
                            <i class="fas fa-credit-card"></i>
                            <span>Virtual Card</span>
                        </a>
                    </li>
                    <li class="<?php active('loan.php');?>">
                        <a href="../user/loan.php">
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
                            <li><a href="../user/credit-debit_transaction.php">Credit / Debit Transaction</a></li>
                            <li><a href="../user/wire-transaction.php">Wire Transaction</a></li>
                            <li><a href="../user/domestic-transaction.php">Domestic Transaction</a></li>
                            <li><a href="../user/loan-transaction.php">Loan Transaction</a></li>
                            <li><a href="../user/withdrawal-transaction.php">All Withdrawal</a></li>
                        </ul>
                    </li>
                    <li class="<?php active('withdrawal.php');?>">
                        <a href="../user/withdrawal.php">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Withdrawal</span>
                        </a>
                    </li>
                    <li class="<?php active('account-manager.php');?>">
                        <a href="../user/account-manager.php">
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
                            <li><a href="../user/profile.php">Profile</a></li>
                            <li><a href="../user/edit-profile.php">Account</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <div class="logout">
                <a href="../user/logout.php"><i class="fas fa-sign-out-alt"></i> Log out</a>
            </div>
        </aside>
        
        </script>