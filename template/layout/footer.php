<!-- Mobile Bottom Navigation -->
<nav class="mobile-bottom-nav">
        <a href="../user/dashboard.php" class="mobile-nav-item active">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="../user/wire-transfer.php" class="mobile-nav-item">
            <i class="fas fa-exchange-alt"></i>
            <span>Transfer</span>
        </a>
        <a href="../user/withdrawal.php" class="mobile-nav-item">
            <i class="fas fa-wallet"></i>
            <span>Withdraw</span>
        </a>
        <a href="../user/profile.php" class="mobile-nav-item">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-overlay"></div>

    <div class="footer-wrapper">
        <div class="footer-section f-section-1">
            <p class="">Copyright © <?php echo date('Y'); ?> <?php echo $pageTitle ?>, All rights reserved.</p>
        </div>
        <div class="footer-section f-section-2">
            <p class=""><?php echo $pageTitle ?> </p>
        </div>
    </div>

    <!-- Include JavaScript -->
    <script src="/assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="../bootstrap/js/popper.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
            App.init();
            
            // Handle submenu toggles
            $('.submenu-toggle').on('click', function(e) {
                e.preventDefault();
                const parent = $(this).parent();
                
                // Close all other open submenus
                $('.has-submenu').not(parent).removeClass('open');
                
                // Toggle current submenu
                parent.toggleClass('open');
            });
        });
    </script>
    <script src="../assets/js/custom.js"></script>
    <script src="script.js"></script>

    <!--Start of Tawk.to Script-->
    <?php if(isset($livechat) && !empty($livechat)): ?>
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='<?= $page['livechat'] ?>';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <?php endif; ?>
    <!--End of Tawk.to Script-->

    <?php if(function_exists('support_plugin')) support_plugin(); ?>
</body>
</html>
