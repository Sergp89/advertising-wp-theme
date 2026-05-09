<?php
/**
 * AuraWP Template Tags
 * 
 * Custom template tags for the theme
 * 
 * @package AuraWP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display theme toggle button
 * 
 * @param array $args Optional arguments for the toggle button.
 * @return void
 */
function aurawp_theme_toggle($args = array()) {
    $defaults = array(
        'before' => '<div class="theme-toggle">',
        'after'  => '</div>',
        'aria_label_light' => __('Switch to light mode', 'aurawp'),
        'aria_label_dark'  => __('Switch to dark mode', 'aurawp')
    );
    
    $args = wp_parse_args($args, $defaults);
    
    echo $args['before'];
    ?>
    <button 
        type="button" 
        data-theme-toggle 
        class="theme-toggle__button"
        aria-label="<?php echo esc_attr($args['aria_label_dark']); ?>"
    >
        <svg data-theme-icon-light xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5"></circle>
            <line x1="12" y1="1" x2="12" y2="3"></line>
            <line x1="12" y1="21" x2="12" y2="23"></line>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
            <line x1="1" y1="12" x2="3" y2="12"></line>
            <line x1="21" y1="12" x2="23" y2="12"></line>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
        </svg>
        <svg data-theme-icon-dark style="display: none;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>
    </button>
    <?php
    echo $args['after'];
}

/**
 * Display mobile menu toggle button
 * 
 * @param array $args Optional arguments for the menu toggle.
 * @return void
 */
function aurawp_mobile_menu_toggle($args = array()) {
    $defaults = array(
        'before'       => '<button type="button" data-menu-toggle class="mobile-menu-toggle" aria-expanded="false" aria-controls="primary-menu">',
        'after'        => '</button>',
        'icon_open'    => '',
        'icon_closed'  => ''
    );
    
    $args = wp_parse_args($args, $defaults);
    
    if (empty($args['icon_open'])) {
        $args['icon_open'] = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>';
    }
    
    if (empty($args['icon_closed'])) {
        $args['icon_closed'] = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
    }
    
    echo $args['before'];
    ?>
    <span class="mobile-menu-toggle__icon"><?php echo $args['icon_open']; ?></span>
    <span class="mobile-menu-toggle__icon--close"><?php echo $args['icon_closed']; ?></span>
    <span class="sr-only"><?php _e('Toggle menu', 'aurawp'); ?></span>
    <?php
    echo $args['after'];
}

/**
 * Display post thumbnail with lazy loading
 * 
 * @param string $size Image size.
 * @param array  $args Optional arguments.
 * @return void
 */
function aurawp_post_thumbnail($size = 'post-thumbnail', $args = array()) {
    if (!has_post_thumbnail()) {
        return;
    }
    
    $defaults = array(
        'class' => '',
        'lazy'  => true
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $thumbnail_id = get_post_thumbnail_id();
    $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, $size)[0];
    $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
    
    if ($args['lazy']) {
        ?>
        <img 
            data-src="<?php echo esc_url($thumbnail_url); ?>"
            alt="<?php echo esc_attr($alt_text); ?>"
            class="<?php echo esc_attr($args['class']); ?>"
            loading="lazy"
        />
        <?php
    } else {
        the_post_thumbnail($size, array(
            'class' => $args['class'],
            'alt'   => $alt_text
        ));
    }
}

/**
 * Display section with animation attributes
 * 
 * @param string $section_class CSS class for the section.
 * @param string $animation_type Type of animation.
 * @param array  $args Optional arguments.
 * @return void
 */
