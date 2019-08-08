<?php
// Custom columns
$classes[] = 'post-item';
$classes[] = 'col-bg-' . Funiter_Functions::funiter_get_option('funiter_blog_bg_items', 4);
$classes[] = 'col-lg-' . Funiter_Functions::funiter_get_option('funiter_blog_lg_items', 4);
$classes[] = 'col-md-' . Funiter_Functions::funiter_get_option('funiter_blog_md_items', 4);
$classes[] = 'col-sm-' . Funiter_Functions::funiter_get_option('funiter_blog_sm_items', 6);
$classes[] = 'col-xs-' . Funiter_Functions::funiter_get_option('funiter_blog_xs_items', 6);
$classes[] = 'col-ts-' . Funiter_Functions::funiter_get_option('funiter_blog_ts_items', 12);
$classes[] = apply_filters('funiter_blog_content_class', '');
if (have_posts()) : ?>
    <?php do_action('funiter_before_blog_content'); ?>
    <div class="row blog-grid content-post auto-clear">
        <?php while (have_posts()) : the_post(); 
            ?>
            <article <?php post_class($classes); ?>>
                <div class="post-item-inner">
                    <?php
                    add_action('funiter_post_info_content', 'funiter_post_date_meta', 5);
                    remove_action('funiter_post_content', 'funiter_post_footer', 30);
                    remove_action( 'funiter_post_info_content', 'funiter_post_author', 30 );
                    
                    /**
                     * Functions hooked into funiter_post_content action
                     *
                     * @hooked funiter_post_thumbnail          - 10
                     * @hooked funiter_post_info               - 20
                     */
                    do_action('funiter_post_content'); 

                    ?>
                </div>
            </article>
        <?php endwhile;
        
        wp_reset_postdata(); ?>
    </div>
    <?php
    /**
     * Functions hooked into funiter_after_blog_content action
     *
     * @hooked funiter_paging_nav               - 10
     */
    do_action('funiter_after_blog_content'); ?>
<?php else :
    get_template_part('content', 'none');
endif;