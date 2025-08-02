<?php
$pageName = "View Wire Transaction";
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
                    <th>Amount</th>
                    <th>Reference ID</th>
                    <th>Bank Name</th>
                    <th>Account Name</th>
                    <th>Account Number</th>
                    <th>Account Type</th>
                    <th>Transfer Type</th>
                    <th>Country</th>
                    <th>Swift Code</th>
                    <th>Routing Code</th>
                    <th>Date</th>
                    <th>Transfer Status</th>
                </tr>
                </thead>
                <tbody>


                <?php

                $sql2 ="SELECT * FROM wire_transfer WHERE acct_id =:acct_id ORDER BY wire_id DESC";
                $wire = $conn->prepare($sql2);
                $wire->execute([
                   'acct_id'=>$acct_id
                ]);



                $sn=1;

                while ($result = $wire->fetch(PDO::FETCH_ASSOC)){
                    $transStatus = wireStatus($result);
                    ?>
                    <tr>
                        <td><?= $sn++ ?></td>
                        <td><?=$currency. $result['amount'] ?></td>
                        <td><?= $result['refrence_id']?></td>
                        <td><?= $result['bank_name'] ?></td>
                        <td><?= $result['acct_name'] ?></td>
                        <td><?= $result['acct_number'] ?></td>
                        <td><?= $result['acct_type'] ?></td>
                        <td><?= $result['trans_type'] ?></td>
                        <td><?= $result['acct_country'] ?></td>
                        <td><?= $result['acct_swift'] ?></td>
                        <td><?= $result['acct_routing'] ?></td>
                        <td><?= $result['createdAt'] ?></td>
<!--                        <td>--><?php //= $result['created_at'] ?><!--</td>-->
                        <td>
                            <?php
                            if ($result['wire_status']==0){?>
                                <span class="badge outline-badge-secondary shadow-none col-md-12">In Progress</span>
                            <?php } elseif ($result['wire_status']==1){ ?>
                                <span class="badge outline-badge-primary shadow-none col-md-12">Completed</span>
                            <?php } elseif ($result['wire_status']==2){ ?>
                                <span class="badge outline-badge-danger shadow-none col-md-12">Hold</span>
                            <?php } elseif ($result['wire_status']==3){ ?>
                                <span class="badge outline-badge-danger shadow-none col-md-12">Cancelled</span>
                            <?php } ?>
                        </td>

                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>S/N</th>
                    <th>Amount</th>
                    <th>Reference ID</th>
                    <th>Bank Name</th>
                    <th>Account Name</th>
                    <th>Account Number</th>
                    <th>Account Type</th>
                    <th>Transfer Type</th>
                    <th>Country</th>
                    <th>Swift Code</th>
                    <th>Routing Code</th>
                    <th>Date</th>
                    <th>Transfer Status</th>
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
$pageName = "Wire Transaction";
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
    
    .wire-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
    }
    
    .status-in-progress {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }
    
    .status-completed {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    
    .status-hold {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }
    
    .status-cancelled {
        background-color: rgba(108, 117, 125, 0.1);
        color: #6c757d;
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
        <h1>Wire Transactions</h1>
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
                <i class="fas fa-university"></i>
                Wire Transfer History
            </h2>
            
            <!-- Filters -->
            <div class="filters-container">
                <div class="filter-group">
                    <label for="transfer-status">Transfer Status</label>
                    <select id="transfer-status">
                        <option value="all">All Status</option>
                        <option value="in-progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="hold">On Hold</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="transfer-type">Transfer Type</label>
                    <select id="transfer-type">
                        <option value="all">All Types</option>
                        <option value="domestic">Domestic</option>
                        <option value="international">International</option>
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
            </div>
            
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Amount</th>
                            <th>Reference ID</th>
                            <th>Bank Name</th>
                            <th>Account Name</th>
                            <th>Account Number</th>
                            <th>Account Type</th>
                            <th>Transfer Type</th>
                            <th>Country</th>
                            <th>Swift Code</th>
                            <th>Routing Code</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT * FROM wire_transfer WHERE acct_id = :acct_id ORDER BY wire_id DESC";
                        $wire = $conn->prepare($sql2);
                        $wire->execute(['acct_id' => $acct_id]);
                        
                        $sn = 1;
                        $hasTransactions = false;
                        
                        while ($result = $wire->fetch(PDO::FETCH_ASSOC)) {
                            $hasTransactions = true;
                        ?>
                            <tr>
                                <td><?= $sn++ ?></td>
                                <td><?= $currency . number_format($result['amount'], 2) ?></td>
                                <td><?= htmlspecialchars($result['refrence_id']) ?></td>
                                <td><?= htmlspecialchars($result['bank_name']) ?></td>
                                <td><?= htmlspecialchars($result['acct_name']) ?></td>
                                <td><?= htmlspecialchars($result['acct_number']) ?></td>
                                <td><?= htmlspecialchars($result['acct_type']) ?></td>
                                <td><?= htmlspecialchars($result['trans_type']) ?></td>
                                <td><?= htmlspecialchars($result['acct_country']) ?></td>
                                <td><?= htmlspecialchars($result['acct_swift']) ?></td>
                                <td><?= htmlspecialchars($result['acct_routing']) ?></td>
                                <td><?= date('M d, Y', strtotime($result['createdAt'])) ?></td>
                                <td>
                                    <?php
                                    if ($result['wire_status'] == 0) {
                                        echo '<span class="wire-status status-in-progress">In Progress</span>';
                                    } elseif ($result['wire_status'] == 1) {
                                        echo '<span class="wire-status status-completed">Completed</span>';
                                    } elseif ($result['wire_status'] == 2) {
                                        echo '<span class="wire-status status-hold">Hold</span>';
                                    } elseif ($result['wire_status'] == 3) {
                                        echo '<span class="wire-status status-cancelled">Cancelled</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                        
                        <?php if (!$hasTransactions): ?>
                            <tr>
                                <td colspan="13">
                                    <div class="no-transactions">
                                        <i class="fas fa-university"></i>
                                        <h3>No Wire Transfers Found</h3>
                                        <p>You haven't made any wire transfers yet.</p>
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
    const statusFilter = document.getElementById('transfer-status');
    const typeFilter = document.getElementById('transfer-type');
    const dateFromFilter = document.getElementById('date-from');
    const dateToFilter = document.getElementById('date-to');
    
    function filterTransactions() {
        const rows = document.querySelectorAll('.table tbody tr');
        const statusValue = statusFilter.value;
        const typeValue = typeFilter.value;
        
        rows.forEach(row => {
            if (row.querySelector('.no-transactions')) return;
            
            let showRow = true;
            
            // Status filter
            if (statusValue !== 'all') {
                const statusCell = row.cells[12];
                const statusText = statusCell.textContent.toLowerCase();
                if (!statusText.includes(statusValue.replace('-', ' '))) {
                    showRow = false;
                }
            }
            
            row.style.display = showRow ? '' : 'none';
        });
    }
    
    statusFilter.addEventListener('change', filterTransactions);
    typeFilter.addEventListener('change', filterTransactions);
    dateFromFilter.addEventListener('change', filterTransactions);
    dateToFilter.addEventListener('change', filterTransactions);
});
</script>

<?php
include_once("layouts/footer.php");
?>
