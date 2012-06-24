<?php

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
        global $wp_meta_boxes, $current_user;
        
        // google blog search
      	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);	
      	// Plugin News
      	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
      	// WordPress Blog
      	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
      	// Additional WordPress News
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);

      	if ( ! cbstdsys_opts('m_blog') ) {
          	// Quickpress
          	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
      	}   
           	
        if ( ! cbstdsys_opts('m_comments') ) {
          	// Letzte Kommentare
          	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
          	// Aktuelles
          	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);      	
      	}
      	
      	if ( ! cbstdsys_opts('m_multi_lang') ) {
          	// WPML
          	unset($wp_meta_boxes['dashboard']['normal']['core']['icl_dashboard_widget']);
      	}
      	
        if ( !in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) ) {
		          	// BackWPup Logs
		            unset($wp_meta_boxes['dashboard']['normal']['core']['backwpup_dashboard_widget_logs']);
		            // BackWPup active Crons
		          	unset($wp_meta_boxes['dashboard']['normal']['core']['backwpup_dashboard_widget_activejobs']);
								// GravatarLocalCache Stats
								unset($wp_meta_boxes['dashboard']['normal']['core']['GravatarLocalCacheWidget']);
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
    if ( in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) )
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
        global $menu, $submenu, $current_user;
        
        // Remove 'Posts'
        if ( ! cbstdsys_opts('m_blog') )
        unset($menu[5]);	
        
        // Remove 'Links'.
        if ( ! cbstdsys_opts('m_links') )
        unset($menu[15]);	
      	
        // Remove 'Comments'. 
        if ( ! cbstdsys_opts('m_comments') )
        unset($menu[25]);	 
        
        // Remove Tags
        if ( ! cbstdsys_opts('m_tags') )
        unset($submenu['edit.php'][16]); 

        // Removes 'Widgets'.
        if ( cbstdsys_opts('m_widgets') == 'not' ) {
        		unset($submenu['themes.php'][7]);
				} elseif ( cbstdsys_opts('m_widgets') == 'admins' && !in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) ) {
        		unset($submenu['themes.php'][7]);
        } else {
            // get the the role object
            $role_object = get_role( 'editor' );
            // add $cap capability to this role object
            $role_object->add_cap( 'edit_theme_options' );
				}
        
        // Show 'Menu' to Editors also
        if ( current_theme_supports( 'menus' ) ) {
            // get the the role object
            $role_object = get_role( 'editor' );
            // add $cap capability to this role object
            $role_object->add_cap( 'edit_theme_options' );
        }
        
        // Removes 'Background'.
        if ( ! cbstdsys_opts('d_bg_images') )
        unset($submenu['themes.php'][11]);     
        
        // Removes 'Header Images'.
        if ( ! cbstdsys_opts('d_header_images') )
        unset($submenu['themes.php'][12]);  
                    
        // Remove for non-Admins
        if ( !in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) ) {
            // Remove 'Plugins'.       
            unset($menu[65]);	
            // Remove 'Tools' or 'Werkzeuge'.	
            unset($menu[75]);
						// Remove Settings
						unset($menu[80]);
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
        #debug($submenu['themes.php']);
        #debug($menu);
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
    if ( !in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) )
    add_action( 'admin_notices', 'warn_user_do_not_change_notice' );
      
      
      
    /*** remove unneccessary meta-boxes from edit-screen */
    function remove_post_custom_fields() {
        global $current_user;
      	// Kommentare zulasen
      	if ( ! cbstdsys_opts('m_comments') ) {
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
      	// #slugdiv just hidden with css, to keep functionality
      	#remove_meta_box( 'slugdiv' , 'post' , 'normal' );
      	#remove_meta_box( 'slugdiv' , 'page' , 'normal' );
      	
      	// Versionierung
      	#remove_meta_box( 'revisionsdiv' , 'post' , 'normal' ); 
      	#remove_meta_box( 'revisionsdiv' , 'page' , 'normal' );
      	
      	// Autor
      	// moved to publishing metabox @since 0.1.4
      	remove_meta_box( 'authordiv' , 'post' , 'normal' );
      	remove_meta_box( 'authordiv' , 'page' , 'normal' );

      	if ( !in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) ) {
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
      	if ( ! cbstdsys_opts('m_tags') ) {
          	remove_meta_box( 'tagsdiv-post_tag' , 'post' , 'normal' ); 
          	remove_meta_box( 'tagsdiv-post_tag' , 'page' , 'normal' );
      	}
      	// Categories
      	if ( ! cbstdsys_opts('m_blog') ) {
		      	remove_meta_box( 'categorydiv' , 'post' , 'normal' );
		      	remove_meta_box( 'categorydiv' , 'page' , 'normal' );
				}
      	

      	
      	// "Einrichtung des mehrsprachigen Inhalts"
      	remove_meta_box( 'icl_div' , 'post' , 'advanced' );
      	remove_meta_box( 'icl_div_config' , 'post' , 'normal' );      	
    }
    add_action( 'admin_menu' , 'remove_post_custom_fields' );



    /**
     *  Adding Excerpts to Pages
     *
     *  @since    0.0.1
     *
     */
    function add_excerpts_to_pages() {
        add_post_type_support('page', array('excerpt') );
    }
    add_action( 'init', 'add_excerpts_to_pages' );



    /**
     *  change wp-admin left footer text
     *  
     *  @since    0.0.2
     *  
     */                    
    function change_footer_admin () {
        echo __('You\'re working with','cb-std-sys')." <a href='http://www.wordpress.org' target='_blank'>WordPress</a> \n";
        echo __('maintained &amp; designed by','cb-std-sys')." <a href='http://carsten-bach.de' target='_blank'>".cbstdsys_opts('a_admin_name')."</a>\n";
    }
    add_filter('admin_footer_text', 'change_footer_admin', 9999);  
    
    
    
    /**
     *  hide WP Version to clients and only show cbstdsys-Version
     *  
     *  @since    0.0.2
     *  
     */                            
    function hide_version_admin ( $default ) {
        global $current_user;
        if ( !in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) ) {
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
        $avatar_defaults[$myavatar] = get_bloginfo( 'name' )." ".__('Default Gravatar','cb-std-sys')." (".$myavatar.")";
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
    if ( !in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) ) {
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
    wp_enqueue_style( 'cb_std_sys_admin_styles', get_bloginfo("template_url"). '/css/cb_std_sys_admin.css', false, CB_STD_SYS_VERSION );



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
    if ( WP_LANG == 'en_EN' ) {
		    add_filter(  'gettext',  'change_post_to_article'  );
		    add_filter(  'ngettext',  'change_post_to_article'  );
    }

 
    
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



		/**
		 *	resize on upload to the largest size in media setting
		 *
		 *  @since  0.1.4
		 *  @source http://wordpress.stackexchange.com/questions/1567/best-collection-of-code-for-your-functions-php-file/9112#9112
		 */
		function replace_uploaded_image($image_data) {
				// if there is no large image : return
				if (!isset($image_data['sizes']['large'])) return $image_data;

				// path to the uploaded image and the large image
				$upload_dir = wp_upload_dir();
				$uploaded_image_location = $upload_dir['basedir'] . '/' .$image_data['file'];
				$large_image_location = $upload_dir['path'] . '/'.$image_data['sizes']['large']['file'];

				// delete the uploaded image
				unlink($uploaded_image_location);

				// rename the large image
				rename($large_image_location,$uploaded_image_location);

				// update image metadata and return them
				$image_data['width'] = $image_data['sizes']['large']['width'];
				$image_data['height'] = $image_data['sizes']['large']['height'];
				unset($image_data['sizes']['large']);

				return $image_data;
		}
		add_filter('wp_generate_attachment_metadata','replace_uploaded_image');



		/**
		 *	Remove pings & trackbacks to webpage itself
		 *
		 *  @since  0.1.4
		 *  @source http://www.splashnology.com/article/handy-tips-and-snippets-for-wordpress/1471/#
		 */
		function no_self_ping( &$links ) {
		    $home = get_option( 'home' );
		    foreach ( $links as $l => $link )
		        if ( 0 === strpos( $link, $home ) )
		            unset($links[$l]);
		}
		add_action( 'pre_ping', 'no_self_ping' );


		/**
		 *	move the author metabox into the publish metabox
		 *
		 *  @since  0.1.4
		 *  @source http://wordpress.stackexchange.com/questions/1567/best-collection-of-code-for-your-functions-php-file/2388#2388
		 */
		function move_author_to_publish_metabox() {
		    global $post_ID;
		    $post = get_post( $post_ID );
		    echo '<div id="author" class="misc-pub-section" style="border-top-style:solid; border-top-width:1px; border-top-color:#EEEEEE; border-bottom-width:0px;">'. __('Author').': ';
		    post_author_meta_box( $post );
		    echo '</div>';
		}
		add_action( 'post_submitbox_misc_actions', 'move_author_to_publish_metabox' );






?>