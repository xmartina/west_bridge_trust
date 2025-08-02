<?php
$pageName = "Credit Debit Transaction";
//session_start();
// include_once("layouts/tranheader.php");
include_once("layouts/header.php");

//require_once("../include/config.php");

//require_once("../include/userFunction.php");
//require_once('../include/userClass.php');
//$conn = dbConnect();
$acct_id = userDetails('id');
// $crypto_name = cryptoName('crypto_name');



if (!$_SESSION['acct_no']) {
    header("location:../login.php");
    die;
}

?>
    <div id="content" class="main-content">
    <div class="layout-px-spacing">
    <div class="row layout-top-spacing" id="cancel-row">

    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="widget-content widget-content-area br-6">
            <table id="default-ordering" class="table table-hover" style="width:100%">

                <thead>
                <tr>
                    <th>S/N</th>
                    <th>AMOUNT</th>
                    <th>TYPE</th>
                    <th>SENDER / RECEIVER</th>
                    <th>DESCRIPTION</th>
                    <th>CREATED AT</th>
                    <th>TIME CREATED</th>
                    <th>STATUS</th>
                </tr>
                </thead>
                <tbody>


                <?php

                $sql="SELECT * FROM transactions LEFT JOIN users ON transactions.user_id =users.id WHERE transactions.user_id =:acct_id order by transactions.trans_id DESC";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    'acct_id'=>$acct_id
                ]);



                $sn=1;

                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $transStatus = transStatus($result);

                    if($result['trans_type'] === '1'){
                        $trans_type = "<span class='text-success'>Credit</span>";
                    }else if($result['trans_type']=== '2'){
                        $trans_type = "<span class='text-danger'>Debit</span>
";
                    }
                    ?>
                    <tr>
                        <td><?= $sn++ ?></td>
                        <td><?=$currency. $result['amount'] ?></td>
                        <td><?= $trans_type ?></td>
                        <td><?= $result['sender_name'] ?></td>
                        <td><?=$result['description'] ?></td>
                        <td><?= $result['created_at'] ?></td>
                        <td><?= $result['time_created'] ?></td>
                        <!--<td><?= $transStatus ?></td>-->
                        <td>Completed</td>

                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>S/N</th>
                    <th>AMOUNT</th>
                    <th>TYPE</th>
                    <th>SENDER / RECEIVER</th>
                    <th>DESCRIPTION</th>
                    <th>CREATED AT</th>
                    <th>TIME CREATED</th>
                    <th>STATUS</th>
                </tr>
                </tfoot>
            </table>
            
            <div class="d-print-none">
                                    <div class="float-end">
                                        <a href="javascript:window.print()"
                                            class="btn btn-success waves-effect waves-light me-1"><i
                                                class="fa fa-print"></i> Print Statement</a>
                                    </div>
                                </div>
        </div>
    </div>


<?php
include_once("layouts/footer.php");
?>
<?php
$pageName = "Credit Debit Transaction";
include_once("layouts/header.php");

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
    .transactions-section {
        background-color: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
        margin-bottom: 30px;
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 24px;
        font-weight: 700;
        color: #104042;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(16, 64, 66, 0.1);
    }
    
    .filters-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
        padding: 20px;
        background: rgba(16, 64, 66, 0.02);
        border-radius: 8px;
        border: 1px solid rgba(16, 64, 66, 0.1);
    }
    
    .filter-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #104042;
        font-size: 14px;
    }
    
    .filter-group select,
    .filter-group input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid rgba(16, 64, 66, 0.2);
        border-radius: 6px;
        background-color: #fff;
        color: #104042;
        font-size: 14px;
    }
    
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid rgba(16, 64, 66, 0.1);
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
        background-color: #fff;
    }
    
    .table th {
        background-color: #104042;
        color: #fff;
        font-weight: 600;
        padding: 15px 12px;
        text-align: left;
        font-size: 14px;
        border: none;
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
    
    .transaction-type-credit {
        color: #28a745;
        font-weight: 600;
    }
    
    .transaction-type-debit {
        color: #dc3545;
        font-weight: 600;
    }
    
    .transaction-amount-positive {
        color: #28a745;
        font-weight: 600;
    }
    
    .transaction-amount-negative {
        color: #dc3545;
        font-weight: 600;
    }
    
    .transaction-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
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
    
    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid rgba(16, 64, 66, 0.1);
    }
    
    .print-button {
        background-color: #104042;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .print-button:hover {
        background-color: #165e61;
        color: #fff;
        text-decoration: none;
    }
    
    .no-transactions {
        text-align: center;
        padding: 60px 20px;
        color: rgba(16, 64, 66, 0.6);
    }
    
    .no-transactions i {
        font-size: 48px;
        margin-bottom: 20px;
        color: rgba(16, 64, 66, 0.3);
    }
    
    @media (max-width: 768px) {
        .filters-container {
            grid-template-columns: 1fr;
        }
        
        .table th,
        .table td {
            padding: 10px 8px;
            font-size: 12px;
        }
        
        .pagination {
            flex-direction: column;
            gap: 15px;
        }
    }
