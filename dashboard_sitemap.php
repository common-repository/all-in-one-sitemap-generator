<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html('All In One Sitemap Generator', 'All In One Sitemap'); ?></h1>
	<a href="http://techsurvi.com/contact-us" target="_blank" class="page-title-action"><?php print_r("Contact Us"); ?></a>
	<hr />
	<form method="POST">
		<table class="form-table">
        	 <tr>
				<th scope="row"><?php echo esc_html('Out Put Of Sitemap', 'all-in-one-sitemap-generator');?></th>
				<td>
					<?php  wp_nonce_field( basename( __FILE__ ), 'wpaiositemap_xmlfile_nonce' ); ?> 
					<input type="checkbox" name="xmlfile"  value="your value" <?php if(isset($_POST['xmlfile'])) echo "checked='checked'"; ?>  /><?php echo esc_html('xml File', 'all-in-one-sitemap-generator');?>
				<td>
			</tr>
            <tr>
            	<th scope="row"></th>
            	<td>
            		<?php  wp_nonce_field( basename( __FILE__ ), 'wpaiositemap_txtfile_nonce' ); ?> 
					<input type="checkbox" name="txtfile" value="your value" <?php if(isset($_POST['txtfile'])) echo "checked='checked'"; ?>  /><?php echo esc_html('txt File', 'all-in-one-sitemap-generator');?>
				<td>
			</tr>
			<tr>
				<th scope="row"><?php echo esc_html('General Settings', 'all-in-one-sitemap-generator');?></th>
				<td>
					<?php  wp_nonce_field( basename( __FILE__ ), 'wpaiositemap_xmlfile_nonce' ); ?> 
					<input type="checkbox" name='aio_robots_file' value="your value" <?php if(isset($_POST['aio_robots_file'])) echo "checked='checked'"; ?>  /><?php echo esc_html('checked to add sitemap url in your robots.txt', 'all-in-one-sitemap-generator');?><br />
						(<?php echo esc_html('Use below line if you would like to add your sitemap file to the robots.txt', 'all-in-one-sitemap-generator');?>)<br />
                    <p><b>Sitemap: <?php echo site_url(); ?>/allinonesitemap.xml</p></b>
                <td>
            </tr>
             <tr>
            	<th scope="row"></th>
                <td>
                	<?php  wp_nonce_field( basename( __FILE__ ), 'wpaiositemap_robotsfile_nonce' ); ?> 
                    <textarea name="aio_robots_file_content" rows="6" cols="45"><?php echo aiositemap_read_robots_file(); ?></textarea>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php echo esc_html('Here is an example of what a robots file look like.', 'all-in-one-sitemap-generator');?></th><br />
                    <td>
                        User-agent: *<br />
                        Disallow: /wp-admin/<br />
                        Disallow: /wp-includes/<br />
                        Disallow: /feed/<br />
                        Disallow: */feed/<br /><br />
				
						<p><a href='https://support.google.com/webmasters/answer/6062608' rel='nofollow' target='_blank'><?php echo esc_html('Know more about robots', 'all-in-one-sitemap-generator');?></a></p>
					</td>
            </tr>
           		<tr>
				<th scope="row"></th>
				<td>
					<?php  wp_nonce_field( basename( __FILE__ ), 'wpaiositemap_pingtogoogle_nonce' ); ?> 
					<input type="checkbox" name='ping_to_google' value="your value" <?php if(isset($_POST['ping_to_google'])) echo "checked='checked'"; ?>  /><?php echo esc_html('Automatically Ping to Google.', 'all-in-one-sitemap-generator');?>
				<td>
			</tr>
			<tr>
                <td>
                	<?php  wp_nonce_field( basename( __FILE__ ), 'wpaiositemap_nonce' ); ?> 
                    <input type="submit" name="update_sitemap" class="button-primary" value="<?php _e('Save Changes'); ?> &raquo;" />
                </td>
            </tr>
			</tr>
		</table>
	</form>
	<?php
		
		if ( isset( $_POST['wpaiositemap_xmlfile_nonce'] ) && wp_verify_nonce( $_POST['wpaiositemap_xmlfile_nonce'], basename( __FILE__ ) ) ) {
    		if(isset($_POST['xmlfile']) && $_POST['xmlfile']!="") create_xml_aiositemap();
		}
		if ( isset( $_POST['wpaiositemap_txtfile_nonce'] ) && wp_verify_nonce( $_POST['wpaiositemap_txtfile_nonce'], basename( __FILE__ ) ) ) {
			if(isset($_POST['txtfile']) && $_POST['txtfile']!="") create_txt_aiositemap();
		}
		if ( isset( $_POST['wpaiositemap_robotsfile_nonce'] ) && wp_verify_nonce( $_POST['wpaiositemap_robotsfile_nonce'], basename( __FILE__ ) ) ) {
			if(isset($_POST['aio_robots_file']) && $_POST['aio_robots_file']!= "" )
				aiositemap_write_robots_file(sanitize_textarea_field($_POST['aio_robots_file_content']));
		}
		if ( isset( $_POST['wpaiositemap_pingtogoogle_nonce'] ) && wp_verify_nonce( $_POST['wpaiositemap_pingtogoogle_nonce'], basename( __FILE__ ) ) ) {
			if(isset($_POST['ping_to_google']) && $_POST['ping_to_google']!="") ping_to_google();
		}
		
		if ( !current_user_can( 'manage_catageries') ) return;
			if ( isset( $_POST['wpaiositemap_nonce'] ) && wp_verify_nonce( $_POST['wpaiositemap_nonce'], basename( __FILE__ ) ) ) {
	
	    		if(isset($_POST['xmlfile']) && $_POST['xmlfile']!="") create_xml_aiositemap();
	
	    		if(isset($_POST['txtfile']) && $_POST['txtfile']!="")  create_txt_aiositemap();
				
				if(isset($_POST['aio_robots_file']) && $_POST['aio_robots_file']!= "" ){
					aiositemap_write_robots_file(sanitize_textarea_field($_POST['aio_robots_file_content']));	
				}
				
				if(isset($_POST['ping_to_google']) && $_POST['ping_to_google']!="") ping_to_google();	
			}
	?>
</div>
