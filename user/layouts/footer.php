<?php
// Get current page for navigation highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
/* Mobile Banking Footer Styles */
.mobile-footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    border-top: 1px solid #e9ecef;
    padding: 8px 0 max(8px, env(safe-area-inset-bottom));
    z-index: 1000;
    box-shadow: 0 -2px 20px rgba(0,0,0,0.1);
}

.footer-nav {
    display: flex;
    justify-content: space-around;
    align-items: center;
    max-width: 500px;
    margin: 0 auto;
    padding: 0 10px;
}

.footer-nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    color: #6c757d;
    transition: all 0.3s ease;
    padding: 8px 12px;
    border-radius: 12px;
    min-width: 60px;
    position: relative;
}

.footer-nav-item.active {
    color: #667eea;
    background: rgba(102, 126, 234, 0.1);
}

.footer-nav-item:hover {
    color: #667eea;
    text-decoration: none;
    transform: translateY(-2px);
}

.footer-nav-icon {
    font-size: 20px;
    margin-bottom: 4px;
    transition: all 0.3s ease;
}

.footer-nav-item.active .footer-nav-icon {
    transform: scale(1.1);
}

.footer-nav-label {
    font-size: 11px;
    font-weight: 500;
    line-height: 1;
}

/* Notification badge */
.nav-badge {
    position: absolute;
    top: 2px;
    right: 8px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

/* Quick action button (center) */
.quick-action-btn {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 50%;
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
    text-decoration: none;
    margin: -8px 0;
}

.quick-action-btn:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.quick-action-btn:active {
    transform: translateY(0) scale(0.95);
}

/* Responsive adjustments */
@media (max-width: 480px) {
    .footer-nav {
        padding: 0 5px;
    }

    .footer-nav-item {
        min-width: 50px;
        padding: 6px 8px;
    }

    .footer-nav-icon {
        font-size: 18px;
    }

    .footer-nav-label {
        font-size: 10px;
    }

    .quick-action-btn {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
}

/* Hide footer on certain pages */
.hide-footer .mobile-footer {
    display: none;
}

/* Add bottom padding to body to prevent content overlap */
body {
    padding-bottom: 80px;
}

/* Animation for page transitions */
.page-transition {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
}

.page-transition.loaded {
    opacity: 1;
    transform: translateY(0);
}

/* Floating action menu */
.floating-menu {
    position: fixed;
    bottom: 90px;
    right: 20px;
    z-index: 999;
    display: none;
}

.floating-menu.show {
    display: block;
}

.floating-menu-item {
    background: white;
    border-radius: 50%;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    color: #667eea;
    text-decoration: none;
    transition: all 0.3s ease;
    transform: scale(0);
    animation: popIn 0.3s ease forwards;
}

.floating-menu-item:nth-child(1) { animation-delay: 0.1s; }
.floating-menu-item:nth-child(2) { animation-delay: 0.2s; }
.floating-menu-item:nth-child(3) { animation-delay: 0.3s; }

.floating-menu-item:hover {
    background: #667eea;
    color: white;
    transform: scale(1.1);
    text-decoration: none;
}

@keyframes popIn {
    to {
        transform: scale(1);
    }
}

/* Loading states */
.nav-loading {
    opacity: 0.6;
    pointer-events: none;
}

.nav-loading .footer-nav-icon {
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>

<!-- Mobile Footer Navigation -->
<footer class="mobile-footer">
    <nav class="footer-nav">
        <!-- Home -->
        <a href="dashboard.php" class="footer-nav-item <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
            <div class="footer-nav-icon">
                <i class="fas fa-home"></i>
            </div>
            <div class="footer-nav-label">Home</div>
        </a>

        <!-- Transactions -->
        <a href="credit-debit_transaction.php" class="footer-nav-item <?= in_array($current_page, ['credit-debit_transaction.php', 'transaction-history.php']) ? 'active' : '' ?>">
            <div class="footer-nav-icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="footer-nav-label">Transactions</div>
            <?php
            // Check for pending transactions
            $pending_sql = "SELECT COUNT(*) as pending_count FROM transactions WHERE user_id = :user_id AND trans_status = 0";
            $pending_stmt = $conn->prepare($pending_sql);
            $pending_stmt->execute([':user_id' => $user_id]);
            $pending_count = $pending_stmt->fetch(PDO::FETCH_ASSOC)['pending_count'];

            if($pending_count > 0):
            ?>
            <span class="nav-badge"><?= $pending_count > 9 ? '9+' : $pending_count ?></span>
            <?php endif; ?>
        </a>

        <!-- Quick Transfer (Center Button) -->
        <a href="wire-transfer.php" class="quick-action-btn" title="Quick Transfer">
            <i class="fas fa-paper-plane"></i>
        </a>

        <!-- Cards -->
        <a href="card.php" class="footer-nav-item <?= $current_page == 'card.php' ? 'active' : '' ?>">
            <div class="footer-nav-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <div class="footer-nav-label">Cards</div>
        </a>

        <!-- Profile -->
        <a href="profile.php" class="footer-nav-item <?= in_array($current_page, ['profile.php', 'edit-profile.php', 'account-details.php']) ? 'active' : '' ?>">
            <div class="footer-nav-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="footer-nav-label">Profile</div>
        </a>
    </nav>
</footer>

<!-- Floating Action Menu -->
<div class="floating-menu" id="floatingMenu">
    <a href="deposit.php" class="floating-menu-item" title="Deposit">
        <i class="fas fa-plus"></i>
    </a>
    <a href="withdrawal.php" class="floating-menu-item" title="Withdraw">
        <i class="fas fa-minus"></i>
    </a>
    <a href="loan.php" class="floating-menu-item" title="Loan">
        <i class="fas fa-hand-holding-usd"></i>
    </a>
</div>

<!-- JavaScript for Footer Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add page transition effect
    document.body.classList.add('page-transition');
    setTimeout(() => {
        document.body.classList.add('loaded');
    }, 100);

    // Handle quick action button
    const quickActionBtn = document.querySelector('.quick-action-btn');
    const floatingMenu = document.getElementById('floatingMenu');
    let menuOpen = false;

    if (quickActionBtn && floatingMenu) {
        quickActionBtn.addEventListener('click', function(e) {
            e.preventDefault();

            if (!menuOpen) {
                // Show floating menu
                floatingMenu.classList.add('show');
                quickActionBtn.style.transform = 'rotate(45deg)';
                menuOpen = true;
            } else {
                // Hide floating menu or navigate to transfer page
                if (e.target.closest('.quick-action-btn')) {
                    window.location.href = 'wire-transfer.php';
                }
            }
        });

        // Close floating menu when clicking outside
        document.addEventListener('click', function(e) {
            if (menuOpen && !e.target.closest('.floating-menu') && !e.target.closest('.quick-action-btn')) {
                floatingMenu.classList.remove('show');
                quickActionBtn.style.transform = 'rotate(0deg)';
                menuOpen = false;
            }
        });
    }

    // Add haptic feedback for mobile devices
    function addHapticFeedback(element) {
        element.addEventListener('touchstart', function() {
            if (navigator.vibrate) {
                navigator.vibrate(10);
            }
        });
    }

    // Apply haptic feedback to navigation items
    document.querySelectorAll('.footer-nav-item, .quick-action-btn, .floating-menu-item').forEach(addHapticFeedback);

    // Handle navigation loading states
    document.querySelectorAll('.footer-nav-item').forEach(item => {
        item.addEventListener('click', function(e) {
            // Don't show loading for current page
            if (!this.classList.contains('active')) {
                this.classList.add('nav-loading');

                // Remove loading state after navigation
                setTimeout(() => {
                    this.classList.remove('nav-loading');
                }, 1000);
            }
        });
    });

    // Update active state based on current page
    function updateActiveNavigation() {
        const currentPath = window.location.pathname;
        const navItems = document.querySelectorAll('.footer-nav-item');

        navItems.forEach(item => {
            const href = item.getAttribute('href');
            if (currentPath.includes(href)) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }

    // Handle browser back/forward navigation
    window.addEventListener('popstate', updateActiveNavigation);

    // Smooth scroll to top when tapping active nav item
    document.querySelectorAll('.footer-nav-item.active').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });

    // Hide footer on scroll down, show on scroll up
    let lastScrollTop = 0;
    const footer = document.querySelector('.mobile-footer');

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down
            footer.style.transform = 'translateY(100%)';
        } else {
            // Scrolling up
            footer.style.transform = 'translateY(0)';
        }

        lastScrollTop = scrollTop;
    }, { passive: true });

    // Add swipe gestures for navigation
    let touchStartX = 0;
    let touchEndX = 0;

    document.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    document.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        const swipeThreshold = 100;
        const swipeDistance = touchEndX - touchStartX;

        if (Math.abs(swipeDistance) > swipeThreshold) {
            const navItems = Array.from(document.querySelectorAll('.footer-nav-item'));
            const currentIndex = navItems.findIndex(item => item.classList.contains('active'));

            if (swipeDistance > 0 && currentIndex > 0) {
                // Swipe right - go to previous tab
                navItems[currentIndex - 1].click();
            } else if (swipeDistance < 0 && currentIndex < navItems.length - 1) {
                // Swipe left - go to next tab
                navItems[currentIndex + 1].click();
            }
        }
    }
});

