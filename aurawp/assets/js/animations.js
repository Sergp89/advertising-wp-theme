/**
 * AuraWP GSAP Animations
 * 
 * Handles section animations, scroll triggers, and micro-interactions
 * using GSAP and ScrollTrigger
 * 
 * @package AuraWP
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * AuraAnimations - GSAP animation manager
     */
    window.AuraAnimations = {
        // Configuration from WordPress Customizer
        config: {
            animationType: 'fade',
            duration: 0.6,
            easing: 'ease-out',
            stagger: 0.1,
            reducedMotion: false
        },
        
        // Animation timelines
        timelines: {},
        
        /**
         * Initialize animations
         */
        init: function() {
            // Load configuration from WordPress
            this.loadConfig();
            
            // Check for reduced motion
            if (this.config.reducedMotion) {
                console.log('Reduced motion enabled - skipping complex animations');
                return;
            }
            
            // Register ScrollTrigger plugin
            gsap.registerPlugin(ScrollTrigger);
            
            // Initialize animations
            this.initSectionAnimations();
            this.initMicroInteractions();
            this.initParallaxEffects();
            
            console.log('GSAP animations initialized');
        },
        
        /**
         * Load configuration from WordPress settings
         */
        loadConfig: function() {
            this.config.animationType = aurawpSettings?.animationType || 'fade';
            this.config.duration = aurawpSettings?.animationDuration || 0.6;
            this.config.easing = aurawpSettings?.animationEasing || 'ease-out';
            this.config.stagger = aurawpSettings?.animationStagger || 0.1;
            this.config.reducedMotion = aurawpSettings?.reducedMotion || false;
        },
        
        /**
         * Get animation properties based on type
         * 
         * @param {string} type - Animation type
         * @return {object} Animation properties
         */
        getAnimationProps: function(type) {
            const props = {
                fade: {
                    from: { opacity: 0 },
                    to: { opacity: 1 }
                },
                slideUp: {
                    from: { opacity: 0, y: 50 },
                    to: { opacity: 1, y: 0 }
                },
                slideDown: {
                    from: { opacity: 0, y: -50 },
                    to: { opacity: 1, y: 0 }
                },
                slideLeft: {
                    from: { opacity: 0, x: 50 },
                    to: { opacity: 1, x: 0 }
                },
                slideRight: {
                    from: { opacity: 0, x: -50 },
                    to: { opacity: 1, x: 0 }
                },
                scale: {
                    from: { opacity: 0, scale: 0.8 },
                    to: { opacity: 1, scale: 1 }
                },
                rotate: {
                    from: { opacity: 0, rotation: -10 },
                    to: { opacity: 1, rotation: 0 }
                }
            };
            
            return props[type] || props.fade;
        },
        
        /**
         * Initialize section reveal animations
         */
        initSectionAnimations: function() {
            const sections = document.querySelectorAll('[data-animate]');
            
            sections.forEach((section, index) => {
                const animationType = section.getAttribute('data-animate') || this.config.animationType;
                const delay = section.getAttribute('data-delay') || index * this.config.stagger;
                const duration = section.getAttribute('data-duration') || this.config.duration;
                
                const props = this.getAnimationProps(animationType);
                
                // Set initial state
                gsap.set(section, { ...props.from, clearProps: 'to' });
                
                // Create scroll-triggered animation
                this.timelines[`section-${index}`] = gsap.to(section, {
                    ...props.to,
                    duration: parseFloat(duration),
                    ease: this.config.easing,
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 85%',
                        end: 'bottom 15%',
                        toggleActions: 'play none none reverse',
                        once: false
                    }
                });
            });
            
            // Animate groups of elements with stagger
            this.animateGroupedElements();
        },
        
        /**
         * Animate grouped elements (cards, list items, etc.)
         */
        animateGroupedElements: function() {
            const groups = document.querySelectorAll('[data-animate-group]');
            
            groups.forEach((group) => {
                const children = group.querySelectorAll('[data-animate-child]');
                const animationType = group.getAttribute('data-animate-group') || 'slideUp';
                
                if (children.length === 0) return;
                
                const props = this.getAnimationProps(animationType);
                
                gsap.fromTo(children,
                    { ...props.from },
                    {
                        ...props.to,
                        duration: this.config.duration,
                        ease: this.config.easing,
                        stagger: this.config.stagger,
                        scrollTrigger: {
                            trigger: group,
                            start: 'top 80%',
                            toggleActions: 'play none none reverse'
                        }
                    }
                );
            });
        },
        
        /**
         * Initialize micro-interactions
         */
        initMicroInteractions: function() {
            // Button hover effects
            this.initButtonAnimations();
            
            // Card hover effects
            this.initCardAnimations();
            
            // Icon animations
            this.initIconAnimations();
            
            // Text reveal animations
            this.initTextReveal();
        },
        
        /**
         * Initialize button animations
         */
        initButtonAnimations: function() {
            const buttons = document.querySelectorAll('.btn--animate');
            
            buttons.forEach((button) => {
                button.addEventListener('mouseenter', () => {
                    gsap.to(button, {
                        scale: 1.05,
                        duration: 0.3,
                        ease: 'back.out(1.7)'
                    });
                });
                
                button.addEventListener('mouseleave', () => {
                    gsap.to(button, {
                        scale: 1,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });
            });
        },
        
        /**
         * Initialize card animations
         */
        initCardAnimations: function() {
            const cards = document.querySelectorAll('.glass-card--interactive');
            
            cards.forEach((card) => {
                card.addEventListener('mouseenter', () => {
                    gsap.to(card, {
                        y: -8,
                        rotationX: 2,
                        rotationY: 2,
                        duration: 0.4,
                        ease: 'power2.out'
                    });
                });
                
                card.addEventListener('mouseleave', () => {
                    gsap.to(card, {
                        y: 0,
                        rotationX: 0,
                        rotationY: 0,
                        duration: 0.4,
                        ease: 'power2.out'
                    });
                });
            });
        },
        
        /**
         * Initialize icon animations
         */
        initIconAnimations: function() {
            const icons = document.querySelectorAll('[data-icon-animate]');
            
            icons.forEach((icon) => {
                const animation = icon.getAttribute('data-icon-animate');
                
                switch (animation) {
                    case 'pulse':
                        gsap.to(icon, {
                            scale: 1.2,
                            duration: 1,
                            repeat: -1,
                            yoyo: true,
                            ease: 'sine.inOut'
                        });
                        break;
                    case 'bounce':
                        gsap.to(icon, {
                            y: -5,
                            duration: 0.5,
                            repeat: -1,
                            yoyo: true,
                            ease: 'bounce.out'
                        });
                        break;
                    case 'spin':
                        gsap.to(icon, {
                            rotation: 360,
                            duration: 2,
                            repeat: -1,
                            ease: 'none'
                        });
                        break;
                }
            });
        },
        
        /**
         * Initialize text reveal animations
         */
        initTextReveal: function() {
            const textElements = document.querySelectorAll('[data-text-reveal]');
            
            textElements.forEach((element) => {
                const text = element.textContent;
                element.innerHTML = '';
                
                // Split text into characters
                const chars = text.split('');
                chars.forEach((char) => {
                    const span = document.createElement('span');
                    span.textContent = char === ' ' ? '\u00A0' : char;
                    span.style.display = 'inline-block';
                    span.style.opacity = '0';
                    element.appendChild(span);
                });
                
                // Animate characters
                gsap.to(element.children, {
                    opacity: 1,
                    y: 0,
                    duration: 0.3,
                    stagger: 0.05,
                    ease: 'back.out(1.7)',
                    scrollTrigger: {
                        trigger: element,
                        start: 'top 80%'
                    }
                });
            });
        },
        
        /**
         * Initialize parallax effects
         */
        initParallaxEffects: function() {
            const parallaxElements = document.querySelectorAll('[data-parallax]');
            
            parallaxElements.forEach((element) => {
                const speed = element.getAttribute('data-parallax') || 0.5;
                
                gsap.to(element, {
                    y: (i, target) => -ScrollTrigger.maxScroll(window) * speed,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: element,
                        start: 'top bottom',
                        end: 'bottom top',
                        scrub: true
                    }
                });
            });
        },
        
        /**
         * Refresh all animations
         */
        refresh: function() {
            ScrollTrigger.refresh();
        },
        
        /**
         * Kill all animations
         */
        kill: function() {
            Object.values(this.timelines).forEach((timeline) => {
                if (timeline.kill) {
                    timeline.kill();
                }
            });
            
            ScrollTrigger.getAll().forEach((trigger) => {
                trigger.kill();
            });
        },
        
        /**
         * Disable animations for reduced motion
         */
        disableAnimations: function() {
            this.kill();
            
            // Reset all animated elements
            document.querySelectorAll('[data-animate]').forEach((el) => {
                gsap.set(el, { clearProps: 'all' });
            });
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        // Wait for other scripts to load
        setTimeout(() => {
            window.AuraAnimations.init();
        }, 200);
    });

})(jQuery);