function aurawp_animated_section($section_class = '', $animation_type = '', $args = array()) {
    $defaults = array(
        'delay'     => '',
        'duration'  => '',
        'group'     => false,
        'parallax'  => false,
        'parallax_speed' => 0.5
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $classes = array('animated-section', $section_class);
    $attributes = array();
    
    // Add animation type
    if (!empty($animation_type)) {
        $attributes[] = 'data-animate="' . esc_attr($animation_type) . '"';
    }
    
    // Add delay
    if (!empty($args['delay'])) {
        $attributes[] = 'data-delay="' . esc_attr($args['delay']) . '"';
    }
    
    // Add duration
    if (!empty($args['duration'])) {
        $attributes[] = 'data-duration="' . esc_attr($args['duration']) . '"';
    }
    
    // Add group attribute
    if ($args['group']) {
        $attributes[] = 'data-animate-group="' . esc_attr($animation_type ?: 'slideUp') . '"';
    }
    
    // Add parallax
    if ($args['parallax']) {
        $attributes[] = 'data-parallax="' . esc_attr($args['parallax_speed']) . '"';
    }
    
    $class_string = implode(' ', array_filter($classes));
    $attr_string = implode(' ', $attributes);
    
    echo '<section class="' . esc_attr($class_string) . '" ' . $attr_string . '>';
}

/**
 * Close animated section
 * 
 * @return void
 */
function aurawp_close_animated_section() {
    echo '</section>';
}

/**
 * Display glass card
 * 
 * @param array $args Card arguments.
 * @return void
 */
function aurawp_glass_card($args = array()) {
    $defaults = array(
        'title'         => '',
        'content'       => '',
        'image'         => '',
        'image_alt'     => '',
        'link'          => '',
        'link_text'     => '',
        'badge'         => '',
        'badge_type'    => '',
        'glow'          => false,
        'interactive'   => true,
        'featured'      => false,
        'child'         => false
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $classes = array('glass-card');
    
    if ($args['interactive']) {
        $classes[] = 'glass-card--interactive';
    }
    
    if ($args['glow']) {
        $classes[] = 'glass-card--glow-' . ($args['glow'] === 'secondary' ? 'secondary' : 'primary');
    }
    
    if ($args['featured']) {
        $classes[] = 'glass-card--featured';
    }
    
    if ($args['child']) {
        $classes[] = 'glass-card__child';
        echo '<div data-animate-child class="' . esc_attr(implode(' ', $classes)) . '">';
    } else {
        echo '<div class="' . esc_attr(implode(' ', $classes)) . '">';
    }
    
    // Image
    if (!empty($args['image'])) {
        ?>
        <img src="<?php echo esc_url($args['image']); ?>" alt="<?php echo esc_attr($args['image_alt']); ?>" class="glass-card__image" loading="lazy" />
        <?php
    }
    
    // Badge
    if (!empty($args['badge'])) {
        $badge_class = !empty($args['badge_type']) ? 'glass-card__badge--' . esc_attr($args['badge_type']) : '';
        ?>
        <span class="glass-card__badge <?php echo esc_attr($badge_class); ?>"><?php echo esc_html($args['badge']); ?></span>
        <?php
    }
    
    // Title
    if (!empty($args['title'])) {
        ?>
        <h3 class="glass-card__title"><?php echo esc_html($args['title']); ?></h3>
        <?php
    }
    
    // Content
    if (!empty($args['content'])) {
        ?>
        <div class="glass-card__content"><?php echo wp_kses_post($args['content']); ?></div>
        <?php
    }
    
    // Link
    if (!empty($args['link']) && !empty($args['link_text'])) {
        ?>
        <a href="<?php echo esc_url($args['link']); ?>" class="btn btn--primary"><?php echo esc_html($args['link_text']); ?></a>
        <?php
    }
    
    echo '</div>';
}

/**
 * Get theme option with fallback
 * 
 * @param string $option Option name.
 * @param mixed  $default Default value.
 * @return mixed
 */
function aurawp_get_option($option, $default = '') {
    return get_theme_mod($option, $default);
}

/**
 * Check if animations are enabled for a section
 * 
 * @param string $section Section name.
 * @return bool
 */
function aurawp_is_animation_enabled($section) {
    $enabled_sections = array(
        'hero'   => get_theme_mod('enable_hero_animation', true),
        'cards'  => get_theme_mod('enable_cards_animation', true),
        'footer' => get_theme_mod('enable_footer_animation', true)
    );
    
    return isset($enabled_sections[$section]) ? $enabled_sections[$section] : true;
}