// Global function to show notification badge
function updateNotificationBadge(count) {
    const badge = document.querySelector('.nav-badge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count > 9 ? '9+' : count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }
}

// Global function to show loading state
function showNavLoading(navItem) {
    if (navItem) {
        navItem.classList.add('nav-loading');
    }
}

// Global function to hide loading state
function hideNavLoading(navItem) {
    if (navItem) {
        navItem.classList.remove('nav-loading');
    }
}
</script>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Custom Banking Scripts -->
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Service Worker for offline functionality
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js').then(function(registration) {
            console.log('ServiceWorker registration successful');
        }, function(err) {
            console.log('ServiceWorker registration failed: ', err);
        });
    });
}

// Handle network status
window.addEventListener('online', function() {
    document.body.classList.remove('offline');
    showAlert('Connection restored', 'success', 2000);
});

window.addEventListener('offline', function() {
    document.body.classList.add('offline');
    showAlert('You are offline', 'warning', 3000);
});

// Global error handler
window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
    // Don't show error alerts in production
    if (window.location.hostname === 'localhost') {
        showAlert('An error occurred: ' + e.message, 'error', 5000);
    }
});

// Performance monitoring
window.addEventListener('load', function() {
    setTimeout(function() {
        const perfData = performance.getEntriesByType('navigation')[0];
        console.log('Page load time:', perfData.loadEventEnd - perfData.loadEventStart, 'ms');
    }, 0);
});
</script>

</div> <!-- End main wrapper -->

</body>
</html>
