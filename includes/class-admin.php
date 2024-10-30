<?php

// Checks if class exits
if ( !class_exists( 'iframeRLL_admin' ) ) {

	/**
	* Class - iframeRLL_admin
	*
	* @since 1.0.0
	*/
	class iframeRLL_admin{

		/**
		* iframeRLL_admin class Constructor
		*
		* @since 1.0.0
		*/
		public function __construct() {

			add_action( 'admin_menu', array($this, 'iframeRLL_menu') );
			add_action( 'admin_init', array($this, 'iframeRLL_admin_ini'));
			add_shortcode( 'iframe_rll', array($this, 'iframeRLL_frame') );

		}

		/**
		* Plugin Menu
		*
		* @since 1.0.0
		*/
		public function iframeRLL_menu() {

			$view = iframe_RLL()->views;
			add_submenu_page(
				'options-general.php',
				__('iframe RLL', 'iframe-rll'),
				__('iframe RLL', 'iframe-rll'),
				'manage_options',
				iframeRLL_plugin_slug,
				array( $this, 'iframeRLL_page_template' )
			);

		}

		/**
		* Registers iframeRLL_fields settings
		*
		* @since 1.0.0
		*/
		public function iframeRLL_admin_ini() {

			register_setting('iframeRLL_fields_manager', 'iframeRLL_fields');

		}

		/**
		* Plugin page
		*
		* @since 1.0.0
		*/
		public function iframeRLL_page_template() {

			$loc = apply_filters('iframeRLL_page_content', array(
				'location' => iframeRLL_dir_path,
				'name' => 'admin-iframe-rll-page'
			));
			extract($loc);
			iframe_RLL()->views->iframeRLL_($location, $name);

		}

		/**
		* Checks if current user is logged in  or not
		*
		* @since 1.0.0
		*/
		public function logged_in_check($src = '',$value = '') {

			$tf_value = '';

			if(! empty($src)){
				if(is_user_logged_in()){
					$tf_value  .= $value;
				}
			}else{
				$tf_value  .= $value;
			}
			return $tf_value;

		}

		/**
		* Plugin shortcode constructor
		*
		* @since 1.0.0
		*/
		public function iframeRLL_frame( $atts ) {

			$field = get_option( 'iframeRLL_fields' );
			if(! empty($field['activate'])){

				extract(
					shortcode_atts(
						apply_filters( 'shortcode_attributes' ,array('src' => '','width' => '','height' => '','class' => '','login' => '') ),
						$atts
					)
				);

				$views 			= iframe_RLL()->views;
				$random 		= $views->random_text();
				$src 			= $views->iframeRLL_validate($src);
				$addition_class = $field['parent_class'];

				if ( empty($width) && empty($height) ) {
					$tf = $this->logged_in_check(
						$login,
						$views->get_iframe_responsive($src,$random,$addition_class,$class)
					);
				}else{
					$width  = (empty($width) )? 'auto' : $width;
					$height = (empty($height))? 'auto' : $height;
					$tf = $this->logged_in_check(
						$login,
						$views->get_adjust_iframe($src,$width,$height,$random,$addition_class,$class)
					);
				}

				return $tf;
			}
		}

	}

}