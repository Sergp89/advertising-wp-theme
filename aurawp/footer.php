<?php
/**
 * The footer template
 * 
 * Displays all of the footer section and everything after </main>
 * 
 * @package AuraWP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="container">
        <!-- Footer Widgets -->
        <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3')) : ?>
            <div class="footer-widgets">
                <div class="footer-widget-column">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
                <div class="footer-widget-column">
                    <?php dynamic_sidebar('footer-2'); ?>
                </div>
                <div class="footer-widget-column">
                    <?php dynamic_sidebar('footer-3'); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-info">
                <p class="copyright">
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 
                    <?php _e('All rights reserved.', 'aurawp'); ?>
                </p>
            </div>

            <!-- Footer Menu -->
            <?php if (has_nav_menu('footer')) : ?>
                <nav class="footer-navigation" aria-label="<?php esc_attr_e('Footer Menu', 'aurawp'); ?>">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_id'        => 'footer-menu',
                        'menu_class'     => 'footer-menu-list',
                        'depth'          => 1,
                        'container'      => false,
                    ));
                    ?>
                </nav>
            <?php endif; ?>

            <!-- Social Links (if available) -->
            <?php if (function_exists('the_social_links')) : ?>
                <div class="social-links">
                    <?php the_social_links(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
