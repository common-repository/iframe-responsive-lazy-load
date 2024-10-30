<?php

// Checks if class exits
if ( !class_exists( 'iframeRLL_admin_editor_btn' ) ) {

	/**
	* Class - iframeRLL_admin_editor_btn
	*
	* @since 1.0.0
	*/
	class iframeRLL_admin_editor_btn {

		/**
		* iframeRLL_admin_editor_btn class Constructor
		*
		* @since 1.0.0
		*/
		public function __construct() {
			add_action( 'media_buttons', array( $this, 'media_button' ), 15 );
		}

		/**
		* Media button to access shortcode
		*
		* @since 1.0.0
		*/
		public function media_button( $editor_id ) {

			if ( ! apply_filters( 'iframeRLL_display_media_button', is_admin(), $editor_id ) ) {
				return;
			}

			$icon = '';

			printf( '<a href="#" class="button iframeRLL_insert_btn" data-editor="%s" title="%s">%s %s</a>',
				esc_attr( $editor_id ),
				'Add Responsive iframe',
				$icon,
				iframeRLL_plugin_name
			);
			add_action( 'admin_footer', array( $this, 'iframeRLL_shortcode_modal' ) );

		}

		/**
		* Media button content
		*
		* @since 1.0.0
		*/
		public function iframeRLL_shortcode_modal() {

			$loc = apply_filters('iframeRLL_button_content', array(
				'location' => iframeRLL_dir_path,
				'name' => 'admin-editor-btn'
			));
			extract($loc);
			iframe_RLL()->views->iframeRLL_($location, $name);

		}

	}

}
