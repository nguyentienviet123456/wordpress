<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 *
 * @var $atts
 * Shortcode class
 * @var $this "Funiter_Shortcode_Adv_Text"
 */
if ( ! class_exists( 'Funiter_Shortcode_Adv_Text' ) ) {
	class Funiter_Shortcode_Adv_Text extends Funiter_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'adv_text';
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'funiter_adv_text', $atts ) : $atts;
			extract( $atts );
			$css_class    = array( 'funiter-adv-text' );
			$css_class[]  = $atts['style'];
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'funiter_adv_text', $atts );
			
			$html = '';
			
			$mobile_text      = str_replace( '`}`', ']', str_replace( '`{`', '[', str_replace( '``', '"', $mobile_text ) ) );
			$none_mobile_text = str_replace( '`}`', ']', str_replace( '`{`', '[', str_replace( '``', '"', $none_mobile_text ) ) );
			
			if ( Funiter_Functions::is_mobile() ) {
				if ( $mobile_text != '' ) {
					$html .= do_shortcode( $mobile_text );
				}
			} else {
				if ( $none_mobile_text ) {
					$html .= do_shortcode( $none_mobile_text );
				}
			}
			
			return apply_filters( 'Funiter_Shortcode_Adv_Text', $html, $atts, $content );
		}
	}
	
	new Funiter_Shortcode_Adv_Text();
}