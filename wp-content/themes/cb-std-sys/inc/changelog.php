<?php
 
 // @todo manually set this in style.css
define('CB_STD_SYS_VERSION','0.1.8');


if(!defined('WP_THEME_URL')) {
  define( 'WP_THEME_URL', get_stylesheet_directory_uri() );
}
/**
 *  Changelog
 *
******  Version 0.1.8   22.02.2012
		header.php
		  - DEL:  <link rel="profile" href="http://gmpg.org/xfn/11" />

		hook_backend.php
		  - FIX:  replacement of "post" to "article" is now only done when WP_LANG is english
		
		hook_frontend.php
		  - DEL:  removed "adjust GeoMashup Output", 'cause we're going to use only WP-Cloudmademaps since now
		  - FIX:  Google-Analytics Tracking Code now only tracks www. calls, to keep assets. cookie-free for better caching
		  - FIX:  Log 404-Errors only with relative, not with absolute pathes
		  
		cbstdsys_options.php
		  - FIX: set default wp- option 'upload_url_path' to 'assets.WP_SITEURL'
		  - ADD: show unused options as "disabled"

******  Version 0.1.7   05.02.2012

		hook_frontend.php
		  - FIX: load defaultindex.php from childtheme directory

		functions.php
		  - DEL:  debug() comes now from own mu-plugin CLASS firePHPdebug()

******  Version 0.1.6   18.01.2012

		update.php
		  - ADD:  enabled automatic theme-updates via the theme-menu
		  
		  
******  Version 0.1.5   22.11.2011

		cbstdsys_options.php
		  - ADD: set default wp opt 'upload_url_path' to 'assets.SITEURL'
			- ADD. get default comment spam blacklist_keys from jedi.org/blacklist.txt

		hook_frontend.php
			- DEL:  prevent_admin_access()
			- ADD: 	childtheme default css to login_head
			- ADD:  define allowed html-tags for comments

			
******  Version 0.1.4   03.11.2011

		hook_frontend.php
		
		  - ADD:  Filter allowed tags for comments
		  - ADD:  Add alt-Attribute to Avatar-Images
		  - ADD:  Remove rel="nofollow" from comment-links
		  
		hook_backend.php
		
		  - ADD:  Remove Pings & trackbacks to webpage itself
		  
		functions_userweb.php
		
		  - ADD:  Filter to change Image-Size of Avatar-Img in Author-Bios
		  - ADD:  i18n for jQery Quicktags JS

		author.php
		
		  - ADD:  this file
		  - ADD:  meta description with cbstdsys_get_author_meta_block()

		single.php
		
		  - FIX:  Author description with cbstdsys_get_author_meta_block()
		  
		/js/libs/jqtags.js
		
		  - FIX:  i18n-Issues
		  
		404.php
		
		  - DEL:  AskApache-Stuff
		  - FIX:  some layout adjustments, like css-classes
		  
		loop-four04.php
		 
		  - ADD:  this file
		  
		loop.php
		
		  - FIX:  use four04.php for empty archives
		  
		wp-config.php
		
		  - ADD:  include wp-config-local.php with DB credentials of local DB; so you can use the same wp-config for live site and dev
		  
******  Version 0.1.3   28.10.2011

		hook_frontend.php

		  - ADD: 	trackPageLoadTime & set BounceTimeOut on 10sec for GA-Snippet
		  - ADD:  setDomainName to allow included tracking of all subdomains
		  - FIX:  Enabled feeds in <head> for posts, comments and categories,
							by moving add_theme_support( 'automatic-feed-links' ) from functions.php
			- ADD:  append '.ie-win' and  '.ie-mac' to body-class-Array
			- ADD:  Add custom background-colors through 'Colorpicker'-Plugin
		  
		  

		functions.php
		
		  - DEL:  twentyten_admin_header_style()
		  - FIX:  moved Debug-Helper-fn from hook_frontend.php to work also in backend
		  - FIX:  changed the debug() to work with FirePHP

		functions_userweb.php
		
		  - FIX: moved conditional JSs for IE from footer.php
		  - ADD: fn to define all post_thumbnail sizes and set cropping
		  - ADD: fn to make wp, edit and save all defined image-sizes on the fly


		cbstdsys_options.php
		
		  - FIX:  some i18n issues
		  - ADD:  check for installed Plugins to use with choosen options
		  
		  
		/wp-content/uploads
		
		  - ADD:	Copy of apache-error-doc.php for use on assets.domain.tld
		  - ADD:  reduced .htaccess with Expires for static contents and Errodocuments
		  
		  
		  
******  Version 0.1.2   27.10.2011

		hook_frontend.php

		  - ADD: 	Debug Helper function  debug()
		  - ADD: 	'SOCIAL'-Plugin Configuration
		  - ADD: 	Keep all visitors outside from Admin-Panel and Loginscreen
		  - FIX: 	Moved filter for wp_title from functions.php
		  - FIX: 	Moved the function to remove default styles of 'Recent Comments widget' from functions.php
		  - FIX: 	Moved add_filter( 'use_default_gallery_style', '__return_false' ) from functions.php
		  - ADD:  Add /tag to exclusion list of robots.txt


		functions_userweb.php
		
		  - FIX:  Complete Rewrite of the way CSS and JS is added to be more flexible and work with 'BWP-Minify'-Plugin
		  - ADD:  Next & Previous Navigation for Fancybox
		  
		  
		wp-config.php

			- FIX: 	moved constant definition for WP_PLUGIN_URL and PLUGINDIR to functions_userweb.php
			        and only use it when BWPMINIFY is not defined
			
			
		Layoutadjustments in themefiles
		
		  - footer.php - removed ANT Builder Comments
		  - single.php - comment_template('social-comments.php')
		  
		  
		/wp-content
		
		  - ADD: /themes/cb-std-sys/img/social with sprite-images for 'SOCIAL'-Plugin
		
		/js/libs/
		
		  - FIX:  Added caller for dd_belatedpng.js into itself, use .png_bg
			
******  Version 0.1.1

		Layoutadjustments in themefiles

			- 404.php
		  - archive.php
		  - category.php
		  - searchform.php
		  - header.php


		/languages

			- Added de_DE.po
		  - Added de_DE.mo


		/wp-content

			- ADD: 'apache-error-doc.php'
			- FIX: 'maintenace.php' & 'db-error.php' with same markup as 'apache-error-doc.php'

		wp-config.php

			- Changed WP_PLUGIN_URL from '/wp-content/plugins' to '/plugins'
			- Added constant definition of 'WP_TEMP_DIR' for use with BACKWPUP-Plugin


		hook-tinymce.php (Plugin)

			- Added 'p' and 'h2' to default format dropdown menu


		.htaccess (docroot)

			- Added '# Protect from spam comments'
			- Changed RR for Image-Directory from 'images' to 'img', pointing to '/wp-content/themes/cb-std-sys/img/'
			- ADD: Testing RR for Apache Error Documents
			- ADD: Errordocuments for Errors 400 - 507 (except 404)


		hook_frontend.php

			- body-class 'page' added for is_404()
			- shedule 'send_error_log_to_admin' now 'weekly'
			- fixes for 'my_task_deactivate' (delete cronjob)
			- add_filter 'get_the_title' for 'fancy_amp', removed 'in_the_loop()'-condition
			- applied filters to prev and next pagination links
			  'cb_pagination_prev_link'  & 'cb_pagination_next_link'
			- changed 'google-Analytics' fn to show snippet only to not logged in users
			- FIX: added whitespace between id and class-attr of menu elements
			- ADD: Performance Stats Comment to wp_footer, for Admins only
			- ADD: Trimmed excerpt for use in meta-description
			- ADD: conditonal-tag is_facebook(), to validate html5 with integrated facebook-Opengraph Markup
			- ADD: Add rel="nofollow" to tag-links from 'the_tags' and 'wp_tag_cloud' for better SEO

		hook_backend.php

			- Added function to rename files during upload
			- Added function to set alt-attr same as title-attr if empty



******  Version 0.1.0

    hook_backend.php
    
		  - check for "is used as blog"
		      - hide MetaBox Quickpress
		      - Posts Menu
		  - check for "use comments"
		      - hide MetaBox RightNow
		  - small adjustments on hide/show metaboxes


    hook_frontend.php
		  - removed "root relative URLs" for attachements
		  - Added 'clean_wp_width_height' to setup the base for responsive webdesign
		  - 1st release of 'contextual_pagination'
  
  
**/ 

 
?>