<?php
$pageName = "Transactions";
include_once("layouts/header.php");

if(!$_SESSION['acct_no']) {
    header("location:../login.php");
    die;
}

// Get filter parameters
$filter_type = $_GET['type'] ?? 'all';
$filter_period = $_GET['period'] ?? '30';
$search_query = $_GET['search'] ?? '';

// Build SQL query based on filters
$sql = "SELECT * FROM transactions WHERE user_id = :user_id";
$params = [':user_id' => $user_id];

if($filter_type !== 'all') {
    $sql .= " AND trans_type = :trans_type";
    $params[':trans_type'] = $filter_type;
}

if($search_query) {
    $sql .= " AND (sender_name LIKE :search OR description LIKE :search)";
    $params[':search'] = '%' . $search_query . '%';
}

if($filter_period !== 'all') {
    $sql .= " AND created_at >= DATE_SUB(NOW(), INTERVAL :period DAY)";
    $params[':period'] = $filter_period;
}

$sql .= " ORDER BY trans_id DESC LIMIT 100";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get summary stats
$summary_sql = "SELECT
    COUNT(*) as total_count,
    SUM(CASE WHEN trans_type = 1 THEN amount ELSE 0 END) as total_income,
    SUM(CASE WHEN trans_type = 2 THEN amount ELSE 0 END) as total_expense
    FROM transactions WHERE user_id = :user_id";

$summary_params = [':user_id' => $user_id];

if($filter_period !== 'all') {
    $summary_sql .= " AND created_at >= DATE_SUB(NOW(), INTERVAL :period DAY)";
    $summary_params[':period'] = $filter_period;
}

$summary_stmt = $conn->prepare($summary_sql);
$summary_stmt->execute($summary_params);
$summary = $summary_stmt->fetch(PDO::FETCH_ASSOC);
?>

<style>
/* Modern Transaction Page Styles */
.transaction-page-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 0;
}

.transaction-header {
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

.filter-toggle {
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

.search-filter-container {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid rgba(255,255,255,0.2);
}

.search-box {
    position: relative;
    margin-bottom: 15px;
}

.search-input {
    background: rgba(255,255,255,0.2);
    border: none;
    border-radius: 12px;
    padding: 12px 15px 12px 45px;
    width: 100%;
    color: white;
    font-size: 16px;
}

.search-input::placeholder {
    color: rgba(255,255,255,0.7);
}

.search-input:focus {
    outline: none;
    background: rgba(255,255,255,0.3);
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255,255,255,0.7);
}

.filter-chips {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 5px;
}

.filter-chip {
    background: rgba(255,255,255,0.2);
    border: none;
    border-radius: 20px;
    padding: 8px 16px;
    color: white;
    font-size: 14px;
    white-space: nowrap;
    transition: all 0.3s ease;
    text-decoration: none;
}

.filter-chip.active {
    background: rgba(255,255,255,0.4);
}

.filter-chip:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    text-decoration: none;
}

.content-section {
    background: #f8f9fa;
    border-radius: 25px 25px 0 0;
    padding: 25px 20px;
    min-height: 60vh;
}

.summary-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 25px;
}

.summary-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.summary-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    color: white;
}

.summary-icon.total {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
}

