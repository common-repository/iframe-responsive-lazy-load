<?php

// Deny direct access
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Remove main registered option when plugin is deleted
delete_option('iframeRLL_fields');