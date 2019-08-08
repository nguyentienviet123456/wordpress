<?php
/**
 * @version    1.0
 * @package    Funiter_Toolkit
 * @author     Funiter
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
/**
 * Class Toolkit Post Type
 *
 * @since    1.0
 */
if ( ! class_exists( 'Funiter_Post_Type' ) ) {
	class Funiter_Post_Type {
		public function __construct() {
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'init', array( $this, 'taxonomy_product_brand' ), 9999 );
		}
		
		public static function init() {
			/* FAQs */
			$args = array(
				'labels'              => array(
					'name'               => __( 'FAQs', 'funiter-toolkit' ),
					'singular_name'      => __( 'FAQs item', 'funiter-toolkit' ),
					'add_new'            => __( 'Add new', 'funiter-toolkit' ),
					'add_new_item'       => __( 'Add new FAQs item', 'funiter-toolkit' ),
					'edit_item'          => __( 'Edit FAQs item', 'funiter-toolkit' ),
					'new_item'           => __( 'New FAQs item', 'funiter-toolkit' ),
					'view_item'          => __( 'View FAQs item', 'funiter-toolkit' ),
					'search_items'       => __( 'Search FAQs items', 'funiter-toolkit' ),
					'not_found'          => __( 'No FAQs items found', 'funiter-toolkit' ),
					'not_found_in_trash' => __( 'No FAQs items found in trash', 'funiter-toolkit' ),
					'parent_item_colon'  => __( 'Parent FAQs item:', 'funiter-toolkit' ),
					'menu_name'          => __( 'FAQs', 'funiter-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'FAQs.', 'funiter-toolkit' ),
				'supports'            => array( 'title', 'editor' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'funiter_menu',
				'menu_position'       => 0,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-welcome-widgets-menus',
			);
			register_post_type( 'faqs', $args );
			/* Mega menu */
			$args = array(
				'labels'              => array(
					'name'               => __( 'Mega Builder', 'funiter-toolkit' ),
					'singular_name'      => __( 'Mega menu item', 'funiter-toolkit' ),
					'add_new'            => __( 'Add new', 'funiter-toolkit' ),
					'add_new_item'       => __( 'Add new menu item', 'funiter-toolkit' ),
					'edit_item'          => __( 'Edit menu item', 'funiter-toolkit' ),
					'new_item'           => __( 'New menu item', 'funiter-toolkit' ),
					'view_item'          => __( 'View menu item', 'funiter-toolkit' ),
					'search_items'       => __( 'Search menu items', 'funiter-toolkit' ),
					'not_found'          => __( 'No menu items found', 'funiter-toolkit' ),
					'not_found_in_trash' => __( 'No menu items found in trash', 'funiter-toolkit' ),
					'parent_item_colon'  => __( 'Parent menu item:', 'funiter-toolkit' ),
					'menu_name'          => __( 'Menu Builder', 'funiter-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'Mega Menus.', 'funiter-toolkit' ),
				'supports'            => array( 'title', 'editor' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'funiter_menu',
				'menu_position'       => 3,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-welcome-widgets-menus',
			);
			register_post_type( 'megamenu', $args );
			/* Footer */
			$args = array(
				'labels'              => array(
					'name'               => __( 'Footers', 'funiter-toolkit' ),
					'singular_name'      => __( 'Footers', 'funiter-toolkit' ),
					'add_new'            => __( 'Add New', 'funiter-toolkit' ),
					'add_new_item'       => __( 'Add new footer', 'funiter-toolkit' ),
					'edit_item'          => __( 'Edit footer', 'funiter-toolkit' ),
					'new_item'           => __( 'New footer', 'funiter-toolkit' ),
					'view_item'          => __( 'View footer', 'funiter-toolkit' ),
					'search_items'       => __( 'Search template footer', 'funiter-toolkit' ),
					'not_found'          => __( 'No template items found', 'funiter-toolkit' ),
					'not_found_in_trash' => __( 'No template items found in trash', 'funiter-toolkit' ),
					'parent_item_colon'  => __( 'Parent template item:', 'funiter-toolkit' ),
					'menu_name'          => __( 'Footer Builder', 'funiter-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'To Build Template Footer.', 'funiter-toolkit' ),
				'supports'            => array( 'title', 'editor' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'funiter_menu',
				'menu_position'       => 4,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
			);
			register_post_type( 'footer', $args );
			
		}
		
		public static function taxonomy_product_brand() {
			
			if ( ! class_exists( 'WooCommerce' ) ) {
				return;
			}
			
			$labels = array(
				'name'                       => _x( 'Brands', 'Taxonomy General Name', 'funiter' ),
				'singular_name'              => _x( 'Brand', 'Taxonomy Singular Name', 'funiter' ),
				'menu_name'                  => __( 'Brands', 'funiter' ),
				'all_items'                  => __( 'All brands', 'funiter' ),
				'parent_item'                => __( 'Parent brand', 'funiter' ),
				'parent_item_colon'          => __( 'Parent brands:', 'funiter' ),
				'new_item_name'              => __( 'New Brand Name', 'funiter' ),
				'add_new_item'               => __( 'Add Brand', 'funiter' ),
				'edit_item'                  => __( 'Edit Brand', 'funiter' ),
				'update_item'                => __( 'Update Brand', 'funiter' ),
				'view_item'                  => __( 'View Brand', 'funiter' ),
				'separate_items_with_commas' => __( 'Separate brand with commas', 'funiter' ),
				'add_or_remove_items'        => __( 'Add or remove brand', 'funiter' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'funiter' ),
				'popular_items'              => __( 'Popular Brands', 'funiter' ),
				'search_items'               => __( 'Search Brands', 'funiter' ),
				'not_found'                  => __( 'Not Found', 'funiter' ),
				'no_terms'                   => __( 'No Brands', 'funiter' ),
				'items_list'                 => __( 'Brands list', 'funiter' ),
				'items_list_navigation'      => __( 'Brands list navigation', 'funiter' ),
			);
			$args   = array(
				'labels'            => $labels,
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud'     => true,
			);
			register_taxonomy( 'product_brand', array( 'product' ), $args );
			
		}
	}
	
	new Funiter_Post_Type();
}
