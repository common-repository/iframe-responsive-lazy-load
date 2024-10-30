<?php

// Creating the widget
class iframeRLL_widget extends WP_Widget {

	/**
	* iframeRLL_widget class Constructor
	*
	* @since 1.0.0
	*/
	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'iframeRLL_widget',

			// Widget name will appear in UI
			__('Insert iframe', 'iframe-rll'),

			// Widget description
			array( 'description' => __( iframeRLL_plugin_name, 'iframe-rll' ), )
		);
	}

	/**
	* Creating widget frontend
	*
	* @since 1.0.0
	*/
	public function widget( $args, $instance ) {
		$field 	= get_option( 'iframeRLL_fields' );
		$title	= $instance['title'];
		$width	= $instance['width'];
		$height = $instance['height'];
		$login  = isset($instance['login']) && $instance['login'] != '' ? $instance['login'] : $instance['login'];

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];

		if ( ! empty( $title ) ){
			if(isset($field['logged_in']) && !empty($field['logged_in'])){
				echo iframe_RLL()->views->render_shortcode($title,$width,$height,$login);
			}else{
				echo iframe_RLL()->views->render_shortcode($title,$width,$height,'');
			}
		}

		echo $args['after_widget'];
	}

	/**
	* Creating widget Backend
	*
	* @since 1.0.0
	*/
	public function form( $instance ) {

		$title	= ( isset($instance['title'])  )? $instance['title']  : __( '', 'iframe-rll' );
		$width	= ( isset($instance['width'])  )? $instance['width']  : __( '', 'iframe-rll' );
		$height = ( isset($instance['height']) )? $instance['height'] : __( '', 'iframe-rll' );
		$login	= ( isset($instance['login'])  )? $instance['login']  : __( '', 'iframe-rll' );

		// Widget admin form
		?>
		<p>
			<label>Source:</label>
			<?php
			$field = get_option( 'iframeRLL_fields' );
			if(isset($field['multiple_iframe']) && !empty($field['multiple_iframe'])){ ?>
			<textarea rows="4" cols="50" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>"><?php echo esc_attr( $title ); ?></textarea>
			<small>
				<?php _e( 'One link per line followed by a comma [ Multiple iframe ]', 'iframe-rll' ); ?>
            </small>
			<?php }else{ ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
			<?php } ?>
		</p>
		<table>
			<tr>
				<td><label>Width:</label></td>
				<td><input size="23" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo esc_attr( $width ); ?>" /></td>
			</tr>
			<tr>
				<td><label>Height:</label></td>
				<td><input size="23" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo esc_attr( $height ); ?>" /></td>
			</tr>
		</table>

		<p>
            <input class="checkbox" type="checkbox" <?php checked( $login , "on" ) ?> id="<?php echo $this->get_field_id( 'login' ); ?>" name="<?php echo $this->get_field_name( 'login' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'login' ); ?>" title=""><?php _e( 'Only for Logged in users', 'iframe-rll' ); ?></label>
		</p>

		<?php
	}

	/**
	* Updating widget replacing old instances with new
	*
	* @since 1.0.0
	*/
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title']  = ( ! empty( $new_instance['title']  ) ) ? strip_tags( $new_instance['title']  ) : '';
		$instance['width']  = ( ! empty( $new_instance['width']  ) ) ? strip_tags( $new_instance['width']  ) : '';
		$instance['height'] = ( ! empty( $new_instance['height'] ) ) ? strip_tags( $new_instance['height'] ) : '';
		$instance['login']  = ( ! empty( $new_instance['login']  ) ) ? strip_tags( $new_instance['login']  ) : '';

		return $instance;
	}
}

/**
* Registering widget
*
* @since 1.0.0
*/
add_action( 'widgets_init', function() {
	$field = get_option( 'iframeRLL_fields' );
	if(isset($field['widget']) && !empty($field['widget'])){
		return register_widget( 'iframeRLL_widget' );
	}else{
		return;
	}
});