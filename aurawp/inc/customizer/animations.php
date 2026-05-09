<?php
/**
 * AuraWP Customizer - Animations Section
 * 
 * Animation settings for the WordPress Customizer
 * 
 * @package AuraWP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Animations section to Customizer
 * 
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 * @return void
 */
function aurawp_customize_animations_section($wp_customize) {
    
    // Add Animations Section
    $wp_customize->add_section('aurawp_animations', array(
        'title'    => esc_html__('Animations', 'aurawp'),
        'priority' => 40,
        'panel'    => 'aurawp_theme_options'
    ));
    
    // Animation Type
    $wp_customize->add_setting('animation_type', array(
        'default'           => 'fade',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'postMessage'
    ));
    
    $wp_customize->add_control('animation_type', array(
        'label'       => esc_html__('Animation Type', 'aurawp'),
        'description' => esc_html__('Choose the default animation style for sections', 'aurawp'),
        'section'     => 'aurawp_animations',
        'type'        => 'select',
        'choices'     => array(
            'fade'       => esc_html__('Fade', 'aurawp'),
            'slideUp'    => esc_html__('Slide Up', 'aurawp'),
            'slideDown'  => esc_html__('Slide Down', 'aurawp'),
            'slideLeft'  => esc_html__('Slide Left', 'aurawp'),
            'slideRight' => esc_html__('Slide Right', 'aurawp'),
            'scale'      => esc_html__('Scale', 'aurawp'),
            'rotate'     => esc_html__('Rotate', 'aurawp')
        )
    ));
    
    // Animation Duration (0.3-2s)
    $wp_customize->add_setting('animation_duration', array(
        'default'           => 0.6,
        'sanitize_callback' => 'floatval',
        'transport'         => 'postMessage'
    ));
    
    $wp_customize->add_control('animation_duration', array(
        'label'       => esc_html__('Animation Duration', 'aurawp'),
        'description' => esc_html__('Duration in seconds (0.3-2)', 'aurawp'),
        'section'     => 'aurawp_animations',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0.3,
            'max'  => 2,
            'step' => 0.1,
        )
    ));
    
    // Animation Easing
    $wp_customize->add_setting('animation_easing', array(
        'default'           => 'ease-out',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'postMessage'
    ));
    
    $wp_customize->add_control('animation_easing', array(
        'label'       => esc_html__('Easing Function', 'aurawp'),
        'description' => esc_html__('Choose the easing curve for animations', 'aurawp'),
        'section'     => 'aurawp_animations',
        'type'        => 'select',
        'choices'     => array(
            'linear'     => esc_html__('Linear', 'aurawp'),
            'ease-in'    => esc_html__('Ease In', 'aurawp'),
            'ease-out'   => esc_html__('Ease Out', 'aurawp'),
            'ease-in-out'=> esc_html__('Ease In Out', 'aurawp'),
            'back-out'   => esc_html__('Back Out (Bouncy)', 'aurawp')
        )
    ));
    
    // Stagger Delay
    $wp_customize->add_setting('animation_stagger', array(
        'default'           => 0.1,
        'sanitize_callback' => 'floatval',
        'transport'         => 'postMessage'
    ));
    
    $wp_customize->add_control('animation_stagger', array(
        'label'       => esc_html__('Stagger Delay', 'aurawp'),
        'description' => esc_html__('Delay between staggered animations in seconds', 'aurawp'),
        'section'     => 'aurawp_animations',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 0.5,
            'step' => 0.05,
        )
    ));
    
    // Enable/Disable Animations by Section
    $wp_customize->add_setting('enable_hero_animation', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean'
    ));
    
    $wp_customize->add_control('enable_hero_animation', array(
        'label'   => esc_html__('Enable Hero Animation', 'aurawp'),
        'section' => 'aurawp_animations',
        'type'    => 'checkbox'
    ));
    
    $wp_customize->add_setting('enable_cards_animation', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean'
    ));
    
    $wp_customize->add_control('enable_cards_animation', array(
        'label'   => esc_html__('Enable Cards Animation', 'aurawp'),
        'section' => 'aurawp_animations',
        'type'    => 'checkbox'
    ));
    
    $wp_customize->add_setting('enable_footer_animation', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean'
    ));
    
    $wp_customize->add_control('enable_footer_animation', array(
        'label'   => esc_html__('Enable Footer Animation', 'aurawp'),
        'section' => 'aurawp_animations',
        'type'    => 'checkbox'
    ));
    
    // Reduced Motion Toggle
    $wp_customize->add_setting('reduced_motion', array(
        'default'           => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'postMessage'
    ));
    
    $wp_customize->add_control('reduced_motion', array(
        'label'       => esc_html__('Reduce Motion', 'aurawp'),
        'description' => esc_html__('Disable complex animations for accessibility', 'aurawp'),
        'section'     => 'aurawp_animations',
        'type'        => 'checkbox'
    ));
}
add_action('customize_register', 'aurawp_customize_animations_section');

/**
 * Output CSS for animation settings
 * 
 * @return void
 */
function aurawp_animations_css() {
    $animation_type = get_theme_mod('animation_type', 'fade');
    $animation_duration = get_theme_mod('animation_duration', 0.6);
    $animation_stagger = get_theme_mod('animation_stagger', 0.1);
    
    $css = ":root {
        --animation-type: {$animation_type};
        --animation-duration: {$animation_duration}s;
        --animation-stagger: {$animation_stagger}s;
    }";
    
    echo '<style>' . $css . '</style>';
}
add_action('wp_head', 'aurawp_animations_css');
