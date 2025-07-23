<?php
$pageName = "My Profile";
include_once("layouts/headerprofile.php");
require_once("../include/config.php");
require_once("../include/userFunction.php");


if (!$_SESSION['acct_no']) {
    header("location:../login.php");
    die;
}

?>

<!-- Add profile page styles -->
<style>
    /* Page specific styles */
    .profile-container {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 30px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 992px) {
        .profile-container {
            grid-template-columns: 1fr;
        }
    }
    
    .profile-sidebar {
        background-color: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background-color: #104042;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 48px;
        font-weight: 600;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }
    
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .change-photo {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(16, 64, 66, 0.8);
        color: #fff;
        padding: 5px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .profile-name {
        font-size: 22px;
        font-weight: 600;
        color: #104042;
        margin-bottom: 5px;
    }
    
    .profile-email {
        color: rgba(16, 64, 66, 0.7);
        margin-bottom: 20px;
    }
    
    .profile-status {
        display: inline-block;
        padding: 5px 15px;
        background-color: rgba(175, 255, 26, 0.2);
        color: #104042;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 25px;
    }
    
    .profile-status.verified {
        background-color: rgba(175, 255, 26, 0.2);
        color: #104042;
    }
    
    .profile-status.unverified {
        background-color: rgba(255, 210, 0, 0.2);
        color: #104042;
    }
    
    .profile-actions {
        width: 100%;
        margin-top: 20px;
    }
    
    .btn-edit-profile {
        background-color: #104042;
        color: #fff;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-bottom: 15px;
    }
    
    .btn-edit-profile:hover {
        background-color: #165e61;
    }
    
    .profile-details {
        background-color: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
    }
    
    .profile-section {
        margin-bottom: 30px;
    }
    
    .profile-section:last-child {
        margin-bottom: 0;
    }
    
    .profile-section-title {
        font-size: 18px;
        font-weight: 600;
        color: #104042;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(16, 64, 66, 0.1);
        display: flex;
        align-items: center;
    }
    
    .profile-section-title i {
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
    
    .profile-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    @media (max-width: 768px) {
        .profile-info-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .profile-info-item {
        margin-bottom: 15px;
    }
    
    .profile-info-label {
        font-size: 14px;
        color: rgba(16, 64, 66, 0.7);
        margin-bottom: 5px;
    }
    
    .profile-info-value {
        font-size: 16px;
        color: #104042;
        font-weight: 500;
    }
</style>

<!-- Main Content -->
<div class="main-content">
    <header class="header">
        <h1>My Profile</h1>
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
        <div class="profile-container">
            <!-- Profile Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-avatar">
                    <?php if($row['image'] == null): ?>
                        <div style="width: 120px; height: 120px; background-color: #104042; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: 600;">
                            <?php echo substr($fullName, 0, 1); ?>
                        </div>
                    <?php else: ?>
                        <img src="../assets/profile/<?php echo $row['image']; ?>" alt="User profile picture">
                    <?php endif; ?>
                </div>
                <div class="profile-name"><?php echo $fullName; ?></div>
                <div class="profile-email"><?php echo $row['acct_email']; ?></div>
                <div class="profile-status <?php echo ($row['acct_status'] === 'active') ? 'verified' : 'unverified'; ?>">
                    <i class="fas <?php echo ($row['acct_status'] === 'active') ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i> 
                    <?php echo ($row['acct_status'] === 'active') ? 'Verified Account' : 'Account on Hold'; ?>
                </div>
                <div class="profile-actions">
                    <a href="./edit-profile.php"><button class="btn-edit-profile">Edit Profile</button></a>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="profile-details">
                <!-- Personal Information -->
                <div class="profile-section">
                    <div class="profile-section-title">
                        <i class="fas fa-user"></i>
                        Personal Information
                    </div>
                    <div class="profile-info-grid">
                        <div class="profile-info-item">
                            <div class="profile-info-label">Full Name</div>
                            <div class="profile-info-value"><?php echo $fullName; ?></div>
                        </div>
                        <div class="profile-info-item">
                            <div class="profile-info-label">Date of Birth</div>
                            <div class="profile-info-value"><?php echo $row['acct_dob']; ?></div>
                        </div>
                        <div class="profile-info-item">
                            <div class="profile-info-label">Email Address</div>
                            <div class="profile-info-value"><?php echo $row['acct_email']; ?></div>
                        </div>
                        <div class="profile-info-item">
                            <div class="profile-info-label">Phone Number</div>
                            <div class="profile-info-value"><?php echo $row['acct_phone']; ?></div>
                        </div>
                        <div class="profile-info-item">
                            <div class="profile-info-label">Gender</div>
                            <div class="profile-info-value"><?php echo ucfirst($row['acct_gender']); ?></div>
                        </div>
                        <div class="profile-info-item">
                            <div class="profile-info-label">Occupation</div>
                            <div class="profile-info-value"><?php echo $row['acct_occupation']; ?></div>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="profile-section">
                    <div class="profile-section-title">
                        <i class="fas fa-university"></i>
                        Account Information
                    </div>
                    <div class="profile-info-grid">
                        <div class="profile-info-item">
                            <div class="profile-info-label">Account Number</div>
                            <div class="profile-info-value"><?php echo $row['acct_no']; ?></div>
                        </div>
                        <div class="profile-info-item">
                            <div class="profile-info-label">Account Type</div>
                            <div class="profile-info-value"><?php echo $row['acct_type']; ?></div>
                        </div>
                        <div class="profile-info-item">
                            <div class="profile-info-label">Currency</div>
                            <div class="profile-info-value"><?php echo $row['acct_currency']; ?></div>
                        </div>
                        <div class="profile-info-item">
                            <div class="profile-info-label">Account Status</div>
                            <div class="profile-info-value"><?php echo ucfirst($row['acct_status']); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="profile-section">
                    <div class="profile-section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Address Information
                    </div>
                    <div class="profile-info-grid">
                        <div class="profile-info-item">
                            <div class="profile-info-label">Address</div>
                            <div class="profile-info-value"><?php echo $row['acct_address']; ?></div>
                        </div>
                        <div class="profile-info-item">
                            <div class="profile-info-label">State/Province</div>
                            <div class="profile-info-value"><?php echo $row['state']; ?></div>
                        </div>
                        <div class="profile-info-item">
                            <div class="profile-info-label">Country</div>
                            <div class="profile-info-value"><?php echo $row['country']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once("layouts/footer.php");
?>
