<?php
$pageName = "Edit Account";
//session_start();
include_once("layouts/header.php");

//require_once("../include/config.php");
//require_once("../include/userFunction.php");
//require_once('../include/userClass.php');
$acct_id = userDetails('id');



if (!$_SESSION['acct_no']) {
    header("location:../login.php");
    die;
}

if(isset($_POST['upload_picture'])){
    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $name = $file['name'];

        $path = pathinfo($name, PATHINFO_EXTENSION);

        $allowed = array('jpg', 'png', 'jpeg');


        $folder = "../assets/profile/";
        $n = $row['firstname'].$name;

        $destination = $folder . $n;
    }
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $sql = "UPDATE users SET image=:image WHERE id =:id";
        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'image'=>$n,
            'id'=>$user_id
        ]);

        if($stmt->rowCount() > 0){
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showToast('success', 'Upload Successful!', 'Your profile photo has been updated successfully.');
                });
            </script>";
        }else{
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showToast('error', 'Database Error', 'Failed to update profile photo in database.');
                });
            </script>";
        }
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('error', 'Upload Failed', 'Failed to upload profile photo. Please try again.');
            });
        </script>";
    }
}

if(isset($_POST['change_password'])) {
    $old_password = inputValidation($_POST['old_password']);
    $new_password = inputValidation($_POST['new_password']);
    $confirm_password = inputValidation($_POST['confirm_password']);

    if (empty($old_password)) {
        notify_alert('Enter Old Password', 'danger', '2000', 'Close');
    } elseif(empty($new_password) || empty($confirm_password)) {
        notify_alert('Enter New Password & Confirm Password', 'danger', '2000', 'Close');
    }else{

        $new_password2 = password_hash((string)$new_password, PASSWORD_BCRYPT);
        $verification = password_verify($old_password, $row['acct_password']);

        if ($verification === false) {
            toast_alert("error", "Incorrect Old Password");

        } else if ($new_password !== $confirm_password) {
            toast_alert("error", "Confirm Password not matched");

        } else if ($new_password === $old_password) {
            toast_alert('error', 'New Password Matched with Old Password');
        } else {
            $sql2 = "UPDATE users SET acct_password=:acct_password WHERE id =:id";
            $passwordUpdate = $conn->prepare($sql2);
            $passwordUpdate->execute([
                'acct_password' => $new_password2,
                'id' => $user_id
            ]);

            $full_name = $user['firstname']. " ". $user['lastname'];
            // $APP_URL = APP_URL;
            $APP_NAME = WEB_TITLE;
            $APP_URL = WEB_URL;
            $APP_EMAIL = WEB_EMAIL;
            $user_email = $user['acct_email'];

            $message = $sendMail->PassChange($full_name,$APP_EMAIL, $APP_NAME);


            $subject = "Password Chnage Notification". "-". $APP_NAME;
            $email_message->send_mail($user_email, $message, $subject);

            $subject = "Password Chnage Notification". "-". $APP_NAME;
            $email_message->send_mail(WEB_EMAIL, $message, $subject);


            if (true) {
                toast_alert('success', 'Your Password Change Successfully !', 'Approved');
            } else {
                toast_alert('error', 'Sorry Something Went Wrong');
            }
        }
    }
}
?>

