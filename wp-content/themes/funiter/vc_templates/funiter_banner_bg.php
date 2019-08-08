<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 *
 * @var $atts
 * Shortcode class
 * @var $this "Funiter_Banner"
 */
if ( ! class_exists( 'Funiter_Shortcode_Banner_Bg' ) ) {
	class Funiter_Shortcode_Banner_Bg extends Funiter_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'banner_bg';
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'funiter_banner_bg', $atts ) : $atts;
			extract( $atts );
			$css_class           = array( 'funiter-banner-bg' );
			$css_class[]         = $atts['style'];
			$css_class[]         = $atts['el_class'];
			$class_editor        = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]         = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'funiter_banner_bg', $atts );
			$banner_link_default = array(
				'url'    => '',
				'title'  => '',
				'target' => '_self',
				'rel'    => ''
			);
			$banner_link         = vc_build_link( $atts['link'] );
			$banner_link         = wp_parse_args( $banner_link, $banner_link_default );
			
			$width  = intval( $width );
			$height = intval( $height );
			if ( $width <= 0 ) {
				$width = 4000;
			}
			if ( $height <= 0 ) {
				$height = 4000;
			}

//			echo '<pre>';
//			print_r( $img_id );
//			echo '</pre>';
			
			$banner_img_trans_url = Funiter_Functions::no_image( array(
				                                                     'width'  => $width,
				                                                     'height' => $height
			                                                     ), false, true );
			$banner_img_trans_arg = array(
				'url'    => $banner_img_trans_url,
				'width'  => $width,
				'height' => $height
			);
			$banner_img           = Funiter_Functions::resize_image( $img_id, null, $width, $height, true, true, false );

//			echo '<pre>';
//			print_r( $banner_img );
//			echo '</pre>';
			
			/* START */
			ob_start(); ?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <div class="banner-bg-inner"
                     style="background-image: url(<?php echo esc_url( $banner_img['url'] ); ?>);">
                    <figure class="banner-thumb">
						<?php echo Funiter_Functions::img_output( $banner_img_trans_arg, '', '', '', true ); ?>
                    </figure>
                    <div class="banner-info">
                        <div class="banner-top">
                            <div class="banner-top-left">
								<?php if ( $title != '' ) { ?>
                                    <h6 class="title"><?php echo wp_specialchars_decode( $title ); ?></h6>
								<?php } ?>
								<?php if ( $bigtitle != '' ) { ?>
                                    <h3 class="bigtitle"><?php echo wp_specialchars_decode( $bigtitle ); ?></h3>
								<?php } ?>
                            </div>
                        </div>
						<?php if ( $banner_link['url'] != '' ) { ?>
                            <a class="button" target="<?php echo esc_attr( $banner_link['target'] ); ?>"
                               rel="<?php echo esc_attr( $banner_link['rel'] ); ?>"
                               href="<?php echo esc_url( $banner_link['url'] ); ?>"><?php echo esc_html( $banner_link['title'] ); ?></a>
						<?php } ?>
                    </div>
                </div>
            </div>
			<?php
			$html = ob_get_clean();
			
			return apply_filters( 'Funiter_Shortcode_Banner_Bg', $html, $atts, $content );
		}
	}
	
	new Funiter_Shortcode_Banner_Bg();
}