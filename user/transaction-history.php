<?php
$pageName = "Transaction History";
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

$sql .= " ORDER BY trans_id DESC LIMIT 50";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
/* Transaction History Styles */
.transaction-history-container {
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

.filter-button {
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

.search-container {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid rgba(255,255,255,0.2);
}

.search-input {
    background: rgba(255,255,255,0.2);
    border: none;
    border-radius: 12px;
    padding: 12px 15px;
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

.filter-chips {
    display: flex;
    gap: 10px;
    margin-top: 15px;
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

.summary-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 25px;
}

.stat-card {
    background: white;
    border-radius: 15px;
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

.stat-icon.total {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
}

.stat-icon.income {
    background: linear-gradient(135deg, #43e97b, #38f9d7);
}

.stat-icon.expense {
    background: linear-gradient(135deg, #fa709a, #fee140);
}

.stat-value {
    font-size: 16px;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.stat-label {
    font-size: 12px;
    color: #666;
    margin: 5px 0 0 0;
}

.transactions-list {
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

.export-button {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    color: white;
    font-size: 12px;
    font-weight: 500;
}

.transaction-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.3s ease;
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

.transaction-description {
    font-size: 12px;
    color: #666;
    margin: 2px 0 0 0;
}

.transaction-date {
    font-size: 11px;
    color: #999;
    margin: 2px 0 0 0;
}

.transaction-amount {
    text-align: right;
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

.load-more-button {
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

/* Filter Modal */
.filter-modal {
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

.filter-modal.show {
    display: flex;
}

.filter-content {
    background: white;
    border-radius: 25px 25px 0 0;
    padding: 25px;
    width: 100%;
    max-height: 70vh;
    overflow-y: auto;
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.filter-title {
    font-size: 20px;
    font-weight: 600;
    margin: 0;
}

.close-filter {
    background: none;
    border: none;
    font-size: 24px;
    color: #666;
}

.filter-group {
    margin-bottom: 25px;
}

.filter-group-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
}

.filter-options {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.filter-option {
    background: #f8f9fa;
    border: 2px solid transparent;
    border-radius: 12px;
    padding: 15px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-option.selected {
    background: rgba(102, 126, 234, 0.1);
    border-color: #667eea;
}

.apply-filters {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 12px;
    padding: 15px;
    width: 100%;
    color: white;
    font-size: 16px;
    font-weight: 500;
}

@media (max-width: 768px) {
    .summary-stats {
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
}
</style>

<div class="transaction-history-container">
    <!-- Transaction Header -->
    <div class="transaction-header">
        <div class="header-nav">
            <a href="dashboard.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="page-title">Transactions</h1>
            <button class="filter-button" onclick="showFilterModal()">
                <i class="fas fa-filter"></i>
            </button>
        </div>

        <!-- Search and Filters -->
        <div class="search-container">
            <form method="GET" action="">
                <input type="text" class="search-input" placeholder="Search transactions..."
                       name="search" value="<?= htmlspecialchars($search_query) ?>">
                <input type="hidden" name="type" value="<?= $filter_type ?>">
                <input type="hidden" name="period" value="<?= $filter_period ?>">
            </form>

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
        <!-- Summary Stats -->
        <?php
        // Calculate summary stats for current filter
        $summary_sql = "SELECT
            COUNT(*) as total_count,
            SUM(CASE WHEN trans_type = 1 THEN amount ELSE 0 END) as total_income,
            SUM(CASE WHEN trans_type = 2 THEN amount ELSE 0 END) as total_expense
            FROM transactions WHERE user_id = :user_id";

        $summary_params = [':user_id' => $user_id];

        if($filter_type !== 'all') {
            $summary_sql .= " AND trans_type = :trans_type";
            $summary_params[':trans_type'] = $filter_type;
        }

        if($search_query) {
            $summary_sql .= " AND (sender_name LIKE :search OR description LIKE :search)";
            $summary_params[':search'] = '%' . $search_query . '%';
        }

        if($filter_period !== 'all') {
            $summary_sql .= " AND created_at >= DATE_SUB(NOW(), INTERVAL :period DAY)";
            $summary_params[':period'] = $filter_period;
        }

        $summary_stmt = $conn->prepare($summary_sql);
        $summary_stmt->execute($summary_params);
        $summary = $summary_stmt->fetch(PDO::FETCH_ASSOC);
        ?>

        <div class="summary-stats">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-list"></i>
                </div>
                <h4 class="stat-value"><?= $summary['total_count'] ?></h4>
                <p class="stat-label">Total</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon income">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <h4 class="stat-value"><?= $currency . number_format($summary['total_income'] ?? 0, 2) ?></h4>
                <p class="stat-label">Income</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon expense">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <h4 class="stat-value"><?= $currency . number_format($summary['total_expense'] ?? 0, 2) ?></h4>
                <p class="stat-label">Expenses</p>
            </div>
        </div>

        <!-- Transactions List -->
        <div class="transactions-list">
            <div class="section-header">
                <h3 class="section-title">Recent Transactions</h3>
                <button class="export-button" onclick="exportTransactions()">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>

            <?php if(count($transactions) > 0): ?>
                <?php foreach($transactions as $transaction):
                    $isCredit = $transaction['trans_type'] == 1;
                    $iconClass = $isCredit ? 'credit' : 'debit';
                    $amountClass = $isCredit ? 'credit' : 'debit';
                    $sign = $isCredit ? '+' : '-';
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
                        <p class="transaction-status">
                            <?= $transaction['trans_status'] == 1 ? 'Completed' : 'Pending' ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>

                <?php if(count($transactions) >= 50): ?>
                <button class="load-more-button" onclick="loadMoreTransactions()">
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

<!-- Filter Modal -->
<div class="filter-modal" id="filterModal">
    <div class="filter-content">
        <div class="filter-header">
            <h3 class="filter-title">Filter Transactions</h3>
            <button class="close-filter" onclick="hideFilterModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="GET" action="">
            <div class="filter-group">
                <h4 class="filter-group-title">Transaction Type</h4>
                <div class="filter-options">
                    <label class="filter-option <?= $filter_type === 'all' ? 'selected' : '' ?>">
                        <input type="radio" name="type" value="all" <?= $filter_type === 'all' ? 'checked' : '' ?> style="display: none;">
                        <div>All Types</div>
                    </label>
                    <label class="filter-option <?= $filter_type === '1' ? 'selected' : '' ?>">
                        <input type="radio" name="type" value="1" <?= $filter_type === '1' ? 'checked' : '' ?> style="display: none;">
                        <div>Income</div>
                    </label>
                    <label class="filter-option <?= $filter_type === '2' ? 'selected' : '' ?>">
                        <input type="radio" name="type" value="2" <?= $filter_type === '2' ? 'checked' : '' ?> style="display: none;">
                        <div>Expenses</div>
                    </label>
                </div>
            </div>

            <div class="filter-group">
                <h4 class="filter-group-title">Time Period</h4>
                <div class="filter-options">
                    <label class="filter-option <?= $filter_period === '7' ? 'selected' : '' ?>">
                        <input type="radio" name="period" value="7" <?= $filter_period === '7' ? 'checked' : '' ?> style="display: none;">
                        <div>Last 7 Days</div>
                    </label>
                    <label class="filter-option <?= $filter_period === '30' ? 'selected' : '' ?>">
                        <input type="radio" name="period" value="30" <?= $filter_period === '30' ? 'checked' : '' ?> style="display: none;">
                        <div>Last 30 Days</div>
                    </label>
                    <label class="filter-option <?= $filter_period === '90' ? 'selected' : '' ?>">
                        <input type="radio" name="period" value="90" <?= $filter_period === '90' ? 'checked' : '' ?> style="display: none;">
                        <div>Last 90 Days</div>
                    </label>
                    <label class="filter-option <?= $filter_period === 'all' ? 'selected' : '' ?>">
                        <input type="radio" name="period" value="all" <?= $filter_period === 'all' ? 'checked' : '' ?> style="display: none;">
                        <div>All Time</div>
                    </label>
                </div>
            </div>

            <input type="hidden" name="search" value="<?= htmlspecialchars($search_query) ?>">
            <button type="submit" class="apply-filters">Apply Filters</button>
        </form>
    </div>
</div>

<script>
function showFilterModal() {
    document.getElementById('filterModal').classList.add('show');
}

function hideFilterModal() {
    document.getElementById('filterModal').classList.remove('show');
}

function exportTransactions() {
    // Create CSV content
    let csvContent = "Date,Type,Description,Amount,Status\n";

    <?php foreach($transactions as $transaction): ?>
    csvContent += "<?= date('Y-m-d H:i', strtotime($transaction['created_at'])) ?>," +
                  "<?= $transaction['trans_type'] == 1 ? 'Credit' : 'Debit' ?>," +
                  "<?= addslashes($transaction['description']) ?>," +
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

function showTransactionDetails(transactionId) {
    // Show transaction details modal or navigate to details page
    console.log('Show details for transaction:', transactionId);
}

function loadMoreTransactions() {
    // Implement pagination
    console.log('Load more transactions');
}

// Handle filter option selection
document.addEventListener('DOMContentLoaded', function() {
    const filterOptions = document.querySelectorAll('.filter-option');
    filterOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                // Remove selected class from siblings
                const siblings = this.parentElement.querySelectorAll('.filter-option');
                siblings.forEach(sibling => sibling.classList.remove('selected'));

                // Add selected class to clicked option
                this.classList.add('selected');
                radio.checked = true;
            }
        });
    });

    // Close modal when clicking outside
    document.getElementById('filterModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideFilterModal();
        }
    });

    // Auto-submit search form on input
    const searchInput = document.querySelector('.search-input');
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
});
</script>

<?php include_once('layouts/footer.php');
