/**
 * AuraWP Main JavaScript
 * 
 * Theme initialization, event handlers, and utility functions
 * 
 * @package AuraWP
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Theme Toggle Manager
     * Handles light/dark theme switching with localStorage persistence
     */
    const ThemeToggle = {
        /**
         * Initialize theme toggle functionality
         */
        init: function() {
            this.cacheElements();
            this.bindEvents();
            this.loadSavedTheme();
            this.checkSystemPreference();
        },

        /**
         * Cache DOM elements
         */
        cacheElements: function() {
            this.toggleButton = document.querySelector('[data-theme-toggle]');
            this.htmlElement = document.documentElement;
            this.iconLight = document.querySelector('[data-theme-icon-light]');
            this.iconDark = document.querySelector('[data-theme-icon-dark]');
        },

        /**
         * Bind event listeners
         */
        bindEvents: function() {
            if (this.toggleButton) {
                this.toggleButton.addEventListener('click', this.toggleTheme.bind(this));
                
                // Keyboard accessibility
                this.toggleButton.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.toggleTheme();
                    }
                });
            }
        },

        /**
         * Load saved theme from localStorage
         */
        loadSavedTheme: function() {
            const savedTheme = localStorage.getItem('aurawp-theme');
            
            if (savedTheme) {
                this.setTheme(savedTheme);
            }
        },

        /**
         * Check system preference for color scheme
         */
        checkSystemPreference: function() {
            const savedTheme = localStorage.getItem('aurawp-theme');
            
            // Only apply system preference if no saved theme exists
            if (!savedTheme && window.matchMedia) {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
                
                if (prefersDark.matches) {
                    this.setTheme('dark');
                } else {
                    this.setTheme('light');
                }

                // Listen for system preference changes
                prefersDark.addEventListener('change', (e) => {
                    if (!localStorage.getItem('aurawp-theme')) {
                        this.setTheme(e.matches ? 'dark' : 'light');
                    }
                });
            }
        },

        /**
         * Toggle between light and dark themes
         */
        toggleTheme: function() {
            const currentTheme = this.htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            this.setTheme(newTheme);
            this.saveTheme(newTheme);
            this.updateIcon(newTheme);
            
            // Dispatch custom event for other components to react
            window.dispatchEvent(new CustomEvent('aurawp-theme-change', { 
                detail: { theme: newTheme } 
            }));
        },

        /**
         * Set theme on HTML element
         * 
         * @param {string} theme - Theme name ('light' or 'dark')
         */
        setTheme: function(theme) {
            this.htmlElement.setAttribute('data-theme', theme);
            this.updateIcon(theme);
        },

        /**
         * Save theme preference to localStorage
         * 
         * @param {string} theme - Theme name to save
         */
        saveTheme: function(theme) {
            try {
                localStorage.setItem('aurawp-theme', theme);
            } catch (e) {
                console.warn('Unable to save theme preference:', e);
            }
        },

        /**
         * Update toggle button icon based on current theme
         * 
         * @param {string} theme - Current theme
         */
        updateIcon: function(theme) {
            if (!this.toggleButton) return;
            
            const ariaLabel = theme === 'dark' 
                ? aurawpSettings?.ariaSwitchToLight || 'Switch to light mode'
                : aurawpSettings?.ariaSwitchToDark || 'Switch to dark mode';
            
            this.toggleButton.setAttribute('aria-label', ariaLabel);
            
            // Toggle icon visibility if icons exist
            if (this.iconLight && this.iconDark) {
                if (theme === 'dark') {
                    this.iconLight.style.display = 'block';
                    this.iconDark.style.display = 'none';
                } else {
                    this.iconLight.style.display = 'none';
                    this.iconDark.style.display = 'block';
                }
            }
        }
    };

    /**
     * Smooth Scroll Handler
     * Handles smooth scrolling for anchor links
     */
    const SmoothScroll = {
        /**
         * Initialize smooth scroll
         */
        init: function() {
            this.bindEvents();
        },

        /**
         * Bind event listeners
         */
        bindEvents: function() {
            document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
                anchor.addEventListener('click', this.handleScroll.bind(this));
            });
        },

        /**
         * Handle smooth scroll click
         * 
         * @param {Event} e - Click event
         */
        handleScroll: function(e) {
            const href = e.currentTarget.getAttribute('href');
            
            // Skip if hash is empty or just '#'
            if (!href || href === '#') return;
            
            const target = document.querySelector(href);
            
            if (target) {
                e.preventDefault();
                
                const headerOffset = document.querySelector('.site-header')?.offsetHeight || 0;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                // Update URL without triggering scroll
                history.pushState(null, null, href);
            }
        }
    };

    /**
     * Mobile Navigation Handler
     * Handles mobile menu toggle and interactions
     */
    const MobileNav = {
        /**
         * Initialize mobile navigation
         */
        init: function() {
            this.cacheElements();
            this.bindEvents();
        },

        /**
         * Cache DOM elements
         */
        cacheElements: function() {
            this.menuToggle = document.querySelector('[data-menu-toggle]');
            this.navMenu = document.querySelector('[data-nav-menu]');
            this.body = document.body;
        },

        /**
         * Bind event listeners
         */
        bindEvents: function() {
            if (this.menuToggle && this.navMenu) {
                this.menuToggle.addEventListener('click', this.toggleMenu.bind(this));
                
                // Close menu on escape key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.isMenuOpen()) {
                        this.closeMenu();
                    }
                });

                // Close menu when clicking outside
                document.addEventListener('click', (e) => {
                    if (this.isMenuOpen() && 
                        !this.navMenu.contains(e.target) && 
                        !this.menuToggle.contains(e.target)) {
                        this.closeMenu();
                    }
                });
            }
        },

        /**
         * Toggle mobile menu
         */
        toggleMenu: function() {
            if (this.isMenuOpen()) {
                this.closeMenu();
            } else {
                this.openMenu();
            }
        },

        /**
         * Open mobile menu
         */
        openMenu: function() {
            if (!this.navMenu || !this.menuToggle) return;
            
            this.navMenu.classList.add('is-active');
            this.menuToggle.classList.add('is-active');
            this.menuToggle.setAttribute('aria-expanded', 'true');
            this.body.classList.add('menu-is-open');
            
            // Focus first menu item for accessibility
            const firstMenuItem = this.navMenu.querySelector('a, button');
            if (firstMenuItem) {
                setTimeout(() => firstMenuItem.focus(), 100);
            }
        },

        /**
         * Close mobile menu
         */
        closeMenu: function() {
            if (!this.navMenu || !this.menuToggle) return;
            
            this.navMenu.classList.remove('is-active');
            this.menuToggle.classList.remove('is-active');
            this.menuToggle.setAttribute('aria-expanded', 'false');
            this.body.classList.remove('menu-is-open');
            
            // Return focus to menu toggle
            this.menuToggle.focus();
        },

        /**
         * Check if menu is open
         * 
         * @return {boolean} True if menu is open
         */
        isMenuOpen: function() {
            return this.navMenu?.classList.contains('is-active') || false;
        }
    };

    /**
     * Lazy Load Handler
     * Handles lazy loading of images and 3D scene
     */
    const LazyLoad = {
        /**
         * Initialize lazy load
         */
        init: function() {
            this.observeImages();
            this.loadThreeScene();
        },

        /**
         * Setup Intersection Observer for images
         */
        observeImages: function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            this.loadImage(img);
                            observer.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: '50px 0px',
                    threshold: 0.01
                });

                document.querySelectorAll('img[data-src]').forEach((img) => {
                    imageObserver.observe(img);
                });
            } else {
                // Fallback for browsers without IntersectionObserver
                document.querySelectorAll('img[data-src]').forEach((img) => {
                    this.loadImage(img);
                });
            }
        },

        /**
         * Load a single image
         * 
         * @param {HTMLImageElement} img - Image element to load
         */
        loadImage: function(img) {
            const src = img.getAttribute('data-src');
            
            if (src) {
                img.onload = () => {
                    img.classList.add('is-loaded');
                };
                
                img.onerror = () => {
                    img.classList.add('is-error');
                    console.warn('Failed to load image:', src);
                };
                
                img.src = src;
                img.removeAttribute('data-src');
            }
        },

        /**
         * Load Three.js scene when needed
         */
        loadThreeScene: function() {
            const threeCanvas = document.getElementById('three-canvas');
            
            if (threeCanvas && window.AuraThreeJS) {
                // Use IntersectionObserver to load 3D scene when visible
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            window.AuraThreeJS.init();
                            observer.disconnect();
                        }
                    });
                }, {
                    threshold: 0
                });

                observer.observe(threeCanvas);
            }
        }
    };

    /**
     * Accessibility Enhancements
     * Various accessibility improvements
     */
    const A11y = {
        /**
         * Initialize accessibility features
         */
        init: function() {
            this.skipLinkFocus();
            this.externalLinks();
            this.formValidation();
        },

        /**
         * Handle skip link focus
         */
        skipLinkFocus: function() {
            const skipLink = document.querySelector('.skip-link');
            
            if (skipLink) {
                skipLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    const targetId = skipLink.getAttribute('href');
                    const target = document.querySelector(targetId);
                    
                    if (target) {
                        target.setAttribute('tabindex', '-1');
                        target.focus();
                    }
                });
            }
        },

        /**
         * Add external link indicators
         */
        externalLinks: function() {
            const currentDomain = window.location.hostname;
            
            document.querySelectorAll('a[href^="http"]').forEach((link) => {
                const linkDomain = new URL(link.href).hostname;
                
                if (linkDomain !== currentDomain) {
                    link.setAttribute('target', '_blank');
                    link.setAttribute('rel', 'noopener noreferrer');
                    
                    // Add visually hidden text for screen readers
                    const srText = document.createElement('span');
                    srText.className = 'sr-only';
                    srText.textContent = ' (opens in new tab)';
                    link.appendChild(srText);
                }
            });
        },

        /**
         * Enhanced form validation
         */
        formValidation: function() {
            document.querySelectorAll('form').forEach((form) => {
                form.addEventListener('submit', (e) => {
                    const invalidFields = form.querySelectorAll(':invalid');
                    
                    if (invalidFields.length > 0) {
                        e.preventDefault();
                        invalidFields[0].focus();
                        
                        // Announce error to screen readers
                        this.announceError(invalidFields[0]);
                    }
                });
            });
        },

        /**
         * Announce form error to screen readers
         * 
         * @param {HTMLElement} field - Invalid form field
         */
        announceError: function(field) {
            const errorMessage = field.validationMessage;
            
            if (errorMessage) {
                const announcement = document.createElement('div');
                announcement.setAttribute('role', 'alert');
                announcement.setAttribute('aria-live', 'polite');
                announcement.className = 'sr-only';
                announcement.textContent = errorMessage;
                
                document.body.appendChild(announcement);
                
                setTimeout(() => {
                    announcement.remove();
                }, 3000);
            }
        }
    };

    /**
     * Document Ready
     * Initialize all modules when DOM is ready
     */
    $(document).ready(function() {
        ThemeToggle.init();
        SmoothScroll.init();
        MobileNav.init();
        LazyLoad.init();
        A11y.init();

        // Remove no-js class and add js class
        document.documentElement.classList.remove('no-js');
        document.documentElement.classList.add('js');

        // Dispatch custom event for theme ready
        window.dispatchEvent(new CustomEvent('aurawp-ready'));
        
        console.log('AuraWP initialized successfully');
    });

})(jQuery);
