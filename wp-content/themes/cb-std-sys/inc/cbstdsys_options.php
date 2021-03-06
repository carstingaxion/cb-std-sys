<?php
global $current_user; get_currentuserinfo(); 

// add default options on theme-activation
if ( ( isset($_GET['activated'] ) && $pagenow == 'themes.php' ) 
      ||
      get_option('cbstdsys_options') == false
    )
    add_action('admin_init', 'cbstdsys_add_defaults' );

// load options for use 
add_action('admin_init', 'cbstdsys_init' );

function cbstdsys_delete_plugin_options() {
		delete_option('cbstdsys_options');
}

// Define default option settings
function cbstdsys_add_defaults() {

		global $wpdb;

		$cbstdsys_defaults = array(	
		// Contents
            "c_first_pub"             => ""
					 ,"c_private_content"       => ""		
					 ,"c_passwd_content"        => ""
					 ,"c_main_author"           => ""		
					 ,"c_main_email"            => ""           
					 ,"c_video_doc"             => ""    
                  		
		// Modules
           ,"m_search"                => ""
					 ,"m_ga_tracking"           => ""
					 ,"m_blog"                  => ""
					 ,"m_multiauthor"           => ""
					 ,"m_tags"                  => ""
					 ,"m_comments"              => ""
					 ,"m_links"                 => ""
					 ,"m_multi_lang"            => ""
					 ,"m_maps"                  => ""
					 ,"m_galleries"             => ""					 
					 ,"m_audio_video"           => ""					 
					 ,"m_newsletter"            => ""			
					 ,"m_widgets"               => ""					 
					 ,"m_admin_use_widgets"     => ""		     
                 
		// Design
           ,"d_bg_images"             => ""
					 ,"d_bg_colors"             => ""		
					 ,"d_header_images"         => ""

		// Security & Backups
           ,"s_core_bu"               => ""
					 ,"s_uploads_bu"            => ""		
					 ,"s_db_bu"                 => ""

		// Administration
           ,"a_admin_user_IDs"        => array('1')
					 ,"a_default_index"         => ""
					 ,"a_admin_name"         		=> "Carsten Bach"
					 ,"a_admin_email"         	=> "mail@carsten-bach.de"
		);
		update_option('cbstdsys_options', $cbstdsys_defaults);
		
		
		// default configuration settings. See 'wp-admin/options.php' for more options.
    $o = array(
        'avatar_default' => 'blank',
        'avatar_rating' => 'G',
        'blacklist_keys'  =>  file_get_contents('http://jedi.org/blacklist.txt'),
        'category_base' => '/thema',
        'comment_max_links' => 0,
        'comments_per_page' => 0,
        'date_format' => 'd. F Y',
        'default_post_edit_rows' => 30,
        'links_updated_date_format' => 'j. F Y, H:i',
        'permalink_structure' => '/%year%/%postname%/',
        'rss_language' => 'de',
        'start_of_week' => 1,
        'timezone_string' => 'Etc/GMT-1',
        'use_smilies' => 0,
        'show_on_front'=> 'page',
        'page_on_front'=> 2,
        'upload_url_path' =>  str_replace( array( 'http://www.', 'http://' ) , 'http://assets.', WP_SITEURL ),
    );
    foreach ( $o as $k => $v ) {
        update_option($k, $v);
    }

    // Delete dummy post and comment.
    wp_delete_post(1, TRUE);
    wp_delete_comment(1);
    
		// Update the sample page on homepage
		$sample_page = array();
		$sample_page['ID'] = 2;
    $sample_page['post_excerpt'] = 'Ein Testartikel mit allerlei interessanten Gestaltungselementen';
		$sample_page['post_content'] = file_get_contents( dirname(__FILE__) . '/dummy-content-for-first-page.html');

		// Update the page
		wp_update_post( $sample_page );

    // empty blogroll
    $wpdb->query("DELETE FROM $wpdb->links WHERE link_id != ''");
    
}


// show options page, but only for carsten
if ( in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) )
    add_action('admin_menu', 'cbstdsys_add_options_page');


