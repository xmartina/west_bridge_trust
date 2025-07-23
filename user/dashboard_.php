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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #ffffff;
            color: #104042;
            line-height: 1.6;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            min-height: 100vh;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f8f9fa;
        }

        .dashboard-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #104042;
            margin: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: linear-gradient(135deg, #104042, #1a5a5c);
            padding: 1rem 1.5rem;
            border-radius: 16px;
            color: white;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            background: #afff1a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #104042;
            font-size: 1.2rem;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(16, 64, 66, 0.1);
            border: 1px solid rgba(16, 64, 66, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(16, 64, 66, 0.15);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #104042, #afff1a, #FFD200);
        }

        .balance-card {
            background: linear-gradient(135deg, #104042, #1a5a5c);
            color: white;
            grid-column: span 2;
        }

        .balance-card::before {
            background: linear-gradient(90deg, #afff1a, #FFD200);
        }

        .card-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #104042;
            margin: 0;
        }

        .balance-card .card-title {
            color: white;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-left: auto;
        }

        .icon-primary {
            background: rgba(16, 64, 66, 0.1);
            color: #104042;
        }

        .icon-success {
            background: rgba(175, 255, 26, 0.2);
            color: #104042;
        }

        .icon-warning {
            background: rgba(255, 210, 0, 0.2);
            color: #104042;
        }

        .balance-amount {
            font-size: 3rem;
            font-weight: 700;
            margin: 1rem 0;
            color: #afff1a;
        }

        .balance-label {
            font-size: 1rem;
            opacity: 0.8;
            margin-bottom: 0.5rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(16, 64, 66, 0.1);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            font-weight: 500;
            color: #104042;
        }

        .summary-value {
            font-weight: 700;
            font-size: 1.1rem;
            color: #104042;
        }

        .balance-card .summary-item {
            border-bottom-color: rgba(255, 255, 255, 0.2);
        }

        .balance-card .summary-label,
        .balance-card .summary-value {
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: #104042;
            color: white;
        }

        .btn-primary:hover {
            background: #1a5a5c;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #afff1a;
            color: #104042;
        }

        .btn-secondary:hover {
            background: #9ee600;
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #104042;
            color: #104042;
        }

        .btn-outline:hover {
            background: #104042;
            color: white;
        }

        .clock-widget {
            text-align: center;
            padding: 1.5rem;
            background: rgba(175, 255, 26, 0.1);
            border-radius: 16px;
            margin-top: 1rem;
        }

        .transactions-section {
            margin-top: 3rem;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #104042;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section-title::after {
            content: '';
            flex: 1;
            height: 2px;
            background: linear-gradient(90deg, #104042, transparent);
        }

        .table-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(16, 64, 66, 0.1);
            margin-bottom: 2rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #104042;
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(16, 64, 66, 0.1);
            font-size: 0.9rem;
        }

        .table tr:hover {
            background: rgba(175, 255, 26, 0.05);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-progress {
            background: rgba(255, 210, 0, 0.2);
            color: #104042;
        }

        .status-completed {
            background: rgba(175, 255, 26, 0.2);
            color: #104042;
        }

        .status-hold {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        .status-cancelled {
            background: rgba(108, 117, 125, 0.2);
            color: #6c757d;
        }

        .text-success {
            color: #28a745 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .print-section {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1rem;
            padding: 1rem;
            background: rgba(16, 64, 66, 0.02);
            border-radius: 12px;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }

            .dashboard-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .dashboard-title {
                font-size: 2rem;
            }

            .grid-container {
                grid-template-columns: 1fr;
            }

            .balance-card {
                grid-column: span 1;
            }

            .balance-amount {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .table-container {
                overflow-x: auto;
            }

            .table {
                min-width: 800px;
            }
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(16, 64, 66, 0.1);
            border-radius: 4px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #104042, #afff1a);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 1rem;
            top: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #104042;
        }
    </style>
    <div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-md-8 offset-md-2">
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">Dashboard</h1>
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($fullName, 0, 2)); ?>
                </div>
                <div>
                    <div style="font-weight: 600;"><?php echo $fullName ?></div>
                    <div style="opacity: 0.8; font-size: 0.9rem;">Welcome back!</div>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid-container">
            <!-- Balance Card -->
            <div class="card balance-card">
                <div class="card-header">
                    <h3 class="card-title">Account Balance</h3>
                    <div class="card-icon" style="background: rgba(255, 255, 255, 0.2); color: white;">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div class="balance-label">Current Balance</div>
                <div class="balance-amount">
                    <?php echo $currency . number_format($acct_balance, 2, '.', ','); ?>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">Pending Balance</span>
                    <span class="summary-value"><?php echo $currency . number_format($avail_balance, 2, '.', ','); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Loan Balance</span>
                    <span class="summary-value text-danger"><?php echo $currency . $row['loan_balance'] ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Last Login IP</span>
                    <span class="summary-value"><?= $logs['ipAddress'] ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Last Login Date</span>
                    <span class="summary-value"><?= $logs['datenow'] ?></span>
                </div>

                <div class="action-buttons">
                    <a href="./domestic-transfer.php" class="btn btn-secondary">
                        <i class="fas fa-exchange-alt"></i>
                        Domestic Transfer
                    </a>
                    <a href="./wire-transfer.php" class="btn btn-outline" style="border-color: white; color: white;">
                        <i class="fas fa-globe"></i>
                        Wire Transfer
                    </a>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Account Summary</h3>
                    <div class="card-icon icon-primary">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">
                        <i class="fas fa-credit-card" style="margin-right: 0.5rem; color: #104042;"></i>
                        Account Limit
                    </span>
                    <span class="summary-value"><?=$currency.$row['acct_limit'] ?></span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 100%;"></div>
                </div>

                <div class="summary-item">
                    <span class="summary-label">
                        <i class="fas fa-hand-holding-usd" style="margin-right: 0.5rem; color: #104042;"></i>
                        Loan Balance
                    </span>
                    <span class="summary-value"><?= $currency.$row['loan_balance']?></span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 100%;"></div>
                </div>

                <div class="summary-item">
                    <span class="summary-label">
                        <i class="fas fa-receipt" style="margin-right: 0.5rem; color: #104042;"></i>
                        Expenses
                    </span>
                    <span class="summary-value"><?=$currency."".$limitRemain ?></span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 100%;"></div>
                </div>

                <div class="clock-widget">
                    <script src="https://cdn.logwork.com/widget/clock.js"></script>
                    <a href="https://logwork.com/clock-widget/" class="clock-time" data-style="default-numeral" data-size="180" data-timezone="Africa/Lagos">Current time</a>
                </div>
            </div>

            <!-- Daily Stats Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daily Statistics</h3>
                    <div class="card-icon icon-success">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
                <div style="text-align: center; padding: 2rem 0;">
                    <div style="font-size: 1.5rem; font-weight: 600; color: #104042; margin-bottom: 1rem;">
                        Transaction Overview
                    </div>
                    <a href="./credit-debit_transaction.php" class="btn btn-primary">
                        <i class="fas fa-eye"></i>
                        View Details
                    </a>
                </div>
            </div>

            <!-- Recent Transaction Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Transaction</h3>
                    <div class="card-icon icon-warning">
                        <i class="fas fa-history"></i>
                    </div>
                </div>
                
                <?php
                $acct_id = userDetails('id');
                $sql2="SELECT * FROM transactions LEFT JOIN users ON transactions.user_id =users.id WHERE transactions.user_id =:acct_id order by transactions.trans_id DESC LIMIT 1";
                $stmt = $conn->prepare($sql2);
                $stmt->execute(['acct_id'=>$acct_id]);
                $sn=1;
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $transStatus = transStatus($result);
                    if($result['trans_type'] === '1'){
                        $trans_type = "<span class='text-success'>Credit</span>";
                    }else if($result['trans_type']=== '2'){
                        $trans_type = "<span class='text-danger'>Debit</span>";
                    }
                    $senderName = $result['sender_name'];
                    $description = $result['description'];
                ?>
                    <div class="summary-item">
                        <span class="summary-label">Amount</span>
                        <span class="summary-value"><?= $currency.$result['amount'] ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Type</span>
                        <span class="summary-value"><?= $trans_type ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">From/To</span>
                        <span class="summary-value"><?= $senderName ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Description</span>
                        <span class="summary-value"><?= $description ?></span>
                    </div>
                <?php } ?>

                <div style="margin-top: 1rem;">
                    <?php echo $userStatus ?>
                </div>
            </div>
        </div>

        <!-- Wire Transactions Section -->
        <div class="transactions-section">
            <h2 class="section-title">
                <i class="fas fa-globe"></i>
                Recent Wire Transactions
            </h2>
            
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
                        $acct_id = userDetails('id');
                        $sql2 ="SELECT * FROM wire_transfer WHERE acct_id =:acct_id ORDER BY wire_id DESC";
                        $wire = $conn->prepare($sql2);
                        $wire->execute(['acct_id'=>$acct_id]);
                        $sn=1;
                        while ($result = $wire->fetch(PDO::FETCH_ASSOC)){
                            $transStatus = wireStatus($result);
                        ?>
                            <tr>
                                <td><?= $sn++ ?></td>
                                <td><strong><?=$currency. $result['amount'] ?></strong></td>
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
                                <td>
                                    <?php if ($result['wire_status']==0){?>
                                        <span class="status-badge status-progress">In Progress</span>
                                    <?php } elseif ($result['wire_status']==1){ ?>
                                        <span class="status-badge status-completed">Completed</span>
                                    <?php } elseif ($result['wire_status']==2){ ?>
                                        <span class="status-badge status-hold">Hold</span>
                                    <?php } elseif ($result['wire_status']==3){ ?>
                                        <span class="status-badge status-cancelled">Cancelled</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                
                <div class="print-section">
                    <a href="javascript:window.print()" class="btn btn-primary">
                        <i class="fas fa-print"></i>
                        Print Statement
                    </a>
                    <a href="./domestic-transaction.php" class="btn btn-outline">
                        <i class="fas fa-eye"></i>
                        View Domestic Transactions
                    </a>
                </div>
            </div>
        </div>

        <!-- Credit/Debit Transactions Section -->
        <div class="transactions-section">
            <h2 class="section-title">
                <i class="fas fa-exchange-alt"></i>
                Credit / Debit Transactions
            </h2>
            
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Sender / Receiver</th>
                            <th>Description</th>
                            <th>Created At</th>
                            <th>Time Created</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql="SELECT * FROM transactions LEFT JOIN users ON transactions.user_id =users.id WHERE transactions.user_id =:acct_id order by transactions.trans_id DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(['acct_id'=>$acct_id]);
                        $sn=1;
                        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                            $transStatus = transStatus($result);
                            if($result['trans_type'] === '1'){
                                $trans_type = "<span class='text-success'><i class='fas fa-arrow-down'></i> Credit</span>";
                            }else if($result['trans_type']=== '2'){
                                $trans_type = "<span class='text-danger'><i class='fas fa-arrow-up'></i> Debit</span>";
                            }
                        ?>
                            <tr>
                                <td><?= $sn++ ?></td>
                                <td><strong><?=$currency. $result['amount'] ?></strong></td>
                                <td><?= $trans_type ?></td>
                                <td><?= $result['sender_name'] ?></td>
                                <td><?=$result['description'] ?></td>
                                <td><?= $result['created_at'] ?></td>
                                <td><?= $result['time_created'] ?></td>
                                <td><span class="status-badge status-completed">Completed</span></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                
                <div class="print-section">
                    <a href="javascript:window.print()" class="btn btn-primary">
                        <i class="fas fa-print"></i>
                        Print Statement
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for transactions (keeping original modal functionality) -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Quick Transfer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Select transfer type:</p>
                    <div class="action-buttons">
                        <a href="./domestic-transfer.php" class="btn btn-primary">Domestic Transfer</a>
                        <a href="./wire-transfer.php" class="btn btn-secondary">Wire Transfer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add smooth scrolling and interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            // Add click handlers for modal
            const modalTrigger = document.getElementById('homeTransModal');
            const modal = document.getElementById('exampleModal');
            
            if (modalTrigger && modal) {
                modalTrigger.addEventListener('click', function() {
                    modal.style.display = 'block';
                });
            }

            // Close modal functionality
            const closeButtons = document.querySelectorAll('.close, [data-dismiss="modal"]');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const modals = document.querySelectorAll('.modal');
                    modals.forEach(modal => {
                        modal.style.display = 'none';
                    });
                });
            });

            // Add loading animation for tables
            const tables = document.querySelectorAll('.table');
            tables.forEach(table => {
                table.style.opacity = '0';
                table.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    table.style.transition = 'all 0.5s ease';
                    table.style.opacity = '1';
                    table.style.transform = 'translateY(0)';
                }, 100);
            });

            // Add hover effects for cards
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });

        // Print functionality
        function printStatement() {
            window.print();
        }

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all cards and sections
        document.querySelectorAll('.card, .transactions-section').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });
    </script>

<?php
include_once('layouts/footer.php');
?>
