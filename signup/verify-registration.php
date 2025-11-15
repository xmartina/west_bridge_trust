<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
if (!defined('INCLUDE_PATH')) {
    define('INCLUDE_PATH', dirname(__DIR__) . '/include/');
}
require INCLUDE_PATH . 'vendor/autoload.php';
require_once('../include/config.php');
require_once('../include/smtp.php');
require_once('../include/userClass.php');

$pageName  = "Registration";
require_once './layout/header.php';

// Initialize email classes
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
    $acct_pin = 1234;

    // Handle profile photo upload
    $profileImage = 'null';
    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $uploadDir = '../assets/profile/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $fileName = $_FILES['profile_pic']['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        if(in_array($fileExt, $allowedTypes)) {
            $newFileName = $acct_username . time() . '.' . $fileExt;
            $uploadPath = $uploadDir . $newFileName;
            
            if(move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadPath)) {
                $profileImage = $newFileName;
            }
        }
    }

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
                'acct_pin' => $acct_pin, // Default PIN
                'ssn' => 0, // You may change this to your requirement
                'frontID' => 'null', // Default values
                'backID' => 'null', // Default values
                'image' => $profileImage
            ]);

            if ($reg) {
                // Extract the main domain (strip subdomain)
                $parts = explode('.', WEB_URL);

                // If it’s a 3-part domain like sub.domain.com
                if (count($parts) === 3) {
                    $mainDomain = $parts[1] . '.' . $parts[2];  // westbridgetrust.com
                } else {
                    $mainDomain = WEB_URL;  // fallback if it's already a main domain
                }
                // Now assign the final contact URL
                $contactUrl = 'https://' . $mainDomain . '/contact.php';
                // Email Sending logic
                $fullName = "$firstname $lastname";
                $APP_NAME = $pageTitle;
                $APP_URL = WEB_URL;
                $message = $sendMail->regMsgUser($fullName, $acct_no, $acct_status, $acct_email, $acct_phone, $acct_type, $acct_pin, $APP_NAME, $APP_URL, $contactUrl);

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - <?php echo WEB_TITLE; ?></title>
    <link rel="icon" type="image/x-icon" href="/assets/images/logo/favicon.png" />
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
            padding: 20px 0;
        }

        .signup-container {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            max-width: 1400px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(16, 64, 66, 0.1);
            border-radius: 16px;
            overflow: hidden;
            background-color: #fff;
            min-height: 90vh;
        }

        .signup-banner {
            background: linear-gradient(135deg, #104042 0%, #0a2a2b 100%);
            padding: 50px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .signup-banner::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background-color: rgba(175, 255, 26, 0.1);
        }

        .signup-banner::after {
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

        .progress-steps {
            display: flex;
            justify-content: center;
            align-items: center;
            list-style: none;
            margin: 0 0 30px 0;
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
            background: rgba(175, 255, 26, 0.3);
            margin: 0 15px;
            transition: all 0.3s ease;
        }

        .progress-steps li.active:not(:last-child)::after,
        .progress-steps li.completed:not(:last-child)::after {
            background: #afff1a;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(175, 255, 26, 0.3);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .progress-steps li.active .step-circle {
            background: #afff1a;
            color: #104042;
            transform: scale(1.1);
        }

        .progress-steps li.completed .step-circle {
            background: #afff1a;
            color: #104042;
        }

        .signup-form-container {
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            overflow-y: auto;
            max-height: 90vh;
        }

        .form-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #104042;
            text-align: center;
        }

        .form-subtitle {
            font-size: 14px;
            color: rgba(16, 64, 66, 0.7);
            margin-bottom: 30px;
            text-align: center;
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
            font-size: 20px;
            font-weight: 600;
            color: #104042;
            margin-bottom: 25px;
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
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #104042;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(16, 64, 66, 0.2);
            border-radius: 8px;
            background-color: rgba(16, 64, 66, 0.02);
            color: #104042;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #104042;
            box-shadow: 0 0 0 2px rgba(16, 64, 66, 0.1);
            outline: none;
        }

        .password-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-input-wrapper input {
            width: 100%;
            padding-right: 45px;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: rgba(16, 64, 66, 0.5);
            z-index: 10;
        }

        .password-toggle:hover {
            color: #104042;
        }

        .form-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(16, 64, 66, 0.1);
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: #104042;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #165e61;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: rgba(16, 64, 66, 0.1);
            color: #104042;
            border: 1px solid rgba(16, 64, 66, 0.2);
        }

        .btn-secondary:hover {
            background-color: rgba(16, 64, 66, 0.15);
        }

        .btn-success {
            background-color: #afff1a;
            color: #104042;
            font-weight: 700;
        }

        .btn-success:hover {
            background-color: #9ee619;
            transform: translateY(-1px);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: rgba(16, 64, 66, 0.7);
            font-size: 14px;
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
            padding: 30px 20px;
            border: 2px dashed rgba(16, 64, 66, 0.2);
            border-radius: 8px;
            background: rgba(16, 64, 66, 0.02);
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            color: rgba(16, 64, 66, 0.7);
        }

        .file-upload-label:hover {
            border-color: #104042;
            background: rgba(16, 64, 66, 0.05);
            color: #104042;
        }

        .upload-icon {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .section-title {
            color: #104042;
            margin: 25px 0 15px 0;
            font-size: 1.1rem;
            font-weight: 600;
            border-bottom: 1px solid rgba(16, 64, 66, 0.1);
            padding-bottom: 5px;
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
            box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.1);
        }

        @media (max-width: 992px) {
            .signup-container {
                grid-template-columns: 1fr;
                max-width: 600px;
                margin: 20px;
            }

            .signup-banner {
                display: none;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .signup-form-container {
                padding: 30px 20px;
            }
        }

        @media (max-width: 576px) {
            .signup-form-container {
                padding: 20px 15px;
            }

            .step-circle {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }

            .progress-steps li:not(:last-child)::after {
                width: 30px;
                margin: 0 10px;
            }
        }

        /* added style */
        .btn-login {
            background-color: #104042;
            color: #fff;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            width: 145px;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <!-- Signup Banner -->
        <div class="signup-banner">
            <div class="banner-content">
                <div class="logo"><?php echo WEB_TITLE; ?></div>
                <h1 class="banner-title text-white">Join <?php echo WEB_TITLE; ?></h1>
                <p class="banner-text text-white">Create your secure banking account and start managing your finances with confidence. Join thousands of satisfied customers worldwide.</p>

                <!-- Progress Steps in Banner -->
                <ul class="progress-steps">
                    <li class="active" data-step="1">
                        <div class="step-circle">1</div>
                    </li>
                    <li data-step="2">
                        <div class="step-circle">2</div>
                    </li>
                </ul>

                <ul class="banner-features">
                    <li><i class="fas fa-shield-alt"></i> Secure and encrypted registration</li>
                    <li><i class="fas fa-user-check"></i> Quick account verification</li>
                    <li><i class="fas fa-mobile-alt"></i> Access from any device</li>
                    <li><i class="fas fa-headset"></i> 24/7 customer support</li>
                </ul>
            </div>
        </div>

        <!-- Signup Form -->
        <div class="signup-form-container">
            <h2 class="form-title">Create Your Account</h2>
            <p class="form-subtitle">Fill in your details to get started</p>

            <form method="POST" enctype="multipart/form-data" id="registrationForm">
                <!-- Step 1: Personal Information -->
                <div class="form-step active" data-step="1">
                    <h3 class="step-title">Personal Information</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstname">First Name *</label>
                            <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" required>
                            <div class="error-message">Please enter your first name</div>
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name *</label>
                            <input type="text" id="lastname" name="lastname" placeholder="Enter your last name" required>
                            <div class="error-message">Please enter your last name</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="acct_currency">Currency Type *</label>
                            <select id="acct_currency" name="acct_currency" required>
                                <option value="">Select Currency Type</option>
                                <option value="USD">USD</option>
                                <option value="Euro">Euro</option>
                                <option value="Yuan">Yuan</option>
                                <option value="GBP">GBP</option>
                                <option value="CAD">CAD</option>
                            </select>
                            <div class="error-message">Please select a currency</div>
                        </div>
                        <div class="form-group">
                            <label for="acct_type">Account Type *</label>
                            <select id="acct_type" name="acct_type" required>
                                <option value="">Select Account Type</option>
                                <option value="Savings">Savings Account</option>
                                <option value="Current">Current Account</option>
                            </select>
                            <div class="error-message">Please select an account type</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="occupation">Occupation</label>
                            <input type="text" id="occupation" name="occupation" placeholder="Enter your occupation">
                        </div>
                        <div class="form-group">
                            <label for="country">Country *</label>
                            <select id="country" name="country" required>
                                <option value="">Select Country</option>
                                <option value="United States of America">United States of America</option>
                                <option value="Canada">Canada</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="Australia">Australia</option>
                                <option value="Germany">Germany</option>
                                <option value="France">France</option>
                                <option value="Japan">Japan</option>
                                <option value="China">China</option>
                                <option value="India">India</option>
                                <option value="Brazil">Brazil</option>
                                <!-- Add more countries as needed -->
                            </select>
                            <div class="error-message">Please select your country</div>
                        </div>
                    </div>

                    <h4 class="section-title">Residential Address</h4>

                    <div class="form-group full-width">
                        <label for="address">Street Address *</label>
                        <input type="text" id="address" name="address" placeholder="Enter your street address" required>
                        <div class="error-message">Please enter your street address</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="suite">Apt/Suite/Unit</label>
                            <input type="text" id="suite" name="suite" placeholder="Apt/Suite/Unit">
                        </div>
                        <div class="form-group">
                            <label for="city">City *</label>
                            <input type="text" id="city" name="city" placeholder="Enter your city" required>
                            <div class="error-message">Please enter your city</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="state">State *</label>
                            <input type="text" id="state" name="state" placeholder="Enter your state" required>
                            <div class="error-message">Please enter your state</div>
                        </div>
                        <div class="form-group">
                            <label for="zipcode">Zip Code *</label>
                            <input type="text" id="zipcode" name="zipcode" placeholder="Enter your zip code" required>
                            <div class="error-message">Please enter your zip code</div>
                        </div>
                    </div>

                    <input name="radio-name" type="hidden" value="male">

                    <div class="form-navigation">
                        <div class="login-link">
                            Already have an account? <a href="../login.php">Login here</a>
                        </div>
                        <button type="button" class="btn btn-login next-step">
                            Next Step <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Login Credentials & Profile Photo -->
                <div class="form-step" data-step="2">
                    <h3 class="step-title">Create Your Login & Profile</h3>

                    <div class="form-group full-width">
                        <label for="acct_email">Email Address *</label>
                        <input type="email" id="acct_email" name="acct_email" placeholder="Enter your email address" required>
                        <div class="error-message">Please enter a valid email address</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phoneNumber">Phone Number *</label>
                            <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Enter your phone number" required>
                            <div class="error-message">Please enter your phone number</div>
                        </div>
                        <div class="form-group">
                            <label for="username">Username *</label>
                            <input type="text" id="username" name="username" placeholder="Choose a username" required>
                            <div class="error-message">Please enter a username</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="acct_password">Password *</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="acct_password" name="acct_password" placeholder="Create a password" required>
                                <div class="password-toggle" onclick="togglePassword('acct_password')">
                                    <i class="far fa-eye"></i>
                                </div>
                            </div>
                            <div class="error-message">Please enter a password</div>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password *</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                                <div class="password-toggle" onclick="togglePassword('confirmPassword')">
                                    <i class="far fa-eye"></i>
                                </div>
                            </div>
                            <div class="error-message">Passwords do not match</div>
                        </div>
                    </div>

                    <h4 class="section-title">Profile Photo</h4>
                    
                    <div class="form-group full-width">
                        <label>Profile Image *</label>
                        <div class="file-upload">
                            <input type="file" name="profile_pic" accept="image/*" id="profile_pic" required>
                            <div class="file-upload-label" id="file-upload-label">
                                <div>
                                    <div class="upload-icon"><i class="fas fa-camera"></i></div>
                                    <div><strong>Click to upload</strong> or drag and drop</div>
                                    <div style="font-size: 0.9rem; margin-top: 5px; opacity: 0.7;">PNG, JPG, JPEG up to 10MB</div>
                                </div>
                            </div>
                        </div>
                        <div class="error-message">Please upload a profile image</div>
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="btn btn-login prev-step">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <button type="submit" name="regSubmit" class="btn btn-success">
                            <i class="fas fa-check"></i> Create Account
                        </button>
                    </div>
                </div>

                
            </form>
        </div>
    </div>

    <script>
        // Form wizard functionality
        let currentStep = 1;
        const totalSteps = 2;

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

            // File upload preview
            const fileInput = document.getElementById('profile_pic');
            const fileLabel = document.getElementById('file-upload-label');
            if (fileInput && fileLabel) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validate file type
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                        if (!allowedTypes.includes(file.type)) {
                            alert('Please select a valid image file (JPG, JPEG, PNG, GIF)');
                            this.value = '';
                            return;
                        }
                        
                        // Validate file size (10MB)
                        if (file.size > 10 * 1024 * 1024) {
                            alert('File size must be less than 10MB');
                            this.value = '';
                            return;
                        }
                        
                        fileLabel.innerHTML = `
                            <div>
                                <div class="upload-icon"><i class="fas fa-check-circle" style="color: #afff1a;"></i></div>
                                <div><strong>${file.name}</strong></div>
                                <div style="font-size: 0.9rem; margin-top: 5px; color: #afff1a;">File selected successfully</div>
                            </div>
                        `;
                        fileLabel.style.borderColor = '#afff1a';
                        fileLabel.style.background = 'rgba(175, 255, 26, 0.1)';
                    }
                });
            }

            // Form validation on input
            document.querySelectorAll('input, select').forEach(input => {
                input.addEventListener('input', function() {
                    if (this.classList.contains('error') && this.value.trim()) {
                        this.classList.remove('error');
                        const errorElement = this.parentElement.querySelector('.error-message');
                        if (errorElement) errorElement.style.display = 'none';
                    }
                });
            });
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
            });

            const currentStepElement = document.querySelector(`.form-step[data-step="${step}"]`);
            if (currentStepElement) {
                currentStepElement.classList.add('active');
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
            });

            return isValid;
        }

        // Password toggle functionality
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = input.parentElement.querySelector('.password-toggle i');

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

<?php
require_once './layout/footer.php';
?>