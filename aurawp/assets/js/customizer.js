/**
 * AuraWP Customizer JavaScript
 * 
 * Live preview functionality for WordPress Customizer
 * 
 * @package AuraWP
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Handle color changes
    wp.customize('color_primary', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--color-primary', to);
        });
    });

    wp.customize('color_secondary', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--color-secondary', to);
        });
    });

    wp.customize('glass_transparency', function(value) {
        value.bind(function(to) {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const alpha = to / 100;
            
            if (isDark) {
                document.documentElement.style.setProperty('--glass-bg', `rgba(0, 0, 0, ${alpha})`);
            } else {
                document.documentElement.style.setProperty('--glass-bg', `rgba(255, 255, 255, ${alpha})`);
            }
        });
    });

    wp.customize('glow_intensity', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--glow-intensity', to);
        });
    });

    // Handle animation settings
    wp.customize('animation_type', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--animation-type', to);
        });
    });

    wp.customize('animation_duration', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--animation-duration', to + 's');
        });
    });

    wp.customize('animation_stagger', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--animation-stagger', to + 's');
        });
    });

    // Handle 3D background settings
    wp.customize('camera_speed', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--camera-speed', to);
            
            if (window.AuraThreeJS) {
                window.AuraThreeJS.settings.cameraSpeed = to;
            }
        });
    });

    wp.customize('fog_density', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--fog-density', to);
            
            if (window.AuraThreeJS && window.AuraThreeJS.scene) {
                window.AuraThreeJS.scene.fog.density = to;
            }
        });
    });

    wp.customize('lod_level', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--lod-level', to);
            
            // Note: LOD changes require scene rebuild
            console.log('LOD level changed to:', to, '- Refresh to apply');
        });
    });

    wp.customize('reduced_motion', function(value) {
        value.bind(function(to) {
            if (to) {
                if (window.AuraAnimations) {
                    window.AuraAnimations.disableAnimations();
                }
                if (window.AuraThreeJS) {
                    window.AuraThreeJS.destroy();
                }
            }
        });
    });

})(jQuery);
