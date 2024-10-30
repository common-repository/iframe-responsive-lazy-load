<?php
	wp_enqueue_style ( 'iframeRLL_editor_btn_css' );
	wp_enqueue_script ( 'iframeRLL_editor_btn_js' );
	$field = get_option( 'iframeRLL_fields' );
?>
<div id="iframeRLL_model_bg"></div>
<div id="iframeRLL_model_div">
	<form id="iframeRLL_model_body" tabindex="-1">
		<div id="iframeRLL_model_title">
			<?php echo iframeRLL_plugin_name; ?>
			<button type="button" id="iframeRLL_model_close"><span class="screen-reader-text">Close</span></button>
		</div>

		<div id="iframeRLL_model_tab">
			<span id="iframeRLL_Basic" class="active">Basic</span>
			<span id="iframeRLL_Login_users">Login users</span>
		</div>

        <div id="iframeRLL_model_container">

			<div class="iframeRLL_model_tab iframeRLL_Basic active">
				<div class="iframeRLL_model_inner_title">Enter Source</div>
				<div class="enable_single_iframe_div">
					<input id="iframeRLL_source_single" type="text">
				</div>
				<div class="iframeRLL_attribute">
					<div class="iframeRLL_width_class">
						<div class="iframeRLL_model_inner_title">Enter Width</div>
						<input id="iframeRLL_width" type="text" placeholder="Default : 100%">
					</div>
					<div class="iframeRLL_height_class">
						<div class="iframeRLL_model_inner_title">Enter Height</div>
						<input id="iframeRLL_height" type="text" placeholder="Default : auto">
					</div>
					<div class="iframeRLL_class">
						<div class="iframeRLL_model_inner_title">Class</div>
						<input id="iframeRLL_class" type="text" placeholder="">
					</div>
				</div>
			</div>

			<div class="iframeRLL_model_tab iframeRLL_Login_users">
				<div class="iframeRLL_model_inner_title">
					<label for="logged-users">
						<input type="checkbox" id="logged-users" value="Bike">Enable for Logged in users
					</label>
				</div>
			</div>

        </div>

		<div class="submitbox">
			<div id="iframeRLL_model_cancel">
				<a class="submitdelete deletion" href="#">Cancel</a>
			</div>
			<div id="iframeRLL_model_update">
				<button class="button button-primary" id="iframeRLL_model_submit">Add iframe</button>
			</div>
		</div>
	</form>
</div>