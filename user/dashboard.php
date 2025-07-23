<?php 
$pageName = "Dashboard";
include 'layouts/header.php'; 

// Welcome message for first visit
if(@!$_COOKIE['firstVisit']){
    setcookie("firstVisit", "no", time() + 3600);
    // If you have a notification function, you can use it here
    // notify_alert('Welcome Back '.$fullName." !",'success','3000','Close');
}

// Clear any session transfer data
unset($_SESSION['wire_transfer'], $_SESSION['dom_transfer']);
?>
        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <h1>Dashboard</h1>
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
                            <?php if(isset($row['image']) && !empty($row['image'])): ?>
                                <img src="../assets/profile/<?php echo $row['image']; ?>" alt="User avatar">
                            <?php else: ?>
                                <div style="width: 40px; height: 40px; background-color: #104042; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                    <?php echo strtoupper(substr($fullName, 0, 2)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="user-name"><?php echo $fullName; ?></div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Stats Cards -->
                <div class="stats-cards">
                    <div class="stat-card income">
                        <div class="stat-info">
                            <div class="stat-title">Account Balance</div>
                            <div class="stat-amount"><?php echo $currency . number_format($acct_balance, 2, '.', ','); ?></div>
                            <div class="stat-detail">
                                <span>Available Balance</span>
                                <span class="positive"><?php echo $currency . number_format($avail_balance, 2, '.', ','); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card spending">
                        <div class="stat-info">
                            <div class="stat-title">Loan Balance</div>
                            <div class="stat-amount"><?php echo $currency . (isset($row['loan_balance']) ? number_format($row['loan_balance'], 2, '.', ',') : '0.00'); ?></div>
                            <div class="stat-detail">
                                <span>Loan Status</span>
                                <span class="negative"><?php echo isset($row['loan_status']) && $row['loan_status'] == 1 ? 'Active' : 'No Active Loan'; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card balance">
                        <div class="stat-info">
                            <div class="stat-title">Your Balance</div>
                            <div class="stat-amount"><?php echo $currency . number_format($acct_balance, 2, '.', ','); ?></div>
                            <div class="stat-detail">
                                <span>Available Funds</span>
                                <button class="btn-quick-action" onclick="location.href='../user/wire-transfer.php'">Transfer</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Section -->
                <div class="quick-actions-section">
                    <div class="section-header">
                        <h2>Quick Actions</h2>
                    </div>
                    <div class="quick-actions-grid">
                        <div class="quick-action-card" onclick="location.href='../user/domestic-transfer.php'">
                            <div class="action-icon">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div class="action-title">Domestic Transfer</div>
                            <div class="action-hint">Send money locally</div>
                        </div>
                        <div class="quick-action-card" onclick="location.href='../user/wire-transfer.php'">
                            <div class="action-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="action-title">Wire Transfer</div>
                            <div class="action-hint">Send internationally</div>
                        </div>
                        <div class="quick-action-card" onclick="location.href='../user/deposit.php'">
                            <div class="action-icon">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                            <div class="action-title">Deposit</div>
                            <div class="action-hint">Add funds</div>
                        </div>
                        <div class="quick-action-card" onclick="location.href='../user/withdrawal.php'">
                            <div class="action-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="action-title">Withdrawal</div>
                            <div class="action-hint">Get cash</div>
                        </div>
                    </div>
                </div>

                <!-- Loan Status Section -->
                <div class="loan-section">
                    <div class="section-header">
                        <h2>Loan Status</h2>
                        <button class="more-options"><i class="fas fa-ellipsis-v"></i></button>
                    </div>
                    <div class="loan-details">
                        <?php
                        // Get loan information
                        $sql = "SELECT * FROM loan WHERE acct_id = :acct_id ORDER BY loan_id DESC LIMIT 1";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(['acct_id' => $user_id]);
                        
                        if($stmt->rowCount() > 0) {
                            $loan = $stmt->fetch(PDO::FETCH_ASSOC);
                            $loanAmount = $loan['amount'];
                            $loanPaid = $loan['amount_paid'] ?? 0;
                            $loanPercent = ($loanPaid / $loanAmount) * 100;
                        ?>
                        <div class="loan-info">
                            <div class="loan-type"><?php echo $loan['loan_type'] ?? 'Personal Loan'; ?></div>
                            <div class="loan-amount"><?php echo $currency . number_format($loanAmount, 2, '.', ','); ?></div>
                            <div class="loan-progress">
                                <div class="progress-bar">
                                    <div class="progress" style="width: <?php echo $loanPercent; ?>%;"></div>
                                </div>
                                <div class="progress-text">
                                    <span><?php echo round($loanPercent); ?>% Paid</span>
                                    <span><?php echo $currency . number_format($loanPaid, 2, '.', ','); ?> / <?php echo $currency . number_format($loanAmount, 2, '.', ','); ?></span>
                                </div>
                            </div>
                            <div class="loan-details-row">
                                <div class="detail-item">
                                    <span class="detail-label">Next Payment</span>
                                    <span class="detail-value"><?php echo $currency . number_format($loan['monthly_payment'] ?? 0, 2, '.', ','); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Due Date</span>
                                    <span class="detail-value"><?php echo date('d M Y', strtotime($loan['due_date'] ?? 'now')); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Interest Rate</span>
                                    <span class="detail-value"><?php echo $loan['interest_rate'] ?? '4.5'; ?>%</span>
                                </div>
                            </div>
                            <button class="btn-pay-loan" onclick="location.href='../user/loan.php'">Make Payment</button>
                        </div>
                        <?php } else { ?>
                        <div class="loan-info">
                            <div class="loan-type">No Active Loan</div>
                            <div class="loan-amount">$0.00</div>
                            <div class="loan-progress">
                                <div class="progress-bar">
                                    <div class="progress" style="width: 0%;"></div>
                                </div>
                                <div class="progress-text">
                                    <span>0% Paid</span>
                                    <span>$0.00 / $0.00</span>
                                </div>
                            </div>
                            <div class="loan-details-row">
                                <div class="detail-item">
                                    <span class="detail-label">Next Payment</span>
                                    <span class="detail-value">$0.00</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Due Date</span>
                                    <span class="detail-value">N/A</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Interest Rate</span>
                                    <span class="detail-value">0.0%</span>
                                </div>
                            </div>
                            <button class="btn-pay-loan" onclick="location.href='../user/loan.php'">Apply for Loan</button>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Wire Transfer History Section -->
                <div class="wire-transfer-section">
                    <div class="section-header">
                        <h2>Wire Transfer History</h2>
                        <button class="more-options" onclick="location.href='../user/wire-transaction.php'"><i class="fas fa-ellipsis-v"></i></button>
                    </div>
                    <div class="wire-transfer-list">
                        <?php
                        // Get wire transfer information
                        $sql = "SELECT * FROM wire_transfer WHERE acct_id = :acct_id ORDER BY wire_id DESC LIMIT 3";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(['acct_id' => $user_id]);
                        
                        if($stmt->rowCount() > 0) {
                            while($transfer = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $transferType = $transfer['trans_type'] ?? 'Wire Transfer';
                                $transferStatus = $transfer['wire_status'];
                                $statusText = 'Pending';
                                
                                if($transferStatus == 1) {
                                    $statusText = 'Completed';
                                } elseif($transferStatus == 2) {
                                    $statusText = 'On Hold';
                                } elseif($transferStatus == 3) {
                                    $statusText = 'Cancelled';
                                }
                        ?>
                        <div class="wire-transfer-item">
                            <div class="transfer-icon outgoing">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <div class="transfer-details">
                                <div class="transfer-info">
                                    <div class="transfer-name"><?php echo $transfer['bank_name']; ?></div>
                                    <div class="transfer-reference">REF: <?php echo $transfer['refrence_id']; ?></div>
                                </div>
                                <div class="transfer-date"><?php echo date('F d, Y', strtotime($transfer['createdAt'])); ?></div>
                            </div>
                            <div class="transfer-amount outgoing">
                                <div class="amount">-<?php echo $currency . number_format($transfer['amount'], 2, '.', ','); ?></div>
                                <div class="status <?php echo strtolower($statusText); ?>"><?php echo $statusText; ?></div>
                            </div>
                        </div>
                        <?php 
                            }
                        } else { 
                        ?>
                        <div class="wire-transfer-item no-records">
                            <div class="transfer-details" style="text-align: center; width: 100%; padding: 20px;">
                                <div class="transfer-info">
                                    <div class="transfer-name">No wire transfer records found</div>
                                    <div class="transfer-reference">Your transfer history will appear here once you make a transfer</div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="view-all-transfers">
                        <button class="btn-view-all" onclick="location.href='../user/wire-transaction.php'">View All Transfers</button>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="transactions-section">
                    <h2>Recent Transactions</h2>
                    <table class="transactions-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>ID</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Get transaction information
                            $sql = "SELECT * FROM transactions WHERE user_id = :user_id ORDER BY trans_id DESC LIMIT 4";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute(['user_id' => $user_id]);
                            
                            if($stmt->rowCount() > 0) {
                                while($transaction = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $transType = $transaction['trans_type'];
                                    $transClass = $transType == '1' ? 'positive' : 'negative';
                                    $transPrefix = $transType == '1' ? '+' : '-';
                                    $transStatus = 'completed';
                                    
                                    // You can add logic to determine status based on your database structure
                                    if(isset($transaction['status'])) {
                                        if($transaction['status'] == 0) {
                                            $transStatus = 'pending';
                                        } elseif($transaction['status'] == 2) {
                                            $transStatus = 'cancelled';
                                        }
                                    }
                            ?>
                            <tr>
                                <td>
                                    <div class="user">
                                        <div style="width: 30px; height: 30px; background-color: #104042; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                            <?php echo strtoupper(substr($transaction['sender_name'] ?? 'User', 0, 1)); ?>
                                        </div>
                                        <span><?php echo $transaction['sender_name'] ?? 'Unknown'; ?></span>
                                    </div>
                                </td>
                                <td><?php echo $transaction['reference_id'] ?? $transaction['trans_id']; ?></td>
                                <td class="<?php echo $transClass; ?>"><?php echo $transPrefix . $currency . number_format($transaction['amount'], 2, '.', ','); ?></td>
                                <td><span class="status <?php echo $transStatus; ?>"><?php echo ucfirst($transStatus); ?></span></td>
                                <td><?php echo date('d/m/y', strtotime($transaction['created_at'])); ?></td>
                                <td>
                                    <span class="card <?php echo $transType == '1' ? 'visa' : 'mastercard'; ?>">
                                        <?php echo $transType == '1' ? 'Credit' : 'Debit'; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php 
                                }
                            } else { 
                            ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">
                                    No transaction records found. Your transaction history will appear here once you make transactions.
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <!-- Right Sidebar -->
        <aside class="right-sidebar">
            <div class="balance-section">
                <h3>Balance</h3>
                <div class="balance-amount"><?php echo $currency . number_format($acct_balance, 2, '.', ','); ?></div>
                <div class="balance-actions">
                    <button class="btn-send" onclick="location.href='../user/domestic-transfer.php'">Send</button>
                    <button class="btn-withdraw" onclick="location.href='../user/withdrawal.php'">Withdraw</button>
                </div>
            </div>

            <div class="cards-section">
                <div class="section-header">
                    <h3>Cards</h3>
                    <button class="more-options" onclick="location.href='../user/card.php'"><i class="fas fa-ellipsis-v"></i></button>
                </div>
                <?php
                // Check if user has a card
                if(isset($cardCheck) && $cardCheck) {
                ?>
                <div class="credit-card">
                    <div class="card-network">
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fas fa-wifi"></i>
                    </div>
                    <div class="card-number"><?php echo chunk_split($cardCheck['card_number'], 4, ' '); ?></div>
                    <div class="card-details">
                        <div class="card-holder">
                            <span>Name</span>
                            <span><?php echo $fullName; ?></span>
                        </div>
                        <div class="card-expiry">
                            <span>Exp Date</span>
                            <span><?php echo $cardCheck['card_expiry']; ?></span>
                        </div>
                    </div>
                </div>
                <?php } else { ?>
                <div class="credit-card" style="background-color: #f5f5f5; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px;">
                    <i class="fas fa-credit-card" style="font-size: 2rem; color: #104042; margin-bottom: 10px;"></i>
                    <div style="text-align: center;">
                        <p>No virtual card found</p>
                        <button class="btn-send" onclick="location.href='../user/card.php'" style="margin-top: 10px;">Apply for Card</button>
                    </div>
                </div>
                <?php } ?>

                <!-- Card Requests Section -->
                <div class="card-requests">
                    <h3>Card Requests</h3>
                    <?php
                    // Check for card requests
                    $sql = "SELECT * FROM card_request WHERE user_id = :user_id ORDER BY request_id DESC LIMIT 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(['user_id' => $user_id]);
                    
                    if($stmt->rowCount() > 0) {
                        $request = $stmt->fetch(PDO::FETCH_ASSOC);
                        $requestStatus = $request['status'] ?? 0;
                        $statusText = 'Processing';
                        
                        if($requestStatus == 1) {
                            $statusText = 'Approved';
                        } elseif($requestStatus == 2) {
                            $statusText = 'Declined';
                        }
                    ?>
                    <div class="request-item">
                        <div class="request-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="request-details">
                            <div class="request-type">Virtual Card</div>
                            <div class="request-status pending"><?php echo $statusText; ?></div>
                        </div>
                        <div class="request-date"><?php echo date('d/m/y', strtotime($request['created_at'] ?? 'now')); ?></div>
                    </div>
                    <?php } else { ?>
                    <div class="request-item" style="display: flex; justify-content: center; padding: 10px;">
                        <div class="request-details" style="text-align: center;">
                            <div class="request-type">No card requests</div>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Messages Section -->
                <div class="messages-section">
                    <div class="section-header">
                        <h3>Messages</h3>
                        <button class="more-options"><i class="fas fa-ellipsis-v"></i></button>
                    </div>
                    <div class="message-list">
                        <?php
                        // Check for messages
                        $sql = "SELECT * FROM messages WHERE user_id = :user_id ORDER BY message_id DESC LIMIT 2";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(['user_id' => $user_id]);
                        
                        if($stmt->rowCount() > 0) {
                            while($message = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $isRead = $message['is_read'] ?? 0;
                                $messageClass = $isRead ? '' : 'unread';
                        ?>
                        <div class="message-item <?php echo $messageClass; ?>">
                            <div class="message-avatar">
                                <img src="/assets/images/admin-avatar.png" alt="Admin">
                            </div>
                            <div class="message-content">
                                <div class="message-sender"><?php echo $message['sender'] ?? 'Admin'; ?></div>
                                <div class="message-preview"><?php echo substr($message['message'], 0, 40) . '...'; ?></div>
                                <div class="message-time"><?php echo date('h:i A', strtotime($message['created_at'])); ?></div>
                            </div>
                        </div>
                        <?php 
                            }
                        } else { 
                        ?>
                        <div class="message-item">
                            <div class="message-avatar">
                                <img src="/assets/images/admin-avatar.png" alt="System">
                            </div>
                            <div class="message-content">
                                <div class="message-sender">System</div>
                                <div class="message-preview">Welcome to your dashboard. No new messages.</div>
                                <div class="message-time">Today</div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="send-money-form">
                    <div class="card-input">
                        <div class="card-icon">
                            <i class="fab fa-cc-mastercard"></i>
                        </div>
                        <input type="text" value="Quick Transfer" readonly>
                    </div>
                    <div class="recipient-input">
                        <label>Recipient Name</label>
                        <input type="text" placeholder="Enter recipient name">
                    </div>
                    <div class="amount-input">
                        <label>Amount</label>
                        <div class="amount-field">
                            <select>
                                <option><?php echo $row['acct_currency']; ?></option>
                            </select>
                            <input type="text" placeholder="0.00">
                        </div>
                    </div>
                    <button class="btn-send-money" onclick="location.href='../user/domestic-transfer.php'">Send Money</button>
                </div>
            </div>
        </aside>
    </div>
<?php include 'layouts/footer.php'; ?>