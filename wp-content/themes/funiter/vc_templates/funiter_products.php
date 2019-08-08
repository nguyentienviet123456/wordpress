<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 *
 * @var $atts
 * Shortcode class
 * @var $this "Funiter_Products"
 */
if ( ! class_exists( 'Funiter_Shortcode_Products' ) ) {
	class Funiter_Shortcode_Products extends Funiter_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'products';
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'funiter_products', $atts ) : $atts;
			extract( $atts );
			$css_class    = array( 'funiter-products' );
			$css_class[]  = 'style-' . $atts['product_style'];
			$css_class[]  = $atts['el_class'];
			$css_class[]  = $atts['rating'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'funiter_products', $atts );
			
			$product_size_args = array(
                'width' => 320,
                'height' => 320
            );
			
			/* Product Size */
			if ( $atts['product_image_size'] ) {
				if ( $atts['product_image_size'] == 'custom' ) {
					$thumb_width  = $atts['product_custom_thumb_width'];
					$thumb_height = $atts['product_custom_thumb_height'];
				} else {
					$product_image_size = explode( "x", $atts['product_image_size'] );
					$thumb_width        = $product_image_size[0];
					$thumb_height       = $product_image_size[1];
				}
				$product_size_args['width'] = $thumb_width;
				$product_size_args['height'] = $thumb_height;
			}
			$attribute = $atts['product_attribute'];
			add_filter( 'funiter_attribute_name',
				function() use ( $attribute ) {
					return $attribute;
				}
			);
			$products             = apply_filters( 'funiter_getProducts', $atts );
			$total_product        = $products->post_count;
			$product_item_class   = array( 'product-item', $atts['target'] );
			$product_item_class[] = 'style-' . $atts['product_style'];
			$product_list_class   = array( 'response-product' );
			$owl_settings         = '';
			if ( $atts['productsliststyle'] == 'grid' ) {
				$product_list_class[] = 'product-list-grid row auto-clear equal-container better-height ';
				$product_item_class[] = $atts['boostrap_rows_space'];
				$product_item_class[] = 'col-bg-' . $atts['boostrap_bg_items'];
				$product_item_class[] = 'col-lg-' . $atts['boostrap_lg_items'];
				$product_item_class[] = 'col-md-' . $atts['boostrap_md_items'];
				$product_item_class[] = 'col-sm-' . $atts['boostrap_sm_items'];
				$product_item_class[] = 'col-xs-' . $atts['boostrap_xs_items'];
				$product_item_class[] = 'col-ts-' . $atts['boostrap_ts_items'];
			}
			if ( $atts['productsliststyle'] == 'owl' ) {
				if ( $total_product < $atts['owl_lg_items'] ) {
					$atts['owl_loop'] = 'false';
				}
                $atts['owl_responsive_margin'] = 1200;
				$product_list_class[] = 'product-list-owl owl-slick equal-container better-height';
				$product_list_class[] = $atts['owl_navigation_style'];
				$product_list_class[] = $atts['owl_navigation_color'];
				$product_list_class[] = $atts['owl_dots_color'];
				$product_item_class[] = $atts['owl_rows_space'];
				$owl_settings         = apply_filters( 'funiter_carousel_data_attributes', 'owl_', $atts );
			}
			$id_loop = array();
			ob_start(); ?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['title'] ) : ?>
                    <h3 class="funiter-title">
                        <span class="title"><?php echo esc_html( $atts['title'] ); ?></span>
                    </h3>
				<?php endif; ?>
				<?php if ( $products->have_posts() ):
					if ( $atts['productsliststyle'] == 'grid' ): ?>
                        <div class="<?php echo esc_attr( implode( ' ', $product_list_class ) ); ?>">
							<?php while ( $products->have_posts() ) : $products->the_post(); ?>
								<?php $id_loop[] = get_the_ID(); ?>
                                <div <?php post_class( $product_item_class ); ?>>
									<?php wc_get_template( 'product-styles/content-product-style-' . $atts['product_style'] . '.php', $product_size_args ); ?>
                                </div>
							<?php endwhile; ?>
                        </div>
                        <!-- OWL Products -->
					<?php elseif ( $atts['productsliststyle'] == 'owl' ) : ?>
                        <div class="<?php echo esc_attr( implode( ' ', $product_list_class ) ); ?>" <?php echo esc_attr( $owl_settings ); ?>>
							<?php while ( $products->have_posts() ) : $products->the_post(); ?>
								<?php $id_loop[] = get_the_ID(); ?>
                                <div <?php post_class( $product_item_class ); ?>>
									<?php wc_get_template( 'product-styles/content-product-style-' . $atts['product_style'] . '.php', $product_size_args ); ?>
                                </div>
							<?php endwhile; ?>
                        </div>
					<?php endif;
				else: ?>
                    <p>
                        <strong><?php esc_html_e( 'No Product', 'funiter' ); ?></strong>
                    </p>
				<?php endif; ?>
				<?php
				$data_class = ' data-class=' . json_encode( $product_item_class ) . ' ';
				$data_thumb = ' data-thumb=' . $thumb_width . 'x' . $thumb_height . ' ';
				if ( $atts['loadmore'] == 'enable' ) : ?>
                    <div class="loadmore-product"
                         data-id="<?php echo json_encode( $id_loop ); ?>"
                         data-style="<?php echo esc_attr( $atts['product_style'] ); ?>"
                         data-type="<?php echo esc_attr( $atts['productsliststyle'] ); ?>"
                         data-loop="<?php echo htmlspecialchars( json_encode( $products->query ), ENT_QUOTES, 'UTF-8' ); ?>"
						<?php echo esc_attr( $data_thumb ); ?>
						<?php echo esc_attr( $data_class ); ?>>
                        <a href="#">
                            <span class="text"><?php echo esc_html__( 'Load more', 'funiter' ); ?></span>
                        </a>
                    </div>
				<?php endif; ?>
            </div>
			<?php
			$array_filter = array(
				'item_class'    => $product_item_class,
				'contain_class' => $product_list_class,
				'carousel'      => $owl_settings,
				'query'         => $products,
			);
			wp_reset_postdata();
			$html = ob_get_clean();
			
			return apply_filters( 'Funiter_Shortcode_Products', $html, $atts, $content, $array_filter );
		}
	}
	
	new Funiter_Shortcode_Products();
}