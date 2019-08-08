<?php
if ( ! isset( $content_width ) ) {
	$content_width = 900;
}
if ( ! class_exists( 'Funiter_Functions' ) ) {
	class Funiter_Functions {
		/**
		 * @var Funiter_Functions The one true Funiter_Functions
		 * @since 1.0
		 */
		private static $instance;
		
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Funiter_Functions ) ) {
				self::$instance = new Funiter_Functions;
			}
			add_action( 'after_setup_theme', array( self::$instance, 'funiter_setup' ) );
			add_action( 'widgets_init', array( self::$instance, 'widgets_init' ) );
			add_action( 'wp_enqueue_scripts', array( self::$instance, 'enqueue_scripts' ) );
			// add_action( 'admin_enqueue_scripts', array( self::$instance, 'admin_enqueue_scripts' ), 99 );
			add_filter( 'get_default_comment_status', array(
				self::$instance,
				'open_default_comments_for_page'
			), 10, 3 );
			add_filter( 'comment_form_fields', array(
				self::$instance,
				'funiter_move_comment_field_to_bottom'
			), 10, 3 );
			
			self::includes();
			
			return self::$instance;
		}
		
		public function funiter_setup() {
			load_theme_textdomain( 'funiter', get_template_directory() . '/languages' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'custom-background' );
			/*This theme uses wp_nav_menu() in two locations.*/
			register_nav_menus( array(
				                    'primary'        => esc_html__( 'Primary Menu', 'funiter' ),
				                    'gradient_menu'  => esc_html__( 'Gradient Menu', 'funiter' ),
				                    'vertical_menu'  => esc_html__( 'Vertical Menu', 'funiter' ),
				                    'top_left_menu'  => esc_html__( 'Top Left Menu', 'funiter' ),
				                    'top_right_menu' => esc_html__( 'Top Right Menu', 'funiter' ),
			                    )
			);
			add_theme_support( 'html5', array(
				                          'search-form',
				                          'comment-form',
				                          'comment-list',
				                          'gallery',
				                          'caption',
			                          )
			);
			add_theme_support( 'post-formats',
			                   array(
				                   'image',
				                   'video',
				                   'quote',
				                   'link',
				                   'gallery',
				                   'audio',
			                   )
			);
			
			// Support WooCommerce
			add_theme_support( 'woocommerce', array(
				'thumbnail_image_width'         => 320,
				'gallery_thumbnail_image_width' => 185,
				'single_image_width'            => 800,
			) );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
			add_theme_support( 'wc-product-gallery-zoom' );
			
			self::support_gutenberg();
		}
		
		public function support_gutenberg() {
			// Add support for Block Styles.
			add_theme_support( 'wp-block-styles' );
			
			// Add support for full and wide align images.
			add_theme_support( 'align-wide' );
			
			// Add support for editor styles.
			add_theme_support( 'editor-styles' );
			
			// Enqueue editor styles.
			add_editor_style( 'style-editor.css' );
			
			// Add custom editor font sizes.
			add_theme_support(
				'editor-font-sizes',
				array(
					array(
						'name'      => __( 'Small', 'funiter' ),
						'shortName' => __( 'S', 'funiter' ),
						'size'      => 13,
						'slug'      => 'small',
					),
					array(
						'name'      => __( 'Normal', 'funiter' ),
						'shortName' => __( 'M', 'funiter' ),
						'size'      => 14,
						'slug'      => 'normal',
					),
					array(
						'name'      => __( 'Large', 'funiter' ),
						'shortName' => __( 'L', 'funiter' ),
						'size'      => 36,
						'slug'      => 'large',
					),
					array(
						'name'      => __( 'Huge', 'funiter' ),
						'shortName' => __( 'XL', 'funiter' ),
						'size'      => 48,
						'slug'      => 'huge',
					),
				)
			);
			
			// Add support for responsive embedded content.
			add_theme_support( 'responsive-embeds' );
		}
		
		public function funiter_move_comment_field_to_bottom( $fields ) {
			$comment_field = $fields['comment'];
			unset( $fields['comment'] );
			$fields['comment'] = $comment_field;
			
			return $fields;
		}
		
		/**
		 * Register widget area.
		 *
		 * @since funiter 1.0
		 *
		 * @link  https://codex.wordpress.org/Function_Reference/register_sidebar
		 */
		function widgets_init() {
			register_sidebar( array(
				                  'name'          => esc_html__( 'Widget Area', 'funiter' ),
				                  'id'            => 'widget-area',
				                  'description'   => esc_html__( 'Blog widget.', 'funiter' ),
				                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
				                  'after_widget'  => '</div>',
				                  'before_title'  => '<h2 class="widgettitle">',
				                  'after_title'   => '<span class="arrow"></span></h2>',
			                  )
			);
			
			if ( class_exists( 'WooCommerce' ) ) {
				register_sidebar( array(
					                  'name'          => esc_html__( 'Widget Shop', 'funiter' ),
					                  'id'            => 'widget-shop',
					                  'description'   => esc_html__( 'This is the default widget displayed on the shop page. It can be replaced with another widget by adjusting the theme options.', 'funiter' ),
					                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
					                  'after_widget'  => '</div>',
					                  'before_title'  => '<h2 class="widgettitle">',
					                  'after_title'   => '<span class="arrow"></span></h2>',
				                  )
				);
				register_sidebar( array(
					                  'name'          => esc_html__( 'Widget Product', 'funiter' ),
					                  'id'            => 'widget-product',
					                  'description'   => esc_html__( 'This is the default widget displayed on the product page. It can be replaced with another widget by adjusting the theme options.', 'funiter' ),
					                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
					                  'after_widget'  => '</div>',
					                  'before_title'  => '<h2 class="widgettitle">',
					                  'after_title'   => '<span class="arrow"></span></h2>',
				                  )
				);
				register_sidebar( array(
					                  'name'          => esc_html__( 'Widget Summary Product', 'funiter' ),
					                  'id'            => 'widget-summary-product',
					                  'description'   => esc_html__( 'This widget is displayed in the summary section of the product details page.', 'funiter' ),
					                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
					                  'after_widget'  => '</div>',
					                  'before_title'  => '<h2 class="widgettitle">',
					                  'after_title'   => '<span class="arrow"></span></h2>',
				                  )
				);
			}
		}
		
		/**
		 * Register custom fonts.
		 */
		function funiter_fonts_url() {
			/**
			 * Translators: If there are characters in your language that are not
			 * supported by Montserrat, translate this to 'off'. Do not translate
			 * into your own language.
			 */
			$funiter_enable_typography = $this->funiter_get_option( 'funiter_enable_typography' );
			$funiter_typography_group  = $this->funiter_get_option( 'typography_group' );
			$settings                  = get_option( 'wpb_js_google_fonts_subsets' );
			$font_families             = array();
			if ( $funiter_enable_typography == 1 && ! empty( $funiter_typography_group ) ) {
				foreach ( $funiter_typography_group as $item ) {
					$font_families[] = str_replace( ' ', '+', $item['funiter_typography_font_family']['family'] );
				}
			}
			$font_families[] = 'Poppins:300,300i,400,400i,500,500i,700,700i';
			$font_families[] = 'Open Sans:300,400,600,700,800';
			$query_args      = array(
				'family' => urlencode( implode( '|', $font_families ) ),
			);
			if ( ! empty( $settings ) ) {
				$query_args['subset'] = implode( ',', $settings );
			}
			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
			
			return esc_url_raw( $fonts_url );
		}
		
		/**
		 * Convert HSL to HEX colors
		 *
		 * @param      $h
		 * @param      $s
		 * @param      $l
		 * @param bool $to_hex
		 *
		 * @return string
		 */
		public static function hsl_hex( $h, $s, $l, $to_hex = true ) {
			
			$h /= 360;
			$s /= 100;
			$l /= 100;
			
			$r = $l;
			$g = $l;
			$b = $l;
			$v = ( $l <= 0.5 ) ? ( $l * ( 1.0 + $s ) ) : ( $l + $s - $l * $s );
			if ( $v > 0 ) {
				$m;
				$sv;
				$sextant;
				$fract;
				$vsf;
				$mid1;
				$mid2;
				
				$m       = $l + $l - $v;
				$sv      = ( $v - $m ) / $v;
				$h       *= 6.0;
				$sextant = floor( $h );
				$fract   = $h - $sextant;
				$vsf     = $v * $sv * $fract;
				$mid1    = $m + $vsf;
				$mid2    = $v - $vsf;
				
				switch ( $sextant ) {
					case 0:
						$r = $v;
						$g = $mid1;
						$b = $m;
						break;
					case 1:
						$r = $mid2;
						$g = $v;
						$b = $m;
						break;
					case 2:
						$r = $m;
						$g = $v;
						$b = $mid1;
						break;
					case 3:
						$r = $m;
						$g = $mid2;
						$b = $v;
						break;
					case 4:
						$r = $mid1;
						$g = $m;
						$b = $v;
						break;
					case 5:
						$r = $v;
						$g = $m;
						$b = $mid2;
						break;
				}
			}
			$r = round( $r * 255, 0 );
			$g = round( $g * 255, 0 );
			$b = round( $b * 255, 0 );
			
			if ( $to_hex ) {
				
				$r = ( $r < 15 ) ? '0' . dechex( $r ) : dechex( $r );
				$g = ( $g < 15 ) ? '0' . dechex( $g ) : dechex( $g );
				$b = ( $b < 15 ) ? '0' . dechex( $b ) : dechex( $b );
				
				return "#$r$g$b";
				
			} else {
				
				return "rgb($r, $g, $b)";
			}
		}
		
		/**
		 * Enqueue scripts and styles.
		 *
		 * @since funiter 1.0
		 */
		function enqueue_scripts() {
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			wp_dequeue_style( 'yith-wcwl-font-awesome' );
			wp_dequeue_style( 'yith-quick-view' );
			
			// Add custom fonts, used in the main stylesheet.
			wp_enqueue_style( 'funiter-fonts', self::funiter_fonts_url(), array(), null );
			/* Theme stylesheet. */
			wp_enqueue_style( 'animate-css' );
			wp_enqueue_style( 'flaticon', get_theme_file_uri( '/assets/fonts/flaticon/flaticon.css' ), array(), '1.0' );
			wp_enqueue_style( 'font-awesome', get_theme_file_uri( '/assets/css/font-awesome.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/assets/css/bootstrap.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'growl', get_theme_file_uri( '/assets/css/jquery.growl.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'magnific-popup', get_theme_file_uri( '/assets/css/magnific-popup.css' ), array(), '1.0' );
			wp_enqueue_style( 'slick', get_theme_file_uri( '/assets/css/slick.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'scrollbar', get_theme_file_uri( '/assets/css/jquery.scrollbar.css' ), array(), '1.0' );
			wp_enqueue_style( 'chosen', get_theme_file_uri( '/assets/css/chosen.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'funiter-style', get_theme_file_uri( '/assets/css/style.css' ), array(), '1.0', 'all' );
			wp_enqueue_style( 'funiter-main-style', get_stylesheet_uri() );
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
			/* SCRIPTS */
			$funiter_gmap_api_key = $this->funiter_get_option( 'gmap_api_key' );
			$funiter_gmap_api_key = trim( $funiter_gmap_api_key );
			if ( $funiter_gmap_api_key != '' ) {
				$load_gmap_js        = false;
				$load_gmap_js_target = $this->funiter_get_option( 'load_gmap_js_target', 'all_pages' );
				if ( $load_gmap_js_target == 'selected_pages' ) {
					$load_gmap_js_on = $this->funiter_get_option( 'load_gmap_js_on', array() );
					if ( ! is_array( $load_gmap_js_on ) ) {
						$load_gmap_js_on = array();
					}
					if ( is_singular( 'page' ) ) {
						if ( in_array( get_the_ID(), $load_gmap_js_on ) ) {
							$load_gmap_js = true;
						}
					}
				}
				if ( $load_gmap_js_target == 'all_pages' ) {
					$load_gmap_js = true;
				}
				if ( $load_gmap_js ) {
					wp_enqueue_script( 'maps', esc_url( 'https://maps.googleapis.com/maps/api/js?key=' . $funiter_gmap_api_key ), array( 'jquery' ), false, true );
				}
			}
			
			if ( ! is_admin() ) {
				wp_dequeue_style( 'woocommerce_admin_styles' );
			}
			
			wp_enqueue_script( 'chosen', get_theme_file_uri( '/assets/js/libs/chosen.min.js' ), array(), '1.0', true );
			wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/assets/js/libs/bootstrap.min.js' ), array(), '3.3.7', true );
			wp_enqueue_script( 'threesixty', get_theme_file_uri( '/assets/js/libs/threesixty.min.js' ), array(), '1.0.7', true ); // 360 image
			wp_enqueue_script( 'growl', get_theme_file_uri( '/assets/js/libs/jquery.growl.min.js' ), array(), '1.0.0', true );
			wp_enqueue_script( 'magnific-popup', get_theme_file_uri( '/assets/js/libs/magnific-popup.min.js' ), array(), '1.1.0', true );
			wp_enqueue_script( 'slick', get_theme_file_uri( '/assets/js/libs/slick.min.js' ), array(), '3.3.7', true );
			wp_enqueue_script( 'scrollbar', get_theme_file_uri( '/assets/js/libs/jquery.scrollbar.min.js' ), array(), '1.0.0', true );
			/* http://hilios.github.io/jQuery.countdown/documentation.html */
			wp_enqueue_script( 'countdown', get_theme_file_uri( '/assets/js/libs/countdown.min.js' ), array(), '1.0.0', true );
			/* http://jquery.eisbehr.de/lazy */
			wp_enqueue_script( 'lazy-load', get_theme_file_uri( '/assets/js/libs/lazyload.min.js' ), array(), '1.7.9', true );
			wp_enqueue_script( 'sticky', get_theme_file_uri( '/assets/js/libs/jquery.sticky.js' ), array(), '1.0.0', true );
			wp_enqueue_script( 'funiter-script', get_theme_file_uri( '/assets/js/functions.js' ), array(), '1.0', true );
			wp_localize_script( 'funiter-script', 'funiter_ajax_frontend', array(
				                                    'ajaxurl'                         => admin_url( 'admin-ajax.php' ),
				                                    'security'                        => wp_create_nonce( 'funiter_ajax_frontend' ),
				                                    'added_to_cart_notification_text' => apply_filters( 'funiter_added_to_cart_notification_text', esc_html__( 'Has been added to cart!', 'funiter' ) ),
				                                    'view_cart_notification_text'     => apply_filters( 'funiter_view_cart_notification_text', esc_html__( 'View Cart', 'funiter' ) ),
				                                    'added_to_cart_text'              => apply_filters( 'funiter_adding_to_cart_text', esc_html__( 'Product has been added to cart!', 'funiter' ) ),
				                                    'wc_cart_url'                     => ( function_exists( 'wc_get_cart_url' ) ? esc_url( wc_get_cart_url() ) : '' ),
				                                    'added_to_wishlist_text'          => get_option( 'yith_wcwl_product_added_text', esc_html__( 'Product has been added to wishlist!', 'funiter' ) ),
				                                    'wishlist_url'                    => ( function_exists( 'YITH_WCWL' ) ? esc_url( YITH_WCWL()->get_wishlist_url() ) : '' ),
				                                    'browse_wishlist_text'            => get_option( 'yith_wcwl_browse_wishlist_text', esc_html__( 'Browse Wishlist', 'funiter' ) ),
				                                    'growl_notice_text'               => esc_html__( 'Notice!', 'funiter' ),
				                                    'removed_cart_text'               => esc_html__( 'Product Removed', 'funiter' ),
				                                    'wp_nonce_url'                    => ( function_exists( 'wc_get_cart_url' ) ? wp_nonce_url( wc_get_cart_url() ) : '' ),
			                                    )
			);
			wp_localize_script( 'funiter-script', 'funiter', array(
				                                    'text' => array(
					                                    'clear_all'      => esc_html__( 'Clear All ', 'funiter' ),
					                                    'out_of_product' => esc_html__( 'OUT OF PRODUCT', 'funiter' )
				                                    )
			                                    )
			);
			$funiter_enable_popup        = $this->funiter_get_option( 'funiter_enable_popup' );
			$funiter_enable_popup_mobile = $this->funiter_get_option( 'funiter_enable_popup_mobile' );
			$funiter_popup_delay_time    = $this->funiter_get_option( 'funiter_popup_delay_time' );
			$atts                        = array(
				'owl_vertical'            => true,
				'owl_responsive_vertical' => 1500, // 1199
				'owl_loop'                => false,
				'owl_slide_margin'        => 10,
				'owl_focus_select'        => true,
				'owl_ts_items'            => $this->funiter_get_option( 'funiter_product_thumbnail_ts_items', 5 ),
				'owl_xs_items'            => $this->funiter_get_option( 'funiter_product_thumbnail_xs_items', 5 ),
				'owl_sm_items'            => $this->funiter_get_option( 'funiter_product_thumbnail_sm_items', 5 ),
				'owl_md_items'            => $this->funiter_get_option( 'funiter_product_thumbnail_md_items', 4 ),
				'owl_lg_items'            => $this->funiter_get_option( 'funiter_product_thumbnail_lg_items', 4 ),
				'owl_ls_items'            => $this->funiter_get_option( 'funiter_product_thumbnail_ls_items', 4 ),
			);
			$atts                        = apply_filters( 'funiter_thumb_product_single_slide', $atts );
			$owl_settings                = explode( ' ', apply_filters( 'funiter_carousel_data_attributes', 'owl_', $atts ) );
			wp_localize_script( 'funiter-script', 'funiter_global_frontend',
			                    array(
				                    'funiter_enable_popup'        => $funiter_enable_popup,
				                    'funiter_popup_delay_time'    => $funiter_popup_delay_time,
				                    'funiter_enable_popup_mobile' => $funiter_enable_popup_mobile,
				                    'data_slick'                  => urldecode( $owl_settings[3] ), // Product only!
				                    'data_responsive'             => urldecode( $owl_settings[6] ),
				                    'countdown_day'               => esc_html__( 'Days', 'funiter' ),
				                    'countdown_hrs'               => esc_html__( 'Hours', 'funiter' ),
				                    'countdown_mins'              => esc_html__( 'Mins', 'funiter' ),
				                    'countdown_secs'              => esc_html__( 'Secs', 'funiter' ),
			                    )
			);
		}
		
		function admin_enqueue_scripts() {
			wp_enqueue_style( 'funiter-google-fonts', self::funiter_fonts_url(), array(), null );
		}
		
		public static function funiter_get_option( $option_name, $default = '' ) {
			$get_value = isset( $_GET[ $option_name ] ) ? $_GET[ $option_name ] : '';
			$cs_option = null;
			if ( defined( 'CS_VERSION' ) ) {
				$cs_option = get_option( CS_OPTION );
			}
			if ( isset( $_GET[ $option_name ] ) ) {
				$cs_option = $get_value;
				$default   = $get_value;
			}
			$options = apply_filters( 'cs_get_option', $cs_option, $option_name, $default );
			if ( ! empty( $option_name ) && ! empty( $options[ $option_name ] ) ) {
				$option = $options[ $option_name ];
				if ( is_array( $option ) && isset( $option['multilang'] ) && $option['multilang'] == true ) {
					if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
						if ( isset( $option[ ICL_LANGUAGE_CODE ] ) ) {
							return $option[ ICL_LANGUAGE_CODE ];
						}
					}
				}
				
				return $option;
			} else {
				return ( ! empty( $default ) ) ? $default : null;
			}
		}
		
		public static function theme_get_meta( $meta_key, $meta_value ) {
			$main_data            = '';
			$enable_theme_options = self::funiter_get_option( 'enable_theme_options' );
			$meta_data            = get_post_meta( get_the_ID(), $meta_key, true );
			if ( is_page() && isset( $meta_data[ $meta_value ] ) && $enable_theme_options == 1 ) {
				$main_data = $meta_data[ $meta_value ];
			}
			
			return $main_data;
		}
		
		/**
		 * Filter whether comments are open for a given post type.
		 *
		 * @param string $status       Default status for the given post type,
		 *                             either 'open' or 'closed'.
		 * @param string $post_type    Post type. Default is `post`.
		 * @param string $comment_type Type of comment. Default is `comment`.
		 *
		 * @return string (Maybe) filtered default status for the given post type.
		 */
		function open_default_comments_for_page( $status, $post_type, $comment_type ) {
			if ( 'page' == $post_type ) {
				return 'open';
			}
			
			return $status;
		}
		
		public static function includes() {
			include_once get_parent_theme_file_path( '/framework/framework.php' );
			define( 'CS_ACTIVE_FRAMEWORK', true );
			define( 'CS_ACTIVE_METABOX', true );
			define( 'CS_ACTIVE_TAXONOMY', false );
			define( 'CS_ACTIVE_SHORTCODE', false );
			define( 'CS_ACTIVE_CUSTOMIZE', false );
		}
		
		/**
		 * No image generator
		 *
		 * @since 1.0
		 *
		 * @param $size : array, image size
		 * @param $echo : bool, echo or return no image url
		 **/
		public static function no_image(
			$size = array(
				'width'  => 500,
				'height' => 500
			), $echo = false, $transparent = false
		) {
			$noimage_dir = get_template_directory() . '/assets';
			$noimage_uri = get_template_directory_uri() . '/assets';
			$suffix      = ( $transparent ) ? '_transparent' : '';
			if ( ! is_array( $size ) || empty( $size ) ):
				$size = array( 'width' => 500, 'height' => 500 );
			endif;
			if ( ! is_numeric( $size['width'] ) && $size['width'] == '' || $size['width'] == null ):
				$size['width'] = 'auto';
			endif;
			if ( ! is_numeric( $size['height'] ) && $size['height'] == '' || $size['height'] == null ):
				$size['height'] = 'auto';
			endif;
			
			if ( file_exists( $noimage_dir . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' ) ) {
				if ( $echo ) {
					echo esc_url( $noimage_uri . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' );
				}
				
				return esc_url( $noimage_uri . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' );
			}
			
			// base image must be exist
			$img_base_fullpath = $noimage_dir . '/images/noimage/no_image' . $suffix . '.png';
			$no_image_src      = $noimage_uri . '/images/noimage/no_image' . $suffix . '.png';
			// Check no image exist or not
			if ( ! file_exists( $noimage_dir . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' ) && is_writable( $noimage_dir . '/images/noimage/' ) ):
				$no_image = wp_get_image_editor( $img_base_fullpath );
				if ( ! is_wp_error( $no_image ) ):
					$no_image->resize( $size['width'], $size['height'], true );
					$no_image_name = $no_image->generate_filename( $size['width'] . 'x' . $size['height'], $noimage_dir . '/images/noimage/', null );
					$no_image->save( $no_image_name );
				endif;
			endif;
			// Check no image exist after resize
			$noimage_path_exist_after_resize = $noimage_dir . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png';
			if ( file_exists( $noimage_path_exist_after_resize ) ):
				$no_image_src = $noimage_uri . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png';
			endif;
			
			if ( $echo ) {
				echo esc_url( $no_image_src );
			}
			
			return esc_url( $no_image_src );
		}
		
		/**
		 * @param int    $attach_id
		 * @param string $img_url
		 * @param int    $width
		 * @param int    $height
		 * @param bool   $crop
		 * @param bool   $place_hold        Using place hold image if the image does not exist
		 * @param bool   $use_real_img_hold Using real image for holder if the image does not exist
		 * @param string $solid_img_color   Solid placehold image color (not text color). Random color if null
		 *
		 * @since 1.0
		 * @return array
		 */
		public static function resize_image( $attach_id = null, $img_url = null, $width, $height, $crop = false, $place_hold = true, $use_real_img_hold = true, $solid_img_color = null ) {
			/*If is singular and has post thumbnail and $attach_id is null, so we get post thumbnail id automatic*/
			if ( is_singular() && ! $attach_id ) {
				if ( has_post_thumbnail() && ! post_password_required() ) {
					$attach_id = get_post_thumbnail_id();
				}
			}
			/*this is an attachment, so we have the ID*/
			$image_src = array();
			if ( $attach_id ) {
				$image_src        = wp_get_attachment_image_src( $attach_id, 'full' );
				$actual_file_path = get_attached_file( $attach_id );
				/*this is not an attachment, let's use the image url*/
			} else if ( $img_url ) {
				$file_path        = str_replace( get_site_url(), get_home_path(), $img_url );
				$actual_file_path = rtrim( $file_path, '/' );
				if ( ! file_exists( $actual_file_path ) ) {
					$file_path        = parse_url( $img_url );
					$actual_file_path = rtrim( ABSPATH, '/' ) . $file_path['path'];
				}
				if ( file_exists( $actual_file_path ) ) {
					$orig_size    = getimagesize( $actual_file_path );
					$image_src[0] = $img_url;
					$image_src[1] = $orig_size[0];
					$image_src[2] = $orig_size[1];
				} else {
					$image_src[0] = '';
					$image_src[1] = 0;
					$image_src[2] = 0;
				}
			}
			if ( ! empty( $actual_file_path ) && file_exists( $actual_file_path ) ) {
				$file_info = pathinfo( $actual_file_path );
				$extension = '.' . $file_info['extension'];
				/*the image path without the extension*/
				$no_ext_path      = $file_info['dirname'] . '/' . $file_info['filename'];
				$cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;
				/*checking if the file size is larger than the target size*/
				/*if it is smaller or the same size, stop right here and return*/
				if ( $image_src[1] > $width || $image_src[2] > $height ) {
					/*the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)*/
					if ( file_exists( $cropped_img_path ) ) {
						$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
						$vt_image        = array(
							'url'    => $cropped_img_url,
							'width'  => $width,
							'height' => $height,
						);
						
						return $vt_image;
					}
					
					if ( $crop == false ) {
						/*calculate the size proportionaly*/
						$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
						$resized_img_path  = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;
						/*checking if the file already exists*/
						if ( file_exists( $resized_img_path ) ) {
							$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
							$vt_image        = array(
								'url'    => $resized_img_url,
								'width'  => $proportional_size[0],
								'height' => $proportional_size[1],
							);
							
							return $vt_image;
						}
					}
					/*no cache files - let's finally resize it*/
					$img_editor = wp_get_image_editor( $actual_file_path );
					if ( is_wp_error( $img_editor ) || is_wp_error( $img_editor->resize( $width, $height, $crop ) ) ) {
						return array(
							'url'    => '',
							'width'  => '',
							'height' => '',
						);
					}
					$new_img_path = $img_editor->generate_filename();
					if ( file_exists( $new_img_path ) ) {
						$new_img      = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
						$new_img_size = getimagesize( $new_img_path );
						/*resized output*/
						$vt_image = array(
							'url'    => $new_img,
							'width'  => $new_img_size[0],
							'height' => $new_img_size[1],
						);
						
						return $vt_image;
					} else {
						if ( is_wp_error( $img_editor->save( $new_img_path ) ) ) {
							return array(
								'url'    => isset( $image_src[0] ) ? esc_url( $image_src[0] ) : '',
								'width'  => '',
								'height' => '',
							);
						}
					}
					if ( ! is_string( $new_img_path ) ) {
						return array(
							'url'    => '',
							'width'  => '',
							'height' => '',
						);
					}
					$new_img_size = getimagesize( $new_img_path );
					$new_img      = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
					/*resized output*/
					$vt_image = array(
						'url'    => $new_img,
						'width'  => $new_img_size[0],
						'height' => $new_img_size[1],
					);
					
					return $vt_image;
				}
				/*default output - without resizing*/
				$vt_image = array(
					'url'    => $image_src[0],
					'width'  => $image_src[1],
					'height' => $image_src[2],
				);
				
				return $vt_image;
			} else {
				if ( $place_hold ) {
					$width  = intval( $width );
					$height = intval( $height );
					/*Real image place hold (https://unsplash.it/)*/
					if ( $use_real_img_hold ) {
						$random_time = time() + rand( 1, 100000 );
						$vt_image    = array(
							'url'    => 'https://unsplash.it/' . $width . '/' . $height . '?random&time=' . $random_time,
							'width'  => $width,
							'height' => $height,
						);
					} else {
						$vt_image = array(
							'url'    => 'http://placehold.it/' . $width . 'x' . $height,
							'width'  => $width,
							'height' => $height,
						);
					}
					
					return $vt_image;
				}
			}
			
			return false;
		}
		
		public static function img_lazy( $width = 1, $height = 1 ) {
			// $img_lazy = 'data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20' . $width . '%20' . $height . '%27%2F%3E';
			// $img_lazy = 'https://via.placeholder.com/' . $width . 'x' . $height . '/fff/fff';
			$img_lazy = self::no_image(
				array(
					'width'  => $width,
					'height' => $height
				), false, true );
			
			return $img_lazy;
		}
		
		/**
		 * @param array  $img
		 * @param string $class
		 * @param string $alt
		 * @param string $title
		 *
		 * @return string
		 */
		public static function img_output( $img, $class = '', $alt = '', $title = '', $disable_lazy = false ) {
			
			$img_default = array(
				'width'  => '',
				'height' => '',
				'url'    => ''
			);
			$img         = wp_parse_args( $img, $img_default );
			$enable_lazy = self::funiter_get_option( 'funiter_theme_lazy_load', false );
			if ( $disable_lazy ) {
				$enable_lazy = false;
			}
			
			if ( $enable_lazy ) {
				$img_lazy = self::img_lazy( $img['width'], $img['height'] );
				$img_html = '<img class="fami-img wp-post-image fami-lazy lazy ' . esc_attr( $class ) . '" width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . $img_lazy . '" data-src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '" />';
			} else {
				$img_html = '<img class="fami-img wp-post-image ' . esc_attr( $class ) . '" width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '" />';
			}
			
			return $img_html;
		}
		
		public static function is_mobile() {
			if ( ! function_exists( 'funiter_toolkit_is_mobile' ) ) {
				return false;
			}
			
			return funiter_toolkit_is_mobile();
		}
	}
}
if ( ! function_exists( 'funiter_init_functions' ) ) {
	function funiter_init_functions() {
		return Funiter_Functions::instance();
	}
	
	funiter_init_functions();
}

add_action( 'admin_head', 'funiter_css_admin' );
if ( ! function_exists( 'funiter_css_admin' ) ) {
	function funiter_css_admin() {
		echo '<style>
	    .vc_license-activation-notice {
	      display:none;
	    } 
	  </style>';
	}
}
