<?php
/**
 * WooCommerce Template
 *
 * Functions for the templating system.
 *
 * @author   Thuy
 * @category Core
 * @package  Funiter_Woo_Functions
 * @version  1.0.0
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! function_exists( 'funiter_action_wp_loaded' ) ) {
	function funiter_action_wp_loaded() {
		/* QUICK VIEW */
		if ( class_exists( 'YITH_WCQV_Frontend' ) ) {
			// Class frontend
			$enable           = get_option( 'yith-wcqv-enable' ) == 'yes' ? true : false;
			$enable_on_mobile = get_option( 'yith-wcqv-enable-mobile' ) == 'yes' ? true : false;
			// Class frontend
			if ( ( ! wp_is_mobile() && $enable ) || ( wp_is_mobile() && $enable_on_mobile && $enable ) ) {
				remove_action( 'woocommerce_after_shop_loop_item', array(
					YITH_WCQV_Frontend::get_instance(),
					'yith_add_quick_view_button'
				), 15 );
				add_action( 'funiter_function_shop_loop_item_quickview', array(
					YITH_WCQV_Frontend::get_instance(),
					'yith_add_quick_view_button'
				), 5 );
			}
		}
		/* WISH LIST */
		if ( defined( 'YITH_WCWL' ) ) {
			add_action( 'funiter_function_shop_loop_item_wishlist', function() {
				echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
			}, 1 );
		}
		/* COMPARE */
		if ( class_exists( 'YITH_Woocompare' ) && get_option( 'yith_woocompare_compare_button_in_products_list' ) == 'yes' ) {
			global $yith_woocompare;
			$is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
			if ( $yith_woocompare->is_frontend() || $is_ajax ) {
				if ( $is_ajax ) {
					if ( ! class_exists( 'YITH_Woocompare_Frontend' ) && file_exists( YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php' ) ) {
						require_once YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php';
					}
					$yith_woocompare->obj = new YITH_Woocompare_Frontend();
				}
				/* Remove button */
				remove_action( 'woocommerce_after_shop_loop_item', array(
					$yith_woocompare->obj,
					'add_compare_link'
				), 20 );
				/* Add compare button */
				if ( ! function_exists( 'funiter_wc_loop_product_compare_btn' ) ) {
					function funiter_wc_loop_product_compare_btn() {
						if ( shortcode_exists( 'yith_compare_button' ) ) {
							echo do_shortcode( '[yith_compare_button product_id="' . get_the_ID() . '"]' );
						} else {
							if ( class_exists( 'YITH_Woocompare_Frontend' ) ) {
								echo do_shortcode( '[yith_compare_button product_id="' . get_the_ID() . '"]' );
							}
						}
					}
				}
				add_action( 'funiter_function_shop_loop_item_compare', 'funiter_wc_loop_product_compare_btn', 1 );
				add_action( 'woocommerce_after_add_to_cart_button', 'funiter_wc_loop_product_compare_btn', 31 );
			}
		}
	}
}
/* SINGLE PRODUCT */
if ( ! function_exists( 'funiter_before_main_content_left' ) ) {
	function funiter_before_main_content_left() {
		global $product;
		$class          = 'no-gallery';
		$attachment_ids = $product->get_gallery_image_ids();
		if ( $attachment_ids && has_post_thumbnail() ) {
			$class = 'has-gallery';
		}
		echo '<div class="main-contain-summary"><div class="contain-left ' . esc_attr( $class ) . '"><div class="single-left">';
	}
}
if ( ! function_exists( 'funiter_after_main_content_left' ) ) {
	function funiter_after_main_content_left() {
		echo '</div>';
	}
}
if ( ! function_exists( 'funiter_woocommerce_after_single_product_summary_1' ) ) {
	function funiter_woocommerce_after_single_product_summary_1() {
		echo '</div>';
	}
}
if ( ! function_exists( 'funiter_woocommerce_before_single_product_summary_2' ) ) {
	function funiter_woocommerce_before_single_product_summary_2() {
		echo '</div>';
	}
}
if ( ! function_exists( 'funiter_woocommerce_before_shop_loop' ) ) {
	function funiter_woocommerce_before_shop_loop() {
		echo '<div class="row auto-clear equal-container better-height funiter-products">';
	}
}
if ( ! function_exists( 'funiter_woocommerce_after_shop_loop' ) ) {
	function funiter_woocommerce_after_shop_loop() {
		echo '</div>';
	}
}
/* GALLERY PRODUCT */
if ( ! function_exists( 'funiter_gallery_product_thumbnail' ) ) {
	function funiter_gallery_product_thumbnail() {
		global $post, $product;
		// GET SIZE IMAGE SETTING
		$width  = 500;
		$height = 500;
		$crop   = true;
		$size   = wc_get_image_size( 'shop_catalog' );
		if ( $size ) {
			$width  = $size['width'];
			$height = $size['height'];
			if ( ! $size['crop'] ) {
				$crop = false;
			}
		}
		$html           = '';
		$html_thumb     = '';
		$attachment_ids = $product->get_gallery_image_ids();
		$width          = apply_filters( 'funiter_shop_product_thumb_width', $width );
		$height         = apply_filters( 'funiter_shop_product_thumb_height', $height );
		/* primary image */
		$image_thumb       = apply_filters( 'funiter_resize_image', get_post_thumbnail_id( $product->get_id() ), $width, $height, $crop, true );
		$thumbnail_primary = apply_filters( 'funiter_resize_image', get_post_thumbnail_id( $product->get_id() ), 136, 130, $crop, true );
		$html              .= '<figure class="product-gallery-image">';
		$html              .= $image_thumb['img'];
		$html              .= '</figure>';
		$html_thumb        .= '<figure>' . $thumbnail_primary['img'] . '</figure>';
		/* thumbnail image */
		if ( $attachment_ids && has_post_thumbnail() ) {
			foreach ( $attachment_ids as $attachment_id ) {
				$gallery_thumb   = apply_filters( 'funiter_resize_image', $attachment_id, $width, $height, $crop, true );
				$thumbnail_image = apply_filters( 'funiter_resize_image', $attachment_id, 136, 130, $crop, true );
				$html            .= '<figure class="product-gallery-image">';
				$html            .= $gallery_thumb['img'];
				$html            .= '</figure>';
				$html_thumb      .= '<figure>' . $thumbnail_image['img'] . '</figure>';
			}
		}
		?>
        <div class="product-gallery">
            <div class="product-gallery-slick">
				<?php echo wp_specialchars_decode( $html ); ?>
            </div>
            <div class="gallery-dots">
				<?php echo wp_specialchars_decode( $html_thumb ); ?>
            </div>
        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_single_thumbnail_addtocart' ) ) {
	function funiter_single_thumbnail_addtocart() {
		global $product;
		// GET SIZE IMAGE SETTING
		$width  = 500;
		$height = 500;
		$crop   = true;
		$size   = wc_get_image_size( 'shop_catalog' );
		if ( $size ) {
			$width  = $size['width'];
			$height = $size['height'];
			if ( ! $size['crop'] ) {
				$crop = false;
			}
		}
		$data_src                = '';
		$attachment_ids          = $product->get_gallery_image_ids();
		$gallery_class_img       = $class_img = array( 'img-responsive' );
		$thumb_gallery_class_img = $thumb_class_img = array( 'thumb-link' );
		$width                   = apply_filters( 'funiter_shop_product_thumb_width', $width );
		$height                  = apply_filters( 'funiter_shop_product_thumb_height', $height );
		$image_thumb             = apply_filters( 'funiter_resize_image', get_post_thumbnail_id( $product->get_id() ), $width, $height, $crop, true );
		$image_url               = $image_thumb['url'];
		$lazy_options            = Funiter_Functions::funiter_get_option( 'funiter_theme_lazy_load' );
		$default_attributes      = $product->get_default_attributes();
		if ( $lazy_options == 1 && empty( $default_attributes ) ) {
			$class_img[] = 'lazy';
			$data_src    = 'data-src=' . esc_attr( $image_thumb['url'] );
			$image_url   = "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20" . $width . "%20" . $height . "%27%2F%3E";
		}
		if ( $attachment_ids && has_post_thumbnail() ) {
			$gallery_class_img[]       = 'wp-post-image';
			$thumb_gallery_class_img[] = 'woocommerce-product-gallery__image';
		} else {
			$class_img[]       = 'wp-post-image';
			$thumb_class_img[] = 'woocommerce-product-gallery__image';
		}
		?>

        <img class="<?php echo implode( ' ', $class_img ); ?>" src="<?php echo esc_attr( $image_url ); ?>"
			<?php echo esc_attr( $data_src ); ?> <?php echo image_hwstring( $width, $height ); ?>
             alt="<?php echo esc_attr( the_title_attribute() ); ?>">
		
		<?php
	}
}
/* CUSTOM DESCRIPTION */
if ( ! function_exists( 'funiter_product_short_description' ) ) {
	function funiter_product_short_description() {
		global $post;
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			if ( ! $post->post_excerpt ) {
				return;
			}
			?>
            <div class="product-des">
				<?php the_excerpt(); ?>
            </div>
			<?php
		}
	}
}
/* ADD TO CART STICKY PRODUCT */
if ( ! function_exists( 'funiter_add_to_cart_sticky' ) ) {
	function funiter_add_to_cart_sticky() {
		if ( ! is_singular( 'product' ) ) {
			return;
		}
		global $product;
		$enable_info_product_single = Funiter_Functions::funiter_get_option( 'enable_info_product_single' );
		if ( $enable_info_product_single == 1 ) : ?>
            <div class="sticky_info_single_product">
                <div class="container">
                    <div class="sticky-thumb-left">
						<?php
						do_action( 'single_product_addtocart_thumb' );
						?>
                    </div>
                    <div class="sticky-info-right">
                        <div class="sticky-title">
							<?php
							do_action( 'single_product_addtocart' );
							do_action( 'woocommerce_after_shop_loop_item_title' );
							?>
                        </div>
						<?php if ( $product->is_purchasable() || $product->is_type( 'external' ) || $product->is_type( 'grouped' ) ) { ?>
							<?php if ( $product->is_in_stock() ) { ?>
                                <button type="button"
                                        class="funiter-single-add-to-cart-fixed-top funiter-single-add-to-cart-btn btn button"><?php echo esc_html( $product->single_add_to_cart_text() ); ?>
                                </button>
							<?php } else { ?>
                                <button type="button"
                                        class="funiter-single-add-to-cart-fixed-top funiter-single-add-to-cart-btn add-to-cart-out-of-stock btn button"><?php esc_html_e( 'Out Of Stock', 'funiter' ); ?>
                                </button>
							<?php } ?>
						<?php } ?>
                    </div>
                </div>
            </div>
		<?php endif;
		
	}
}
if ( ! function_exists( 'funiter_add_categories_product' ) ) {
	function funiter_add_categories_product() {
		$get_term_cat = get_the_terms( get_the_ID(), 'product_cat' );
		if ( ! is_wp_error( $get_term_cat ) && ! empty( $get_term_cat ) ) : ?>
            <div class="product-cats">
                <span><?php esc_html_e( 'Categories:', 'funiter' ); ?></span>
                <div class="cats-list">
					<?php foreach ( $get_term_cat as $item ):
						$link = get_term_link( $item->term_id, 'product_cat' );
						?>
                        <a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $item->name ); ?></a>
					<?php endforeach; ?>
                </div>
            </div>
		<?php endif;
	}
}
if ( ! function_exists( 'funiter_faqs_single_product' ) ) {
	function funiter_faqs_single_product() {
		$page_faqs_product = Funiter_Functions::funiter_get_option( 'funiter_add_page_product' );
		if ( $page_faqs_product && is_product() ) {
			$posts   = get_post( $page_faqs_product );
			$content = $posts->post_content;
			$content = apply_filters( 'the_content', $content );
			$content = str_replace( ']]>', ']]>', $content );
			echo '<div class="recent-product-woo">';
			echo wp_specialchars_decode( $content );
			echo '</div>';
		}
	}
}
if ( ! function_exists( 'funiter_noteworthy_products' ) ) {
	function funiter_noteworthy_products() {
		$enable_noteworthy_products = Funiter_Functions::funiter_get_option( 'enable_noteworthy_products' );
		if ( $enable_noteworthy_products ) : ?>
            <div class="container">
                <div class="recent-product-woo row">
                    <div class="col-sm-4">
						<?php echo do_shortcode( '[funiter_products product_style="3" product_image_size="200x200" target="featured_products" per_page="3" boostrap_rows_space="rows-space-20" boostrap_bg_items="12" boostrap_lg_items="12" boostrap_md_items="12" boostrap_sm_items="12" boostrap_xs_items="12" boostrap_ts_items="12" funiter_custom_id="" title="' . esc_attr__( 'Featured Products', 'funiter' ) . '"]' ); ?>
                    </div>
                    <div class="col-sm-4">
						<?php echo do_shortcode( '[funiter_products product_style="3" product_image_size="200x200" target="top-rated" per_page="3" boostrap_rows_space="rows-space-20" boostrap_bg_items="12" boostrap_lg_items="12" boostrap_md_items="12" boostrap_sm_items="12" boostrap_xs_items="12" boostrap_ts_items="12" funiter_custom_id="" title="' . esc_attr__( 'Top Rated Products', 'funiter' ) . '"]' ); ?>
                    </div>
                    <div class="col-sm-4">
						<?php echo do_shortcode( '[funiter_products product_style="3" product_image_size="200x200" target="best-selling" per_page="3" boostrap_rows_space="rows-space-20" boostrap_bg_items="12" boostrap_lg_items="12" boostrap_md_items="12" boostrap_sm_items="12" boostrap_xs_items="12" boostrap_ts_items="12" funiter_custom_id="" title="' . esc_attr__( 'Top Selling Products', 'funiter' ) . '"]' ); ?>
                    </div>
                </div>
            </div>
		<?php endif;
	}
}

