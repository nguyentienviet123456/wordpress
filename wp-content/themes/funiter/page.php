<?php get_header(); ?>
<?php
$sidebar_isset = wp_get_sidebars_widgets();
/* Data MetaBox */
$data_meta = get_post_meta( get_the_ID(), '_custom_page_side_options', true );
/* Data MetaBox */
$data_meta_banner              = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
$funiter_metabox_enable_banner = isset( $data_meta_banner['funiter_metabox_enable_banner'] ) ? $data_meta_banner['funiter_metabox_enable_banner'] : 0;
/*Default page layout*/
$funiter_page_extra_class = isset( $data_meta['page_extra_class'] ) ? $data_meta['page_extra_class'] : '';
$funiter_page_layout      = isset( $data_meta['sidebar_page_layout'] ) ? $data_meta['sidebar_page_layout'] : 'left';
$funiter_page_sidebar     = isset( $data_meta['page_sidebar'] ) ? $data_meta['page_sidebar'] : 'widget-area';
if ( isset( $sidebar_isset[ $funiter_page_sidebar ] ) && empty( $sidebar_isset[ $funiter_page_sidebar ] ) ) {
	$funiter_page_layout = 'full';
}
/*Main container class*/
$funiter_main_container_class   = array();
$funiter_main_container_class[] = $funiter_page_extra_class;
$funiter_main_container_class[] = 'main-container';
if ( $funiter_page_layout == 'full' ) {
	$funiter_main_container_class[] = 'no-sidebar';
} else {
	$funiter_main_container_class[] = $funiter_page_layout . '-sidebar';
}
$funiter_main_content_class   = array();
$funiter_main_content_class[] = 'main-content';
if ( $funiter_page_layout == 'full' ) {
	$funiter_main_content_class[] = 'col-sm-12';
} else {
	$funiter_main_content_class[] = 'col-lg-9 col-md-8 col-sm-8 col-xs-12';
}
$funiter_slidebar_class   = array();
$funiter_slidebar_class[] = 'sidebar';
if ( $funiter_page_layout != 'full' ) {
	$funiter_slidebar_class[] = 'col-lg-3 col-md-4 col-sm-4 col-xs-12';
}
?>
    <main class="site-main <?php echo esc_attr( implode( ' ', $funiter_main_container_class ) ); ?>">
		<?php
		get_template_part( 'templates-part/page', 'banner' );
		?>

        <div class="container">
			<?php if ( $funiter_metabox_enable_banner != 1 ) :
				if ( ! is_front_page() ) {
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
			endif; ?>
            <div class="row">
                <div class="<?php echo esc_attr( implode( ' ', $funiter_main_content_class ) ); ?>">
					<?php if ( $funiter_metabox_enable_banner != 1 ) : ?>
						<?php if ( class_exists( 'WooCommerce' ) ) { ?>
							<?php if ( ! is_cart() ) { ?>
                                <h2 class="page-title">
                                    <span><?php single_post_title(); ?></span>
                                </h2>
							<?php } ?>
						<?php } else { ?>
                            <h2 class="page-title">
                                <span><?php single_post_title(); ?></span>
                            </h2>
						<?php } ?>
					<?php endif;
					if ( have_posts() ) {
						while ( have_posts() ) {
							the_post();
							?>
                            <div class="page-main-content">
								<?php
								the_content();
								wp_link_pages( array(
									               'before'      => '<div class="post-pagination"><span class="title">' . esc_html__( 'Pages:', 'funiter' ) . '</span>',
									               'after'       => '</div>',
									               'link_before' => '<span>',
									               'link_after'  => '</span>',
								               ) );
								?>
                            </div>
							<?php
							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
						}
					}
					?>
                </div>
				<?php if ( $funiter_page_layout != "full" ):
					if ( is_active_sidebar( $funiter_page_sidebar ) ) : ?>
                        <div id="widget-area"
                             class="widget-area <?php echo esc_attr( implode( ' ', $funiter_slidebar_class ) ); ?>">
							<?php dynamic_sidebar( $funiter_page_sidebar ); ?>
                        </div><!-- .widget-area -->
					<?php endif;
				endif; ?>
            </div>
        </div>
		<?php
		if ( function_exists( 'funiter_noteworthy_products' ) ) {
			funiter_noteworthy_products();
		}
		?>
    </main>
<?php get_footer();