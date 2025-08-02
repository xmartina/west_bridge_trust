<?php

$pageName = "Crypto Withdrawal";
include("../include/vendor/autoload.php");
include_once("layouts/header.php");
//require_once("../include/config.php");
//require_once("../include/userFunction.php");
//require_once('../include/userClass.php');


$email = $row['acct_email'];


if(isset($_POST['withdraw'])){
    // $user_id = $_POST['user_id'];
    // $sender_name = $_POST['sender_name'];
    // $amount = $_POST['amount'];
    // $description = $_POST['description'];
    // $created_at = $_POST['created_at'];
    // $time_created = $_POST['time_created'];
    $user_id = userDetails('id');
    $amount = $_POST['amount'];
    $withdraw_method = $_POST['withdraw_method'];
    $wallet_address = $_POST['wallet_address'];

    $trans_type = 2;
    $checkUser = $conn->query("SELECT * FROM users WHERE id='$user_id'");
    $result = $checkUser->fetch(PDO::FETCH_ASSOC);

    if($amount > $result['acct_balance']){
        toast_alert('error','Insufficient Balance');
    }else {




        $available_balance = ($result['acct_balance'] - $amount);
//        $amount-=$result['acct_balance'];

        $sql = "UPDATE users SET acct_balance=:available_balance WHERE id=:user_id";
        $addUp = $conn->prepare($sql);
        $addUp->execute([
            'available_balance' => $available_balance,
            'user_id'=>$user_id
        ]);

            $trans_id = uniqid();
            $sql = "INSERT INTO withdrawal (user_id,amount,withdraw_method,wallet_address,reference_id,trans_type) VALUES(:user_id,:amount,:withdraw_method,:wallet_address,:reference_id,:trans_type)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'user_id'=>$user_id,
                'amount' => $amount,
                'withdraw_method' => $withdraw_method,
                'wallet_address' => $wallet_address,
                'reference_id'=>$trans_id,
                'trans_type' => $trans_type,
            ]);

            $full_name = $user['firstname']. " ". $user['lastname'];
                        // $APP_URL = APP_URL;
                        $APP_NAME = WEB_TITLE;
                        $APP_URL = WEB_URL;
             $user_email = $user['acct_email'];

             $message = $sendMail->WithdrawMsg($currency, $full_name, $amount, $withdraw_method, $wallet_address, $APP_NAME);


             $subject = "Withdrawal Notification". "-". $APP_NAME;
             $email_message->send_mail($user_email, $message, $subject);

             $subject = "User Withdrawal Notification". "-". $APP_NAME;
             $email_message->send_mail(WEB_EMAIL, $message, $subject);

        if (true) {
            toast_alert('success', 'Your Withdrawal has been processed', 'Pending');
        } else {
            toast_alert('error', 'Sorry Something Went Wrong');
        }
        
            // header("Location:./withdrawal-transaction.php");
            // exit;
        
    }
}


?>

<div id="content" class="main-content">
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">
            <div class="col-md-8 offset-md-2">
                <div class="card component-card">
                    <div class="card-body">
                        <div class="user-profile">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    if($acct_stat === 'active'){
                                    ?>
                                    <form method="POST"  enctype="multipart/form-data">
                                        <p>Crypto Withdrawal</p>
                                        <div class="form-group mb-4 mt-4">
                                            <label for="">Amount</label>
                                            <div class="input-group ">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><svg
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather"><line x1="12" y1="1"
                                                                                                          x2="12"
                                                                                                          y2="23"></line><path
                                                                    d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></span>
                                                </div>

                                                <input type="number" class="form-control" name="amount" placeholder="Amount"
                                                       aria-label="notification" aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="form-group mb-4 mt-4">
                                                    <label for="">Withdrawal Type</label>
                                                    <div class="input-group">
                                                       <select name="withdraw_method" required class='selectpicker' onchange="crypto_type(this.value)" data-width='100%'>
                                                           <option>Select</option>
                                                           <?php
                                                            $sql = $conn->query("SELECT * FROM crypto_currency ORDER BY crypto_name");
                                                            while($rs = $sql->fetch(PDO::FETCH_ASSOC)){
                                                                $data[] = array(
                                                                        'id'=>$rs['id'],
                                                                        'wallet_address'=>$rs['wallet_address']
                                                                );
                                                                ?>
                                                                <option value="<?= $rs['id'] ?>"><?= ucwords($rs['crypto_name']) ?></option>
                                                                <?php
                                                            }
                                                           ?>
                                                       </select>




                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4 mt-4">
                                                    <label for="">Wallet Address</label>
                                                    <div class="input-group ">
                                                        <input type="text" class="form-control" name="wallet_address"  required placeholder="Wallet Address"
                                                               aria-label="notification" aria-describedby="basic-addon1">
                                                         </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!--<div class="row">-->
                                        <!--    <div class="col-md-12">-->
                                        <!--        <div class="widget-content widget-content-area">-->
                                        <!--            <div class="custom-file-container" data-upload-id="myFirstImage">-->
                                        <!--                <label>Upload (Single File) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>-->
                                        <!--                <label class="custom-file-container__custom-file" >-->
                                        <!--                    <input type="file" class="custom-file-container__custom-file__custom-file-input" name="image" accept="image/*">-->
                                        <!--                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />-->
                                        <!--                    <span class="custom-file-container__custom-file__custom-file-control"></span>-->
                                        <!--                </label>-->
                                        <!--                <div class="custom-file-container__image-preview"></div>-->
                                        <!--            </div>-->
                                        <!--    </div>-->
                                        <!--</div>-->

                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <button class="btn btn-primary mb-2 mr-2" name="withdraw" >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                    <polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> Withdraw Funds</button>


                                                    <a href="./bank-withdraw.php" class="btn btn-danger mb-2 mr-2" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                    <polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> Use Bank Withdrawal</a>
                                            </div>
                                        </div>
                                </div>
                                        
                                </form>
                            </div>
                                <?php
                                }else{
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div  class="alert custom-alert-1 mb-4 bg-danger border-danger" role="alert">

                                            <div class="media">
                                                <div class="alert-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                                </div>
                                                <div class="media-body">
                                                    <div class="alert-text">
                                                        <strong>Warning! </strong><span> Account on <span class="text-uppercase "><b>hold</b></span> contact support.</span>
                                                    </div>
                                                    <div class="alert-btn">
                                                        <a class="btn btn-default btn-dismiss" href="mailto:<?=$url_email?>">Contact Us</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            <?php
                            }
                            ?>

                            </div>
                </div>
            </div>

        </div>
    </div>


    <?php
    include_once('layouts/footer.php')
    ?>
