<?php
$pageName = "Loan";
include_once("layouts/header.php");
$acct_id = userDetails('id');

if(isset($_POST['loan-submit'])){
    $amount = $_POST['amount'];
    $loan_remarks = $_POST['loan_remarks'];
    $reference_id = uniqid();
    
   if(empty($amount)){
       notify_alert('Amount Required','info','3000','Close');
   }elseif($amount <= 0){
        notify_alert('Invalid Amount','info','3000','Close');
    }elseif(empty($loan_remarks)){
        notify_alert('Loan Description Required !','info','3000','Close');
    }else {

        $sql2 = "INSERT INTO loan (loan_reference_id,acct_id,amount,loan_remarks) VALUES (:loan_reference_id,:acct_id,:amount,:loan_remarks)";
        $stmt = $conn->prepare($sql2);
        $stmt->execute([
            'loan_reference_id' => $reference_id,
            'acct_id' => $acct_id,
            'amount' => $amount,
            'loan_remarks' => $loan_remarks
        ]);

        //EMAIL SENDING
        $email = $acct_email;
        $APP_NAME = $pageTitle;
        $APP_URL = APP_URL;
        $BANK_PHONE = $BANK_PHONE;
        $message = $sendMail->LoanMsg($currency,$amount,$loan_remarks,$fullName,$APP_NAME,$APP_URL);

        $subject = "Loan Status - $APP_NAME";
        $email_message->send_mail($email, $message, $subject);
        // Admin Email
        $subject = "Loan Status - $APP_NAME";
        $email_message->send_mail(WEB_EMAIL, $message, $subject);

        if (true) {
            toast_alert('success', 'Your Loan have been submitted, Kindly wait for Approval', 'Success');
        } else {
            toast_alert('error', 'Sorry An Error occurred, Try Again !');
        }
    }
}
?>

