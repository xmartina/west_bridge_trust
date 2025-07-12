<?php
$pageName = "Account Details";
include_once("layouts/header.php");

if(!$_SESSION['acct_no']) {
    header("location:../login.php");
    die;
}
?>

<style>
/* Account Details Specific Styles */
.account-details-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 0;
}

.account-header {
    background: transparent;
    padding: 20px;
    color: white;
}

.header-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.back-button {
    background: rgba(255,255,255,0.2);
    border: none;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    margin: 0;
}

.menu-button {
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

.account-summary-card {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 25px;
    margin: 0 20px 20px;
    border: 1px solid rgba(255,255,255,0.2);
}

.account-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.info-item {
    text-align: center;
}

.info-label {
    color: rgba(255,255,255,0.8);
    font-size: 12px;
    margin-bottom: 5px;
}

.info-value {
    color: white;
    font-size: 18px;
    font-weight: 600;
}

.account-number-display {
    background: rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: 15px;
    text-align: center;
    margin-top: 15px;
}

.account-number-label {
    color: rgba(255,255,255,0.7);
    font-size: 12px;
    margin-bottom: 5px;
}

.account-number-value {
    color: white;
    font-size: 20px;
    font-weight: 700;
    letter-spacing: 2px;
}

.content-tabs {
    background: #f8f9fa;
    border-radius: 25px 25px 0 0;
    padding: 0;
    min-height: 60vh;
}

.tab-navigation {
    display: flex;
    background: white;
    border-radius: 25px 25px 0 0;
    padding: 5px;
    margin: 0 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.tab-button {
    flex: 1;
    background: none;
    border: none;
    padding: 15px;
    border-radius: 20px;
    font-weight: 500;
    color: #666;
    transition: all 0.3s ease;
}

.tab-button.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.tab-content {
    padding: 20px;
    display: none;
}

.tab-content.active {
    display: block;
}

.chart-container {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.chart-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.chart-period {
    background: #f8f9fa;
    border: none;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 12px;
    color: #666;
}

.balance-chart {
    height: 200px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    font-size: 14px;
}

.stats-overview {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.stat-item {
    background: white;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    color: white;
}

.stat-icon.income {
    background: linear-gradient(135deg, #43e97b, #38f9d7);
}

.stat-icon.expense {
    background: linear-gradient(135deg, #fa709a, #fee140);
}

.stat-icon.transfer {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
}

.stat-icon.loan {
    background: linear-gradient(135deg, #a8edea, #fed6e3);
}

.stat-amount {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.stat-label {
    font-size: 12px;
    color: #666;
    margin: 5px 0 0 0;
}

.account-details-list {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-size: 14px;
    color: #666;
}

.detail-value {
    font-size: 14px;
    font-weight: 500;
    color: #333;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.active {
    background: rgba(67, 233, 123, 0.2);
    color: #43e97b;
}

.status-badge.hold {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-top: 20px;
}

.action-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    text-decoration: none;
    color: #333;
    transition: transform 0.2s;
}

.action-card:hover {
    transform: translateY(-2px);
    text-decoration: none;
    color: #333;
}

.action-card-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: white;
}

.action-card-icon.transfer {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.action-card-icon.statement {
    background: linear-gradient(135deg, #43e97b, #38f9d7);
}

.action-card-icon.support {
    background: linear-gradient(135deg, #fa709a, #fee140);
}

.action-card-icon.settings {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
}

.action-card-title {
    font-size: 14px;
    font-weight: 600;
    margin: 0;
}

@media (max-width: 768px) {
    .account-info-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .stats-overview {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .quick-actions-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}
</style>

<div class="account-details-container">
    <!-- Account Header -->
    <div class="account-header">
        <div class="header-nav">
            <a href="dashboard.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="page-title">Account Details</h1>
            <button class="menu-button">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>

        <!-- Account Summary Card -->
        <div class="account-summary-card">
            <div class="account-info-grid">
                <div class="info-item">
                    <p class="info-label">Total Balance</p>
                    <p class="info-value"><?= $currency . number_format($acct_balance, 2) ?></p>
                </div>
                <div class="info-item">
                    <p class="info-label">Available</p>
                    <p class="info-value"><?= $currency . number_format($avail_balance, 2) ?></p>
                </div>
                <div class="info-item">
                    <p class="info-label">Credit Limit</p>
                    <p class="info-value"><?= $currency . number_format($row['acct_limit'], 0) ?></p>
                </div>
                <div class="info-item">
                    <p class="info-label">Loan Balance</p>
                    <p class="info-value"><?= $currency . number_format($row['loan_balance'], 2) ?></p>
                </div>
            </div>

            <div class="account-number-display">
                <p class="account-number-label">Account Number</p>
                <p class="account-number-value"><?= $row['acct_no'] ?></p>
            </div>
        </div>
    </div>

    <!-- Content Tabs -->
    <div class="content-tabs">
        <div class="tab-navigation">
            <button class="tab-button active" onclick="switchTab('overview')">Overview</button>
            <button class="tab-button" onclick="switchTab('details')">Details</button>
            <button class="tab-button" onclick="switchTab('actions')">Actions</button>
        </div>

        <!-- Overview Tab -->
        <div class="tab-content active" id="overview">
            <!-- Balance Chart -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">Balance Trend</h3>
                    <select class="chart-period">
                        <option>Last 30 days</option>
                        <option>Last 3 months</option>
                        <option>Last 6 months</option>
                        <option>Last year</option>
                    </select>
                </div>
                <div class="balance-chart">
                    <i class="fas fa-chart-line" style="margin-right: 10px;"></i>
                    Balance trend chart will be displayed here
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="stats-overview">
                <?php
                // Calculate monthly stats
                $sql = "SELECT
                    SUM(CASE WHEN trans_type = 1 THEN amount ELSE 0 END) as total_income,
                    SUM(CASE WHEN trans_type = 2 THEN amount ELSE 0 END) as total_expense,
                    COUNT(CASE WHEN trans_type = 1 THEN 1 END) as income_count,
                    COUNT(CASE WHEN trans_type = 2 THEN 1 END) as expense_count
                    FROM transactions
                    WHERE user_id = :user_id
                    AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':user_id' => $user_id]);
                $stats = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>

                <div class="stat-item">
                    <div class="stat-icon income">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <h4 class="stat-amount"><?= $currency . number_format($stats['total_income'] ?? 0, 2) ?></h4>
                    <p class="stat-label">Monthly Income</p>
                </div>

                <div class="stat-item">
                    <div class="stat-icon expense">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <h4 class="stat-amount"><?= $currency . number_format($stats['total_expense'] ?? 0, 2) ?></h4>
                    <p class="stat-label">Monthly Expenses</p>
                </div>

                <div class="stat-item">
                    <div class="stat-icon transfer">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h4 class="stat-amount"><?= ($stats['income_count'] ?? 0) + ($stats['expense_count'] ?? 0) ?></h4>
                    <p class="stat-label">Total Transactions</p>
                </div>

                <div class="stat-item">
                    <div class="stat-icon loan">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h4 class="stat-amount"><?= $currency . number_format($limitRemain, 2) ?></h4>
                    <p class="stat-label">Available Credit</p>
                </div>
            </div>
        </div>

        <!-- Details Tab -->
        <div class="tab-content" id="details">
            <div class="account-details-list">
                <div class="detail-item">
                    <span class="detail-label">Account Holder</span>
                    <span class="detail-value"><?= $fullName ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Account Type</span>
                    <span class="detail-value"><?= $row['acct_type'] ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Account Status</span>
                    <span class="status-badge <?= $acct_stat ?>"><?= ucfirst($acct_stat) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Currency</span>
                    <span class="detail-value"><?= $row['acct_currency'] ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email</span>
                    <span class="detail-value"><?= $row['acct_email'] ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Phone</span>
                    <span class="detail-value"><?= $row['acct_phone'] ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date Opened</span>
                    <span class="detail-value"><?= date('M d, Y', strtotime($row['createdAt'])) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Last Login IP</span>
                    <span class="detail-value"><?= $ipAddress ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Last Login</span>
                    <span class="detail-value"><?= date('M d, Y H:i', strtotime($datenow)) ?></span>
                </div>
            </div>
        </div>

        <!-- Actions Tab -->
        <div class="tab-content" id="actions">
            <div class="quick-actions-grid">
                <a href="wire-transfer.php" class="action-card">
                    <div class="action-card-icon transfer">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <h4 class="action-card-title">Wire Transfer</h4>
                </a>

                <a href="credit-debit_transaction.php" class="action-card">
                    <div class="action-card-icon statement">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h4 class="action-card-title">View Statement</h4>
                </a>

                <a href="account-manager.php" class="action-card">
                    <div class="action-card-icon support">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4 class="action-card-title">Account Manager</h4>
                </a>

                <a href="edit-profile.php" class="action-card">
                    <div class="action-card-icon settings">
                        <i class="fas fa-cog"></i>
                    </div>
                    <h4 class="action-card-title">Account Settings</h4>
                </a>

                <a href="loan.php" class="action-card">
                    <div class="action-card-icon transfer">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h4 class="action-card-title">Apply for Loan</h4>
                </a>

                <a href="card.php" class="action-card">
                    <div class="action-card-icon statement">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h4 class="action-card-title">Virtual Card</h4>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.remove('active');
    });

    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active');
    });

    // Show selected tab content
    document.getElementById(tabName).classList.add('active');

    // Add active class to clicked button
    event.target.classList.add('active');
}

// Add smooth scrolling and animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate stat items on load
    const statItems = document.querySelectorAll('.stat-item');
    statItems.forEach((item, index) => {
        setTimeout(() => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'all 0.5s ease';

            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 100);
        }, index * 100);
    });

    // Add click animations to action cards
    const actionCards = document.querySelectorAll('.action-card');
    actionCards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Add ripple effect
            const ripple = document.createElement('div');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(102, 126, 234, 0.3)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.left = (e.clientX - card.offsetLeft - 25) + 'px';
            ripple.style.top = (e.clientY - card.offsetTop - 25) + 'px';
            ripple.style.width = '50px';
            ripple.style.height = '50px';

            card.style.position = 'relative';
            card.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});

// Add CSS for ripple animation
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>

<?php include_once('layouts/footer.php'); ?>
