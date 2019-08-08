<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
if ( ! class_exists( 'Funiter_ThemeOption' ) ) {
	class Funiter_ThemeOption {
		public function __construct() {
			add_filter( 'cs_framework_settings', array( $this, 'framework_settings' ) );
			add_filter( 'cs_framework_options', array( $this, 'framework_options' ) );
			add_filter( 'cs_metabox_options', array( $this, 'metabox_options' ) );
		}
		
		public function get_header_options() {
			$layoutDir      = get_template_directory() . '/templates/header/';
			$header_options = array();
			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' ) {
								$file_data                    = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name                    = str_replace( 'header-', '', $fileInfo['filename'] );
								$header_options[ $file_name ] = array(
									'title'   => $file_data['Name'],
									'preview' => get_theme_file_uri( '/templates/header/header-' . $file_name . '.jpg' ),
								);
							}
						}
					}
				}
			}
			
			return $header_options;
			
		}
		
		public function get_sidebar_options() {
			$sidebars = array();
			global $wp_registered_sidebars;
			foreach ( $wp_registered_sidebars as $sidebar ) {
				$sidebars[ $sidebar['id'] ] = $sidebar['name'];
			}
			
			return $sidebars;
		}
		
		public function get_social_options() {
			$socials     = array();
			$all_socials = cs_get_option( 'user_all_social' );
			if ( $all_socials ) {
				foreach ( $all_socials as $key => $social ) {
					$socials[ $key ] = $social['title_social'];
				}
			}
			
			return $socials;
		}
		
		public function get_footer_options() {
			$layoutDir      = get_template_directory() . '/templates/footer/';
			$footer_options = array();
			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' ) {
								$file_data                    = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name                    = str_replace( 'footer-', '', $fileInfo['filename'] );
								$footer_options[ $file_name ] = array(
									'title'   => $file_data['Name'],
									'preview' => get_theme_file_uri( '/templates/footer/footer-' . $file_name . '.jpg' ),
								);
							}
						}
					}
				}
			}
			
			return $footer_options;
		}
		
		public function get_footer_preview() {
			$footer_preview = array();
			$args           = array(
				'post_type'      => 'footer',
				'posts_per_page' => - 1,
				'orderby'        => 'ASC',
			);
			$loop           = get_posts( $args );
			foreach ( $loop as $value ) {
				setup_postdata( $value );
				$data_meta                    = get_post_meta( $value->ID, '_custom_footer_options', true );
				$template_style               = isset( $data_meta['funiter_footer_style'] ) ? $data_meta['funiter_footer_style'] : 'default';
				$footer_preview[ $value->ID ] = array(
					'title'   => $value->post_title,
					'preview' => get_theme_file_uri( '/templates/footer/footer-' . $template_style . '.jpg' ),
				);
			}
			
			return $footer_preview;
		}
		
		public function funiter_attributes_options() {
			$attributes     = array();
			$attributes_tax = array();
			if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
				$attributes_tax = wc_get_attribute_taxonomies();
			}
			if ( is_array( $attributes_tax ) && count( $attributes_tax ) > 0 ) {
				foreach ( $attributes_tax as $attribute ) {
					$attributes[ $attribute->attribute_name ] = $attribute->attribute_label;
				}
			}
			
			return $attributes;
		}
		
		function framework_settings( $settings ) {
			// ===============================================================================================
			// -----------------------------------------------------------------------------------------------
			// FRAMEWORK SETTINGS
			// -----------------------------------------------------------------------------------------------
			// ===============================================================================================
			$settings = array(
				'menu_title'      => esc_html__( 'Theme Options', 'funiter' ),
				'menu_type'       => 'submenu', // menu, submenu, options, theme, etc.
				'menu_slug'       => 'funiter',
				'ajax_save'       => false,
				'menu_parent'     => 'funiter_menu',
				'show_reset_all'  => true,
				'menu_position'   => 5,
				'framework_title' => '<a href="' . esc_url( 'https://funiter.famithemes.com/' ) . '" target="_blank"><img src="' . get_theme_file_uri( '/assets/images/logo-options.png' ) . '" alt="' . esc_attr( 'funiter' ) . '"></a> <i>' . esc_html__( 'By ', 'funiter' ) . '<a href="' . esc_url( 'https://themeforest.net/user/fami_themes/portfolio' ) . '" target="_blank">' . esc_html__( 'FamiThemes', 'funiter' ) . '</a></i>',
			);
			
			return $settings;
		}
		
		function framework_options( $options ) {
			// ===============================================================================================
			// -----------------------------------------------------------------------------------------------
			// FRAMEWORK OPTIONS
			// -----------------------------------------------------------------------------------------------
			// ===============================================================================================
			$options = array();
			// ----------------------------------------
			// a option section for options overview  -
			// ----------------------------------------
			$options[] = array(
				'name'     => 'general',
				'title'    => esc_html__( 'General', 'funiter' ),
				'icon'     => 'fa fa-wordpress',
				'sections' => array(
					array(
						'name'   => 'main_settings',
						'title'  => esc_html__( 'Main Settings', 'funiter' ),
						'fields' => array(
							array(
								'id'    => 'funiter_logo',
								'type'  => 'image',
								'title' => esc_html__( 'Logo', 'funiter' ),
							),
							array(
								'id'      => 'funiter_width_logo',
								'type'    => 'number',
								'default' => '103',
								'title'   => esc_html__( 'Width Logo', 'funiter' ),
								'desc'    => esc_html__( 'Unit PX', 'funiter' )
							),
							array(
								'id'      => 'funiter_main_color',
								'type'    => 'color_picker',
								'title'   => esc_html__( 'Main Color', 'funiter' ),
								'default' => '#a8854a',
								'rgba'    => true,
							),
							array(
								'id'             => 'funiter_page_404',
								'type'           => 'select',
								'title'          => esc_html__( '404 Page Content', 'funiter' ),
								'options'        => 'pages',
								'default_option' => esc_html__( 'Select a page', 'funiter' ),
							),
							
							array(
								'id'    => 'gmap_api_key',
								'type'  => 'text',
								'title' => esc_html__( 'Google Map API Key', 'funiter' ),
								'desc'  => esc_html__( 'Enter your Google Map API key. ', 'funiter' ) . '<a href="' . esc_url( 'https://developers.google.com/maps/documentation/javascript/get-api-key' ) . '" target="_blank">' . esc_html__( 'How to get?', 'funiter' ) . '</a>',
							),
							array(
								'id'         => 'load_gmap_js_target',
								'type'       => 'select',
								'title'      => esc_html__( 'Load GMap JS On', 'funiter' ),
								'options'    => array(
									'all_pages'      => esc_html__( 'All Pages', 'funiter' ),
									'selected_pages' => esc_html__( 'Selected Pages', 'funiter' ),
									'disabled'       => esc_html__( 'Don\'t Load Gmap JS', 'funiter' ),
								),
								'default'    => 'all_pages',
								'dependency' => array( 'gmap_api_key', '!=', '' ),
							),
							array(
								'id'         => 'load_gmap_js_on',
								'type'       => 'select',
								'title'      => esc_html__( 'Select Pages To Load GMap JS', 'funiter' ),
								'options'    => 'pages',
								'query_args' => array(
									'post_type'      => 'page',
									'orderby'        => 'post_date',
									'order'          => 'ASC',
									'posts_per_page' => - 1
								),
								'attributes' => array(
									'multiple' => 'multiple',
									'style'    => 'width: 500px; height: 125px;',
								),
								'class'      => 'chosen',
								'desc'       => esc_html__( 'Load Google Map JS on selected pages', 'funiter' ),
								'dependency' => array(
									'gmap_api_key|load_gmap_js_target',
									'!=|==',
									'|selected_pages'
								),
							),
							array(
								'id'    => 'enable_theme_options',
								'type'  => 'switcher',
								'title' => esc_html__( 'Enable Meta Box Options', 'funiter' ),
								'desc'  => esc_html__( 'Enable for using Themes setting each single page.', 'funiter' ),
							),
							array(
								'id'    => 'funiter_theme_lazy_load',
								'type'  => 'switcher',
								'title' => esc_html__( 'Enable Images Lazy Load', 'funiter' ),
							),
						),
					),
					array(
						'name'   => 'popup_settings',
						'title'  => esc_html__( 'Newsletter Settings', 'funiter' ),
						'fields' => array(
							array(
								'id'    => 'funiter_enable_popup',
								'type'  => 'switcher',
								'title' => esc_html__( 'Enable Newsletter Popup', 'funiter' ),
							),
							array(
								'id'         => 'funiter_select_newsletter_page',
								'type'       => 'select',
								'title'      => esc_html__( 'Page Newsletter Popup', 'funiter' ),
								'options'    => 'pages',
								'query_args' => array(
									'sort_order'  => 'ASC',
									'sort_column' => 'post_title',
								),
								'attributes' => array(
									'multiple' => 'multiple',
								),
								'class'      => 'chosen',
								'dependency' => array( 'funiter_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'funiter_popup_background',
								'type'       => 'image',
								'title'      => esc_html__( 'Popup Background', 'funiter' ),
								'dependency' => array( 'funiter_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'funiter_popup_title',
								'type'       => 'text',
								'title'      => esc_html__( 'Title', 'funiter' ),
								'dependency' => array( 'funiter_enable_popup', '==', '1' ),
								'default'    => esc_html__( 'Sign up & connect to Funiter', 'funiter' ),
							),
							array(
								'id'         => 'funiter_popup_desc',
								'type'       => 'textarea',
								'title'      => esc_html__( 'Description', 'funiter' ),
								'dependency' => array( 'funiter_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'funiter_popup_input_placeholder',
								'type'       => 'text',
								'title'      => esc_html__( 'Placeholder Input', 'funiter' ),
								'default'    => esc_html__( 'Email address here...', 'funiter' ),
								'dependency' => array( 'funiter_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'funiter_popup_input_submit',
								'type'       => 'text',
								'title'      => esc_html__( 'Button', 'funiter' ),
								'default'    => esc_html__( 'SUBSCRIBE', 'funiter' ),
								'dependency' => array( 'funiter_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'funiter_popup_delay_time',
								'type'       => 'number',
								'title'      => esc_html__( 'Delay Time', 'funiter' ),
								'default'    => '0',
								'dependency' => array( 'funiter_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'funiter_enable_popup_mobile',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Enable Poppup on Mobile', 'funiter' ),
								'default'    => false,
								'dependency' => array( 'funiter_enable_popup', '==', '1' ),
							),
						),
					),
					array(
						'name'   => 'widget_settings',
						'title'  => esc_html__( 'Widget Settings', 'funiter' ),
						'fields' => array(
							array(
								'id'              => 'multi_widget',
								'type'            => 'group',
								'title'           => esc_html__( 'Multi Widget', 'funiter' ),
								'button_title'    => esc_html__( 'Add Widget', 'funiter' ),
								'accordion_title' => esc_html__( 'Add New Field', 'funiter' ),
								'fields'          => array(
									array(
										'id'    => 'add_widget',
										'type'  => 'text',
										'title' => esc_html__( 'Name Widget', 'funiter' ),
									),
								),
							),
						),
					),
					array(
						'name'   => 'theme_js_css',
						'title'  => esc_html__( 'Customs JS', 'funiter' ),
						'fields' => array(
							array(
								'id'         => 'funiter_custom_js',
								'type'       => 'ace_editor',
								'before'     => '<h1>' . esc_html__( 'Custom JS', 'funiter' ) . '</h1>',
								'attributes' => array(
									'data-theme' => 'twilight',  // the theme for ACE Editor
									'data-mode'  => 'javascript',     // the language for ACE Editor
								),
							),
						),
					),
					array(
						'name'   => 'live_search_settings',
						'title'  => esc_html__( 'Live Search Settings', 'funiter' ),
						'fields' => array(
							array(
								'id'         => 'enable_live_search',
								'type'       => 'switcher',
								'attributes' => array(
									'data-depend-id' => 'enable_live_search',
								),
								'title'      => esc_html__( 'Enable Live Search', 'funiter' ),
								'default'    => false,
							),
							array(
								'id'         => 'min_characters',
								'type'       => 'number',
								'default'    => 3,
								'title'      => esc_html__( 'Min Search Characters', 'funiter' ),
								'dependency' => array(
									'enable_live_search',
									'==',
									true,
								),
							),
							array(
								'id'         => 'max_results',
								'type'       => 'number',
								'default'    => 3,
								'title'      => esc_html__( 'Max Search Characters', 'funiter' ),
								'dependency' => array(
									'enable_live_search',
									'==',
									true,
								),
							),
							array(
								'id'         => 'search_in',
								'type'       => 'checkbox',
								'title'      => esc_html__( 'Search In', 'funiter' ),
								'options'    => array(
									'title'       => esc_html__( 'Title', 'funiter' ),
									'description' => esc_html__( 'Description', 'funiter' ),
									'content'     => esc_html__( 'Content', 'funiter' ),
									'sku'         => esc_html__( 'SKU', 'funiter' ),
								),
								'dependency' => array(
									'enable_live_search',
									'==',
									true,
								),
							),
						),
					),
				),
			);
			$options[] = array(
				'name'     => 'header',
				'title'    => esc_html__( 'Header Settings', 'funiter' ),
				'icon'     => 'fa fa-folder-open-o',
				'sections' => array(
					array(
						'name'   => 'main_header',
						'title'  => esc_html__( 'Header Settings', 'funiter' ),
						'fields' => array(
							array(
								'id'    => 'funiter_header_background',
								'type'  => 'image',
								'title' => esc_html__( 'Header Background', 'funiter' ),
							),
							array(
								'id'         => 'funiter_background_url',
								'type'       => 'text',
								'default'    => '#',
								'title'      => esc_html__( 'Header Background Url', 'funiter' ),
								'dependency' => array( 'funiter_header_background', '!=', '' ),
							),
							array(
								'id'    => 'funiter_enable_sticky_menu',
								'type'  => 'switcher',
								'title' => esc_html__( 'Main Menu Sticky', 'funiter' ),
							),
							array(
								'id'         => 'funiter_used_header',
								'type'       => 'select_preview',
								'title'      => esc_html__( 'Header Layout', 'funiter' ),
								'desc'       => esc_html__( 'Select a header layout', 'funiter' ),
								'options'    => self::get_header_options(),
								'default'    => 'style-02',
								'attributes' => array(
									'data-depend-id' => 'funiter_used_header',
								),
							),
							array(
								'id'    => 'funiter_header_top',
								'type'  => 'textarea',
								'title' => esc_html__( 'Header Top', 'funiter' ),
								'desc'  => esc_html__( 'Notice on top of site', 'funiter' ),
							),
							array(
								'id'         => 'enable_header_top_right',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Enable Header Top Right', 'funiter' ),
								'default'    => false,
								'dependency' => array( 'funiter_used_header', '==', 'style-02' ),
							),
							array(
								'id'         => 'enable_header_bottom',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Enable Header Bottom', 'funiter' ),
								'default'    => false,
								'dependency' => array( 'funiter_used_header', '==', 'style-02' ),
							),
							array(
								'id'    => 'short_notice_text',
								'type'  => 'text',
								'title' => esc_html__( 'Notice Text', 'funiter' ),
								'desc'  => esc_html__( 'Eg: Notice open time', 'funiter' ),
							),
						),
					),
					array(
						'name'   => 'header_contact_online',
						'title'  => esc_html__( 'Header Contact', 'funiter' ),
						'fields' => array(
							array(
								'id'      => 'header_icon',
								'type'    => 'icon',
								'title'   => esc_html__( 'Header Icon', 'funiter' ),
								'default' => 'flaticon-people',
							),
							array(
								'id'    => 'header_text',
								'type'  => 'text',
								'title' => esc_html__( 'Phone Title', 'funiter' ),
							),
							array(
								'id'    => 'header_phone',
								'type'  => 'text',
								'title' => esc_html__( 'Header Phone Number', 'funiter' ),
							),
						),
					),
					array(
						'name'   => 'vertical',
						'title'  => esc_html__( 'Vertical Settings', 'funiter' ),
						'fields' => array(
							array(
								'id'         => 'funiter_enable_vertical_menu',
								'type'       => 'switcher',
								'attributes' => array(
									'data-depend-id' => 'enable_vertical_menu',
								),
								'title'      => esc_html__( 'Enable Vertical Menu', 'funiter' ),
							),
							array(
								'id'         => 'funiter_block_vertical_menu',
								'type'       => 'select',
								'title'      => esc_html__( 'Vertical Menu Always Open', 'funiter' ),
								'options'    => 'page',
								'class'      => 'chosen',
								'attributes' => array(
									'placeholder' => 'Select a page',
									'multiple'    => 'multiple',
								),
								'dependency' => array(
									'enable_vertical_menu',
									'==',
									true,
								),
								'after'      => '<i class="funiter-text-desc">' . esc_html__( '-- Vertical menu will be always open --', 'funiter' ) . '</i>',
							),
							array(
								'id'         => 'funiter_vertical_menu_title',
								'type'       => 'text',
								'title'      => esc_html__( 'Vertical Menu Title', 'funiter' ),
								'dependency' => array(
									'enable_vertical_menu',
									'==',
									true,
								),
								'default'    => esc_html__( 'CATEGORIES', 'funiter' ),
							),
							array(
								'id'         => 'funiter_vertical_menu_button_all_text',
								'type'       => 'text',
								'title'      => esc_html__( 'Vertical Menu Button Show All Text', 'funiter' ),
								'dependency' => array(
									'enable_vertical_menu',
									'==',
									true,
								),
								'default'    => esc_html__( 'All Categories', 'funiter' ),
							),
							array(
								'id'         => 'funiter_vertical_menu_button_close_text',
								'type'       => 'text',
								'title'      => esc_html__( 'Vertical Menu Button Close Text', 'funiter' ),
								'dependency' => array(
									'enable_vertical_menu',
									'==',
									true,
								),
								'default'    => esc_html__( 'Close', 'funiter' ),
							),
							array(
								'id'         => 'funiter_vertical_item_visible',
								'type'       => 'number',
								'title'      => esc_html__( 'The Number of Visible Vertical Menu Items On Device Full HD 1920px', 'funiter' ),
								'desc'       => esc_html__( 'The Number of Visible Vertical Menu Items On Device Full HD 1920px', 'funiter' ),
								'dependency' => array(
									'enable_vertical_menu',
									'==',
									true,
								),
								'default'    => 10,
							),
						),
					),
					array(
						'name'   => 'header_mobile',
						'title'  => esc_html__( 'Header Mobile', 'funiter' ),
						'fields' => array(
							array(
								'id'      => 'enable_header_mobile',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Header Mobile', 'funiter' ),
								'default' => false,
							),
							array(
								'id'         => 'funiter_mobile_logo',
								'type'       => 'image',
								'title'      => esc_html__( 'Mobile Logo', 'funiter' ),
								'add_title'  => esc_html__( 'Add Mobile Logo', 'funiter' ),
								'desc'       => esc_html__( 'Add custom logo for mobile. If no mobile logo is selected, the default logo will be used or custom logo if placed in the page', 'funiter' ),
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
							array(
								'id'         => 'enable_header_mini_cart_mobile',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Show Mini Cart Icon', 'funiter' ),
								'desc'       => esc_html__( 'Show/Hide header mini cart icon on mobile', 'funiter' ),
								'default'    => true,
								'on'         => esc_html__( 'On', 'funiter' ),
								'off'        => esc_html__( 'Off', 'funiter' ),
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
							array(
								'id'         => 'enable_wishlist_mobile',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Show Wish List Icon', 'funiter' ),
								'desc'       => esc_html__( 'Show/Hide wish list icon on siding menu mobile', 'funiter' ),
								'default'    => false,
								'on'         => esc_html__( 'Show', 'funiter' ),
								'off'        => esc_html__( 'Hide', 'funiter' ),
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
							array(
								'id'         => 'enable_header_product_search_mobile',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Show Products Search', 'funiter' ),
								'desc'       => esc_html__( 'Show/Hide header product search icon on mobile', 'funiter' ),
								'default'    => true,
								'on'         => esc_html__( 'On', 'funiter' ),
								'off'        => esc_html__( 'Off', 'funiter' ),
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
							array(
								'id'         => 'enable_lang_mobile',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Show Languges and Currency', 'funiter' ),
								'desc'       => esc_html__( 'Show/Hide wish Show Languges and Currency on siding menu mobile', 'funiter' ),
								'default'    => false,
								'on'         => esc_html__( 'Show', 'funiter' ),
								'off'        => esc_html__( 'Hide', 'funiter' ),
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
						),
					),
				),
			);
			$options[] = array(
				'name'   => 'footer',
				'title'  => esc_html__( 'Footer Settings', 'funiter' ),
				'icon'   => 'fa fa-folder-open-o',
				'fields' => array(
					array(
						'id'      => 'funiter_footer_options',
						'type'    => 'select_preview',
						'title'   => esc_html__( 'Select Footer Layout', 'funiter' ),
						'options' => self::get_footer_preview(),
						'default' => 'default',
					),
					array(
						'id'         => 'enable_footer_mobile',
						'type'       => 'switcher',
						'title'      => esc_html__( 'Footer Mobile', 'funiter' ),
						'desc'       => esc_html__( 'On/Off footer on mobile', 'funiter' ),
						'default'    => true,
						'on'         => esc_html__( 'On', 'funiter' ),
						'off'        => esc_html__( 'Off', 'funiter' ),
						'dependency' => array( 'enable_header_mobile', '==', true )
					),
					array(
						'id'         => 'funiter_footer_mobile_options',
						'type'       => 'select_preview',
						'title'      => esc_html__( 'Select Footer Layout', 'funiter' ),
						'options'    => self::get_footer_preview(),
						'default'    => 'default',
						'dependency' => array( 'enable_footer_mobile', '==', true )
					),
				
				),
			);
			
			$options[] = array(
				'name'     => 'blog_main',
				'title'    => esc_html__( 'Blog', 'funiter' ),
				'icon'     => 'fa fa-wordpress',
				'sections' => array(
					array(
						'name'   => 'blog',
						'title'  => esc_html__( 'Blog', 'funiter' ),
						'fields' => array(
							array(
								'id'    => 'funiter_blog_banner',
								'type'  => 'image',
								'title' => esc_html__( 'Blog Banner', 'funiter' ),
							),
							array(
								'id'         => 'funiter_blog_banner_link',
								'type'       => 'text',
								'default'    => '#',
								'title'      => esc_html__( 'Blog Banner Url', 'funiter' ),
								'dependency' => array( 'funiter_blog_banner', '!=', '' ),
							),
							'funiter_sidebar_blog_layout' => array(
								'id'      => 'funiter_sidebar_blog_layout',
								'type'    => 'image_select',
								'title'   => esc_html__( 'Blog Sidebar Layout', 'funiter' ),
								'desc'    => esc_html__( 'Select sidebar position on Blog.', 'funiter' ),
								'options' => array(
									'left'  => esc_attr( ' data:image/png;base64,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' ),
									'right' => esc_attr( ' data:image/png;base64,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' ),
									'full'  => esc_attr( ' data:image/png;base64,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' ),
								),
								'default' => 'left',
							),
							'funiter_blog_used_sidebar'   => array(
								'id'         => 'funiter_blog_used_sidebar',
								'type'       => 'select',
								'default'    => 'widget-area',
								'title'      => esc_html__( 'Blog Sidebar', 'funiter' ),
								'options'    => $this->get_sidebar_options(),
								'dependency' => array( 'funiter_sidebar_blog_layout_full', '==', false ),
							),
							'funiter_blog_list_style'     => array(
								'id'      => 'funiter_blog_list_style',
								'type'    => 'select',
								'default' => 'standard',
								'title'   => esc_html__( 'Blog List Style', 'funiter' ),
								'options' => array(
									'standard' => esc_html__( 'Standard', 'funiter' ),
									'classic'  => esc_html__( 'Classic', 'funiter' ),
									'grid'     => esc_html__( 'Grid', 'funiter' ),
								),
							),
							'funiter_blog_bg_items'       => array(
								'id'         => 'funiter_blog_bg_items',
								'type'       => 'select',
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'funiter' ),
								'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'funiter' ),
								'options'    => array(
									'12' => esc_html__( '1 item', 'funiter' ),
									'6'  => esc_html__( '2 items', 'funiter' ),
									'4'  => esc_html__( '3 items', 'funiter' ),
									'3'  => esc_html__( '4 items', 'funiter' ),
									'15' => esc_html__( '5 items', 'funiter' ),
									'2'  => esc_html__( '6 items', 'funiter' ),
								),
								'default'    => '4',
								'dependency' => array( 'funiter_blog_list_style', '==', 'grid' ),
							),
							'funiter_blog_lg_items'       => array(
								'id'         => 'funiter_blog_lg_items',
								'default'    => '4',
								'type'       => 'select',
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'funiter' ),
								'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'funiter' ),
								'options'    => array(
									'12' => esc_html__( '1 item', 'funiter' ),
									'6'  => esc_html__( '2 items', 'funiter' ),
									'4'  => esc_html__( '3 items', 'funiter' ),
									'3'  => esc_html__( '4 items', 'funiter' ),
									'15' => esc_html__( '5 items', 'funiter' ),
									'2'  => esc_html__( '6 items', 'funiter' ),
								),
								'dependency' => array( 'funiter_blog_list_style', '==', 'grid' ),
							),
							'funiter_blog_md_items'       => array(
								'id'         => 'funiter_blog_md_items',
								'default'    => '4',
								'type'       => 'select',
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'funiter' ),
								'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'funiter' ),
								'options'    => array(
									'12' => esc_html__( '1 item', 'funiter' ),
									'6'  => esc_html__( '2 items', 'funiter' ),
									'4'  => esc_html__( '3 items', 'funiter' ),
									'3'  => esc_html__( '4 items', 'funiter' ),
									'15' => esc_html__( '5 items', 'funiter' ),
									'2'  => esc_html__( '6 items', 'funiter' ),
								),
								'dependency' => array( 'funiter_blog_list_style', '==', 'grid' ),
							),
							'funiter_blog_sm_items'       => array(
								'id'         => 'funiter_blog_sm_items',
								'default'    => '4',
								'type'       => 'select',
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'funiter' ),
								'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'funiter' ),
								'options'    => array(
									'12' => esc_html__( '1 item', 'funiter' ),
									'6'  => esc_html__( '2 items', 'funiter' ),
									'4'  => esc_html__( '3 items', 'funiter' ),
									'3'  => esc_html__( '4 items', 'funiter' ),
									'15' => esc_html__( '5 items', 'funiter' ),
									'2'  => esc_html__( '6 items', 'funiter' ),
								),
								'dependency' => array( 'funiter_blog_list_style', '==', 'grid' ),
							),
							'funiter_blog_xs_items'       => array(
								'id'         => 'funiter_blog_xs_items',
								'default'    => '6',
								'type'       => 'select',
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'funiter' ),
								'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'funiter' ),
								'options'    => array(
									'12' => esc_html__( '1 item', 'funiter' ),
									'6'  => esc_html__( '2 items', 'funiter' ),
									'4'  => esc_html__( '3 items', 'funiter' ),
									'3'  => esc_html__( '4 items', 'funiter' ),
									'15' => esc_html__( '5 items', 'funiter' ),
									'2'  => esc_html__( '6 items', 'funiter' ),
								),
								'dependency' => array( 'funiter_blog_list_style', '==', 'grid' ),
							),
							'funiter_blog_ts_items'       => array(
								'id'         => 'funiter_blog_ts_items',
								'default'    => '12',
								'type'       => 'select',
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'funiter' ),
								'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'funiter' ),
								'options'    => array(
									'12' => esc_html__( '1 item', 'funiter' ),
									'6'  => esc_html__( '2 items', 'funiter' ),
									'4'  => esc_html__( '3 items', 'funiter' ),
									'3'  => esc_html__( '4 items', 'funiter' ),
									'15' => esc_html__( '5 items', 'funiter' ),
									'2'  => esc_html__( '6 items', 'funiter' ),
								),
								'dependency' => array( 'funiter_blog_list_style', '==', 'grid' ),
							),
						),
					),
					array(
						'name'   => 'blog_single',
						'title'  => esc_html__( 'Blog Single', 'funiter' ),
						'fields' => array(
							'enable_share_post'             => array(
								'id'    => 'enable_share_post',
								'type'  => 'switcher',
								'title' => esc_html__( 'Enable Share Button', 'funiter' ),
							),
							'enable_author_info'            => array(
								'id'    => 'enable_author_info',
								'type'  => 'switcher',
								'title' => esc_html__( 'Enable Author Info', 'funiter' ),
							),
							'funiter_sidebar_single_layout' => array(
								'id'      => 'funiter_sidebar_single_layout',
								'type'    => 'image_select',
								'default' => 'left',
								'title'   => esc_html__( 'Single Post Sidebar Layout', 'funiter' ),
								'desc'    => esc_html__( 'Select sidebar position on Blog.', 'funiter' ),
								'options' => array(
									'left'  => esc_attr( ' data:image/png;base64,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' ),
									'right' => esc_attr( ' data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC0AAAAkCAYAAAAdFbNSAAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzABhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/phCJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhMWE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQAkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+IoUspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdpr+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZD5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61MbU2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllirSKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79up+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6VhlWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lOk06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7RyFDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3IveRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+BZ7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5pDoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5qPNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIsOpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQrAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1dT1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aXDm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3SPVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKaRptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfVP1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADqYAAAOpgAABdvkl/FRgAAANRJREFUeNrs2TEKwkAQheF/Y0QUMSKIWOjZPJLn8SZptbSKSEQkjoVTiF0SXQ28aWanmN2PJWlmg5nRtUgB8jzfA5NvH2ZmZa+XbmaL5a6qqq3ZfVNzi9NiNl2nXqwiXVIGjIEAzL2u20/iRREJXQJ3X18a9Bev6FhhwNXzrekmyQ/+o/CWO4FuHUILLbTQQgsttNBCCy200EILLbTQQn8u7C3/PToAA8/9tugsEnr0cuawQX8GPlQHDkQYqvMc9Z790zhSf8R8AghdfL54AAAA//8DAAqrKVvBESHfAAAAAElFTkSuQmCC' ),
									'full'  => esc_attr( ' data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC0AAAAkCAYAAAAdFbNSAAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzABhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/phCJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhMWE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQAkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+IoUspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdpr+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZD5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61MbU2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllirSKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79up+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6VhlWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lOk06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7RyFDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3IveRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+BZ7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5pDoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5qPNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIsOpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQrAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1dT1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aXDm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3SPVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKaRptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfVP1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADqYAAAOpgAABdvkl/FRgAAAHpJREFUeNrs2TEOgCAMRuGHYcYT6Mr9j8PsCfQCuDAY42pCk/cvXRi+Nkxt6r0TLRmgtfaUX8BMnaRRC3DUWvf88ahMPOQNYAn2M86IaESLFi1atGjRokWLFi1atGjRokWLFi36r6wwluqvTL1UB0gRzxc3AAAA//8DAMyCEVUq/bK3AAAAAElFTkSuQmCC' ),
								),
							),
							'funiter_single_used_sidebar'   => array(
								'id'         => 'funiter_single_used_sidebar',
								'type'       => 'select',
								'default'    => 'widget-area',
								'title'      => esc_html__( 'Single Blog Sidebar', 'funiter' ),
								'options'    => $this->get_sidebar_options(),
								'dependency' => array( 'funiter_sidebar_single_layout_full', '==', false ),
							),
						),
					),
				),
			);
			if ( class_exists( 'WooCommerce' ) ) {
				$options[] = array(
					'name'     => 'woocommerce_main',
					'title'    => esc_html__( 'WooCommerce Options', 'funiter' ),
					'icon'     => 'fa fa-wordpress',
					'sections' => array(
						array(
							'name'   => 'woocommerce',
							'title'  => esc_html__( 'WooCommerce', 'funiter' ),
							'fields' => array(
								'enable_noteworthy_products'  => array(
									'id'      => 'enable_noteworthy_products',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Enable Noteworthy Products', 'funiter' ),
									'default' => false,
									'desc'    => esc_html__( 'Show featured, top rated and top selling products on shop, single product, pages...', 'funiter' ),
								),
								'funiter_product_newness'     => array(
									'id'      => 'funiter_product_newness',
									'default' => '10',
									'type'    => 'number',
									'title'   => esc_html__( 'Products Newness', 'funiter' ),
									'desc'    => esc_html__( 'The number of days the product is still considered new', 'funiter' ),
								),
								'funiter_sidebar_shop_layout' => array(
									'id'      => 'funiter_sidebar_shop_layout',
									'type'    => 'image_select',
									'default' => 'left',
									'title'   => esc_html__( 'Shop Page Sidebar Layout', 'funiter' ),
									'desc'    => esc_html__( 'Select sidebar position on Shop Page.', 'funiter' ),
									'options' => array(
										'left'  => esc_attr( ' data:image/png;base64,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' ),
										'right' => esc_attr( ' data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC0AAAAkCAYAAAAdFbNSAAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzABhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/phCJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhMWE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQAkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+IoUspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdpr+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZD5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61MbU2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllirSKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79up+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6VhlWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lOk06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7RyFDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3IveRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+BZ7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5pDoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5qPNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIsOpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQrAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1dT1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aXDm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3SPVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKaRptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfVP1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADqYAAAOpgAABdvkl/FRgAAANRJREFUeNrs2TEKwkAQheF/Y0QUMSKIWOjZPJLn8SZptbSKSEQkjoVTiF0SXQ28aWanmN2PJWlmg5nRtUgB8jzfA5NvH2ZmZa+XbmaL5a6qqq3ZfVNzi9NiNl2nXqwiXVIGjIEAzL2u20/iRREJXQJ3X18a9Bev6FhhwNXzrekmyQ/+o/CWO4FuHUILLbTQQgsttNBCCy200EILLbTQQn8u7C3/PToAA8/9tugsEnr0cuawQX8GPlQHDkQYqvMc9Z790zhSf8R8AghdfL54AAAA//8DAAqrKVvBESHfAAAAAElFTkSuQmCC' ),
										'full'  => esc_attr( ' data:image/png;base64,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' ),
									),
								),
								'funiter_shop_used_sidebar'   => array(
									'id'         => 'funiter_shop_used_sidebar',
									'type'       => 'select',
									'title'      => esc_html__( 'Sidebar Used For Shop', 'funiter' ),
									'options'    => $this->get_sidebar_options(),
									'dependency' => array( 'funiter_sidebar_shop_layout_full', '==', false ),
								),
								'funiter_shop_list_style'     => array(
									'id'      => 'funiter_shop_list_style',
									'type'    => 'image_select',
									'default' => 'grid',
									'title'   => esc_html__( 'Shop Default Layout', 'funiter' ),
									'desc'    => esc_html__( 'Select default layout for shop, product category archive.', 'funiter' ),
									'options' => array(
										'grid' => get_theme_file_uri( 'assets/images/grid-display.png' ),
										'list' => get_theme_file_uri( 'assets/images/list-display.png' ),
									),
								),
								//								'funiter_attribute_product'   => array(
								//									'id'      => 'funiter_attribute_product',
								//									'type'    => 'select',
								//									'title'   => esc_html__( 'Product Attribute', 'funiter' ),
								//									'options' => $this->funiter_attributes_options(),
								//								),
								'funiter_product_per_page'    => array(
									'id'      => 'funiter_product_per_page',
									'type'    => 'number',
									'default' => '10',
									'title'   => esc_html__( 'Products perpage', 'funiter' ),
									'desc'    => esc_html__( 'Number of products on shop page.', 'funiter' ),
								),
								'cart_layout'                 => array(
									'id'      => 'cart_layout',
									'type'    => 'select',
									'title'   => esc_html__( 'Cart Layout', 'funiter' ),
									'options' => array(
										'full_width' => esc_html__( 'Full Width', 'funiter' ),
										'side_bar'   => esc_html__( 'Side Bar', 'funiter' )
									),
									'default' => 'full_width',
								),
								array(
									'id'      => 'enable_second_product_img',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Second Image', 'funiter' ),
									'default' => false,
									'desc'    => esc_html__( 'Show second image when hover on product', 'funiter' ),
								),
								array(
									'id'      => 'enable_shop_mobile',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Shop Mobile Layout', 'funiter' ),
									'default' => true,
									'desc'    => esc_html__( 'Use the dedicated mobile interface on a real device instead of responsive. Note, this option is not available for desktop browsing and uses resize the screen.', 'funiter' ),
								),
								'product_carousel'            => array(
									'id'      => 'product_carousel',
									'type'    => 'heading',
									'content' => esc_html__( 'Grid Settings', 'funiter' ),
								),
								'funiter_woo_bg_items'        => array(
									'id'      => 'funiter_woo_bg_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'funiter' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1500px )', 'funiter' ),
									'options' => array(
										'12' => esc_html__( '1 item', 'funiter' ),
										'6'  => esc_html__( '2 items', 'funiter' ),
										'4'  => esc_html__( '3 items', 'funiter' ),
										'3'  => esc_html__( '4 items', 'funiter' ),
										'15' => esc_html__( '5 items', 'funiter' ),
										'2'  => esc_html__( '6 items', 'funiter' ),
									),
									'default' => '4',
								),
								'funiter_woo_lg_items'        => array(
									'id'      => 'funiter_woo_lg_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'funiter' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'funiter' ),
									'options' => array(
										'12' => esc_html__( '1 item', 'funiter' ),
										'6'  => esc_html__( '2 items', 'funiter' ),
										'4'  => esc_html__( '3 items', 'funiter' ),
										'3'  => esc_html__( '4 items', 'funiter' ),
										'15' => esc_html__( '5 items', 'funiter' ),
										'2'  => esc_html__( '6 items', 'funiter' ),
									),
									'default' => '4',
								),
								'funiter_woo_md_items'        => array(
									'id'      => 'funiter_woo_md_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'funiter' ),
									'desc'    => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'funiter' ),
									'options' => array(
										'12' => esc_html__( '1 item', 'funiter' ),
										'6'  => esc_html__( '2 items', 'funiter' ),
										'4'  => esc_html__( '3 items', 'funiter' ),
										'3'  => esc_html__( '4 items', 'funiter' ),
										'15' => esc_html__( '5 items', 'funiter' ),
										'2'  => esc_html__( '6 items', 'funiter' ),
									),
									'default' => '4',
								),
								'funiter_woo_sm_items'        => array(
									'id'      => 'funiter_woo_sm_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'funiter' ),
									'desc'    => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'funiter' ),
									'options' => array(
										'12' => esc_html__( '1 item', 'funiter' ),
										'6'  => esc_html__( '2 items', 'funiter' ),
										'4'  => esc_html__( '3 items', 'funiter' ),
										'3'  => esc_html__( '4 items', 'funiter' ),
										'15' => esc_html__( '5 items', 'funiter' ),
										'2'  => esc_html__( '6 items', 'funiter' ),
									),
									'default' => '4',
								),
								'funiter_woo_xs_items'        => array(
									'id'      => 'funiter_woo_xs_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'funiter' ),
									'desc'    => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'funiter' ),
									'options' => array(
										'12' => esc_html__( '1 item', 'funiter' ),
										'6'  => esc_html__( '2 items', 'funiter' ),
										'4'  => esc_html__( '3 items', 'funiter' ),
										'3'  => esc_html__( '4 items', 'funiter' ),
										'15' => esc_html__( '5 items', 'funiter' ),
										'2'  => esc_html__( '6 items', 'funiter' ),
									),
									'default' => '6',
								),
								'funiter_woo_ts_items'        => array(
									'id'      => 'funiter_woo_ts_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'funiter' ),
									'desc'    => esc_html__( '(Screen resolution of device < 480px)', 'funiter' ),
									'options' => array(
										'12' => esc_html__( '1 item', 'funiter' ),
										'6'  => esc_html__( '2 items', 'funiter' ),
										'4'  => esc_html__( '3 items', 'funiter' ),
										'3'  => esc_html__( '4 items', 'funiter' ),
										'15' => esc_html__( '5 items', 'funiter' ),
										'2'  => esc_html__( '6 items', 'funiter' ),
									),
									'default' => '12',
								),
							),
						),
						array(
							'name'   => 'categories',
							'title'  => esc_html__( 'Categories', 'funiter' ),
							'fields' => array(
								'funiter_woo_cat_enable'   => array(
									'id'    => 'funiter_woo_cat_enable',
									'type'  => 'switcher',
									'title' => esc_html__( 'Enable Category Products', 'funiter' ),
								),
								array(
									'id'         => 'category_banner',
									'type'       => 'image',
									'title'      => esc_html__( 'Categories banner', 'funiter' ),
									'desc'       => esc_html__( 'Banner in category page WooCommerce.', 'funiter' ),
									'dependency' => array( 'funiter_woo_cat_enable', '==', true ),
								),
								array(
									'id'         => 'category_banner_url',
									'type'       => 'text',
									'default'    => '#',
									'title'      => esc_html__( 'Banner Url', 'funiter' ),
									'dependency' => array( 'funiter_woo_cat_enable', '==', true ),
								),
								'funiter_woo_cat_ls_items' => array(
									'id'         => 'funiter_woo_cat_ls_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Category products items per row on Desktop', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '3',
									'dependency' => array( 'funiter_woo_cat_enable', '==', true ),
								),
								'funiter_woo_cat_lg_items' => array(
									'id'         => 'funiter_woo_cat_lg_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Category products items per row on Desktop', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '3',
									'dependency' => array( 'funiter_woo_cat_enable', '==', true ),
								),
								'funiter_woo_cat_md_items' => array(
									'id'         => 'funiter_woo_cat_md_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Category products items per row on landscape tablet', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '3',
									'dependency' => array( 'funiter_woo_cat_enable', '==', true ),
								),
								'funiter_woo_cat_sm_items' => array(
									'id'         => 'funiter_woo_cat_sm_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Category product items per row on portrait tablet', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '2',
									'dependency' => array( 'funiter_woo_cat_enable', '==', true ),
								),
								'funiter_woo_cat_xs_items' => array(
									'id'         => 'funiter_woo_cat_xs_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Category products items per row on Mobile', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '1',
									'dependency' => array( 'funiter_woo_cat_enable', '==', true ),
								),
								'funiter_woo_cat_ts_items' => array(
									'id'         => 'funiter_woo_cat_ts_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Category products items per row on Mobile', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '1',
									'dependency' => array( 'funiter_woo_cat_enable', '==', true ),
								),
							),
						),
						array(
							'name'   => 'single_product',
							'title'  => esc_html__( 'Single Products', 'funiter' ),
							'fields' => array(
								array(
									'id'      => 'enable_single_product_mobile',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Enable Product Mobile Layout', 'funiter' ),
									'default' => false,
									'desc'    => esc_html__( 'Enabling this feature will display a mobile-specific layout. This layout is independent of the responsive interface. Maybe some hooks of 3rd party plugins will not work correctly on the mobile layout.', 'funiter' ),
								),
								array(
									'id'      => 'enable_info_product_single',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Single Product Sticky Info ', 'funiter' ),
									'default' => true,
									'desc'    => esc_html__( 'Enable or disable product info sticky on the single product page. This function only applies to the desktop view.', 'funiter' ),
								),
								array(
									'id'             => 'funiter_add_page_product',
									'type'           => 'select',
									'title'          => esc_html__( 'Page Content', 'funiter' ),
									'options'        => 'pages',
									'default_option' => esc_html__( 'Select a page', 'funiter' ),
								),
								array(
									'id'      => 'show_single_product_brands',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Show Brands', 'funiter' ),
									'default' => false
								),
								array(
									'id'         => 'show_single_product_brand_titles',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Show Brands Title', 'funiter' ),
									'default'    => false,
									'desc'       => esc_html__( 'Only brand images are displayed if this feature is disabled.', 'funiter' ),
									'dependency' => array( 'show_single_product_brands', '==', true ),
								),
								array(
									'id'      => 'enable_single_product_sharing',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Enable Product Sharing', 'funiter' ),
									'default' => false,
								),
								array(
									'id'         => 'enable_single_product_sharing_fb',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Facebook Sharing', 'funiter' ),
									'default'    => true,
									'dependency' => array( 'enable_single_product_sharing', '==', true ),
									'desc'       => esc_html__( 'On or Off Facebook Share When On Product Sharing', 'funiter' )
								),
								array(
									'id'         => 'enable_single_product_sharing_tw',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Twitter Sharing', 'funiter' ),
									'default'    => true,
									'dependency' => array( 'enable_single_product_sharing', '==', true ),
									'desc'       => esc_html__( 'On or Off Twitter Share When On Product Sharing', 'funiter' )
								),
								array(
									'id'         => 'enable_single_product_sharing_pinterest',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Pinterest Sharing', 'funiter' ),
									'default'    => true,
									'dependency' => array( 'enable_single_product_sharing', '==', true ),
									'desc'       => esc_html__( 'On or Off Pinterest Share When On Product Sharing', 'funiter' )
								),
								array(
									'id'         => 'enable_single_product_sharing_gplus',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Google Plus Sharing', 'funiter' ),
									'default'    => true,
									'dependency' => array( 'enable_single_product_sharing', '==', true ),
									'desc'       => esc_html__( 'On or Off Google Share When On Product Sharing', 'funiter' )
								),
								'funiter_product_thumbnail_ls_items' => array(
									'id'      => 'funiter_product_thumbnail_ls_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Thumbnail items per row on Desktop', 'funiter' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1500px )', 'funiter' ),
									'options' => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default' => '3',
								),
								'funiter_product_thumbnail_lg_items' => array(
									'id'      => 'funiter_product_thumbnail_lg_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Thumbnail items per row on Desktop', 'funiter' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'funiter' ),
									'options' => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default' => '3',
								),
								'funiter_product_thumbnail_md_items' => array(
									'id'      => 'funiter_product_thumbnail_md_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Thumbnail items per row on landscape tablet', 'funiter' ),
									'desc'    => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'funiter' ),
									'options' => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default' => '3',
								),
								'funiter_product_thumbnail_sm_items' => array(
									'id'      => 'funiter_product_thumbnail_sm_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Thumbnail items per row on portrait tablet', 'funiter' ),
									'desc'    => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'funiter' ),
									'options' => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default' => '2',
								),
								'funiter_product_thumbnail_xs_items' => array(
									'id'      => 'funiter_product_thumbnail_xs_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Thumbnail items per row on Mobile', 'funiter' ),
									'desc'    => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'funiter' ),
									'options' => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default' => '1',
								),
								'funiter_product_thumbnail_ts_items' => array(
									'id'      => 'funiter_product_thumbnail_ts_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Thumbnail items per row on Mobile', 'funiter' ),
									'desc'    => esc_html__( '(Screen resolution of device < 480px)', 'funiter' ),
									'options' => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default' => '1',
								),
							),
						),
						array(
							'name'   => 'extend_single_product',
							'title'  => esc_html__( 'Extend Single Products', 'funiter' ),
							'fields' => array(
								'funiter_sidebar_product_layout'               => array(
									'id'      => 'funiter_sidebar_product_layout',
									'type'    => 'image_select',
									'default' => 'left',
									'title'   => esc_html__( 'Product Page Sidebar Layout', 'funiter' ),
									'desc'    => esc_html__( 'Select sidebar position on Product Page.', 'funiter' ),
									'options' => array(
										'left'  => esc_attr( ' data:image/png;base64,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' ),
										'right' => esc_attr( ' data:image/png;base64,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' ),
										'full'  => esc_attr( ' data:image/png;base64,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' ),
									),
								),
								'funiter_single_product_used_sidebar'          => array(
									'id'         => 'funiter_single_product_used_sidebar',
									'type'       => 'select',
									'title'      => esc_html__( 'Sidebar Used For Single Product', 'funiter' ),
									'options'    => $this->get_sidebar_options(),
									'dependency' => array( 'funiter_sidebar_product_layout_full', '==', false ),
								),
								'funiter_single_product_summary_sidebar'       => array(
									'id'         => 'funiter_single_product_summary_sidebar',
									'type'       => 'select',
									'title'      => esc_html__( 'Sidebar Used For summary Single Product', 'funiter' ),
									'options'    => $this->get_sidebar_options(),
									'dependency' => array( 'funiter_sidebar_product_layout_full', '==', true ),
								),
								'funiter_single_product_summary_sidebar_style' => array(
									'id'         => 'funiter_single_product_summary_sidebar_style',
									'type'       => 'select',
									'title'      => esc_html__( 'Type Of Sidebar Used For summary Single Product', 'funiter' ),
									'desc'       => esc_html__( 'Sidebar for summary vertical or horizontal', 'funiter' ),
									'options'    => array(
										'vertical'   => esc_html__( 'Vertical', 'funiter' ),
										'horizontal' => esc_html__( 'Horizontal', 'funiter' ),
										'disable'    => esc_html__( 'Disable', 'funiter' ),
									),
									'default'    => 'vertical',
									'dependency' => array( 'funiter_sidebar_product_layout_full', '==', true ),
								),
							),
						),
						array(
							'name'   => 'funiter_recently_view_product',
							'title'  => esc_html__( 'Recently Viewed Products', 'funiter' ),
							'fields' => array(
								'funiter_woo_recently_enable'         => array(
									'id'      => 'funiter_woo_recently_enable',
									'type'    => 'select',
									'default' => 'disable',
									'options' => array(
										'enable'  => esc_html__( 'Enable', 'funiter' ),
										'disable' => esc_html__( 'Disable', 'funiter' ),
									),
									'title'   => esc_html__( 'Enable Recently Viewed Products', 'funiter' ),
								),
								'funiter_woo_recently_products_title' => array(
									'id'         => 'funiter_woo_recently_products_title',
									'type'       => 'text',
									'title'      => esc_html__( 'Recently products title', 'funiter' ),
									'desc'       => esc_html__( 'Recently products title', 'funiter' ),
									'dependency' => array( 'funiter_woo_recently_enable', '==', 'enable' ),
									'default'    => esc_html__( 'Recently Viewed', 'funiter' ),
								),
								'funiter_woo_recently_ls_items'       => array(
									'id'         => 'funiter_woo_recently_ls_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Recently products items per row on Desktop', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '3',
									'dependency' => array( 'funiter_woo_recently_enable', '==', 'enable' ),
								),
								'funiter_woo_recently_lg_items'       => array(
									'id'         => 'funiter_woo_recently_lg_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Recently products items per row on Desktop', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '3',
									'dependency' => array( 'funiter_woo_recently_enable', '==', 'enable' ),
								),
								'funiter_woo_recently_md_items'       => array(
									'id'         => 'funiter_woo_recently_md_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Recently products items per row on landscape tablet', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '3',
									'dependency' => array( 'funiter_woo_recently_enable', '==', 'enable' ),
								),
								'funiter_woo_recently_sm_items'       => array(
									'id'         => 'funiter_woo_recently_sm_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Recently product items per row on portrait tablet', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '2',
									'dependency' => array( 'funiter_woo_recently_enable', '==', 'enable' ),
								),
								'funiter_woo_recently_xs_items'       => array(
									'id'         => 'funiter_woo_recently_xs_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Recently products items per row on Mobile', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '1',
									'dependency' => array( 'funiter_woo_recently_enable', '==', 'enable' ),
								),
								'funiter_woo_recently_ts_items'       => array(
									'id'         => 'funiter_woo_recently_ts_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Recently products items per row on Mobile', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '1',
									'dependency' => array( 'funiter_woo_recently_enable', '==', 'enable' ),
								),
							),
						),
						array(
							'name'   => 'funiter_related_product',
							'title'  => esc_html__( 'Related Products', 'funiter' ),
							'fields' => array(
								'funiter_woo_related_enable'         => array(
									'id'      => 'funiter_woo_related_enable',
									'type'    => 'select',
									'default' => 'enable',
									'options' => array(
										'enable'  => esc_html__( 'Enable', 'funiter' ),
										'disable' => esc_html__( 'Disable', 'funiter' ),
									),
									'title'   => esc_html__( 'Enable Related Products', 'funiter' ),
								),
								'funiter_woo_related_products_title' => array(
									'id'         => 'funiter_woo_related_products_title',
									'type'       => 'text',
									'title'      => esc_html__( 'Related products title', 'funiter' ),
									'desc'       => esc_html__( 'Related products title', 'funiter' ),
									'dependency' => array( 'funiter_woo_related_enable', '==', 'enable' ),
									'default'    => esc_html__( 'Related Products', 'funiter' ),
								),
								'funiter_woo_related_ls_items'       => array(
									'id'         => 'funiter_woo_related_ls_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Related products items per row on Desktop', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '3',
									'dependency' => array( 'funiter_woo_related_enable', '==', 'enable' ),
								),
								'funiter_woo_related_lg_items'       => array(
									'id'         => 'funiter_woo_related_lg_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Related products items per row on Desktop', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '3',
									'dependency' => array( 'funiter_woo_related_enable', '==', 'enable' ),
								),
								'funiter_woo_related_md_items'       => array(
									'id'         => 'funiter_woo_related_md_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Related products items per row on landscape tablet', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '3',
									'dependency' => array( 'funiter_woo_related_enable', '==', 'enable' ),
								),
								'funiter_woo_related_sm_items'       => array(
									'id'         => 'funiter_woo_related_sm_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Related product items per row on portrait tablet', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '2',
									'dependency' => array( 'funiter_woo_related_enable', '==', 'enable' ),
								),
								'funiter_woo_related_xs_items'       => array(
									'id'         => 'funiter_woo_related_xs_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Related products items per row on Mobile', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '1',
									'dependency' => array( 'funiter_woo_related_enable', '==', 'enable' ),
								),
								'funiter_woo_related_ts_items'       => array(
									'id'         => 'funiter_woo_related_ts_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Related products items per row on Mobile', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '1',
									'dependency' => array( 'funiter_woo_related_enable', '==', 'enable' ),
								),
							),
						),
						array(
							'name'   => 'crosssell_product',
							'title'  => esc_html__( 'Cross Sell Products', 'funiter' ),
							'fields' => array(
								'funiter_woo_crosssell_enable'         => array(
									'id'      => 'funiter_woo_crosssell_enable',
									'type'    => 'select',
									'default' => 'enable',
									'options' => array(
										'enable'  => esc_html__( 'Enable', 'funiter' ),
										'disable' => esc_html__( 'Disable', 'funiter' ),
									),
									'title'   => esc_html__( 'Enable Cross Sell Products', 'funiter' ),
								),
								'funiter_woo_crosssell_products_title' => array(
									'id'         => 'funiter_woo_crosssell_products_title',
									'type'       => 'text',
									'title'      => esc_html__( 'Cross Sell products title', 'funiter' ),
									'desc'       => esc_html__( 'Cross Sell products title', 'funiter' ),
									'dependency' => array( 'funiter_woo_crosssell_enable', '==', 'enable' ),
									'default'    => esc_html__( 'Cross Sell Products', 'funiter' ),
								),
								'funiter_woo_crosssell_ls_items'       => array(
									'id'         => 'funiter_woo_crosssell_ls_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Cross Sell products items per row on Desktop', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '3',
									'dependency' => array( 'funiter_woo_crosssell_enable', '==', 'enable' ),
								),
								'funiter_woo_crosssell_lg_items'       => array(
									'id'         => 'funiter_woo_crosssell_lg_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Cross Sell products items per row on Desktop', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '3',
									'dependency' => array( 'funiter_woo_crosssell_enable', '==', 'enable' ),
								),
								'funiter_woo_crosssell_md_items'       => array(
									'id'         => 'funiter_woo_crosssell_md_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Cross Sell products items per row on landscape tablet', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '3',
									'dependency' => array( 'funiter_woo_crosssell_enable', '==', 'enable' ),
								),
								'funiter_woo_crosssell_sm_items'       => array(
									'id'         => 'funiter_woo_crosssell_sm_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Cross Sell product items per row on portrait tablet', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '2',
									'dependency' => array( 'funiter_woo_crosssell_enable', '==', 'enable' ),
								),
								'funiter_woo_crosssell_xs_items'       => array(
									'id'         => 'funiter_woo_crosssell_xs_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Cross Sell products items per row on Mobile', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '1',
									'dependency' => array( 'funiter_woo_crosssell_enable', '==', 'enable' ),
								),
								'funiter_woo_crosssell_ts_items'       => array(
									'id'         => 'funiter_woo_crosssell_ts_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Cross Sell products items per row on Mobile', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '1',
									'dependency' => array( 'funiter_woo_crosssell_enable', '==', 'enable' ),
								),
							),
						),
						array(
							'name'   => 'upsell_product',
							'title'  => esc_html__( 'Upsell Products', 'funiter' ),
							'fields' => array(
								'funiter_woo_upsell_enable'         => array(
									'id'      => 'funiter_woo_upsell_enable',
									'type'    => 'select',
									'default' => 'enable',
									'options' => array(
										'enable'  => esc_html__( 'Enable', 'funiter' ),
										'disable' => esc_html__( 'Disable', 'funiter' ),
									),
									'title'   => esc_html__( 'Enable Upsell Products', 'funiter' ),
								),
								'funiter_woo_upsell_products_title' => array(
									'id'         => 'funiter_woo_upsell_products_title',
									'type'       => 'text',
									'title'      => esc_html__( 'Upsell products title', 'funiter' ),
									'desc'       => esc_html__( 'Upsell products title', 'funiter' ),
									'dependency' => array( 'funiter_woo_upsell_enable', '==', 'enable' ),
									'default'    => esc_html__( 'Upsell Products', 'funiter' ),
								),
								'funiter_woo_upsell_ls_items'       => array(
									'id'         => 'funiter_woo_upsell_ls_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Upsell products items per row on Desktop', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '3',
									'dependency' => array( 'funiter_woo_upsell_enable', '==', 'enable' ),
								),
								'funiter_woo_upsell_lg_items'       => array(
									'id'         => 'funiter_woo_upsell_lg_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Upsell products items per row on Desktop', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '3',
									'dependency' => array( 'funiter_woo_upsell_enable', '==', 'enable' ),
								),
								'funiter_woo_upsell_md_items'       => array(
									'id'         => 'funiter_woo_upsell_md_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Upsell products items per row on landscape tablet', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '3',
									'dependency' => array( 'funiter_woo_upsell_enable', '==', 'enable' ),
								),
								'funiter_woo_upsell_sm_items'       => array(
									'id'         => 'funiter_woo_upsell_sm_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Upsell product items per row on portrait tablet', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '2',
									'dependency' => array( 'funiter_woo_upsell_enable', '==', 'enable' ),
								),
								'funiter_woo_upsell_xs_items'       => array(
									'id'         => 'funiter_woo_upsell_xs_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Upsell products items per row on Mobile', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '1',
									'dependency' => array( 'funiter_woo_upsell_enable', '==', 'enable' ),
								),
								'funiter_woo_upsell_ts_items'       => array(
									'id'         => 'funiter_woo_upsell_ts_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Upsell products items per row on Mobile', 'funiter' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'funiter' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'funiter' ),
										'2' => esc_html__( '2 items', 'funiter' ),
										'3' => esc_html__( '3 items', 'funiter' ),
										'4' => esc_html__( '4 items', 'funiter' ),
										'5' => esc_html__( '5 items', 'funiter' ),
										'6' => esc_html__( '6 items', 'funiter' ),
									),
									'default'    => '1',
									'dependency' => array( 'funiter_woo_upsell_enable', '==', 'enable' ),
								),
							),
						),
					),
				);
			}
			$options[] = array(
				'name'   => 'social_settings',
				'title'  => esc_html__( 'Social Settings', 'funiter' ),
				'icon'   => 'fa fa-users',
				'fields' => array(
					array(
						'type'    => 'subheading',
						'content' => esc_html__( 'Social User', 'funiter' ),
					),
					array(
						'id'              => 'user_all_social',
						'type'            => 'group',
						'title'           => esc_html__( 'Social', 'funiter' ),
						'button_title'    => esc_html__( 'Add New Social', 'funiter' ),
						'accordion_title' => esc_html__( 'Social Settings', 'funiter' ),
						'fields'          => array(
							array(
								'id'      => 'title_social',
								'type'    => 'text',
								'title'   => esc_html__( 'Title Social', 'funiter' ),
								'default' => 'Facebook',
							),
							array(
								'id'      => 'link_social',
								'type'    => 'text',
								'title'   => esc_html__( 'Link Social', 'funiter' ),
								'default' => 'https://facebook.com',
							),
							array(
								'id'      => 'icon_social',
								'type'    => 'icon',
								'title'   => esc_html__( 'Icon Social', 'funiter' ),
								'default' => 'fa fa-facebook',
							),
						),
					),
				),
			);
			$options[] = array(
				'name'   => 'typography',
				'title'  => esc_html__( 'Typography Options', 'funiter' ),
				'icon'   => 'fa fa-font',
				'fields' => array(
					'funiter_enable_typography' => array(
						'id'    => 'funiter_enable_typography',
						'type'  => 'switcher',
						'title' => esc_html__( 'Enable Typography', 'funiter' ),
					),
					array(
						'id'              => 'typography_group',
						'type'            => 'group',
						'title'           => esc_html__( 'Typography Options', 'funiter' ),
						'button_title'    => esc_html__( 'Add New Typography', 'funiter' ),
						'accordion_title' => esc_html__( 'Typography Item', 'funiter' ),
						'dependency'      => array(
							'funiter_enable_typography',
							'==',
							true,
						),
						'fields'          => array(
							'funiter_element_tag'            => array(
								'id'      => 'funiter_element_tag',
								'type'    => 'select',
								'options' => array(
									'body' => esc_html__( 'Body', 'funiter' ),
									'h1'   => esc_html__( 'H1', 'funiter' ),
									'h2'   => esc_html__( 'H2', 'funiter' ),
									'h3'   => esc_html__( 'H3', 'funiter' ),
									'h4'   => esc_html__( 'H4', 'funiter' ),
									'h5'   => esc_html__( 'H5', 'funiter' ),
									'h6'   => esc_html__( 'H6', 'funiter' ),
									'p'    => esc_html__( 'P', 'funiter' ),
								),
								'title'   => esc_html__( 'Element Tag', 'funiter' ),
								'desc'    => esc_html__( 'Select a Element Tag HTML', 'funiter' ),
							),
							'funiter_typography_font_family' => array(
								'id'     => 'funiter_typography_font_family',
								'type'   => 'typography',
								'title'  => esc_html__( 'Font Family', 'funiter' ),
								'desc'   => esc_html__( 'Select a Font Family', 'funiter' ),
								'chosen' => false,
							),
							'funiter_body_text_color'        => array(
								'id'    => 'funiter_body_text_color',
								'type'  => 'color_picker',
								'title' => esc_html__( 'Body Text Color', 'funiter' ),
							),
							'funiter_typography_font_size'   => array(
								'id'      => 'funiter_typography_font_size',
								'type'    => 'number',
								'default' => 16,
								'title'   => esc_html__( 'Font Size', 'funiter' ),
								'desc'    => esc_html__( 'Unit PX', 'funiter' ),
							),
							'funiter_typography_line_height' => array(
								'id'      => 'funiter_typography_line_height',
								'type'    => 'number',
								'default' => 24,
								'title'   => esc_html__( 'Line Height', 'funiter' ),
								'desc'    => esc_html__( 'Unit PX', 'funiter' ),
							),
						),
						'default'         => array(
							array(
								'funiter_element_tag'            => 'body',
								'funiter_typography_font_family' => 'Arial',
								'funiter_body_text_color'        => '#81d742',
								'funiter_typography_font_size'   => 16,
								'funiter_typography_line_height' => 24,
							),
						),
					),
				),
			);
			$options[] = array(
				'name'   => 'backup_option',
				'title'  => esc_html__( 'Backup Options', 'funiter' ),
				'icon'   => 'fa fa-bold',
				'fields' => array(
					array(
						'type'  => 'backup',
						'title' => esc_html__( 'Backup Field', 'funiter' ),
					),
				),
			);
			
			return $options;
		}
		
		function metabox_options( $options ) {
			$options = array();
			// -----------------------------------------
			// Page Meta box Options                   -
			// -----------------------------------------
			$options[] = array(
				'id'        => '_custom_metabox_theme_options',
				'title'     => esc_html__( 'Custom Theme Options', 'funiter' ),
				'post_type' => 'page',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					'header' => array(
						'name'   => 'header',
						'title'  => esc_html__( 'Header Settings', 'funiter' ),
						'icon'   => 'fa fa-folder-open-o',
						'fields' => array(
							array(
								'id'      => 'funiter_metabox_enable_custom_header',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Custom Header', 'funiter' ),
								'default' => false,
							),
							array(
								'id'         => 'metabox_funiter_used_header',
								'type'       => 'select_preview',
								'title'      => esc_html__( 'Header Layout', 'funiter' ),
								'desc'       => esc_html__( 'Select a header layout', 'funiter' ),
								'options'    => self::get_header_options(),
								'default'    => 'style-02',
								'dependency' => array( 'funiter_metabox_enable_custom_header', '==', true ),
							)
						),
					),
					'footer' => array(
						'name'   => 'footer',
						'title'  => esc_html__( 'Footer Settings', 'funiter' ),
						'icon'   => 'fa fa-folder-open-o',
						'fields' => array(
							array(
								'id'      => 'funiter_metabox_enable_custom_footer',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Custom Footer', 'funiter' ),
								'default' => false,
							),
							array(
								'id'         => 'metabox_funiter_footer_options',
								'type'       => 'select_preview',
								'title'      => esc_html__( 'Select Footer Builder', 'funiter' ),
								'options'    => self::get_footer_preview(),
								'default'    => 'default',
								'dependency' => array( 'funiter_metabox_enable_custom_footer', '==', true ),
							),
						),
					),
				),
			);
			// -----------------------------------------
			// Post Meta box Options                   -
			// -----------------------------------------
			$options[] = array(
				'id'        => '_custom_metabox_post_options',
				'title'     => esc_html__( 'Custom Post Options', 'funiter' ),
				'post_type' => 'post',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					array(
						'name'   => 'gallery_settings',
						'title'  => esc_html__( 'Gallery Settings', 'funiter' ),
						'fields' => array(
							array(
								'id'    => 'gallery_post',
								'type'  => 'gallery',
								'title' => esc_html__( 'Gallery', 'funiter' ),
							),
						),
					),
					array(
						'name'   => 'video_settings',
						'title'  => esc_html__( 'Video Settings', 'funiter' ),
						'fields' => array(
							array(
								'id'       => 'video_post',
								'type'     => 'upload',
								'title'    => esc_html__( 'Video Url', 'funiter' ),
								'settings' => array(
									'upload_type'  => 'video',
									'button_title' => esc_html__( 'Video', 'funiter' ),
									'frame_title'  => esc_html__( 'Select a video', 'funiter' ),
									'insert_title' => esc_html__( 'Use this video', 'funiter' ),
								),
								'desc'     => esc_html__( 'Supports video Url Youtube and upload.', 'funiter' ),
							),
						),
					),
					array(
						'name'   => 'quote_settings',
						'title'  => esc_html__( 'Quote Settings', 'funiter' ),
						'fields' => array(
							array(
								'id'    => 'quote_post',
								'type'  => 'wysiwyg',
								'title' => esc_html__( 'Quote Text', 'funiter' ),
							),
						),
					),
				),
			);
			// -----------------------------------------
			// Page Footer Meta box Options            -
			// -----------------------------------------
			$options[] = array(
				'id'        => '_custom_footer_options',
				'title'     => esc_html__( 'Custom Footer Options', 'funiter' ),
				'post_type' => 'footer',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					array(
						'name'   => esc_html__( 'FOOTER STYLE', 'funiter' ),
						'fields' => array(
							array(
								'id'       => 'funiter_footer_style',
								'type'     => 'select_preview',
								'title'    => esc_html__( 'Footer Style', 'funiter' ),
								'subtitle' => esc_html__( 'Select a Footer Style', 'funiter' ),
								'options'  => self::get_footer_options(),
								'default'  => 'style-01',
							),
						),
					),
				),
			);
			// -----------------------------------------
			// Page Side Meta box Options              -
			// -----------------------------------------
			$options[] = array(
				'id'        => '_custom_page_side_options',
				'title'     => esc_html__( 'Custom Page Side Options', 'funiter' ),
				'post_type' => 'page',
				'context'   => 'side',
				'priority'  => 'default',
				'sections'  => array(
					array(
						'name'   => 'page_option',
						'fields' => array(
							array(
								'id'      => 'sidebar_page_layout',
								'type'    => 'image_select',
								'title'   => esc_html__( 'Single Post Sidebar Position', 'funiter' ),
								'desc'    => esc_html__( 'Select sidebar position on Page.', 'funiter' ),
								'options' => array(
									'left'  => esc_attr( ' data:image/png;base64,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' ),
									'right' => esc_attr( ' data:image/png;base64,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' ),
									'full'  => esc_attr( ' data:image/png;base64,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' ),
								),
								'default' => 'left',
							),
							array(
								'id'         => 'page_sidebar',
								'type'       => 'select',
								'title'      => esc_html__( 'Page Sidebar', 'funiter' ),
								'options'    => self::get_sidebar_options(),
								'default'    => 'blue',
								'dependency' => array( 'sidebar_page_layout_full', '==', false ),
							),
							array(
								'id'    => 'page_extra_class',
								'type'  => 'text',
								'title' => esc_html__( 'Extra Class', 'funiter' ),
							),
						),
					),
				),
			);
			// -----------------------------------------
			// Page Product Meta box Options      	   -
			// -----------------------------------------
			$options[] = array(
				'id'        => '_custom_product_woo_options',
				'title'     => esc_html__( 'Custom Product Options', 'funiter' ),
				'post_type' => 'product',
				'context'   => 'side',
				'priority'  => 'high',
				'sections'  => array(
					array(
						'name'   => 'product_detail',
						'fields' => array(
							array(
								'id'      => 'product_options',
								'type'    => 'select',
								'title'   => esc_html__( 'Format Product', 'funiter' ),
								'options' => array(
									'video'  => esc_html__( 'Video', 'funiter' ),
									'360deg' => esc_html__( '360 Degree', 'funiter' ),
								),
							),
							array(
								'id'         => 'degree_product_gallery',
								'type'       => 'gallery',
								'title'      => esc_html__( '360 Degree Product', 'funiter' ),
								'dependency' => array( 'product_options', '==', '360deg' ),
							),
							array(
								'id'         => 'video_product_url',
								'type'       => 'upload',
								'title'      => esc_html__( 'Video Url', 'funiter' ),
								'dependency' => array( 'product_options', '==', 'video' ),
							),
						),
					),
				),
			);
			
			return $options;
		}
	}
	
	new Funiter_ThemeOption();
}