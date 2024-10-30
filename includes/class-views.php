<?php

// Checks if class exits
if ( !class_exists( 'iframeRLL_views' ) ) {

	/**
	* Class - iframeRLL_views
	*
	* @since 1.0.0
	*/
	class iframeRLL_views{

		/**
		* iframeRLL_views class Constructor
		*
		* @since 1.0.0
		*/
		public function __construct() {

			add_action('admin_init',array( $this, 'register_settings_fields'));

		}

		/**
		* Creates Random text as frontend parent class
		*
		* @since 1.0.0
		*/
		public function random_text( $length = 10 ) {

			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$random_text = substr( str_shuffle( $chars ), 0, $length );
			return $random_text;

		}

		/**
		* Plugin name across site
		*
		* @since 1.0.0
		*/
		public function iframeRLL_name() {

			$name = apply_filters('iframeRLL_name',iframeRLL_plugin_name);
			return $name;

		}

		/**
		* access file in view folder
		*
		* @since 1.0.0
		*/
		public function iframeRLL_( $path , $def ) {

			include( $path . '/includes/views/' . $def .'.php');

		}

		/**
		* check for youtube link and converts to embed link
		*
		* @since 1.0.0
		*/
		public function iframeRLL_validate($arr) {

			$validate = (strpos($arr, 'youtube.com/watch') == true) ? "https://www.youtube.com/embed/". str_replace(" ","",str_replace("https://www.youtube.com/watch?v=","",$arr)) : $arr;
			return $validate;

		}

		/**
		* Creates settings field
		*
		* @since 1.0.0
		*/
		public function register_settings_fields () {

			register_setting('iframeRLL_manage_field', 'iframeRLL_fields');

		}

		/**
		* Creates html element with closing PHP tags
		*
		* @since 1.0.0
		*/
		public function iframeRLL_render( $tag = '', $attrs = array(), $content = '' ) {

			$html = '';
			$attr = '';
			$contents = '';

			if($tag != 'style'){

				foreach($attrs as $assign => $value){
					$attr .= $assign .' = "'. $value .'" ';
				}

				$html .= '<'.$tag.' '.$attr.'>';
				$html .= $content;
				$html .= '</'.$tag.'>';

			}else{

				foreach($attrs as $a => $b){

					//class
					$numItems = count($b['class']);
					$i = 0;
					foreach($b['class'] as $c => $d){
						if(++$i === $numItems) {
							$seperator = '';
						}else{
							$seperator = ',';
						}

						$contents .= (isset($d) ? str_replace(":"," ",str_replace(" ",".",$d)).$seperator : '');

					}

					$contents .= '{';

					//style
					foreach($b['value'] as $e => $f){

						$contents .= $e .' : '. $f .';';

					}

					$contents .= '}';

				}
				$contents;

				$html .= '<'.$tag.'>';
				$html .= $contents;
				$html .= '</'.$tag.'>';

			}

			return $html;

		}

		/**
		* Main function to display iframe content( for responsive )
		*
		* @since 1.0.0
		*/
		public function get_iframe_responsive($src,$random,$class,$s_class) {

			$construct = $this->iframeRLL_render(
				'style',
				array(
					array(
						'class' => array(
							' iframe-rll-content-class '. $random .''
						),
						'value' => array(
							'position' => 'relative',
							'padding-bottom' => '56.25%',
							'height' => '0',
							'overflow' => 'hidden',
							'max-width' => '100%'
						)
					),
					array(
						'class' => array(
							' iframe-rll-content-class '. $random .':iframe',
							' iframe-rll-content-class '. $random .':object',
							' iframe-rll-content-class '. $random .':embed'
						),
						'value' => array(
							'position' => 'absolute',
							'top' => '0',
							'left' => '0',
							'width' => '100%',
							'height' => '100%'
						)
					)
				)
			);

			$construct .=  $this->iframeRLL_render(
				'div',
				array('class' => ''. $class . ' ' . $s_class .' iframe-rll-content-class '. $random .''),
				$this->iframeRLL_render(
					'iframe',
					array(
						'src' => $src,
						'frameborder' => '0',
						'webkitAllowFullScreen' => '',
						'mozallowfullscreen' => '',
						'allowfullscreen' => '',
					),
					''
				)
			);

			return $construct;

		}

		/**
		* Main function to display iframe content( for custom width and height)
		*
		* @since 1.0.0
		*/
		public function get_adjust_iframe($src,$width,$height,$random,$class,$s_class) {

			$construct = $this->iframeRLL_render(
				'style',
				array(
					array(
						'class' => array(
							' iframe-rll-content-class '. $random .''
						),
						'value' => array()
					),
					array(
						'class' => array(
							' iframe-rll-content-class '. $random .':iframe',
							' iframe-rll-content-class '. $random .':object',
							' iframe-rll-content-class '. $random .':embed'
						),
						'value' => array(
							'width' => $width,
							'height' => $height
						)
					)
				)
			);

			$construct .=  $this->iframeRLL_render(
				'div',
				array('class' =>  ''. $class . ' ' . $s_class .' iframe-rll-content-class '. $random .''),
				$this->iframeRLL_render(
					'iframe',
					array(
						'src' => $src,
						'frameborder' => '0',
						'webkitAllowFullScreen' => '',
						'mozallowfullscreen' => '',
						'allowfullscreen' => '',
					),
					''
				)
			);

			return $construct;

		}

		/**
		* Render shortcode for widget
		*
		* @since 1.0.0
		*/
		public function render_shortcode($source='',$width='',$height='',$login='') {

			$width_val  = (! empty($width) )? $width  : '';
			$height_val = (! empty($height))? $height : '';
			$login_val  = (! empty($login) )? 'true'  : '';
			$shortcode  = do_shortcode( '[iframe_rll src="'. $source .'" width="'. $width_val .'"  height="'. $height_val .'" login="'. $login_val .'"]' );

			return $shortcode;

		}

	}

}