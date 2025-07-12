<?php
$pageName = "Balance Overview";
include_once("layouts/header.php");

if(!$_SESSION['acct_no']) {
    header("location:../login.php");
    die;
}

// Get balance history data for charts
$balance_history_sql = "SELECT
    DATE(created_at) as date,
    SUM(CASE WHEN trans_type = 1 THEN amount ELSE -amount END) as daily_change,
    COUNT(*) as transaction_count
    FROM transactions
    WHERE user_id = :user_id
    AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date ASC";

$stmt = $conn->prepare($balance_history_sql);
$stmt->execute([':user_id' => $user_id]);
$balance_history = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate running balance
$running_balance = $acct_balance;
foreach(array_reverse($balance_history) as &$day) {
    $day['balance'] = $running_balance;
    $running_balance -= $day['daily_change'];
}
$balance_history = array_reverse($balance_history);

// Get spending categories
$category_sql = "SELECT
    CASE
        WHEN description LIKE '%grocery%' OR description LIKE '%food%' THEN 'Food & Dining'
        WHEN description LIKE '%gas%' OR description LIKE '%fuel%' THEN 'Transportation'
        WHEN description LIKE '%bill%' OR description LIKE '%electric%' OR description LIKE '%water%' THEN 'Bills & Utilities'
        WHEN description LIKE '%shopping%' OR description LIKE '%store%' THEN 'Shopping'
        WHEN description LIKE '%entertainment%' OR description LIKE '%movie%' THEN 'Entertainment'
        ELSE 'Other'
    END as category,
    SUM(amount) as total_amount,
    COUNT(*) as transaction_count
    FROM transactions
    WHERE user_id = :user_id
    AND trans_type = 2
    AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY category
    ORDER BY total_amount DESC";

$stmt = $conn->prepare($category_sql);
$stmt->execute([':user_id' => $user_id]);
$spending_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get monthly comparison
$monthly_sql = "SELECT
    MONTH(created_at) as month,
    YEAR(created_at) as year,
    SUM(CASE WHEN trans_type = 1 THEN amount ELSE 0 END) as income,
    SUM(CASE WHEN trans_type = 2 THEN amount ELSE 0 END) as expenses
    FROM transactions
    WHERE user_id = :user_id
    AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY YEAR(created_at), MONTH(created_at)
    ORDER BY year DESC, month DESC";

$stmt = $conn->prepare($monthly_sql);
$stmt->execute([':user_id' => $user_id]);
$monthly_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
/* Balance Overview Styles */
.balance-overview-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 0;
}

