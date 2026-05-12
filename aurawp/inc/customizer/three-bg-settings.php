<?php
/**
 * Three.js City Background Customizer Settings
 * Mirror's Edge style 3D background configuration options
 *
 * @package AURA_WP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Add Three.js City Background settings to WordPress Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aurawp_customize_three_bg_settings($wp_customize) {
	// Add section for Three.js background
	$wp_customize->add_section('aurawp_three_bg_section', array(
		'title'    => __('3D City Background', 'aurawp'),
		'priority' => 160,
		'panel'    => 'aurawp_theme_options',
	));

	// ===== Platforms Settings =====
	$wp_customize->add_setting('aurawp_three_platforms_enabled', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control('aurawp_three_platforms_enabled', array(
		'label'       => __('Enable Floating Platforms', 'aurawp'),
		'section'     => 'aurawp_three_bg_section',
		'type'        => 'checkbox',
		'description' => __('Display floating geometric platforms in the background.', 'aurawp'),
	));

	$wp_customize->add_setting('aurawp_three_platforms_count', array(
		'default'           => 50,
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control('aurawp_three_platforms_count', array(
		'label'       => __('Platform Count', 'aurawp'),
		'section'     => 'aurawp_three_bg_section',
		'type'        => 'range',
		'input_attrs' => array(
			'min'  => 10,
			'max'  => 100,
			'step' => 5,
		),
	));

	$wp_customize->add_setting('aurawp_three_platforms_color', array(
		'default'           => '#ffffff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aurawp_three_platforms_color', array(
		'label'   => __('Platform Color', 'aurawp'),
		'section' => 'aurawp_three_bg_section',
	)));

	$wp_customize->add_setting('aurawp_three_platforms_opacity', array(
		'default'           => 0.9,
		'sanitize_callback' => 'floatval',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control('aurawp_three_platforms_opacity', array(
		'label'       => __('Platform Opacity', 'aurawp'),
		'section'     => 'aurawp_three_bg_section',
		'type'        => 'range',
		'input_attrs' => array(
			'min'  => 0.1,
			'max'  => 1,
			'step' => 0.1,
		),
	));

	$wp_customize->add_setting('aurawp_three_bridges_enabled', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control('aurawp_three_bridges_enabled', array(
		'label'   => __('Enable Connecting Bridges', 'aurawp'),
		'section' => 'aurawp_three_bg_section',
		'type'    => 'checkbox',
	));

	$wp_customize->add_setting('aurawp_three_bridge_color', array(
		'default'           => '#ff6b00',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aurawp_three_bridge_color', array(
		'label'   => __('Bridge Color', 'aurawp'),
		'section' => 'aurawp_three_bg_section',
	)));

	// ===== Parallax Settings =====
	$wp_customize->add_setting('aurawp_three_parallax_enabled', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control('aurawp_three_parallax_enabled', array(
		'label'       => __('Enable Parallax Effect', 'aurawp'),
		'section'     => 'aurawp_three_bg_section',
		'type'        => 'checkbox',
		'description' => __('Enable multi-layer depth parallax scrolling effect.', 'aurawp'),
	));

	$wp_customize->add_setting('aurawp_three_parallax_layers', array(
		'default'           => 3,
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control('aurawp_three_parallax_layers', array(
		'label'       => __('Parallax Layers', 'aurawp'),
		'section'     => 'aurawp_three_bg_section',
		'type'        => 'range',
		'input_attrs' => array(
			'min'  => 1,
			'max'  => 5,
			'step' => 1,
		),
	));

	$wp_customize->add_setting('aurawp_three_fog_color', array(
		'default'           => '#1a1a2e',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aurawp_three_fog_color', array(
		'label'   => __('Fog/Background Color', 'aurawp'),
		'section' => 'aurawp_three_bg_section',
	)));

	// ===== Path Markers Settings =====
	$wp_customize->add_setting('aurawp_three_path_markers_enabled', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control('aurawp_three_path_markers_enabled', array(
		'label'       => __('Enable Path Markers', 'aurawp'),
		'section'     => 'aurawp_three_bg_section',
		'type'        => 'checkbox',
		'description' => __('Display glowing flow paths (Mirror\'s Edge style).', 'aurawp'),
	));

	$wp_customize->add_setting('aurawp_three_path_color', array(
		'default'           => '#ff6b00',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aurawp_three_path_color', array(
		'label'   => __('Path Marker Color', 'aurawp'),
		'section' => 'aurawp_three_bg_section',
	)));

	$wp_customize->add_setting('aurawp_three_glow_intensity', array(
		'default'           => 1.5,
		'sanitize_callback' => 'floatval',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control('aurawp_three_glow_intensity', array(
		'label'       => __('Glow Intensity', 'aurawp'),
		'section'     => 'aurawp_three_bg_section',
		'type'        => 'range',
		'input_attrs' => array(
			'min'  => 0.5,
			'max'  => 3,
			'step' => 0.1,
		),
	));

	$wp_customize->add_setting('aurawp_three_pulse_enabled', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control('aurawp_three_pulse_enabled', array(
		'label'   => __('Enable Pulse Animation', 'aurawp'),
		'section' => 'aurawp_three_bg_section',
		'type'    => 'checkbox',
	));

	// ===== Performance Settings =====
	$wp_customize->add_setting('aurawp_three_disable_mobile', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('aurawp_three_disable_mobile', array(
		'label'       => __('Disable on Mobile', 'aurawp'),
		'section'     => 'aurawp_three_bg_section',
		'type'        => 'checkbox',
		'description' => __('Completely disable 3D background on mobile devices for better performance.', 'aurawp'),
	));
}
add_action('customize_register', 'aurawp_customize_three_bg_settings');

/**
 * Output Three.js background customizer styles and config
 */
