<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Funiter Post
 *
 * Displays Post widget.
 *
 * @author   Khanh
 * @category Widgets
 * @package  Funiter/Widgets
 * @version  1.0.0
 * @extends  FUNITER_Widget
 */
if ( !class_exists( 'Funiter_Post_Widget' ) ) {
	class Funiter_Post_Widget extends FUNITER_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$array_settings           = apply_filters( 'funiter_filter_settings_widget_post',
				array(
					'title'     => array(
						'type'  => 'text',
						'title' => esc_html__( 'Title', 'funiter-toolkit' ),
					),
					'type_post' => array(
						'type'    => 'select',
						'options' => array(
							'popular' => esc_html__( 'Popular Post', 'funiter-toolkit' ),
							'recent'  => esc_html__( 'Recent Post', 'funiter-toolkit' ),
						),
						'title'   => esc_html__( 'Posts Type', 'funiter-toolkit' ),
					),
					'category'  => array(
						'type'           => 'select',
						'title'          => esc_html__( 'Category', 'funiter-toolkit' ),
						'options'        => 'categories',
						'query_args'     => array(
							'orderby' => 'name',
							'order'   => 'ASC',
						),
						'default_option' => esc_html__( 'Select a category', 'funiter-toolkit' ),
					),
					'orderby'   => array(
						'type'    => 'select',
						'options' => array(
							'date'          => esc_html__( 'Date', 'funiter-toolkit' ),
							'ID'            => esc_html__( 'ID', 'funiter-toolkit' ),
							'author'        => esc_html__( 'Author', 'funiter-toolkit' ),
							'title'         => esc_html__( 'Title', 'funiter-toolkit' ),
							'modified'      => esc_html__( 'Modified', 'funiter-toolkit' ),
							'rand'          => esc_html__( 'Random', 'funiter-toolkit' ),
							'comment_count' => esc_html__( 'Comment count', 'funiter-toolkit' ),
							'menu_order'    => esc_html__( 'Menu order', 'funiter-toolkit' ),
						),
						'title'   => esc_html__( 'Orderby', 'funiter-toolkit' ),
					),
					'order'     => array(
						'type'    => 'select',
						'options' => array(
							'DESC' => esc_html__( 'DESC', 'funiter-toolkit' ),
							'ASC'  => esc_html__( 'ASC', 'funiter-toolkit' ),
						),
						'title'   => esc_html__( 'Order', 'funiter-toolkit' ),
					),
					'number'    => array(
						'type'    => 'number',
						'default' => 4,
						'title'   => esc_html__( 'Posts Per Page', 'funiter-toolkit' ),
					),
				)
			);
			$this->widget_cssclass    = 'widget-funiter-post';
			$this->widget_description = esc_html__( 'Display the customer Post.', 'funiter-toolkit' );
			$this->widget_id          = 'widget_funiter_post';
			$this->widget_name        = esc_html__( 'Funiter: Post', 'funiter-toolkit' );
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
			$args_loop = array(
				'post_type'           => 'post',
				'showposts'           => $instance['number'],
				'nopaging'            => 0,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'order'               => $instance['order'],
				'orderby'             => $instance['orderby'],
				'cat'                 => $instance['category'],
			);
			if ( $instance['type_post'] == 'popular' ) {
				$args_loop['meta_key'] = 'funiter_post_views_count';
				$args_loop['olderby']  = 'meta_value_num';
			}
			$loop_posts = new WP_Query( $args_loop );
			if ( $loop_posts->have_posts() ) : ?>
                <div class="funiter-posts equal-container better-height">
					<?php while ( $loop_posts->have_posts() ) : $loop_posts->the_post() ?>
                        <article <?php post_class( 'equal-elem' ); ?>>
                            <div class="post-item-inner">
                                <div class="post-thumb">
                                    <a href="<?php the_permalink(); ?>">
										<?php
										$image_thumb = apply_filters( 'funiter_resize_image', get_post_thumbnail_id(), 83, 83, true, true );
										echo wp_specialchars_decode( $image_thumb['img'] );
										?>
                                    </a>
                                </div>
                                <div class="post-info">
                                    <div class="block-title"> 
										<div class="date">
											<a href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a>
										</div>
							        	<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    </div>
                                    
                                </div>
                            </div>
                        </article>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
                </div>
			<?php else :
				get_template_part( 'content', 'none' );
			endif;
			echo apply_filters( 'funiter_filter_widget_post', ob_get_clean(), $instance );
			$this->widget_end( $args );
		}
	}
}
add_action( 'widgets_init', 'Funiter_Post_Widget' );
if ( !function_exists( 'Funiter_Post_Widget' ) ) {
	function Funiter_Post_Widget()
	{
		register_widget( 'Funiter_Post_Widget' );
	}
}