if ( ! function_exists( 'funiter_action_attributes' ) ) {
	function funiter_action_attributes() {
		global $product;
		if ( $product->get_type() == 'variable' ) :
			$attribute_array = array();
			$attributes = $product->get_variation_attributes();
			$attribute_keys = array_keys( $attributes );
			$get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
			$available_variations = $get_variations ? $product->get_available_variations() : false;
			
			if ( is_wp_error( $available_variations ) ) {
				return;
			}
			if ( empty( $available_variations ) ) {
				return;
			}
			
			// GET SIZE IMAGE SETTING
			$width  = 500;
			$height = 500;
			$size   = wc_get_image_size( 'shop_catalog' );
			if ( $size ) {
				$width  = $size['width'];
				$height = $size['height'];
			}
			$width  = apply_filters( 'funiter_shop_product_thumb_width', $width );
			$height = apply_filters( 'funiter_shop_product_thumb_height', $height );
			foreach ( $available_variations as $available_variation ) {
				$image_variable                            = apply_filters( 'funiter_resize_image', $available_variation['image_id'], $width, $height, true, false );
				$available_variation['image']['src']       = $image_variable['url'];
				$available_variation['image']['url']       = $image_variable['url'];
				$available_variation['image']['full_src']  = $image_variable['url'];
				$available_variation['image']['thumb_src'] = $image_variable['url'];
				$available_variation['image']['src_w']     = $width;
				$available_variation['image']['src_h']     = $height;
				$attribute_array[]                         = $available_variation;
			}
			if ( ! empty( $attributes ) ):?>
                <form class="variations_form cart" method="post" enctype='multipart/form-data'
                      data-product_id="<?php echo absint( $product->get_id() ); ?>"
                      data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $attribute_array ) ) ?>">
                    <table class="variations">
                        <tbody>
						<?php foreach ( $attributes as $attribute_name => $options ) : ?>
                            <tr>
                                <td class="value">
									<?php
									$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
									wc_dropdown_variation_attribute_options( array(
										                                         'options'   => $options,
										                                         'attribute' => $attribute_name,
										                                         'product'   => $product,
										                                         'selected'  => $selected
									                                         ) );
									echo end( $attribute_keys ) === $attribute_name ? apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'funiter' ) . '</a>' ) : '';
									?>
                                </td>
                            </tr>
						<?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
				<?php
			endif;
		endif;
	}
}

if ( ! function_exists( 'funiter_single_product_brands' ) ) {
	function funiter_single_product_brands() {
		global $product;
		
		$show_single_product_brands = Funiter_Functions::funiter_get_option( 'show_single_product_brands', false );
		if ( ! $show_single_product_brands ) {
			return;
		}
		
		$terms = get_the_terms( $product->get_id(), 'product_brand' );
		
		if ( is_wp_error( $terms ) ) {
			return;
		}
		
		if ( ! $terms ) {
			return;
		}
		
		$show_single_product_brand_titles = Funiter_Functions::funiter_get_option( 'show_single_product_brand_titles', false );
		
		?>
        <div class="brand-product">
            <p class="title-brand"><?php esc_html_e( 'Brand: ', 'funiter' ); ?></p>
            <ul class="list-brands product-taxonomies-list">
				<?php
				foreach ( $terms as $term ) {
					if ( ! $term ) {
						continue;
					}
					$tax_img_id  = get_term_meta( $term->term_id, 'tax_image', true );
					$term_img    = Funiter_Functions::resize_image( $tax_img_id, null, 125, 39, false, true, false );
					$brand_title = $show_single_product_brand_titles ? $term->name : '';
					?>
                    <li>
                        <a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $brand_title ); ?><?php echo Funiter_Functions::img_output( $term_img ); ?></a>
                    </li>
					<?php
				}
				?>
            </ul>
        </div>
		<?php
	}
}

if ( ! function_exists( 'funiter_single_show_sku' ) ) {
	function funiter_single_show_sku() {
		global $product;
		if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
            <div class="product-sku">
				<?php esc_html_e( 'SKU:', 'funiter' ); ?>
                <span class="sku">
                    <?php
                    ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'funiter' );
                    echo esc_html( $sku );
                    ?>
                </span>
            </div>
		<?php endif;
	}
}
if ( ! function_exists( 'funiter_single_show_tags' ) ) {
	function funiter_single_show_tags() {
		$get_term_tag = get_the_terms( get_the_ID(), 'product_tag' );
		if ( ! is_wp_error( $get_term_tag ) && ! empty( $get_term_tag ) ) : ?>
            <div class="product-tags">
                <span><?php esc_html_e( 'Tags:', 'funiter' ); ?></span>
                <div class="tags-list">
					<?php foreach ( $get_term_tag as $item ):
						$link = get_term_link( $item->term_id, 'product_tag' );
						?>
                        <a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $item->name ); ?></a>
					<?php endforeach; ?>
                </div>
            </div>
		<?php endif;
	}
}

