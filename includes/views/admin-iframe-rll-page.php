<?php
$view = iframe_RLL()->views;
?>
<div class="wrap">
   <h1><?php echo $view->iframeRLL_name(); ?></h1>
   <?php do_action('iframeRLL_errors'); ?>
   <form id="iframe-rll-options" method="post" action="options.php">
      <?php settings_fields( 'iframeRLL_manage_field' ); ?>
      <div class="metabox-holder">
         <div style="width: 70%;float: left;">
            <div class="postbox">
               <div class="inside">
                  <?php $field = get_option( 'iframeRLL_fields' ); ?>
                  <table class="form-table">
                     <tbody>
                        <tr class="iframe-rll-header">
                           <td colspan="2">
                              <h4>Shortcode</h4>
                           </td>
                        </tr>
                        <tr valign="top">
                           <th scope="row">Default</th>
                           <td><code>[iframe_rll src="" width="" height="" class=""]</code></td>
                        </tr>
                        <tr valign="top">
                           <th scope="row">PHP</th>
                           <td><code>&lt;?php echo do_shortcode('[iframe_rll src="" width="" height="" class=""]'); ?></code></td>
                        </tr>
                        <tr valign="top">
                           <th scope="row">All Attributes</th>
							<td>
								<span class="attr">src</span>
								<span class="attr">width</span>
								<span class="attr">height</span>
								<span class="attr">class</span>
								<span class="attr">login : true or false</span>
							</td>
                        </tr>
                        <tr valign="top" class="no-space">
                           <th scope="row">&nbsp;</th>
                           <td>&nbsp;</td>
                        </tr>
                     </tbody>
                  </table>

                  <table class="form-table">
                     <tbody>
                        <tr class="iframe-rll-header">
                           <td colspan="2">
                              <h4>Settings</h4>
                           </td>
                        </tr>
                        <tr valign="top">
                           <th scope="row"><b>Activate</b></th>
                           <td><input type="checkbox" name="iframeRLL_fields[activate]" value="1" <?php checked( 1, isset($field['activate']) ) ?>/></td>
                        </tr>
					    <tr valign="top">
						   <th scope="row">Lazy Loading</th>
						   <td><input type="checkbox" name="iframeRLL_fields[lazy_load]" value="1" <?php checked( 1, isset($field['lazy_load']) ) ?>/></td>
					    </tr>
					    <tr valign="top">
						   <th scope="row">Add to Widget</th>
						   <td><input type="checkbox" name="iframeRLL_fields[widget]" value="1" <?php checked( 1, isset($field['widget']) ) ?>/></td>
					    </tr>
                        <tr valign="top">
                           <th scope="row">Additional Class</th>
                           <td class="iframe-rll-flex"><input type="text" name="iframeRLL_fields[parent_class]" value="<?php echo (isset($field['parent_class']))? $field['parent_class'] : ''; ?>" /><i>add class name seperated by space</i></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
            <p class="submit">
               <input type="submit" class="button-primary" name="Submit" value="Save Changes">
            </p>
         </div>
		 <div style="width: 29%;float: left;margin-left: 1%;">
			<div class="postbox">
			   <div class="inside">
				  <table class="form-table">
					 <tbody>
						<tr class="iframe-rll-header">
						   <td colspan="2">
							  <h4>Features</h4>
						   </td>
						</tr>
						<tr valign="top">
						   <td colspan="2" style="padding: 0px 10px;">
								<ul style="margin: 0;">
									<li><span class="dashicons dashicons-yes"></span>Fully Responsive</li>
									<li><span class="dashicons dashicons-yes"></span>Lazy Load</li>
									<li><span class="dashicons dashicons-yes"></span>Add Parent class</li>
									<li><span class="dashicons dashicons-yes"></span>Custom Width and Height</li>
									<li><span class="dashicons dashicons-yes"></span>Login users ( true or false )</li>
									<li><span class="dashicons dashicons-yes"></span>Widget</li>
									<li><span class="dashicons dashicons-yes"></span>Media button</li>
								</ul>
						   </td>
						</tr>
					 </tbody>
				  </table>
				  <table class="form-table">
					 <tbody>
						<tr class="iframe-rll-header">
						   <td colspan="2">
							  <h4>Links</h4>
						   </td>
						</tr>
						<tr valign="top">
						   <td colspan="2" style="padding: 0px 10px;">
								<a href="<?php echo iframeRLL_rating; ?>" target="_blank" class="links">Rating</a>
								<a href="<?php echo iframeRLL_visit . '?donate=yes'; ?>" target="_blank" class="links">Donate</a>
								<a href="<?php echo iframeRLL_visit; ?>" target="_blank" class="links">Visit</a>
						   </td>
						</tr>
					 </tbody>
				  </table>
			   </div>
			</div>
		 </div>
      </div>
   </form>
</div>