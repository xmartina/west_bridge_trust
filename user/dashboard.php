<?php
$pageName = "Dashboard";
include_once("layouts/header.php");
//include_once("../include/userFunction.php");
if(!$_SESSION['acct_no']) {
    header("location:../login.php");
    die;
}
if(@!$_COOKIE['firstVisit']){
    setcookie("firstVisit", "no", time() + 3600);
    notify_alert('Welcome Back '.$fullName." !",'success','3000','Close');
}

unset($_SESSION['wire_transfer'], $_SESSION['dom_transfer']);

?>

<style>
/* Modern Mobile Banking Styles */
.mobile-banking-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 0;
    margin: 0;
}

.banking-header {
    background: transparent;
    padding: 20px;
    color: white;
}

.user-greeting {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.user-info h2 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
}

.user-info p {
    margin: 0;
    opacity: 0.8;
    font-size: 14px;
}

.notification-bell {
    background: rgba(255,255,255,0.2);
    border: none;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.balance-card {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 25px;
    margin: 20px;
    border: 1px solid rgba(255,255,255,0.2);
}

.balance-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.balance-label {
    color: rgba(255,255,255,0.8);
    font-size: 14px;
    margin: 0;
}

.eye-icon {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
}

.balance-amount {
    font-size: 36px;
    font-weight: 700;
    color: white;
    margin: 10px 0;
}

.account-number {
    color: rgba(255,255,255,0.7);
    font-size: 14px;
    margin: 0;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    padding: 20px;
}

.action-btn {
    background: white;
    border: none;
    border-radius: 15px;
    padding: 20px 10px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.2s;
    text-decoration: none;
    color: #333;
}

.action-btn:hover {
    transform: translateY(-2px);
    text-decoration: none;
    color: #333;
}

.action-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    color: white;
}

.action-label {
    font-size: 12px;
    font-weight: 500;
    margin: 0;
}

