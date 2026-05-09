<?php
/**
 * The template for displaying the 404 page
 * 
 * @package AuraWP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main error-404-page">
    <div class="container">
        <section class="error-404 glass-card">
            <header class="page-header">
                <h1 class="page-title"><?php _e('404 - Page Not Found', 'aurawp'); ?></h1>
            </header>

            <div class="page-content">
                <p><?php _e('Oops! The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'aurawp'); ?></p>

                <div class="error-actions">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary">
                        <?php _e('Go to Homepage', 'aurawp'); ?>
                    </a>

                    <button onclick="history.back()" class="btn btn--secondary">
                        <?php _e('Go Back', 'aurawp'); ?>
                    </button>
                </div>

                <div class="error-search">
                    <h2><?php _e('Try searching instead:', 'aurawp'); ?></h2>
                    <?php get_search_form(); ?>
                </div>
            </div>
        </section>
    </div>
</main>

<?php
get_footer();
