<?php
/**
 * The main template file
 * 
 * This is the fallback template for all requests that don't match
 * a more specific template.
 * 
 * @package AuraWP
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        if (have_posts()) :
            ?>
            <div class="content-area">
                <div class="primary-content">
                    <?php
                    while (have_posts()) :
                        the_post();
                        get_template_part('template-parts/content', get_post_type());
                    endwhile;

                    the_posts_navigation();
                    ?>
                </div>

                <?php get_sidebar(); ?>
            </div>
            <?php
        else :
            get_template_part('template-parts/content', 'none');
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
