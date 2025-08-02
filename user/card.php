<?php
$pageName = "Virtual Card";
include_once("layouts/header.php");
require_once("../include/cardFunction.php");


if($acct_stat != 'active'){
    header("Location:./error.php");
    exit();
}
?>

<style>
    /* Page specific styles */
    .card-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 992px) {
        .card-container {
            grid-template-columns: 1fr;
        }
    }
    
    .virtual-card {
        background: linear-gradient(135deg, #104042 0%, #0a2a2b 100%);
        border-radius: 16px;
        padding: 25px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(16, 64, 66, 0.2);
        margin-bottom: 30px;
        height: 220px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .card-chip {
        position: absolute;
        right: 25px;
        top: 25px;
        font-size: 24px;
        color: #FFD200;
    }
    
    .card-type {
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 10px;
        font-weight: 500;
    }
    
    .card-number {
        font-size: 22px;
        letter-spacing: 2px;
        margin-bottom: 20px;
        font-weight: 600;
    }
    
    .card-details {
        display: flex;
        justify-content: space-between;
    }
    
    .card-holder, .card-expiry {
        display: flex;
        flex-direction: column;
    }
    
    .detail-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
        opacity: 0.7;
    }
    
    .detail-value {
        font-size: 16px;
        font-weight: 500;
    }
    
    .card-brand {
        position: absolute;
        bottom: 25px;
        right: 25px;
        font-size: 24px;
        font-weight: 700;
        color: #afff1a;
    }
    
    .card-actions {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }
    
    .card-action-btn {
        background-color: rgba(16, 64, 66, 0.05);
        color: #104042;
        border: 1px solid rgba(16, 64, 66, 0.1);
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .card-action-btn:hover {
        background-color: rgba(16, 64, 66, 0.1);
    }
    
    .card-action-btn i {
        font-size: 18px;
    }
    
    .card-info-container {
        background-color: #fff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
        margin-bottom: 30px;
    }
    
    .card-info-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(16, 64, 66, 0.1);
    }
    
    .card-info-title {
        font-size: 18px;
        font-weight: 600;
        color: #104042;
    }
    
    .card-info-item {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid rgba(16, 64, 66, 0.05);
    }
    
    .card-info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        color: rgba(16, 64, 66, 0.7);
        font-size: 14px;
    }
    
    .info-value {
        font-weight: 500;
        color: #104042;
    }
    
    .card-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .status-active {
        background-color: rgba(175, 255, 26, 0.2);
        color: #104042;
    }
    
    .status-inactive {
        background-color: rgba(255, 210, 0, 0.2);
        color: #104042;
    }
    
    .status-blocked {
        background-color: rgba(255, 0, 0, 0.1);
        color: #d32f2f;
    }
    
    .card-request-container {
        background-color: #fff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(16, 64, 66, 0.1);
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #104042;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #104042;
    }
    
    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid rgba(16, 64, 66, 0.2);
        border-radius: 8px;
        background-color: rgba(16, 64, 66, 0.02);
        color: #104042;
        font-size: 15px;
    }
    
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        border-color: #104042;
        box-shadow: 0 0 0 2px rgba(16, 64, 66, 0.1);
    }
    
    .btn-request-card {
        background-color: #104042;
        color: #fff;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-top: 10px;
    }
    
    .btn-request-card:hover {
        background-color: #165e61;
    }
    
    @media (max-width: 576px) {
        .card-actions {
            flex-direction: column;
            gap: 10px;
        }
        
        .card-action-btn {
            width: 100%;
            justify-content: center;
        }
        
        .virtual-card {
            height: auto;
            padding: 20px;
        }
        
        .card-number {
            font-size: 18px;
            margin-bottom: 15px;
        }
        
        .card-details {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>

<div class="main-content">
    <header class="header">
        <h1>Virtual Card</h1>
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
        <?php
        $sql2 = "SELECT * FROM card WHERE user_id=:user_id";
        $stmt = $conn->prepare($sql2);
        $stmt->execute([
            'user_id'=>$user_id
        ]);

        $cardCheck = $stmt->fetch(PDO::FETCH_ASSOC);

        if($stmt->rowCount() === 0) {
        ?>
            <!-- Enhanced Card Generation Form -->
            <div class="card-info-container">
                <div class="section-header">
                    <div class="section-title">Generate Credit Card</div>
                </div>
                
                <form action="" method="post">
                    <div class="form-group">
                        <label for="name">Card Holder Name</label>
                        <input id="name" maxlength="20" value="<?=$fullName?>" name="card_name" type="text" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="card_type">Card Type</label>
                        <select name="card_type" id="card_type" required>
                            <option value="">Select Card Type</option>
                            <option value="mastercard">Master CARD</option>
                            <option value="visa">VISA</option>
                            <option value="american express">AMERICAN EXPRESS</option>
                            <option value="discover">Discover</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="card_reason">Request Reason</label>
                        <textarea id="card_reason" name="card_reason" rows="3" placeholder="Please explain why you need this card" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="cardnumber">Card Number</label>
                        <div class="d-flex align-items-center">
                            <input id="cardnumber" type="text" inputmode="numeric" name="card_number" readonly required>
                            <span id="generatecard" class="btn-request-card" style="width: auto; margin-left: 10px; margin-top: 0;">Generate Card</span>
                        </div>
                    </div>
                    
                    <div class="form-row" style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label for="expirationdate">Expiration (mm/yy)</label>
                            <input id="expirationdate" type="text" inputmode="numeric" name="card_expiration" value="07/26" readonly required>
                        </div>
                        
                        <div class="form-group" style="flex: 1;">
                            <label for="securitycode">Security Code</label>
                            <input id="securitycode" type="text" inputmode="numeric" name="security" value="897" readonly required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-request-card" name="card_generate">Submit Request</button>
                </form>
            </div>
        <?php
        } else {
            $card_number = explode(' ',$cardCheck['card_number']);
            $card_type = cardTypeName($card_number);
            $cardStatus = getCardStatus($cardCheck);
        ?>
            <!-- Virtual Card Display -->
            <div class="virtual-card">
                <div class="card-chip">
                    <i class="fas fa-wifi"></i>
                </div>
                <div>
                    <div class="card-type">Virtual Debit Card</div>
                    <div class="card-number">
                        <span><?=$card_number[0]?></span>
                        <span><?=$card_number[1]?></span>
                        <span><?=$card_number[2]?></span>
                        <span><?=$card_number[3]?></span>
                    </div>
                </div>
                <div class="card-details">
                    <div class="card-holder">
                        <div class="detail-label">Card Holder</div>
                        <div class="detail-value"><?=$cardCheck['card_name']?></div>
                    </div>
                    <div class="card-expiry">
                        <div class="detail-label">Expires</div>
                        <div class="detail-value"><?=$cardCheck['card_expiration']?></div>
                    </div>
                </div>
                <div class="card-brand"><?=$card_type?></div>
            </div>

            <!-- Card Actions -->
            <form method="POST">
                <div class="card-actions">
                    <?php
                    $sql2 = "SELECT * FROM card_request WHERE user_id=:user_id";
                    $stmt = $conn->prepare($sql2);
                    $stmt->execute([
                        'user_id'=>$user_id
                    ]);

                    if($stmt->rowCount() < 1) {
                    ?>
                        <a href="#card-generation" class="card-action-btn" onclick="document.getElementById('card_type').focus();">
                            <i class="fas fa-plus"></i>
                            <span>New Card</span>
                        </a>
                    <?php
                    } else {
                    ?>
                        <button type="button" class="card-action-btn" disabled>
                            <i class="fas fa-clock"></i>
                            <span>New Card On Progress</span>
                        </button>
                    <?php
                    }
                    
                    if($cardCheck['card_status']==='1') {
                    ?>
                        <button type="submit" class="card-action-btn" name="pause_card">
                            <i class="fas fa-pause"></i>
                            <span>Pause Card</span>
                        </button>
                    <?php
                    }
                    
                    if($cardCheck['card_status']==='4') {
                    ?>
                        <button type="submit" class="card-action-btn" name="active_card">
                            <i class="fas fa-play"></i>
                            <span>Activate Card</span>
                        </button>
                    <?php
                    }
                    
                    if($cardCheck['card_status']==='3') {
                    ?>
                        <a href="mailto:<?=$page['url_email']?>" class="card-action-btn">
                            <i class="fas fa-envelope"></i>
                            <span>Contact Support</span>
                        </a>
                    <?php
                    }
                    ?>
                </div>
            </form>

            <!-- Card Info and Transactions Container -->
            <div class="card-container">
                <div class="left-column">
                    <!-- Card Information -->
                    <div class="card-info-container">
                        <div class="card-info-header">
                            <div class="card-info-title">Card Information</div>
                        </div>
                        
                        <div class="card-info-item">
                            <div class="info-label">Card Status</div>
                            <div class="info-value">
                                <?php 
                                if($cardCheck['card_status'] === '1') {
                                    echo '<span class="card-status status-active">Active</span>';
                                } elseif($cardCheck['card_status'] === '4') {
                                    echo '<span class="card-status status-inactive">Paused</span>';
                                } elseif($cardCheck['card_status'] === '3') {
                                    echo '<span class="card-status status-blocked">Blocked</span>';
                                } else {
                                    echo '<span class="card-status status-inactive">Inactive</span>';
                                }
                                ?>
                            </div>
                        </div>
                        
                        <div class="card-info-item">
                            <div class="info-label">Card Type</div>
                            <div class="info-value"><?= $card_type ?> Debit Card</div>
                        </div>
                        
                        <div class="card-info-item">
                            <div class="info-label">Card Number</div>
                            <div class="info-value">
                                <?=$card_number[0]?> <?=$card_number[1]?> <?=$card_number[2]?> <?=$card_number[3]?>
                            </div>
                        </div>
                        
                        <div class="card-info-item">
                            <div class="info-label">Expiration Date</div>
                            <div class="info-value"><?=$cardCheck['card_expiration']?></div>
                        </div>
                        
                        <div class="card-info-item">
                            <div class="info-label">Card Limit</div>
                            <div class="info-value"><?= $currency ?><?=$cardCheck['card_limit'] ?></div>
                        </div>
                        
                        <div class="card-info-item">
                            <div class="info-label">Card Limit Remain</div>
                            <div class="info-value"><?= $currency ?><?= $cardCheck['card_limit_remain'] ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="right-column">
                    <!-- Card Transaction Logs -->
                    <div class="card-request-container">
                        <div class="section-header">
                            <div class="section-title">Recent Card Transactions</div>
                        </div>
                        
                        <?php
                        // Get card transactions from the database
                        $cardTransSql = "SELECT * FROM transactions WHERE user_id = :user_id AND description LIKE '%card%' ORDER BY trans_id DESC LIMIT 10";
                        $cardTransStmt = $conn->prepare($cardTransSql);
                        $cardTransStmt->execute(['user_id' => $user_id]);
                        $cardTransactions = $cardTransStmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (count($cardTransactions) > 0) {
                        ?>
                            <div class="transaction-list" style="max-height: 400px; overflow-y: auto;">
                                <?php foreach ($cardTransactions as $trans): ?>
                                    <div class="transaction-item" style="padding: 15px; border-bottom: 1px solid rgba(16, 64, 66, 0.1); display: flex; justify-content: between; align-items: center;">
                                        <div style="flex: 1;">
                                            <div style="font-weight: 600; color: #104042; margin-bottom: 5px;">
                                                <?= htmlspecialchars($trans['description']) ?>
                                            </div>
                                            <div style="font-size: 12px; color: rgba(16, 64, 66, 0.7);">
                                                <?= date('M d, Y', strtotime($trans['created_at'])) ?>
                                            </div>
                                        </div>
                                        <div style="text-align: right;">
                                            <div style="font-weight: 600; <?= $trans['trans_type'] === '1' ? 'color: #28a745;' : 'color: #dc3545;' ?>">
                                                <?= $trans['trans_type'] === '1' ? '+' : '-' ?><?= $currency . number_format($trans['amount'], 2) ?>
                                            </div>
                                            <div style="font-size: 12px;">
                                                <span style="padding: 2px 8px; border-radius: 12px; font-size: 10px; background: rgba(40, 167, 69, 0.1); color: #28a745;">
                                                    Completed
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div style="text-align: center; margin-top: 15px;">
                                <a href="credit-debit_transaction.php" class="btn-request-card" style="display: inline-block; padding: 8px 15px; font-size: 14px; text-decoration: none;">
                                    View All Transactions
                                </a>
                            </div>
                        <?php } else { ?>
                            <div class="no-transactions" style="text-align: center; padding: 40px 20px;">
                                <div style="font-size: 48px; color: rgba(16, 64, 66, 0.3); margin-bottom: 20px;">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div style="font-size: 18px; font-weight: 600; color: #104042; margin-bottom: 10px;">
                                    No Card Transactions Yet
                                </div>
                                <div style="color: rgba(16, 64, 66, 0.7); font-size: 14px; line-height: 1.5;">
                                    Once you start using your card, your transaction history will appear here.
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>



<?php
include_once("layouts/cardfooter.php");
?>


