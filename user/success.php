<?php
$pageName = "Success";
include_once("layouts/header.php");
include("./userPinfunction.php");

//TEMP TRANSACTION FETCH
$sql = "SELECT * FROM wire_transfer WHERE acct_id =:acct_id ORDER BY wire_id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'acct_id'=>$user_id
]);
$wire_trans = $stmt->fetch(PDO::FETCH_ASSOC);

$status = wireStatus($wire_trans);
?>

<style>
    :root {
        --primary-color: #104042;
        --secondary-color-1: #afff1a;
        --secondary-color-2: #FFD200;
        --white: #ffffff;
        --light-gray: #f8f9fa;
        --dark-text: #333333;
        --success-color: #2e7d32;
        --danger-color: #d32f2f;
    }
    
    /* Success Card Styles */
    .success-container {
        max-width: 800px;
        margin: 40px auto;
    }
    
    .success-card {
        background-color: var(--white);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(16, 64, 66, 0.1);
        overflow: hidden;
        position: relative;
    }
    
    .success-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #0d3335 100%);
        color: var(--white);
        padding: 30px;
        text-align: center;
        position: relative;
    }
    
    .success-icon {
        width: 80px;
        height: 80px;
        background-color: var(--secondary-color-1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: var(--primary-color);
        font-size: 40px;
        box-shadow: 0 10px 20px rgba(175, 255, 26, 0.3);
    }
    
    .success-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .success-message {
        font-size: 16px;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .success-body {
        padding: 30px;
    }
    
    .transaction-details {
        margin-bottom: 30px;
    }
    
    .transaction-message {
        background-color: rgba(16, 64, 66, 0.05);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        border-left: 4px solid var(--secondary-color-2);
    }
    
    .transaction-message p {
        color: var(--primary-color);
        font-weight: 500;
        margin-bottom: 15px;
    }
    
    .progress-container {
        width: 100%;
        height: 10px;
        background-color: #e9ecef;
        border-radius: 5px;
        margin-top: 10px;
        overflow: hidden;
    }
    
    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--secondary-color-1), var(--secondary-color-2));
        border-radius: 5px;
        width: 100%;
        transition: width 1.5s ease;
        position: relative;
        overflow: hidden;
    }
    
    .progress-bar::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0.4) 50%,
            rgba(255, 255, 255, 0) 100%
        );
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .transaction-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }
    
    .transaction-table td {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .transaction-table tr:last-child td {
        border-bottom: none;
    }
    
    .transaction-table td:first-child {
        font-weight: 600;
        color: var(--primary-color);
        width: 40%;
    }
    
    .transaction-table td:last-child {
        font-weight: 500;
    }
    
    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .status-completed {
        background-color: rgba(46, 125, 50, 0.1);
        color: var(--success-color);
    }
    
    .status-pending {
        background-color: rgba(255, 210, 0, 0.1);
        color: #ff8f00;
    }
    
    .status-processing {
        background-color: rgba(33, 150, 243, 0.1);
        color: #1976d2;
    }
    
    .status-failed {
        background-color: rgba(211, 47, 47, 0.1);
        color: var(--danger-color);
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 20px;
    }
    
    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        color: var(--white);
    }
    
    .btn-primary:hover {
        background-color: #0d3335;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 64, 66, 0.2);
    }
    
    .btn-outline {
        background-color: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }
    
    .btn-outline:hover {
        background-color: rgba(16, 64, 66, 0.05);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 64, 66, 0.1);
    }
    
    .btn-success {
        background-color: var(--success-color);
        color: var(--white);
    }
    
    .btn-success:hover {
        background-color: #2e7d32;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(46, 125, 50, 0.2);
    }
    
    @media (max-width: 576px) {
        .success-container {
            margin: 20px auto;
        }
        
        .success-header {
            padding: 20px;
        }
        
        .success-body {
            padding: 20px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="main-content">
    <header class="header">
        <h1>Transaction Status</h1>
        <div class="search-notification">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search">
            </div>
            <div class="user-profile">
                <div class="notification">
                    <i class="far fa-bell"></i>
                </div>
                <div class="avatar">
                    <?php if($row['image'] == null): ?>
                        <div style="width: 40px; height: 40px; background-color: #104042; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                            <?php echo substr($fullName, 0, 1); ?>
                        </div>
                    <?php else: ?>
                        <img src="../assets/profile/<?php echo $row['image']; ?>" alt="User avatar">
                    <?php endif; ?>
                </div>
                <div class="user-name"><?php echo $fullName; ?></div>
            </div>
        </div>
    </header>
    
    <div class="success-container">
        <div class="success-card">
            <?php if($_SESSION['wire_transfer']): ?>
                <?php
                    $amount = $wire_trans['amount'];
                    $bank_name = $wire_trans['bank_name'];
                    $acct_name = $wire_trans['acct_name'];
                    $acct_number = $wire_trans['acct_number'];
                    $acct_country = $wire_trans['acct_country'];
                    $acct_swift = $wire_trans['acct_swift'];
                    $acct_routing = $wire_trans['acct_routing'];
                    $acct_type = $wire_trans['acct_type'];

                    $APP_NAME = $pageTitle;
                    $message = $sendMail->UserWireTransfer($currency, $amount, $fullName, $bank_name, $acct_name, $acct_number, $acct_country, $acct_swift, $acct_routing, $acct_type, $APP_NAME);
                    // User Email
                    $subject = "Wire Transfer - $APP_NAME";
                    $email_message->send_mail($email, $message, $subject);
                    // Admin Email
                    $subject = "Wire Transfer - $APP_NAME";
                    $email_message->send_mail(WEB_EMAIL, $message, $subject);
                ?>
                
                <div class="success-header">
                    <div class="success-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <h1 class="success-title">Transfer is being processed</h1>
                    <p class="success-message">Your wire transfer has been initiated and is on its way to the recipient.</p>
                </div>
                
                <div class="success-body">
                    <div class="transaction-message">
                        <p>Dear <strong><?= ucwords($fullName) ?></strong>, your transfer to <strong><?= strtoupper($wire_trans['acct_name']) ?></strong> is being processed. It will be completed within 48 to 72 hours.</p>
                        <div class="progress-container">
                            <div class="progress-bar"></div>
                        </div>
                    </div>
                    
                    <div class="transaction-details">
                        <table class="transaction-table">
                            <tr>
                                <td>Amount</td>
                                <td><?= $currency . $wire_trans['amount'] ?></td>
                            </tr>
                            <tr>
                                <td>Reference ID</td>
                                <td><?= $wire_trans['refrence_id'] ?></td>
                            </tr>
                            <tr>
                                <td>Bank Name</td>
                                <td><?= $wire_trans['bank_name'] ?></td>
                            </tr>
                            <tr>
                                <td>Account Name</td>
                                <td><?= $wire_trans['acct_name'] ?></td>
                            </tr>
                            <tr>
                                <td>Account No</td>
                                <td><?= $wire_trans['acct_number'] ?></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>
                                    <?php if($status == "Completed"): ?>
                                        <span class="status-badge status-completed"><?= $status ?></span>
                                    <?php elseif($status == "Pending"): ?>
                                        <span class="status-badge status-pending"><?= $status ?></span>
                                    <?php elseif($status == "Processing"): ?>
                                        <span class="status-badge status-processing"><?= $status ?></span>
                                    <?php else: ?>
                                        <span class="status-badge status-processing"><?= $status ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Date & Time</td>
                                <td><?= date("M d, Y - h:i A", strtotime($wire_trans['created_at'])) ?></td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="./dashboard.php" class="btn btn-primary">
                            <i class="fas fa-home"></i> Back to Dashboard
                        </a>
                        <button onclick="window.print()" class="btn btn-outline">
                            <i class="fas fa-print"></i> Print Receipt
                        </button>
                        <a href="./wire-transfer.php" class="btn btn-success">
                            <i class="fas fa-exchange-alt"></i> New Transfer
                        </a>
                    </div>
                </div>
                
            <?php elseif($_SESSION['dom_transfer']): ?>
                <?php
                    //DOMESTIC TRANSACTION FETCH
                    $sql = "SELECT * FROM domestic_transfer WHERE acct_id =:acct_id ORDER BY dom_id DESC LIMIT 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        'acct_id'=>$user_id
                    ]);
                    $dom_transfer = $stmt->fetch(PDO::FETCH_ASSOC);
                    $status = domestic($dom_transfer);

                    $amount = $dom_transfer['amount'];
                    $bank_name = $dom_transfer['bank_name'];
                    $acct_name = $dom_transfer['acct_name'];
                    $acct_number = $dom_transfer['acct_number'];
                    $acct_type = $dom_transfer['acct_type'];
                    
                    $APP_NAME = $pageTitle;
                    $message = $sendMail->UserDomTransfer($currency, $amount, $fullName, $bank_name,$acct_name, $acct_number, $acct_type, $APP_NAME);
                    // User Email
                    $subject = "Domestic Transfer - $APP_NAME";
                    $email_message->send_mail($email, $message, $subject);
                    // Admin Email
                    $subject = "Domestic Transfer - $APP_NAME";
                    $email_message->send_mail(WEB_EMAIL, $message, $subject);
                ?>
                
                <div class="success-header">
                    <div class="success-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <h1 class="success-title">Transfer Successfully Initiated</h1>
                    <p class="success-message">Your domestic transfer has been initiated and is on its way to the recipient.</p>
                </div>
                
                <div class="success-body">
                    <div class="transaction-message">
                        <p>Dear <strong><?= ucwords($fullName) ?></strong>, your transfer to <strong><?= strtoupper($dom_transfer['acct_name']) ?></strong> is being processed. It will be completed within 48 to 72 hours.</p>
                        <div class="progress-container">
                            <div class="progress-bar"></div>
                        </div>
                    </div>
                    
                    <div class="transaction-details">
                        <table class="transaction-table">
                            <tr>
                                <td>Amount</td>
                                <td><?= $currency . $dom_transfer['amount'] ?></td>
                            </tr>
                            <tr>
                                <td>Reference ID</td>
                                <td><?= $dom_transfer['refrence_id'] ?></td>
                            </tr>
                            <tr>
                                <td>Bank Name</td>
                                <td><?= $dom_transfer['bank_name'] ?></td>
                            </tr>
                            <tr>
                                <td>Account Name</td>
                                <td><?= $dom_transfer['acct_name'] ?></td>
                            </tr>
                            <tr>
                                <td>Account No</td>
                                <td><?= $dom_transfer['acct_number'] ?></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>
                                    <?php if($status == "Completed"): ?>
                                        <span class="status-badge status-completed"><?= $status ?></span>
                                    <?php elseif($status == "Pending"): ?>
                                        <span class="status-badge status-pending"><?= $status ?></span>
                                    <?php elseif($status == "Processing"): ?>
                                        <span class="status-badge status-processing"><?= $status ?></span>
                                    <?php else: ?>
                                        <span class="status-badge status-processing"><?= $status ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Date & Time</td>
                                <td><?= date("M d, Y - h:i A", strtotime($dom_transfer['created_at'])) ?></td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="./dashboard.php" class="btn btn-primary">
                            <i class="fas fa-home"></i> Back to Dashboard
                        </a>
                        <button onclick="window.print()" class="btn btn-outline">
                            <i class="fas fa-print"></i> Print Receipt
                        </button>
                        <a href="./domestic-transfer.php" class="btn btn-success">
                            <i class="fas fa-exchange-alt"></i> New Transfer
                        </a>
                    </div>
                </div>
                
            <?php else: ?>
                <div class="success-header">
                    <div class="success-icon" style="background-color: #ffcdd2; color: #d32f2f;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h1 class="success-title">No Transaction Found</h1>
                    <p class="success-message">We couldn't find the transaction you're looking for.</p>
                </div>
                
                <div class="success-body">
                    <div class="transaction-message" style="border-left: 4px solid #d32f2f;">
                        <p>SORRY WE CAN'T FIND WHAT YOU ARE LOOKING FOR!</p>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="./dashboard.php" class="btn btn-primary">
                            <i class="fas fa-home"></i> GO HOME
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include_once("layouts/footer.php");
?>