if ( ! function_exists( 'funiter_woocommerce_breadcrumb' ) ) {
	function funiter_woocommerce_breadcrumb() {
		
		// Don't show breadcrumb on mobile devices on single products if mobile single product layout is enabled
		if ( is_singular( 'product' ) ) {
			if ( Funiter_Functions::is_mobile() ) {
				$enable_single_product_mobile = Funiter_Functions::funiter_get_option( 'enable_single_product_mobile', false );
				if ( $enable_single_product_mobile ) {
					return;
				}
			}
		}
		
		$args = array(
			'delimiter' => '<i class="fa fa-angle-right"></i>',
		);
		woocommerce_breadcrumb( $args );
	}
}
if ( ! function_exists( 'funiter_woocommerce_before_loop_content' ) ) {
	function funiter_woocommerce_before_loop_content() {
		$sidebar_isset = wp_get_sidebars_widgets();
		/*Shop layout*/
		$shop_layout  = Funiter_Functions::funiter_get_option( 'funiter_sidebar_shop_layout', 'left' );
		$shop_sidebar = Funiter_Functions::funiter_get_option( 'funiter_shop_used_sidebar', 'widget-shop' );
		if ( is_product() ) {
			$shop_layout  = Funiter_Functions::funiter_get_option( 'funiter_sidebar_product_layout', 'left' );
			$shop_sidebar = Funiter_Functions::funiter_get_option( 'funiter_single_product_used_sidebar', 'widget-product' );
		}
		if ( isset( $sidebar_isset[ $shop_sidebar ] ) && empty( $sidebar_isset[ $shop_sidebar ] ) ) {
			$shop_layout = 'full';
		}
		$main_content_class   = array();
		$main_content_class[] = 'main-content';
		if ( $shop_layout == 'full' ) {
			$main_content_class[] = 'col-sm-12';
		} else {
			$main_content_class[] = 'col-lg-9 col-md-8 col-sm-8 col-xs-12 has-sidebar';
		}
		$main_content_class = apply_filters( 'funiter_class_archive_content', $main_content_class, $shop_layout );
		echo '<div class="' . esc_attr( implode( ' ', $main_content_class ) ) . '">';
	}
}
if ( ! function_exists( 'funiter_woocommerce_after_loop_content' ) ) {
	function funiter_woocommerce_after_loop_content() {
		echo '</div>';
	}
}
if ( ! function_exists( 'funiter_woocommerce_before_main_content' ) ) {
	function funiter_woocommerce_before_main_content() {
		/*Main container class*/
		$main_container_class = array();
		$sidebar_isset        = wp_get_sidebars_widgets();
		$enable_shop_mobile   = Funiter_Functions::funiter_get_option( 'enable_shop_mobile' );
		$shop_layout          = Funiter_Functions::funiter_get_option( 'funiter_sidebar_shop_layout', 'left' );
		$shop_sidebar         = Funiter_Functions::funiter_get_option( 'funiter_shop_used_sidebar', 'widget-shop' );
		$side_summary_layout  = Funiter_Functions::funiter_get_option( 'funiter_single_product_summary_sidebar_style', 'disable' );
		if ( is_product() ) {
			// Single product has it own mobile setting
			$enable_shop_mobile     = false;
			$shop_layout            = Funiter_Functions::funiter_get_option( 'funiter_sidebar_product_layout', 'left' );
			$shop_sidebar           = Funiter_Functions::funiter_get_option( 'funiter_single_product_used_sidebar', 'widget-product' );
			$thumbnail_layout       = 'vertical';
			$main_container_class[] = 'single-thumb-' . $thumbnail_layout;
		}
		if ( isset( $sidebar_isset[ $shop_sidebar ] ) && empty( $sidebar_isset[ $shop_sidebar ] ) ) {
			$shop_layout = 'full';
		}
		if ( ( $enable_shop_mobile == 1 ) && ( funiter_is_mobile() ) ) {
			$main_container_class[] = 'shop-mobile-real';
		}
		$main_container_class[] = 'main-container shop-page';
		if ( $shop_layout == 'full' ) {
			$main_container_class[] = 'no-sidebar';
		} else {
			$main_container_class[] = $shop_layout . '-sidebar';
		}
		if ( is_product() && $side_summary_layout == 'horizontal' ) {
			$main_container_class[] = 'single-product-modern';
		} elseif ( is_product() && $side_summary_layout == 'vertical' ) {
			$main_container_class[] = 'single-product-vertical';
		} else {
			$main_container_class[] = 'single-no-extend';
		}
		$main_container_class = apply_filters( 'funiter_class_before_main_content_product', $main_container_class, $shop_layout );
		echo '<div class="' . esc_attr( implode( ' ', $main_container_class ) ) . '">';
		echo '<div class="container">';
		echo '<div class="row">';
	}
}
if ( ! function_exists( 'funiter_woocommerce_after_main_content' ) ) {
	function funiter_woocommerce_after_main_content() {
		echo '</div></div></div>';
	}
}
if ( ! function_exists( 'funiter_woocommerce_sidebar' ) ) {
	function funiter_woocommerce_sidebar() {
		$shop_layout  = Funiter_Functions::funiter_get_option( 'funiter_sidebar_shop_layout', 'left' );
		$shop_sidebar = Funiter_Functions::funiter_get_option( 'funiter_shop_used_sidebar', 'widget-shop' );
		if ( is_product() ) {
			$shop_layout  = Funiter_Functions::funiter_get_option( 'funiter_sidebar_product_layout', 'left' );
			$shop_sidebar = Funiter_Functions::funiter_get_option( 'funiter_single_product_used_sidebar', 'widget-product' );
		}
		$sidebar_class = array();
		$sidebar_isset = wp_get_sidebars_widgets();
		if ( isset( $sidebar_isset[ $shop_sidebar ] ) && empty( $sidebar_isset[ $shop_sidebar ] ) ) {
			$shop_layout = 'full';
		}
		$sidebar_class[] = 'sidebar';
		if ( $shop_layout != 'full' ) {
			$sidebar_class[] = 'col-lg-3 col-md-4 col-sm-4 col-xs-12';
		}
		$sidebar_class = apply_filters( 'funiter_class_sidebar_content_product', $sidebar_class, $shop_layout, $shop_sidebar );
		if ( $shop_layout != "full" ): ?>
            <div class="<?php echo esc_attr( implode( ' ', $sidebar_class ) ); ?>">
				<?php if ( is_active_sidebar( $shop_sidebar ) ) : ?>
                    <div id="widget-area" class="widget-area shop-sidebar">
						<?php dynamic_sidebar( $shop_sidebar ); ?>
                    </div><!-- .widget-area -->
				<?php endif; ?>
            </div>
		<?php endif;
	}
}
if ( ! function_exists( 'funiter_sidebar_single_product' ) ) {
	function funiter_sidebar_single_product() {
		$shop_layout         = Funiter_Functions::funiter_get_option( 'funiter_sidebar_product_layout', 'left' );
		$side_summary_layout = Funiter_Functions::funiter_get_option( 'funiter_single_product_summary_sidebar_style', 'vertical' );
		$shop_sidebar        = Funiter_Functions::funiter_get_option( 'funiter_single_product_summary_sidebar', 'widget-summary-product' );
		if ( is_product() && is_active_sidebar( $shop_sidebar ) && $shop_layout == 'full' && $side_summary_layout == 'vertical' ) : ?>
            <div id="widget-area" class="widget-area shop-sidebar">
				<?php dynamic_sidebar( $shop_sidebar ); ?>
            </div><!-- .widget-area -->
			<?php
		endif;
	}
}
if ( ! function_exists( 'funiter_horizontal_single_product' ) ) {
	function funiter_horizontal_single_product() {
		$shop_layout         = Funiter_Functions::funiter_get_option( 'funiter_sidebar_product_layout', 'left' );
		$side_summary_layout = Funiter_Functions::funiter_get_option( 'funiter_single_product_summary_sidebar_style', 'vertical' );
		$shop_sidebar        = Funiter_Functions::funiter_get_option( 'funiter_single_product_summary_sidebar', 'widget-summary-product' );
		if ( is_product() && is_active_sidebar( $shop_sidebar ) && $shop_layout == 'full' && $side_summary_layout == 'horizontal' ) : ?>
            <div id="widget-area-extra" class="widget-area extra-sidebar">
				<?php dynamic_sidebar( $shop_sidebar ); ?>
            </div><!-- .widget-area -->
			<?php
		endif;
	}
}
if ( ! function_exists( 'funiter_product_get_rating_html' ) ) {
	function funiter_product_get_rating_html( $html, $rating, $count ) {
		global $product;
		$rating_count = $product->get_rating_count();
		$class_star   = 'rating-wapper ';
		if ( 0 >= $rating ) {
			$class_star .= '';
		}
		$html = '<div class="' . esc_attr( $class_star ) . '"><div class="star-rating">';
		$html .= wc_get_star_rating_html( $rating, $count );
		$html .= '</div>';
		if ( $rating_count == 1 ) {
			$html .= '<span class="review">( ' . $rating_count . ' ' . esc_html__( 'review', 'funiter' ) . ' )</span>';
		} else {
			$html .= '<span class="review">( ' . $rating_count . ' ' . esc_html__( 'reviews', 'funiter' ) . ' )</span>';
		}
		$html .= '</div>';
		
		return $html;
	}
}
if ( ! function_exists( 'funiter_before_shop_control' ) ) {
	function funiter_before_shop_control() {
		?>
        <div class="shop-control shop-before-control">
			<?php do_action( 'funiter_control_before_content' ); ?>
        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_after_shop_control' ) ) {
	function funiter_after_shop_control() {
		?>
        <div class="shop-control shop-after-control">
			<?php do_action( 'funiter_control_after_content' ); ?>
        </div>
		<?php
	}
}
if ( ! function_exists( 'product_display_mode_request' ) ) {
	function product_display_mode_request() {
		if ( isset( $_POST['display_mode_action'] ) ) {
			wp_redirect(
				add_query_arg(
					array(
						'funiter_shop_list_style' => $_POST['display_mode_value'],
					), $_POST['display_mode_action']
				)
			);
			exit();
		}
	}
}
if ( ! function_exists( 'funiter_shop_display_mode_tmp' ) ) {
	function funiter_shop_display_mode_tmp() {
		$shop_display_mode = Funiter_Functions::funiter_get_option( 'funiter_shop_list_style', 'grid' );
		$current_url       = home_url( add_query_arg( null, null ) );
		if ( class_exists( 'WooCommerce' ) ) {
			if ( is_shop() ) {
				$current_url = get_permalink( wc_get_page_id( 'shop' ) );
			} else {
				if ( is_product_taxonomy() ) {
					$queried_object = get_queried_object();
					if ( isset( $queried_object->term_id ) ) {
						$term_link = get_term_link( $queried_object->term_id, 'product_cat' );
						if ( ! is_wp_error( $term_link ) ) {
							$current_url = $term_link;
						}
					}
				}
			}
		}
		
		?>
        <div class="grid-view-mode">
            <form method="POST" action="">
                <button type="submit"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="<?php echo esc_html__( 'Shop Grid v.1' ); ?>"
                        class="modes-mode mode-grid display-mode <?php if ( $shop_display_mode == 'grid' ): ?>active<?php endif; ?>"
                        value="<?php echo esc_attr( $current_url ); ?>"
                        name="display_mode_action">
                        <span class="button-inner">
                            <?php echo esc_html__( 'Grid', 'funiter' ); ?>
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                </button>
                <input type="hidden" value="grid" name="display_mode_value">
            </form>
            <form method="POST" action="<?php echo esc_attr( $current_url ); ?>">
                <button type="submit"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="<?php echo esc_html__( 'Shop List Mode' ); ?>"
                        class="modes-mode mode-list display-mode <?php if ( $shop_display_mode == 'list' ): ?>active<?php endif; ?>"
                        value="<?php echo esc_attr( $current_url ); ?>"
                        name="display_mode_action">
                        <span class="button-inner">
                            <?php echo esc_html__( 'List', 'funiter' ); ?>
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                </button>
                <input type="hidden" value="list" name="display_mode_value">
            </form>
        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_loop_shop_per_page' ) ) {
	function funiter_loop_shop_per_page() {
		$funiter_woo_products_perpage = Funiter_Functions::funiter_get_option( 'funiter_product_per_page', '12' );
		
		return $funiter_woo_products_perpage;
	}
}
if ( ! function_exists( 'funiter_woof_products_query' ) ) {
	function funiter_woof_products_query( $wr ) {
		$funiter_woo_products_perpage = Funiter_Functions::funiter_get_option( 'funiter_product_per_page', '12' );
		$wr['posts_per_page']         = $funiter_woo_products_perpage;
		
		return $wr;
	}
}
if ( ! function_exists( 'product_per_page_request' ) ) {
	function product_per_page_request() {
		if ( isset( $_POST['perpage_action_form'] ) ) {
			wp_redirect(
				add_query_arg(
					array(
						'funiter_product_per_page' => $_POST['product_per_page_filter'],
					), $_POST['perpage_action_form']
				)
			);
			exit();
		}
	}
}

if ( ! function_exists( 'funiter_woocommerce_catalog_ordering' ) ) {
	
	/**
	 * Output the product sorting options.
	 */
	function funiter_woocommerce_catalog_ordering() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
			return;
		}
		$show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
			'menu_order' => esc_html__( 'Default sorting', 'funiter' ),
			'popularity' => esc_html__( 'Sort by popularity', 'funiter' ),
			'rating'     => esc_html__( 'Sort by average rating', 'funiter' ),
			'date'       => esc_html__( 'Sort by newness', 'funiter' ),
			'price'      => esc_html__( 'Sort by price: low to high', 'funiter' ),
			'price-desc' => esc_html__( 'Sort by price: high to low', 'funiter' ),
		) );
		
		$default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
		$orderby         = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby; // WPCS: sanitization ok, input var ok, CSRF ok.
		
		if ( wc_get_loop_prop( 'is_search' ) ) {
			$catalog_orderby_options = array_merge( array( 'relevance' => __( 'Relevance', 'funiter' ) ), $catalog_orderby_options );
			
			unset( $catalog_orderby_options['menu_order'] );
		}
		
		if ( ! $show_default_orderby ) {
			unset( $catalog_orderby_options['menu_order'] );
		}
		
		if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
			unset( $catalog_orderby_options['rating'] );
		}
		
		if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
			$orderby = current( array_keys( $catalog_orderby_options ) );
		}
		if ( class_exists( 'PrdctfltrInit' ) ) {
			wc_get_template( 'loop/fami-orderby.php', array(
				'catalog_orderby_options' => $catalog_orderby_options,
				'orderby'                 => $orderby,
				'show_default_orderby'    => $show_default_orderby,
			) );
		}
	}
}
if ( ! function_exists( 'funiter_product_per_page_tmp' ) ) {
	function funiter_product_per_page_tmp() {
		$perpage     = Funiter_Functions::funiter_get_option( 'funiter_product_per_page', '12' );
		$current_url = home_url( add_query_arg( null, null ) );
		$products    = wc_get_loop_prop( 'total' );
		?>
        <form class="per-page-form" method="POST" action="">
            <label>
                <select name="product_per_page_filter" class="option-perpage" onchange="this.form.submit()">
                    <option value="<?php echo esc_attr( $perpage ); ?>" <?php echo esc_attr( 'selected' ); ?>>
						<?php echo esc_html__( 'Show', 'funiter' ); ?><?php echo zeroise( $perpage, 2 ); ?>
                    </option>
                    <option value="5">
						<?php echo esc_html__( 'Show 05', 'funiter' ); ?>
                    </option>
                    <option value="10">
						<?php echo esc_html__( 'Show 10', 'funiter' ); ?>
                    </option>
                    <option value="12">
						<?php echo esc_html__( 'Show 12', 'funiter' ); ?>
                    </option>
                    <option value="15">
						<?php echo esc_html__( 'Show 15', 'funiter' ); ?>
                    </option>
                    <option value="<?php echo esc_attr( $products ); ?>">
						<?php echo esc_html__( 'Show All', 'funiter' ); ?>
                    </option>
                </select>
            </label>
            <label>
                <input type="hidden" name="perpage_action_form" value="<?php echo esc_attr( $current_url ); ?>">
            </label>
        </form>
		<?php
	}
}
if ( ! function_exists( 'funiter_custom_pagination' ) ) {
	function funiter_custom_pagination() {
		global $wp_query;
		if ( $wp_query->max_num_pages > 1 ) {
			?>
            <nav class="woocommerce-pagination pagination">
				<?php
				echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
					'base'      => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
					'format'    => '',
					'add_args'  => false,
					'current'   => max( 1, get_query_var( 'paged' ) ),
					'total'     => $wp_query->max_num_pages,
					'prev_text' => esc_html__( 'Previous', 'funiter' ),
					'next_text' => esc_html__( 'Next', 'funiter' ),
					'type'      => 'plain',
					'end_size'  => 3,
					'mid_size'  => 3,
				) ) );
				?>
            </nav>
			<?php
		}
	}
}
if ( ! function_exists( 'funiter_related_title_product' ) ) {
	add_action( 'funiter_before_related_single_product', 'funiter_related_title_product' );
	function funiter_related_title_product( $prefix ) {
		if ( $prefix == 'funiter_woo_crosssell' ) {
			$default_text = esc_html__( 'Cross Sell Products', 'funiter' );
		} elseif ( $prefix == 'funiter_woo_related' ) {
			$default_text = esc_html__( 'Related Products', 'funiter' );
		} else {
			$default_text = esc_html__( 'Upsell Products', 'funiter' );
		}
		$title = Funiter_Functions::funiter_get_option( $prefix . '_products_title', $default_text );
		$aUrl  = get_permalink( get_option( 'woocommerce_shop_page_id' ) );
		?>
        <div class="single-block-wrap">
            <h2 class="single-block-title">
                <span><?php echo esc_html( $title ); ?></span>
            </h2>
        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_woocommerce_category_description' ) ) {
	function funiter_woocommerce_category_description() {
		$enable_cat = Funiter_Functions::funiter_get_option( 'funiter_woo_cat_enable' );
		$banner_cat = Funiter_Functions::funiter_get_option( 'category_banner' );
		$banner_url = Funiter_Functions::funiter_get_option( 'category_banner_url', '#' );
		if ( is_product_category() && $enable_cat == 1 ) {
			$category_html = '';
			$prefix        = 'funiter_woo_cat';
			$woo_ls_items  = Funiter_Functions::funiter_get_option( $prefix . '_ls_items', 3 );
			$woo_lg_items  = Funiter_Functions::funiter_get_option( $prefix . '_lg_items', 3 );
			$woo_md_items  = Funiter_Functions::funiter_get_option( $prefix . '_md_items', 3 );
			$woo_sm_items  = Funiter_Functions::funiter_get_option( $prefix . '_sm_items', 2 );
			$woo_xs_items  = Funiter_Functions::funiter_get_option( $prefix . '_xs_items', 1 );
			$woo_ts_items  = Funiter_Functions::funiter_get_option( $prefix . '_ts_items', 1 );
			$atts          = array(
				'owl_loop'              => 'false',
				'owl_slide_margin'      => 40,
				'owl_dots'              => 'true',
				'owl_ts_items'          => $woo_ts_items,
				'owl_xs_items'          => $woo_xs_items,
				'owl_sm_items'          => $woo_sm_items,
				'owl_md_items'          => $woo_md_items,
				'owl_lg_items'          => $woo_lg_items,
				'owl_ls_items'          => $woo_ls_items,
				'owl_responsive_margin' => 1200,
			);
			$owl_settings  = apply_filters( 'funiter_carousel_data_attributes', 'owl_', $atts );
			// We can still render if display is forced.
			$cat_args           = array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'parent'     => get_queried_object_id(),
			);
			$product_categories = get_terms( $cat_args );
			if ( $banner_cat ) {
				$banner_thumb  = apply_filters( 'funiter_resize_image', $banner_cat, false, false, true, true );
				$category_html .= '<div class="product-grid col-sm-12"><a href="' . esc_url( $banner_url ) . '"><figure class="banner-cat">' . wp_specialchars_decode( $banner_thumb['img'] ) . '</figure></a></div>';
			}
			if ( ! is_wp_error( $product_categories ) && ! empty( $product_categories ) ) {
				$category_html .= '<div class="product-grid categories-slide col-sm-12"><div class="owl-slick" ' . esc_attr( $owl_settings ) . '>';
				foreach ( $product_categories as $category ) {
					$cat_link      = get_term_link( $category->term_id, 'product_cat' );
					$thumbnail_id  = get_term_meta( $category->term_id, 'thumbnail_id', true );
					$cat_thumb     = apply_filters( 'funiter_resize_image', $thumbnail_id, 500, 500, true, true );
					$category_html .= '<div><a href="' . esc_url( $cat_link ) . '"><figure>' . wp_specialchars_decode( $cat_thumb['img'] ) . '</figure><span class="name">' . esc_html( $category->name ) . '</span></a></div>';
				}
				$category_html .= '</div></div>';
			}
			?>
            <div class="categories-product-woo row <?php //echo esc_attr( $class_shop ); ?>">
				<?php echo wp_specialchars_decode( $category_html ); ?>
                <div class="product-grid col-sm-12">
                    <div class="block-title">
                        <h2 class="product-grid-title">
                            <span><?php echo esc_html__( 'Bestseller Products', 'funiter' ); ?></span>
                        </h2>
                        <a href="<?php echo get_permalink( get_option( 'woocommerce_shop_page_id' ) ); ?>">
							<?php echo esc_html__( 'Shop more', 'funiter' ); ?>
                        </a>
                    </div>
					<?php echo do_shortcode( '[funiter_products product_style="1" product_image_size="500x500" productsliststyle="owl" target="best-selling" per_page="6" owl_dots="true" owl_slide_margin="40" owl_ls_items="' . $woo_ls_items . '" owl_lg_items="' . $woo_lg_items . '" owl_md_items="' . $woo_md_items . '" owl_sm_items="' . $woo_sm_items . '" owl_xs_items="' . $woo_xs_items . '" owl_ts_items="' . $woo_ts_items . '" funiter_custom_id=""]' ); ?>
                </div>
            </div>
			<?php
		}
	}
}
if ( ! function_exists( 'funiter_carousel_products' ) ) {
	function funiter_carousel_products( $prefix, $data_args ) {
		$enable_product = Funiter_Functions::funiter_get_option( $prefix . '_enable', 'enable' );
		if ( $enable_product == 'disable' ) {
			return;
		}
		$classes                   = array( 'product-item' );
		$funiter_woo_product_style = apply_filters( 'funiter_single_product_style', 1 );
		$classes[]                 = 'style-' . $funiter_woo_product_style;
		$classes[]                 = apply_filters( 'funiter_single_product_class', '' );
		$template_style            = 'style-' . $funiter_woo_product_style;
		$woo_ls_items              = Funiter_Functions::funiter_get_option( $prefix . '_ls_items', 3 );
		$woo_lg_items              = Funiter_Functions::funiter_get_option( $prefix . '_lg_items', 3 );
		$woo_md_items              = Funiter_Functions::funiter_get_option( $prefix . '_md_items', 3 );
		$woo_sm_items              = Funiter_Functions::funiter_get_option( $prefix . '_sm_items', 2 );
		$woo_xs_items              = Funiter_Functions::funiter_get_option( $prefix . '_xs_items', 1 );
		$woo_ts_items              = Funiter_Functions::funiter_get_option( $prefix . '_ts_items', 1 );
		$atts                      = array(
			'owl_dots'              => 'true',
			'owl_loop'              => 'false',
			'owl_ts_items'          => $woo_ts_items,
			'owl_xs_items'          => $woo_xs_items,
			'owl_sm_items'          => $woo_sm_items,
			'owl_md_items'          => $woo_md_items,
			'owl_lg_items'          => $woo_lg_items,
			'owl_ls_items'          => $woo_ls_items,
			'owl_responsive_margin' => 1200,
		);
		$atts                      = apply_filters( 'funiter_carousel_related_single_product', $atts );
		$owl_settings              = apply_filters( 'funiter_carousel_data_attributes', 'owl_', $atts );
		if ( $data_args ) : ?>
            <div class="col-sm-12 col-xs-12 products product-grid <?php echo esc_attr( $prefix ); ?>-product">
				<?php do_action( 'funiter_before_related_single_product', $prefix ); ?>
                <div class="owl-slick owl-products equal-container better-height" <?php echo esc_attr( $owl_settings ); ?>>
					<?php foreach ( $data_args as $value ) : ?>
                        <div <?php post_class( $classes ) ?>>
							<?php
							$post_object = get_post( $value->get_id() );
							setup_postdata( $GLOBALS['post'] =& $post_object );
							wc_get_template_part( 'product-styles/content-product', $template_style );
							?>
                        </div>
					<?php endforeach; ?>
                </div>
				<?php do_action( 'funiter_after_related_single_product', $prefix ); ?>
            </div>
		<?php endif;
		wp_reset_postdata();
	}
}
if ( ! function_exists( 'funiter_cross_sell_products' ) ) {
	function funiter_cross_sell_products( $limit = 2, $columns = 2, $orderby = 'rand', $order = 'desc' ) {
		if ( is_checkout() ) {
			return;
		}
		$cross_sells                 = array_filter( array_map( 'wc_get_product', WC()->cart->get_cross_sells() ), 'wc_products_array_filter_visible' );
		$woocommerce_loop['name']    = 'cross-sells';
		$woocommerce_loop['columns'] = apply_filters( 'woocommerce_cross_sells_columns', $columns );
		// Handle orderby and limit results.
		$orderby     = apply_filters( 'woocommerce_cross_sells_orderby', $orderby );
		$cross_sells = wc_products_array_orderby( $cross_sells, $orderby, $order );
		$limit       = apply_filters( 'woocommerce_cross_sells_total', $limit );
		$cross_sells = $limit > 0 ? array_slice( $cross_sells, 0, $limit ) : $cross_sells;
		funiter_carousel_products( 'funiter_woo_crosssell', $cross_sells );
	}
}
if ( ! function_exists( 'funiter_related_products' ) ) {
	function funiter_related_products() {
		global $product;
		$related_products = array();
		if ( $product ) {
			$defaults                    = array(
				'posts_per_page' => 6,
				'columns'        => 6,
				'orderby'        => 'rand',
				'order'          => 'desc',
			);
			$args                        = wp_parse_args( $defaults );
			$args['related_products']    = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
			$args['related_products']    = wc_products_array_orderby( $args['related_products'], $args['orderby'], $args['order'] );
			$woocommerce_loop['name']    = 'related';
			$woocommerce_loop['columns'] = apply_filters( 'woocommerce_related_products_columns', $args['columns'] );
			$related_products            = $args['related_products'];
		}
		
		if ( ! is_product() ) {
			$related_products = array();
		}
		funiter_carousel_products( 'funiter_woo_related', $related_products );
	}
}
if ( ! function_exists( 'funiter_upsell_display' ) ) {
	function funiter_upsell_display( $orderby = 'rand', $order = 'desc', $limit = '-1', $columns = 4 ) {
		global $product;
		$upsells = array();
		if ( $product ) {
			$args                        = array( 'posts_per_page' => 4, 'orderby' => 'rand', 'columns' => 4, );
			$woocommerce_loop['name']    = 'up-sells';
			$woocommerce_loop['columns'] = apply_filters( 'woocommerce_upsells_columns', isset( $args['columns'] ) ? $args['columns'] : $columns );
			$orderby                     = apply_filters( 'woocommerce_upsells_orderby', isset( $args['orderby'] ) ? $args['orderby'] : $orderby );
			$limit                       = apply_filters( 'woocommerce_upsells_total', isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : $limit );
			// Get visible upsells then sort them at random, then limit result set.
			$upsells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' ), $orderby, $order );
			$upsells = $limit > 0 ? array_slice( $upsells, 0, $limit ) : $upsells;
		}
		
		if ( ! is_product() ) {
			$upsells = array();
		}
		funiter_carousel_products( 'funiter_woo_upsell', $upsells );
	}
}

