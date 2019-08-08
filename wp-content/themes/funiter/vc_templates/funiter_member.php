<?php
if ( !class_exists( 'Funiter_Shortcode_Member' ) ) {
	class Funiter_Shortcode_Member extends Funiter_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'member';

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'funiter_member', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class     = array( 'funiter-member' );
			$css_class[]   = $atts['el_class'];
			$class_editor  = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]   = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'funiter_blog', $atts );
			$member_avatar = apply_filters( 'funiter_resize_image', $atts['avatar_member'], 320, 348, true, true );
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['avatar_member'] ): ?>
                    <div class="member-image">
						<?php echo wp_specialchars_decode( $member_avatar['img'] ); ?>
                    </div>
				<?php endif; ?>
                <div class="member-info">
					<?php if ( $atts['name'] ): ?>
                        <h4><?php echo esc_html( $atts['name'] ); ?></h4>
					<?php endif; ?>
					<?php if ( $atts['position'] ): ?>
                        <p class="positions"><?php echo esc_html( $atts['position'] ); ?></p>
					<?php endif; ?>
					<?php if ( $atts['desc'] ): ?>
                        <p class="desc"><?php echo wp_specialchars_decode( $atts['desc'] ); ?></p>
					<?php endif; ?>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'funiter_toolkit_shortcode_member', $html, $atts, $content );
		}
	}

	new Funiter_Shortcode_Member();
}