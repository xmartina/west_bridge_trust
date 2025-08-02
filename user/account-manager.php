
<?php
$pageName = "Account Manager";
include_once("layouts/header.php");

if($acct_stat != 'active'){
    header("Location:./error.php");
    exit();
}
?>

<style>
    .main-content {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 20px;
    }
    
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        background: #fff;
        padding: 20px 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
    }
    
    .header h1 {
        color: #104042;
        margin: 0;
        font-size: 28px;
        font-weight: 600;
    }
    
    .search-notification {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .search-bar {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .search-bar i {
        position: absolute;
        left: 15px;
        color: rgba(16, 64, 66, 0.5);
    }
    
    .search-bar input {
        padding: 10px 15px 10px 45px;
        border: 1px solid rgba(16, 64, 66, 0.2);
        border-radius: 25px;
        background-color: rgba(16, 64, 66, 0.02);
        color: #104042;
        font-size: 14px;
        width: 250px;
    }
    
    .user-profile {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .notification {
        position: relative;
        padding: 8px;
        border-radius: 50%;
        background-color: rgba(16, 64, 66, 0.1);
        cursor: pointer;
    }
    
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
    }
    
    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .user-name {
        font-weight: 500;
        color: #104042;
    }
    
    .dashboard-content {
        display: grid;
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .manager-card {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
        margin-bottom: 30px;
    }
    
    .manager-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(16, 64, 66, 0.1);
    }
    
    .manager-avatar {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        overflow: hidden;
        background: linear-gradient(135deg, #104042 0%, #0a2a2b 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 48px;
        font-weight: 600;
    }
    
    .manager-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .manager-info h2 {
        color: #104042;
        margin: 0 0 10px 0;
        font-size: 24px;
        font-weight: 600;
    }
    
    .manager-title {
        color: rgba(16, 64, 66, 0.7);
        font-size: 16px;
        margin-bottom: 15px;
    }
    
    .contact-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .contact-btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    
    .contact-btn.primary {
        background-color: #104042;
        color: #fff;
    }
    
    .contact-btn.primary:hover {
        background-color: #165e61;
        color: #fff;
    }
    
    .contact-btn.secondary {
        background-color: rgba(16, 64, 66, 0.1);
        color: #104042;
        border: 1px solid rgba(16, 64, 66, 0.2);
    }
    
    .contact-btn.secondary:hover {
        background-color: rgba(16, 64, 66, 0.2);
        color: #104042;
    }
    
    .manager-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }
    
    .detail-section {
        background: rgba(16, 64, 66, 0.02);
        padding: 25px;
        border-radius: 12px;
        border: 1px solid rgba(16, 64, 66, 0.1);
    }
    
    .detail-section h3 {
        color: #104042;
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid rgba(16, 64, 66, 0.1);
    }
    
    .detail-item:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        color: rgba(16, 64, 66, 0.7);
        font-size: 14px;
        font-weight: 500;
    }
    
    .detail-value {
        color: #104042;
        font-weight: 600;
        text-align: right;
    }
    
    .account-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background-color: rgba(175, 255, 26, 0.2);
        color: #104042;
    }
    
    @media (max-width: 768px) {
        .manager-header {
            flex-direction: column;
            text-align: center;
        }
        
        .manager-details {
            grid-template-columns: 1fr;
        }
        
        .contact-buttons {
            justify-content: center;
        }
        
        .search-bar input {
            width: 200px;
        }
    }
</style>

<div class="main-content">
    <header class="header">
        <h1>Account Manager</h1>
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
        <div class="manager-card">
            <div class="manager-header">
                <div class="manager-avatar">
                    <?php if($row['mgr_image'] != null): ?>
                        <img src="../assets/profile/<?php echo $row['mgr_image']; ?>" alt="Account Manager">
                    <?php else: ?>
                        <?php echo substr($row['mgr_name'], 0, 1); ?>
                    <?php endif; ?>
                </div>
                
                <div class="manager-info">
                    <h2><?php echo $row['mgr_name']; ?></h2>
                    <div class="manager-title">Personal Account Manager</div>
                    <div class="contact-buttons">
                        <a href="tel:<?php echo $row['mgr_no']; ?>" class="contact-btn primary">
                            <i class="fas fa-phone"></i>
                            Call Manager
                        </a>
                        <a href="mailto:<?php echo $row['mgr_email']; ?>" class="contact-btn secondary">
                            <i class="fas fa-envelope"></i>
                            Send Email
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="manager-details">
                <div class="detail-section">
                    <h3>
                        <i class="fas fa-user-tie"></i>
                        Contact Information
                    </h3>
                    <div class="detail-item">
                        <div class="detail-label">Manager Name</div>
                        <div class="detail-value"><?php echo $row['mgr_name']; ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Phone Number</div>
                        <div class="detail-value"><?php echo $row['mgr_no']; ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Email Address</div>
                        <div class="detail-value"><?php echo $row['mgr_email']; ?></div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>
                        <i class="fas fa-university"></i>
                        Account Details
                    </h3>
                    <div class="detail-item">
                        <div class="detail-label">Account Type</div>
                        <div class="detail-value"><?php echo $row['acct_type']; ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Account Status</div>
                        <div class="detail-value">
                            <span class="account-status">Active</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Account Limit</div>
                        <div class="detail-value"><?php echo $currency . number_format($row['acct_limit'], 2); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once("layouts/footer.php");
?>
