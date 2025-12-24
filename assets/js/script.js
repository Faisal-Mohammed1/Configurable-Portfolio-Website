document.addEventListener('DOMContentLoaded', () => {
    // --- Mobile Menu Toggle ---
    const mobileBtn = document.querySelector('.mobile-menu-btn');
    const navMenu = document.querySelector('.nav-menu');
    const navLinks = document.querySelectorAll('.nav-links a');

    if (mobileBtn && navMenu) {
        mobileBtn.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            // Toggle icon between bars and times (X)
            const icon = mobileBtn.querySelector('i');
            if(icon.classList.contains('fa-bars')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                 icon.classList.remove('fa-times');
                 icon.classList.add('fa-bars');
            }
        });

        // Close menu when a link is clicked
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                const icon = mobileBtn.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            });
        });
    }

    // --- Dark/Light Mode Toggle ---
    const themeToggleBtn = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;

    // Check local storage for saved theme
    const currentTheme = localStorage.getItem('theme') || 'dark';
    htmlElement.setAttribute('data-theme', currentTheme);

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            let theme = htmlElement.getAttribute('data-theme');
            // Switch theme
            if (theme === 'dark') {
                theme = 'light';
            } else {
                theme = 'dark';
            }
            // Apply and save
            htmlElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
        });
    }

    // --- Language Toggle (Basic Implementation) ---
    const langToggleBtn = document.getElementById('lang-toggle');
    
    if(langToggleBtn) {
        langToggleBtn.addEventListener('click', () => {
            const currentLang = htmlElement.getAttribute('lang');
            if(currentLang === 'en') {
                htmlElement.setAttribute('lang', 'ar');
                // CSS handles direction: rtl automatically based on lang="ar"
            } else {
                htmlElement.setAttribute('lang', 'en');
            }
        });
    }
});