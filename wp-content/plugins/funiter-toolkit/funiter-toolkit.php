<?php
/**
 * Plugin Name: Funiter Toolkit
 * Plugin URI: https://themeforest.net/user/fami_themes
 * Description: The Funiter Toolkit For WordPress Theme WooCommerce Shop.
 * Author: Famithemes
 * Author URI: https://themeforest.net/user/fami_themes
 * Version: 1.1.4
 * Text Domain: funiter-toolkit
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Funiter_Toolkit' ) ) {
	class  Funiter_Toolkit {
		/**
		 * @var Funiter_Toolkit The one true Funiter_Toolkit
		 * @since 1.0
		 */
		private static $instance;
		
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Funiter_Toolkit ) ) {
				self::$instance = new Funiter_Toolkit;
				self::$instance->setup_constants();
				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
				self::$instance->includes();
				add_action( 'after_setup_theme', array( self::$instance, 'after_setup_theme' ) );
				add_action( 'admin_enqueue_scripts', array( self::$instance, 'admin_scripts' ) );
			}
			
			return self::$instance;
		}
		
		public function after_setup_theme() {
			require_once FUNITER_TOOLKIT_PATH . 'includes/admin/import/import.php';
			/* MAILCHIP */
			require_once FUNITER_TOOLKIT_PATH . 'includes/admin/mailchimp/MCAPI.class.php';
			require_once FUNITER_TOOLKIT_PATH . 'includes/admin/mailchimp/mailchimp-settings.php';
			require_once FUNITER_TOOLKIT_PATH . 'includes/admin/mailchimp/mailchimp.php';
			require_once FUNITER_TOOLKIT_PATH . 'includes/frontend/includes/core/cs-framework.php';
			require_once FUNITER_TOOLKIT_PATH . 'includes/admin/live-search/live-search.php';
			require_once FUNITER_TOOLKIT_PATH . 'includes/mapper/includes/core.php';
			// require_once FUNITER_TOOLKIT_PATH . 'includes/taxonomy-options.php'; // add_taxonomy_fields
		}
		
		public function setup_constants() {
			// Plugin version.
			if ( ! defined( 'FUNITER_TOOLKIT_VERSION' ) ) {
				define( 'FUNITER_TOOLKIT_VERSION', '1.0.0' );
			}
			// Plugin Folder Path.
			if ( ! defined( 'FUNITER_TOOLKIT_PATH' ) ) {
				define( 'FUNITER_TOOLKIT_PATH', plugin_dir_path( __FILE__ ) );
			}
			// Plugin Folder URL.
			if ( ! defined( 'FUNITER_TOOLKIT_URL' ) ) {
				define( 'FUNITER_TOOLKIT_URL', plugin_dir_url( __FILE__ ) );
			}
		}
		
		public function includes() {
			require_once FUNITER_TOOLKIT_PATH . 'includes/admin/welcome.php';
			require_once FUNITER_TOOLKIT_PATH . 'includes/post-types.php';
			require_once FUNITER_TOOLKIT_PATH . 'includes/taxonomy-images.php'; // add_taxonomy_fields
			require_once FUNITER_TOOLKIT_PATH . 'includes/helpers.php';
			require_once FUNITER_TOOLKIT_PATH . 'includes/frontend/framework.php';
		}
		
		public function load_textdomain() {
			load_plugin_textdomain( 'funiter-toolkit', false, FUNITER_TOOLKIT_URL . 'languages' );
		}
		
		public function admin_scripts() {
			wp_enqueue_script( 'funiter-toolkit-backend', FUNITER_TOOLKIT_URL . 'assets/js/backend.js', array(), null );
		}
		
	}
}
if ( ! function_exists( 'FUNITER_TOOLKIT' ) ) {
	function FUNITER_TOOLKIT() {
		return Funiter_Toolkit::instance();
	}
	
	FUNITER_TOOLKIT();
	add_action( 'plugins_loaded', 'FUNITER_TOOLKIT', 10 );
}
if ( ! function_exists( 'funiter_toolkit_is_mobile' ) ) {
	function funiter_toolkit_is_mobile() {
		if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$is_mobile = false;
		} elseif ( strpos( $_SERVER['HTTP_USER_AGENT'], 'Mobile' ) !== false // many mobile devices (all iPhone, iPad, etc.)
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'Android' ) !== false
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'Silk/' ) !== false
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'Kindle' ) !== false
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'BlackBerry' ) !== false
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mini' ) !== false
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mobi' ) !== false ) {
			$is_mobile = true;
		} else {
			$is_mobile = false;
		}
		
		return apply_filters( 'wp_is_mobile', $is_mobile );
	}
}