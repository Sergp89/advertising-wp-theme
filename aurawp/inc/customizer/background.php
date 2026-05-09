<?php
/**
 * AuraWP Customizer - 3D Background Section
 * 
 * 3D background settings for the WordPress Customizer
 * 
 * @package AuraWP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add 3D Background section to Customizer
 * 
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 * @return void
 */
function aurawp_customize_background_section($wp_customize) {
    
    // Add 3D Background Section
    $wp_customize->add_section('aurawp_3d_background', array(
        'title'    => esc_html__('3D Background', 'aurawp'),
        'priority' => 50,
        'panel'    => 'aurawp_theme_options'
    ));
    
    // Camera Speed (0.1-2)
    $wp_customize->add_setting('camera_speed', array(
        'default'           => 0.5,
        'sanitize_callback' => 'floatval',
        'transport'         => 'postMessage'
    ));
    
    $wp_customize->add_control('camera_speed', array(
        'label'       => esc_html__('Camera Speed', 'aurawp'),
        'description' => esc_html__('Speed of camera movement during scroll (0.1-2)', 'aurawp'),
        'section'     => 'aurawp_3d_background',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0.1,
            'max'  => 2,
            'step' => 0.1,
        )
    ));
    
    // Fog Density (0.01-0.1)
    $wp_customize->add_setting('fog_density', array(
        'default'           => 0.02,
        'sanitize_callback' => 'floatval',
        'transport'         => 'postMessage'
    ));
    
    $wp_customize->add_control('fog_density', array(
        'label'       => esc_html__('Fog Density', 'aurawp'),
        'description' => esc_html__('Density of atmospheric fog (0.01-0.1)', 'aurawp'),
        'section'     => 'aurawp_3d_background',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0.01,
            'max'  => 0.1,
            'step' => 0.01,
        )
    ));
    
    // Level of Detail (1-3)
    $wp_customize->add_setting('lod_level', array(
        'default'           => 1,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage'
    ));
    
    $wp_customize->add_control('lod_level', array(
        'label'       => esc_html__('Level of Detail', 'aurawp'),
        'description' => esc_html__('Building density (1=Low, 2=Medium, 3=High). Higher values impact performance.', 'aurawp'),
        'section'     => 'aurawp_3d_background',
        'type'        => 'select',
        'choices'     => array(
            '1' => esc_html__('Low (Better Performance)', 'aurawp'),
            '2' => esc_html__('Medium', 'aurawp'),
            '3' => esc_html__('High (Best Visuals)', 'aurawp')
        )
    ));
    
    // Enable/Disable 3D Background
    $wp_customize->add_setting('enable_3d_background', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean'
    ));
    
    $wp_customize->add_control('enable_3d_background', array(
        'label'       => esc_html__('Enable 3D Background', 'aurawp'),
        'description' => esc_html__('Show/hide the 3D cityscape background', 'aurawp'),
        'section'     => 'aurawp_3d_background',
        'type'        => 'checkbox'
    ));
    
    // Custom GLB Model Upload
    $wp_customize->add_setting('custom_glb_model', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh'
    ));
    
    $wp_customize->add_control(new WP_Customize_Upload_Control($wp_customize, 'custom_glb_model', array(
        'label'       => esc_html__('Custom 3D Model (.glb)', 'aurawp'),
        'description' => esc_html__('Upload a custom GLB model to replace the procedural city', 'aurawp'),
        'section'     => 'aurawp_3d_background',
        'mime_type'   => 'model/gltf-binary'
    )));
    
    // Fallback Background Image
    $wp_customize->add_setting('fallback_background', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'fallback_background', array(
        'label'       => esc_html__('Fallback Background Image', 'aurawp'),
        'description' => esc_html__('Static image shown when 3D is disabled or fails to load', 'aurawp'),
        'section'     => 'aurawp_3d_background'
    )));
}
add_action('customize_register', 'aurawp_customize_background_section');

/**
 * Output CSS for 3D background settings
 * 
 * @return void
 */
function aurawp_background_css() {
    $camera_speed = get_theme_mod('camera_speed', 0.5);
    $fog_density = get_theme_mod('fog_density', 0.02);
    $lod_level = get_theme_mod('lod_level', 1);
    $enable_3d = get_theme_mod('enable_3d_background', true);
    $fallback_bg = get_theme_mod('fallback_background', '');
    
    $css = ":root {
        --camera-speed: {$camera_speed};
        --fog-density: {$fog_density};
        --lod-level: {$lod_level};
    }";
    
    if (!$enable_3d && !empty($fallback_bg)) {
        $css .= "#three-canvas { display: none; }
        .fallback-bg { 
            display: block !important; 
            background-image: url({$fallback_bg});
            background-size: cover;
            background-position: center;
        }";
    }
    
    echo '<style>' . $css . '</style>';
}
add_action('wp_head', 'aurawp_background_css');
