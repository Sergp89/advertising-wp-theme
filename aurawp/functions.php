<?php
/**
 * AuraWP Theme Functions and Definitions
 *
 * @package AuraWP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define theme constants
 */
define('AURAWP_VERSION', '1.0.0');
define('AURAWP_DIR', get_template_directory());
define('AURAWP_URI', get_template_directory_uri());

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since 1.0.0
 * @return void
 */
function aurawp_setup() {
    /*
     * Make theme available for translation.
     */
    load_theme_textdomain('aurawp', AURAWP_DIR . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
     * Let WordPress manage the document title.
     */
    add_theme_support('title-tag');

    /*
     * Enable support for Post Thumbnails on posts and pages.
     */
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(1200, 675, true);

    /*
     * Register custom image sizes
     */
    add_image_size('aurawp-hero', 1920, 1080, true);
    add_image_size('aurawp-card', 600, 400, true);
    add_image_size('aurawp-thumbnail', 400, 300, true);

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    /*
     * Set up the WordPress custom logo support.
     */
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    /*
     * Set up the WordPress custom background support.
     */
    add_theme_support('custom-background', array(
        'default-color' => '18181b',
    ));

    /*
     * Add support for core custom navigation menu.
     */
    register_nav_menus(array(
        'primary'   => esc_html__('Primary Menu', 'aurawp'),
        'footer'    => esc_html__('Footer Menu', 'aurawp'),
        'mobile'    => esc_html__('Mobile Menu', 'aurawp'),
    ));

    /*
     * Add support for responsive embeds.
     */
    add_theme_support('responsive-embeds');

    /*
     * Add support for full and wide align images.
     */
    add_theme_support('align-wide');

    /*
     * Add support for editor styles.
     */
    add_theme_support('editor-styles');

    /*
     * Add Custom Editor Style.
     */
    add_editor_style(AURAWP_URI . '/assets/css/editor-style.css');

    /*
     * Add support for custom color scheme.
     */
    add_theme_support('custom-colors');
}
add_action('after_setup_theme', 'aurawp_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * @since 1.0.0
 * @return void
 */
function aurawp_content_width() {
    $GLOBALS['content_width'] = apply_filters('aurawp_content_width', 1440);
}
add_action('after_setup_theme', 'aurawp_content_width', 0);

/**
 * Register widget areas.
 *
 * @since 1.0.0
 * @return void
 */
function aurawp_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'aurawp'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'aurawp'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget 1', 'aurawp'),
        'id'            => 'footer-1',
        'description'   => esc_html__('First footer widget area.', 'aurawp'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget 2', 'aurawp'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Second footer widget area.', 'aurawp'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget 3', 'aurawp'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Third footer widget area.', 'aurawp'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'aurawp_widgets_init');

/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.0
 * @return void
 */
function aurawp_scripts() {
    // Google Fonts - Preconnect
    wp_enqueue_style('google-fonts-preconnect', 'https://fonts.googleapis.com', array(), null);
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap', array(), null);

    // Main stylesheet
    wp_enqueue_style('aurawp-style', get_stylesheet_uri(), array(), AURAWP_VERSION);

    // Critical CSS (inline)
    $critical_css = '
        body{font-family:var(--font-family-base);line-height:1.6}
        .container{max-width:1440px;margin:0 auto;padding:0 1.5rem}
        #three-canvas{position:fixed;top:0;left:0;width:100%;height:100vh;z-index:-1}
    ';
    wp_add_inline_style('aurawp-style', $critical_css);

    // Three.js from CDN
    wp_enqueue_script('three-js', 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js', array(), 'r128', true);
    
    // GSAP from CDN
    wp_enqueue_script('gsap-core', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), '3.12.2', true);
    wp_enqueue_script('gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', array('gsap-core'), '3.12.2', true);

    // Theme JavaScript files
    wp_enqueue_script('aurawp-main', AURAWP_URI . '/assets/js/main.js', array('jquery'), AURAWP_VERSION, true);
    wp_enqueue_script('aurawp-three', AURAWP_URI . '/assets/js/three-city.js', array('three-js'), AURAWP_VERSION, true);
    wp_enqueue_script('aurawp-animations', AURAWP_URI . '/assets/js/animations.js', array('gsap-core', 'gsap-scrolltrigger'), AURAWP_VERSION, true);

    // Localize script with theme settings
    wp_localize_script('aurawp-main', 'aurawpSettings', array(
        'ajaxUrl'       => admin_url('admin-ajax.php'),
        'themeUri'      => AURAWP_URI,
        'nonce'         => wp_create_nonce('aurawp_nonce'),
        'reducedMotion' => get_theme_mod('reduced_motion', false),
        'cameraSpeed'   => get_theme_mod('camera_speed', 0.5),
        'fogDensity'    => get_theme_mod('fog_density', 0.02),
        'lodLevel'      => get_theme_mod('lod_level', 1),
    ));

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'aurawp_scripts');

/**
 * Enqueue admin scripts and styles.
 *
 * @since 1.0.0
 * @param string $hook The current admin page.
 * @return void
 */
function aurawp_admin_scripts($hook) {
    // Only load on customizer page
    if ('customize.php' !== $hook) {
        return;
    }

    wp_enqueue_script('aurawp-customizer', AURAWP_URI . '/assets/js/customizer.js', array('customize-preview'), AURAWP_VERSION, true);
}
add_action('admin_enqueue_scripts', 'aurawp_admin_scripts');

/**
 * Add no-js class to html element
 *
 * @since 1.0.0
 * @param array $classes The existing classes.
 * @return array Modified classes
 */
function aurawp_no_js_class($classes) {
    $classes[] = 'no-js';
    return $classes;
}
add_filter('body_class', 'aurawp_no_js_class');

/**
 * Remove no-js class when JS is enabled
 *
 * @since 1.0.0
 * @return void
 */
function aurawp_remove_no_js() {
    ?>
    <script>
        document.documentElement.classList.remove('no-js');
        document.documentElement.classList.add('js');
    </script>
    <?php
}
add_action('wp_head', 'aurawp_remove_no_js', 1);

/**
 * Add preconnect hints for performance
 *
 * @since 1.0.0
 * @param array  $urls          An array of resource URLs to preconnect to.
 * @param string $relation_type The relation type.
 * @return array Modified URLs
 */
function aurawp_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
        );
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
        $urls[] = array(
            'href' => 'https://cdnjs.cloudflare.com',
            'crossorigin' => 'anonymous',
        );
    }
    return $urls;
}
add_filter('wp_resource_hints', 'aurawp_resource_hints', 10, 2);

/**
 * Include additional theme files
 */
require_once AURAWP_DIR . '/inc/template-tags.php';
require_once AURAWP_DIR . '/inc/performance.php';

// Customizer files - load only in admin/customizer context
if (is_admin()) {
    add_action('customize_register', 'aurawp_load_customizer_files', 1);
}

/**
 * Load customizer configuration files
 * 
 * @param WP_Customize_Manager $wp_customize The Customizer manager object.
 * @return void
 */
function aurawp_load_customizer_files($wp_customize) {
    require_once AURAWP_DIR . '/inc/customizer/panel.php';
    require_once AURAWP_DIR . '/inc/customizer/colors.php';
    require_once AURAWP_DIR . '/inc/customizer/animations.php';
    require_once AURAWP_DIR . '/inc/customizer/background.php';
    require_once AURAWP_DIR . '/inc/customizer/export-import.php';
}
