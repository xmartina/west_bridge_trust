
<?php

$pageName = "Funding";
include("../include/vendor/autoload.php");
include_once("layouts/header.php");
//require_once("../include/config.php");
//require_once("../include/userFunction.php");
//require_once('../include/userClass.php');


// // virtual deposit
$sql7 = "SELECT * FROM v_bank WHERE id='48' ";
$stmt7 = $conn->prepare($sql7);
$stmt7->execute();

$deposit = $stmt7->fetch(PDO::FETCH_ASSOC);

$routine_no = $deposit['routine_no'];
$bank_name = $deposit['bank_name'];
$swift_code = $deposit['swift_code'];
$acct_no = $deposit['acct_no'];

$email = $row['acct_email'];

if(isset($_POST['deposit'])) {
    $amount = $_POST['amount'];
    $crypto_name = $_POST['crypto_name'];
    $wallet_address = $_POST['wallet_address'];
    $acct_id = userDetails('id');

    if (empty($amount) || empty($crypto_name) || empty($wallet_address)) {
        notify_alert('Fill Required Form', 'danger', '3000', 'Close');
    } else if(empty($_FILES['image'])){
        notify_alert('Upload Payment Screenshot', 'danger', '3000', 'Close');
    }else{

    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $name = $file['name'];

        $path = pathinfo($name, PATHINFO_EXTENSION);

        $allowed = array('jpg', 'png', 'jpeg');


        $folder = "../assets/deposit/";
        $n = time() . $name;

        $destination = $folder . $n;
    }
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        if ($acct_stat === 'hold') {
            toast_alert('error', 'Account on Hold Contact Support for more info');
        } elseif ($amount < 0) {
            toast_alert('error', 'Invalid amount entered');
        } elseif ($amount < $trans_limit_min) {
            toast_alert('error', 'Amount Less than Deposit Limit');
        } elseif ($amount > $trans_limit_max) {
            toast_alert('error', 'Amount greater than than Deposit Limit');
        } else {
            $reference_id = uniqid();
            $deposited = "INSERT INTO deposit (amount,user_id,wallet_address,crypto_id,image,refrence_id)VALUES(:amount,:user_id,:wallet_address,:crypto_id,:image,:refrence_id)";
            $stmt = $conn->prepare($deposited);

            $stmt->execute([
                'amount' => $amount,
                'user_id' => $acct_id,
                'wallet_address' => $wallet_address,
                'crypto_id' => $crypto_name,
                'image' => $n,
                'refrence_id' => $reference_id

            ]);
            if (true) {
                $sql = "SELECT d.*, c.crypto_name FROM deposit d INNER JOIN crypto_currency c ON d.crypto_id = c.id WHERE d.user_id =:acct_id ORDER BY d.d_id DESC LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    'acct_id' => $acct_id
                ]);

                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $trans_id = $result['refrence_id'];
                $crypto_name = $result['crypto_name'];


                $APP_NAME = $pageTitle;
                $message = $sendMail->depositMsg($currency, $amount, $crypto_name, $fullName, $trans_id, $APP_NAME);
                $subject = "[DEPOSIT] - $APP_NAME";
                $email_message->send_mail($email, $message, $subject);

                $subject = "Pending Deposit Notification - $APP_NAME";
                $email_message->send_mail(WEB_EMAIL, $message, $subject);
                


                if (true) {
                    toast_alert("success", "Your Deposit is been on Process", "Thanks!");

                } else {
                    toast_alert("error", "Sorry Something Went Wrong !");
                }
            }
        }
    }


    }
}

?>

<style>
/* Page specific styles */
.deposit-methods {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.deposit-method {
    background-color: #fff;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
    border-left: 4px solid #104042;
    cursor: pointer;
    transition: all 0.3s ease;
}

.deposit-method:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(16, 64, 66, 0.15);
}

.deposit-method.bank {
    border-left-color: #afff1a;
}

.deposit-method.card {
    border-left-color: #FFD200;
}

