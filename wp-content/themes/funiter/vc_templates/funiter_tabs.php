<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this "Funiter_Tabs"
 */
if ( !class_exists( 'Funiter_Shortcode_Tabs' ) ) {
	class Funiter_Shortcode_Tabs extends Funiter_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'tabs';

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'funiter_tabs', $atts ) : $atts;
			extract( $atts );
			$css_class    = array( 'funiter-tabs' );
			$css_class[]  = $atts['style'];
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'funiter_tabs', $atts );
			$sections     = self::get_all_attributes( 'vc_tta_section', $content );
			$rand         = uniqid();
			ob_start(); ?>
            <div class="<?php echo implode( ' ', $css_class ); ?>">
				<?php if ( $sections && is_array( $sections ) && count( $sections ) > 0 ): ?>
                    <div class="tab-head">
						<?php if ( $atts['tab_title'] && $atts['style'] == 'style-01' ): ?>
                            <h2 class="funiter-title">
                                <span class="text"><?php echo esc_html( $atts['tab_title'] ); ?></span>
                            </h2>
						<?php endif; ?>
                        <?php
                        $tab_responsive = '';
                        if ($atts['style'] == 'style-01' || $atts['style'] == 'style-02') {
                            $tab_responsive = 'tab-responsive';
                        }
                        ?>
                        <?php
                        $using_loop = '0';
                        if ( $atts['using_loop'] == 1 ) {
                            $using_loop = '1';
                        }
                        $owl_slick = '';
                        $owl_settings = '';
                        if ($atts['style'] == 'style-03') {
                            $owl_slick = 'owl-slick nav-center';
                            $owl_atts = array(
                                'owl_navigation' => 'true',
                                'owl_dots' => 'false',
                                'owl_loop' => 'false',
                                'owl_slide_margin' => '0',
                                'owl_ts_items' => 2,
                                'owl_xs_items' => 2,
                                'owl_sm_items' => 3,
                                'owl_md_items' => 4,
                                'owl_lg_items' => 5,
                                'owl_ls_items' => 5,
                            );
                            $owl_settings = apply_filters('funiter_carousel_data_attributes', 'owl_', $owl_atts);
                        }
                        ?>
                        <ul class="<?php echo esc_attr($tab_responsive); ?> tab-link equal-container <?php echo esc_attr($owl_slick); ?>" <?php echo esc_attr($owl_settings)?> data-loop="<?php echo esc_attr($using_loop); ?>">
                            <?php foreach ( $sections as $key => $section ) : ?>
								<?php
								/* Get icon from section tabs */
								$section['i_type'] = isset( $section['i_type'] ) ? $section['i_type'] : 'fontawesome';
								$add_icon          = isset( $section['add_icon'] ) ? $section['add_icon'] : '';
								$position_icon     = isset( $section['i_position'] ) ? $section['i_position'] : '';
								$icon_html         = $this->constructIcon( $section );
								$class_load        = '';
								if ( $key == $atts['active_section'] )
									$class_load = 'loaded';
								?>
                                <li class="<?php if ( $key == $atts['active_section'] ): ?>active<?php endif; ?>">
                                    <a class="<?php echo esc_attr( $class_load ); ?>"
                                       data-ajax="<?php echo esc_attr( $atts['ajax_check'] ) ?>"
                                       data-animate="<?php echo esc_attr( $atts['css_animation'] ); ?>"
                                       data-section="<?php echo esc_attr( $section['tab_id'] ); ?>"
                                       data-id="<?php echo get_the_ID(); ?>"
                                       href="#<?php echo esc_attr( $section['tab_id'] ); ?>-<?php echo esc_attr( $rand ); ?>">
										<?php if ( isset( $section['title_image'] ) ) : ?>
                                            <figure>
												<?php
												$image_thumb = apply_filters( 'funiter_resize_image', $section['title_image'], false, false, true, true );
												echo wp_specialchars_decode( $image_thumb['img'] );
												?>
                                            </figure>
										<?php endif; ?>
                                        <?php echo ( 'true' === $add_icon && 'right' !== $position_icon ) ? $icon_html : ''; ?>
                                        <?php if ( isset($section['title']) ): ?>
                                            <span><?php echo esc_html( $section['title'] ); ?></span>
                                        <?php endif; ?>
                                        <?php echo ( 'true' === $add_icon && 'right' === $position_icon ) ? $icon_html : ''; ?>
                                    </a>
                                </li>
							<?php endforeach; ?>
                            <?php if ($atts['style'] == 'style-01' || $atts['style'] == 'style-02') {?>
                                <li class="dropdown">
                                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                        <span class="fa fa-ellipsis-v"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right"></ul>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="tab-container">
						<?php foreach ( $sections as $key => $section ): ?>
                            <div class="tab-panel <?php if ( $key == $atts['active_section'] ): ?>active<?php endif; ?>"
                                 id="<?php echo esc_attr( $section['tab_id'] ); ?>-<?php echo esc_attr( $rand ); ?>">
								<?php if ( $atts['ajax_check'] == '1' ) {
									if ( $key == $atts['active_section'] )
										echo do_shortcode( $section['content'] );
								} else {
									echo do_shortcode( $section['content'] );
								} ?>
                            </div>
						<?php endforeach; ?>
                    </div>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Funiter_Shortcode_Tabs', $html, $atts, $content );
		}
	}

	new Funiter_Shortcode_Tabs();
}