<?php
$pageName = "Withdrawal";
include_once("layouts/header.php");

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
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }
    
    .withdrawal-container {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
    }
    
    .withdrawal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(16, 64, 66, 0.1);
    }
    
    .withdrawal-title {
        font-size: 24px;
        font-weight: 600;
        color: #104042;
    }
    
    .balance-info {
        text-align: right;
    }
    
    .balance-label {
        font-size: 14px;
        color: rgba(16, 64, 66, 0.7);
        margin-bottom: 5px;
    }
    
    .balance-amount {
        font-size: 18px;
        font-weight: 600;
        color: #104042;
    }
    
    .withdrawal-methods {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .method-card {
        border: 2px solid rgba(16, 64, 66, 0.1);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fff;
    }
    
    .method-card:hover {
        border-color: #104042;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.1);
    }
    
    .method-card.active {
        border-color: #104042;
        background: rgba(16, 64, 66, 0.02);
    }
    
    .method-icon {
        font-size: 32px;
        color: #104042;
        margin-bottom: 15px;
    }
    
    .method-title {
        font-size: 16px;
        font-weight: 600;
        color: #104042;
        margin-bottom: 8px;
    }
    
    .method-description {
        font-size: 14px;
        color: rgba(16, 64, 66, 0.7);
    }
    
    .withdrawal-form {
        display: none;
    }
    
    .withdrawal-form.active {
        display: block;
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
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid rgba(16, 64, 66, 0.2);
        border-radius: 8px;
        background-color: rgba(16, 64, 66, 0.02);
        color: #104042;
        font-size: 15px;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #104042;
        box-shadow: 0 0 0 2px rgba(16, 64, 66, 0.1);
        outline: none;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .btn-withdraw {
        background-color: #104042;
        color: #fff;
        padding: 15px 30px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-top: 20px;
        transition: all 0.3s ease;
    }
    
    .btn-withdraw:hover {
        background-color: #165e61;
    }
    
    .sidebar-info {
        background: #fff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
        height: fit-content;
    }
    
    .info-title {
        font-size: 18px;
        font-weight: 600;
        color: #104042;
        margin-bottom: 20px;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid rgba(16, 64, 66, 0.1);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-size: 14px;
        color: rgba(16, 64, 66, 0.7);
    }
    
    .info-value {
        font-weight: 500;
        color: #104042;
    }
    
    @media (max-width: 992px) {
        .dashboard-content {
            grid-template-columns: 1fr;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="main-content">
    <header class="header">
        <h1>Withdrawal</h1>
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
        <div class="withdrawal-container">
            <div class="withdrawal-header">
                <div class="withdrawal-title">Withdraw Funds</div>
                <div class="balance-info">
                    <div class="balance-label">Available Balance</div>
                    <div class="balance-amount"><?php echo $currency . number_format($acct_balance, 2); ?></div>
                </div>
            </div>
            
            <!-- Withdrawal Methods -->
            <div class="withdrawal-methods">
                <div class="method-card" onclick="selectMethod('bank')">
                    <div class="method-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="method-title">Bank Transfer</div>
                    <div class="method-description">Transfer to your bank account</div>
                </div>
                
                <div class="method-card" onclick="selectMethod('wire')">
                    <div class="method-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="method-title">Wire Transfer</div>
                    <div class="method-description">International wire transfer</div>
                </div>
                
                <div class="method-card" onclick="selectMethod('check')">
                    <div class="method-icon">
                        <i class="fas fa-money-check"></i>
                    </div>
                    <div class="method-title">Check Request</div>
                    <div class="method-description">Request a physical check</div>
                </div>
            </div>
            
            <!-- Bank Transfer Form -->
            <form class="withdrawal-form" id="bank-form" method="POST">
                <h3 style="color: #104042; margin-bottom: 20px;">Bank Transfer Details</h3>
                
                <div class="form-group">
                    <label for="bank-amount">Withdrawal Amount</label>
                    <input type="number" id="bank-amount" name="amount" placeholder="Enter amount" required step="0.01" min="1">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="bank-name">Bank Name</label>
                        <input type="text" id="bank-name" name="bank_name" placeholder="Enter bank name" required>
                    </div>
                    <div class="form-group">
                        <label for="account-number">Account Number</label>
                        <input type="text" id="account-number" name="account_number" placeholder="Enter account number" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="routing-number">Routing Number</label>
                        <input type="text" id="routing-number" name="routing_number" placeholder="Enter routing number" required>
                    </div>
                    <div class="form-group">
                        <label for="account-type">Account Type</label>
                        <select id="account-type" name="account_type" required>
                            <option value="">Select Account Type</option>
                            <option value="checking">Checking</option>
                            <option value="savings">Savings</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="purpose">Purpose of Withdrawal</label>
                    <textarea id="purpose" name="purpose" rows="3" placeholder="Describe the purpose of this withdrawal" required></textarea>
                </div>
                
                <button type="submit" class="btn-withdraw" name="bank_withdraw">Process Bank Transfer</button>
            </form>
            
            <!-- Wire Transfer Form -->
            <form class="withdrawal-form" id="wire-form" method="POST">
                <h3 style="color: #104042; margin-bottom: 20px;">Wire Transfer Details</h3>
                
                <div class="form-group">
                    <label for="wire-amount">Withdrawal Amount</label>
                    <input type="number" id="wire-amount" name="amount" placeholder="Enter amount" required step="0.01" min="1">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="beneficiary-name">Beneficiary Name</label>
                        <input type="text" id="beneficiary-name" name="beneficiary_name" placeholder="Enter beneficiary name" required>
                    </div>
                    <div class="form-group">
                        <label for="beneficiary-account">Beneficiary Account</label>
                        <input type="text" id="beneficiary-account" name="beneficiary_account" placeholder="Enter account number" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="swift-code">SWIFT Code</label>
                        <input type="text" id="swift-code" name="swift_code" placeholder="Enter SWIFT code" required>
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" placeholder="Enter country" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="wire-purpose">Purpose of Transfer</label>
                    <textarea id="wire-purpose" name="purpose" rows="3" placeholder="Describe the purpose of this wire transfer" required></textarea>
                </div>
                
                <button type="submit" class="btn-withdraw" name="wire_withdraw">Process Wire Transfer</button>
            </form>
            
            <!-- Check Request Form -->
            <form class="withdrawal-form" id="check-form" method="POST">
                <h3 style="color: #104042; margin-bottom: 20px;">Check Request Details</h3>
                
                <div class="form-group">
                    <label for="check-amount">Check Amount</label>
                    <input type="number" id="check-amount" name="amount" placeholder="Enter amount" required step="0.01" min="1">
                </div>
                
                <div class="form-group">
                    <label for="payee-name">Payee Name</label>
                    <input type="text" id="payee-name" name="payee_name" value="<?php echo $fullName; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="mailing-address">Mailing Address</label>
                    <textarea id="mailing-address" name="mailing_address" rows="3" placeholder="Enter complete mailing address" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="check-purpose">Purpose</label>
                    <textarea id="check-purpose" name="purpose" rows="2" placeholder="Purpose of check request" required></textarea>
                </div>
                
                <button type="submit" class="btn-withdraw" name="check_withdraw">Request Check</button>
            </form>
        </div>
        
        <div class="sidebar-info">
            <div class="info-title">Withdrawal Information</div>
            
            <div class="info-item">
                <div class="info-label">Daily Limit</div>
                <div class="info-value"><?php echo $currency; ?>50,000</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Processing Time</div>
                <div class="info-value">1-3 Business Days</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Wire Transfer Fee</div>
                <div class="info-value"><?php echo $currency; ?>25.00</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Check Fee</div>
                <div class="info-value"><?php echo $currency; ?>10.00</div>
            </div>
            
            <div style="margin-top: 20px; padding: 15px; background: rgba(175, 255, 26, 0.1); border-radius: 8px;">
                <div style="font-size: 14px; color: #104042; line-height: 1.5;">
                    <strong>Important:</strong> All withdrawals are subject to verification and may require additional documentation for amounts over $10,000.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectMethod(method) {
    // Remove active class from all method cards
    document.querySelectorAll('.method-card').forEach(card => {
        card.classList.remove('active');
    });
    
    // Hide all forms
    document.querySelectorAll('.withdrawal-form').forEach(form => {
        form.classList.remove('active');
    });
    
    // Activate selected method
    event.target.closest('.method-card').classList.add('active');
    document.getElementById(method + '-form').classList.add('active');
}
</script>

<?php
include_once("layouts/footer.php");
?>