<style>
    /* Page specific styles */
    .loan-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 992px) {
        .loan-container {
            grid-template-columns: 1fr;
        }
    }
    
    .loan-section {
        background-color: #fff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
        margin-bottom: 30px;
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
    
    .loan-application {
        background-color: #fff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
    }
    
    .loan-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .loan-option {
        border: 1px solid rgba(16, 64, 66, 0.1);
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .loan-option:hover {
        border-color: #104042;
        background-color: rgba(16, 64, 66, 0.02);
    }
    
    .loan-option.active {
        border-color: #104042;
        background-color: rgba(16, 64, 66, 0.05);
    }
    
    .option-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgba(16, 64, 66, 0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        color: #104042;
        font-size: 18px;
    }
    
    .option-title {
        font-weight: 600;
        color: #104042;
        margin-bottom: 5px;
        font-size: 14px;
    }
    
    .option-description {
        font-size: 12px;
        color: rgba(16, 64, 66, 0.7);
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
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
    
    .btn-apply {
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
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .btn-apply:hover {
        background-color: #165e61;
    }
    
    .loan-calculator {
        margin-top: 25px;
        padding-top: 25px;
        border-top: 1px solid rgba(16, 64, 66, 0.1);
    }
    
    .calculator-result {
        background-color: rgba(16, 64, 66, 0.02);
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
    }
    
    .result-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .result-row:last-child {
        margin-bottom: 0;
        padding-top: 10px;
        border-top: 1px solid rgba(16, 64, 66, 0.1);
    }
    
    .result-label {
        color: rgba(16, 64, 66, 0.7);
    }
    
    .result-value {
        font-weight: 600;
        color: #104042;
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
</style>

<div class="main-content">
    <header class="header">
        <h1>Loan & Mortgages</h1>
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
            <!-- Loan Container -->
            <div class="loan-container">
                <!-- Left Column - Apply for Loan -->
                <div class="left-column">
                    <div class="loan-application">
                        <div class="section-header">
                            <div class="section-title">Apply for a New Loan</div>
                        </div>
                        
                        <div class="loan-options">
                            <div class="loan-option active">
                                <div class="option-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="option-title">Personal Loan</div>
                                <div class="option-description">For personal expenses</div>
                            </div>
                            <div class="loan-option">
                                <div class="option-icon">
                                    <i class="fas fa-car"></i>
                                </div>
                                <div class="option-title">Auto Loan</div>
                                <div class="option-description">For vehicle purchase</div>
                            </div>
                            <div class="loan-option">
                                <div class="option-icon">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="option-title">Mortgage</div>
                                <div class="option-description">For home purchase</div>
                            </div>
                            <div class="loan-option">
                                <div class="option-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="option-title">Business Loan</div>
                                <div class="option-description">For business needs</div>
                            </div>
                        </div>
                        
                        <form method="POST">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="amount">Loan Amount</label>
                                    <input type="number" id="amount" name="amount" value="<?= $_POST['amount']?>" placeholder="Enter amount" required>
                                </div>
                                <div class="form-group">
                                    <label for="loan-term">Loan Term</label>
                                    <select id="loan-term">
                                        <option value="12">12 months</option>
                                        <option value="24">24 months</option>
                                        <option value="36" selected>36 months</option>
                                        <option value="48">48 months</option>
                                        <option value="60">60 months</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="loan-purpose">Purpose of Loan</label>
                                <select id="loan-purpose">
                                    <option value="debt-consolidation">Debt Consolidation</option>
                                    <option value="home-improvement">Home Improvement</option>
                                    <option value="major-purchase">Major Purchase</option>
                                    <option value="medical">Medical Expenses</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="loan_remarks">Loan Description</label>
                                <textarea id="loan_remarks" name="loan_remarks" placeholder="Describe your loan purpose in detail"><?=$_POST['loan_remarks']?></textarea>
                            </div>
                            
                            <button type="submit" name="loan-submit" class="btn-apply">
                                <i class="fas fa-paper-plane"></i> Submit Application
                            </button>
                        </form>
                        
                        <div class="loan-calculator">
                            <div class="section-header">
                                <div class="section-title">Loan Calculator</div>
                            </div>
                            
                            <div class="calculator-result">
                                <div class="result-row">
                                    <div class="result-label">Loan Amount</div>
                                    <div class="result-value"><?= $currency ?>10,000.00</div>
                                </div>
                                <div class="result-row">
                                    <div class="result-label">Interest Rate</div>
                                    <div class="result-value">5.25%</div>
                                </div>
                                <div class="result-row">
                                    <div class="result-label">Loan Term</div>
                                    <div class="result-value">36 months</div>
                                </div>
                                <div class="result-row">
                                    <div class="result-label">Monthly Payment</div>
                                    <div class="result-value"><?= $currency ?>301.80</div>
                                </div>
                                <div class="result-row">
                                    <div class="result-label">Total Interest</div>
                                    <div class="result-value"><?= $currency ?>864.80</div>
                                </div>
                                <div class="result-row">
                                    <div class="result-label">Total Payment</div>
                                    <div class="result-value"><?= $currency ?>10,864.80</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Mortgage Rates -->
                <div class="right-column">
                    <div class="loan-section">
                        <div class="section-header">
                            <div class="section-title">Current Mortgage Rates</div>
                        </div>
                        
                        <div class="mortgage-rates">
                            <div class="rate-item">
                                <div class="rate-name">30-Year Fixed <span class="featured-rate">Best</span></div>
                                <div class="rate-value">6.25%</div>
                            </div>
                            <div class="rate-item">
                                <div class="rate-name">15-Year Fixed</div>
                                <div class="rate-value">5.75%</div>
                            </div>
                            <div class="rate-item">
                                <div class="rate-name">5/1 ARM</div>
                                <div class="rate-value">5.50%</div>
                            </div>
                            <div class="rate-item">
                                <div class="rate-name">7/1 ARM</div>
                                <div class="rate-value">5.65%</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="loan-section">
                        <div class="section-header">
                            <div class="section-title">Personal Loan Rates</div>
                        </div>
                        
                        <div class="mortgage-rates">
                            <div class="rate-item">
                                <div class="rate-name">Excellent Credit <span class="featured-rate">Best</span></div>
                                <div class="rate-value">5.25%</div>
                            </div>
                            <div class="rate-item">
                                <div class="rate-name">Good Credit</div>
                                <div class="rate-value">7.50%</div>
                            </div>
                            <div class="rate-item">
                                <div class="rate-name">Fair Credit</div>
                                <div class="rate-value">12.75%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                    <a href="mailto:<?=$url_email?>" class="btn-contact">Contact Us</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include_once("layouts/footer.php");
?>
