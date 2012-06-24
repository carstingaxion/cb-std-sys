<?php
 
 // @todo manually set this in style.css
define('CB_STD_SYS_VERSION','0.1.1');


if(!defined('WP_THEME_URL')) {
  define( 'WP_THEME_URL', get_stylesheet_directory_uri() );
}
/**
 *  Changelog
 *  
******  Version 0.1.1

		wp-config.php

			- Changed WP_PLUGIN_URL from '/wp-content/plugins' to '/plugins'
			- Added constant definition of 'WP_TEMP_DIR' for use with BACKWPUP-Plugin


		hook-tinymce.php (Plugin)

			- Added 'p' and 'h2' to default format dropdown menu


		.htaccess (docroot)

			- Added '# Protect from spam comments'
			- Changed RR for Image-Directory from 'images' to 'img', pointing to '/wp-content/themes/cb-std-sys/img/'


		hook_frontend.php

			- body-class 'page' added for is_404()
			- shedule 'send_error_log_to_admin' now 'weekly'
			- fixes for 'my_task_deactivate' (delete cronjob)
			- add_filter 'get_the_title' for 'fancy_amp', removed 'in_the_loop()'-condition
			- applied filters to prev and next pagination links
			  'cb_pagination_prev_link'  & 'cb_pagination_next_link'
			- changed 'google-Analytics' fn to show snippet only to not logged in users


		hook_backend.php

			- Added function to rename files during upload
			- Added function to set alt-attr same as title-attr if empty



******  Version 0.1.0
FILES CHANGED:    hook_backend.php, hook_frontend.php

  - check for "is used as blog"
      - hide MetaBox Quickpress
      - Posts Menu
      
  - check for "use comments"
      - hide MetaBox RightNow
      
  - removed "root relative URLs" for attachements  
  
  - small adjustments on hide/show metaboxes 

  - Added 'clean_wp_width_height' to setup the base for responsive webdesign
  
  - 1st release of 'contextual_pagination' 
  
  
**/ 

 
?>