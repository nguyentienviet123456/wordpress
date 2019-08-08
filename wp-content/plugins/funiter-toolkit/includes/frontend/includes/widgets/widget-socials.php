<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Funiter socials
 *
 * Displays socials widget.
 *
 * @author   Khanh
 * @category Widgets
 * @package  Funiter/Widgets
 * @version  1.0.0
 * @extends  FUNITER_Widget
 */
if ( !class_exists( 'Funiter_Socials_Widget' ) ) {
	class Funiter_Socials_Widget extends FUNITER_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$socials     = array();
			$all_socials = cs_get_option( 'user_all_social' );
			if ( $all_socials ) {
				foreach ( $all_socials as $key => $social ) {
					$socials[$key] = $social['title_social'];
				}
			}
			$array_settings           = apply_filters( 'funiter_filter_settings_widget_socials',
				array(
					'title'         => array(
						'type'  => 'text',
						'title' => esc_html__( 'Title', 'funiter-toolkit' ),
					),
					'funiter_socials' => array(
						'type'    => 'checkbox',
						'class'   => 'horizontal',
						'title'   => esc_html__( 'Select Social', 'funiter-toolkit' ),
						'options' => $socials,
					),
				)
			);
			$this->widget_cssclass    = 'widget-funiter-socials';
			$this->widget_description = esc_html__( 'Display the customer Socials.', 'funiter-toolkit' );
			$this->widget_id          = 'widget_funiter_socials';
			$this->widget_name        = esc_html__( 'Funiter: Socials', 'funiter-toolkit' );
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
			$all_socials = cs_get_option( 'user_all_social' );
			ob_start();
			?>
            <div class="content-socials">
				<?php if ( !empty( $instance['funiter_socials'] ) ) : ?>
                    <ul class="socials-list">
						<?php foreach ( $instance['funiter_socials'] as $value ) : ?>
							<?php if ( isset( $all_socials[$value] ) ) :
								$array_socials = $all_socials[$value]; ?>
                                <li>
                                    <a href="<?php echo esc_url( $array_socials['link_social'] ) ?>"
                                       target="_blank">
                                        <span class="<?php echo esc_attr( $array_socials['icon_social'] ); ?>"></span>
										<?php echo esc_html( $array_socials['title_social'] ); ?>
                                    </a>
                                </li>
							<?php endif; ?>
						<?php endforeach; ?>
                    </ul>
				<?php endif; ?>
            </div>
			<?php
			echo apply_filters( 'funiter_filter_widget_socials', ob_get_clean(), $instance );
			$this->widget_end( $args );
		}
	}
}
/**
 * Register Widgets.
 *
 * @since 2.3.0
 */
function Funiter_Socials_Widget()
{
	register_widget( 'Funiter_Socials_Widget' );
}

add_action( 'widgets_init', 'Funiter_Socials_Widget' );