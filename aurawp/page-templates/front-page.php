<?php
/**
 * AuraWP Front Page Template
 * 
 * Custom landing page template for advertising agency
 * 
 * @package AuraWP
 * @since 1.0.0
 */

/*
Template Name: Front Page (Landing)
Template Post Type: page
*/

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main front-page">
    
    <!-- Hero Section -->
    <section class="hero-section" data-animate="<?php echo esc_attr(get_theme_mod('animation_type', 'fade')); ?>">
        <div class="container">
            <div class="hero-content glass-card glass-card--interactive">
                <h1 class="hero-title">
                    <?php echo get_theme_mod('hero_title', __('Creative Digital Agency', 'aurawp')); ?>
                </h1>
                <p class="hero-subtitle">
                    <?php echo get_theme_mod('hero_subtitle', __('We create stunning digital experiences that captivate and convert.', 'aurawp')); ?>
                </p>
                <div class="hero-actions">
                    <a href="#portfolio" class="btn btn--primary btn--glow">
                        <?php _e('View Our Work', 'aurawp'); ?>
                    </a>
                    <a href="#contact" class="btn btn--secondary">
                        <?php _e('Get in Touch', 'aurawp'); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services-section" data-animate="slideUp">
        <div class="container">
            <header class="section-header glass-card">
                <h2 class="section-title"><?php _e('Our Services', 'aurawp'); ?></h2>
                <p class="section-description"><?php _e('Comprehensive digital solutions for modern brands', 'aurawp'); ?></p>
            </header>

            <div class="services-grid">
                <?php
                $services = array(
                    array(
                        'icon' => 'palette',
                        'title' => __('Brand Strategy', 'aurawp'),
                        'desc' => __('Building memorable brands that resonate with your audience.', 'aurawp')
                    ),
                    array(
                        'icon' => 'monitor',
                        'title' => __('Web Design', 'aurawp'),
                        'desc' => __('Creating stunning, user-friendly websites that convert.', 'aurawp')
                    ),
                    array(
                        'icon' => 'rocket',
                        'title' => __('Digital Marketing', 'aurawp'),
                        'desc' => __('Data-driven campaigns that deliver measurable results.', 'aurawp')
                    ),
                    array(
                        'icon' => 'video',
                        'title' => __('Content Creation', 'aurawp'),
                        'desc' => __('Engaging content that tells your story effectively.', 'aurawp')
                    )
                );

                foreach ($services as $index => $service) :
                    ?>
                    <div class="service-card glass-card glass-card--interactive" 
                         data-animate="scale" 
                         data-delay="<?php echo esc_attr($index * 0.1); ?>">
                        <div class="service-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <?php if ($service['icon'] === 'palette') : ?>
                                    <path d="M12 19l7-7 3 3-7 7-3-3z"/>
                                    <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/>
                                    <path d="M2 2l7.586 7.586"/>
                                    <circle cx="11" cy="11" r="2"/>
                                <?php elseif ($service['icon'] === 'monitor') : ?>
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                    <line x1="8" y1="21" x2="16" y2="21"/>
                                    <line x1="12" y1="17" x2="12" y2="21"/>
                                <?php elseif ($service['icon'] === 'rocket') : ?>
                                    <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/>
                                    <path d="M12 15l-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/>
                                    <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/>
                                    <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/>
                                <?php else : ?>
                                    <polygon points="23 7 16 12 23 17 23 7"/>
                                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                                <?php endif; ?>
                            </svg>
                        </div>
                        <h3 class="service-title"><?php echo esc_html($service['title']); ?></h3>
                        <p class="service-description"><?php echo esc_html($service['desc']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="portfolio-section" data-animate="fadeIn">
        <div class="container">
            <header class="section-header glass-card">
                <h2 class="section-title"><?php _e('Featured Work', 'aurawp'); ?></h2>
                <p class="section-description"><?php _e('Showcasing our best projects', 'aurawp'); ?></p>
            </header>

            <div class="portfolio-grid">
                <?php
                $portfolio_args = array(
                    'post_type'      => 'any',
                    'posts_per_page' => 6,
                    'post_status'    => 'publish'
                );
                
                $portfolio_query = new WP_Query($portfolio_args);
                
                if ($portfolio_query->have_posts()) :
                    while ($portfolio_query->have_posts()) : $portfolio_query->the_post();
                        if (has_post_thumbnail()) :
                            ?>
                            <article class="portfolio-item glass-card glass-card--interactive" 
                                     data-animate="slideRight"
                                     data-animate-group>
                                <a href="<?php the_permalink(); ?>" class="portfolio-link">
                                    <div class="portfolio-thumbnail">
                                        <?php the_post_thumbnail('aurawp-card', array('loading' => 'lazy')); ?>
                                        <div class="portfolio-overlay">
                                            <span class="portfolio-view"><?php _e('View Project', 'aurawp'); ?></span>
                                        </div>
                                    </div>
                                    <div class="portfolio-info">
                                        <h3 class="portfolio-title"><?php the_title(); ?></h3>
                                        <?php if (has_category()) : ?>
                                            <span class="portfolio-category"><?php echo get_the_category()[0]->name; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </article>
                            <?php
                        endif;
                    endwhile;
                    wp_reset_postdata();
                else :
                    // Fallback portfolio items
                    for ($i = 1; $i <= 6; $i++) :
                        ?>
                        <article class="portfolio-item glass-card glass-card--interactive" 
                                 data-animate="slideRight"
                                 data-delay="<?php echo esc_attr(($i - 1) * 0.1); ?>">
                            <div class="portfolio-placeholder">
                                <span><?php printf(__('Project %d', 'aurawp'), $i); ?></span>
                            </div>
                        </article>
                        <?php
                    endfor;
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section" data-animate="slideUp">
        <div class="container">
            <div class="contact-wrapper glass-card">
                <div class="contact-info">
                    <h2 class="contact-title"><?php _e("Let's Create Something Amazing", 'aurawp'); ?></h2>
                    <p class="contact-text"><?php _e('Ready to start your next project? Get in touch with us.', 'aurawp'); ?></p>
                    
                    <div class="contact-details">
                        <div class="contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <span><?php echo get_option('admin_email'); ?></span>
                        </div>
                        <div class="contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            <span><?php echo get_bloginfo('name'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="contact-form-wrapper">
                    <?php echo do_shortcode('[contact-form-7 id="contact-form" title="Contact Form"]'); ?>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