.summary-icon.income {
    background: linear-gradient(135deg, #43e97b, #38f9d7);
}

.summary-icon.expense {
    background: linear-gradient(135deg, #fa709a, #fee140);
}

.summary-value {
    font-size: 16px;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.summary-label {
    font-size: 12px;
    color: #666;
    margin: 5px 0 0 0;
}

.transactions-container {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.export-btn {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    color: white;
    font-size: 12px;
    font-weight: 500;
    text-decoration: none;
}

.transaction-list {
    max-height: 60vh;
    overflow-y: auto;
}

.transaction-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.3s ease;
    cursor: pointer;
}

.transaction-item:last-child {
    border-bottom: none;
}

.transaction-item:hover {
    background: #f8f9fa;
    margin: 0 -20px;
    padding: 15px 20px;
    border-radius: 12px;
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
    flex-shrink: 0;
}

.transaction-icon.credit {
    background: linear-gradient(135deg, #43e97b, #38f9d7);
}

.transaction-icon.debit {
    background: linear-gradient(135deg, #fa709a, #fee140);
}

.transaction-details {
    flex: 1;
    min-width: 0;
}

.transaction-title {
    font-size: 16px;
    font-weight: 500;
    color: #333;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.transaction-description {
    font-size: 12px;
    color: #666;
    margin: 2px 0 0 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.transaction-date {
    font-size: 11px;
    color: #999;
    margin: 2px 0 0 0;
}

.transaction-amount {
    text-align: right;
    flex-shrink: 0;
}

.amount-value {
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}

.amount-value.credit {
    color: #43e97b;
}

.amount-value.debit {
    color: #fa709a;
}

.transaction-status {
    font-size: 11px;
    color: #666;
    margin: 2px 0 0 0;
}

.status-badge {
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 500;
}

.status-badge.completed {
    background: rgba(67, 233, 123, 0.2);
    color: #43e97b;
}

.status-badge.pending {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.empty-icon {
    font-size: 48px;
    color: #ddd;
    margin-bottom: 15px;
}

.empty-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
}

.empty-message {
    font-size: 14px;
    color: #999;
}

.load-more-btn {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 12px;
    padding: 15px;
    width: 100%;
    color: white;
    font-size: 16px;
    font-weight: 500;
    margin-top: 20px;
}

/* Transaction Detail Modal */
.transaction-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    display: none;
    align-items: flex-end;
}

.transaction-modal.show {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 25px 25px 0 0;
    padding: 25px;
    width: 100%;
    max-height: 80vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.modal-title {
    font-size: 20px;
    font-weight: 600;
    margin: 0;
}

.close-modal {
    background: none;
    border: none;
    font-size: 24px;
    color: #666;
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
    text-align: right;
}

@media (max-width: 768px) {
    .summary-cards {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .filter-chips {
        gap: 8px;
    }

    .filter-chip {
        padding: 6px 12px;
        font-size: 12px;
    }

    .transaction-title {
        font-size: 14px;
    }

    .amount-value {
        font-size: 14px;
    }
}
</style>

<div class="transaction-page-container">
    <!-- Transaction Header -->
    <div class="transaction-header">
        <div class="header-nav">
            <a href="dashboard.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="page-title">Transactions</h1>
            <button class="filter-toggle" onclick="toggleAdvancedFilters()">
                <i class="fas fa-sliders-h"></i>
            </button>
        </div>

        <!-- Search and Filters -->
        <div class="search-filter-container">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <form method="GET" action="">
                    <input type="text" class="search-input" placeholder="Search transactions..."
                           name="search" value="<?= htmlspecialchars($search_query) ?>" onchange="this.form.submit()">
                    <input type="hidden" name="type" value="<?= $filter_type ?>">
                    <input type="hidden" name="period" value="<?= $filter_period ?>">
                </form>
            </div>

            <div class="filter-chips">
                <a href="?type=all&period=<?= $filter_period ?>&search=<?= urlencode($search_query) ?>"
                   class="filter-chip <?= $filter_type === 'all' ? 'active' : '' ?>">All</a>
                <a href="?type=1&period=<?= $filter_period ?>&search=<?= urlencode($search_query) ?>"
                   class="filter-chip <?= $filter_type === '1' ? 'active' : '' ?>">Income</a>
                <a href="?type=2&period=<?= $filter_period ?>&search=<?= urlencode($search_query) ?>"
                   class="filter-chip <?= $filter_type === '2' ? 'active' : '' ?>">Expenses</a>
                <a href="?type=<?= $filter_type ?>&period=7&search=<?= urlencode($search_query) ?>"
                   class="filter-chip <?= $filter_period === '7' ? 'active' : '' ?>">7 Days</a>
                <a href="?type=<?= $filter_type ?>&period=30&search=<?= urlencode($search_query) ?>"
                   class="filter-chip <?= $filter_period === '30' ? 'active' : '' ?>">30 Days</a>
                <a href="?type=<?= $filter_type ?>&period=90&search=<?= urlencode($search_query) ?>"
                   class="filter-chip <?= $filter_period === '90' ? 'active' : '' ?>">90 Days</a>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="content-section">
        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-icon total">
                    <i class="fas fa-list"></i>
                </div>
                <h4 class="summary-value"><?= $summary['total_count'] ?></h4>
                <p class="summary-label">Total</p>
            </div>

            <div class="summary-card">
                <div class="summary-icon income">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <h4 class="summary-value"><?= $currency . number_format($summary['total_income'] ?? 0, 2) ?></h4>
                <p class="summary-label">Income</p>
            </div>

            <div class="summary-card">
                <div class="summary-icon expense">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <h4 class="summary-value"><?= $currency . number_format($summary['total_expense'] ?? 0, 2) ?></h4>
                <p class="summary-label">Expenses</p>
            </div>
        </div>

        <!-- Transactions List -->
        <div class="transactions-container">
            <div class="section-header">
                <h3 class="section-title">Transaction History</h3>
                <a href="javascript:void(0)" class="export-btn" onclick="exportTransactions()">
                    <i class="fas fa-download"></i> Export
                </a>
            </div>

            <div class="transaction-list">
                <?php if(count($transactions) > 0): ?>
                    <?php foreach($transactions as $transaction):
                        $isCredit = $transaction['trans_type'] == 1;
                        $iconClass = $isCredit ? 'credit' : 'debit';
                        $amountClass = $isCredit ? 'credit' : 'debit';
                        $sign = $isCredit ? '+' : '-';
                        $statusClass = $transaction['trans_status'] == 1 ? 'completed' : 'pending';
                        $statusText = $transaction['trans_status'] == 1 ? 'Completed' : 'Pending';
                    ?>
                    <div class="transaction-item" onclick="showTransactionDetails(<?= $transaction['trans_id'] ?>)">
                        <div class="transaction-icon <?= $iconClass ?>">
                            <i class="fas fa-<?= $isCredit ? 'arrow-down' : 'arrow-up' ?>"></i>
                        </div>
                        <div class="transaction-details">
                            <h5 class="transaction-title"><?= htmlspecialchars($transaction['sender_name']) ?></h5>
                            <p class="transaction-description"><?= htmlspecialchars($transaction['description']) ?></p>
                            <p class="transaction-date"><?= date('M d, Y • H:i', strtotime($transaction['created_at'])) ?></p>
                        </div>
                        <div class="transaction-amount">
                            <p class="amount-value <?= $amountClass ?>">
                                <?= $sign . $currency . number_format($transaction['amount'], 2) ?>
                            </p>
                            <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php if(count($transactions) >= 100): ?>
                    <button class="load-more-btn" onclick="loadMoreTransactions()">
                        Load More Transactions
                    </button>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h4 class="empty-title">No transactions found</h4>
                        <p class="empty-message">Try adjusting your filters or search terms</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Detail Modal -->
<div class="transaction-modal" id="transactionModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Transaction Details</h3>
            <button class="close-modal" onclick="hideTransactionModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="transactionDetails">
            <!-- Transaction details will be loaded here -->
        </div>
    </div>
</div>

<script>
function showTransactionDetails(transactionId) {
    // Find transaction data
    const transactions = <?= json_encode($transactions) ?>;
    const transaction = transactions.find(t => t.trans_id == transactionId);

    if (!transaction) return;

    const isCredit = transaction.trans_type == '1';
    const sign = isCredit ? '+' : '-';
    const amountClass = isCredit ? 'credit' : 'debit';
    const statusText = transaction.trans_status == '1' ? 'Completed' : 'Pending';
    const statusClass = transaction.trans_status == '1' ? 'completed' : 'pending';

    const detailsHtml = `
        <div class="detail-item">
            <span class="detail-label">Transaction ID</span>
            <span class="detail-value">#${transaction.refrence_id || transaction.trans_id}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Amount</span>
            <span class="detail-value ${amountClass}">${sign}<?= $currency ?>${parseFloat(transaction.amount).toLocaleString()}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Type</span>
            <span class="detail-value">${isCredit ? 'Credit' : 'Debit'}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Sender/Receiver</span>
            <span class="detail-value">${transaction.sender_name}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Description</span>
            <span class="detail-value">${transaction.description}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Date</span>
            <span class="detail-value">${new Date(transaction.created_at).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            })}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Status</span>
            <span class="status-badge ${statusClass}">${statusText}</span>
        </div>
    `;

    document.getElementById('transactionDetails').innerHTML = detailsHtml;
    document.getElementById('transactionModal').classList.add('show');
}

function hideTransactionModal() {
    document.getElementById('transactionModal').classList.remove('show');
}

function exportTransactions() {
    // Create CSV content
    let csvContent = "Date,Type,Description,Sender/Receiver,Amount,Status\n";

    <?php foreach($transactions as $transaction): ?>
    csvContent += "<?= date('Y-m-d H:i', strtotime($transaction['created_at'])) ?>," +
                  "<?= $transaction['trans_type'] == 1 ? 'Credit' : 'Debit' ?>," +
                  "<?= addslashes($transaction['description']) ?>," +
                  "<?= addslashes($transaction['sender_name']) ?>," +
                  "<?= $transaction['amount'] ?>," +
                  "<?= $transaction['trans_status'] == 1 ? 'Completed' : 'Pending' ?>\n";
    <?php endforeach; ?>

    // Download CSV
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'transactions_' + new Date().toISOString().split('T')[0] + '.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

function loadMoreTransactions() {
    // Implement pagination
    console.log('Load more transactions');
}

function toggleAdvancedFilters() {
    // Toggle advanced filter options
    console.log('Toggle advanced filters');
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('transactionModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideTransactionModal();
        }
    });

    // Add animation to transaction items
    const transactionItems = document.querySelectorAll('.transaction-item');
    transactionItems.forEach((item, index) => {
        setTimeout(() => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'all 0.3s ease';

            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 50);
        }, index * 50);
    });
});
</script>

<?php include_once('layouts/footer.php');
