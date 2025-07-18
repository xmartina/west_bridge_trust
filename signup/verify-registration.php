<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
const rootDir = '/home/multistream6/domains/dashboard.westbridgetrust.com/public_html/';
require '../include/vendor/autoload.php';
require rootDir . 'include/vendor/autoload.php';
$pageName  = "Registration";
require_once './layout/header.php';

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Bank Account - Registration</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffffff;
            color: #333;
            line-height: 1.6;
        }

        .registration-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .registration-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(16, 64, 66, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            position: relative;
        }

        .registration-header {
            background: linear-gradient(135deg, #104042 0%, #0d3335 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .registration-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(175, 255, 26, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .registration-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .registration-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
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

        @media (max-width: 768px) {
            .registration-header h1 {
                font-size: 2rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .form-container {
                padding: 30px 20px;
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
<body>
    <div class="registration-container">
        <div class="registration-card">
            <div class="registration-header">
                <h1>Create Your Bank Account</h1>
                <p>Join thousands of satisfied customers worldwide</p>
            </div>

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
                    <li data-step="4">
                        <div class="step-circle">✓</div>
                    </li>
                </ul>
            </div>

            <div class="form-container">
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
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Austria">Austria</option>
                                    <option value="Azerbaijan">Azerbaijan</option>
                                    <option value="Bahamas">Bahamas</option>
                                    <option value="Bahrain">Bahrain</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Belgium">Belgium</option>
                                    <option value="Belize">Belize</option>
                                    <option value="Benin">Benin</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bonaire">Bonaire</option>
                                    <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                    <option value="Brunei">Brunei</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Canary Islands">Canary Islands</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Channel Islands">Channel Islands</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos Island">Cocos Island</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote DIvoire">Cote DIvoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curacao">Curacao</option>
                                    <option value="Cyprus">Cyprus</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Denmark">Denmark</option>
                                    <option value="Djibouti">Djibouti</option>
                                    <option value="Dominica">Dominica</option>
                                    <option value="Dominican Republic">Dominican Republic</option>
                                    <option value="East Timor">East Timor</option>
                                    <option value="Ecuador">Ecuador</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="El Salvador">El Salvador</option>
                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                    <option value="Eritrea">Eritrea</option>
                                    <option value="Estonia">Estonia</option>
                                    <option value="Ethiopia">Ethiopia</option>
                                    <option value="Falkland Islands">Falkland Islands</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Ter">French Southern Ter</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Great Britain">Great Britain</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Hawaii">Hawaii</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="India">India</option>
                                    <option value="Iran">Iran</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea North">Korea North</option>
                                    <option value="Korea South">Korea South</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Laos">Laos</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libya">Libya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macau">Macau</option>
                                    <option value="Macedonia">Macedonia</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Midway Islands">Midway Islands</option>
                                    <option value="Moldova">Moldova</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Namibia">Namibia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherland Antilles">Netherland Antilles</option>
                                    <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                    <option value="Nevis">Nevis</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau Island">Palau Island</option>
                                    <option value="Palestine">Palestine</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Pitcairn Island">Pitcairn Island</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Republic of Montenegro">Republic of Montenegro</option>
                                    <option value="Republic of Serbia">Republic of Serbia</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russia">Russia</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="St Barthelemy">St Barthelemy</option>
                                    <option value="St Eustatius">St Eustatius</option>
                                    <option value="St Helena">St Helena</option>
                                    <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                    <option value="St Lucia">St Lucia</option>
                                    <option value="St Maarten">St Maarten</option>
                                    <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                    <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                    <option value="Saipan">Saipan</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="Samoa American">Samoa American</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syria">Syria</option>
                                    <option value="Tahiti">Tahiti</option>
                                    <option value="Taiwan">Taiwan</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United States of America">United States of America</option>
                                    <option value="Uruguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Vatican City State">Vatican City State</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                    <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                    <option value="Wake Island">Wake Island</option>
                                    <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zaire">Zaire</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
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
                                Already have an account? <a href="../login">Login here</a>
                            </div>
                            <button type="button" class="btn btn-primary next-step">
                                Next Step →
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
                                <div class="password-toggle" onclick="togglePassword(this)">👁️</div>
                                <div class="error-message">Please enter a password</div>
                            </div>
                            <div class="form-group password-field">
                                <input type="password" class="form-control" name="confirmPassword" placeholder=" " required>
                                <label class="form-label">Confirm Password *</label>
                                <div class="password-toggle" onclick="togglePassword(this)">👁️</div>
                                <div class="error-message">Passwords do not match</div>
                            </div>
                        </div>

                        <input type="hidden" name="acct_pin" value="1234">

                        <div class="form-navigation">
                            <button type="button" class="btn btn-secondary prev-step">
                                ← Previous
                            </button>
                            <button type="button" class="btn btn-primary next-step">
                                Next Step →
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Profile Image -->
                    <div class="form-step" data-step="3">
                        <h2 class="step-title">Upload Profile Image</h2>
                        
                        <div class="form-group full-width">
                            <div class="file-upload">
                                <input type="file" name="profile_pic" accept="image/*" required>
                                <div class="file-upload-label">
                                    <div>
                                        <div class="upload-icon">📷</div>
                                        <div><strong>Click to upload</strong> or drag and drop</div>
                                        <div style="font-size: 0.9rem; margin-top: 5px;">PNG, JPG, JPEG up to 10MB</div>
                                    </div>
                                </div>
                            </div>
                            <div class="error-message">Please upload a profile image</div>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="btn btn-secondary prev-step">
                                ← Previous
                            </button>
                            <button type="submit" name="regSubmit" class="btn btn-success">
                                Create Account ✓
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Form wizard functionality
        let currentStep = 1;
        const totalSteps = 3;

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
            document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
            document.querySelector(`[data-step="${step}"]`).classList.add('active');
            updateProgressSteps();
        }

        function validateStep(step) {
            const currentStepElement = document.querySelector(`[data-step="${step}"]`);
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

        // Next step buttons
        document.querySelectorAll('.next-step').forEach(btn => {
            btn.addEventListener('click', () => {
                if (validateStep(currentStep) && currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
        });

        // Previous step buttons
        document.querySelectorAll('.prev-step').forEach(btn => {
            btn.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        });

        // Password toggle functionality
        function togglePassword(element) {
            const input = element.previousElementSibling.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                element.textContent = '🙈';
            } else {
                input.type = 'password';
                element.textContent = '👁️';
            }
        }

        // File upload preview
        document.querySelector('input[type="file"]').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const label = this.nextElementSibling;
                label.innerHTML = `
                    <div>
                        <div class="upload-icon">✓</div>
                        <div><strong>${file.name}</strong></div>
                        <div style="font-size: 0.9rem; margin-top: 5px;">File selected successfully</div>
                    </div>
                `;
                label.style.borderColor = '#afff1a';
                label.style.background = 'rgba(175, 255, 26, 0.1)';
                label.style.color = '#104042';
            }
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
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            if (!validateStep(currentStep)) {
                e.preventDefault();
            }
        });

        // Initialize
        updateProgressSteps();
    </script>
</body>
</html>

<?php
require_once './layout/footer.php';
?>