</style>

<div class="main-content">
    <header class="header">
        <h1>Credit / Debit Transactions</h1>
        <div class="search-notification">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search transactions">
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
        <div class="transactions-section">
            <h2 class="section-title">
                <i class="fas fa-exchange-alt"></i>
                Credit / Debit Transaction History
            </h2>
            
            <!-- Filters -->
            <div class="filters-container">
                <div class="filter-group">
                    <label for="transaction-type">Transaction Type</label>
                    <select id="transaction-type">
                        <option value="all">All Transactions</option>
                        <option value="credit">Credit Only</option>
                        <option value="debit">Debit Only</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="date-from">From Date</label>
                    <input type="date" id="date-from">
                </div>
                <div class="filter-group">
                    <label for="date-to">To Date</label>
                    <input type="date" id="date-to">
                </div>
                <div class="filter-group">
                    <label for="amount-filter">Amount Range</label>
                    <select id="amount-filter">
                        <option value="all">All Amounts</option>
                        <option value="0-100">$0 - $100</option>
                        <option value="100-500">$100 - $500</option>
                        <option value="500-1000">$500 - $1,000</option>
                        <option value="1000+">$1,000+</option>
                    </select>
                </div>
            </div>
            
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Sender / Receiver</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM transactions LEFT JOIN users ON transactions.user_id = users.id WHERE transactions.user_id = :acct_id ORDER BY transactions.trans_id DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(['acct_id' => $acct_id]);
                        
                        $sn = 1;
                        $hasTransactions = false;
                        
                        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $hasTransactions = true;
                            $transStatus = transStatus($result);
                            
                            if($result['trans_type'] === '1'){
                                $trans_type = '<span class="transaction-type-credit">Credit</span>';
                                $amount_class = 'transaction-amount-positive';
                                $amount_sign = '+';
                            } else {
                                $trans_type = '<span class="transaction-type-debit">Debit</span>';
                                $amount_class = 'transaction-amount-negative';
                                $amount_sign = '-';
                            }
                        ?>
                            <tr>
                                <td><?= $sn++ ?></td>
                                <td class="<?= $amount_class ?>"><?= $amount_sign . $currency . number_format($result['amount'], 2) ?></td>
                                <td><?= $trans_type ?></td>
                                <td><?= htmlspecialchars($result['sender_name']) ?></td>
                                <td><?= htmlspecialchars($result['description']) ?></td>
                                <td><?= date('M d, Y', strtotime($result['created_at'])) ?></td>
                                <td><?= $result['time_created'] ?></td>
                                <td><span class="transaction-status status-completed">Completed</span></td>
                            </tr>
                        <?php } ?>
                        
                        <?php if (!$hasTransactions): ?>
                            <tr>
                                <td colspan="8">
                                    <div class="no-transactions">
                                        <i class="fas fa-exchange-alt"></i>
                                        <h3>No Transactions Found</h3>
                                        <p>You haven't made any credit or debit transactions yet.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($hasTransactions): ?>
                <div class="pagination">
                    <div class="pagination-info">
                        Showing <?= min($sn-1, 10) ?> of <?= $sn-1 ?> transactions
                    </div>
                    <div>
                        <a href="javascript:window.print()" class="print-button">
                            <i class="fa fa-print"></i> Print Statement
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Simple filtering functionality
document.addEventListener('DOMContentLoaded', function() {
    const typeFilter = document.getElementById('transaction-type');
    const amountFilter = document.getElementById('amount-filter');
    const dateFromFilter = document.getElementById('date-from');
    const dateToFilter = document.getElementById('date-to');
    
    function filterTransactions() {
        const rows = document.querySelectorAll('.table tbody tr');
        const typeValue = typeFilter.value;
        const amountValue = amountFilter.value;
        const dateFromValue = dateFromFilter.value;
        const dateToValue = dateToFilter.value;
        
        rows.forEach(row => {
            if (row.querySelector('.no-transactions')) return;
            
            let showRow = true;
            
            // Type filter
            if (typeValue !== 'all') {
                const typeCell = row.cells[2];
                const isCredit = typeCell.textContent.toLowerCase().includes('credit');
                if ((typeValue === 'credit' && !isCredit) || (typeValue === 'debit' && isCredit)) {
                    showRow = false;
                }
            }
            
            row.style.display = showRow ? '' : 'none';
        });
    }
    
    typeFilter.addEventListener('change', filterTransactions);
    amountFilter.addEventListener('change', filterTransactions);
    dateFromFilter.addEventListener('change', filterTransactions);
    dateToFilter.addEventListener('change', filterTransactions);
});
</script>

<?php
include_once("layouts/footer.php");
?>
