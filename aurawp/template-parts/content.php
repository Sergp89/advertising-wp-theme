<?php
/**
 * The template for displaying post content
 * 
 * @package AuraWP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('glass-card'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('aurawp-card', array(
                    'loading' => 'lazy',
                    'alt' => get_the_title()
                )); ?>
            </a>
        </div>
    <?php endif; ?>

    <header class="entry-header">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;
        ?>

        <div class="entry-meta">
            <span class="posted-on">
                <time datetime="<?php echo get_the_date('c'); ?>">
                    <?php echo get_the_date(); ?>
                </time>
            </span>
            <span class="posted-by">
                <?php _e('by', 'aurawp'); ?> <?php the_author_posts_link(); ?>
            </span>
        </div>
    </header>

    <div class="entry-content">
        <?php
        if (is_singular()) :
            the_content();
        else :
            the_excerpt();
        endif;
        ?>
    </div>

    <?php if (is_singular()) : ?>
        <footer class="entry-footer">
            <?php the_tags('<span class="tags-links">' . __('Tags: ', 'aurawp'), ', ', '</span>'); ?>
            <?php edit_post_link(__('Edit', 'aurawp'), '<span class="edit-link">', '</span>'); ?>
        </footer>
    <?php endif; ?>
</article>
