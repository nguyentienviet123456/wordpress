<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this "Funiter_Slide"
 */
if ( !class_exists( 'Funiter_Shortcode_Slide' ) ) {
	class Funiter_Shortcode_Slide extends Funiter_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'slide';

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'funiter_slide', $atts ) : $atts;
			extract( $atts );
			$css_class    = array( 'funiter-slide equal-container better-height' );
			$css_class[]  = $atts['el_class'];
			$css_class[]  = $atts['owl_rows_space'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'funiter_slide', $atts );
            $owl_settings = '';
            if ($atts['margin_responsive'] && $atts['margin_responsive'] == 'yes') {
                $atts['owl_responsive_margin'] = 1200;
            }
			$owl_settings .= apply_filters( 'funiter_carousel_data_attributes', 'owl_', $atts );
            $owl_class    = array();
            $owl_class[]  = $atts['owl_navigation_style'];
            $owl_class[]  = $atts['owl_navigation_color'];
            $owl_class[]  = $atts['owl_dots_color'];
			ob_start(); ?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['slider_title'] ) : ?>
                    <h3 class="funiter-title"><span><?php echo esc_html( $atts['slider_title'] ); ?></span></h3>
				<?php endif; ?>
                <div class="owl-slick <?php echo esc_attr( implode( ' ', $owl_class ) ); ?>" <?php echo esc_attr( $owl_settings ); ?>>
					<?php echo wpb_js_remove_wpautop( $content ); ?>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Funiter_Shortcode_Slide', $html, $atts, $content );
		}
	}

	new Funiter_Shortcode_Slide();
}