if ( ! function_exists( 'funiter_track_product_view' ) ) {
	
	/**
	 * Track product view for recently viewed products
	 */
	function funiter_track_product_view() {
		if ( ! is_singular( 'product' ) ) {
			return;
		}
		
		global $post;
		
		if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) { // @codingStandardsIgnoreLine.
			$viewed_products = array();
		} else {
			$viewed_products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) ); // @codingStandardsIgnoreLine.
		}
		
		// Unset if already in viewed products list.
		$keys = array_flip( $viewed_products );
		
		if ( isset( $keys[ $post->ID ] ) ) {
			unset( $viewed_products[ $keys[ $post->ID ] ] );
		}
		
		$viewed_products[] = $post->ID;
		
		if ( count( $viewed_products ) > 15 ) {
			array_shift( $viewed_products );
		}
		
		// Store for session only.
		wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
	}
}

if ( ! function_exists( 'funiter_recently_viewed_products' ) ) {
	function funiter_recently_viewed_products() {
		if ( is_singular( 'product' ) ) {
			$enable_recenly_view          = Funiter_Functions::funiter_get_option( 'funiter_woo_recently_enable', 'disable' );
			$lazy_options                 = Funiter_Functions::funiter_get_option( 'funiter_theme_lazy_load', 0 );
			$enable_single_product_mobile = Funiter_Functions::funiter_get_option( 'enable_single_product_mobile', false );
			
			// Don't show recently viewed products on real mobile if $enable_single_product_mobile
			if ( $enable_single_product_mobile ) {
				$enable_recenly_view = 'disable';
			}
			
			if ( $enable_recenly_view == 'disable' ) {
				return;
			}
			// Get viewed products
			$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
			
			// Reverse array (order of view)
			$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
			
			// If there are no $viewed_products return;
			if ( empty( $viewed_products ) ) {
				return;
			}
			
			ob_start();
			
			// Set query args using $limit and $viewed_products
			$query_args = array(
				'posts_per_page' => 20,
				'no_found_rows'  => 1,
				'post_status'    => 'publish',
				'post_type'      => 'product',
				'post__in'       => $viewed_products,
				'orderby'        => 'post__in',
			);
			
			$query = new WP_Query( $query_args );
			if ( $query->have_posts() ) {
				$prefix       = 'funiter_woo_recently';
				$default_text = esc_html__( 'Recently Viewed', 'funiter' );
				$title        = Funiter_Functions::funiter_get_option( $prefix . '_products_title', $default_text );
				$woo_ls_items = Funiter_Functions::funiter_get_option( $prefix . '_ls_items', 3 );
				$woo_lg_items = Funiter_Functions::funiter_get_option( $prefix . '_lg_items', 3 );
				$woo_md_items = Funiter_Functions::funiter_get_option( $prefix . '_md_items', 3 );
				$woo_sm_items = Funiter_Functions::funiter_get_option( $prefix . '_sm_items', 2 );
				$woo_xs_items = Funiter_Functions::funiter_get_option( $prefix . '_xs_items', 1 );
				$woo_ts_items = Funiter_Functions::funiter_get_option( $prefix . '_ts_items', 1 );
				$atts         = array(
					'owl_dots'              => 'true',
					'owl_loop'              => 'false',
					'owl_ts_items'          => $woo_ts_items,
					'owl_xs_items'          => $woo_xs_items,
					'owl_sm_items'          => $woo_sm_items,
					'owl_md_items'          => $woo_md_items,
					'owl_lg_items'          => $woo_lg_items,
					'owl_ls_items'          => $woo_ls_items,
					'owl_responsive_margin' => 1200,
				);
				$owl_settings = apply_filters( 'funiter_carousel_data_attributes', 'owl_', $atts ); ?>
                <div class="col-sm-12 col-xs-12 products product-grid recently-review-product">
                    <div class="single-block-wrap">
                        <h2 class="single-block-title">
                            <span><?php echo esc_html( $title ); ?></span>
                        </h2>
                    </div>
                    <div class="owl-slick owl-products equal-container better-height" <?php echo esc_attr( $owl_settings ); ?>><?php while ( $query->have_posts() ) : $query->the_post();
							global $product;
							// GET SIZE IMAGE SETTING
							$width  = 500;
							$height = 500;
							$crop   = true;
							$size   = wc_get_image_size( 'shop_catalog' );
							if ( $size ) {
								$width  = $size['width'];
								$height = $size['height'];
								if ( ! $size['crop'] ) {
									$crop = false;
								}
							}
							$data_src                = '';
							$attachment_ids          = $product->get_gallery_image_ids();
							$gallery_class_img       = $class_img = array( 'img-responsive' );
							$thumb_gallery_class_img = $thumb_class_img = array( 'thumb-link' );
							$width                   = apply_filters( 'funiter_shop_product_thumb_width', $width );
							$height                  = apply_filters( 'funiter_shop_product_thumb_height', $height );
							$image_thumb             = apply_filters( 'funiter_resize_image', get_post_thumbnail_id( $product->get_id() ), $width, $height, $crop, true );
							$image_url               = $image_thumb['url'];
							$default_attributes      = $product->get_default_attributes();
							if ( $lazy_options == 1 && empty( $default_attributes ) ) {
								$class_img[] = 'lazy';
								$data_src    = 'data-src=' . esc_attr( $image_thumb['url'] );
								$image_url   = "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20" . $width . "%20" . $height . "%27%2F%3E";
							}
							if ( $attachment_ids && has_post_thumbnail() ) {
								$gallery_class_img[]       = 'wp-post-image';
								$thumb_gallery_class_img[] = 'woocommerce-product-gallery__image';
							} else {
								$class_img[]       = 'wp-post-image';
								$thumb_class_img[] = 'woocommerce-product-gallery__image';
							}
							?>
                            <div class="product-recent-item">
                                <div class="thumb-item">
                                    <a class="thumb-link" href="<?php the_permalink(); ?>">
                                        <img class="<?php echo implode( ' ', $class_img ); ?>"
                                             src="<?php echo esc_attr( $image_url ); ?>"
											<?php echo esc_attr( $data_src ); ?> <?php echo image_hwstring( $width, $height ); ?>
                                             alt="<?php echo the_title_attribute(); ?>">
                                    </a>
                                </div>
                            </div>
							
							<?php
						
						endwhile; ?>

                    </div>
                </div>
			
			<?php };
			
			wp_reset_postdata();
			
			$content = ob_get_clean();
			echo apply_filters( 'funiter_recently_viewed_products', $content );
		}
	}
}

