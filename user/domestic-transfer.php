<?php
$pageName = "Domestic Transfer";
include_once("layouts/header.php");
require_once("./userPinfunction.php");
//require_once("../include/config.php");
//require_once("../include/loginFunction.php");
//require_once("../include/userFunction.php");
//require_once('../include/userClass.php');
?>

<style>
    /* Page specific styles */
    .transfer-form-container {
        background-color: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
        border-left: 4px solid #afff1a;
        margin-bottom: 30px;
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
    
    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid rgba(16, 64, 66, 0.2);
        border-radius: 8px;
        background-color: rgba(16, 64, 66, 0.02);
        color: #104042;
        font-size: 15px;
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }
    
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
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
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .btn-submit:hover {
        background-color: #165e61;
    }
    
    .transfer-info {
        margin-top: 30px;
        padding: 20px;
        background-color: rgba(175, 255, 26, 0.1);
        border-radius: 8px;
        border-left: 4px solid #afff1a;
    }
    
    .transfer-info h3 {
        color: #104042;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    
    .transfer-info h3 i {
        margin-right: 10px;
    }
    
    .transfer-info ul {
        list-style: none;
        padding-left: 30px;
    }
    
    .transfer-info ul li {
        margin-bottom: 10px;
        position: relative;
        color: #104042;
    }
    
    .transfer-info ul li:before {
        content: '•';
        position: absolute;
        left: -15px;
        color: #afff1a;
        font-weight: bold;
    }
    
    .alert-custom {
        background-color: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
        border-left: 4px solid #d32f2f;
        margin-bottom: 30px;
    }
    
    .alert-icon {
        width: 40px;
        height: 40px;
        background-color: rgba(211, 47, 47, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d32f2f;
        font-size: 20px;
        margin-right: 15px;
    }
    
    .alert-content {
        display: flex;
        align-items: center;
    }
    
    .alert-text {
        flex-grow: 1;
    }
    
    .alert-text strong {
        color: #d32f2f;
        font-weight: 600;
    }
    
    .alert-btn {
        margin-top: 15px;
    }
    
    .btn-contact {
        background-color: transparent;
        color: #104042;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s ease;
        border: 1px solid #104042;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-contact:hover {
        background-color: rgba(16, 64, 66, 0.05);
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }
</style>

<div class="main-content">
    <header class="header">
        <h1>Domestic Transfer</h1>
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
        <?php if($acct_stat === 'active'): ?>
            <?php if($page['transfer'] == '1'): ?>
                <?php if($row['transfer'] == '1'): ?>
                    <!-- Transfer Form -->
                    <div class="transfer-form-container">
                        <div class="form-title">
                            <i class="fas fa-share"></i>
                            Domestic Transfer Details
                        </div>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" id="amount" name="amount" placeholder="Enter amount" required>
                                </div>
                                <div class="form-group">
                                    <label for="acct_name">Beneficiary Account Name</label>
                                    <input type="text" id="acct_name" name="acct_name" placeholder="Beneficiary Account Name" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" id="bank_name" name="bank_name" placeholder="Bank Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="acct_number">Beneficiary Account No</label>
                                    <input type="number" id="acct_number" name="acct_number" placeholder="Beneficiary Account Number" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="acct_type">Select Account Type</label>
                                    <select name="acct_type" id="acct_type" required>
                                        <option value="">Select Account Type</option>
                                        <option value="Savings">Savings Account</option>
                                        <option value="Current">Current Account</option>
                                        <option value="Checking">Checking Account</option>
                                        <option value="Fixed Deposit">Fixed Deposit</option>
                                        <option value="Non Resident">Non Resident Account</option>
                                        <option value="Online Banking">Online Banking</option>
                                        <option value="Domicilary Account">Domicilary Account</option>
                                        <option value="Joint Account">Joint Account</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="acct_remarks">Narration/Purpose</label>
                                    <textarea id="acct_remarks" name="acct_remarks" placeholder="Fund Description"></textarea>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn-submit" name="domestic-transfer">
                                <i class="fas fa-paper-plane"></i> Process Transfer
                            </button>
                        </form>
                    </div>

                    <!-- Transfer Information -->
                    <div class="transfer-info">
                        <h3><i class="fas fa-info-circle"></i> Important Information</h3>
                        <ul>
                            <li>Domestic transfers are typically processed within 1 business day.</li>
                            <li>There is no fee for domestic transfers between accounts at our bank.</li>
                            <li>Transfers to other banks may incur a small fee depending on your account type.</li>
                            <li>For security reasons, new beneficiaries may require additional verification.</li>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="alert-custom">
                        <div class="alert-content">
                            <div class="alert-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="alert-text">
                                <strong>Warning! </strong>You cannot make <b>Domestic Transfer</b>. Please contact support.
                            </div>
                        </div>
                        <div class="alert-btn">
                            <a href="mailto:<?=$page['url_email'] ?>" class="btn-contact">Contact Us</a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert-custom">
                    <div class="alert-content">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="alert-text">
                            <strong>Warning! </strong>You cannot make <b>Transfer</b>. Please contact support.
                        </div>
                    </div>
                    <div class="alert-btn">
                        <a href="mailto:<?=$page['url_email'] ?>" class="btn-contact">Contact Us</a>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert-custom">
                <div class="alert-content">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-text">
                        <strong>Warning! </strong>Account on <b>hold</b>. Please contact support.
                    </div>
                </div>
                <div class="alert-btn">
                    <a href="mailto:<?=$page['url_email'] ?>" class="btn-contact">Contact Us</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include_once("layouts/footer.php");
?>