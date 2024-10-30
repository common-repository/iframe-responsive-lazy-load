<?php
/**
 * iframe - Responsive, Lazy Load
 *
 * Plugin Name: iframe - Responsive, Lazy Load
 * Description: Embedd iframe with auto responsive and fast loading speed with ease.
 * Version: 	1.0
 * Author: 		WPacho
 * Author URI: 	https://wpacho.com/
 * Text Domain: iframe-rll
 * Domain Path: /languages/
 * License: 	GPLv2 or later
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

//----------------------------------------------------------------------

// Exit if accessed directly
if(!defined('ABSPATH')) exit;

// Checks if class exits
if ( !class_exists( 'iframe_Responsive_Lazy_Load' ) ) {

	/**
	* Final Class - iframe_Responsive_Lazy_Load
	*
	* @since 1.0.0
	*/
	final class iframe_Responsive_Lazy_Load{

		// Important initialization
		public  $iframeRLL_version		= '1.0';
		public  $iframeRLL_plugin_slug	= 'iframe-rll';
		public  $iframeRLL_full_form 	= 'iframe - Responsive, Lazy Load';
		public  $review				 	= 'https://wordpress.org/support/plugin/iframe-responsive-lazy-load/reviews/';
		public  $visit				 	= 'https://wpacho.com/';
		private $sri				  	= array();

		/**
		* iframe_Responsive_Lazy_Load class Constructor
		*
		* @since 1.0.0
		*/
		public function __construct() {
			$this->iframeRLL_define();
			$this->iframeRLL_require();
			$this->localize_plugin();
			add_action('init', array( $this, 'iframeRLL_register_script_style'));
			add_action('init', array($this, 'iframeRLL_ini'));
			add_filter('plugin_action_links_'.plugin_basename(__FILE__), array( $this, 'iframeRLL_settings_link'));
			add_filter('plugin_row_meta', array( $this,'iframeRLL_right_link' ), 10, 2 );
			register_activation_hook( __FILE__, array( $this, 'iframeRLL_ini_value' ) );
		}

		/**
		* Active fields ini value
		*
		* @since 1.0.0
		*/
		public function iframeRLL_ini_value() {
			update_option('iframeRLL_fields', array(
				'activate' => 1,
				'parent_class' => 'iframe-rll-add-class',
				'lazy_load' => 1,
				'widget' => 1,
			));
		}

		/**
		* Point to classes
		*
		* @since 1.0.0
		*/
		public function __get( $prop ) {
			if ( array_key_exists( $prop, $this->sri ) ) {
				return $this->sri[ $prop ];
			}
			return $this->{$prop};
		}

		/**
		* checks if variable is defined
		*
		* @since 1.0.0
		*/
		public function __isset( $prop ) {
			return isset( $this->{$prop} ) || isset( $this->sri[ $prop ] );
		}

		/**
		* All Defined value with this plugin
		*
		* @since 1.0.0
		*/
		protected function iframeRLL_define() {
			define( 'iframeRLL_ver', $this->iframeRLL_version );
			define( 'iframeRLL_plugin_slug', $this->iframeRLL_plugin_slug );
			define( 'iframeRLL_plugin_name', $this->iframeRLL_full_form );
			define( 'iframeRLL_dir_path', untrailingslashit( dirname( __FILE__ ) ) );
			define( 'iframeRLL_dir_url', untrailingslashit( plugin_dir_url(__FILE__) ) );
			define( 'iframeRLL_inc_dir', iframeRLL_dir_path . '/includes');
			define( 'iframeRLL_nonce', 'check_its_iframeRLL');
			define( 'iframeRLL_visit', $this->visit);
			define( 'iframeRLL_rating', $this->review);
		}

		/**
		* All require files for initialization
		*
		* @since 1.0.0
		*/
		public function iframeRLL_require() {
			require_once iframeRLL_inc_dir . "/class-admin.php";
			require_once iframeRLL_inc_dir . "/class-admin-editor-btn.php";
			require_once iframeRLL_inc_dir . "/class-views.php";
			require_once iframeRLL_inc_dir . "/class-widgets.php";
			require_once iframeRLL_inc_dir . '/class-lazy-load.php';
		}

		/**
		* Points to respective classes
		*
		* @since 1.0.0
		*/
		public function iframeRLL_ini() {
			$this->sri['admin'] 	 = new iframeRLL_admin();
			$this->sri['editor_btn'] = new iframeRLL_admin_editor_btn();
			$this->sri['views'] 	 = new iframeRLL_views();
			$this->sri['widget'] 	 = new iframeRLL_widget();
		}

		/**
		* Registers all required styles and scripts
		*
		* @since 1.0.0
		*/
		public function iframeRLL_register_script_style() {
			wp_register_style( 'iframeRLL_editor_btn_css', iframeRLL_dir_url.'/assests/css/iframe-rll-editor-btn.css' );
			wp_register_style( 'iframeRLL_admin_page', iframeRLL_dir_url.'/assests/css/iframe-rll-admin-page.css' );
			wp_enqueue_script( 'iframeRLL_lazysizes_js', iframeRLL_dir_url.'/assests/js/lazysizes.min.js', array(), false, true );
			wp_register_script( 'iframeRLL_editor_btn_js', iframeRLL_dir_url.'/assests/js/iframe-rll-editor-btn.js' );
			if(isset($_GET['page'])){
				if($_GET['page'] == iframeRLL_plugin_slug && is_admin()){
					wp_enqueue_style ( 'iframeRLL_admin_page' );
				}
			}
			wp_localize_script( 'iframeRLL_editor_btn_js', 'SRI', array(
				'ajaxurl' 	=> admin_url( 'admin-ajax.php' ),
				'nonce' 	=> wp_create_nonce(iframeRLL_nonce)
			));
		}

		/**
		* Left hand side plugin row links
		*
		* @since 1.0.0
		*/
		public function iframeRLL_settings_link( $links ) {
			$arr 	    = $links;
			$main_arr   = array();
			$main_arr[] = '<a href="' . admin_url( 'options-general.php?page='. iframeRLL_plugin_slug .'' ) .'">' . __('Settings', 'iframe-rll') . '</a>';
			return array_merge($main_arr,$arr);
		}

		/**
		* Right hand side plugin row links
		*
		* @since 1.0.0
		*/
		public function iframeRLL_right_link( $links,$file ) {
			$plugin = plugin_basename(__FILE__);
			if ( $file == $plugin ) {
				return array_merge(
					$links,
					array(
							'<a href="'. $this->review .'" target="_blank">'.__('Rating', 'iframe-rll').'</a>',
							'<a href="'. iframeRLL_visit . '?donate=yes' .'" target="_blank">'.__('Donate', 'iframe-rll').'</a>'
						)
				);
			}
			return $links;
		}

		/**
		* Textdomain required for translation
		*
		* @since 1.0.0
		*/
		public function localize_plugin() {
			load_plugin_textdomain(
				'iframe-rll',
				false,
				plugin_basename( dirname( __FILE__ ) ) . '/languages'
			);
		}

		/**
		* Initialize function
		* @since 1.0
		*/
		public static function init() {
			static $instance = false;
			if ( ! $instance ) {
				$instance = new iframe_Responsive_Lazy_Load();
			}
			return $instance;
		}

	}

}

// Construct common function
function iframe_RLL() {
    return iframe_Responsive_Lazy_Load::init();
}

// Start
iframe_RLL();