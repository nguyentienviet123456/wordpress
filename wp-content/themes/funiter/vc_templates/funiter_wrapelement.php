<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this "Funiter_Wrapelement"
 */
if ( !class_exists( 'Funiter_Shortcode_Wrapelement' ) ) {

	class Funiter_Shortcode_Wrapelement extends Funiter_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'wrapelement';


		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'funiter_wrapelement', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );

			$css_class   = array( 'funiter-container' );

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}
			ob_start();
			?>
			<div class="<?php echo esc_attr( implode( ' ', $css_class )); ?>">
				<?php if ( $atts['element_title'] ) : ?>
                    <h2 class="widgettitle">
                        <?php echo esc_html( $atts['element_title'] ); ?>
                    </h2>
				<?php endif; ?>
				<div class="funiter-container-inner">
					<?php echo wpb_js_remove_wpautop( $content ); ?>
				</div>
			</div>
			<?php
			$html = ob_get_clean();
			return apply_filters( 'Funiter_Shortcode_Wrapelement', $html, $atts, $content);
		}
	}
	new Funiter_Shortcode_Wrapelement();
}