.content-section {
    background: #f8f9fa;
    border-radius: 25px 25px 0 0;
    margin-top: 20px;
    padding: 25px 20px;
    min-height: 60vh;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-title {
    font-size: 20px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.view-all-btn {
    color: #667eea;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.stat-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    color: white;
}

.stat-icon.limit {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
}

.stat-icon.loan {
    background: linear-gradient(135deg, #43e97b, #38f9d7);
}

.stat-icon.expenses {
    background: linear-gradient(135deg, #fa709a, #fee140);
}

.stat-icon.available {
    background: linear-gradient(135deg, #a8edea, #fed6e3);
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.stat-label {
    font-size: 14px;
    color: #666;
    margin: 5px 0 0 0;
}

.recent-transactions {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.transaction-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.transaction-item:last-child {
    border-bottom: none;
}

.transaction-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: white;
}

.transaction-icon.credit {
    background: linear-gradient(135deg, #43e97b, #38f9d7);
}

.transaction-icon.debit {
    background: linear-gradient(135deg, #fa709a, #fee140);
}

.transaction-details {
    flex: 1;
}

.transaction-title {
    font-size: 16px;
    font-weight: 500;
    color: #333;
    margin: 0;
}

.transaction-date {
    font-size: 12px;
    color: #666;
    margin: 2px 0 0 0;
}

.transaction-amount {
    font-size: 16px;
    font-weight: 600;
}

.transaction-amount.credit {
    color: #43e97b;
}

.transaction-amount.debit {
    color: #fa709a;
}

@media (max-width: 768px) {
    .quick-actions {
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        padding: 15px;
    }

    .action-btn {
        padding: 15px 8px;
    }

    .action-icon {
        width: 35px;
        height: 35px;
    }

    .action-label {
        font-size: 11px;
    }

    .balance-amount {
        font-size: 28px;
    }

    .stats-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}
</style>

<!--  BEGIN CONTENT AREA  -->
<div class="mobile-banking-container">
    <!-- Banking Header -->
    <div class="banking-header">
        <div class="user-greeting">
            <div class="user-info">
                <h2>Hello, <?php echo explode(' ', $fullName)[0]; ?>!</h2>
                <p>Welcome back to your account</p>
            </div>
            <button class="notification-bell">
                <i class="fas fa-bell"></i>
            </button>
        </div>

        <!-- Balance Card -->
        <div class="balance-card">
            <div class="balance-header">
                <p class="balance-label">Available Balance</p>
                <button class="eye-icon">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <h1 class="balance-amount"><?= $currency . number_format($avail_balance, 2) ?></h1>
            <p class="account-number">Account: <?= $row['acct_no'] ?></p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="wire-transfer.php" class="action-btn">
            <div class="action-icon">
                <i class="fas fa-paper-plane"></i>
            </div>
            <p class="action-label">Transfer</p>
        </a>
        <a href="deposit.php" class="action-btn">
            <div class="action-icon">
                <i class="fas fa-plus"></i>
            </div>
            <p class="action-label">Deposit</p>
        </a>
        <a href="withdrawal.php" class="action-btn">
            <div class="action-icon">
                <i class="fas fa-minus"></i>
            </div>
            <p class="action-label">Withdraw</p>
        </a>
        <a href="loan.php" class="action-btn">
            <div class="action-icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <p class="action-label">Loan</p>
        </a>
    </div>

    <!-- Content Section -->
    <div class="content-section">
        <!-- Account Stats -->
        <div class="section-header">
            <h3 class="section-title">Account Overview</h3>
            <a href="account-details.php" class="view-all-btn">View Details</a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon limit">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h4 class="stat-value"><?= $currency . number_format($row['acct_limit'], 0) ?></h4>
                <p class="stat-label">Credit Limit</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon available">
                    <i class="fas fa-wallet"></i>
                </div>
                <h4 class="stat-value"><?= $currency . number_format($acct_balance, 2) ?></h4>
                <p class="stat-label">Total Balance</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon loan">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h4 class="stat-value"><?= $currency . number_format($row['loan_balance'], 2) ?></h4>
                <p class="stat-label">Loan Balance</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon expenses">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h4 class="stat-value"><?= $currency . number_format($limitRemain, 2) ?></h4>
                <p class="stat-label">Available Credit</p>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="section-header">
            <h3 class="section-title">Recent Transactions</h3>
            <a href="credit-debit_transaction.php" class="view-all-btn">View All</a>
        </div>

        <div class="recent-transactions">
            <?php
            // Fetch recent transactions
            $sql = "SELECT * FROM transactions WHERE user_id = :user_id ORDER BY trans_id DESC LIMIT 5";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':user_id' => $user_id]);
            $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($transactions) > 0):
                foreach($transactions as $transaction):
                    $isCredit = $transaction['trans_type'] == 1;
                    $iconClass = $isCredit ? 'credit' : 'debit';
                    $amountClass = $isCredit ? 'credit' : 'debit';
                    $sign = $isCredit ? '+' : '-';
            ?>
            <div class="transaction-item">
                <div class="transaction-icon <?= $iconClass ?>">
                    <i class="fas fa-<?= $isCredit ? 'arrow-down' : 'arrow-up' ?>"></i>
                </div>
                <div class="transaction-details">
                    <h5 class="transaction-title"><?= htmlspecialchars($transaction['sender_name']) ?></h5>
                    <p class="transaction-date"><?= date('M d, Y', strtotime($transaction['created_at'])) ?></p>
                </div>
                <div class="transaction-amount <?= $amountClass ?>">
                    <?= $sign . $currency . number_format($transaction['amount'], 2) ?>
                </div>
            </div>
            <?php
                endforeach;
            else:
            ?>
            <div class="transaction-item">
                <div class="transaction-icon credit">
                    <i class="fas fa-info"></i>
                </div>
                <div class="transaction-details">
                    <h5 class="transaction-title">No transactions yet</h5>
                    <p class="transaction-date">Start using your account</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quick Transfer Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-
