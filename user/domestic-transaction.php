
<?php
$pageName = "Domestic Transaction";
include_once("layouts/header.php");
$acct_id = userDetails('id');

if (!$_SESSION['acct_no']) {
    header("location:../login.php");
    die;
}

if($acct_stat != 'active'){
    header("Location:./error.php");
    exit();
}
?>

<style>
    .main-content {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 20px;
    }
    
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        background: #fff;
        padding: 20px 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
    }
    
    .header h1 {
        color: #104042;
        margin: 0;
        font-size: 28px;
        font-weight: 600;
    }
    
    .search-notification {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .search-bar {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .search-bar i {
        position: absolute;
        left: 15px;
        color: rgba(16, 64, 66, 0.5);
    }
    
    .search-bar input {
        padding: 10px 15px 10px 45px;
        border: 1px solid rgba(16, 64, 66, 0.2);
        border-radius: 25px;
        background-color: rgba(16, 64, 66, 0.02);
        color: #104042;
        font-size: 14px;
        width: 250px;
    }
    
    .user-profile {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .notification {
        position: relative;
        padding: 8px;
        border-radius: 50%;
        background-color: rgba(16, 64, 66, 0.1);
        cursor: pointer;
    }
    
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
    }
    
    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .user-name {
        font-weight: 500;
        color: #104042;
    }
    
    .dashboard-content {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
    }
    
    .transactions-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(16, 64, 66, 0.1);
    }
    
    .transactions-title {
        font-size: 24px;
        font-weight: 600;
        color: #104042;
    }
    
    .filter-controls {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    
    .filter-controls select,
    .filter-controls input {
        padding: 8px 12px;
        border: 1px solid rgba(16, 64, 66, 0.2);
        border-radius: 6px;
        background-color: rgba(16, 64, 66, 0.02);
        color: #104042;
        font-size: 14px;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }
    
    .table th {
        background-color: rgba(16, 64, 66, 0.05);
        color: #104042;
        font-weight: 600;
        padding: 15px 12px;
        text-align: left;
        border-bottom: 2px solid rgba(16, 64, 66, 0.1);
        font-size: 14px;
    }
    
    .table td {
        padding: 15px 12px;
        border-bottom: 1px solid rgba(16, 64, 66, 0.1);
        color: #104042;
        font-size: 14px;
    }
    
    .table tbody tr:hover {
        background-color: rgba(16, 64, 66, 0.02);
    }
    
    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .status-completed {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    
    .status-pending {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }
    
    .status-failed {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }
    
    .amount-transferred {
        color: #dc3545;
        font-weight: 600;
    }
    
    .no-transactions {
        text-align: center;
        padding: 60px 20px;
        color: rgba(16, 64, 66, 0.7);
    }
    
    .no-transactions i {
        font-size: 48px;
        margin-bottom: 20px;
        color: rgba(16, 64, 66, 0.3);
    }
    
    .print-button {
        background-color: #104042;
        color: #fff;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .print-button:hover {
        background-color: #165e61;
        color: #fff;
    }
</style>

<div class="main-content">
    <header class="header">
        <h1>Domestic Transfers</h1>
        <div class="search-notification">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search transfers">
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

    <div class="dashboard-content">
        <div class="transactions-header">
            <div class="transactions-title">Your Domestic Transfer History</div>
            <div class="filter-controls">
                <select id="transfer-status">
                    <option value="all">All Status</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                    <option value="failed">Failed</option>
                </select>
                <input type="date" id="date-from" placeholder="From Date">
                <input type="date" id="date-to" placeholder="To Date">
            </div>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                        <th>Bank Name</th>
                        <th>Account Name</th>
                        <th>Account Number</th>
                        <th>Transfer Type</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql2 = "SELECT * FROM domestic_transfer WHERE acct_id = :acct_id ORDER BY dom_id DESC";
                    $transfer = $conn->prepare($sql2);
                    $transfer->execute(['acct_id' => $acct_id]);

                    $sn = 1;
                    if($transfer->rowCount() > 0) {
                        while ($result = $transfer->fetch(PDO::FETCH_ASSOC)) {
                            $transStatus = domestic($result);
                            ?>
                            <tr>
                                <td><?= $sn++ ?></td>
                                <td><strong><?= $result['refrence_id'] ?></strong></td>
                                <td class="amount-transferred">-<?= $currency . number_format($result['amount'], 2) ?></td>
                                <td><?= htmlspecialchars($result['bank_name']) ?></td>
                                <td><?= htmlspecialchars($result['acct_name']) ?></td>
                                <td><?= $result['acct_number'] ?></td>
                                <td><?= ucfirst($result['trans_type']) ?></td>
                                <td><?= $transStatus ?></td>
                                <td><?= date('M d, Y', strtotime($result['created_at'] ?? 'now')) ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="9" class="no-transactions">
                                <i class="fas fa-exchange-alt"></i>
                                <div style="font-size: 18px; font-weight: 600; margin-bottom: 10px;">No Domestic Transfers</div>
                                <div>You haven't made any domestic transfers yet. When you do, they'll appear here.</div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div style="text-align: right; margin-top: 20px;">
            <a href="javascript:window.print()" class="print-button">
                <i class="fa fa-print"></i>
                Print Statement
            </a>
        </div>
    </div>
</div>

<script>
// Simple filtering functionality
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('transfer-status');
    const dateFromFilter = document.getElementById('date-from');
    const dateToFilter = document.getElementById('date-to');
    
    function filterTransactions() {
        const rows = document.querySelectorAll('.table tbody tr');
        const statusValue = statusFilter.value;
        
        rows.forEach(row => {
            if (row.querySelector('.no-transactions')) return;
            
            let showRow = true;
            
            // Status filter
            if (statusValue !== 'all') {
                const statusCell = row.cells[7];
                const statusText = statusCell.textContent.toLowerCase();
                if (!statusText.includes(statusValue)) {
                    showRow = false;
                }
            }
            
            row.style.display = showRow ? '' : 'none';
        });
    }
    
    statusFilter.addEventListener('change', filterTransactions);
    dateFromFilter.addEventListener('change', filterTransactions);
    dateToFilter.addEventListener('change', filterTransactions);
});
</script>

<?php
include_once("layouts/footer.php");
?>
