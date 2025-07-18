<?php
include_once("layout/header.php");
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

<style>
:root {
    --primary-color: #104042;
    --secondary-color-1: #afff1a;
    --secondary-color-2: #FFD200;
    --white: #ffffff;
    --light-gray: #f8f9fa;
    --dark-text: #333333;
}

.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--light-gray);
    padding: 20px;
}

.login-card {
    background-color: var(--white);
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    width: 100%;
    max-width: 420px;
    padding: 40px;
    position: relative;
    overflow: hidden;
}

.login-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 6px;
    background: linear-gradient(90deg, var(--secondary-color-1), var(--secondary-color-2));
}

.login-header {
    text-align: center;
    margin-bottom: 30px;
}

.login-header h1 {
    color: var(--primary-color);
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
}

.login-header p {
    color: #666;
    font-size: 15px;
}

.login-form .form-group {
    margin-bottom: 20px;
    position: relative;
}

.login-form label {
    display: block;
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.login-form .form-control {
    width: 100%;
    padding: 14px 12px 14px 40px;
    border: 1px solid #e1e1e1;
    border-radius: 6px;
    font-size: 15px;
    transition: all 0.3s ease;
}

.login-form .form-control:focus {
    border-color: var(--secondary-color-2);
    box-shadow: 0 0 0 3px rgba(255, 210, 0, 0.15);
    outline: none;
}

.login-form .icon-wrapper {
    position: absolute;
    top: 42px;
    left: 14px;
    color: var(--primary-color);
}

.login-form .toggle-password {
    position: absolute;
    top: 42px;
    right: 14px;
    cursor: pointer;
    color: #aaa;
}

.login-form .toggle-password:hover {
    color: var(--primary-color);
}

.login-btn {
    width: 100%;
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 15px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
}

.login-btn:hover {
    background-color: #0c3132;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.signup-link {
    color: var(--primary-color);
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.signup-link:hover {
    color: var(--secondary-color-2);
}

@media (max-width: 480px) {
    .login-card {
        padding: 25px;
    }
}
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Sign in to continue to your account</p>
        </div>
        
        <form class="login-form" method="POST">
            <div class="form-group">
                <label for="username">Account ID</label>
                <div class="icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <input id="username" name="acct_no" type="number" class="form-control" placeholder="Enter your Account ID">
            </div>
            
            <div class="form-group">
                <div class="d-flex justify-content-between align-items-center">
                    <label for="password">Password</label>
                    <a href="./signup" class="signup-link">Create Account</a>
                </div>
                <div class="icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                </div>
                <input id="password" name="acct_password" type="password" class="form-control" placeholder="Enter your password">
                <div class="toggle-password" id="toggle-password">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </div>
            </div>
            
            <button type="submit" class="login-btn" name="login">Sign In</button>
        </form>
    </div>
</div>

<script>
document.getElementById('toggle-password').addEventListener('click', function() {
    const passwordField = document.getElementById('password');
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    
    // Change the eye icon
    this.innerHTML = type === 'password' 
        ? '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>'
        : '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
});
</script>

<?php
include_once("layout/footer.php");
?>