<!-- Add profile edit page styles -->
<style>
    /* Page specific styles */
    .profile-edit-container {
        background-color: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
        margin-bottom: 30px;
    }
    
    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
    }
    
    .profile-avatar-edit {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #104042;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 36px;
        font-weight: 600;
        margin-right: 20px;
        position: relative;
        overflow: hidden;
    }
    
    .profile-avatar-edit img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(16, 64, 66, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .profile-avatar-edit:hover .avatar-overlay {
        opacity: 1;
    }
    
    .avatar-overlay i {
        color: #fff;
        font-size: 24px;
    }
    
    .profile-upload-info {
        flex-grow: 1;
    }
    
    .profile-upload-info h3 {
        font-size: 18px;
        font-weight: 600;
        color: #104042;
        margin-bottom: 5px;
    }
    
    .profile-upload-info p {
        font-size: 14px;
        color: rgba(16, 64, 66, 0.7);
        margin-bottom: 15px;
    }
    
    .btn-upload {
        background-color: rgba(16, 64, 66, 0.1);
        color: #104042;
        padding: 8px 15px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-upload:hover {
        background-color: rgba(16, 64, 66, 0.2);
    }
    
    .form-section {
        margin-bottom: 30px;
    }
    
    .form-section-title {
        font-size: 18px;
        font-weight: 600;
        color: #104042;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(16, 64, 66, 0.1);
        display: flex;
        align-items: center;
    }
    
    .form-section-title i {
        margin-right: 10px;
        color: #afff1a;
        background-color: #104042;
        width: 28px;
        height: 28px;
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
    
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
    }
    
    .btn-cancel {
        background-color: transparent;
        color: #104042;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        border: 1px solid rgba(16, 64, 66, 0.2);
        cursor: pointer;
    }
    
    .btn-cancel:hover {
        background-color: rgba(16, 64, 66, 0.05);
    }
    
    .btn-save {
        background-color: #104042;
        color: #fff;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-save:hover {
        background-color: #165e61;
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .profile-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .profile-avatar-edit {
            margin-right: 0;
            margin-bottom: 20px;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-cancel, .btn-save {
            width: 100%;
        }
    }
    
    /* Custom SweetAlert toast styles for top-left positioning */
    .swal2-container.swal2-top-start {
        padding: 20px;
    }
    
    .swal2-popup.swal2-toast {
        background-color: #104042;
        color: #fff;
        border-left: 4px solid #afff1a;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.2);
    }
    
    .swal2-popup.swal2-toast .swal2-title {
        color: #fff;
        font-size: 14px;
        font-weight: 600;
    }
    
    .swal2-popup.swal2-toast .swal2-content {
        color: rgba(255, 255, 255, 0.9);
        font-size: 13px;
    }
    
    .swal2-popup.swal2-toast .swal2-icon.swal2-success {
        border-color: #afff1a;
        color: #afff1a;
    }
    
    .swal2-popup.swal2-toast .swal2-icon.swal2-error {
        border-color: #ff6b6b;
        color: #ff6b6b;
    }
    
    .swal2-popup.swal2-toast .swal2-icon.swal2-info {
        border-color: #afff1a;
        color: #afff1a;
    }
</style>

<!-- Main Content -->
<div class="main-content">
    <header class="header">
        <h1>Edit Profile</h1>
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

    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <div class="profile-edit-container">
            <!-- Profile Photo Edit -->
            <div class="profile-header">
                <div class="profile-avatar-edit">
                    <?php if($row['image'] == null): ?>
                        <div style="width: 100px; height: 100px; background-color: #104042; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 36px; font-weight: 600;">
                            <?php echo substr($fullName, 0, 1); ?>
                        </div>
                    <?php else: ?>
                        <img src="../assets/profile/<?php echo $row['image']; ?>" alt="User profile picture">
                    <?php endif; ?>
                    <div class="avatar-overlay">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                <div class="profile-upload-info">
                    <h3>Profile Photo</h3>
                    <p>Upload a new profile photo. Recommended size: 300x300px, max 5MB.</p>
                    <form method="POST" enctype="multipart/form-data" id="upload-form" style="display: none;">
                        <input type="file" name="image" id="profile-upload" accept="image/*">
                    </form>
                    <button class="btn-upload" onclick="document.getElementById('profile-upload').click();">Upload New Photo</button>
                </div>
            </div>

            <!-- Account Information -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-university"></i>
                    Account Information
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="acct_no">Account Number</label>
                        <input type="text" id="acct_no" value="<?= $row['acct_no'] ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="acct_type">Account Type</label>
                        <input type="text" id="acct_type" value="<?= $row['acct_type'] ?>" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="acct_email">Email</label>
                        <input type="email" id="acct_email" value="<?= $row['acct_email'] ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="acct_dob">Date of Birth</label>
                        <input type="text" id="acct_dob" value="<?= $row['acct_dob'] ?>" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="acct_occupation">Occupation</label>
                        <input type="text" id="acct_occupation" value="<?= $row['acct_occupation'] ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="acct_phone">Phone Number</label>
                        <input type="text" id="acct_phone" value="<?= $row['acct_phone'] ?>" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="acct_gender">Gender</label>
                        <input type="text" class="text-capitalize" id="acct_gender" value="<?= $row['acct_gender'] ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="marital_status">Marital Status</label>
                        <input type="text" class="text-capitalize" id="marital_status" value="<?= $row['marital_status'] ?>" readonly>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-map-marker-alt"></i>
                    Address Information
                </div>
                <div class="form-group">
                    <label for="acct_address">Contact Address</label>
                    <input type="text" id="acct_address" value="<?= $row['acct_address'] ?>" readonly>
                </div>
            </div>

            <!-- Change Password Form -->
            <form method="post" class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-lock"></i>
                    Change Password
                </div>
                <div class="form-group">
                    <label for="old_password">Current Password</label>
                    <input type="password" id="old_password" name="old_password" placeholder="Enter your current password">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" placeholder="Enter new password">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password">
                    </div>
                </div>
                <p style="font-size: 13px; color: rgba(16, 64, 66, 0.7); margin-top: -10px; margin-bottom: 20px;">
                    Leave password fields blank if you don't want to change your password.
                </p>
                <div class="form-actions">
                    <a href="profile.php"><button type="button" class="btn-cancel">Cancel</button></a>
                    <button type="submit" name="change_password" class="btn-save">Change Password</button>
                </div>
            </form>

            <!-- Change PIN Form -->
            <form method="post" class="form-section" autocomplete="off" autofocus="off">
                <div class="form-section-title">
                    <i class="fas fa-key"></i>
                    Change PIN
                </div>
                <div class="form-group">
                    <label for="current_pin">Current PIN</label>
                    <input type="password" id="current_pin" name="current_pin" placeholder="Enter your current PIN">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="new_pin">New PIN</label>
                        <input type="password" id="new_pin" name="new_pin" placeholder="Enter new PIN">
                    </div>
                    <div class="form-group">
                        <label for="confirm_pin">Confirm PIN</label>
                        <input type="password" id="confirm_pin" name="confirm_pin" placeholder="Confirm new PIN">
                    </div>
                </div>
                <p style="font-size: 13px; color: rgba(16, 64, 66, 0.7); margin-top: -10px; margin-bottom: 20px;">
                    Leave PIN fields blank if you don't want to change your PIN.
                </p>
                <div class="form-actions">
                    <a href="profile.php"><button type="button" class="btn-cancel">Cancel</button></a>
                    <button type="submit" name="change_pin" class="btn-save">Change PIN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Custom toast notification function with system colors
    function showToast(type, title, message) {
        let iconColor, borderColor;
        
        switch(type) {
            case 'success':
                iconColor = '#afff1a';
                borderColor = '#afff1a';
                break;
            case 'error':
                iconColor = '#ff6b6b';
                borderColor = '#ff6b6b';
                break;
            case 'info':
                iconColor = '#afff1a';
                borderColor = '#afff1a';
                break;
            default:
                iconColor = '#afff1a';
                borderColor = '#afff1a';
        }
        
        Swal.fire({
            toast: true,
            position: 'top-start',
            icon: type,
            title: title,
            text: message,
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: '#104042',
            color: '#fff',
            customClass: {
                popup: 'custom-toast-popup'
            },
            didOpen: (toast) => {
                toast.style.borderLeft = `4px solid ${borderColor}`;
                const icon = toast.querySelector('.swal2-icon');
                if (icon) {
                    icon.style.borderColor = iconColor;
                    icon.style.color = iconColor;
                }
            }
        });
    }

    // Handle profile image upload
    document.addEventListener('DOMContentLoaded', function() {
        const profileUpload = document.getElementById('profile-upload');
        const uploadForm = document.getElementById('upload-form');
        
        if (profileUpload) {
            profileUpload.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    // Show loading notification
                    Swal.fire({
                        title: 'Uploading...',
                        text: 'Please wait while we upload your profile photo',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        position: 'top-start',
                        toast: true,
                        timer: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Add a hidden submit button to the form
                    const submitBtn = document.createElement('button');
                    submitBtn.type = 'submit';
                    submitBtn.name = 'upload_picture';
                    submitBtn.style.display = 'none';
                    uploadForm.appendChild(submitBtn);
                    
                    // Submit the form
                    submitBtn.click();
                }
            });
        }
    });
</script>

<?php
include_once("layouts/footer.php");
?>
