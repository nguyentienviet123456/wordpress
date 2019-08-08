<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Funiter Content Page
 *
 * Displays Content Page widget.
 *
 * @author   Khanh
 * @category Widgets
 * @package  Funiter/Widgets
 * @version  1.0.0
 * @extends  FUNITER_Widget
 */
if ( !class_exists( 'Funiter_Content_Page_Widget' ) ) {
	class Funiter_Content_Page_Widget extends FUNITER_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$array_settings           = apply_filters( 'funiter_filter_settings_widget_content_page',
				array(
					'title'         => array(
						'type'  => 'text',
						'title' => esc_html__( 'Title', 'funiter-toolkit' ),
					),
					'funiter_page_id' => array(
						'type'    => 'select',
						'title'   => esc_html__( 'Select Content', 'funiter-toolkit' ),
						'options' => 'pages',
					),
				)
			);
			$this->widget_cssclass    = 'widget-funiter-content_page funiter-content_page';
			$this->widget_description = esc_html__( 'Display the customer Content Page.', 'funiter-toolkit' );
			$this->widget_id          = 'widget_funiter_content_page';
			$this->widget_name        = esc_html__( 'Funiter: Content Page', 'funiter-toolkit' );
			$this->settings           = $array_settings;
			parent::__construct();
		}

		/**
		 * Output widget.
		 *
		 * @see WP_Widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance )
		{
			$this->widget_start( $args, $instance );
			if ( $instance['funiter_page_id'] ) {
				$post_id = get_post( $instance['funiter_page_id'] );
				$content = $post_id->post_content;
				$content = apply_filters( 'the_content', $content );
				$content = str_replace( ']]>', ']]>', $content );
				/* GET CUSTOM CSS */
				$post_custom_css = get_post_meta( $instance['funiter_page_id'], '_Funiter_Shortcode_custom_css', true );
				echo '<style type="text/css">' . $post_custom_css . '</style>';
				echo $content;
			}
			$this->widget_end( $args );
		}
	}
}
/**
 * Register Widgets.
 *
 * @since 2.3.0
 */
function Funiter_Content_Page_Widget()
{
	register_widget( 'Funiter_Content_Page_Widget' );
}

add_action( 'widgets_init', 'Funiter_Content_Page_Widget' );