function aurawp_three_bg_customizer_preview() {
	if (!is_customize_preview()) {
		return;
	}

	$config = array(
		'platforms' => array(
			'enabled'  => get_theme_mod('aurawp_three_platforms_enabled', true),
			'count'    => get_theme_mod('aurawp_three_platforms_count', 50),
			'color'    => get_theme_mod('aurawp_three_platforms_color', '#ffffff'),
			'opacity'  => get_theme_mod('aurawp_three_platforms_opacity', 0.9),
			'bridges'  => get_theme_mod('aurawp_three_bridges_enabled', true),
			'bridgeColor' => get_theme_mod('aurawp_three_bridge_color', '#ff6b00'),
		),
		'parallax' => array(
			'enabled'  => get_theme_mod('aurawp_three_parallax_enabled', true),
			'layers'   => get_theme_mod('aurawp_three_parallax_layers', 3),
			'fogColor' => get_theme_mod('aurawp_three_fog_color', '#1a1a2e'),
		),
		'pathMarkers' => array(
			'enabled'      => get_theme_mod('aurawp_three_path_markers_enabled', true),
			'color'        => get_theme_mod('aurawp_three_path_color', '#ff6b00'),
			'glowIntensity' => get_theme_mod('aurawp_three_glow_intensity', 1.5),
			'pulseEnabled' => get_theme_mod('aurawp_three_pulse_enabled', true),
		),
		'general' => array(
			'disableMobile' => get_theme_mod('aurawp_three_disable_mobile', false),
		),
	);

	?>
	<script type="text/javascript">
		(function() {
			wp.customize.bind('ready', function() {
				var settings = <?php echo wp_json_encode($config); ?>;

				// Update preview when settings change
				wp.customize('aurawp_three_platforms_enabled', function(value) {
					value.bind(function(newValue) {
						settings.platforms.enabled = newValue;
						window.aurawpThreeUserConfig = settings;
					});
				});

				wp.customize('aurawp_three_platforms_count', function(value) {
					value.bind(function(newValue) {
						settings.platforms.count = parseInt(newValue);
						window.aurawpThreeUserConfig = settings;
					});
				});

				wp.customize('aurawp_three_platforms_color', function(value) {
					value.bind(function(newValue) {
						settings.platforms.color = newValue;
						window.aurawpThreeUserConfig = settings;
					});
				});

				wp.customize('aurawp_three_path_color', function(value) {
					value.bind(function(newValue) {
						settings.pathMarkers.color = newValue;
						window.aurawpThreeUserConfig = settings;
					});
				});

				wp.customize('aurawp_three_fog_color', function(value) {
					value.bind(function(newValue) {
						settings.parallax.fogColor = newValue;
						window.aurawpThreeUserConfig = settings;
					});
				});
			});
		})();
	</script>
	<?php
}
add_action('wp_footer', 'aurawp_three_bg_customizer_preview', 100);
