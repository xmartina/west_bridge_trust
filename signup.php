<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - <?php echo $pageTitle; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #104042;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .registration-container {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            max-width: 1200px;
            box-shadow: 0 10px 30px rgba(16, 64, 66, 0.1);
            border-radius: 16px;
            overflow: hidden;
            background-color: #fff;
            margin: 40px 20px;
        }
        
        .registration-banner {
            background: linear-gradient(135deg, #104042 0%, #0a2a2b 100%);
            padding: 50px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .registration-banner::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background-color: rgba(175, 255, 26, 0.1);
        }
        
        .registration-banner::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background-color: rgba(255, 210, 0, 0.1);
        }
        
        .banner-content {
            position: relative;
            z-index: 1;
        }
        
        .logo {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 40px;
            color: #afff1a;
        }
        
        .banner-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .banner-text {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .banner-features {
            list-style: none;
        }
        
        .banner-features li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .banner-features i {
            color: #afff1a;
            margin-right: 10px;
            font-size: 18px;
        }
        
        .progress-container {
            padding: 30px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        
        .progress-steps {
            display: flex;
            justify-content: center;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .progress-steps li {
            display: flex;
            align-items: center;
            position: relative;
        }
        
        .progress-steps li:not(:last-child)::after {
            content: '';
            width: 60px;
            height: 2px;
            background: #e9ecef;
            margin: 0 15px;
            transition: all 0.3s ease;
        }
        
        .progress-steps li.active:not(:last-child)::after,
        .progress-steps li.completed:not(:last-child)::after {
            background: #afff1a;
        }
        
        .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }
        
        .progress-steps li.active .step-circle {
            background: #104042;
            color: white;
            transform: scale(1.1);
        }
        
        .progress-steps li.completed .step-circle {
            background: #afff1a;
            color: #104042;
        }
        
        .form-container {
            padding: 40px;
        }
        
        .form-step {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .form-step.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .step-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #104042;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            position: relative;
            margin-bottom: 25px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #ffffff;
            outline: none;
        }
        
        .form-control:focus {
            border-color: #104042;
            box-shadow: 0 0 0 3px rgba(16, 64, 66, 0.1);
        }
        
        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            transform: translateY(-35px) scale(0.85);
            color: #104042;
            background: white;
            padding: 0 8px;
        }
        
        .form-label {
            position: absolute;
            left: 20px;
            top: 15px;
            color: #6c757d;
            font-size: 1rem;
            transition: all 0.3s ease;
            pointer-events: none;
            background: transparent;
        }
        
        select.form-control {
            cursor: pointer;
        }
        
        select.form-control + .form-label {
            transform: translateY(-35px) scale(0.85);
            color: #104042;
            background: white;
            padding: 0 8px;
        }
        
        .password-field {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #104042;
        }
        
        .form-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e9ecef;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #104042 0%, #0d3335 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 64, 66, 0.3);
        }
        
        .btn-secondary {
            background: #f8f9fa;
            color: #6c757d;
            border: 2px solid #e9ecef;
        }
        
        .btn-secondary:hover {
            background: #e9ecef;
            color: #495057;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #afff1a 0%, #9ee619 100%);
            color: #104042;
            font-weight: 700;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(175, 255, 26, 0.4);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }
        
        .login-link a {
            color: #104042;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            color: #afff1a;
        }
        
        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            border: 2px dashed #e9ecef;
            border-radius: 12px;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            color: #6c757d;
        }
        
        .file-upload-label:hover {
            border-color: #104042;
            background: rgba(16, 64, 66, 0.05);
            color: #104042;
        }
        
        .upload-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        @media (max-width: 992px) {
            .registration-container {
                grid-template-columns: 1fr;
                max-width: 600px;
            }
            
            .registration-banner {
                display: none;
            }
        }
        
        @media (max-width: 576px) {
            .form-container {
                padding: 30px 20px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .progress-steps li:not(:last-child)::after {
                width: 30px;
                margin: 0 10px;
            }
            
            .step-circle {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }
        
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
            display: none;
        }
        
        .form-control.error {
            border-color: #dc3545;
        }
        
        .form-control.error:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }
    </style>
</head>
<?php
ob_start();
require_once("./include/loginFunction.php");
require_once('./session.php');
$pageName = "Registration";

$sql = "SELECT * FROM settings WHERE id ='1'";
$stmt = $conn->prepare($sql);
$stmt->execute();

