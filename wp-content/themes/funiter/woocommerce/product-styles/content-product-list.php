<?php
/**
 * Name: Product list
 * Slug: content-product-list
 **/

$args = isset($args) ? $args : null;
?>
<div class="product-inner images equal-elem">
	<?php
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @removed woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );
	?>
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
    </div>
    <div class="product-info">
		<?php
		/**
		 * woocommerce_shop_loop_item_title hook.
		 *
		 * @hooked funiter_template_loop_product_title - 10
		 */
		do_action( 'woocommerce_shop_loop_list_item_title' );
		/**
		 * woocommerce_after_shop_loop_item_title hook.
		 *
		 * @hooked woocommerce_template_loop_rating - 5
		 * @hooked woocommerce_template_loop_price - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item_title' );
		do_action( 'woocommerce_shop_loop_item_rate' );
		do_action( 'woocommerce_shop_loop_list_item_attributes' );
		do_action( 'funiter_product_short_description' );
		?>
        <div class="group-button">
        	<?php do_action( 'funiter_function_shop_loop_item_quickview' ); ?>
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
			do_action( 'funiter_function_shop_loop_item_wishlist' );
			do_action( 'funiter_function_shop_loop_item_compare' );
			?>
        </div>
    </div>
    <?php do_action( 'funiter_show_attributes' ); ?>
</div>