<?php
/**
 * Funiter Visual composer setup
 *
 * @category API
 * @package  Funiter_Visual_composer
 * @since    1.0.0
 */
if ( ! function_exists( 'funiter_custom_param_vc' ) ) {
	add_filter( 'funiter_add_param_visual_composer', 'funiter_custom_param_vc' );
	function funiter_custom_param_vc( $param ) {
		$attributes_tax = array();
		if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			$attributes_tax = wc_get_attribute_taxonomies();
		}
		$attributes = array();
		if ( is_array( $attributes_tax ) && count( $attributes_tax ) > 0 ) {
			foreach ( $attributes_tax as $attribute ) {
				$attributes[ $attribute->attribute_label ] = $attribute->attribute_name;
			}
		}
		// CUSTOM PRODUCT OPTIONS
		$layoutDir       = get_template_directory() . '/woocommerce/product-styles/';
		$product_options = array();
		if ( is_dir( $layoutDir ) ) {
			$files = scandir( $layoutDir );
			if ( $files && is_array( $files ) ) {
				foreach ( $files as $file ) {
					if ( $file != '.' && $file != '..' ) {
						$fileInfo = pathinfo( $file );
						if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' && $fileInfo['filename'] != 'content-product-list' ) {
							$file_data                     = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
							$file_name                     = str_replace( 'content-product-style-', '', $fileInfo['filename'] );
							$product_options[ $file_name ] = array(
								'title'   => $file_data['Name'],
								'preview' => get_theme_file_uri( 'woocommerce/product-styles/content-product-style-' . $file_name . '.jpg' ),
							);
						}
					}
				}
			}
		}
		// CUSTOM PRODUCT SIZE
		$product_size_width_list = array();
		$width                   = 320;
		$height                  = 320;
		$crop                    = 1;
		if ( function_exists( 'wc_get_image_size' ) ) {
			$size   = wc_get_image_size( 'shop_catalog' );
			$width  = isset( $size['width'] ) ? $size['width'] : $width;
			$height = isset( $size['height'] ) ? $size['height'] : $height;
			$crop   = isset( $size['crop'] ) ? $size['crop'] : $crop;
		}
		for ( $i = 100; $i < $width; $i = $i + 10 ) {
			array_push( $product_size_width_list, $i );
		}
		$product_size_list                           = array();
		$product_size_list[ $width . 'x' . $height ] = $width . 'x' . $height;
		foreach ( $product_size_width_list as $k => $w ) {
			$w = intval( $w );
			if ( isset( $width ) && $width > 0 ) {
				$h = round( $height * $w / $width );
			} else {
				$h = $w;
			}
			$product_size_list[ $w . 'x' . $h ] = $w . 'x' . $h;
		}
		$product_size_list['Custom'] = 'custom';
		
		$param['funiter_adv_text'] = array(
			'base'        => 'funiter_adv_text',
			'name'        => esc_html__( 'Funiter: Advance Text', 'funiter' ),
			'icon'        => '',
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Creates 2 different text versions on mobile and desktop', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'textarea',
					'heading'     => esc_html__( 'Text', 'funiter' ),
					'param_name'  => 'none_mobile_text',
					'admin_label' => true
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_html__( 'Mobile Text', 'funiter' ),
					'param_name'  => 'mobile_text',
					'admin_label' => true
				)
			),
		);
		
		$param['funiter_banner_bg'] = array(
			'base'        => 'funiter_banner_bg',
			'name'        => esc_html__( 'Funiter: Banner 2', 'funiter' ),
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/banner.png' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display banner image as a background', 'funiter' ),
			'params'      => array(
				array(
					'param_name'  => 'img_id',
					'heading'     => esc_html__( 'Banner Image', 'funiter' ),
					'type'        => 'attach_image',
					'admin_label' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'title',
					'admin_label' => true
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_html__( 'Big Title', 'funiter' ),
					'param_name'  => 'bigtitle',
					'admin_label' => true,
				),
				array(
					'param_name' => 'link',
					'heading'    => esc_html__( 'Button', 'funiter' ),
					'type'       => 'vc_link',
				),
				array(
					'type'        => 'number',
					'heading'     => esc_html__( 'Width', 'funiter' ),
					'param_name'  => 'width',
					'description' => esc_html__( 'Width in pixel (px)', 'funiter' ),
					'std'         => 587 // 587 445
				),
				array(
					'type'        => 'number',
					'heading'     => esc_html__( 'Height', 'funiter' ),
					'param_name'  => 'height',
					'description' => esc_html__( 'Height in pixel (px)', 'funiter' ),
					'std'         => 334 // 334 254
				),
			)
		);
		
		$param['funiter_banner'] = array(
			'base'        => 'funiter_banner',
			'name'        => esc_html__( 'Funiter: Banner', 'funiter' ),
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/banner.png' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display Custom Banner', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'funiter' ),
					'value'       => array(
						'style-01' => array(
							'title'   => esc_html__( 'Style 01', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/banner/style-01.jpg' ),
						),
						'style-02' => array(
							'title'   => esc_html__( 'Style 02', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/banner/style-02.jpg' ),
						),
						'style-03' => array(
							'title'   => esc_html__( 'Style 03', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/banner/style-03.jpg' ),
						),
						'style-04' => array(
							'title'   => esc_html__( 'Style 04', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/banner/style-04.jpg' ),
						),
						'style-05' => array(
							'title'   => esc_html__( 'Style 05', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/banner/style-05.jpg' ),
						),
						'style-06' => array(
							'title'   => esc_html__( 'Style 06', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/banner/style-06.jpg' ),
						),
						'style-07' => array(
							'title'   => esc_html__( 'Style 07', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/banner/style-07.jpg' ),
						),
						'style-08' => array(
							'title'   => esc_html__( 'Style 08', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/banner/style-08.jpg' ),
						),
						'style-09' => array(
							'title'   => esc_html__( 'Style 09', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/banner/style-09.jpg' ),
						),
						'style-10' => array(
							'title'   => esc_html__( 'Style 10', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/banner/style-10.jpg' ),
						),
						'style-11' => array(
							'title'   => esc_html__( 'Style 11', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/banner/style-11.jpg' ),
						),
						'style-12' => array(
							'title'   => esc_html__( 'Style 12', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/banner/style-12.jpg' ),
						),
					),
					'default'     => 'style-01',
					'admin_label' => true,
					'param_name'  => 'style',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Label text', 'funiter' ),
					'param_name' => 'label',
					'dependency' => array(
						'element' => 'style',
						'value'   => array( 'style-08' ),
					),
				),
				array(
					'param_name'  => 'banner',
					'heading'     => esc_html__( 'Banner Image', 'funiter' ),
					'type'        => 'attach_image',
					'admin_label' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'title',
					'admin_label' => true,
					'dependency'  => array(
						'element' => 'style',
						'value'   => array( 'style-02', 'style-03', 'style-04', 'style-05', 'style-06' ),
					),
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_html__( 'Big Title', 'funiter' ),
					'param_name'  => 'bigtitle',
					'admin_label' => true,
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_html__( 'Description', 'funiter' ),
					'param_name'  => 'desc',
					'description' => esc_html__( 'Strong or em tag to hightlight text.', 'funiter' ),
					'dependency'  => array(
						'element' => 'style',
						'value'   => array(
							'style-01',
							'style-02',
							'style-03',
							'style-06',
							'style-07',
							'style-08',
							'style-09',
							'style-10',
							'style-11'
						),
					),
				),
				array(
					'type'        => 'number',
					'heading'     => esc_html__( 'Sale off', 'funiter' ),
					'param_name'  => 'sale',
					'description' => esc_html__( 'Percent(%)', 'funiter' ),
					'dependency'  => array(
						'element' => 'style',
						'value'   => array( 'style-06' ),
					),
				),
				array(
					'param_name' => 'link',
					'heading'    => esc_html__( 'Button', 'funiter' ),
					'type'       => 'vc_link',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Disable Lazy Load On Mobile', 'funiter' ),
					'param_name' => 'disable_lazy_mobile',
					'value'      => array(
						esc_html__( 'Yes', 'funiter' ) => 'yes',
						esc_html__( 'No', 'funiter' )  => 'no',
					),
					'std'        => 'no'
				),
			
			),
		);
		/* Map New blog */
		$param['funiter_blog']   = array(
			'base'        => 'funiter_blog',
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/blog.png' ),
			'name'        => esc_html__( 'Funiter: Blog', 'funiter' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display Post, Custom Post Type', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'title',
					'admin_label' => true,
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Blog List style', 'funiter' ),
					'param_name'  => 'productsliststyle',
					'value'       => array(
						esc_html__( 'Grid Bootstrap', 'funiter' ) => 'grid',
						esc_html__( 'Owl Carousel', 'funiter' )   => 'owl',
					),
					'description' => esc_html__( 'Select a style', 'funiter' ),
					'std'         => 'owl',
				),
				array(
					'type'        => 'loop',
					'heading'     => esc_html__( 'Option Query', 'funiter' ),
					'param_name'  => 'loop',
					'save_always' => true,
					'value'       => 'post_type:post|size:5|order_by:date',
					'settings'    => array(
						'size'     => array(
							'hidden' => false,
							'value'  => 6,
						),
						'order_by' => array( 'value' => 'date' ),
					),
					'description' => esc_html__( 'Create WordPress loop, to populate content from your site.', 'funiter' ),
				),
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'funiter' ),
					'value'       => array(
						'style-01' => array(
							'title'   => esc_html__( 'Style 01', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/blog/style-01.jpg' ),
						),
					),
					'default'     => 'style-01',
					'admin_label' => true,
					'param_name'  => 'style',
					'description' => esc_html__( 'Select a style', 'funiter' ),
				),
			),
		);
		$param['funiter_button'] = array(
			'base'        => 'funiter_button',
			'name'        => esc_html__( 'Funiter: Button', 'funiter' ),
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/accordion.png' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display Button', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'funiter' ),
					'value'       => array(
						'style-01' => array(
							'title'   => esc_html__( 'Style 01', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/button/style-01.jpg' ),
						),
						'style-02' => array(
							'title'   => esc_html__( 'Style 02', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/button/style-02.jpg' ),
						),
					),
					'default'     => 'style-01',
					'admin_label' => true,
					'param_name'  => 'style',
				),
				array(
					'type'       => 'vc_link',
					'heading'    => esc_html__( 'Link', 'funiter' ),
					'param_name' => 'link',
				),
			),
		);
		/* Map New Categories */
		$categories_array = array(
			esc_html__( 'All', 'funiter' ) => '',
		);
		$args             = array();
		$categories       = get_categories( $args );
		foreach ( $categories as $category ) {
			$categories_array[ $category->name ] = $category->slug;
		}
		$param['funiter_category']     = array(
			'base'        => 'funiter_category',
			'name'        => esc_html__( 'Funiter: Category', 'funiter' ),
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/cat.png' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display Custom Category Product', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Category List style', 'funiter' ),
					'param_name'  => 'productsliststyle',
					'value'       => array(
						esc_html__( 'Grid Bootstrap', 'funiter' ) => 'grid',
						esc_html__( 'Owl Carousel', 'funiter' )   => 'owl',
					),
					'description' => esc_html__( 'Select a style', 'funiter' ),
					'std'         => 'owl',
				),
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'funiter' ),
					'value'       => array(
						'style-01' => array(
							'title'   => esc_html__( 'Style 01', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/category/style-01.jpg' ),
						),
						'style-02' => array(
							'title'   => esc_html__( 'Style 02', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/category/style-02.jpg' ),
						),
					),
					'default'     => 'style-01',
					'admin_label' => true,
					'param_name'  => 'style',
					'description' => esc_html__( 'Select a style', 'funiter' ),
				),
				array(
					'type'        => 'taxonomy',
					'heading'     => esc_html__( 'Product Category', 'funiter' ),
					'param_name'  => 'taxonomy',
					'options'     => array(
						'multiple'   => true,
						'hide_empty' => true,
						'taxonomy'   => 'product_cat',
					),
					'placeholder' => esc_html__( 'Choose category', 'funiter' ),
					'description' => esc_html__( 'Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.', 'funiter' ),
				),
			),
		);
		$param['funiter_categorywrap'] = array(
			'base'                    => 'funiter_categorywrap',
			'icon'                    => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/single-lookbook.png' ),
			'name'                    => esc_html__( 'Funiter: Category Product Wrap', 'funiter' ),
			'category'                => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description'             => esc_html__( 'Display Category Product Wrap', 'funiter' ),
			'content_element'         => true,
			'show_settings_on_create' => true,
			'is_container'            => true,
			'js_view'                 => 'VcColumnView',
			'as_parent'               => array(
				'only' => 'vc_single_image, vc_custom_heading, funiter_person, vc_column_text, funiter_iconbox, funiter_category, funiter_socials, vc_row , funiter_newsletter, funiter_slide, funiter_wrapelement, funiter_custommenu',
			),
			'params'                  => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'funiter' ),
					'value'       => array(
						'style-01' => array(
							'title'   => esc_html__( 'Style 01', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/categorywrap/style-01.jpg' ),
						),
						'style-02' => array(
							'title'   => esc_html__( 'Style 02', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/categorywrap/style-02.jpg' ),
						),
					),
					'default'     => 'style-01',
					'admin_label' => true,
					'param_name'  => 'style',
					'description' => esc_html__( 'Select a style', 'funiter' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'title',
					'admin_label' => true,
					'dependency'  => array(
						'element' => 'style',
						'value'   => array( 'style-01' ),
					),
				),
				array(
					'type'        => 'taxonomy',
					'heading'     => esc_html__( 'Product Category', 'funiter' ),
					'param_name'  => 'taxonomy',
					'options'     => array(
						'multiple'   => true,
						'hide_empty' => true,
						'taxonomy'   => 'product_cat',
					),
					'placeholder' => esc_html__( 'Choose category', 'funiter' ),
					'description' => esc_html__( 'Note: selected first category will be hightlight.', 'funiter' ),
				),
			),
		);
		$param['funiter_countdown']    = array(
			'base'        => 'funiter_countdown',
			'name'        => esc_html__( 'Funiter: Countdown', 'funiter' ),
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/single-lookbook.png' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display Countdown', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'funiter' ),
					'value'       => array(
						'style-01' => array(
							'title'   => esc_html__( 'Style 01', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/countdown/style-01.jpg' ),
						),
						'style-02' => array(
							'title'   => esc_html__( 'Style 02', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/countdown/style-02.jpg' ),
						),
					),
					'default'     => 'style-01',
					'admin_label' => true,
					'param_name'  => 'style',
					'description' => esc_html__( 'Select a style', 'funiter' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'title',
					'description' => esc_html__( 'The title of shortcode', 'funiter' ),
					'admin_label' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Subittle text', 'funiter' ),
					'param_name'  => 'subtitle',
					'description' => esc_html__( 'The subtitle of shortcode', 'funiter' ),
				),
				array(
					'type'        => 'textarea_html',
					'heading'     => esc_html__( 'Text before date', 'funiter' ),
					'param_name'  => 'content',
					'description' => esc_html__( 'The before date of shortcode', 'funiter' ),
				),
				array(
					'type'       => 'datepicker',
					'heading'    => esc_html__( 'Date', 'funiter' ),
					'param_name' => 'date',
				),
				array(
					'type'        => 'vc_link',
					'heading'     => esc_html__( 'Link', 'funiter' ),
					'param_name'  => 'link',
					'description' => esc_html__( 'The Link', 'funiter' ),
				),
			),
		);
		/* Map New Custom menu */
		$all_menu = array();
		$menus    = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		if ( $menus && count( $menus ) > 0 ) {
			foreach ( $menus as $m ) {
				$all_menu[ $m->name ] = $m->slug;
			}
		}
		$param['funiter_custommenu'] = array(
			'base'        => 'funiter_custommenu',
			'name'        => esc_html__( 'Funiter: Custom Menu', 'funiter' ),
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/title-short-desc.png' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display Custom Menu', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'funiter' ),
					'value'       => array(
						'style-01' => array(
							'title'   => esc_html__( 'Style 01', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/custommenu/style-01.jpg' ),
						),
					),
					'default'     => 'style-01',
					'admin_label' => true,
					'param_name'  => 'style',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'title',
					'description' => esc_html__( 'What text use as a widget title. Leave blank to use default widget title.', 'funiter' ),
					'admin_label' => true,
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Menu', 'funiter' ),
					'value'       => $all_menu,
					'admin_label' => true,
					'param_name'  => 'nav_menu',
					'description' => esc_html__( 'Select menu to display.', 'funiter' ),
				),
			),
		);
		/* Map New heading */
		$param['funiter_heading'] = array(
			'base'        => 'funiter_heading',
			'name'        => esc_html__( 'Funiter: Custom Heading', 'funiter' ),
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/section-title.png' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display Custom Heading', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'funiter' ),
					'value'       => array(
						'style-01' => array(
							'title'   => esc_html__( 'Style 01', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/heading/style-01.jpg' ),
						),
						'style-02' => array(
							'title'   => esc_html__( 'Style 02', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/heading/style-02.jpg' ),
						),
					),
					'default'     => 'style-01',
					'admin_label' => true,
					'param_name'  => 'style',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Type Color', 'funiter' ),
					'param_name' => 'type_color',
					'value'      => array(
						esc_html__( 'Dark', 'funiter' )  => '',
						esc_html__( 'Light', 'funiter' ) => 'light',
					),
					'std'        => '',
					'dependency' => array(
						'element' => 'style',
						'value'   => array( 'style-01' ),
					),
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'title',
					'admin_label' => true,
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Text align', 'funiter' ),
					'param_name' => 'position',
					'value'      => array(
						esc_html__( 'Text Left', 'funiter' )   => '',
						esc_html__( 'Text Center', 'funiter' ) => 'text-center',
					),
					'std'        => '',
					'dependency' => array(
						'element' => 'style',
						'value'   => array( 'style-02' ),
					),
				),
			),
		);
		/* Map New Icon box */
		$param['funiter_iconbox']   = array(
			'base'        => 'funiter_iconbox',
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/iconbox.png' ),
			'name'        => esc_html__( 'Funiter: Icon Box', 'funiter' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display Icon Box', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'funiter' ),
					'value'       => array(
						'style-01' => array(
							'title'   => esc_html__( 'Style 01', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/iconbox/style-01.jpg' ),
						),
						'style-02' => array(
							'title'   => esc_html__( 'Style 02', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/iconbox/style-02.jpg' ),
						),
						'style-03' => array(
							'title'   => esc_html__( 'Style 03', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/iconbox/style-03.jpg' ),
						),
						'style-04' => array(
							'title'   => esc_html__( 'Style 04', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/iconbox/style-04.jpg' ),
						),
						'style-05' => array(
							'title'   => esc_html__( 'Style 05', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/iconbox/style-05.jpg' ),
						),
					),
					'default'     => 'style-01',
					'admin_label' => true,
					'param_name'  => 'style',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'title',
					'admin_label' => true,
					'dependency'  => array(
						'element' => 'style',
						'value'   => array( 'style-01', 'style-02', 'style-04', 'style-05' ),
					),
				),
				array(
					'param_name' => 'text_content',
					'heading'    => esc_html__( 'Content', 'funiter' ),
					'type'       => 'textarea',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Icon library', 'funiter' ),
					'value'       => array(
						esc_html__( 'Font Awesome', 'funiter' )  => 'fontawesome',
						esc_html__( 'Open Iconic', 'funiter' )   => 'openiconic',
						esc_html__( 'Typicons', 'funiter' )      => 'typicons',
						esc_html__( 'Entypo', 'funiter' )        => 'entypo',
						esc_html__( 'Linecons', 'funiter' )      => 'linecons',
						esc_html__( 'Mono Social', 'funiter' )   => 'monosocial',
						esc_html__( 'Material', 'funiter' )      => 'material',
						esc_html__( 'Funiter Fonts', 'funiter' ) => 'funitercustomfonts',
					),
					'admin_label' => true,
					'param_name'  => 'type',
					'description' => esc_html__( 'Select icon library.', 'funiter' ),
				),
				array(
					'param_name'  => 'icon_funitercustomfonts',
					'heading'     => esc_html__( 'Icon', 'funiter' ),
					'description' => esc_html__( 'Select icon from library.', 'funiter' ),
					'type'        => 'iconpicker',
					'settings'    => array(
						'emptyIcon' => false,
						'type'      => 'funitercustomfonts',
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'funitercustomfonts',
					),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'funiter' ),
					'param_name'  => 'icon_fontawesome',
					'value'       => 'fa fa-adjust',
					'settings'    => array(
						'emptyIcon'    => false,
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'fontawesome',
					),
					'description' => esc_html__( 'Select icon from library.', 'funiter' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'funiter' ),
					'param_name'  => 'icon_openiconic',
					'value'       => 'vc-oi vc-oi-dial',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'openiconic',
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'openiconic',
					),
					'description' => esc_html__( 'Select icon from library.', 'funiter' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'funiter' ),
					'param_name'  => 'icon_typicons',
					'value'       => 'typcn typcn-adjust-brightness',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'typicons',
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'typicons',
					),
					'description' => esc_html__( 'Select icon from library.', 'funiter' ),
				),
				array(
					'type'       => 'iconpicker',
					'heading'    => esc_html__( 'Icon', 'funiter' ),
					'param_name' => 'icon_entypo',
					'value'      => 'entypo-icon entypo-icon-note',
					'settings'   => array(
						'emptyIcon'    => false,
						'type'         => 'entypo',
						'iconsPerPage' => 100,
					),
					'dependency' => array(
						'element' => 'type',
						'value'   => 'entypo',
					),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'funiter' ),
					'param_name'  => 'icon_linecons',
					'value'       => 'vc_li vc_li-heart',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'linecons',
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'linecons',
					),
					'description' => esc_html__( 'Select icon from library.', 'funiter' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'funiter' ),
					'param_name'  => 'icon_monosocial',
					'value'       => 'vc-mono vc-mono-fivehundredpx',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'monosocial',
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'monosocial',
					),
					'description' => esc_html__( 'Select icon from library.', 'funiter' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'funiter' ),
					'param_name'  => 'icon_material',
					'value'       => 'vc-material vc-material-cake',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'material',
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'material',
					),
					'description' => esc_html__( 'Select icon from library.', 'funiter' ),
				),
			),
		);
		$param['funiter_instagram'] = array(
			'base'        => 'funiter_instagram',
			'name'        => esc_html__( 'Funiter: Instagram', 'funiter' ),
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/instagram.png' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display Instagram', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'funiter' ),
					'value'       => array(
						'style-01' => array(
							'title'   => esc_html__( 'style 01', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/instagram/style-01.jpg' ),
						),
						'style-02' => array(
							'title'   => esc_html__( 'Style 02', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/instagram/style-02.jpg' ),
						),
					),
					'default'     => 'style-01',
					'admin_label' => true,
					'param_name'  => 'style',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'title',
					'description' => esc_html__( 'The title of shortcode', 'funiter' ),
					'admin_label' => true,
				),
				array(
					'type'       => 'vc_link',
					'heading'    => esc_html__( 'Link', 'funiter' ),
					'param_name' => 'link',
					'dependency' => array(
						'element' => 'style',
						'value'   => array( 'style-01' ),
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Instagram style', 'funiter' ),
					'param_name' => 'productsliststyle',
					'value'      => array(
						esc_html__( 'Grid Bootstrap', 'funiter' ) => 'grid',
						esc_html__( 'Owl Carousel', 'funiter' )   => 'owl',
					),
					'std'        => 'grid',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Image Resolution', 'funiter' ),
					'param_name' => 'image_resolution',
					'value'      => array(
						esc_html__( 'Thumbnail', 'funiter' )           => 'thumbnail',
						esc_html__( 'Low Resolution', 'funiter' )      => 'low_resolution',
						esc_html__( 'Standard Resolution', 'funiter' ) => 'standard_resolution',
					),
					'std'        => 'thumbnail',
					'dependency' => array(
						'element' => 'image_source',
						'value'   => array( 'instagram' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'ID Instagram', 'funiter' ),
					'param_name'  => 'id_instagram',
					'admin_label' => true,
					'dependency'  => array(
						'element' => 'image_source',
						'value'   => array( 'instagram' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Token Instagram', 'funiter' ),
					'param_name'  => 'token',
					'dependency'  => array(
						'element' => 'image_source',
						'value'   => array( 'instagram' ),
					),
					'description' => wp_kses( sprintf( '<a href="%s" target="_blank">' . esc_html__( 'Get Token Instagram Here!', 'funiter' ) . '</a>', 'http://instagram.pixelunion.net' ), array(
						'a' => array(
							'href'   => array(),
							'target' => array()
						)
					) ),
				),
				array(
					'type'        => 'number',
					'heading'     => esc_html__( 'Items Instagram', 'funiter' ),
					'param_name'  => 'items_limit',
					'description' => esc_html__( 'the number items show', 'funiter' ),
					'std'         => '8',
					'dependency'  => array(
						'element' => 'image_source',
						'value'   => array( 'instagram' ),
					),
				),
			),
		);
		$param['funiter_map']       = array(
			'base'        => 'funiter_map',
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/gmap.png' ),
			'name'        => esc_html__( 'Funiter: Google Map', 'funiter' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display Google Map', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'title',
					'admin_label' => true,
					'description' => esc_html__( 'title.', 'funiter' ),
					'std'         => 'Funiter',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Phone', 'funiter' ),
					'param_name'  => 'phone',
					'description' => esc_html__( 'phone.', 'funiter' ),
					'std'         => '088-465 9965 02',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Email', 'funiter' ),
					'param_name'  => 'email',
					'description' => esc_html__( 'email.', 'funiter' ),
					'std'         => 'famithemes@gmail.com',
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'Map Height', 'funiter' ),
					'param_name' => 'map_height',
					'std'        => '400',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Maps type', 'funiter' ),
					'param_name' => 'map_type',
					'value'      => array(
						esc_html__( 'ROADMAP', 'funiter' )   => 'ROADMAP',
						esc_html__( 'SATELLITE', 'funiter' ) => 'SATELLITE',
						esc_html__( 'HYBRID', 'funiter' )    => 'HYBRID',
						esc_html__( 'TERRAIN', 'funiter' )   => 'TERRAIN',
					),
					'std'        => 'ROADMAP',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Address', 'funiter' ),
					'param_name'  => 'address',
					'admin_label' => true,
					'description' => esc_html__( 'address.', 'funiter' ),
					'std'         => 'Z115 TP. Thai Nguyen',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Longitude', 'funiter' ),
					'param_name'  => 'longitude',
					'admin_label' => true,
					'description' => esc_html__( 'longitude.', 'funiter' ),
					'std'         => '105.800286',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Latitude', 'funiter' ),
					'param_name'  => 'latitude',
					'admin_label' => true,
					'description' => esc_html__( 'latitude.', 'funiter' ),
					'std'         => '21.587001',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Zoom', 'funiter' ),
					'param_name'  => 'zoom',
					'admin_label' => true,
					'description' => esc_html__( 'zoom.', 'funiter' ),
					'std'         => '14',
				),
			),
		);
		/*Section Team*/
		$param['funiter_member']     = array(
			'base'        => 'funiter_member',
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/product.png' ),
			'name'        => esc_html__( 'Funiter: Member', 'funiter' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display member info', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'funiter' ),
					'value'       => array(
						'style-01' => array(
							'title'   => esc_html__( 'Style 01', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/member/style-01.jpg' ),
						),
					),
					'default'     => 'style-01',
					'admin_label' => true,
					'param_name'  => 'style',
				),
				array(
					'param_name'  => 'avatar_member',
					'heading'     => esc_html__( 'Avatar Member', 'funiter' ),
					'type'        => 'attach_image',
					'admin_label' => true,
				),
				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Member Name", "funiter" ),
					"param_name"  => "name",
					"description" => esc_html__( "Add name member.", "funiter" ),
				),
				array(
					"type"       => "textfield",
					"heading"    => esc_html__( "Member Postion", "funiter" ),
					"param_name" => "position",
				),
				array(
					"type"       => "textarea",
					"heading"    => esc_html__( "Member Descriptions", "funiter" ),
					"param_name" => "desc",
				),
			),
		);
		$param['funiter_newsletter'] = array(
			'base'        => 'funiter_newsletter',
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/newsletter.png' ),
			'name'        => esc_html__( 'Funiter: Newsletter', 'funiter' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display Newsletter', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'funiter' ),
					'value'       => array(
						'style-01' => array(
							'title'   => esc_html__( 'Style 01', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/newsletter/style-01.jpg' ),
						),
						'style-02' => array(
							'title'   => esc_html__( 'Style 02', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/newsletter/style-02.jpg' ),
						),
					),
					'default'     => 'style-01',
					'admin_label' => true,
					'param_name'  => 'style',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'title',
					'admin_label' => true,
				),
				array(
					'param_name' => 'desc',
					'heading'    => esc_html__( 'Descriptions', 'funiter' ),
					'type'       => 'textarea',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Placeholder text', 'funiter' ),
					'param_name' => 'placeholder_text',
					'std'        => esc_html__( 'Enter your email address', 'funiter' ),
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Button text', 'funiter' ),
					'std'        => esc_html__( 'Subscribe', 'funiter' ),
					'param_name' => 'button_text',
				),
			),
		);
		/* GET PINMAP */
		$args_pm        = array(
			'post_type'      => 'funiter_mapper',
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
		);
		$pinmap_loop    = new wp_query( $args_pm );
		$pinmap_options = array();
		while ( $pinmap_loop->have_posts() ) {
			$pinmap_loop->the_post();
			$attachment_id                  = get_post_meta( get_the_ID(), 'funiter_mapper_image', true );
			$pinmap_options[ get_the_ID() ] = array(
				'title'   => get_the_title(),
				'preview' => wp_get_attachment_image_url( $attachment_id, 'medium' ),
			);
		}
		$param['funiter_pinmapper'] = array(
			'base'        => 'funiter_pinmapper',
			'name'        => esc_html__( 'Funiter: Pin Map', 'funiter' ),
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/pinmapper.png' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display Pin Map', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Pinmaper style', 'funiter' ),
					'value'       => $pinmap_options,
					'admin_label' => true,
					'param_name'  => 'pinmaper_style',
					'description' => esc_html__( 'Select a style', 'funiter' ),
				),
			),
		);
		$param['funiter_products']  = array(
			'base'        => 'funiter_products',
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/product.png' ),
			'name'        => esc_html__( 'Funiter: Products', 'funiter' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display Products', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'title',
					'admin_label' => true,
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Product Attribute', 'funiter' ),
					'param_name'  => 'product_attribute',
					'value'       => $attributes,
					'description' => esc_html__( 'Select a Attribute for product', 'funiter' ),
					'dependency'  => array( 'element' => 'product_style', 'value' => array( '1', '2' ) ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Product Star Rating', 'funiter' ),
					'param_name'  => 'rating',
					'value'       => array(
						esc_html__( 'Yes', 'funiter' ) => '',
						esc_html__( 'No', 'funiter' )  => 'nostar',
					),
					'description' => esc_html__( 'Show Rating', 'funiter' ),
					'std'         => '',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Product List style', 'funiter' ),
					'param_name'  => 'productsliststyle',
					'value'       => array(
						esc_html__( 'Grid Bootstrap', 'funiter' ) => 'grid',
						esc_html__( 'Owl Carousel', 'funiter' )   => 'owl',
					),
					'description' => esc_html__( 'Select a style', 'funiter' ),
					'std'         => 'grid',
				),
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Product style', 'funiter' ),
					'value'       => $product_options,
					'default'     => '1',
					'admin_label' => true,
					'param_name'  => 'product_style',
					'description' => esc_html__( 'Select a style', 'funiter' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Image size', 'funiter' ),
					'param_name'  => 'product_image_size',
					'value'       => $product_size_list,
					'description' => esc_html__( 'Select a size for product', 'funiter' ),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'Width', 'funiter' ),
					'param_name' => 'product_custom_thumb_width',
					'value'      => $width,
					'suffix'     => esc_html__( 'px', 'funiter' ),
					'dependency' => array( 'element' => 'product_image_size', 'value' => array( 'custom' ) ),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'Height', 'funiter' ),
					'param_name' => 'product_custom_thumb_height',
					'value'      => $height,
					'suffix'     => esc_html__( 'px', 'funiter' ),
					'dependency' => array( 'element' => 'product_image_size', 'value' => array( 'custom' ) ),
				),
				/* Products */
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Enable Load More', 'funiter' ),
					'param_name' => 'loadmore',
					'value'      => array(
						esc_html__( 'Enable', 'funiter' )  => 'enable',
						esc_html__( 'Disable', 'funiter' ) => 'disable',
					),
					'std'        => 'disable',
					'group'      => esc_html__( 'Products options', 'funiter' ),
				),
				array(
					'type'        => 'taxonomy',
					'heading'     => esc_html__( 'Product Category', 'funiter' ),
					'param_name'  => 'taxonomy',
					'options'     => array(
						'multiple'   => true,
						'hide_empty' => true,
						'taxonomy'   => 'product_cat',
					),
					'placeholder' => esc_html__( 'Choose category', 'funiter' ),
					'description' => esc_html__( 'Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.', 'funiter' ),
					'group'       => esc_html__( 'Products options', 'funiter' ),
					'dependency'  => array(
						'element' => 'target',
						'value'   => array(
							'top-rated',
							'recent-product',
							'product-category',
							'featured_products',
							'on_sale',
							'on_new'
						)
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Target', 'funiter' ),
					'param_name'  => 'target',
					'value'       => array(
						esc_html__( 'Best Selling Products', 'funiter' ) => 'best-selling',
						esc_html__( 'Top Rated Products', 'funiter' )    => 'top-rated',
						esc_html__( 'Recent Products', 'funiter' )       => 'recent-product',
						esc_html__( 'Product Category', 'funiter' )      => 'product-category',
						esc_html__( 'Products', 'funiter' )              => 'products',
						esc_html__( 'Featured Products', 'funiter' )     => 'featured_products',
						esc_html__( 'On Sale', 'funiter' )               => 'on_sale',
						esc_html__( 'On New', 'funiter' )                => 'on_new',
					),
					'description' => esc_html__( 'Choose the target to filter products', 'funiter' ),
					'std'         => 'recent-product',
					'group'       => esc_html__( 'Products options', 'funiter' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Order by', 'funiter' ),
					'param_name'  => 'orderby',
					'value'       => array(
						esc_html__( 'Date', 'funiter' )          => 'date',
						esc_html__( 'ID', 'funiter' )            => 'ID',
						esc_html__( 'Author', 'funiter' )        => 'author',
						esc_html__( 'Title', 'funiter' )         => 'title',
						esc_html__( 'Modified', 'funiter' )      => 'modified',
						esc_html__( 'Random', 'funiter' )        => 'rand',
						esc_html__( 'Comment count', 'funiter' ) => 'comment_count',
						esc_html__( 'Menu order', 'funiter' )    => 'menu_order',
						esc_html__( 'Sale price', 'funiter' )    => '_sale_price',
					),
					'std'         => 'date',
					'description' => esc_html__( 'Select how to sort.', 'funiter' ),
					'dependency'  => array(
						'element' => 'target',
						'value'   => array(
							'top-rated',
							'recent-product',
							'product-category',
							'featured_products',
							'on_sale',
							'on_new',
							'product_attribute'
						)
					),
					'group'       => esc_html__( 'Products options', 'funiter' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Order', 'funiter' ),
					'param_name'  => 'order',
					'value'       => array(
						esc_html__( 'ASC', 'funiter' )  => 'ASC',
						esc_html__( 'DESC', 'funiter' ) => 'DESC',
					),
					'std'         => 'DESC',
					'description' => esc_html__( 'Designates the ascending or descending order.', 'funiter' ),
					'dependency'  => array(
						'element' => 'target',
						'value'   => array(
							'top-rated',
							'recent-product',
							'product-category',
							'featured_products',
							'on_sale',
							'on_new',
							'product_attribute'
						)
					),
					'group'       => esc_html__( 'Products options', 'funiter' ),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'Product per page', 'funiter' ),
					'param_name' => 'per_page',
					'value'      => 6,
					'dependency' => array(
						'element' => 'target',
						'value'   => array(
							'best-selling',
							'top-rated',
							'recent-product',
							'product-category',
							'featured_products',
							'product_attribute',
							'on_sale',
							'on_new'
						)
					),
					'group'      => esc_html__( 'Products options', 'funiter' ),
				),
				array(
					'type'        => 'autocomplete',
					'heading'     => esc_html__( 'Products', 'funiter' ),
					'param_name'  => 'ids',
					'settings'    => array(
						'multiple'      => true,
						'sortable'      => true,
						'unique_values' => true,
					),
					'save_always' => true,
					'description' => esc_html__( 'Enter List of Products', 'funiter' ),
					'dependency'  => array( 'element' => 'target', 'value' => array( 'products' ) ),
					'group'       => esc_html__( 'Products options', 'funiter' ),
				),
			),
		);
		$param['funiter_slide']     = array(
			'base'                    => 'funiter_slide',
			'icon'                    => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/slide.png' ),
			'name'                    => esc_html__( 'Funiter: Slide', 'funiter' ),
			'category'                => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description'             => esc_html__( 'Display Slide', 'funiter' ),
			'as_parent'               => array(
				'only' => 'vc_single_image, vc_custom_heading, funiter_person, vc_column_text, funiter_iconbox, funiter_socials, funiter_testimonial, funiter_banner, funiter_wrapelement, vc_row',
			),
			'content_element'         => true,
			'show_settings_on_create' => true,
			'js_view'                 => 'VcColumnView',
			'params'                  => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'slider_title',
					'admin_label' => true,
				),
				array(
					'type'       => 'dropdown',
					'value'      => array(
						esc_html__( 'No', 'funiter' )  => '',
						esc_html__( 'Yes', 'funiter' ) => 'yes',
					),
					'std'        => '',
					'heading'    => esc_html__( 'Margin Responsive', 'funiter' ),
					'param_name' => 'margin_responsive',
				),
			),
		);
		$socials                    = array();
		$all_socials                = Funiter_Functions::funiter_get_option( 'user_all_social' );
		if ( ! empty( $all_socials ) ) {
			foreach ( $all_socials as $key => $social ) {
				$socials[ $social['title_social'] ] = $key;
			}
		}
		$param['funiter_socials']     = array(
			'base'        => 'funiter_socials',
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/socials.png' ),
			'name'        => esc_html__( 'Funiter: Socials', 'funiter' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display Socials', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'funiter' ),
					'value'       => array(
						'style-01' => array(
							'title'   => esc_html__( 'Style 01', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/socials/style-01.jpg' ),
						),
					),
					'default'     => 'style-01',
					'admin_label' => true,
					'param_name'  => 'style',
					'description' => esc_html__( 'Select a style', 'funiter' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'title',
					'admin_label' => true,
				),
				array(
					'type'       => 'checkbox',
					'heading'    => esc_html__( 'List Social', 'funiter' ),
					'param_name' => 'socials',
					'value'      => $socials,
				),
			),
		);
		$param['funiter_tabs']        = array(
			'base'                    => 'funiter_tabs',
			'icon'                    => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/tabs.png' ),
			'name'                    => esc_html__( 'Funiter: Tabs', 'funiter' ),
			'category'                => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description'             => esc_html__( 'Display Tabs', 'funiter' ),
			'is_container'            => true,
			'show_settings_on_create' => false,
			'as_parent'               => array(
				'only' => 'vc_tta_section',
			),
			'params'                  => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'funiter' ),
					'value'       => array(
						'style-01' => array(
							'title'   => esc_html__( 'Style 01', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/tabs/style-01.jpg' ),
						),
						'style-02' => array(
							'title'   => esc_html__( 'Style 02', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/tabs/style-02.jpg' ),
						),
						'style-03' => array(
							'title'   => esc_html__( 'Style 03', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/tabs/style-03.jpg' ),
						),
					),
					'default'     => 'style-01',
					'admin_label' => true,
					'param_name'  => 'style',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'tab_title',
					'description' => esc_html__( 'The title of shortcode', 'funiter' ),
					'admin_label' => true,
				),
				vc_map_add_css_animation(),
				array(
					'param_name' => 'ajax_check',
					'heading'    => esc_html__( 'Using Ajax Tabs', 'funiter' ),
					'type'       => 'dropdown',
					'value'      => array(
						esc_html__( 'Yes', 'funiter' ) => '1',
						esc_html__( 'No', 'funiter' )  => '0',
					),
					'std'        => '0',
				),
				array(
					'param_name' => 'using_loop',
					'heading'    => esc_html__( 'Using Loop', 'funiter' ),
					'type'       => 'dropdown',
					'value'      => array(
						esc_html__( 'Yes', 'funiter' ) => '1',
						esc_html__( 'No', 'funiter' )  => '0',
					),
					'std'        => '1',
					'dependency' => array(
						'element' => 'style',
						'value'   => array( 'style4' ),
					),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'Active Section', 'funiter' ),
					'param_name' => 'active_section',
					'std'        => 0,
				),
			),
			'js_view'                 => 'VcBackendTtaTabsView',
			'custom_markup'           => '
                    <div class="vc_tta-container" data-vc-action="collapse">
                        <div class="vc_general vc_tta vc_tta-tabs vc_tta-color-backend-tabs-white vc_tta-style-flat vc_tta-shape-rounded vc_tta-spacing-1 vc_tta-tabs-position-top vc_tta-controls-align-left">
                            <div class="vc_tta-tabs-container">'
			                             . '<ul class="vc_tta-tabs-list">'
			                             . '<li class="vc_tta-tab" data-vc-tab data-vc-target-model-id="{{ model_id }}" data-element_type="vc_tta_section"><a href="javascript:;" data-vc-tabs data-vc-container=".vc_tta" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-target-model-id="{{ model_id }}"><span class="vc_tta-title-text">{{ section_title }}</span></a></li>'
			                             . '</ul>
                            </div>
                            <div class="vc_tta-panels vc_clearfix {{container-class}}">
                              {{ content }}
                            </div>
                        </div>
                    </div>',
			'default_content'         => '
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Tab', 'funiter' ), 1 ) . '"][/vc_tta_section]
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Tab', 'funiter' ), 2 ) . '"][/vc_tta_section]
                    ',
			'admin_enqueue_js'        => array(
				vc_asset_url( 'lib/vc_tabs/vc-tabs.min.js' ),
			),
		);
		$param['funiter_testimonial'] = array(
			'base'        => 'funiter_testimonial',
			'name'        => esc_html__( 'Funiter: Testimonial', 'funiter' ),
			'icon'        => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/testimonial.png' ),
			'category'    => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description' => esc_html__( 'Display Testimonial', 'funiter' ),
			'params'      => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'funiter' ),
					'value'       => array(
						'style-01' => array(
							'title'   => esc_html__( 'Style 01', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/testimonial/style-01.jpg' ),
						),
						'style-02' => array(
							'title'   => esc_html__( 'Style 02', 'funiter' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/testimonial/style-02.jpg' ),
						),
					),
					'default'     => 'style-01',
					'admin_label' => true,
					'param_name'  => 'style',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Type Color', 'funiter' ),
					'param_name' => 'type_color',
					'value'      => array(
						esc_html__( 'Dark', 'funiter' )  => '',
						esc_html__( 'Light', 'funiter' ) => 'light',
					),
					'std'        => '',
					'dependency' => array(
						'element' => 'style',
						'value'   => array( 'style-02' ),
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Star Rating', 'funiter' ),
					'param_name' => 'rating',
					'value'      => array(
						esc_html__( '1 Star', 'funiter' )  => 'rating-1',
						esc_html__( '2 Stars', 'funiter' ) => 'rating-2',
						esc_html__( '3 Stars', 'funiter' ) => 'rating-3',
						esc_html__( '4 Stars', 'funiter' ) => 'rating-4',
						esc_html__( '5 Stars', 'funiter' ) => 'rating-5',
					),
					'std'        => 'rating-5',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Title', 'funiter' ),
					'param_name' => 'title',
				),
				array(
					'type'       => 'textarea',
					'heading'    => esc_html__( 'Content', 'funiter' ),
					'param_name' => 'desc',
				),
				array(
					'type'       => 'attach_image',
					'heading'    => esc_html__( 'Image', 'funiter' ),
					'param_name' => 'image',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Name', 'funiter' ),
					'param_name'  => 'name',
					'description' => esc_html__( 'Name', 'funiter' ),
					'admin_label' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Position', 'funiter' ),
					'param_name'  => 'position',
					'description' => esc_html__( 'Position', 'funiter' ),
					'admin_label' => true,
				),
				array(
					'type'       => 'vc_link',
					'heading'    => esc_html__( 'Link', 'funiter' ),
					'param_name' => 'link',
				),
			),
		);
		$param['funiter_wrapelement'] = array(
			'base'                    => 'funiter_wrapelement',
			'icon'                    => get_theme_file_uri( 'assets/images/vc-shortcodes-icons/container.png' ),
			'name'                    => esc_html__( 'Funiter: Wrap Element', 'funiter' ),
			'category'                => esc_html__( 'Funiter Shortcode', 'funiter' ),
			'description'             => esc_html__( 'Display Wrap Element', 'funiter' ),
			'content_element'         => true,
			'show_settings_on_create' => true,
			'is_container'            => true,
			'js_view'                 => 'VcColumnView',
			'as_parent'               => array(
				'only' => 'vc_single_image, vc_custom_heading, funiter_person, vc_column_text, funiter_iconbox, funiter_category, funiter_socials, vc_row , funiter_newsletter, funiter_custommenu',
			),
			'params'                  => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'funiter' ),
					'param_name'  => 'element_title',
					'admin_label' => true,
				),
			),
		);
		
		
		return $param;
	}
}