// Init plugin options to white list our options
function cbstdsys_init(){
	register_setting( 'cbstdsys_plugin_options', 'cbstdsys_options', 'cbstdsys_validate_options' );
}

// Add menu page
function cbstdsys_add_options_page() {
	$icon = WP_THEME_URL.'/images/admin-icon.png';
	add_menu_page(  'cb-std-sys '.__('Settings').', Version '.CB_STD_SYS_VERSION, 'cb-std-sys '.__('Settings'), 'administrator', 'cbstdsys', 'cbstdsys_render_form');
	add_options_page(__('All Settings','cb-std-sys'), __('All Settings','cb-std-sys'), 'administrator', 'options.php');    
}

// Render the Plugin options form
function cbstdsys_render_form() {
	?>
	<div class="wrap">
		
		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h2><span style="font-family:Consolas,Monaco,Courier,monospace">cb-std-sys</span>-<?php _e('Settings'); ?>, Version <?php echo CB_STD_SYS_VERSION; ?></h2>

		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('cbstdsys_plugin_options'); ?>
			<?php $options = get_option('cbstdsys_options'); ?>

        <fieldset>
          <legend><?php _e('Content','cb-std-sys'); ?></legend>
          
    			<table class="form-table">

      				<tr>
      					<th scope="row"><?php _e('Year of first publishing','cb-std-sys'); ?></th>
      					<td>
      						<input type="text" size="57" name="cbstdsys_options[c_first_pub]" value="<?php echo $options['c_first_pub']; ?>" />
                  <br /><span style="color:#666666;margin-left:2px;"><?php _e('Used for copyright-Note in metas and footer','cb-std-sys'); ?></span>
      					</td>
      				</tr>
      				
        			<tr valign="top" class="disabled">
        					<th scope="row"><?php _e('Secured Areas','cb-std-sys'); ?></th>
        					<td>
        						<label><input name="cbstdsys_options[c_private_content]" type="checkbox" value="1" <?php if (isset($options['c_private_content'])) { checked('1', $options['c_private_content']); } ?> /> <?php _e('use private content parts','cb-std-sys'); ?></label><br />
        
        						<label><input name="cbstdsys_options[c_passwd_content]" type="checkbox" value="1" <?php if (isset($options['c_passwd_content'])) { checked('1', $options['c_passwd_content']); } ?> /> <?php _e('use password-protected content parts','cb-std-sys'); ?></label><br />
                	</td>
        				</tr>  

      				<tr>
      					<th scope="row"><?php _e('Main Author','cb-std-sys'); ?></th>
      					<td>
      						<input type="text" size="57" name="cbstdsys_options[c_main_author]" value="<?php echo $options['c_main_author']; ?>" />
                  <br /><span style="color:#666666;margin-left:2px;"><?php _e('Used for Authorinfo in metas and RSS','cb-std-sys'); ?></span>
        						<?php if ( $options['c_main_author'] == '' ) { 
                          echo '<div class="error"><p><span class="error-topic">'.__('Main Author','cb-std-sys').':</span>required field is empty</p></div>';
                    }  ?>                  
      					</td>
      				</tr>

      				<tr>
      					<th scope="row"><?php _e('Main Email','cb-std-sys'); ?></th>
      					<td>
      						<input type="text" size="57" name="cbstdsys_options[c_main_email]" value="<?php echo $options['c_main_email']; ?>" />
                  <br /><span style="color:#666666;margin-left:2px;"><?php _e('Used for Contactinfo in metas, RSS and the commentmoderation ','cb-std-sys'); ?></span>
        						<?php if ( $options['c_main_email'] == '' ) { 
                          echo '<div class="error"><p><span class="error-topic">'. __('Main Email','cb-std-sys').':</span>required field is empty</div>';
                    }  ?>
      					</td>
      				</tr>

           </table>
             
        </fieldset>
        
        
        <fieldset>
          <legend><?php _e('Modules','cb-std-sys'); ?></legend>
          
    			<table class="form-table">
          
      			  <tr valign="top">
      					<th scope="row"><?php _e('Search','cb-std-sys'); ?></th>
      					<td>
      						<label><input name="cbstdsys_options[m_search]" type="checkbox" value="1" <?php if (isset($options['m_search'])) { checked('1', $options['m_search']); } ?> /> <?php _e('Use Site Search','cb-std-sys'); ?></label><br />
        						<?php if ( $options['m_search'] && !is_plugin_active('cbach-opensearch/cbach-opensearch.php') ) {
                          echo '<div class="error"><p><span class="error-topic">'.__('Search','cb-std-sys').':</span>Activate <a href="'.admin_url('plugins.php').'#opensearch"><strong>OpenSearch</strong></a> Plugin</p></div>';
                    }  ?>
      					</td>
      				</tr>   

      				<tr>
      					<th scope="row"><?php _e('Statistics','cb-std-sys'); ?></th>
      					<td>      					
      						<input type="text" name="cbstdsys_options[m_ga_tracking]" value="<?php echo $options['m_ga_tracking']; ?>" />
                  <span style="color:#666666;margin-left:2px;"><?php _e('Google Analytics Tracking Code','cb-std-sys'); ?></span>
        						<?php if ( $options['m_ga_tracking'] != '' && !is_plugin_active('google-analytics-dashboard/google-analytics-dashboard.php') ) {
                          echo '<div class="error"><p><span class="error-topic">'.__('Statistics','cb-std-sys').':</span>Activate <a href="'.admin_url('plugins.php').'#google-analytics-dashboard"><strong>Google Analytics Dashboard</strong></a> Plugin</p></div>';
                    }  ?>
      					</td>
      				</tr>  
          
      			  <tr valign="top">
      					<th scope="row"><?php _e('Blog','cb-std-sys'); ?></th>
      					<td>
      						<label><input name="cbstdsys_options[m_blog]" type="checkbox" value="1" <?php if (isset($options['m_blog'])) { checked('1', $options['m_blog']); } ?> /> <?php _e('Use website with Blog','cb-std-sys'); ?></label><br />
        						<?php if ( $options['m_blog'] && !is_plugin_active('rich-text-tags/rich-text-tags.php') ) {
                          echo '<div class="error"><p><span class="error-topic">'.__('Blog','cb-std-sys').':</span>Activate <a href="'.admin_url('plugins.php').'#rich-text-tags-categories-and-taxonomies"><strong>Rich Text Tags, Categories, and Taxonomies</strong></a> Plugin</p></div>';
                    }  ?>
								</td>
      				</tr>
							        
      			  <tr valign="top">
      					<th scope="row"><?php _e('Multiple Authors','cb-std-sys'); ?></th>
      					<td>
      						<label><input name="cbstdsys_options[m_multiauthor]" type="checkbox" value="1" <?php if (isset($options['m_multiauthor'])) { checked('1', $options['m_multiauthor']); } ?> /> <?php _e('If is more than one Author','cb-std-sys'); ?></label><br />
								</td>
      				</tr>
      				
      			  <tr valign="top">
      					<th scope="row"><?php _e('Tags','cb-std-sys'); ?></th>
      					<td>
      						<label><input name="cbstdsys_options[m_tags]" type="checkbox" value="1" <?php if (isset($options['m_tags'])) { checked('1', $options['m_tags']); } ?> /> <?php _e('Use Tags','cb-std-sys'); ?></label><br />
        						<?php if ( $options['m_tags'] && !is_plugin_active('rich-text-tags/rich-text-tags.php') ) {
                          echo '<div class="error"><p><span class="error-topic">'.__('Tags','cb-std-sys').':</span>Activate <a href="'.admin_url('plugins.php').'#rich-text-tags-categories-and-taxonomies"><strong>Rich Text Tags, Categories, and Taxonomies</strong></a> Plugin</p></div>';
                    }  ?>
								</td>
      				</tr>                       					      
          
      			  <tr valign="top">
      					<th scope="row"><?php _e('Comments','cb-std-sys'); ?></th>
      					<td>
     						   <label><input name="cbstdsys_options[m_comments]" type="checkbox" value="1" <?php if (isset($options['m_comments'])) {  checked( '1' , $options['m_comments']); }  ?> /> <?php _e('Let users comment and allow trackbacks','cb-std-sys'); ?></label><br />    
										<?php if ( $options['m_comments'] ) {
        						      if ( !is_plugin_active('comment-moderation-e-mail-to-post-author/comment-moderation-to-author.php') )
                          echo '<div class="error"><p><span class="error-topic">'.__('Comments','cb-std-sys').':</span>Activate <a href="'.admin_url('plugins.php').'#comment-moderation-e-mail-to-post-author"><strong>Comment Moderation E-mail to Post Author</strong></a> Plugin</p></div>';
        						      if ( !is_plugin_active('gravatarlocalcache/GravatarLocalCache.php') )
                          echo '<div class="error"><p><span class="error-topic">'.__('Comments','cb-std-sys').':</span>Activate <a href="'.admin_url('plugins.php').'#gravatarlocalcache"><strong>GravatarLocalCache</strong></a> Plugin</p></div>';
        						      if ( !is_plugin_active('social/social.php') )
													echo '<div class="error"><p><span class="error-topic">'.__('Comments','cb-std-sys').':</span>Activate <a href="'.admin_url('plugins.php').'#social"><strong>Social</strong></a> Plugin</p></div>';
                    } else {
                          if ( !is_plugin_active('bueltge-Remove-Comments-Absolutely-734119d/remove-comments-absolute.php') )
                          echo '<div class="error"><p><span class="error-topic">'.__('Comments','cb-std-sys').':</span>Activate <a href="'.admin_url('plugins.php').'#remove-comments-absolutely"><strong>Remove Comments Absolutely</strong></a> Plugin</p></div>';
										}  ?>
								</td>
      				</tr>   
          
      			  <tr valign="top">
      					<th scope="row"><?php _e('Link-Listings','cb-std-sys'); ?></th>
      					<td>
      						<label><input name="cbstdsys_options[m_links]" type="checkbox" value="1" <?php if (isset($options['m_links'])) { checked('1', $options['m_links']); } ?> /> <?php _e('Use the WP Links feature','cb-std-sys'); ?></label><br />
      					</td>
      				</tr>      

        			<tr valign="top">
        					<th scope="row"><?php _e('Widgets','cb-std-sys'); ?></th>
        					<td>
						<!--
										<label><input name="cbstdsys_options[m_widgets]" type="checkbox" value="1" <?php if (isset($options['m_widgets'])) { checked('1', $options['m_widgets']); } ?> /> <?php _e('let editors use widgets','cb-std-sys'); ?></label><br />
        
        						<label><input name="cbstdsys_options[m_admin_use_widgets]" type="checkbox" value="1" <?php if (isset($options['m_admin_use_widgets'])) { checked('1', $options['m_admin_use_widgets']); } ?> /> <?php _e('let only admins use widgets','cb-std-sys'); ?></label><br />
						 -->
										<label><input name="cbstdsys_options[m_widgets]" type="radio" value="not" <?php checked('not', $options['m_widgets']); ?> /> <?php _e('do not use any widgets','cb-std-sys'); ?></label><br />

										<label><input name="cbstdsys_options[m_widgets]" type="radio" value="editor" <?php checked('editor', $options['m_widgets']); ?> /> <?php _e('editors use widgets','cb-std-sys'); ?></label><br />

										<label><input name="cbstdsys_options[m_widgets]" type="radio" value="admins" <?php checked('admins', $options['m_widgets']); ?> /> <?php _e('only super-admins use widgets','cb-std-sys'); ?></label><br />

									</td>
        				</tr>       
        				
      			  <tr valign="top">
      					<th scope="row"><?php _e('Languages','cb-std-sys'); ?></th>
      					<td>
      						<label><input name="cbstdsys_options[m_multi_lang]" type="checkbox" value="1" <?php if (isset($options['m_multi_lang'])) { checked('1', $options['m_multi_lang']); } ?> /> <?php _e('Define for use in a multilingual environment','cb-std-sys'); ?></label><br />
        						<?php if ( $options['m_multi_lang'] ) {
        						      if ( !is_plugin_active('sitepress-multilingual-cms/sitepress.php') )
                          echo '<div class="error"><p><span class="error-topic">'.__('Languages','cb-std-sys').':</span>Activate <a href="'.admin_url('plugins.php').'#wpml-multilingual-cms"><strong>WPML Multilingual CMS</strong></a> Plugin</p></div>';
        						      if ( !is_plugin_active('wpml-string-translation/plugin.php') )
													echo '<div class="error"><p><span class="error-topic">'.__('Languages','cb-std-sys').':</span>Activate <a href="'.admin_url('plugins.php').'#wpml-string-translation"><strong>WPML String Translation</strong></a> Plugin</p></div>';
        						      if ( !is_plugin_active('wpml-translation-management/plugin.php') )
													echo '<div class="error"><p><span class="error-topic">'.__('Languages','cb-std-sys').':</span>Activate <a href="'.admin_url('plugins.php').'#wpml-translation-management"><strong>WPML Translation Management</strong></a> Plugin</p></div>';
        						      if ( !is_plugin_active('wpml-media/plugin.php') )
													echo '<div class="error"><p><span class="error-topic">'.__('Languages','cb-std-sys').':</span>Activate <a href="'.admin_url('plugins.php').'#wpml-media"><strong>WPML Media</strong></a> Plugin</p></div>';
										}  ?>
      					</td>
      				</tr>                       					      
          
      			  <tr valign="top" class="disabled">
      					<th scope="row"><?php _e('Maps','cb-std-sys'); ?></th>
      					<td>
      						<label><input name="cbstdsys_options[m_maps]" type="checkbox" value="1" <?php if (isset($options['m_maps'])) { checked('1', $options['m_maps']); } ?> /> <?php _e('Use mapping application','cb-std-sys'); ?></label><br />
      					</td>
      				</tr>   
          
      			  <tr valign="top" class="disabled">
      					<th scope="row"><?php _e('Galleries','cb-std-sys'); ?></th>
      					<td>
      						<label><input name="cbstdsys_options[m_galleries]" type="checkbox" value="1" <?php if (isset($options['m_galleries'])) { checked('1', $options['m_galleries']); } ?> /> <?php _e('Use WP Gallery feature','cb-std-sys'); ?></label><br />
      					</td>
      				</tr>                    
          
      			  <tr valign="top" class="disabled">
      					<th scope="row"><?php _e('Audio & Video','cb-std-sys'); ?></th>
      					<td>
      						<label><input name="cbstdsys_options[m_audio_video]" type="checkbox" value="1" <?php if (isset($options['m_audio_video'])) { checked('1', $options['m_audio_video']); } ?> /> <?php _e('Make use of audio- and video-content','cb-std-sys'); ?></label><br />
      					</td>
      				</tr>   
          
      			  <tr valign="top" class="disabled">
      					<th scope="row"><?php _e('Newsletter','cb-std-sys'); ?></th>
      					<td>
      						<label><input name="cbstdsys_options[m_newsletter]" type="checkbox" value="1" <?php if (isset($options['m_newsletter'])) { checked('1', $options['m_newsletter']); } ?> /> <?php _e('Use newsletter','cb-std-sys'); ?></label><br />
        						<?php if ( $options['m_newsletter'] ) {
        						      if ( !is_plugin_active('post-notification/post_notification.php') )
                          echo '<div class="error"><p><span class="error-topic">'.__('Newsletter','cb-std-sys').':</span>Activate <a href="'.admin_url('plugins.php').'#post-notification"><strong>Post Notification</strong></a> Plugin</p></div>';
										}  ?>
								</td>
      				</tr>                  				
           </table>
             
        </fieldset>

        <fieldset>
          <legend><?php _e('Design','cb-std-sys'); ?></legend>
          
    			<table class="form-table">

        			<tr valign="top">
        					<th scope="row"><?php _e('Backgrounds','cb-std-sys'); ?></th>
        					<td>
        						<label><input name="cbstdsys_options[d_bg_images]" type="checkbox" value="1" <?php if (isset($options['d_bg_images'])) { checked('1', $options['d_bg_images']); } ?> /> <?php _e('use customized background images','cb-std-sys'); ?></label><br />
        
        						<label><input name="cbstdsys_options[d_bg_colors]" type="checkbox" value="1" <?php if (isset($options['d_bg_colors'])) { checked('1', $options['d_bg_colors']); } ?> /> <?php _e('use customized background colors','cb-std-sys'); ?></label><br />
        						<?php if ( $options['d_bg_colors'] ) {
		        						      if ( !is_plugin_active('colorpicker/colorpicker.php') )
		                          echo '<div class="error"><p><span class="error-topic">'.__('Backgrounds','cb-std-sys').':</span>Activate <a href="'.admin_url('plugins.php').'#wordpress-color-picker"><strong>Wordpress Color Picker</strong></a> Plugin</p></div>';
                    }  ?>
                	</td>
        				</tr>  

        			<tr valign="top">
        					<th scope="row"><?php _e('Header Images','cb-std-sys'); ?></th>
        					<td>
        						<label><input name="cbstdsys_options[d_header_images]" type="checkbox" value="1" <?php if (isset($options['d_header_images'])) { checked('1', $options['d_header_images']); } ?> /> <?php _e('use customized header images','cb-std-sys'); ?></label><br />
                	</td>
        				</tr>  
                                                                      
           </table>
             
        </fieldset>

        <fieldset>
          <legend><?php _e('Security & Backups','cb-std-sys'); ?></legend>
          
    			<table class="form-table">

        			<tr valign="top" class="disabled">
        					<th scope="row"><?php _e('Backups','cb-std-sys'); ?></th>
        					<td>
        						<label><input name="cbstdsys_options[s_core_bu]" type="checkbox" value="1" <?php if (isset($options['s_core_bu'])) { checked('1', $options['s_core_bu']); } ?> /> <?php _e('do core backups, including the themes folder','cb-std-sys'); ?></label><br />
        						<label><input name="cbstdsys_options[s_uploads_bu]" type="checkbox" value="1" <?php if (isset($options['s_uploads_bu'])) { checked('1', $options['s_uploads_bu']); } ?> /> <?php _e('do backup the /uploads directory','cb-std-sys'); ?></label><br />
        						<label><input name="cbstdsys_options[s_db_bu]" type="checkbox" value="1" <?php if (isset($options['s_db_bu'])) { checked('1', $options['s_db_bu']); } ?> /> <?php _e('do database backups','cb-std-sys'); ?></label><br />
        						<?php if ( $options['s_core_bu'] || $options['s_uploads_bu'] || $options['s_db_bu'] ) {
		        						      if ( !is_plugin_active('backwpup/backwpup.php') )
		                          echo '<div class="error"><p><span class="error-topic">'.__('Backups','cb-std-sys').':</span>Activate <a href="'.admin_url('plugins.php').'#backwpup"><strong>BackWPup</strong></a> Plugin</p></div>';
                    }  ?>
									</td>
        				</tr>  
                                                                      
           </table>
             
        </fieldset>

        <fieldset>
          <legend><?php _e('Administration','cb-std-sys'); ?></legend>
          
    			<table class="form-table">

      				<tr>
      					<th scope="row"><?php _e('Admin User IDs','cb-std-sys'); ?></th>
      					<td>      					
      						<input type="text" name="cbstdsys_options[a_admin_user_IDs]" value="<?php echo implode (',', $options['a_admin_user_IDs'] ); ?>" />
                  <span style="color:#666666;margin-left:2px;"><?php _e('Separate User IDs by comma, whom to give FULL Administration privilegs','cb-std-sys'); ?></span>
      					</td>
      				</tr>                                                                        

      				<tr>
      					<th scope="row"><?php _e('Admin Name','cb-std-sys'); ?></th>
      					<td>
      						<input type="text" name="cbstdsys_options[a_admin_name]" value="<?php echo $options['a_admin_name']; ?>" />
                  <span style="color:#666666;margin-left:2px;"><?php _e('Show as developer and contact person','cb-std-sys'); ?></span>
      					</td>
      				</tr>

      				<tr>
      					<th scope="row"><?php _e('Admin Email','cb-std-sys'); ?></th>
      					<td>
      						<input type="text" name="cbstdsys_options[a_admin_email]" value="<?php echo $options['a_admin_email']; ?>" />
                  <span style="color:#666666;margin-left:2px;"><?php _e('Use as sidewide admin-address','cb-std-sys'); ?></span>
      					</td>
      				</tr>
      				
        			<tr valign="top">
        					<th scope="row"><?php _e('Default Index-Page','cb-std-sys'); ?></th>
        					<td>
        						<label><input name="cbstdsys_options[a_default_index]" type="checkbox" value="1" <?php if (isset($options['a_default_index'])) { checked('1', $options['a_default_index']); } ?> /> <?php _e('keep all visitors (not logged-in users) outside and show them a default index page','cb-std-sys'); ?></label><br />
        						<?php if ( $options['a_default_index'] ) {
															#if ( defined( WP_CACHE )  )
		                          #echo '<div class="error"><p><span class="error-topic">'.__('Default Index-Page','cb-std-sys').':</span>'.__('Please deactivate Caching in <strong>wp-config.php</strong>.', 'cb-std-sys').'</div>';
															if ( !file_exists($_SERVER['DOCUMENT_ROOT'].get_stylesheet_directory_uri().'/defaultindex.php' ) )
		                          echo '<div class="error"><p><span class="error-topic">'.__('Default Index-Page','cb-std-sys').':</span>'.__('Please add a <strong>defaultindex.php</strong> to the docroot.', 'cb-std-sys').'</div>';
                    }  ?>
                	</td>
        				</tr>  

           </table>
             
        </fieldset>

			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
			
		</form>

	</div>
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function cbstdsys_validate_options($input) {
#	$input['textarea_one'] =  wp_filter_nohtml_kses($input['textarea_one']); // Sanitize textarea input (strip html tags, and escape characters)

// Contents
  if ( is_float( $input['c_first_pub'] ) )
  $input['c_first_pub']           = $input['c_first_pub'];
  $input['c_private_content']     = is_numeric( $input['c_private_content'] );
  $input['c_passwd_content']      = is_numeric( $input['c_passwd_content'] );
  $input['c_main_author']         = wp_filter_nohtml_kses($input['c_main_author']);
  $input['c_main_email']          = is_email($input['c_main_email']);  
  $input['c_video_doc']           = is_numeric( $input['c_video_doc'] );

// Modules
  $input['m_search']              = is_numeric( $input['m_search'] );
  $input['m_ga_tracking']         = wp_filter_nohtml_kses($input['m_ga_tracking']);
  $input['m_blog']                = is_numeric( $input['m_blog'] );
  $input['m_multiauthor']         = is_numeric( $input['m_multiauthor'] );
  $input['m_tags']                = is_numeric( $input['m_tags'] );
  $input['m_comments']            = is_numeric( $input['m_comments'] ); 
  $input['m_links']               = is_numeric( $input['m_links'] );
  $input['m_widgets']             = wp_filter_nohtml_kses( $input['m_widgets'] );
  $input['m_admin_use_widgets']   = is_numeric( $input['m_admin_use_widgets'] );  
  
  $input['m_multi_lang']          = is_numeric( $input['m_multi_lang'] );
  $input['m_maps']                = is_numeric( $input['m_maps'] );
  $input['m_galleries']           = is_numeric( $input['m_galleries'] ); 
  $input['m_audio_video']         = is_numeric( $input['m_audio_video'] );
  $input['m_newsletter']          = is_numeric( $input['m_newsletter'] );

// Design
  $input['d_bg_images']           = is_numeric( $input['d_bg_images'] ); 
  $input['d_bg_colors']           = is_numeric( $input['d_bg_colors'] );
  $input['d_header_images']       = is_numeric( $input['d_header_images'] );

// Security & Backups
  $input['s_core_bu']             = is_numeric( $input['s_core_bu'] ); 
  $input['s_uploads_bu']          = is_numeric( $input['s_uploads_bu'] );
  $input['s_db_bu']               = is_numeric( $input['s_db_bu'] );
   
// Administration
  $input['a_admin_user_IDs']      = explode( ',', $input['a_admin_user_IDs'] );
  $input['a_default_index']       = is_numeric( $input['a_default_index'] );
  $input['a_admin_name']          = wp_filter_nohtml_kses( $input['a_admin_name'] );
  $input['a_admin_email']         = is_email( $input['a_admin_email'] );
  return $input;
}
