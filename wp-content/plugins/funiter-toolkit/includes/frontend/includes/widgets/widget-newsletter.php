<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Funiter Mailchimp
 *
 * Displays Mailchimp widget.
 *
 * @author   Khanh
 * @category Widgets
 * @package  Funiter/Widgets
 * @version  1.0.0
 * @extends  FUNITER_Widget
 */
if ( !class_exists( 'Funiter_Mailchimp_Widget' ) ) {
	class Funiter_Mailchimp_Widget extends FUNITER_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$array_settings           = apply_filters( 'funiter_filter_settings_widget_mailchimp',
				array(
					'title'       => array(
						'type'  => 'text',
						'title' => esc_html__( 'Title', 'funiter-toolkit' ),
					),
					'placeholder' => array(
						'type'    => 'text',
						'title'   => esc_html__( 'Placeholder Text:', 'funiter-toolkit' ),
						'default' => esc_html__( 'Enter your email address', 'funiter-toolkit' ),
					),
				)
			);
			$this->widget_cssclass    = 'widget-funiter-mailchimp';
			$this->widget_description = esc_html__( 'Display the customer Newsletter.', 'funiter-toolkit' );
			$this->widget_id          = 'widget_funiter_mailchimp';
			$this->widget_name        = esc_html__( 'Funiter: Newsletter', 'funiter-toolkit' );
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
			ob_start();
			?>
            <div class="newsletter-form-wrap">
                <input class="email email-newsletter" type="email" name="email"
                       placeholder="<?php echo esc_attr( $instance['placeholder'] ); ?>">
                <a href="#" class="button btn-submit submit-newsletter">
                    <span class="fa fa-envelope"></span>
                </a>
            </div>
			<?php
			echo apply_filters( 'funiter_filter_widget_newsletter', ob_get_clean(), $instance );
			$this->widget_end( $args );
		}
	}
}
add_action( 'widgets_init', 'Funiter_Mailchimp_Widget' );
if ( !function_exists( 'Funiter_Mailchimp_Widget' ) ) {
	function Funiter_Mailchimp_Widget()
	{
		register_widget( 'Funiter_Mailchimp_Widget' );
	}
}