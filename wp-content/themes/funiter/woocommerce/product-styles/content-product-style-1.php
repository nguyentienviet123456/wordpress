<?php
/**
 * Name: Product style 1
 * Slug: content-product-style-1
 **/

$args = isset($args) ? $args : null;

?>
<?php
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 10 ); ?>
    <div class="product-inner images">
        <div class="product-thumb">
			<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook.
			 *
			 * @hooked funiter_woocommerce_group_flash - 10
			 * @hooked funiter_template_loop_product_thumbnail - 10
			 * @hooked funiter_action_attributes - 20
			 */
			do_action( 'woocommerce_before_shop_loop_item_title', $args );
			?>
			<div class="group-button">
				<?php if ( class_exists( 'YITH_WCQV_Frontend' ) ) { ?>
                    <div class="quickview-item">
						<?php
						do_action( 'funiter_function_shop_loop_item_quickview' );
						?>
                    </div>
				<?php } ?>
	            <div class="add-to-cart">
					<?php
					/**
					 * woocommerce_after_shop_loop_item hook.
					 *
					 * @removed woocommerce_template_loop_product_link_close - 5
					 * @hooked woocommerce_template_loop_add_to_cart - 10
					 */
					do_action( 'woocommerce_after_shop_loop_item' );
					?>
	            </div>
				<?php
				do_action( 'funiter_function_shop_loop_item_compare' );
				?>
	        </div>
        </div>
        <div class="product-info">
			<?php
			/**
			 * woocommerce_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_rating - 10
			 * @hooked funiter_add_categories_product - 20
			 * @hooked funiter_template_loop_product_title - 30
			 */
			do_action( 'funiter_function_shop_loop_item_wishlist' );
			do_action( 'woocommerce_shop_loop_item_title' );
			/**
			 * woocommerce_after_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			
			do_action( 'woocommerce_shop_loop_item_rate' );
			?>
        </div>
        
    </div>
<?php
add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 10 );
