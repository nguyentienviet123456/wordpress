<?php
/***
 * Core Name: WooCommerce
 * Version: 1.0.0
 * Author: Khanh
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
include_once dirname( __FILE__ ) . '/template-functions.php';
/**
 * HOOK TEMPLATE
 */
add_action( 'init', 'product_per_page_request' );
add_action( 'init', 'product_display_mode_request' );
add_action( 'wp_loaded', 'funiter_action_wp_loaded' );
// add_action( 'funiter_show_attributes', 'funiter_show_attributes', 10 );
add_action( 'funiter_function_shop_loop_item_countdown', 'funiter_function_shop_loop_item_countdown', 10 );
add_action( 'funiter_function_shop_loop_process_variable', 'funiter_function_shop_loop_process_variable', 10 );
// add_action( 'wp', 'funiter_ajax_add_to_cart_redirect_template', 1000 );

/**
 * CART
 */
add_action( 'woocommerce_before_cart_table', 'funiter_cart_title' );

/**
 *
 * HOOK MINI CART
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'funiter_cart_link_fragment' );
add_action( 'funiter_header_mini_cart', 'funiter_header_mini_cart' );
add_action( 'funiter_header_wishlist', 'funiter_header_wishlist' );
/**
 *
 * WRAPPER CONTENT
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', 'funiter_woocommerce_before_main_content', 10 );
add_action( 'woocommerce_before_main_content', 'funiter_woocommerce_before_loop_content', 50 );
add_action( 'woocommerce_after_main_content', 'funiter_woocommerce_after_loop_content', 50 );
add_action( 'woocommerce_sidebar', 'funiter_woocommerce_sidebar', 10 );
add_action( 'woocommerce_sidebar', 'funiter_woocommerce_after_main_content', 100 );
add_action( 'woocommerce_before_shop_loop', 'funiter_woocommerce_before_shop_loop', 50 );
add_action( 'woocommerce_after_shop_loop', 'funiter_woocommerce_after_shop_loop', 10 );
add_action( 'woocommerce_before_main_content', 'woocommerce_product_archive_description', 60 );
add_action( 'template_redirect', 'funiter_track_product_view', 20 );
add_action( 'woocommerce_sidebar', 'funiter_noteworthy_products', 60 );
add_action( 'woocommerce_sidebar', 'funiter_recently_viewed_products', 60 );
add_action( 'wp_footer', 'funiter_recently_viewed_products_sliding' );
/**
 *
 * SHOP SINGLE
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_before_single_product_summary', 'funiter_before_main_content_left', 5 );
add_action( 'woocommerce_before_single_product_summary', 'funiter_after_main_content_left', 50 );
add_action( 'woocommerce_after_single_product_summary', 'funiter_woocommerce_after_single_product_summary_1', 5 );
add_action( 'woocommerce_after_single_product_summary', 'funiter_sidebar_single_product', 5 );
add_action( 'woocommerce_after_single_product_summary', 'funiter_woocommerce_before_single_product_summary_2', 5 );
add_action( 'woocommerce_after_single_product_summary', 'funiter_horizontal_single_product', 5 );

// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 5 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 9 );
add_action( 'woocommerce_single_product_summary', 'funiter_template_single_available', 10 );
// add_action( 'woocommerce_single_product_summary', 'funiter_single_show_attributes', 10 );
add_action( 'woocommerce_single_product_summary', 'funiter_single_product_brands', 10 );
//add_action( 'woocommerce_single_product_summary', 'funiter_single_show_sku', 10 );
//add_action( 'woocommerce_single_product_summary', 'funiter_single_show_tags', 55 );
//add_action( 'woocommerce_single_product_summary', 'funiter_add_categories_product', 54 );
add_action( 'woocommerce_single_product_summary', 'funiter_product_share', 7 );
/**
 *
 * SHOP CATEGORY PAGE
 */
add_action( 'woocommerce_before_main_content', 'funiter_woocommerce_category_description', 60 );
/**
 *
 * SHOP CONTROL
 */