.deposit-method.crypto {
    border-left-color: #104042;
}

.method-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background-color: rgba(16, 64, 66, 0.05);
    color: #104042;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 28px;
    transition: all 0.3s ease;
}

.deposit-method:hover .method-icon {
    background-color: #104042;
    color: #afff1a;
}

.method-title {
    font-weight: 600;
    font-size: 18px;
    margin-bottom: 10px;
    color: #104042;
}

.method-description {
    font-size: 14px;
    color: rgba(16, 64, 66, 0.7);
    margin-bottom: 20px;
}

.deposit-form {
    background-color: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
    border-left: 4px solid #afff1a;
}

.form-title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 25px;
    color: #104042;
    display: flex;
    align-items: center;
}

.form-title i {
    margin-right: 10px;
    color: #afff1a;
    background-color: #104042;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
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

.form-group input, .form-group select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid rgba(16, 64, 66, 0.2);
    border-radius: 8px;
    background-color: rgba(16, 64, 66, 0.02);
    color: #104042;
    font-size: 15px;
}

.form-group input:focus, .form-group select:focus {
    border-color: #104042;
    box-shadow: 0 0 0 2px rgba(16, 64, 66, 0.1);
}

.btn-submit {
    background-color: #104042;
    color: #fff;
    padding: 14px 25px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    width: 100%;
}

.btn-submit:hover {
    background-color: #165e61;
}

.deposit-info {
    margin-top: 30px;
    padding: 20px;
    background-color: rgba(175, 255, 26, 0.1);
    border-radius: 8px;
    border-left: 4px solid #afff1a;
}

.deposit-info h3 {
    color: #104042;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
}

.deposit-info h3 i {
    margin-right: 10px;
}

.deposit-info ul {
    list-style: none;
    padding-left: 30px;
}

.deposit-info ul li {
    margin-bottom: 10px;
    position: relative;
    color: #104042;
}

.deposit-info ul li:before {
    content: '•';
    position: absolute;
    left: -15px;
    color: #afff1a;
    font-weight: bold;
}

