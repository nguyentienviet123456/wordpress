<div class="post-item-inner content-post blog-grid">
    <?php if (has_post_thumbnail()) { ?>
        <div class="post-thumb">
            <a href="<?php the_permalink(); ?>">
                <?php
                // $image_thumb = apply_filters( 'funiter_resize_image', get_post_thumbnail_id(), 440, 440, true, true );
                // echo wp_specialchars_decode( $image_thumb['img'] );
                $image_thumb = Funiter_Functions::resize_image(get_post_thumbnail_id(), null, 440, 440, true, true, false);
                echo Funiter_Functions::img_output($image_thumb);
                ?>
            </a>
        </div>
    <?php } ?>
    <div class="post-info">
        <?php
        funiter_post_date_meta();
        funiter_post_title();
        ?>
        <div class="post-content">
            <?php echo wp_trim_words(apply_filters('the_excerpt', get_the_excerpt()), 15, esc_html__('...', 'funiter')); ?>
        </div>
        <a class="post-link" href="<?php the_permalink(); ?>"><?php echo esc_html__('Read more', 'funiter') ?></a>
    </div>
</div>