add_action( 'funiter_control_before_content', 'funiter_shop_display_mode_tmp', 40 );
add_action( 'funiter_control_before_content', 'funiter_woocommerce_catalog_ordering', 20 );
add_action( 'funiter_control_before_content', 'woocommerce_catalog_ordering', 20 );
add_action( 'funiter_control_before_content', 'funiter_product_per_page_tmp', 30 );
add_action( 'funiter_control_after_content', 'funiter_custom_pagination', 10 );
add_action( 'funiter_control_after_content', 'woocommerce_catalog_ordering', 20 );
add_action( 'funiter_control_after_content', 'funiter_woocommerce_catalog_ordering', 20 );
add_action( 'funiter_control_after_content', 'funiter_product_per_page_tmp', 30 );
/**
 * CUSTOM SHOP CONTROL
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
add_action( 'woocommerce_before_shop_loop', 'funiter_before_shop_control', 20 );
add_action( 'woocommerce_after_shop_loop', 'funiter_after_shop_control', 50 );
add_action( 'woocommerce_archive_description', 'woocommerce_result_count', 5 );

/**
 * CUSTOM PRODUCT POST PER PAGE
 */
add_filter( 'loop_shop_per_page', 'funiter_loop_shop_per_page', 20 );
add_filter( 'woof_products_query', 'funiter_woof_products_query', 20 );
/**
 *
 * CUSTOM PRODUCT RATING
 */
add_filter( 'woocommerce_product_get_rating_html', 'funiter_product_get_rating_html', 10, 3 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 10 );
/**
 *
 * REMOVE CSS
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
//remove_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_meta', 30 );
/**
 *
 * CUSTOM PRODUCT NAME
 */
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'funiter_template_loop_product_title', 30 );
add_action( 'woocommerce_shop_loop_list_item_title', 'funiter_template_loop_product_title', 30 );
add_action( 'woocommerce_shop_loop_item_rate', 'woocommerce_template_loop_rating', 20 );
/* Short Product description */
add_action( 'funiter_product_short_description', 'funiter_product_short_description', 15 );
/**
 *
 * PRODUCT THUMBNAIL
 */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'funiter_template_loop_product_thumbnail', 10, 1 );
add_action( 'woocommerce_before_shop_loop_item_title', 'funiter_action_attributes', 20 );
add_action( 'woocommerce_shop_loop_list_item_attributes', 'funiter_action_attributes', 20 );
/**
 * REMOVE "woocommerce_template_loop_product_link_open"
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
/**
 *
 * CUSTOM FLASH
 */
add_action( 'funiter_group_flash_content', 'woocommerce_show_product_loop_sale_flash', 5 );
add_action( 'funiter_group_flash_content', 'funiter_custom_new_flash', 10 );
add_filter( 'woocommerce_sale_flash', 'funiter_custom_sale_flash' );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'funiter_woocommerce_group_flash', 10 );
add_action( 'woocommerce_before_single_product_summary', 'funiter_woocommerce_group_flash', 10 );
/**
 *
 * BREADCRUMB
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
add_action( 'woocommerce_before_main_content', 'funiter_woocommerce_breadcrumb', 20 );

// Sticky add to cart
add_action( 'single_product_addtocart', 'woocommerce_template_single_title', 8 );
add_action( 'single_product_addtocart_thumb', 'funiter_single_thumbnail_addtocart', 10 );
add_action( 'woocommerce_after_single_product', 'funiter_add_to_cart_sticky', 21 );
/**
 *
 * RELATED
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_sidebar', 'funiter_related_products', 50 );
/**
 *
 * UPSELL
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_sidebar', 'funiter_faqs_single_product', 50 );
add_action( 'woocommerce_sidebar', 'funiter_upsell_display', 50 );
/**
 *
 * CROSS SELL
 */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart', 'funiter_cross_sell_products' );

/**
 * SINGLE PRODUCT MOBILE
 */