.btn-quick-action {
    background-color: #104042;
    color: #fff;
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-quick-action:hover {
    background-color: #165e61;
}

.alert-custom {
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-danger {
    background-color: rgba(220, 53, 69, 0.1);
    border-left: 4px solid #dc3545;
    color: #721c24;
}

.input-group {
    display: flex;
    align-items: center;
}

.input-group .btn {
    margin-left: 10px;
}

.custom-file-container {
    border: 2px dashed rgba(16, 64, 66, 0.2);
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    margin-bottom: 20px;
}

.custom-file-container__custom-file__custom-file-input {
    width: 100%;
    padding: 10px;
}

@media (max-width: 992px) {
    .deposit-methods {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}

@media (max-width: 576px) {
    .deposit-methods {
        grid-template-columns: 1fr;
    }
}
</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-md-12">
                
                <?php if($acct_stat === 'active'): ?>
                
                <!-- Deposit Methods -->
                <div class="deposit-methods">
                    <div class="deposit-method crypto">
                        <div class="method-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="method-title">Crypto Deposit</div>
                        <div class="method-description">Deposit using cryptocurrency</div>
                        <button class="btn-quick-action">Available</button>
                    </div>
                    
                    <?php if($page['bank_deposit']==='1'): ?>
                    <div class="deposit-method bank">
                        <div class="method-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="method-title">Bank Transfer</div>
                        <div class="method-description">Transfer funds directly from your bank account</div>
                        <button class="btn-quick-action" data-toggle="modal" data-target="#exampleModal">Available</button>
                    </div>
                    <?php endif; ?>
                    
                    <div class="deposit-method card">
                        <div class="method-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="method-title">Credit/Debit Card</div>
                        <div class="method-description">Coming soon - deposit using your card</div>
                        <button class="btn-quick-action" disabled>Coming Soon</button>
                    </div>
                </div>

                <!-- Deposit Form -->
                <div class="deposit-form">
                    <div class="form-title">
                        <i class="fas fa-coins"></i>
                        Cryptocurrency Deposit
                    </div>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" name="amount" id="amount" placeholder="Enter amount" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="crypto_name">Crypto Type</label>
                                <select name="crypto_name" class="form-control" onchange="crypto_type(this.value)" required>
                                    <option value="">Select Cryptocurrency</option>
                                    <?php
                                    $sql = $conn->query("SELECT * FROM crypto_currency ORDER BY crypto_name");
                                    $data = [];
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
                            
                            <div class="form-group">
                                <label for="wallet_address">Wallet Address</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="wallet_address" id="wallet_address" 
                                           placeholder="Wallet Address" readonly required>
                                    <button type="button" class="btn btn-primary" data-clipboard-action="copy" 
                                            data-clipboard-target="#wallet_address">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Upload Payment Screenshot</label>
                            <div class="custom-file-container" data-upload-id="myFirstImage">
                                <label>Upload Screenshot <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">×</a></label>
                                <label class="custom-file-container__custom-file">
                                    <input type="file" class="custom-file-container__custom-file__custom-file-input" 
                                           name="image" accept="image/*" required>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                    <span class="custom-file-container__custom-file__custom-file-control"></span>
                                </label>
                                <div class="custom-file-container__image-preview"></div>
                            </div>
                        </div>

                        <button type="submit" name="deposit" class="btn-submit">
                            <i class="fas fa-arrow-up"></i> Submit Deposit
                        </button>
                    </form>
                </div>

                <!-- Deposit Information -->
                <div class="deposit-info">
                    <h3><i class="fas fa-info-circle"></i> Important Information</h3>
                    <ul>
                        <li>Cryptocurrency deposits are processed within 1-3 confirmations on the blockchain.</li>
                        <li>Please ensure you send the exact amount to the correct wallet address.</li>
                        <li>Upload a clear screenshot of your transaction for faster processing.</li>
                        <li>Minimum deposit amount varies by cryptocurrency.</li>
                        <li>For any issues, please contact our support team immediately.</li>
                    </ul>
                </div>

                <?php else: ?>
                <!-- Account Hold Alert -->
                <div class="alert-custom alert-danger">
                    <div class="media">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="media-body">
                            <div class="alert-text">
                                <strong>Warning!</strong> Account on <span class="text-uppercase"><b>hold</b></span> contact support.
                            </div>
                            <div class="alert-btn">
                                <a class="btn btn-primary" href="mailto:<?=$url_email?>">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>


<!-- Include Clipboard.js library -->
<script src="../assets/js/clipboard/clipboard.min.js"></script>

<script>
// Crypto type selection function
function crypto_type(value) {
    if (value) {
        // Find the wallet address for selected crypto
        const data = <?= json_encode($data) ?>;
        const selectedCrypto = data.find(item => item.id == value);
        if (selectedCrypto) {
            document.getElementById('wallet_address').value = selectedCrypto.wallet_address;
        }
    } else {
        document.getElementById('wallet_address').value = '';
    }
}

// Initialize clipboard functionality
document.addEventListener('DOMContentLoaded', function() {
    var clipboard = new ClipboardJS('[data-clipboard-action="copy"]');
    
    clipboard.on('success', function(e) {
        // Show success feedback
        const button = e.trigger;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i> Copied!';
        button.style.backgroundColor = '#28a745';
        
        setTimeout(function() {
            button.innerHTML = originalText;
            button.style.backgroundColor = '';
        }, 2000);
        
        e.clearSelection();
    });
    
    clipboard.on('error', function(e) {
        // Show error feedback
        const button = e.trigger;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-times"></i> Failed';
        button.style.backgroundColor = '#dc3545';
        
        setTimeout(function() {
            button.innerHTML = originalText;
            button.style.backgroundColor = '';
        }, 2000);
    });
});
</script>

<?php
include_once('layouts/footer.php')
?>
