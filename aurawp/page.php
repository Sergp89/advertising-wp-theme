<?php
/**
 * The template for displaying pages
 * 
 * @package AuraWP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main page-template">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('glass-card'); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages(array(
                        'before'      => '<div class="page-links">' . __('Pages:', 'aurawp'),
                        'after'       => '</div>',
                        'link_before' => '<span class="page-link">',
                        'link_after'  => '</span>',
                    ));
                    ?>
                </div>

                <?php if (comments_open() || get_comments_number()) : ?>
                    <footer class="entry-footer">
                        <?php edit_post_link(__('Edit', 'aurawp'), '<span class="edit-link">', '</span>'); ?>
                    </footer>

                    <?php comments_template(); ?>
                <?php endif; ?>
            </article>

        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();
