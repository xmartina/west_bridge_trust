<?php
$pageName = "Pin";
require_once("layouts/header.php");
include("./userPinfunction.php");
?>

<!-- Add the verification card styles -->
<style>
    :root {
        --primary-color: #104042;
        --secondary-color-1: #afff1a;
        --secondary-color-2: #FFD200;
        --white: #ffffff;
        --light-gray: #f8f9fa;
        --dark-text: #333333;
    }
    
    /* Verification Card Styles */
    .verification-container {
        max-width: 600px;
        margin: 60px auto;
    }
    
    .verification-card {
        background-color: var(--white);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(16, 64, 66, 0.1);
        overflow: hidden;
        position: relative;
    }
    
    .verification-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #0d3335 100%);
        color: var(--white);
        padding: 30px;
        text-align: center;
        position: relative;
    }
    
    .verification-icon {
        width: 80px;
        height: 80px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: var(--secondary-color-2);
        font-size: 36px;
        border: 2px solid var(--secondary-color-2);
    }
    
    .verification-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .verification-subtitle {
        font-size: 16px;
        opacity: 0.9;
        max-width: 500px;
        margin: 0 auto;
    }
    
    .verification-body {
        padding: 30px;
    }
    
    .verification-message {
        text-align: center;
        margin-bottom: 30px;
        color: var(--primary-color);
        font-weight: 500;
        font-size: 16px;
        line-height: 1.6;
    }
    
    .verification-message strong {
        color: var(--primary-color);
        font-weight: 700;
    }
    
    .verification-form {
        margin-top: 20px;
    }
    
    .input-group {
        position: relative;
        margin-bottom: 25px;
    }
    
    .input-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--primary-color);
        font-weight: 600;
        font-size: 14px;
    }
    
    .input-group input {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        letter-spacing: 4px;
        text-align: center;
        color: var(--primary-color);
        transition: all 0.3s ease;
        background-color: var(--white);
    }
    
    .input-group input:focus {
        border-color: var(--secondary-color-2);
        box-shadow: 0 0 0 3px rgba(255, 210, 0, 0.2);
        outline: none;
    }
    
    .input-group input::placeholder {
        color: #ccc;
        letter-spacing: 2px;
    }
    
    .verification-info {
        background-color: rgba(16, 64, 66, 0.05);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        border-left: 4px solid var(--secondary-color-2);
    }
    
    .verification-info p {
        color: var(--primary-color);
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 10px;
    }
    
    .verification-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-top: 30px;
    }
    
    .btn {
        padding: 15px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        border: none;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        color: var(--white);
    }
    
    .btn-primary:hover {
        background-color: #0d3335;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 64, 66, 0.2);
    }
    
    .btn-outline {
        background-color: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }
    
    .btn-outline:hover {
        background-color: rgba(16, 64, 66, 0.05);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 64, 66, 0.1);
    }
    
    @media (max-width: 576px) {
        .verification-container {
            margin: 20px auto;
        }
        
        .verification-header {
            padding: 20px;
        }
        
        .verification-body {
            padding: 20px;
        }
        
        .verification-actions {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="main-content">
    <header class="header">
        <h1>Verification Required</h1>
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
    
    <div class="verification-container">
        <div class="verification-card">
            <?php
            if($_SESSION['wire-transfer']){
            ?>
            <div class="verification-header">
                <div class="verification-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 class="verification-title">OTP VERIFICATION</h1>
                <p class="verification-subtitle">Please enter the OTP code to complete your wire transfer</p>
            </div>
            
            <div class="verification-body">
                <div class="verification-message">
                    Hello, <strong><?= ucwords($fullName)?></strong>! Please enter your One time pin (OTP) code to complete this transaction successfully.
                </div>
                
                <div class="verification-info">
                    <p><strong>Note:</strong> This is a security measure to protect your account and ensure the safety of your transaction.</p>
                    <p>The code will expire in 10 minutes. If you didn't receive a code, please contact support.</p>
                </div>
                
                <form class="verification-form" action="" method="post">
                    <div class="input-group">
                        <label for="pin-code">Enter OTP Code</label>
                        <input type="number" id="pin-code" name="pin" placeholder="• • • • • •" maxlength="6" autocomplete="off" required>
                    </div>
                    
                    <!-- Hidden inputs for transaction details -->
                    <input type="text" name="type" value="wire_transfer" hidden>
                    <input type="number" value="<?= $temp_trans['amount'] ?>" name="amount" hidden>
                    <input type="text" value="<?= $temp_trans['bank_name'] ?>" name="bank_name" hidden>
                    <input type="text" value="<?= $temp_trans['acct_name_id']?>" name="acct_name" hidden>
                    <input type="number" value="<?= $temp_trans['acct_number'] ?>" name="acct_number" hidden>
                    <input type="text" value="<?= $temp_trans['acct_type'] ?>" name="acct_type" hidden>
                    <input type="text" value="<?= $temp_trans['acct_country'] ?>" name="acct_country" hidden>
                    <input type="text" value="<?= $temp_trans['acct_swift']?>" name="acct_swift" hidden>
                    <input type="number" value="<?= $temp_trans['acct_routing'] ?>" name="acct_routing" hidden>
                    <input type="text" value="<?= $temp_trans['acct_remarks'] ?>" name="acct_remarks" hidden>
                    <input type="number" value="<?= $temp_trans['acct_id'] ?>" name="account_id" hidden>
                    <input type="number" value="<?= $row['acct_no'] ?>" name="acct_no" id="acct_no" hidden>
                    
                    <div class="verification-actions">
                        <button type="button" class="btn btn-outline" onclick="window.history.back()">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="submit-pin">Submit</button>
                    </div>
                </form>
            </div>
            <?php
            } elseif($_SESSION['dom-transfer']){
            ?>
            <div class="verification-header">
                <div class="verification-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 class="verification-title">OTP VERIFICATION</h1>
                <p class="verification-subtitle">Please enter the OTP code to complete your domestic transfer</p>
            </div>
            
            <div class="verification-body">
                <div class="verification-message">
                    Hello, <strong><?= ucwords($fullName)?></strong>! Please enter your One time pin (OTP) code to complete this transaction successfully.
                </div>
                
                <div class="verification-info">
                    <p><strong>Note:</strong> This is a security measure to protect your account and ensure the safety of your transaction.</p>
                    <p>The code will expire in 10 minutes. If you didn't receive a code, please contact support.</p>
                </div>
                
                <form class="verification-form" action="" method="post">
                    <div class="input-group">
                        <label for="pin-code-dom">Enter OTP Code</label>
                        <input type="number" id="pin-code-dom" name="pin" placeholder="• • • • • •" maxlength="6" autocomplete="off" required>
                    </div>
                    
                    <!-- Hidden inputs for transaction details -->
                    <input type="text" name="type" value="dom_tranfer" hidden>
                    <input type="number" value="<?= $temp_trans['amount'] ?>" name="amount" hidden id="amount">
                    <input type="text" value="<?= $temp_trans['bank_name'] ?>" name="bank_name" hidden id="bank_name">
                    <input type="text" value="<?= $temp_trans['acct_name_id']?>" name="acct_name" hidden id="acct_name">
                    <input type="number" value="<?= $temp_trans['acct_number'] ?>" name="acct_number" hidden id="acct_number">
                    <input type="text" value="<?= $temp_trans['acct_type'] ?>" name="acct_type" hidden id="acct_type">
                    <input type="text" value="<?= $temp_trans['trans_type'] ?>" name="trans_type" hidden id="trans_type">
                    <input type="text" value="<?= $temp_trans['acct_remarks'] ?>" name="acct_remarks" hidden id="acct_remarks">
                    <input type="number" value="<?= $temp_trans['acct_id'] ?>" name="account_id" id="account_id" hidden>
                    <input type="number" value="<?= $row['acct_no'] ?>" name="acct_no" id="acct_no" hidden>
                    
                    <div class="verification-actions">
                        <button type="button" class="btn btn-outline" onclick="window.history.back()">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="submit-pin">Submit</button>
                    </div>
                </form>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Allow only numbers in the verification code input
        const verificationInputs = document.querySelectorAll('input[type="number"]');
        if (verificationInputs) {
            verificationInputs.forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (!/^\d$/.test(e.key)) {
                        e.preventDefault();
                    }
                });
            });
        }
    });
</script>

<?php
include_once("layouts/footer.php");
?>
