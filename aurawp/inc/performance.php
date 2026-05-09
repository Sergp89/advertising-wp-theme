<?php
/**
 * AuraWP Performance Optimizations
 * 
 * Performance enhancements including lazy loading, critical CSS, and optimizations
 * 
 * @package AuraWP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add preload hints for critical resources
 * 
 * @return void
 */
function aurawp_preload_resources() {
    // Preload Google Fonts
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" as="style">';
    
    // Preload theme stylesheet critical portion
    echo '<link rel="preload" href="' . esc_url(get_stylesheet_uri()) . '" as="style">';
}
add_action('wp_head', 'aurawp_preload_resources', 1);

/**
 * Defer non-critical JavaScript
 * 
 * @param string $tag The script tag.
 * @param string $handle The script handle.
 * @return string Modified script tag
 */
function aurawp_defer_scripts($tag, $handle) {
    // Defer non-critical scripts
    $defer_handles = array(
        'three-js',
        'gsap-core',
        'gsap-scrolltrigger',
        'aurawp-three',
        'aurawp-animations'
    );
    
    if (in_array($handle, $defer_handles, true)) {
        return str_replace(' src', ' defer="defer" src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aurawp_defer_scripts', 10, 2);

/**
 * Add async attribute to specific scripts
 * 
 * @param string $tag The script tag.
 * @param string $handle The script handle.
 * @return string Modified script tag
 */
function aurawp_async_scripts($tag, $handle) {
    // Async analytics or non-critical scripts
    $async_handles = array();
    
    if (in_array($handle, $async_handles, true)) {
        return str_replace(' src', ' async="async" src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aurawp_async_scripts', 10, 2);

/**
 * Remove unnecessary WordPress emojis
 * 
 * @return void
 */
function aurawp_remove_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    
    // Remove emoji DNS prefetch
    add_filter('emoji_svg_url', '__return_false');
}
add_action('init', 'aurawp_remove_emojis');

/**
 * Remove WordPress version from head
 * 
 * @return void
 */
function aurawp_remove_version() {
    remove_action('wp_head', 'wp_generator');
}
add_action('init', 'aurawp_remove_version');

/**
 * Remove RSD link
 * 
 * @return void
 */
function aurawp_remove_rsd() {
    remove_action('wp_head', 'rsd_link');
}
add_action('init', 'aurawp_remove_rsd');

/**
 * Remove wlwmanifest link
 * 
 * @return void
 */
function aurawp_remove_wlwmanifest() {
    remove_action('wp_head', 'wlwmanifest_link');
}
add_action('init', 'aurawp_remove_wlwmanifest');

/**
 * Remove shortlink
 * 
 * @return void
 */
function aurawp_remove_shortlink() {
    remove_action('wp_head', 'wp_shortlink_wp_head');
}
add_action('init', 'aurawp_remove_shortlink');

/**
 * Clean up WordPress head
 * 
 * @return void
 */
function aurawp_cleanup_head() {
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_resource_hints', 2);
}
add_action('init', 'aurawp_cleanup_head');

/**
 * Add resource hints for CDN domains
 * 
 * @param array  $urls URLs to print for resource hints.
 * @param string $relation_type The relation type.
 * @return array Modified URLs
 */
function aurawp_cdn_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $cdn_urls = array(
            'https://cdnjs.cloudflare.com',
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com'
        );
        
        foreach ($cdn_urls as $url) {
            $urls[] = array(
                'href' => $url,
                'crossorigin' => 'anonymous'
            );
        }
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'aurawp_cdn_resource_hints', 10, 2);

/**
 * Lazy load images with native loading attribute
 * 
 * @param string $html The image HTML.
 * @return string Modified HTML
 */
function aurawp_lazy_load_images($html) {
    // Add loading="lazy" to images that don't have it
    if (!str_contains($html, 'loading=')) {
        $html = str_replace('<img', '<img loading="lazy"', $html);
    }
    
    return $html;
}
add_filter('wp_get_attachment_image', 'aurawp_lazy_load_images');
add_filter('post_thumbnail_html', 'aurawp_lazy_load_images');

/**
 * Optimize iframe loading
 * 
 * @param string $html The iframe HTML.
 * @return string Modified HTML
 */
function aurawp_lazy_load_iframes($html) {
    if (!str_contains($html, 'loading=')) {
        $html = str_replace('<iframe', '<iframe loading="lazy"', $html);
    }
    
    return $html;
}
add_filter('wp_video_shortcode', 'aurawp_lazy_load_iframes');

/**
 * Disable self-pingbacks
 * 
 * @param object $links Pings to send.
 * @return array Modified pings
 */
function aurawp_disable_self_ping($links) {
    foreach ($links as $l => $link) {
        if (strpos($link, get_option('home')) === 0) {
            unset($links[$l]);
        }
    }
    return $links;
}
add_action('pre_ping', 'aurawp_disable_self_ping');

/**
 * Limit post revisions
 * 
 * @return int Number of revisions to keep
 */
function aurawp_limit_revisions() {
    return 5;
}
add_filter('wp_revisions_to_keep', 'aurawp_limit_revisions');

/**
 * Set maximum embed width
 * 
 * @return int Maximum embed width
 */
function aurawp_embed_oembed_html_max_width() {
    return 800;
}
add_filter('embed_oembed_html', 'aurawp_embed_oembed_html_max_width');

/**
 * Remove query strings from static resources
 * 
 * @param string $src Resource URL.
 * @return string Modified URL
 */
function aurawp_remove_query_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'aurawp_remove_query_strings', 15, 1);
add_filter('script_loader_src', 'aurawp_remove_query_strings', 15, 1);

/**
 * Output critical CSS inline
 * 
 * @return void
 */
function aurawp_critical_css() {
    $critical_css = '
        body{font-family:var(--font-family-base);line-height:1.6;margin:0}
        .container{max-width:1440px;margin:0 auto;padding:0 1.5rem}
        #three-canvas{position:fixed;top:0;left:0;width:100%;height:100vh;z-index:-1;pointer-events:none}
        .site-header{position:sticky;top:0;z-index:1000}
        .skip-link{position:absolute;top:-40px;left:0;background:var(--color-primary);color:#fff;padding:.5rem 1rem;z-index:9999}
        .skip-link:focus{top:0}
    ';
    
    echo '<style id="aurawp-critical-css">' . $critical_css . '</style>';
}
add_action('wp_head', 'aurawp_critical_css', 0);

/**
 * Check if user prefers reduced motion
 * 
 * @return bool
 */
function aurawp_prefers_reduced_motion() {
    return get_theme_mod('reduced_motion', false) || 
           (function_exists('wp_accessibility') && wp_accessibility()->prefers_reduced_motion());
}

/**
 * Get optimized image URL
 * 
 * @param int    $attachment_id Attachment ID.
 * @param string $size Image size.
 * @return string Optimized image URL
 */
function aurawp_get_optimized_image_url($attachment_id, $size = 'medium') {
    $image_data = wp_get_attachment_image_src($attachment_id, $size);
    
    if (!$image_data) {
        return '';
    }
    
    $url = $image_data[0];
    
    // Add quality parameter for JPEG images
    if (pathinfo($url, PATHINFO_EXTENSION) === 'jpg') {
        $url = add_query_arg('quality', 85, $url);
    }
    
    return $url;
}

/**
 * Preconnect to external domains used by theme
 * 
 * @return void
 */
function aurawp_external_domains() {
    $domains = array(
        'https://cdnjs.cloudflare.com',
        'https://fonts.googleapis.com',
        'https://fonts.gstatic.com'
    );
    
    foreach ($domains as $domain) {
        echo '<link rel="preconnect" href="' . esc_url($domain) . '" crossorigin>' . "\n";
    }
}
add_action('wp_head', 'aurawp_external_domains', 2);
