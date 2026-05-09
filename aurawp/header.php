<?php
/**
 * The header template
 * 
 * Displays all of the <head> section and everything up until <body>
 * 
 * @package AuraWP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary">
    <?php _e('Skip to content', 'aurawp'); ?>
</a>

<!-- 3D Background Canvas -->
<div id="three-canvas"></div>
<div class="fallback-bg"></div>

<header id="masthead" class="site-header" role="banner">
    <div class="container">
        <div class="header-inner">
            <!-- Logo / Site Branding -->
            <div class="site-branding">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title-link" rel="home">
                        <span class="site-title"><?php bloginfo('name'); ?></span>
                    </a>
                <?php endif; ?>
                
                <?php $description = get_bloginfo('description', 'display');
                if ($description || is_customize_preview()) : ?>
                    <p class="site-description"><?php echo $description; ?></p>
                <?php endif; ?>
            </div>

            <!-- Primary Navigation -->
            <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'aurawp'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'primary-menu-list',
                    'container'      => false,
                    'fallback_cb'    => 'aurawp_fallback_menu',
                ));
                ?>
            </nav>

            <!-- Header Actions -->
            <div class="header-actions">
                <!-- Theme Toggle -->
                <?php aurawp_theme_toggle(); ?>
                
                <!-- Mobile Menu Toggle -->
                <?php aurawp_mobile_menu_toggle(); ?>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="mobile-navigation" data-nav-menu>
            <?php
            wp_nav_menu(array(
                'theme_location' => 'mobile',
                'menu_id'        => 'mobile-menu',
                'menu_class'     => 'mobile-menu-list',
                'container'      => false,
                'fallback_cb'    => false,
            ));
            ?>
        </div>
    </div>
</header>
