<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this "Funiter_Countdown"
 */
if ( !class_exists( 'Funiter_Shortcode_Countdown' ) ) {
	class Funiter_Shortcode_Countdown extends Funiter_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'countdown';

		static public function add_css_generate( $atts )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'funiter_countdown', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';
			return apply_filters( 'Funiter_Shortcode_Countdown_css', $css, $atts );
		}

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'funiter_countdown', $atts ) : $atts;
			extract( $atts );
			$css_class    = array( 'funiter-countdown-sc' );
			$css_class[]  = $atts['style'];
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'funiter_countdown', $atts );
            $countdown_link = vc_build_link($atts['link']);
            if ($countdown_link['url']) {
                $link_url = $countdown_link['url'];
            } else {
                $link_url = '#';
            }
            if ($countdown_link['target']) {
                $link_target = $countdown_link['target'];
            } else {
                $link_target = '_self';
            }
			ob_start(); ?>
			<div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<div class="head">
					<?php if ( $atts['title'] ) : ?>
						<h4 class="title">
							<?php echo esc_html( $atts['title'] ); ?>
						</h4>
					<?php endif; ?>
					<?php if ( $atts['subtitle'] ) : ?>
						<p class="subtitle">
							<?php echo esc_html( $atts['subtitle'] ); ?>
						</p>
					<?php endif; ?>
					<?php if ( $content ) : ?>
						<div class="text-date">
							<?php echo wp_specialchars_decode( $content ); ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="funiter-countdown"
					 data-datetime="<?php echo esc_attr( $atts['date'] ); ?>">
				</div>
                <?php if($countdown_link['title']) :?>
                    <a class="button" target="<?php echo esc_attr($link_target); ?>" href="<?php echo esc_url($link_url); ?>"><?php echo esc_html($countdown_link['title']); ?></a>
                <?php endif; ?>
			</div>
			<?php
			$html = ob_get_clean();
			return apply_filters( 'Funiter_Shortcode_Countdown', $html, $atts, $content );
		}
	}

	new Funiter_Shortcode_Countdown();
}