$page = $stmt->fetch(PDO::FETCH_ASSOC);

$title = $page['url_name'];
$pageTitle = $title;
$BANK_PHONE = $page['url_tel'];

$title = new pageTitle();
$email_message = new message();
$sendMail = new emailMessage();

if(isset($_POST['regSubmit'])){
    // Initialize variables
    $acct_no = "9909" . substr(number_format(time() * rand(), 0, '', ''), 0, 6);
    $acct_type = isset($_POST['acct_type']) ? $_POST['acct_type'] : null;
    $acct_currency = isset($_POST['acct_currency']) ? $_POST['acct_currency'] : null;
    $firstname = isset($_POST['firstname']) ? $_POST['firstname'] : null;
    $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : null;
    $acct_occupation = isset($_POST['occupation']) ? $_POST['occupation'] : null;
    $acct_status = "hold"; // Default value
    $country = isset($_POST['country']) ? $_POST['country'] : null;
    $acct_gender = isset($_POST['radio-name']) ? $_POST['radio-name'] : null;
    $address = isset($_POST['address']) ? $_POST['address'] : null;
    $suite = isset($_POST['suite']) ? $_POST['suite'] : null;
    $city = isset($_POST['city']) ? $_POST['city'] : null;
    $state = isset($_POST['state']) ? $_POST['state'] : null;
    $zipcode = isset($_POST['zipcode']) ? $_POST['zipcode'] : null;
    $acct_address = "$address $suite $city $state $zipcode";
    $acct_email = isset($_POST['acct_email']) ? $_POST['acct_email'] : null;
    $acct_phone = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : null;
    $acct_username = isset($_POST['username']) ? $_POST['username'] : null;
    $acct_password = isset($_POST['acct_password']) ? $_POST['acct_password'] : null;
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : null;
    $acct_pin = isset($_POST['acct_pin']) ? $_POST['acct_pin'] : null;

    // Set default values for SSN
    $ssn = 0;
    $confirm_ssn = 0;

    if($acct_password !== $confirmPassword){
        notify_alert('Password not matched','danger','3000','close');
    } elseif ($ssn !== $confirm_ssn){
        notify_alert('SSN / TIN not matched','danger','3000','close');
    } else {
        // Prepare and execute SQL query (use prepared statements to prevent SQL injection)
        $usersVerified = "SELECT * FROM users WHERE acct_email=:acct_email or acct_username=:acct_username";
        $stmt = $conn->prepare($usersVerified);
        $stmt->execute([
            'acct_email' => $acct_email,
            'acct_username' => $acct_username
        ]);

        if ($stmt->rowCount() > 0) {
            notify_alert('Email or Username Already Exist', 'danger', '3000', 'close');
        } else {
            // INSERT INTO DATABASE (use prepared statements to prevent SQL injection)
            $registered = "INSERT INTO users (acct_username, firstname, lastname, acct_email, acct_password, acct_no, acct_type, acct_gender, acct_currency, acct_status, acct_phone, acct_occupation, country, state, acct_address, acct_dob, acct_pin, ssn, frontID, backID, image) VALUES(:acct_username, :firstname, :lastname, :acct_email, :acct_password, :acct_no, :acct_type, :acct_gender, :acct_currency, :acct_status, :acct_phone, :acct_occupation, :country, :state, :acct_address, :acct_dob, :acct_pin, :ssn, :frontID, :backID, :image)";
            $reg = $conn->prepare($registered);
            $reg->execute([
                'acct_username' => $acct_username,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'acct_email' => $acct_email,
                'acct_password' => password_hash($acct_password, PASSWORD_BCRYPT),
                'acct_no' => $acct_no,
                'acct_type' => $acct_type,
                'acct_gender' => $acct_gender,
                'acct_currency' => $acct_currency,
                'acct_status' => $acct_status,
                'acct_phone' => $acct_phone,
                'acct_occupation' => $acct_occupation,
                'country' => $country,
                'state' => $state,
                'acct_address' => $acct_address,
                'acct_dob' => 0, // You may change this to your requirement
                'acct_pin' => $acct_pin,
                'ssn' => 0, // You may change this to your requirement
                'frontID' => 'null', // Default values
                'backID' => 'null', // Default values
                'image' => 'null' // Default values
            ]);

            if ($reg) {
                // Email Sending logic
                $fullName = "$firstname $lastname";
                $APP_NAME = $pageTitle;
                $APP_URL = WEB_URL;
                $message = $sendMail->regMsgUser($fullName, $acct_no, $acct_status, $acct_email, $acct_phone, $acct_type, $acct_pin, $APP_NAME, $APP_URL);

                // User Email
                $subject = "Register - $APP_NAME";
                $email_message->send_mail($acct_email, $message, $subject);

                // Admin Email
                $subject = "User Register - $APP_NAME";
                $email_message->send_mail(WEB_EMAIL, $message, $subject);

                toast_alert('success', 'Account Created Successfully, Kindly proceed to login', 'Approved');
            } else {
                toast_alert('error', 'Sorry something went wrong');
            }
        }
    }
}
?> 
<body>
    <div class="registration-container">
        <!-- Registration Banner -->
        <div class="registration-banner">
            <div class="banner-content">
                <div class="logo"><?php echo $pageTitle; ?></div>
                <h1 class="banner-title">Create Your Bank Account</h1>
                <p class="banner-text">Join thousands of satisfied customers worldwide and enjoy secure banking services with our comprehensive dashboard.</p>
                <ul class="banner-features">
                    <li><i class="fas fa-shield-alt"></i> Secure and encrypted connection</li>
                    <li><i class="fas fa-bolt"></i> Fast and reliable transactions</li>
                    <li><i class="fas fa-mobile-alt"></i> Access from any device</li>
                    <li><i class="fas fa-headset"></i> 24/7 customer support</li>
                    <li><i class="fas fa-globe"></i> International wire transfers</li>
                </ul>
            </div>
        </div>
        
        <!-- Registration Form -->
        <div class="form-container">
            <div class="progress-container">
                <ul class="progress-steps">
                    <li class="active" data-step="1">
                        <div class="step-circle">1</div>
                    </li>
                    <li data-step="2">
                        <div class="step-circle">2</div>
                    </li>
                    <li data-step="3">
                        <div class="step-circle">3</div>
                    </li>
                </ul>
            </div>
            
            <form action="" method="post" enctype="multipart/form-data" id="registrationForm">
                <!-- Step 1: Personal Information -->
                <div class="form-step active" data-step="1">
                    <h2 class="step-title">Personal Information</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" class="form-control" name="firstname" placeholder=" " required>
                            <label class="form-label">First Name *</label>
                            <div class="error-message">Please enter your first name</div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="lastname" placeholder=" " required>
                            <label class="form-label">Last Name *</label>
                            <div class="error-message">Please enter your last name</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <select class="form-control" name="acct_currency" required>
                                <option value="">Select Currency Type</option>
                                <option value="USD">USD</option>
                                <option value="Euro">Euro</option>
                                <option value="Yuan">Yuan</option>
                                <option value="GBP">GBP</option>
                                <option value="CAD">CAD</option>
                            </select>
                            <label class="form-label">Currency Type *</label>
                            <div class="error-message">Please select a currency</div>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="acct_type" required>
                                <option value="">Select Account Type</option>
                                <option value="Savings">Savings Account</option>
                                <option value="Current">Current Account</option>
                            </select>
                            <label class="form-label">Account Type *</label>
                            <div class="error-message">Please select an account type</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" class="form-control" name="occupation" placeholder=" ">
                            <label class="form-label">Occupation</label>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="country" required>
                                <option value="">Select Country</option>
                                <option value="United States">United States</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="Canada">Canada</option>
                                <option value="Australia">Australia</option>
                                <option value="Germany">Germany</option>
                                <option value="France">France</option>
                                <option value="Japan">Japan</option>
                                <option value="China">China</option>
                                <option value="India">India</option>
                                <!-- More countries would be added here -->
                            </select>
                            <label class="form-label">Country *</label>
                            <div class="error-message">Please select your country</div>
                        </div>
                    </div>

                    <h3 style="color: #104042; margin: 30px 0 20px 0; font-size: 1.3rem;">Residential Address</h3>
                    
                    <div class="form-group full-width">
                        <input type="text" class="form-control" name="address" placeholder=" " required>
                        <label class="form-label">Street Address *</label>
                        <div class="error-message">Please enter your street address</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" class="form-control" name="suite" placeholder=" ">
                            <label class="form-label">Apt/Suite/Unit</label>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="city" placeholder=" " required>
                            <label class="form-label">City *</label>
                            <div class="error-message">Please enter your city</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" class="form-control" name="state" placeholder=" " required>
                            <label class="form-label">State *</label>
                            <div class="error-message">Please enter your state</div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="zipcode" placeholder=" " required>
                            <label class="form-label">Zip Code *</label>
                            <div class="error-message">Please enter your zip code</div>
                        </div>
                    </div>

                    <input name="radio-name" type="hidden" value="male">

                    <div class="form-navigation">
                        <div class="login-link">
                            Already have an account? <a href="login.php">Login here</a>
                        </div>
                        <button type="button" class="btn btn-primary next-step">
                            Next Step <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 2: Login Credentials -->
                <div class="form-step" data-step="2">
                    <h2 class="step-title">Create Your Login</h2>
                    
                    <div class="form-group full-width">
                        <input type="email" class="form-control" name="acct_email" placeholder=" " required>
                        <label class="form-label">Email Address *</label>
                        <div class="error-message">Please enter a valid email address</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <input type="tel" class="form-control" name="phoneNumber" placeholder=" " required>
                            <label class="form-label">Phone Number *</label>
                            <div class="error-message">Please enter your phone number</div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="username" placeholder=" " required>
                            <label class="form-label">Username *</label>
                            <div class="error-message">Please enter a username</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group password-field">
                            <input type="password" class="form-control" name="acct_password" placeholder=" " required>
                            <label class="form-label">Password *</label>
                            <div class="password-toggle" onclick="togglePassword(this)">
                                <i class="far fa-eye"></i>
                            </div>
                            <div class="error-message">Please enter a password</div>
                            <div class="password-requirements">Must be at least 8 characters with letters, numbers, and symbols</div>
                        </div>
                        <div class="form-group password-field">
                            <input type="password" class="form-control" name="confirmPassword" placeholder=" " required>
                            <label class="form-label">Confirm Password *</label>
                            <div class="password-toggle" onclick="togglePassword(this)">
                                <i class="far fa-eye"></i>
                            </div>
                            <div class="error-message">Passwords do not match</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group password-field">
                            <input type="password" class="form-control" name="acct_pin" placeholder=" " required>
                            <label class="form-label">Account PIN (4 digits) *</label>
                            <div class="password-toggle" onclick="togglePassword(this)">
                                <i class="far fa-eye"></i>
                            </div>
                            <div class="error-message">Please enter a 4-digit PIN</div>
                            <div class="password-requirements">Your PIN will be used for transaction verification</div>
                        </div>
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="btn btn-secondary prev-step">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <button type="button" class="btn btn-primary next-step">
                            Next Step <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 3: Terms and Conditions -->
                <div class="form-step" data-step="3">
                    <h2 class="step-title">Complete Registration</h2>
                    
                    <div class="form-group full-width" style="text-align: center; padding: 20px; background-color: #f8f9fa; border-radius: 12px; margin-bottom: 30px;">
                        <i class="fas fa-shield-alt" style="font-size: 3rem; color: #104042; margin-bottom: 15px;"></i>
                        <h3 style="margin-bottom: 10px; color: #104042;">Security Verification</h3>
                        <p style="margin-bottom: 0; color: #6c757d;">Your account will be reviewed by our team for security purposes.</p>
                    </div>
                    
                    <div class="form-group full-width">
                        <div style="padding: 20px; border: 1px solid #e9ecef; border-radius: 12px; height: 200px; overflow-y: auto; margin-bottom: 20px;">
                            <h4 style="margin-bottom: 10px;">Terms and Conditions</h4>
                            <p style="font-size: 0.9rem; color: #6c757d; line-height: 1.6;">
                                By creating an account with <?php echo $pageTitle; ?>, you agree to our Terms of Service and Privacy Policy. 
                                You consent to receive communications from us electronically. We will protect your personal information and 
                                only use it in accordance with our privacy practices. You are responsible for maintaining the confidentiality 
                                of your account credentials and for all activities that occur under your account.
                            </p>
                        </div>
                        
                        <div style="display: flex; align-items: flex-start; margin-bottom: 20px;">
                            <input type="checkbox" id="terms" required style="margin-right: 10px; margin-top: 5px;">
                            <label for="terms" style="font-size: 0.9rem; color: #6c757d;">
                                I have read and agree to the Terms of Service and Privacy Policy, and I consent to the processing of my personal data as described.
                            </label>
                        </div>
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="btn btn-secondary prev-step">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <button type="submit" name="regSubmit" class="btn btn-success">
                            Create Account <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Form wizard functionality
        let currentStep = 1;
        const totalSteps = 3;

        // Initialize on document ready
        document.addEventListener('DOMContentLoaded', function() {
            updateProgressSteps();
            showStep(currentStep);
            
            // Next step buttons
            document.querySelectorAll('.next-step').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (validateStep(currentStep) && currentStep < totalSteps) {
                        currentStep++;
                        showStep(currentStep);
                        window.scrollTo(0, 0);
                    }
                });
            });

            // Previous step buttons
            document.querySelectorAll('.prev-step').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (currentStep > 1) {
                        currentStep--;
                        showStep(currentStep);
                        window.scrollTo(0, 0);
                    }
                });
            });

            // Form validation on input
            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('input', function() {
                    if (this.classList.contains('error') && this.value.trim()) {
                        this.classList.remove('error');
                        const errorElement = this.parentElement.querySelector('.error-message');
                        if (errorElement) errorElement.style.display = 'none';
                    }
                });
            });

            // Form submission validation
            const regForm = document.getElementById('registrationForm');
            if (regForm) {
                regForm.addEventListener('submit', function(e) {
                    if (!validateStep(currentStep)) {
                        e.preventDefault();
                    }
                });
            }
        });

        function updateProgressSteps() {
            const steps = document.querySelectorAll('.progress-steps li');
            steps.forEach((step, index) => {
                const stepNumber = index + 1;
                if (stepNumber < currentStep) {
                    step.classList.add('completed');
                    step.classList.remove('active');
                } else if (stepNumber === currentStep) {
                    step.classList.add('active');
                    step.classList.remove('completed');
                } else {
                    step.classList.remove('active', 'completed');
                }
            });
        }

        function showStep(step) {
            const formSteps = document.querySelectorAll('.form-step');
            formSteps.forEach(s => {
                s.classList.remove('active');
                s.style.display = 'none';
            });
            
            const currentStepElement = document.querySelector(`.form-step[data-step="${step}"]`);
            if (currentStepElement) {
                currentStepElement.classList.add('active');
                currentStepElement.style.display = 'block';
            }
            
            updateProgressSteps();
        }

        function validateStep(step) {
            const currentStepElement = document.querySelector(`[data-step="${step}"]`);
            if (!currentStepElement) return true;
            
            const requiredFields = currentStepElement.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                const errorElement = field.parentElement.querySelector('.error-message');
                
                if (!field.value.trim()) {
                    field.classList.add('error');
                    if (errorElement) errorElement.style.display = 'block';
                    isValid = false;
                } else {
                    field.classList.remove('error');
                    if (errorElement) errorElement.style.display = 'none';
                }

                // Special validation for email
                if (field.type === 'email' && field.value.trim()) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(field.value)) {
                        field.classList.add('error');
                        if (errorElement) {
                            errorElement.textContent = 'Please enter a valid email address';
                            errorElement.style.display = 'block';
                        }
                        isValid = false;
                    }
                }

                // Password confirmation validation
                if (field.name === 'confirmPassword') {
                    const password = document.querySelector('[name="acct_password"]').value;
                    if (field.value !== password) {
                        field.classList.add('error');
                        if (errorElement) {
                            errorElement.textContent = 'Passwords do not match';
                            errorElement.style.display = 'block';
                        }
                        isValid = false;
                    }
                }
                
                // PIN validation
                if (field.name === 'acct_pin') {
                    const pinRegex = /^\d{4}$/;
                    if (!pinRegex.test(field.value)) {
                        field.classList.add('error');
                        if (errorElement) {
                            errorElement.textContent = 'PIN must be exactly 4 digits';
                            errorElement.style.display = 'block';
                        }
                        isValid = false;
                    }
                }
                
                // Terms checkbox validation
                if (field.type === 'checkbox' && !field.checked) {
                    if (errorElement) errorElement.style.display = 'block';
                    isValid = false;
                }
            });

            return isValid;
        }

        // Password toggle functionality
        function togglePassword(element) {
            const input = element.previousElementSibling.previousElementSibling;
            const icon = element.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
    
    <!--tidio support-->
    <?php support_plugin() ?>
</body>
</html> 