if ( ! function_exists( 'funiter_recently_viewed_products_sliding' ) ) {
	function funiter_recently_viewed_products_sliding() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		$prefix              = 'funiter_woo_recently';
		$default_text        = esc_html__( 'Recently Viewed', 'funiter' );
		$title               = Funiter_Functions::funiter_get_option( $prefix . '_products_title', $default_text );
		$enable_recenly_view = Funiter_Functions::funiter_get_option( 'funiter_woo_recently_enable', 'disable' );
		$enable_lazy_load    = Funiter_Functions::funiter_get_option( 'funiter_theme_lazy_load', 0 );
		if ( $enable_recenly_view == 'disable' ) {
			return;
		}
		// Get viewed products
		$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
		
		// Reverse array (order of view)
		$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
		
		// If there are no $viewed_products return;
		if ( empty( $viewed_products ) ) {
			return;
		}
		
		$html = '';
		
		// Set query args using $limit and $viewed_products
		$query_args = array(
			'posts_per_page' => 20,
			'no_found_rows'  => 1,
			'post_status'    => 'publish',
			'post_type'      => 'product',
			'post__in'       => $viewed_products,
			'orderby'        => 'post__in',
		);
		
		$query = new WP_Query( $query_args );
		
		if ( $query->have_posts() ) {
			$product_size_args = array(
				'width'  => 300,
				'height' => 300
			);
			?>
            <div class="funiter-recent-viewed-products-wraper">
                <div class="button-sliding"><span class="flaticon-view"></span></div>
                <div class="funiter-recent-viewed-products-wrap">
                    <div class="button-close"><span class="flaticon-close"></span></div>
                    <h2 class="single-block-title">
                        <span><?php echo esc_html( $title ); ?></span>
                    </h2>
                    <div class="funiter-recent-viewed-products-sliding">
						<?php
						while ( $query->have_posts() ) {
							$query->the_post();
							?>
                            <div class="recent-viewed-product recent-viewed-item">
								<?php wc_get_template( 'product-styles/content-product-style-1.php', $product_size_args ); ?>
                            </div>
							<?php
						}
						?>
                    </div>
                </div>
            </div>
			<?php
		}
		
		wp_reset_postdata();
		
		$html .= ob_get_clean();
		echo apply_filters( 'funiter_recently_viewed_products_sliding', $html );
		
	}
}

