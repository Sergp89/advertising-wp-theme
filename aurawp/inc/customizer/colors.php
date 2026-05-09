<?php
/**
 * AuraWP Customizer - Colors Section
 * 
 * Color settings for the WordPress Customizer
 * 
 * @package AuraWP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Colors section to Customizer
 * 
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 * @return void
 */
function aurawp_customize_colors_section($wp_customize) {
    
    // Add Colors Section
    $wp_customize->add_section('aurawp_colors', array(
        'title'    => esc_html__('Colors', 'aurawp'),
        'priority' => 30,
        'panel'    => 'aurawp_theme_options'
    ));
    
    // Primary Color
    $wp_customize->add_setting('color_primary', array(
        'default'           => '#6366f1',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage'
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_primary', array(
        'label'    => esc_html__('Primary Color', 'aurawp'),
        'section'  => 'aurawp_colors',
        'settings' => 'color_primary'
    )));
    
    // Secondary Color
    $wp_customize->add_setting('color_secondary', array(
        'default'           => '#ec4899',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage'
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_secondary', array(
        'label'    => esc_html__('Secondary Color', 'aurawp'),
        'section'  => 'aurawp_colors',
        'settings' => 'color_secondary'
    )));
    
    // Glass Transparency (0-100)
    $wp_customize->add_setting('glass_transparency', array(
        'default'           => 10,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage'
    ));
    
    $wp_customize->add_control('glass_transparency', array(
        'label'       => esc_html__('Glass Transparency', 'aurawp'),
        'description' => esc_html__('Opacity level for glass effects (0-100)', 'aurawp'),
        'section'     => 'aurawp_colors',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        )
    ));
    
    // Glow Intensity (0-1)
    $wp_customize->add_setting('glow_intensity', array(
        'default'           => 0.5,
        'sanitize_callback' => 'floatval',
        'transport'         => 'postMessage'
    ));
    
    $wp_customize->add_control('glow_intensity', array(
        'label'       => esc_html__('Glow Intensity', 'aurawp'),
        'description' => esc_html__('Intensity of glow effects (0-1)', 'aurawp'),
        'section'     => 'aurawp_colors',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        )
    ));
}
add_action('customize_register', 'aurawp_customize_colors_section');

/**
 * Output CSS for color settings
 * 
 * @return void
 */
function aurawp_colors_css() {
    $color_primary = get_theme_mod('color_primary', '#6366f1');
    $color_secondary = get_theme_mod('color_secondary', '#ec4899');
    $glass_transparency = get_theme_mod('glass_transparency', 10);
    $glow_intensity = get_theme_mod('glow_intensity', 0.5);
    
    $alpha = $glass_transparency / 100;
    
    $css = ":root {
        --color-primary: {$color_primary};
        --color-secondary: {$color_secondary};
        --glass-bg: rgba(255, 255, 255, {$alpha});
        --glow-intensity: {$glow_intensity};
    }";
    
    echo '<style>' . $css . '</style>';
}
add_action('wp_head', 'aurawp_colors_css');
