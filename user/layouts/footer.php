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

    <?php if(function_exists('support_plugin')) support_plugin(); ?>
</body>
</html>
