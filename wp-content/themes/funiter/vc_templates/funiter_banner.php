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
if ( ! class_exists( 'Funiter_Shortcode_Banner' ) ) {
	class Funiter_Shortcode_Banner extends Funiter_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'banner';
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'funiter_banner', $atts ) : $atts;
			extract( $atts );
			$css_class    = array( 'funiter-banner' );
			$css_class[]  = $atts['style'];
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'funiter_banner', $atts );
			$banner_link  = vc_build_link( $atts['link'] );
			if ( $banner_link['url'] ) {
				$link_url = $banner_link['url'];
			} else {
				$link_url = '#';
			}
			if ( $banner_link['target'] ) {
				$link_target = $banner_link['target'];
			} else {
				$link_target = '_self';
			}
			/* START */
			ob_start(); ?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <div class="banner-inner">
					<?php if ( $atts['banner'] ) : ?>
                        <figure class="banner-thumb">
							<?php
							$banner_id    = $atts['banner'];
							$banner_img   = Funiter_Functions::resize_image( $banner_id, null, 4000, 4000, true, true, false );
							$disable_lazy = $disable_lazy_mobile == 'yes' && Funiter_Functions::is_mobile();
							?>
							<?php echo Funiter_Functions::img_output( $banner_img, '', '', '', $disable_lazy ); ?>
							<?php if ( $atts['label'] && $atts['style'] == 'style-08' ): ?>
                                <span><?php echo esc_html( $atts['label'] ); ?></span>
							<?php endif; ?>
                        </figure>
					<?php endif; ?>
                    <div class="banner-info">
                        <div class="banner-top">
                            <div class="banner-top-left">
								<?php if ( $atts['title'] && ( $atts['style'] == 'style-02' || $atts['style'] == 'style-03' || $atts['style'] == 'style-04' || $atts['style'] == 'style-05' || $atts['style'] == 'style-06' ) ) : ?>
                                    <h6 class="title">
										<?php echo esc_html( $atts['title'] ); ?>
                                    </h6>
								<?php endif; ?>
								<?php if ( $atts['bigtitle'] ) : ?>
                                    <h3 class="bigtitle">
										<?php if ( $atts['style'] == 'style-08' || $atts['style'] == 'style-09' || $atts['style'] == 'style-12' ): ?>
                                            <a target="<?php echo esc_attr( $link_target ); ?>"
                                               href="<?php echo esc_url( $link_url ); ?>">
												<?php echo wp_specialchars_decode( $atts['bigtitle'] ); ?>
                                            </a>
										<?php else: ?>
											<?php echo wp_specialchars_decode( $atts['bigtitle'] ); ?>
										<?php endif; ?>
                                    </h3>
								<?php endif; ?>
								<?php if ( $atts['desc'] && ( $atts['style'] == 'style-01' || $atts['style'] == 'style-02' || $atts['style'] == 'style-03' || $atts['style'] == 'style-06' || $atts['style'] == 'style-07' || $atts['style'] == 'style-08' || $atts['style'] == 'style-09' || $atts['style'] == 'style-10' || $atts['style'] == 'style-11' ) ): ?>
                                    <div class="desc">
										<?php echo wp_specialchars_decode( $atts['desc'] ); ?>
                                    </div>
								<?php endif; ?>
                            </div>
							<?php if ( $atts['sale'] && $atts['style'] == 'style-06' ) : ?>
                                <div class="sale">
									<?php echo esc_html( $atts['sale'] ); ?>
                                    <span class="percent"><?php echo esc_html__( '%', 'funiter' ) ?></span>
                                    <span class="off"><?php echo esc_html__( 'off', 'funiter' ) ?></span>
                                </div>
							<?php endif; ?>
                        </div>
						<?php if ( $banner_link['title'] && ( $atts['style'] == 'style-01' || $atts['style'] == 'style-02' || $atts['style'] == 'style-03' || $atts['style'] == 'style-04' || $atts['style'] == 'style-05' || $atts['style'] == 'style-06' || $atts['style'] == 'style-07' || $atts['style'] == 'style-10' || $atts['style'] == 'style-11' ) ) : ?>
                            <a class="button" target="<?php echo esc_attr( $link_target ); ?>"
                               href="<?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $banner_link['title'] ); ?></a>
						<?php endif; ?>
                    </div>
                </div>
            </div>
			<?php
			$html = ob_get_clean();
			
			return apply_filters( 'Funiter_Shortcode_Banner', $html, $atts, $content );
		}
	}
	
	new Funiter_Shortcode_Banner();
}