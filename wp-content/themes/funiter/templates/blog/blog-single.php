<?php
do_action( 'funiter_before_single_blog_content' );
add_action( 'funiter_post_info_content', 'funiter_post_single_meta', 30 );
remove_action( 'funiter_post_info_content', 'funiter_post_author', 30 );
$funiter_blog_style = Funiter_Functions::funiter_get_option( 'funiter_blog_list_style', 'standard' );
$class_default = 'single-default';
if ( ( $funiter_blog_style == 'grid') || ( $funiter_blog_style == 'classic')){
	$class_default ='';
}
?>
    <article <?php post_class( 'post-item post-single' ); ?>>
        <div class="post-item-inner <?php echo esc_attr( $class_default ); ?>">
			<?php
			/**
			 * Functions hooked into funiter_single_post_content action
			 *
			 * @hooked funiter_post_thumbnail          - 10
			 * @hooked funiter_post_info               - 20
			 * @hooked funiter_post_single_author      - 30
			 */
			do_action( 'funiter_single_post_content' ); ?>
        </div>
		<?php do_action( 'funiter_single_post_bottom_content' ); ?>
    </article>
<?php
do_action( 'funiter_after_single_blog_content' );
add_action( 'funiter_post_info_content', 'funiter_post_author', 30 );
remove_action( 'funiter_post_info_content', 'funiter_post_single_meta', 30 );