<?php
/* Data MetaBox */
$data_meta                    = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
$funiter_metabox_enable_banner  = isset( $data_meta['funiter_metabox_enable_banner'] ) ? $data_meta['funiter_metabox_enable_banner'] : 0;
$funiter_page_header_background = isset( $data_meta['bg_banner_page'] ) ? $data_meta['bg_banner_page'] : '';
$funiter_page_heading_height    = isset( $data_meta['height_banner'] ) ? $data_meta['height_banner'] : '';
$funiter_page_margin_top        = isset( $data_meta['page_margin_top'] ) ? $data_meta['page_margin_top'] : '';
$funiter_page_margin_bottom     = isset( $data_meta['page_margin_bottom'] ) ? $data_meta['page_margin_bottom'] : '';
$css                          = '';
if ( $funiter_metabox_enable_banner != 1 ) {
	return;
}
if ( $funiter_page_header_background != "" ) {
	$css .= 'background-image:  url("' . esc_url( $funiter_page_header_background['image'] ) . '");';
	$css .= 'background-repeat: ' . esc_attr( $funiter_page_header_background['repeat'] ) . ';';
	$css .= 'background-position:   ' . esc_attr( $funiter_page_header_background['position'] ) . ';';
	$css .= 'background-attachment: ' . esc_attr( $funiter_page_header_background['attachment'] ) . ';';
	$css .= 'background-size:   ' . esc_attr( $funiter_page_header_background['size'] ) . ';';
	$css .= 'background-color:  ' . esc_attr( $funiter_page_header_background['color'] ) . ';';
}
if ( $funiter_page_heading_height != "" ) {
	$css .= 'min-height:' . $funiter_page_heading_height . 'px;';
}
if ( $funiter_page_margin_top != "" ) {
	$css .= 'margin-top:' . $funiter_page_margin_top . 'px;';
}
if ( $funiter_page_margin_bottom != "" ) {
	$css .= 'margin-bottom:' . $funiter_page_margin_bottom . 'px;';
}
?>
<!-- Banner page -->
<div class="container">
    <div class="inner-page-banner" style='<?php echo esc_attr( $css ); ?>'></div>
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
    <h1 class="page-title"><?php single_post_title(); ?></h1>
</div>
<!-- /Banner page -->