// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to buttons - alerts removed for production
    document.querySelector('.btn-send')?.addEventListener('click', function() {
        // Send functionality
    });

    document.querySelector('.btn-withdraw')?.addEventListener('click', function() {
        // Withdraw functionality
    });

    document.querySelector('.btn-send-money')?.addEventListener('click', function() {
        // Send money functionality
    });

    // Quick action button in balance card
    document.querySelector('.btn-quick-action')?.addEventListener('click', function() {
        // Transfer functionality
    });

    // Handle submenu toggles
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;

            // Close all other open submenus
            const allSubmenus = document.querySelectorAll('.has-submenu');
            allSubmenus.forEach(item => {
                if (item !== parent && item.classList.contains('open')) {
                    item.classList.remove('open');
                }
            });

            // Toggle current submenu
            parent.classList.toggle('open');
        });
    });

    // Add hover effect to navigation items
    const navItems = document.querySelectorAll('.menu-list li a');
    navItems.forEach(item => {
        item.addEventListener('mouseover', function() {
            if (!this.parentElement.classList.contains('active')) {
                this.style.backgroundColor = 'rgba(175, 255, 26, 0.1)';
            }
        });
        item.addEventListener('mouseout', function() {
            if (!this.parentElement.classList.contains('active')) {
                this.style.backgroundColor = '';
            }
        });
    });

    // Quick Actions functionality
    const quickActionCards = document.querySelectorAll('.quick-action-card');
    quickActionCards.forEach(card => {
        card.addEventListener('click', function() {
            // Quick action functionality - alerts removed for production
        });
    });

    // Loan payment button
    document.querySelector('.btn-pay-loan')?.addEventListener('click', function() {
        // Loan payment functionality
    });

    // Wire transfer items click
    const wireTransferItems = document.querySelectorAll('.wire-transfer-item');
    wireTransferItems.forEach(item => {
        item.addEventListener('click', function() {
            // Wire transfer details functionality
        });
    });

    // View all transfers button
    document.querySelector('.btn-view-all')?.addEventListener('click', function() {
        // View all transfers functionality
    });

    // Card request item
    document.querySelector('.request-item')?.addEventListener('click', function() {
        // Card request details functionality
    });

    // Message items
    const messageItems = document.querySelectorAll('.message-item');
    messageItems.forEach(item => {
        item.addEventListener('click', function() {
            if (this.classList.contains('unread')) {
                this.classList.remove('unread');
            }
            // Message functionality
        });
    });

    // Mobile bottom navigation
    const mobileNavItems = document.querySelectorAll('.mobile-nav-item');
    mobileNavItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Remove active class from all items
            mobileNavItems.forEach(navItem => navItem.classList.remove('active'));
            // Add active class to clicked item
            this.classList.add('active');
            
            // Mobile navigation functionality - alerts removed for production
            
            e.preventDefault();
        });
    });
});

// Add responsive menu toggle for mobile
function createMobileMenu() {
    const container = document.querySelector('.container');
    const sidebar = document.querySelector('.sidebar');
    let overlay = document.querySelector('.mobile-overlay');
    
    if (!container || !sidebar) return;
    
    // Create overlay if it doesn't exist
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.classList.add('mobile-overlay');
        document.body.appendChild(overlay);
    }
    
    // Create toggle button
    const toggleBtn = document.createElement('button');
    toggleBtn.classList.add('mobile-menu-toggle');
    toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
    
    // Add to body instead of header
    document.body.appendChild(toggleBtn);
    
    // Add event listener to open menu
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.add('show-mobile');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    });
    
    // Add event listener to close menu when clicking overlay
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('show-mobile');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    });
    
    // Add close button to sidebar if it doesn't exist
    let closeBtn = sidebar.querySelector('.sidebar-close');
    if (!closeBtn) {
        closeBtn = document.createElement('button');
        closeBtn.classList.add('sidebar-close');
        closeBtn.innerHTML = '<i class="fas fa-times"></i>';
        sidebar.prepend(closeBtn);
    }
    
    closeBtn.addEventListener('click', function() {
        sidebar.classList.remove('show-mobile');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    });
    
    // Add media query for mobile
    const mediaQuery = window.matchMedia('(max-width: 768px)');
    function handleScreenChange(e) {
        if (e.matches) {
            container.classList.add('mobile-layout');
        } else {
            container.classList.remove('mobile-layout');
            sidebar.classList.remove('show-mobile');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    // Initial check
    handleScreenChange(mediaQuery);
    
    // Add listener for changes
    mediaQuery.addEventListener('change', handleScreenChange);
    
    // Handle escape key to close menu
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('show-mobile')) {
            sidebar.classList.remove('show-mobile');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
}

// Function to handle search
document.querySelector('.search-bar input')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    
    // In a real app, this would filter data or make API calls
    console.log(`Searching for: ${searchTerm}`);
});

// Add notification functionality
const notificationIcon = document.querySelector('.notification i');
if (notificationIcon) {
    notificationIcon.addEventListener('click', function() {
        // Notification functionality
    });
}

// Call the function to set up mobile menu
createMobileMenu();