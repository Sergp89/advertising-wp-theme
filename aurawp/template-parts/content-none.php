<?php
/**
 * The template for displaying "nothing found" content
 * 
 * @package AuraWP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="no-results not-found glass-card">
    <header class="page-header">
        <h1 class="page-title"><?php _e('Nothing Found', 'aurawp'); ?></h1>
    </header>

    <div class="page-content">
        <?php if (is_search()) : ?>
            <p><?php _e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aurawp'); ?></p>
            <?php get_search_form(); ?>
        <?php else : ?>
            <p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aurawp'); ?></p>
            <?php get_search_form(); ?>
        <?php endif; ?>
    </div>
</section>
