<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

global $product;
// Ensure visibility
if ( !empty( $product ) || $product->is_visible() ) {
	// Custom columns
	$funiter_woo_bg_items = Funiter_Functions::funiter_get_option( 'funiter_woo_bg_items', 4 );
	$funiter_woo_lg_items = Funiter_Functions::funiter_get_option( 'funiter_woo_lg_items', 4 );
	$funiter_woo_md_items = Funiter_Functions::funiter_get_option( 'funiter_woo_md_items', 4 );
	$funiter_woo_sm_items = Funiter_Functions::funiter_get_option( 'funiter_woo_sm_items', 6 );
	$funiter_woo_xs_items = Funiter_Functions::funiter_get_option( 'funiter_woo_xs_items', 6 );
	$funiter_woo_ts_items = Funiter_Functions::funiter_get_option( 'funiter_woo_ts_items', 12 );
	$shop_display_mode    = Funiter_Functions::funiter_get_option( 'funiter_shop_list_style', 'grid' );
	$enable_shop_mobile   = Funiter_Functions::funiter_get_option( 'enable_shop_mobile' );
	$classes[]            = 'product-item';
	if ( $shop_display_mode == 'list' ) {
		$classes[] = 'list col-sm-12';
	} else {
		$classes[] = 'col-bg-' . $funiter_woo_bg_items;
		$classes[] = 'col-lg-' . $funiter_woo_lg_items;
		$classes[] = 'col-md-' . $funiter_woo_md_items;
		$classes[] = 'col-sm-' . $funiter_woo_sm_items;
		$classes[] = 'col-xs-' . $funiter_woo_xs_items;
		$classes[] = 'col-ts-' . $funiter_woo_ts_items;
	}
	if ( $shop_display_mode == 'grid' ) {
		$classes[] = 'style-1';
	} elseif ( $shop_display_mode == 'grid-v2' ) {
		$classes[] = 'style-2';
	}
	if ( ( $enable_shop_mobile == 1 ) && ( funiter_is_mobile() ) ) {
		$classes[] = 'shop-mobile';
	}
	?>
    <li <?php post_class( $classes ); ?>>
		<?php if ( $shop_display_mode == 'list' ):
			get_template_part( 'woocommerce/product-styles/content-product', 'list' );
        elseif ( $shop_display_mode == 'grid-v2' ):
			get_template_part( 'woocommerce/product-styles/content-product', 'style-2' );
		else:
			get_template_part( 'woocommerce/product-styles/content-product', 'style-1' );
		endif; ?>
    </li>
	<?php
}