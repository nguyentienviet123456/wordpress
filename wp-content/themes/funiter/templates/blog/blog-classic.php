<?php
if (have_posts()) : ?>
    <?php do_action('funiter_before_blog_content'); ?>
    <div class="content-post">
        <?php while (have_posts()) : the_post();
            ?>
            <article <?php post_class('post-item'); ?>>
                <div class="post-item-inner">

                    <?php
                    /**
                     * Functions hooked into funiter_post_content action
                     *
                     * @hooked funiter_post_thumbnail          - 10
                     * @hooked funiter_post_info               - 20
                     */
                    add_action('funiter_post_info_content', 'funiter_post_author', 5);
                    do_action('funiter_post_content'); ?>
                </div>
            </article>
            <?php
        endwhile;
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
endif; ?>