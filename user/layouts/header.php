<?php
const rootDir = '/home/multistream6/domains/dashboard.westbridgetrust.com/public_html/';
require '../include/vendor/autoload.php';
require rootDir . 'include/vendor/autoload.php';
ob_start();
// session_start();
require_once ('../session.php');
require_once("../include/loginFunction.php");
//require_once("../include/userFunction.php");
require_once("../include/userClass.php");
require_once ("../include/twilioController.php");

//  session_start();
      if(isset($_SESSION["name"]))
      {
           if((time() - $_SESSION['last_login_timestamp']) > 60) // 900 = 15 * 60
           {
                header("location:logout.php");
           }
           else
           {
                $_SESSION['last_login_timestamp'] = time();
           }
      }

if(!$_SESSION['acct_no']) {
    header("location:../login.php");
    exit;
}

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

$viesConn="SELECT * FROM users WHERE acct_no = :acct_no";
$stmt = $conn->prepare($viesConn);

$stmt->execute([
    ':acct_no'=>$_SESSION['acct_no']
]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$user_id = $row['id'];
$acct_stat = $row['acct_status'];

// audit_logs
$sql = "SELECT * FROM audit_logs ORDER BY datenow DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();

$logs = $stmt->fetch(PDO::FETCH_ASSOC);

$device = $logs['device'];
$ipAddress = $logs['ipAddress'];
$datenow = $logs['datenow'];

// TEMP TRANSACTION FETCH
$sql = "SELECT * FROM temp_trans WHERE acct_id =:acct_id ORDER BY wire_id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'acct_id'=>$user_id
]);
$temp_trans = $stmt->fetch(PDO::FETCH_ASSOC);

$limitRemain = $row['limit_remain'];
$acct_balance = $row['acct_balance'];
$avail_balance = $row['avail_balance'];
$fullName = $row['firstname']." ".$row['lastname'];

$currency = $row['acct_currency'] == 'USD' ? '$' : $row['acct_currency'];

// User status check
if($acct_stat == 'active'){
    $userStatus = '<span class="badge badge-success">Active</span>';
} else {
    $userStatus = '<span class="badge badge-warning">Hold</span>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?= $pageTitle ?> - Mobile Banking</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico"/>

    <!-- Mobile Banking Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8f9fa;
            line-height: 1.6;
            color: #333;
            overflow-x: hidden;
        }

        /* Mobile Banking Navigation */
        .mobile-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e9ecef;
            padding: 10px 0;
            z-index: 1000;
            box-shadow: 0 -2px 20px rgba(0,0,0,0.1);
        }

        .nav-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 500px;
            margin: 0 auto;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #6c757d;
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 12px;
            min-width: 60px;
        }

        .nav-item.active {
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .nav-item:hover {
            color: #667eea;
            text-decoration: none;
            transform: translateY(-2px);
        }

        .nav-icon {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .nav-label {
            font-size: 11px;
            font-weight: 500;
        }

        /* Top Header */
        .top-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 15px 20px;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: none; /* Hidden by default, shown on specific pages */
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 500px;
            margin: 0 auto;
        }

        .back-btn {
            background: none;
            border: none;
            font-size: 20px;
            color: #333;
            cursor: pointer;
        }

        .page-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .header-action {
            background: none;
            border: none;
            font-size: 18px;
            color: #667eea;
            cursor: pointer;
        }

        /* Main Content Wrapper */
        .main-wrapper {
            padding-bottom: 80px; /* Space for bottom nav */
            min-height: 100vh;
        }

        /* Page specific styles */
        .page-with-header .main-wrapper {
            padding-top: 70px; /* Space for top header */
        }

        .page-with-header .top-header {
            display: block;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .nav-container {
                padding: 0 10px;
            }

            .nav-item {
                min-width: 50px;
                padding: 6px 8px;
            }

            .nav-icon {
                font-size: 18px;
            }

            .nav-label {
                font-size: 10px;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Loading animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Alert styles */
        .alert-container {
            position: fixed;
            top: 20px;
            left: 20px;
            right: 20px;
            z-index: 10000;
            max-width: 500px;
            margin: 0 auto;
        }

        .custom-alert {
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border-left: 4px solid #667eea;
            transform: translateY(-100px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .custom-alert.show {
            transform: translateY(0);
            opacity: 1;
        }

        .custom-alert.success {
            border-left-color: #28a745;
        }

        .custom-alert.error {
            border-left-color: #dc3545;
        }

        .custom-alert.warning {
            border-left-color: #ffc107;
        }
    </style>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Alert Container -->
    <div class="alert-container" id="alertContainer"></div>

    <!-- Top Header (shown on specific pages) -->
    <div class="top-header" id="topHeader">
        <div class="header-content">
            <button class="back-btn" onclick="history.back()">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h1 class="page-title" id="pageTitle"><?= $pageName ?? 'Banking' ?></h1>
            <button class="header-action" id="headerAction">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>
    </div>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper" id="mainWrapper">

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-nav">
        <div class="nav-container">
            <a href="dashboard.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="nav-label">Home</div>
            </a>

            <a href="credit-debit_transaction.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'credit-debit_transaction.php' ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="nav-label">Transactions</div>
            </a>

            <a href="wire-transfer.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'wire-transfer.php' ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="nav-label">Transfer</div>
            </a>

            <a href="card.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'card.php' ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="nav-label">Cards</div>
            </a>

            <a href="profile.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">
                <div class="nav-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="nav-label">Profile</div>
            </a>
        </div>
    </nav>

    <script>
        // Mobile Banking JavaScript Functions

        // Show loading overlay
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('show');
        }

        // Hide loading overlay
        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('show');
        }

        // Show custom alert
        function showAlert(message, type = 'info', duration = 3000) {
            const alertContainer = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `custom-alert ${type}`;
            alert.innerHTML = `
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; font-size: 18px; color: #999; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            alertContainer.appendChild(alert);

            // Show alert
            setTimeout(() => alert.classList.add('show'), 100);

            // Auto remove after duration
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 300);
            }, duration);
        }

        // Page navigation with loading
        function navigateToPage(url) {
            showLoading();
            setTimeout(() => {
                window.location.href = url;
            }, 500);
        }

        // Update page title and show header for specific
