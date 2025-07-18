<?php
include_once("layout/header.php");

if(@!$_SESSION['login']){
    header("Location:./login.php");
}
if(@$_SESSION['acct_no']){
    header('Location:./user/dashboard.php');
}
$viesConn="SELECT * FROM users WHERE acct_no = :acct_no";
$stmt = $conn->prepare($viesConn);

$stmt->execute([
    ':acct_no'=>$_SESSION['login']
]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$user_id = $row['id'];
$fullName = $row['firstname']." ".$row['lastname'];
$acct_no = $row['acct_no'];


if(isset($_POST['pin_submit'])){
    $pinVerified = $_POST['input'];
    $old_otp = $row['acct_pin'];

    if($pinVerified !== $old_otp){
        notify_alert('Invalid OTP CODE','danger','2000','Close');
    }
    if(empty($pinVerified)){
        notify_alert('Enter Your OTP','danger','2000','Close');
    }
    if($pinVerified === $old_otp){
        session_start();
        $_SESSION['acct_no'] = $acct_no;
        $_COOKIE['firstVisit'] = $acct_no;
        header("Location:./user/dashboard.php");
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

.pin-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--light-gray);
    padding: 20px;
}

.pin-card {
    background-color: var(--white);
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    width: 100%;
    max-width: 420px;
    padding: 40px;
    position: relative;
    overflow: hidden;
}

.pin-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 6px;
    background: linear-gradient(90deg, var(--secondary-color-1), var(--secondary-color-2));
}

.user-profile {
    display: flex;
    align-items: center;
    margin-bottom: 25px;
    justify-content: center;
    flex-direction: column;
    text-align: center;
}

.user-profile img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--secondary-color-2);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.user-profile p {
    margin-top: 10px;
    font-size: 18px;
    font-weight: 600;
    color: var(--primary-color);
}

.pin-header {
    text-align: center;
    margin-bottom: 25px;
}

.pin-header h3 {
    color: var(--primary-color);
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 5px;
}

.pin-header p {
    color: #666;
    font-size: 15px;
}

.pin-form .form-group {
    margin-bottom: 20px;
    position: relative;
}

.pin-form label {
    display: block;
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.pin-form .form-control {
    width: 100%;
    padding: 14px 12px 14px 40px;
    border: 1px solid #e1e1e1;
    border-radius: 6px;
    font-size: 15px;
    transition: all 0.3s ease;
    text-align: center;
    letter-spacing: 3px;
    font-weight: 600;
}

.pin-form .form-control:focus {
    border-color: var(--secondary-color-2);
    box-shadow: 0 0 0 3px rgba(255, 210, 0, 0.15);
    outline: none;
}

.pin-form .icon-wrapper {
    position: absolute;
    top: 42px;
    left: 14px;
    color: var(--primary-color);
}

.pin-keypad {
    margin-top: 20px;
}

.pin-keypad-row {
    display: flex;
    justify-content: center;
    margin-bottom: 10px;
}

.pin-key {
    width: 60px;
    height: 60px;
    margin: 0 5px;
    border-radius: 50%;
    border: none;
    background-color: var(--white);
    color: var(--primary-color);
    font-size: 20px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.pin-key:hover {
    background-color: #f5f5f5;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.pin-key:active {
    transform: translateY(0);
    background-color: #eeeeee;
}

.pin-key.del {
    background-color: #ff6b6b;
    color: white;
}

.pin-key.del:hover {
    background-color: #ff5252;
}

.pin-key.faq {
    background-color: var(--secondary-color-2);
    color: var(--primary-color);
}

.pin-key.faq:hover {
    background-color: #ffc800;
}

.pin-submit {
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
    margin-top: 20px;
}

.pin-submit:hover {
    background-color: #0c3132;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

@media (max-width: 480px) {
    .pin-card {
        padding: 25px;
    }
    
    .pin-key {
        width: 50px;
        height: 50px;
        font-size: 18px;
    }
}
</style>

<div class="pin-container">
    <div class="pin-card">
        <div class="user-profile">
            <img src="./assets/profile/<?= $row['image']?>" alt="User Profile">
            <p><?= $fullName ?></p>
        </div>
        
        <div class="pin-header">
            <h3>Welcome</h3>
            <p>Please enter your PIN to continue</p>
        </div>
        
        <form class="pin-form" method="post">
            <div class="form-group">
                <label for="pincode">PIN Code</label>
                <div class="icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                </div>
                <input id="datepicker" name="input" type="number" class="form-control" placeholder="• • • •" autocomplete="off">
            </div>
            
            <div class="pin-keypad">
                <div class="pin-keypad-row">
                    <button type="button" class="pin-key shuffle">1</button>
                    <button type="button" class="pin-key shuffle">2</button>
                    <button type="button" class="pin-key shuffle">3</button>
                </div>
                <div class="pin-keypad-row">
                    <button type="button" class="pin-key shuffle">4</button>
                    <button type="button" class="pin-key shuffle">5</button>
                    <button type="button" class="pin-key shuffle">6</button>
                </div>
                <div class="pin-keypad-row">
                    <button type="button" class="pin-key shuffle">7</button>
                    <button type="button" class="pin-key shuffle">8</button>
                    <button type="button" class="pin-key shuffle">9</button>
                </div>
                <div class="pin-keypad-row">
                    <button type="button" class="pin-key del">X</button>
                    <button type="button" class="pin-key shuffle">0</button>
                    <button type="button" class="pin-key faq">?</button>
                </div>
            </div>
            
            <button type="submit" class="pin-submit" name="pin_submit">Verify PIN</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pinInput = document.getElementById('datepicker');
    const keys = document.querySelectorAll('.pin-key');
    
    keys.forEach(key => {
        key.addEventListener('click', function() {
            if (this.classList.contains('shuffle')) {
                // Add digit to PIN input
                if (pinInput.value.length < 6) {
                    pinInput.value += this.textContent;
                }
            } else if (this.classList.contains('del')) {
                // Delete last digit
                pinInput.value = pinInput.value.slice(0, -1);
            } else if (this.classList.contains('faq')) {
                // FAQ button functionality
                alert('Please enter your PIN code to access your account.');
            }
        });
    });
    
    // Optional: Shuffle the keypad numbers for security
    function shuffleKeypad() {
        const keypadNumbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        const shuffleButtons = document.querySelectorAll('.shuffle');
        
        // Fisher-Yates shuffle algorithm
        for (let i = keypadNumbers.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [keypadNumbers[i], keypadNumbers[j]] = [keypadNumbers[j], keypadNumbers[i]];
        }
        
        // Assign shuffled numbers to buttons
        shuffleButtons.forEach((button, index) => {
            button.textContent = keypadNumbers[index];
        });
    }
    
    // Uncomment the line below if you want to enable keypad shuffling
    // shuffleKeypad();
});
</script>

<?php
include_once("layout/footer.php");
?>
