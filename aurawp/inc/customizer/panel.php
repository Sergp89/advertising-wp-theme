<?php
/**
 * AuraWP Theme Panel Registration
 * 
 * Registers the main theme options panel for Customizer
 * 
 * @package AuraWP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register main theme panel
 * 
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 * @return void
 */
function aurawp_register_theme_panel($wp_customize) {
    
    // Add main panel
    $wp_customize->add_panel('aurawp_theme_options', array(
        'title'       => esc_html__('AuraWP Theme Options', 'aurawp'),
        'priority'    => 30,
        'description' => esc_html__('Customize your AuraWP theme settings', 'aurawp')
    ));
}
add_action('customize_register', 'aurawp_register_theme_panel', 1);
