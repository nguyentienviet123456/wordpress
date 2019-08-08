<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this "Funiter_Heading"
 */
if ( !class_exists( 'Funiter_Shortcode_Heading' ) ) {
	class Funiter_Shortcode_Heading extends Funiter_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'heading';

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'funiter_heading', $atts ) : $atts;
			extract( $atts );
			$css_class    = array( 'funiter-heading' );
			$css_class[]  = $atts['style'];
			$css_class[]  = $atts['type_color'];
			$css_class[]  = $atts['position'];
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'funiter_heading', $atts );
			ob_start(); ?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['title'] ) : ?>
                    <h4 class="funiter-title">
                        <?php echo wp_specialchars_decode( $atts['title'] ); ?>
                    </h4>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Funiter_Shortcode_Heading', $html, $atts, $content );
		}
	}

	new Funiter_Shortcode_Heading();
}