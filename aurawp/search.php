<?php
/**
 * The template for displaying search results pages
 * 
 * @package AuraWP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main search-results-page">
    <div class="container">
        <header class="page-header glass-card">
            <h1 class="page-title">
                <?php
                printf(
                    esc_html__('Search Results for: %s', 'aurawp'),
                    '<span>' . get_search_query() . '</span>'
                );
                ?>
            </h1>
        </header>

        <?php if (have_posts()) : ?>
            <div class="search-results-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/content', 'search'); ?>
                <?php endwhile; ?>
            </div>

            <?php the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => __('Previous', 'aurawp'),
                'next_text' => __('Next', 'aurawp'),
            )); ?>

        <?php else : ?>
            <?php get_template_part('template-parts/content', 'none'); ?>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
