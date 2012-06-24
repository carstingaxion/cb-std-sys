<?php

		$cbstdsys_opts = get_option('cbstdsys_options');
    global $current_user; get_currentuserinfo();

    
    /**
     *  cleanup dashboard widgets
     *  
     *  - incoming links from google blog search
     *  - WP News
     *  - WP Plugin News
     *  - Recent comments ( if not needed )
     *  + 'pixelfans'-Blogfeed
     *  
     *  @since    0.0.4
     *  
     */                                                  
    function cleanup_dashboard_widgets( ) {
        global $cbstdsys_opts, $wp_meta_boxes, $current_user;
        
        // google blog search
      	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);	
      	// Plugin News
      	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
      	
      	if ( !$cbstdsys_opts['m_blog'] ) {
          	// Quickpress
          	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
      	}   
           	
        if ( !$cbstdsys_opts['m_comments'] ) {
          	// Letzte Kommentare
          	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
          	// Aktuelles
          	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);      	
      	}
      	
      	if ( !$cbstdsys_opts['m_multi_lang'] ) {
          	// WPML
          	unset($wp_meta_boxes['dashboard']['normal']['core']['icl_dashboard_widget']);
      	}
      	
      	//
      	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
      	//
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
      	
        if ( !in_array($current_user->ID, $cbstdsys_opts['a_admin_user_IDs'] )
            &&
             ( $cbstdsys_opts['s_core_bu'] || $cbstdsys_opts['s_uploads_bu'] || $cbstdsys_opts['s_db_bu'] ) 
        ) {
          	// BackWPup Logs
            unset($wp_meta_boxes['dashboard']['normal']['core']['backwpup_dashboard_widget_logs']);         
            // BackWPup active Crons     	
          	unset($wp_meta_boxes['dashboard']['normal']['core']['backwpup_dashboard_widget_activejobs']); 
      	}
        // add a custom dashboard widget
        wp_add_dashboard_widget( 'pixelfans_feed', 'pixelfans-Feed', 'dashboard_custom_feed_output' ); //add new RSS feed output
    }
    function dashboard_custom_feed_output() {
         echo '<div class="rss-widget">';
         wp_widget_rss_output(array(
              'url' => 'http://www.pixelfans.de/atom',
              'title' => 'pixelfans\' Blog',
              'items' => 2,
              'show_summary' => 1,
              'show_author' => 0,
              'show_date' => 1
         ));
         echo "</div>";
    }
    add_action('wp_dashboard_setup', 'cleanup_dashboard_widgets' );
    
    

    /**
     *  server monitoring as dashboard widget
     *  reads php_error.log
     *  
     *  @since    0.0.1
     *  
     */                              
    function slt_PHPErrorsWidget() {
      	$logfile = WP_CONTENT_DIR.'/php_error.log'; // Enter the server path to your logs file here
      	$displayErrorsLimit = 100; // The maximum number of errors to display in the widget
      	$errorLengthLimit = 300; // The maximum number of characters to display for each error
      	$fileCleared = false;
      	$userCanClearLog = current_user_can( 'manage_options' );
      	// Clear file?
      	if ( $userCanClearLog && isset( $_GET["slt-php-errors"] ) && $_GET["slt-php-errors"]=="clear" ) {
        		$handle = fopen( $logfile, "w" );
        		fclose( $handle );
        		$fileCleared = true;
      	}
      	// Read file
      	if ( file_exists( $logfile ) ) {
        		$errors = file( $logfile );
        		$errors = array_reverse( $errors );
        		if ( $fileCleared ) echo '<p><em>File cleared.</em></p>';
        		if ( $errors ) {
          			echo '<p>'.count( $errors ).' error';
          			if ( $errors != 1 ) echo 's';
          			echo '.';
          			if ( $userCanClearLog ) echo ' [ <b><a href="'.get_bloginfo("url").'/wp-admin/?slt-php-errors=clear" onclick="return confirm(\'Are you sure?\');">CLEAR LOG FILE</a></b> ]';
          			echo '</p>';
          			echo '<div id="slt-php-errors" style="height:250px;overflow:scroll;padding:2px;background-color:#faf9f7;border:1px solid #ccc;">';
          			echo '<ol style="padding:0;margin:0;">';
          			$i = 0;
          			foreach ( $errors as $error ) {
            				echo '<li style="padding:2px 4px 6px;border-bottom:1px solid #ececec;">';
            				$errorOutput = preg_replace( '/\[([^\]]+)\]/', '<b>[$1]</b>', $error, 1 );
            				if ( strlen( $errorOutput ) > $errorLengthLimit ) {
            					 echo substr( $errorOutput, 0, $errorLengthLimit ).' [...]';
            				} else {
            					 echo $errorOutput;
            				}
            				echo '</li>';
            				$i++;
            				if ( $i > $displayErrorsLimit ) {
              					echo '<li style="padding:2px;border-bottom:2px solid #ccc;"><em>More than '.$displayErrorsLimit.' errors in log...</em></li>';
              					break;
            				}
          			}
          			echo '</ol></div>';
        		} else {
        			 echo '<p>No errors currently logged.</p>';
        		}
      	} else {
      		  echo '<p><em>There was a problem reading the error log file.</em></p>';
      	}
    }
    function slt_dashboardWidgets() {
        wp_add_dashboard_widget( 'slt-php-errors', 'Error Logging', 'slt_PHPErrorsWidget' );
    }
    if ( in_array($current_user->ID, $cbstdsys_opts['a_admin_user_IDs'] ) )
        add_action( 'wp_dashboard_setup', 'slt_dashboardWidgets' ); 
    

    
    /**
     *  remove some menus 
     *  
     *  completely if not needed for the website
     *  or
     *  hide them for everybody than admins
     *  
     *  @since    0.0.3
     *  
     */                    
    function remove_menus () {
        global $cbstdsys_opts;
        global $menu, $submenu, $current_user;
        
        // Remove 'Posts'
        if ( !$cbstdsys_opts['m_blog'] )
        unset($menu[5]);	
        
        // Remove 'Links'.
        if ( !$cbstdsys_opts['m_links'] )
        unset($menu[15]);	
      	
        // Remove 'Comments'. 
        if ( !$cbstdsys_opts['m_comments'] )
        unset($menu[25]);	 
        
        // Remove Tags
        if ( !$cbstdsys_opts['m_tags'] )        
        unset($submenu['edit.php'][16]); 

        // Removes 'Widgets'.
        if ( !$cbstdsys_opts['m_widgets'] && !$cbstdsys_opts['m_admin_use_widgets']  )
        unset($submenu['themes.php'][7]); 
        
        // Show 'Menu' to Editors also
        if ( current_theme_supports( 'menus' ) ) {
            // get the the role object
            $role_object = get_role( 'editor' );
            // add $cap capability to this role object
            $role_object->add_cap( 'edit_theme_options' );
        }
        
        // Removes 'Background'.
        if ( !$cbstdsys_opts['d_bg_images'] )
        unset($submenu['themes.php'][11]);     
        
        // Removes 'Header Images'.
        if ( !$cbstdsys_opts['d_header_images'] )
        unset($submenu['themes.php'][12]);  
                    
        // Remove for non-Admins
        if ( !in_array($current_user->ID, $cbstdsys_opts['a_admin_user_IDs'] ) ) {
            // Removes 'Widgets'.
            unset($submenu['themes.php'][7]);
            // Remove 'Plugins'.       
            unset($menu[65]);	
            // Remove 'Tools' or 'Werkzeuge'.	
            unset($menu[75]);	
            // Remove CF7 'Formular'.
            unset($menu[100]);	
            // Remove 'BackWPup'.
            unset($menu[101]);	
            // Remove 'Cloudmade Maps'.
            unset($menu[102]);                        
            // Removes 'Updates'.
            unset($submenu['index.php'][10]); 
            // Removes 'Themes'.
            unset($submenu['themes.php'][5]); 
            // Removes 'Editor'.
            unset($submenu['themes.php'][13]);             
        }
        // debug
        #echo '<pre>'; print_r($submenu['themes.php']);echo '</pre>';
        #echo '<pre>'; print_r($menu);echo '</pre>';
    }
    add_action('admin_init', 'remove_menus');
    
    
    /**
     *  warning users, not to change anything
     *  
     *  @since    0.0.3
     *  
     */               
    function warn_user_do_not_change_notice(){

         global $current_screen;
         if ( $current_screen->parent_base == 'options-general' ||
              $current_screen->parent_base == 'wpcf7' ||
              $current_screen->parent_base == 'cloudmade-map/menu/general'
             )
              echo '<div class="error"><p>'.__("Warning - changing settings on these pages may cause problems with your website’s design! Please, be carefull.","cb-std-sys").'</p></div>';
    }    
    if ( !in_array($current_user->ID, $cbstdsys_opts['a_admin_user_IDs'] ) )
    add_action( 'admin_notices', 'warn_user_do_not_change_notice' );
      
      
      
    /*** remove unneccessary meta-boxes from edit-screen */
    function remove_post_custom_fields() {
        global $cbstdsys_opts, $current_user;
      	// Kommentare zulasen
      	if ( !$cbstdsys_opts['m_comments'] ) {      	
            remove_meta_box( 'commentstatusdiv' , 'post' , 'normal' ); 
          	remove_meta_box( 'commentstatusdiv' , 'page' , 'normal' );
          	// eingegangene Kommentare
          	remove_meta_box( 'commentsdiv' , 'post' , 'normal' ); 
          	remove_meta_box( 'commentsdiv' , 'page' , 'normal' );
          	// Trackbacks zulassen
          	remove_meta_box( 'trackbacksdiv' , 'post' , 'normal' ); 
          	remove_meta_box( 'trackbacksdiv' , 'page' , 'normal' );
      	}          	
      	// Titelform
      	remove_meta_box( 'slugdiv' , 'post' , 'normal' ); 
      	remove_meta_box( 'slugdiv' , 'page' , 'normal' );
      	// Versionierung
      	#remove_meta_box( 'revisionsdiv' , 'post' , 'normal' ); 
      	#remove_meta_box( 'revisionsdiv' , 'page' , 'normal' );
      	// Autor
      	remove_meta_box( 'authordiv' , 'post' , 'normal' ); 
      	remove_meta_box( 'authordiv' , 'page' , 'normal' );

      	if ( !in_array($current_user->ID, $cbstdsys_opts['a_admin_user_IDs'] ) ) {
          	// Custom Fields
            remove_meta_box( 'postcustom' , 'post' , 'normal' ); 
          	remove_meta_box( 'postcustom' , 'page' , 'normal' );
          	// Parent Pages
          	remove_meta_box( 'pageparentdiv' , 'page' , 'normal' );
      	}
      	// Excerpt
      	#remove_meta_box( 'postexcerpt' , 'post' , 'normal' ); 
      	#remove_meta_box( 'postexcerpt' , 'page' , 'normal' );
      	
        // Artikelbild
       	if ( ! current_theme_supports( 'post-thumbnails' ) ) {     	
            remove_meta_box( 'postimagediv' , 'post' , 'normal' ); 
          	remove_meta_box( 'postimagediv' , 'page' , 'normal' );
      	}
        // post Format
      	if ( ! current_theme_supports( 'post-formats' ) ) {
          	remove_meta_box( 'formatdiv' , 'post' , 'normal' ); 
          	remove_meta_box( 'formatdiv' , 'page' , 'normal' );
      	}
      	// Tags
      	if ( !$cbstdsys_opts['m_tags'] ) {
          	remove_meta_box( 'tagsdiv-post_tag' , 'post' , 'normal' ); 
          	remove_meta_box( 'tagsdiv-post_tag' , 'page' , 'normal' );
      	}
      	// Categories
      	#remove_meta_box( 'categorydiv' , 'post' , 'normal' ); 
      	#remove_meta_box( 'categorydiv' , 'page' , 'normal' );	
      	

      	
      	// "Einrichtung des mehrsprachigen Inhalts"
      	#remove_meta_box( 'icl_div' , 'post' , 'advanced' );
      	remove_meta_box( 'icl_div_config' , 'post' , 'normal' );      	
    }
    #add_action( 'admin_menu' , 'remove_post_custom_fields' );



    /**
     *  Adding Excerpts to Pages
     *  
     *  @since    0.0.1
     *  
     */                    
    function my_add_excerpts_to_pages() {
        add_post_type_support( 'page', 'excerpt' );
    }
    add_action( 'init', 'my_add_excerpts_to_pages' );



    /**
     *  change wp-admin left footer text
     *  
     *  @since    0.0.2
     *  
     */                    
    function change_footer_admin () {
        echo __('You\'re working with','cb-std-sys')." <a href='http://www.wordpress.org' target='_blank'>WordPress</a> \n";
        echo __('maintained &amp; designed by','cb-std-sys')." <a href='http://carsten-bach.de' target='_blank'>Carsten Bach</a>\n";
    }
    add_filter('admin_footer_text', 'change_footer_admin', 9999);  
    
    
    
    /**
     *  hide WP Version to clients and only show cbstdsys-Version
     *  
     *  @since    0.0.2
     *  
     */                            
    function hide_version_admin ( $default ) {
        global $cbstdsys_opts;    
        global $current_user;
        if ( !in_array($current_user->ID, $cbstdsys_opts['a_admin_user_IDs'] ) ) {
            return 'CB-STD-SYS Version '.CB_STD_SYS_VERSION;
        } else {
            return 'WP-'.$default.'  &amp;  CB-STD-SYS Version <a href="'.admin_url('theme-editor.php?file=/themes/cb-std-sys/changelog.php&theme=cb-std-sys&dir=theme').'">'.CB_STD_SYS_VERSION.'</a>';
        }
    }
    add_filter('update_footer', 'hide_version_admin', 9999);      
    
    
    
    /**
     *  add own default gravatar
     *  
     *  @since    0.0.2
     *  
     */                     
    function cbstdsys_avatar_defaults ($avatar_defaults) {
        $myavatar = get_stylesheet_directory_uri() . '/img/def-gravatar.gif';
        $avatar_defaults[$myavatar] = get_bloginfo( 'name' )." ".__('Default Gravatar','cb-std-sys')." (".get_stylesheet_directory_uri()."/images/def-gravatar.gif)";
        return $avatar_defaults;
    }
    add_filter( 'avatar_defaults', 'cbstdsys_avatar_defaults' );
        


    /**
     *  disable update-info on dashboard for clients (all users then me)  
     *  
     *  also needs mu-plugin/no-update-nag.php to hide the "admin_notice"     
     *  
     *  @since  0.0.6            
     */
    if ( !in_array($current_user->ID, $cbstdsys_opts['a_admin_user_IDs'] ) ) {
        // core updates
        add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
        add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );
        // plugin updates
        add_action( 'admin_menu', create_function( '$a', "remove_action( 'load-plugins.php', 'wp_update_plugins' );" ) );
        add_action( 'admin_init', create_function( '$a', "remove_action( 'admin_init', 'wp_update_plugins' );" ), 2 );
        add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_update_plugins' );" ), 2 );
        add_filter( 'pre_option_update_plugins', create_function( '$a', "return null;" ) );  
        add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );   
    } 

    
    
    /**
     *  add admin stylesheet
     *  
     *  @since    0.0.2
     *  
     */                    
    wp_enqueue_style( 'cb_std_sys_admin_styles', WP_THEME_URL. '/css/cb_std_sys_admin.css', false, CB_STD_SYS_VERSION );



    /**
     *  change "Posts" to "Articles" on english admin area
     *       
     *  hooks the translation filters
     *  
     *  @since    0.0.3
     *  
     */                    
    function change_post_to_article( $translated ) {
        $translated = str_ireplace(  'Post', 'Article', $translated );  // ireplace is PHP5 only
        return $translated;
    }
    add_filter(  'gettext',  'change_post_to_article'  );
    add_filter(  'ngettext',  'change_post_to_article'  );

 
    
    /**
     *
     *  add post-thumbnail-coulumns to posts- and pages-listings 
     *  
     *  @since  0.0.6
     *  
     */          
    function cbstdsys_AddThumbColumn($cols) {
        if ( current_theme_supports( 'post-thumbnails' ) ) {  
            $cols['thumbnail'] = __('Image');
            return $cols;
        }
    }
    // for posts
    add_filter( 'manage_posts_columns', 'cbstdsys_AddThumbColumn' );
    // for pages
    add_filter( 'manage_pages_columns', 'cbstdsys_AddThumbColumn' );       
    
    function cbstdsys_AddThumbValue($column_name, $post_id) {
        if ( current_theme_supports( 'post-thumbnails' ) ) {  
            $width = (int) 35;
            $height = (int) 35;
            if ( 'thumbnail' == $column_name ) {
                // thumbnail of WP 2.9
                $thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
                // image from gallery
                $attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'numberposts' => 1 ) );
                if ($thumbnail_id)
                    $thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
                elseif ($attachments) {
                    foreach ( $attachments as $attachment_id => $attachment ) {
                        $thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );
                    }
                }
                if ( isset($thumb) && $thumb ) {
                    echo $thumb;
                } 
            }
        }    
    }
    // for posts        
    add_action( 'manage_posts_custom_column', 'cbstdsys_AddThumbValue', 10, 2 );
    // for pages
    add_action( 'manage_pages_custom_column', 'cbstdsys_AddThumbValue', 10, 2 );



		/**
		 *
		 *  Rename files during upload
		 *  OldFilename.jpeg  ->  (Attachement-Title)-(Title of post, the file is attached to)-(Website-Url)-###.jpg
		 *
		 *  @source Based on 'Rename Media'-Plugin http://urbangiraffe.com/plugins/rename-media/
		 *  @since  0.1.1
		 *
		 */

		function rename_media_save( $post, $attachment ) {
			$old 			= get_attached_file( $post['ID'] );
			$parent		= get_post( $post['post_parent'] );
			$ext 			= str_replace( 'jpeg', 'jpg', pathinfo( basename( $old ), PATHINFO_EXTENSION ) );
			$blogurl  = str_replace( 'http://', '', WP_HOME );
			$new			= $attachment['post_title'].'-'.$parent->post_name.'-'.$blogurl.'.'.$ext;
			$new 			= wp_unique_filename( dirname( $old ), $new );
			$new 			= dirname( $old ).'/'.strtolower( $new );

			if ( $post['post_name'] != sanitize_title( $attachment['post_title'] ) ) {
				// Ensure attachment page title changes
				$post['post_name'] = sanitize_title( $attachment['post_title'] );

				// Save
				wp_update_post( $post );

				$new_url = get_permalink( $post['ID'] );

				$post['guid'] = $new_url;
				if ( isset( $_REQUEST['_wp_original_http_referer'] ) && strpos( $_REQUEST['_wp_original_http_referer'], '/wp-admin/' ) === false ) {
					$_REQUEST['_wp_original_http_referer'] = $post['guid'];
				}

				$meta = wp_get_attachment_metadata( $post['ID'] );

				// Rename the original file
				$old_filename = basename( $old );
				$new_filename = basename( $new );

				$meta['file'] = str_replace( $old_filename, $new_filename, $meta['file'] );

				// Check if new file exists
				if ( file_exists( $new ) === false ) {
					$original_filename = get_post_meta( $post['ID'], '_original_filename', true );
					if ( empty( $original_filename ) )
						add_post_meta( $post['ID'], '_original_filename', $old_filename );

					rename( $old, $new );

					// Rename the sizes
					$old_filename = pathinfo( basename( $old ), PATHINFO_FILENAME );
					$new_filename = pathinfo( basename( $new ), PATHINFO_FILENAME );

					foreach ( (array)$meta['sizes'] AS $size => $meta_size ) {
						$old_file = dirname( $old ).'/'.$meta['sizes'][$size]['file'];

						$meta['sizes'][$size]['file'] = str_replace( $old_filename, $new_filename, $meta['sizes'][$size]['file'] );

						$new_file = dirname( $old ).'/'.$meta['sizes'][$size]['file'];

						rename( $old_file, $new_file );
					}

					wp_update_attachment_metadata( $post['ID'], $meta );

					update_attached_file( $post['ID'], $new );
				}
			}

			return $post;
		}

		add_filter( 'attachment_fields_to_save', 'rename_media_save', 10, 2 );



		/**
		 *  If 'alt is empty for images, set the 'alt' attribute to always have the same value as the 'title' attribute
		 *
		 *  @source   http://www.webtechwise.com/wordpress-filter-examples-changing-attributes-when-adding-images-to-posts/
		 *  @since    0.1.1
		 *
		 */
		function add_alt_attr_if_is_empty_for_image_tag($html, $id , $alt, $title){
				if ( empty($alt) ) {
						$html = str_replace( 'alt=""', 'alt="'.$title.'"', $html );
				}
				return $html;
		}
		add_filter('get_image_tag','add_alt_attr_if_is_empty_for_image_tag',10,4);


?>