.balance-header {
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

.period-selector {
    background: rgba(255,255,255,0.2);
    border: none;
    border-radius: 20px;
    padding: 8px 16px;
    color: white;
    font-size: 14px;
}

.balance-summary-card {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 25px;
    margin: 0 20px 20px;
    border: 1px solid rgba(255,255,255,0.2);
}

.current-balance {
    text-align: center;
    margin-bottom: 20px;
}

.balance-label {
    color: rgba(255,255,255,0.8);
    font-size: 14px;
    margin-bottom: 5px;
}

.balance-amount {
    font-size: 42px;
    font-weight: 700;
    color: white;
    margin: 10px 0;
}

.balance-change {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    font-size: 14px;
}

.balance-change.positive {
    color: #43e97b;
}

.balance-change.negative {
    color: #fa709a;
}

.balance-metrics {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-top: 20px;
}

.metric-item {
    text-align: center;
    padding: 15px;
    background: rgba(255,255,255,0.1);
    border-radius: 12px;
}

.metric-value {
    font-size: 18px;
    font-weight: 600;
    color: white;
    margin: 0;
}

.metric-label {
    font-size: 12px;
    color: rgba(255,255,255,0.7);
    margin: 5px 0 0 0;
}

.content-section {
    background: #f8f9fa;
    border-radius: 25px 25px 0 0;
    padding: 25px 20px;
    min-height: 60vh;
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

.chart-tabs {
    display: flex;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 2px;
}

.chart-tab {
    background: none;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 12px;
    color: #666;
    transition: all 0.3s ease;
}

.chart-tab.active {
    background: white;
    color: #333;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.chart-canvas {
    height: 250px;
    position: relative;
}

.insights-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.insight-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.insight-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    color: white;
}

.insight-icon.spending {
    background: linear-gradient(135deg, #fa709a, #fee140);
}

.insight-icon.saving {
    background: linear-gradient(135deg, #43e97b, #38f9d7);
}

.insight-icon.trend {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
}

.insight-icon.goal {
    background: linear-gradient(135deg, #a8edea, #fed6e3);
}

.insight-value {
    font-size: 20px;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.insight-label {
    font-size: 12px;
    color: #666;
    margin: 5px 0 0 0;
}

.insight-description {
    font-size: 14px;
    color: #666;
    margin: 10px 0 0 0;
    line-height: 1.4;
}

.spending-breakdown {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.category-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.category-item:last-child {
    border-bottom: none;
}

.category-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 15px;
}

.category-details {
    flex: 1;
}

.category-name {
    font-size: 14px;
    font-weight: 500;
    color: #333;
    margin: 0;
}

.category-transactions {
    font-size: 12px;
    color: #666;
    margin: 2px 0 0 0;
}

.category-amount {
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.category-percentage {
    font-size: 12px;
    color: #666;
    margin: 2px 0 0 0;
}

.recommendations {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.recommendation-item {
    display: flex;
    align-items: flex-start;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.recommendation-item:last-child {
    border-bottom: none;
}

.recommendation-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 15px;
    flex-shrink: 0;
}

.recommendation-content {
    flex: 1;
}

.recommendation-title {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin: 0 0 5px 0;
}

.recommendation-description {
    font-size: 12px;
    color: #666;
    line-height: 1.4;
    margin: 0;
}

@media (max-width: 768px) {
    .balance-metrics {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .insights-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .balance-amount {
        font-size: 32px;
    }
}
</style>

<div class="balance-overview-container">
    <!-- Balance Header -->
    <div class="balance-header">
        <div class="header-nav">
            <a href="dashboard.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="page-title">Balance Overview</h1>
            <select class="period-selector" onchange="updatePeriod(this.value)">
                <option value="30">30 Days</option>
                <option value="90">90 Days</option>
                <option value="180">6 Months</option>
                <option value="365">1 Year</option>
            </select>
        </div>

        <!-- Balance Summary Card -->
        <div class="balance-summary-card">
            <div class="current-balance">
                <p class="balance-label">Current Balance</p>
                <h1 class="balance-amount"><?= $currency . number_format($acct_balance, 2) ?></h1>
                <?php
                $balance_change = 0;
                if(count($balance_history) > 1) {
                    $balance_change = $balance_history[count($balance_history)-1]['daily_change'];
                }
                $change_class = $balance_change >= 0 ? 'positive' : 'negative';
                $change_icon = $balance_change >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                ?>
                <div class="balance-change <?= $change_class ?>">
                    <i class="fas <?= $change_icon ?>"></i>
                    <?= $currency . number_format(abs($balance_change), 2) ?> today
                </div>
            </div>

            <div class="balance-metrics">
                <div class="metric-item">
                    <h4 class="metric-value"><?= $currency . number_format($avail_balance, 2) ?></h4>
                    <p class="metric-label">Available</p>
                </div>
                <div class="metric-item">
                    <h4 class="metric-value"><?= $currency . number_format($row['acct_limit'] - $limitRemain, 2) ?></h4>
                    <p class="metric-label">Used Credit</p>
                </div>
                <div class="metric-item">
                    <h4 class="metric-value"><?= $currency . number_format($limitRemain, 2) ?></h4>
                    <p class="metric-label">Available Credit</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="content-section">
        <!-- Balance Trend Chart -->
        <div class="chart-container">
            <div class="chart-header">
                <h3 class="chart-title">Balance Trend</h3>
                <div class="chart-tabs">
                    <button class="chart-tab active" onclick="switchChart('balance')">Balance</button>
                    <button class="chart-tab" onclick="switchChart('income')">Income</button>
                    <button class="chart-tab" onclick="switchChart('expenses')">Expenses</button>
                </div>
            </div>
            <div class="chart-canvas" id="balanceChart">
                <canvas id="balanceChartCanvas"></canvas>
            </div>
        </div>

        <!-- Financial Insights -->
        <div class="insights-grid">
            <?php
            $total_income = array_sum(array_column($monthly_data, 'income'));
            $total_expenses = array_sum(array_column($monthly_data, 'expenses'));
            $avg_monthly_income = count($monthly_data) > 0 ? $total_income / count($monthly_data) : 0;
            $avg_monthly_expenses = count($monthly_data) > 0 ? $total_expenses / count($monthly_data) : 0;
            $savings_rate = $total_income > 0 ? (($total_income - $total_expenses) / $total_income) * 100 : 0;
            ?>

            <div class="insight-card">
                <div class="insight-icon spending">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <h4 class="insight-value"><?= $currency . number_format($avg_monthly_expenses, 0) ?></h4>
                <p class="insight-label">Avg Monthly Spending</p>
                <p class="insight-description">Your average monthly expenses over the last 6 months</p>
            </div>

            <div class="insight-card">
                <div class="insight-icon saving">
                    <i class="fas fa-piggy-bank"></i>
                </div>
                <h4 class="insight-value"><?= number_format($savings_rate, 1) ?>%</h4>
                <p class="insight-label">Savings Rate</p>
                <p class="insight-description">Percentage of income you're saving</p>
            </div>

            <div class="insight-card">
                <div class="insight-icon trend">
                    <i class="fas fa-trending-up"></i>
                </div>
                <h4 class="insight-value"><?= count($balance_history) ?></h4>
                <p class="insight-label">Active Days</p>
                <p class="insight-description">Days with transactions this month</p>
            </div>

            <div class="insight-card">
                <div class="insight-icon goal">
                    <i class="fas fa-target"></i>
                </div>
                <h4 class="insight-value"><?= $currency . number_format($avg_monthly_income, 0) ?></h4>
                <p class="insight-label">Avg Monthly Income</p>
                <p class="insight-description">Your average monthly income</p>
            </div>
        </div>

        <!-- Spending Breakdown -->
        <div class="spending-breakdown">
            <div class="chart-header">
                <h3 class="chart-title">Spending Categories</h3>
            </div>

            <?php
            $total_spending = array_sum(array_column($spending_categories, 'total_amount'));
            $colors = ['#667eea', '#43e97b', '#fa709a', '#4facfe', '#a8edea', '#fee140'];

            if(count($spending_categories) > 0):
                foreach($spending_categories as $index => $category):
                    $percentage = $total_spending > 0 ? ($category['total_amount'] / $total_spending) * 100 : 0;
                    $color = $colors[$index % count($colors)];
            ?>
            <div class="category-item">
                <div class="category-color" style="background-color: <?= $color ?>"></div>
                <div class="category-details">
                    <h5 class="category-name"><?= htmlspecialchars($category['category']) ?></h5>
                    <p class="category-transactions"><?= $category['transaction_count'] ?> transactions</p>
                </div>
                <div class="category-amount">
                    <?= $currency . number_format($category['total_amount'], 2) ?>
                    <p class="category-percentage"><?= number_format($percentage, 1) ?>%</p>
                </div>
            </div>
            <?php
                endforeach;
            else:
            ?>
            <div class="category-item">
                <div class="category-details">
                    <h5 class="category-name">No spending data available</h5>
                    <p class="category-transactions">Start making transactions to see insights</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Financial Recommendations -->
        <div class="recommendations">
            <div class="chart-header">
                <h3 class="chart-title">Financial Insights</h3>
            </div>

            <?php if($savings_rate < 20): ?>
            <div class="recommendation-item">
                <div class="recommendation-icon">
                    <i class="fas fa-exclamation"></i>
                </div>
                <div class="recommendation-content">
                    <h5 class="recommendation-title">Improve Your Savings Rate</h5>
                    <p class="recommendation-description">Your current savings rate is <?= number_format($savings_rate, 1) ?>%. Consider setting aside 20% of your income for better financial health.</p>
                </div>
            </div>
            <?php endif; ?>

            <?php if($avg_monthly_expenses > $avg_monthly_income * 0.8): ?>
            <div class="recommendation-item">
                <div class="recommendation-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="recommendation-content">
                    <h5 class="recommendation-title">Monitor Your Spending</h5>
                    <p class="recommendation-description">Your expenses are high relative to your income. Review your spending categories to identify areas for reduction.</p>
                </div>
            </div>
            <?php endif; ?>

            <div class="recommendation-item">
                <div class="recommendation-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="recommendation-content">
                    <h5 class="recommendation-title">Set Up Automatic Savings</h5>
                    <p class="recommendation-description">Consider setting up automatic transfers to your savings account to build wealth consistently.</p>
                </div>
            </div>

            <?php if($limitRemain < $row['acct_limit'] * 0.3): ?>
            <div class="recommendation-item">
                <div class="recommendation-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="recommendation-content">
                    <h5 class="recommendation-title">Credit Utilization Alert</h5>
                    <p class="recommendation-description">You're using <?= number_format((($row['acct_limit'] - $limitRemain) / $row['acct_limit']) * 100, 1) ?>% of your credit limit. Consider paying down balances to improve your credit score.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart data from PHP
const balanceData = <?= json_encode($balance_history) ?>;
const monthlyData = <?= json_encode($monthly_data) ?>;
const spendingData = <?= json_encode($spending_categories) ?>;

let currentChart = null;

function initializeCharts() {
    const ctx = document.getElementById('balanceChartCanvas').getContext('2d');

    // Default to balance chart
    showBalanceChart(ctx);
}

function showBalanceChart(ctx) {
    if (currentChart) {
        currentChart.destroy();
    }

    const labels = balanceData.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    });

    const data = balanceData.map(item => parseFloat(item.balance));

    currentChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Balance',
                data: data,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '<?= $currency ?>' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            elements: {
                point: {
                    hoverRadius: 8
                }
            }
        }
    });
}

function showIncomeChart(ctx) {
    if (currentChart) {
        currentChart.destroy();
    }

    const labels = monthlyData.map(item => {
        const date = new Date(item.year, item.month - 1);
        return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
    });

    const data = monthlyData.map(item => parseFloat(item.income));

    currentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Income',
                data: data,
                backgroundColor: 'rgba(67, 233, 123, 0.8)',
                borderColor: '#43e97b',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '<?= $currency ?>' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function showExpensesChart(ctx) {
    if (currentChart) {
        currentChart.destroy();
    }

    const labels = monthlyData.map(item => {
        const date = new Date(item.year, item.month - 1);
        return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
    });

    const data = monthlyData.map(item => parseFloat(item.expenses));

    currentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Expenses',
                data: data,
                backgroundColor: 'rgba(250, 112, 154, 0.8)',
                borderColor: '#fa709a',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '<?= $currency ?>' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function switchChart(type) {
    const ctx = document.getElementById('balanceChartCanvas').getContext('2d');

    // Update active tab
    document.querySelectorAll('.chart-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    event.target.classList.add('active');

    // Show appropriate chart
    switch(type) {
        case 'balance':
            showBalanceChart(ctx);
            break;
        case 'income':
            showIncomeChart(ctx);
            break;
        case 'expenses':
            showExpensesChart(ctx);
            break;
    }
}

function updatePeriod(days) {
    // Reload page with new period
    const url = new URL(window.location);
    url.searchParams.set('period', days);
    window.location.href = url.toString();
}

// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();

    // Add animation to insight cards
    const insightCards = document.querySelectorAll('.insight-card');
    insightCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';

            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }, index * 100);
    });
});
</script>

<?php include_once('layouts/footer.php');
