<?php
$funiter_blog_used_sidebar = Funiter_Functions::funiter_get_option( 'funiter_blog_used_sidebar', 'widget-area' );
if ( is_single() ) {
	$funiter_blog_used_sidebar = Funiter_Functions::funiter_get_option( 'funiter_single_used_sidebar', 'widget-area' );
}
?>
<?php if ( is_active_sidebar( $funiter_blog_used_sidebar ) ) : ?>
    <div id="widget-area" class="widget-area sidebar-blog">
		<?php dynamic_sidebar( $funiter_blog_used_sidebar ); ?>
    </div><!-- .widget-area -->
<?php endif; ?>