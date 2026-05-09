<?php
/**
 * The template for displaying single posts
 * 
 * @package AuraWP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main single-post-page">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="container">
            <div class="single-post-content glass-card">
                <?php
                while (have_posts()) :
                    the_post();
                    ?>

                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>

                        <div class="entry-meta">
                            <span class="posted-on">
                                <time datetime="<?php echo get_the_date('c'); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                            </span>
                            <span class="posted-by">
                                <?php _e('by', 'aurawp'); ?> <?php the_author_posts_link(); ?>
                            </span>
                            <?php if (has_category()) : ?>
                                <span class="cat-links">
                                    <?php the_category(', '); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <?php the_post_thumbnail('aurawp-hero', array(
                                    'loading' => 'eager',
                                    'alt' => get_the_title()
                                )); ?>
                            </div>
                        <?php endif; ?>
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

                    <footer class="entry-footer">
                        <?php
                        $tags_list = get_the_tag_list('', ', ');
                        if ($tags_list) {
                            echo '<div class="tags-links">' . __('Tags: ', 'aurawp') . $tags_list . '</div>';
                        }

                        edit_post_link(__('Edit', 'aurawp'), '<span class="edit-link">', '</span>');
                        ?>

                        <!-- Post Navigation -->
                        <div class="post-navigation">
                            <?php
                            the_post_navigation(array(
                                'prev_text' => '<span class="nav-subtitle">' . __('Previous:', 'aurawp') . '</span> <span class="nav-title">%title</span>',
                                'next_text' => '<span class="nav-subtitle">' . __('Next:', 'aurawp') . '</span> <span class="nav-title">%title</span>',
                            ));
                            ?>
                        </div>
                    </footer>

                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>

                <?php endwhile; ?>
            </div>
        </div>
    </article>
</main>

<?php
get_footer();