if ( ! function_exists( 'funiter_template_loop_product_title' ) ) {
	function funiter_template_loop_product_title() {
		$title_class = array( 'product-name product_title' );
		?>
        <h3 class="<?php echo esc_attr( implode( ' ', $title_class ) ); ?>">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
		<?php
	}
}
if ( ! function_exists( 'funiter_template_loop_product_thumbnail' ) ) {
	function funiter_template_loop_product_thumbnail( $args = array() ) {
		global $product;
		// GET SIZE IMAGE SETTING
		$crop      = true;
		$size      = wc_get_image_size( 'shop_catalog' );
		$wc_width  = 300;
		$wc_height = 300;
		if ( $size ) {
			$wc_width  = $size['width'];
			$wc_height = $size['height'];
			if ( ! $size['crop'] ) {
				$crop = false;
			}
		}
		
		$width  = isset( $args['width'] ) ? intval( $args['width'] ) : $wc_width;
		$height = isset( $args['height'] ) ? intval( $args['height'] ) : $wc_height;
		
		$attachment_ids          = $product->get_gallery_image_ids();
		$gallery_class_img       = $class_img = array( 'img-responsive' );
		$thumb_gallery_class_img = $thumb_class_img = array( 'thumb-link' );
		$width                   = apply_filters( 'funiter_shop_product_thumb_width', $width );
		$height                  = apply_filters( 'funiter_shop_product_thumb_height', $height );
		
		if ( $attachment_ids && has_post_thumbnail() ) {
			$gallery_class_img[]       = 'wp-post-image';
			$thumb_gallery_class_img[] = 'woocommerce-product-gallery__image';
		} else {
			$thumb_class_img[] = 'woocommerce-product-gallery__image';
		}
		$first_img = Funiter_Functions::resize_image( get_post_thumbnail_id( $product->get_id() ), null, $width, $height, true, true, false );
		?>
        <a class="<?php echo implode( ' ', $thumb_class_img ); ?>" href="<?php the_permalink(); ?>">
			<?php echo Funiter_Functions::img_output( $first_img ); ?>
        </a>
		<?php
		$enable_second_product_img = Funiter_Functions::funiter_get_option( 'enable_second_product_img', false );
		if ( $attachment_ids && has_post_thumbnail() && $enable_second_product_img ) {
			$second_img = Funiter_Functions::resize_image( $attachment_ids[0], null, $width, $height, true, true, false );
			?>
            <div class="second-image">
                <a href="<?php the_permalink(); ?>" class="<?php echo implode( ' ', $thumb_gallery_class_img ); ?>">
					<?php echo Funiter_Functions::img_output( $second_img, implode( ' ', $gallery_class_img ) ); ?>
                </a>
            </div>
			<?php
		};
	}
}
if ( ! function_exists( 'funiter_custom_new_flash' ) ) {
	function funiter_custom_new_flash() {
		global $post, $product;
		$postdate      = get_the_time( 'Y-m-d' );
		$postdatestamp = strtotime( $postdate );
		$newness       = Funiter_Functions::funiter_get_option( 'funiter_product_newness', 7 );
		if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) :
			echo apply_filters( 'woocommerce_new_flash', '<span class="onnew"><span class="text">' . esc_html__( 'New', 'funiter' ) . '</span></span>', $post, $product );
		else:
			echo apply_filters( 'woocommerce_new_flash', '<span class="onnew hidden"></span>', $post, $product );
		endif;
	}
}
if ( ! function_exists( 'funiter_woocommerce_group_flash' ) ) {
	function funiter_woocommerce_group_flash() {
		?>
        <div class="flash">
			<?php do_action( 'funiter_group_flash_content' ); ?>
        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_custom_sale_flash' ) ) {
	function funiter_custom_sale_flash( $text ) {
		$percent = funiter_get_percent_discount();
		if ( $percent != '' ) {
			return '<span class="onsale"><span class="text">' . $percent . '</span></span>';
		}
		
		return '';
	}
}
if ( ! function_exists( 'funiter_get_percent_discount' ) ) {
	function funiter_get_percent_discount() {
		global $product;
		$percent = '';
		if ( $product->is_on_sale() ) {
			if ( $product->is_type( 'variable' ) ) {
				$available_variations = $product->get_available_variations();
				$maximumper           = 0;
				$minimumper           = 0;
				$percentage           = 0;
				for ( $i = 0; $i < count( $available_variations ); ++ $i ) {
					$variation_id      = $available_variations[ $i ]['variation_id'];
					$variable_product1 = new WC_Product_Variation( $variation_id );
					$regular_price     = $variable_product1->get_regular_price();
					$sales_price       = $variable_product1->get_sale_price();
					if ( $regular_price > 0 && $sales_price > 0 ) {
						$percentage = round( ( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ), 0 );
					}
					if ( $minimumper == 0 ) {
						$minimumper = $percentage;
					}
					if ( $percentage > $maximumper ) {
						$maximumper = $percentage;
					}
					if ( $percentage < $minimumper ) {
						$minimumper = $percentage;
					}
				}
				if ( $minimumper == $maximumper ) {
					$percent .= '-' . $minimumper . '%';
				} else {
					$percent .= '-(' . $minimumper . '-' . $maximumper . ')%';
				}
			} else {
				if ( $product->get_regular_price() > 0 && $product->get_sale_price() > 0 ) {
					$percentage = round( ( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 ), 0 );
					$percent    .= '-' . $percentage . '%';
				}
			}
		}
		
		return $percent;
	}
}
if ( ! function_exists( 'funiter_function_shop_loop_item_countdown' ) ) {
	function funiter_function_shop_loop_item_countdown() {
		global $product;
		$date = funiter_get_max_date_sale( $product->get_id() );
		if ( $date > 0 ) {
			?>
            <div class="countdown-product">
                <h5 class="title"><?php echo esc_html__( 'Deal ends in :', 'funiter' ); ?></h5>
                <div class="funiter-countdown"
                     data-datetime="<?php echo date( 'm/j/Y g:i:s', $date ); ?>">
                </div>
            </div>
			<?php
		}
	}
}
if ( ! function_exists( 'funiter_template_single_available' ) ) {
	function funiter_template_single_available() {
		global $product;
		if ( $product->is_in_stock() ) {
			$class = 'in-stock available-product';
			$text  = $product->get_stock_quantity() . ' In Stock';
		} else {
			$class = 'out-stock available-product';
			$text  = 'Out stock';
		}
		?>

        <p class="stock <?php echo esc_attr( $class ); ?>">
			<?php echo esc_html__( 'Availability:', 'funiter' ); ?>
            <span> <?php echo esc_html( $text ); ?></span>
        </p>
		<?php
	}
}
if ( ! function_exists( 'funiter_function_shop_loop_process_variable' ) ) {
	function funiter_function_shop_loop_process_variable() {
		global $product;
		$units_sold   = get_post_meta( $product->get_id(), 'total_sales', true );
		$availability = $product->get_stock_quantity();
		if ( $availability == '' ) {
			$percent = 0;
		} else {
			$total_percent = $availability + $units_sold;
			$percent       = round( ( ( $units_sold / $total_percent ) * 100 ), 0 );
		}
		?>
        <div class="process-valiable">
            <div class="valiable-text">
                <span class="text">
                    <?php
                    echo esc_attr( $percent ) . '%';
                    echo esc_html__( ' already claimed', 'funiter' );
                    ?>
                </span>
                <span class="text">
                    <?php echo esc_html__( 'Available: ', 'funiter' ) ?>
                    <span>
                        <?php
                        if ( $availability != '' ) {
	                        echo esc_html( $availability );
                        } else {
	                        echo esc_html__( 'Unlimit', 'funiter' );
                        }
                        ?>
                    </span>
                </span>
            </div>
            <span class="valiable-total total">
                <span class="process"
                      style="width: <?php echo esc_attr( $percent ) . '%' ?>"></span>
            </span>
        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_get_max_date_sale' ) ) {
	function funiter_get_max_date_sale( $product_id ) {
		$date_now = current_time( 'timestamp', 0 );
		// Get variations
		$args          = array(
			'post_type'   => 'product_variation',
			'post_status' => array( 'private', 'publish' ),
			'numberposts' => - 1,
			'orderby'     => 'menu_order',
			'order'       => 'asc',
			'post_parent' => $product_id,
		);
		$variations    = get_posts( $args );
		$variation_ids = array();
		if ( $variations ) {
			foreach ( $variations as $variation ) {
				$variation_ids[] = $variation->ID;
			}
		}
		$sale_price_dates_to = false;
		if ( ! empty( $variation_ids ) ) {
			global $wpdb;
			$sale_price_dates_to = $wpdb->get_var( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_sale_price_dates_to' and post_id IN(" . join( ',', $variation_ids ) . ") ORDER BY meta_value DESC LIMIT 1" );
			if ( $sale_price_dates_to != '' ) {
				return $sale_price_dates_to;
			}
		}
		if ( ! $sale_price_dates_to ) {
			$sale_price_dates_to   = get_post_meta( $product_id, '_sale_price_dates_to', true );
			$sale_price_dates_from = get_post_meta( $product_id, '_sale_price_dates_from', true );
			if ( $sale_price_dates_to == '' || $date_now < $sale_price_dates_from ) {
				$sale_price_dates_to = '0';
			}
		}
		
		return $sale_price_dates_to;
	}
}
/* MINI CART */
if ( ! function_exists( 'funiter_header_cart_link' ) ) {
	function funiter_header_cart_link() {
		?>
        <div class="shopcart-dropdown block-cart-link" data-funiter="funiter-dropdown">
            <a class="link-dropdown" href="<?php echo wc_get_cart_url(); ?>">
                <span class="flaticon-bag">
                    <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </span>
            </a>
            <div class="cart-detail">
                <p><?php echo esc_html__( 'Your cart', 'funiter' ); ?></p>
                <span class="total-price"><?php printf( '%s', WC()->cart->get_cart_subtotal() ); ?></span>
            </div>
        </div>
		<?php
	}
}
/* Cart Link Mobile */
if ( ! function_exists( 'funiter_header_cart_link_mobile' ) ) {
	function funiter_header_cart_link_mobile() {
		?>
        <a class="cart-link-mobile" href="<?php echo wc_get_cart_url(); ?>">
                <span class="flaticon-bag">
                    <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </span>
        </a>
		<?php
	}
}
if ( ! function_exists( 'funiter_header_mini_cart' ) ) {
	function funiter_header_mini_cart() {
		?>
        <div class="block-minicart funiter-mini-cart hii funiter-dropdown">
			<?php
			funiter_header_cart_link();
			the_widget( 'WC_Widget_Cart', 'title=' );
			?>
        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_cart_link_fragment' ) ) {
	function funiter_cart_link_fragment( $fragments ) {
		ob_start();
		funiter_header_cart_link();
		$fragments['div.block-cart-link'] = ob_get_clean();
		
		return $fragments;
	}
}
if ( ! function_exists( 'funiter_header_wishlist' ) ) {
	function funiter_header_wishlist() {
		if ( defined( 'YITH_WCWL' ) ) :
			$yith_wcwl_wishlist_page_id = get_option( 'yith_wcwl_wishlist_page_id' );
			$wishlist_url = get_page_link( $yith_wcwl_wishlist_page_id );
			if ( $wishlist_url != '' ) : ?>
                <div class="block-wishlist">
                    <a class="woo-wishlist-link" href="<?php echo esc_url( $wishlist_url ); ?>">
                        <span class="flaticon-heart-1"></span>
                    </a>
                </div>
			<?php endif;
		endif;
	}
}
if ( ! function_exists( 'funiter_wisth_list_url' ) ) {
	function funiter_wisth_list_url() {
		$url = '';
		if ( function_exists( 'yith_wcwl_object_id' ) ) {
			$wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );
			$url              = get_the_permalink( $wishlist_page_id );
		}
		
		return $url;
	}
}

if ( ! function_exists( 'funiter_yith_wcwl_ajax_update_count' ) ) {
	// https://support.yithemes.com/hc/en-us/articles/115001372967-Wishlist-How-to-count-number-of-products-wishlist-in-ajax
	function funiter_yith_wcwl_ajax_update_count() {
	
	}
}

// Share Single
if ( ! function_exists( 'funiter_product_share' ) ) {
	function funiter_product_share() {
		$enable_single_product_sharing = Funiter_Functions::funiter_get_option( 'enable_single_product_sharing', false );
		if ( $enable_single_product_sharing ) {
			
			$facecbook_url  = add_query_arg( array( 'u' => rawurlencode( get_permalink() ) ), 'https://www.facebook.com/sharer/sharer.php' );
			$twitter_url    = add_query_arg( array(
				                                 'url'  => rawurlencode( get_permalink() ),
				                                 'text' => rawurlencode( get_the_title() ),
			                                 ), 'https://twitter.com/intent/tweet' );
			$pinterest_url  = add_query_arg( array(
				                                 'url'         => rawurlencode( get_permalink() ),
				                                 'media'       => get_the_post_thumbnail_url(),
				                                 'description' => rawurlencode( get_the_title() ),
			                                 ), 'http://pinterest.com/pin/create/button' );
			$googleplus_url = add_query_arg( array(
				                                 'url'  => rawurlencode( get_permalink() ),
				                                 'text' => rawurlencode( get_the_title() ),
			                                 ), 'https://plus.google.com/share' );
			
			$enable_fb_sharing    = Funiter_Functions::funiter_get_option( 'enable_single_product_sharing_fb' );
			$enable_tw_sharing    = Funiter_Functions::funiter_get_option( 'enable_single_product_sharing_tw' );
			$enable_pin_sharing   = Funiter_Functions::funiter_get_option( 'enable_single_product_sharing_pinterest' );
			$enable_gplus_sharing = Funiter_Functions::funiter_get_option( 'enable_single_product_sharing_gplus' );
			
			if ( $enable_fb_sharing || $enable_tw_sharing || $enable_pin_sharing || $enable_gplus_sharing ) {
				?>
                <div class="social-share-product">
                    <div class="button-share"><span
                                class="fa fa-share-alt"></span><?php echo esc_html__( 'Share', 'funiter' ) ?></div>
                    <div class="share-overlay"></div>
                    <div class="social-share-product-inner">
                        <h3 class="title-share"><?php echo esc_html__( 'Share This', 'funiter' ) ?></h3>
                        <div class="funiter-social-product">
							<?php if ( $enable_tw_sharing ) { ?>
                                <a href="<?php echo esc_url( $twitter_url ) ?>" target="_blank"
                                   class="twitter-share-link"
                                   title="<?php echo esc_attr__( 'Twitter', 'funiter' ) ?>">
                                    <i class="fa fa-twitter"></i>
                                </a>
							<?php } ?>
							<?php if ( $enable_fb_sharing ) { ?>
                                <a href="<?php echo esc_url( $facecbook_url ) ?>" target="_blank"
                                   class="facebook-share-link"
                                   title="<?php echo esc_attr__( 'Facebook', 'funiter' ) ?>">
                                    <i class="fa fa-facebook"></i>
                                </a>
							<?php } ?>
							<?php if ( $enable_gplus_sharing ) { ?>
                                <a href="<?php echo esc_url( $googleplus_url ) ?>" target="_blank"
                                   class="google-share-link"
                                   title="<?php echo esc_attr__( 'Google Plus', 'funiter' ) ?>">
                                    <i class="fa fa-google-plus"></i>
                                </a>
							<?php } ?>
							<?php if ( $enable_pin_sharing ) { ?>
                                <a href="<?php echo esc_url( $pinterest_url ) ?>" target="_blank"
                                   class="pinterest-share-link"
                                   title="<?php echo esc_attr__( 'Pinterest', 'funiter' ) ?>">
                                    <i class="fa fa-pinterest-p"></i>
                                </a>
							<?php } ?>
                        </div>
                    </div>
                </div>
				<?php
			}
		}
		
	}
}

if ( ! function_exists( 'woocommerce_product_archive_description' ) ) {
	/**
	 * Override the woocommerce_product_archive_description() function
	 */
	function woocommerce_product_archive_description() {
		// Don't display the description on search results page.
		if ( is_search() ) {
			return;
		}
		
		if ( is_post_type_archive( 'product' ) && in_array( absint( get_query_var( 'paged' ) ), array(
				0,
				1
			), true ) ) {
			
			if ( ! is_tax() ) {
				$shop_page = get_post( wc_get_page_id( 'shop' ) );
				if ( $shop_page ) {
					$description = wc_format_content( $shop_page->post_content );
					if ( $description ) {
						echo '<div class="page-description funiter-page-description is-shop">' . $description . '</div>'; // WPCS: XSS ok.
					} else {
						echo '<div class="page-description funiter-page-description funier-empty-description">' . $description . '</div>'; // WPCS: XSS ok.
					}
				}
			}
			
		} else {
			echo '<div class="page-description funiter-page-description funier-empty-description funiter-none-shop-description"></div>'; // WPCS: XSS ok.
		}
	}
}

if ( ! function_exists( 'funiter_cart_title' ) ) {
	function funiter_cart_title() {
		?>
        <h2 class="page-title cart-title">
            <span><?php single_post_title(); ?></span>
        </h2>
		<?php
	}
}