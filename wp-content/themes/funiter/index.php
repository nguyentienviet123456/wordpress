<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Funiter
 */
?>
<?php
get_header();
$term_id       = get_queried_object_id();
$sidebar_isset = wp_get_sidebars_widgets();
/* Blog Layout */
$video                   = '';
$funiter_blog_layout       = Funiter_Functions::funiter_get_option( 'funiter_sidebar_blog_layout', 'left' );
$funiter_blog_list_style   = Funiter_Functions::funiter_get_option( 'funiter_blog_list_style', 'standard' );
$funiter_blog_used_sidebar = Funiter_Functions::funiter_get_option( 'funiter_blog_used_sidebar', 'widget-area' );
$funiter_container_class   = array( 'main-container' );
if ( is_single() ) {
	/*Single post layout*/
	$funiter_blog_layout       = Funiter_Functions::funiter_get_option( 'funiter_sidebar_single_layout', 'left' );
	$funiter_blog_used_sidebar = Funiter_Functions::funiter_get_option( 'funiter_single_used_sidebar', 'widget-area' );
}
if ( isset( $sidebar_isset[$funiter_blog_used_sidebar] ) && empty( $sidebar_isset[$funiter_blog_used_sidebar] ) ) {
	$funiter_blog_layout = 'full';
}
if ( $funiter_blog_layout == 'full' ) {
	$funiter_container_class[] = 'no-sidebar';
} else {
	$funiter_container_class[] = $funiter_blog_layout . '-sidebar';
}
$funiter_content_class   = array();
$funiter_content_class[] = 'main-content funiter_blog';
if ( $funiter_blog_layout == 'full' ) {
	$funiter_content_class[] = 'col-sm-12';
} else {
	$funiter_content_class[] = 'col-lg-9 col-md-8 col-sm-8 col-xs-12';
}
$funiter_slidebar_class   = array();
$funiter_slidebar_class[] = 'sidebar funiter_sidebar';
if ( $funiter_blog_layout != 'full' ) {
	$funiter_slidebar_class[] = 'col-lg-3 col-md-4 col-sm-4 col-xs-12';
}
?>
<div class="<?php echo esc_attr( implode( ' ', $funiter_container_class ) ); ?>">
    <!-- POST LAYOUT -->
    <div class="container">
		<?php
		if ( !is_front_page() ) {
			$args = array(
				'container'     => 'div',
				'before'        => '',
				'after'         => '',
				'show_on_front' => true,
				'network'       => false,
				'show_title'    => true,
				'show_browse'   => false,
				'post_taxonomy' => array(),
				'labels'        => array(),
				'echo'          => true,
			);
			do_action( 'funiter_breadcrumb', $args );
		}
		?>
		<?php if ( !is_single() ) : ?>
			<?php funiter_blog_banner(); ?>
			<?php if ( is_home() ) : ?>
				<?php if ( is_front_page() ): ?>
                    <h1 class="page-title blog-title"><?php esc_html_e( 'Blog', 'funiter' ); ?></h1>
				<?php else: ?>
                    <h1 class="page-title blog-title"><?php single_post_title(); ?></h1>
				<?php endif; ?>
			<?php elseif ( is_page() ): ?>
                <h1 class="page-title blog-title"><?php single_post_title(); ?></h1>
			<?php elseif ( is_search() ): ?>
                <h1 class="page-title blog-title"><?php printf( esc_html__( 'Search Results for: %s', 'funiter' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			<?php else: ?>
                <h1 class="page-title blog-title"><?php the_archive_title( '', '' );; ?></h1>
				<?php
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			<?php endif; ?>

		<?php endif; ?>
        <div class="row">
            <div class="<?php echo esc_attr( implode( ' ', $funiter_content_class ) ); ?>">
				<?php
				if ( is_single() ) {
					while ( have_posts() ): the_post();
						funiter_set_post_views( get_the_ID() );
						get_template_part( 'templates/blog/blog', 'single' );
						/*If comments are open or we have at least one comment, load up the comment template.*/
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					endwhile;
					wp_reset_postdata();
				} else {
					get_template_part( 'templates/blog/blog', $funiter_blog_list_style ); 
				} ?>
            </div>
			<?php if ( $funiter_blog_layout != 'full' ): ?>
                <div class="<?php echo esc_attr( implode( ' ', $funiter_slidebar_class ) ); ?>">
					<?php get_sidebar(); ?>
                </div>
			<?php endif; ?>
        </div>
    </div>
	<?php
	if ( function_exists( 'funiter_noteworthy_products' ) )
		funiter_noteworthy_products();
	?>
</div>
<?php get_footer(); ?>
