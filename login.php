<?php
ob_start();
require_once("./include/loginFunction.php");
require_once ('./session.php');
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

require_once("include/userClass.php");
require_once("include/loginFunction.php");


if(@$_SESSION['acct_no']){
    header("Location:./user/dashboard.php");
}
  
 if(isset($_POST["sub"]))  
 {  
      $_SESSION["name"] = $_POST["name"];  
      $_SESSION['last_login_timestamp'] = time();  
      header("location:index.php");       
 }



if(isset($_POST['login'])){
    $acct_no = inputValidation($_POST['acct_no']);
    $acct_password = inputValidation($_POST['acct_password']);



    $log = "SELECT * FROM users WHERE acct_no =:acct_no";
    $stmt = $conn->prepare($log);
    $stmt->execute([
        'acct_no'=>$acct_no
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if($stmt->rowCount() === 0){
        toast_alert("error","Invalid login details");
    }else{
        $validPassword = password_verify($acct_password, $user['acct_password']);

        if ($validPassword === false){
           toast_alert("error","Invalid login details");
        }else{
            
                //IP LOGIN DETAILS
            
            $device = $_SERVER['HTTP_USER_AGENT'];
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            $nowDate = date('Y-m-d H:i:s');
            $user_id = $user['id'];
          
            
            $stmt = $conn->prepare("INSERT INTO audit_logs (user_id,device,ipAddress,datenow) VALUES(:user_id,:device,:ipAddress,:datenow)");
            $stmt->execute([
                'user_id'=>$user_id,
                'device'=>$device,
                'ipAddress'=>$ipAddress,
                'datenow'=>$nowDate
                ]);

                if (true) {

                   
                        $full_name = $user['firstname']. " ". $user['lastname'];
                        // $APP_URL = APP_URL;
                        $APP_NAME = WEB_TITLE;
                        $APP_URL = WEB_URL;
                        $user_email = $user['acct_email'];

                        $message = $sendMail->LoginMsg($full_name, $device, $ipAddress, $nowDate, $APP_NAME, $APP_URL, $BANK_PHONE);

                        // User Email
                        $subject = "Login Notification". "-". $APP_NAME;
                        $email_message->send_mail($user_email, $message, $subject);
                        // Admin Email
                        $subject = "User Login Notification". "-". $APP_NAME;
                        $email_message->send_mail(WEB_EMAIL, $message, $subject);
                    }
                    
                     if (true) {
                        $_SESSION['login'] = $user['acct_no'];
                        header("Location:./pin.php");
                        exit;
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
    <title>Login - <?php echo WEB_TITLE; ?></title>
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
        
        .login-container {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            max-width: 1000px;
            box-shadow: 0 10px 30px rgba(16, 64, 66, 0.1);
            border-radius: 16px;
            overflow: hidden;
            background-color: #fff;
        }
        
        .login-banner {
            background: linear-gradient(135deg, #104042 0%, #0a2a2b 100%);
            padding: 50px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-banner::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background-color: rgba(175, 255, 26, 0.1);
        }
        
        .login-banner::after {
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
        
        .login-form-container {
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .form-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #104042;
        }
        
        .form-subtitle {
            font-size: 14px;
            color: rgba(16, 64, 66, 0.7);
            margin-bottom: 30px;
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
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(16, 64, 66, 0.2);
            border-radius: 8px;
            background-color: rgba(16, 64, 66, 0.02);
            color: #104042;
            font-size: 15px;
        }
        
        .form-group input:focus {
            border-color: #104042;
            box-shadow: 0 0 0 2px rgba(16, 64, 66, 0.1);
            outline: none;
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 8px;
        }
        
        .forgot-password {
            color: #104042;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        
        .forgot-password:hover {
            color: #afff1a;
        }
        
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
            width: 100%;
            margin-bottom: 20px;
        }
        
        .btn-login:hover {
            background-color: #165e61;
        }
        
        .signup-link {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: rgba(16, 64, 66, 0.7);
        }
        
        .signup-link a {
            color: #104042;
            font-weight: 600;
            text-decoration: none;
        }
        
        .signup-link a:hover {
            color: #afff1a;
        }
        
        @media (max-width: 992px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 500px;
                margin: 20px;
            }
            
            .login-banner {
                display: none;
            }
        }
        
        @media (max-width: 576px) {
            .login-form-container {
                padding: 30px 20px;
            }
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 45px;
            cursor: pointer;
            color: rgba(16, 64, 66, 0.5);
        }
        
        .password-toggle:hover {
            color: #104042;
        }
        
        .form-group {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Login Banner -->
        <div class="login-banner">
            <div class="banner-content">
                <div class="logo"><?php echo WEB_TITLE; ?></div>
                <h1 class="banner-title">Welcome to <?php echo WEB_TITLE; ?></h1>
                <p class="banner-text">Access your accounts, manage transactions, and enjoy secure banking services with our comprehensive dashboard.</p>
                <ul class="banner-features">
                    <li><i class="fas fa-shield-alt"></i> Secure and encrypted connection</li>
                    <li><i class="fas fa-bolt"></i> Fast and reliable transactions</li>
                    <li><i class="fas fa-mobile-alt"></i> Access from any device</li>
                    <li><i class="fas fa-headset"></i> 24/7 customer support</li>
                </ul>
            </div>
        </div>
        
        <!-- Login Form -->
        <div class="login-form-container">
            <h2 class="form-title">Login to your account</h2>
            <p class="form-subtitle">Enter your credentials to access your account</p>
            
            <form method="POST">
                <div class="form-group">
                    <label for="acct_no">Account ID</label>
                    <input type="number" id="acct_no" name="acct_no" placeholder="Enter your account ID" required>
                </div>
                
                <div class="form-group">
                    <label for="acct_password">Password</label>
                    <input type="password" id="acct_password" name="acct_password" placeholder="Enter your password" required>
                    <div class="password-toggle" id="toggle-password">
                        <i class="far fa-eye"></i>
                    </div>
                </div>
                
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
                
                <button type="submit" name="login" class="btn-login">Login</button>
                
                <div class="signup-link">
                    Don't have an account? <a href="signup.php">Sign Up</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordField = document.getElementById('acct_password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            
            // Change the eye icon
            const icon = this.querySelector('i');
            if (type === 'password') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    </script>

<!--tidio support-->
<?php support_plugin() ?>
</body>
</html>
<?php
include_once("layout/footer.php");
?>

