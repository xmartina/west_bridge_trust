// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const mobileToggle = document.getElementById('mobile-menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-overlay');
    
    if (mobileToggle && sidebar && overlay) {
        mobileToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            sidebar.classList.toggle('show-mobile');
            overlay.classList.toggle('active');
        });
        
        overlay.addEventListener('click', function() {
            mobileToggle.classList.remove('active');
            sidebar.classList.remove('show-mobile');
            overlay.classList.remove('active');
        });
        
        // Close menu when clicking on menu items
        const menuLinks = sidebar.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    mobileToggle.classList.remove('active');
                    sidebar.classList.remove('show-mobile');
                    overlay.classList.remove('active');
                }
            });
        });
    }
    // Add event listeners to buttons
    document.querySelector('.btn-send')?.addEventListener('click', function() {
        alert('Send functionality would be implemented here');
    });

    document.querySelector('.btn-withdraw')?.addEventListener('click', function() {
        alert('Withdraw functionality would be implemented here');
    });

    document.querySelector('.btn-send-money')?.addEventListener('click', function() {
        const amount = document.querySelector('.amount-field input').value;
        const recipient = document.querySelector('.recipient-input input').value;
        alert(`Successfully sent $${amount} to ${recipient}`);
    });

    // Quick action button in balance card
    document.querySelector('.btn-quick-action')?.addEventListener('click', function() {
        alert('Transfer functionality would be implemented here');
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
            const action = this.querySelector('.action-title').textContent;
            switch(action) {
                case 'Domestic Transfer':
                    alert('Domestic Transfer form would open here');
                    break;
                case 'Wire Transfer':
                    alert('Wire Transfer form would open here');
                    break;
                case 'Deposit':
                    alert('Deposit form would open here');
                    break;
                case 'Withdrawal':
                    alert('Withdrawal form would open here');
                    break;
            }
        });
    });

    // Loan payment button
    document.querySelector('.btn-pay-loan')?.addEventListener('click', function() {
        alert('Loan payment form would open here');
    });

    // Wire transfer items click
    const wireTransferItems = document.querySelectorAll('.wire-transfer-item');
    wireTransferItems.forEach(item => {
        item.addEventListener('click', function() {
            const bankName = this.querySelector('.transfer-name').textContent;
            const reference = this.querySelector('.transfer-reference').textContent;
            alert(`Wire transfer details for ${bankName} (${reference}) would be shown here`);
        });
    });

    // View all transfers button
    document.querySelector('.btn-view-all')?.addEventListener('click', function() {
        alert('All wire transfers would be shown here');
    });

    // Card request item
    document.querySelector('.request-item')?.addEventListener('click', function() {
        alert('Card request details would be shown here');
    });

    // Message items
    const messageItems = document.querySelectorAll('.message-item');
    messageItems.forEach(item => {
        item.addEventListener('click', function() {
            if (this.classList.contains('unread')) {
                this.classList.remove('unread');
            }
            const sender = this.querySelector('.message-sender').textContent;
            alert(`Message from ${sender} would open here`);
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
            
            // Handle specific actions based on navigation item
            const action = this.querySelector('span').textContent.toLowerCase();
            switch(action) {
                case 'withdraw':
                    alert('Withdraw functionality would be implemented here');
                    break;
                case 'transfer':
                    alert('Transfer functionality would be implemented here');
                    break;
                case 'profile':
                    alert('Profile page would be loaded here');
                    break;
            }
            
            e.preventDefault();
        });
    });
});

// Add responsive menu toggle for mobile
function createMobileMenu() {
    const container = document.querySelector('.container');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.mobile-overlay');
    
    if (!container || !sidebar) return;
    
    // Create toggle button
    const toggleBtn = document.createElement('button');
    toggleBtn.classList.add('mobile-menu-toggle');
    toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
    
    // Add to DOM
    document.querySelector('.header')?.prepend(toggleBtn);
    
    // Add event listener to open menu
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.add('show-mobile');
        overlay.classList.add('active');
    });
    
    // Add event listener to close menu when clicking overlay
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('show-mobile');
        overlay.classList.remove('active');
    });
    
    // Add close button to sidebar
    const closeBtn = document.createElement('button');
    closeBtn.classList.add('sidebar-close');
    closeBtn.innerHTML = '<i class="fas fa-times"></i>';
    closeBtn.style.position = 'absolute';
    closeBtn.style.top = '10px';
    closeBtn.style.right = '10px';
    closeBtn.style.background = 'none';
    closeBtn.style.border = 'none';
    closeBtn.style.fontSize = '20px';
    closeBtn.style.color = '#666';
    closeBtn.style.cursor = 'pointer';
    
    sidebar.prepend(closeBtn);
    
    closeBtn.addEventListener('click', function() {
        sidebar.classList.remove('show-mobile');
        overlay.classList.remove('active');
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
        alert('You have no new notifications');
    });
}

// Call the function to set up mobile menu
createMobileMenu();