<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( ! class_exists( 'Funiter_Shortcode_Instagram' ) ) {
	class Funiter_Shortcode_Instagram extends Funiter_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'instagram';
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'funiter_instagram', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class            = array( 'funiter-instagram-sc' );
			$css_class[]          = $atts['style'];
			$css_class[]          = $atts['el_class'];
			$class_editor         = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]          = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'funiter_instagram', $atts );
			$owl_settings         = '';
			$instagram_list_class = array();
			$instagram_item_class = array();
			if ( $atts['productsliststyle'] == 'grid' ) {
				$instagram_list_class[] = 'instagram-grid row auto-clear';
				$instagram_item_class[] = $atts['boostrap_rows_space'];
				$instagram_item_class[] = 'col-bg-' . $atts['boostrap_bg_items'];
				$instagram_item_class[] = 'col-lg-' . $atts['boostrap_lg_items'];
				$instagram_item_class[] = 'col-md-' . $atts['boostrap_md_items'];
				$instagram_item_class[] = 'col-sm-' . $atts['boostrap_sm_items'];
				$instagram_item_class[] = 'col-xs-' . $atts['boostrap_xs_items'];
				$instagram_item_class[] = 'col-ts-' . $atts['boostrap_ts_items'];
			}
			if ( $atts['productsliststyle'] == 'owl' ) {
				if ( $atts['style'] == 'style-01' ) {
					$atts['owl_responsive_margin'] = 1200;
				}
				$instagram_list_class[] = 'instagram-owl owl-slick';
				$instagram_item_class[] = $atts['owl_navigation_style'];
				$instagram_item_class[] = $atts['owl_navigation_color'];
				$instagram_item_class[] = $atts['owl_dots_color'];
				$instagram_item_class[] = $atts['owl_rows_space'];
				$owl_settings           .= apply_filters( 'funiter_carousel_data_attributes', 'owl_', $atts );
			}
			$instagram_link = vc_build_link( $atts['link'] );
			if ( $instagram_link['url'] ) {
				$link_url = $instagram_link['url'];
			} else {
				$link_url = '#';
			}
			if ( $instagram_link['target'] ) {
				$link_target = $instagram_link['target'];
			} else {
				$link_target = '_self';
			}
			/* START */
			ob_start(); ?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['title'] ) : ?>
                    <h3 class="widgettitle"><span
                                class="flaticon-instagram"></span><?php echo esc_html( $atts['title'] ); ?></h3>
				<?php endif;
				if ( intval( $atts['id_instagram'] ) === 0 || intval( $atts['token'] ) === 0 ) {
					esc_html_e( 'No user ID specified.', 'funiter' );
				}
				if ( ! empty( $id_instagram ) && ! empty( $token ) ) {
					$response = wp_remote_get( 'https://api.instagram.com/v1/users/' . esc_attr( $id_instagram ) . '/media/recent/?access_token=' . esc_attr( $token ) . '&count=' . esc_attr( $atts['items_limit'] ) );
					if ( ! is_wp_error( $response ) ) {
						$items         = array();
						$response_body = json_decode( $response['body'] );
						$response_code = json_decode( $response['response']['code'] );
						if ( $response_code != 200 ) {
							echo '<p>' . esc_html__( 'User ID and access token do not match. Please check again.', 'funiter' ) . '</p>';
						} else {
							$items_as_objects = $response_body->data;
							if ( ! empty( $items_as_objects ) ) {
								foreach ( $items_as_objects as $item_object ) {
									$item['link']     = $item_object->link;
									$item['user']     = $item_object->user;
									$item['likes']    = $item_object->likes->count;
									$item['comments'] = $item_object->comments->count;
									$item['src']      = $item_object->images->{$atts['image_resolution']}->url;
									$item['width']    = $item_object->images->{$atts['image_resolution']}->width;
									$item['height']   = $item_object->images->{$atts['image_resolution']}->height;
									$items[]          = $item; ?>
									<?php
								}
							}
						}
					}
				}
				
				if ( isset( $items ) && $items ):?>
                    <div class="<?php echo implode( ' ', $instagram_list_class ); ?>" <?php echo esc_attr( $owl_settings ); ?>>
						<?php foreach ( $items as $item ):
							$img_lazy = "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20" . $item['width'] . "%20" . $item['height'] . "%27%2F%3E"; ?>
                            <div class="<?php echo implode( ' ', $instagram_item_class ); ?>">
                                <a target="_blank" href="<?php echo esc_url( $item['link'] ) ?>" class="item">
                                    <img class="img-responsive lazy" src="<?php echo esc_attr( $img_lazy ); ?>"
                                         data-src="<?php echo esc_url( $item['src'] ); ?>"
										<?php echo image_hwstring( $item['width'], $item['height'] ); ?>
                                         alt="<?php echo the_title_attribute(); ?>"/>
                                    <span class="instagram-info">
                                         <span class="social-wrap">
                                            <span class="social-info"><?php echo esc_attr( $item['likes'] ); ?><i
                                                        class="flaticon-heart"></i></span>
                                            <span class="social-info"><?php echo esc_attr( $item['comments'] ); ?><i
                                                        class="flaticon-comment-1"></i></span>
                                        </span>
                                    </span>
                                </a>
                            </div>
						<?php endforeach; ?>
                    </div>
				<?php endif; ?>
				<?php if ( $atts['style'] == 'style-01' && $instagram_link['title'] ) : ?>
                    <a class="button" target="<?php echo esc_attr( $link_target ); ?>"
                       href="<?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $instagram_link['title'] ); ?></a>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();
			
			return apply_filters( 'Funiter_Shortcode_Instagram', $html, $atts, $content );
		}
	}
	
	new Funiter_Shortcode_Instagram();
}