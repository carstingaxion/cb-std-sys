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
      	// Quickpress
      	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
           	
        if ( ! cbstdsys_opts('m_comments') ) {
          	// Letzte Kommentare
          	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
          	// Aktuelles
          	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);      	
      	}
      	
      	if ( cbstdsys_opts('m_multi_lang') ) {
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
      	
        /* add "Webdev Partner RSS" dashboard widget
        wp_add_dashboard_widget(
						'webdev_partner',
						apply_filters( 'cbstdsys_dashboard_widget_webdevpartner_title', 'pixelfans Blog'),
						'webdev_partner_rss_dashboard_widget'
				);
				*/
	
    }

    add_action('wp_dashboard_setup', 'cleanup_dashboard_widgets' );

function rearrange_dashboard_widgets ( $wp_dashboard_widgets ) {
        global $wp_meta_boxes, $current_user;

/*
				echo '<pre>';
				var_dump($wp_meta_boxes);
				echo '</pre>';
   */
				// Get the regular dashboard widgets array
				// (which has our new widget already but at the end)
				$core_dashboard_widgets = $wp_meta_boxes['dashboard']['normal']['core'];
				$side_dashboard_widgets = $wp_meta_boxes['dashboard']['side']['core'];

				// Backup and delete "Webdev Partner RSS" dashboard widget from the end of the array
				$webdev_partner_widget_backup = array('webdev_partner' => $core_dashboard_widgets['webdev_partner']);
				unset($core_dashboard_widgets['webdev_partner']);

				// Backup and delete "Google Analytics" dashboard widget from the end of the array
				#$dashboard_gad_widget_backup = array('dashboard_gad' => $core_dashboard_widgets['dashboard_gad']);
				#unset($core_dashboard_widgets['dashboard_gad']);
				
				// Merge the two arrays together so our widget is at the beginning
				#$core_dashboard_widgets = array_merge($webdev_partner_widget_backup, $core_dashboard_widgets);
				$side_dashboard_widgets = array_merge( $side_dashboard_widgets, $webdev_partner_widget_backup );
				
				// Save the sorted array back into the original metaboxes
				$wp_meta_boxes['dashboard']['normal']['core'] = $core_dashboard_widgets;

				// Now we just add "Google Analytics" dashboard widget back on the right
				$wp_meta_boxes['dashboard']['side']['core'] = $side_dashboard_widgets;

				return $wp_dashboard_widgets;

}
#add_filter('wp_dashboard_widgets', 'rearrange_dashboard_widgets' );
    
// RSS Dashboard Widget
function webdev_partner_rss_dashboard_widget() {

// get admin- & contact user from cbstdsys setting
	$user_info = get_userdata(1);
#echo '<pre>';var_dump($user_info); echo '</pre>';


	if(function_exists('fetch_feed')) {
		include_once(ABSPATH . WPINC . '/feed.php');               // include the required file
		$feed = fetch_feed( $user_info->user_url );        					// specify the source feed
		$limit = $feed->get_item_quantity(3);                      // specify number of items
		$items = $feed->get_items(0, $limit);                      // create an array of items
	} ?>




	
	<div class="table vcard">
	<p class="sub"><?php _e( 'Contact', 'cb-std-sys' ); ?></p>
	
		 <div class="org"><?php #echo $user_info->; ?></div>
		 <a class="url fn n" href="<?php echo $user_info->user_url; ?>">
		  <span class="given-name"><?php echo $user_info->user_firstname; ?></span>
		  <span class="family-name"><?php echo $user_info->user_lastname; ?></span>
		 </a>
		 <div class="tel"><?php #echo $user_info->; ?></div>
		 <div class="e-mail">
		     <a href="mailto:<?php echo $user_info->user_email; ?>" class="email" ><?php echo $user_info->user_email; ?></a>
		 </div>
		 
	</div>
	
	<div class="table rss">
	<p class="sub"><?php _e( 'News', 'cb-std-sys' ); ?></p>



<?php
	if ($limit == 0) echo '<p class="error">'. __('The RSS Feed is either empty or unavailable.', 'cb-std-sys' ) .'</p>';   // fallback message
	else foreach ($items as $item) : ?>

			<h4 style="margin-bottom: 0;">
				<a href="<?php echo $item->get_permalink(); ?>" title="<?php echo $item->get_date('j F Y @ g:i a'); ?>" target="_blank">
					<?php echo $item->get_title(); ?>
					<time class="howto"><?php echo $item->get_date('j.m.Y'); ?></time>
				</a>
			</h4>
			<p style="margin-top: 0.5em;">
				<?php echo substr($item->get_description(), 0, 200); ?> [&hellip;]
			</p>
	<?php endforeach; ?>
	</div>
	
 <div class="aside"><?php echo $user_info->user_description; ?></div>
<?php
}









    /**
     *  server monitoring as dashboard widget
     *  reads php_error.log or debug.log if WP_DEBUG is TRUE
     *  
     *  @since    0.0.1
     *  
     */                              
    function cbstdsys_phperror_log_dashboard_widget() {

				//check for WP_DEBUG
				if ( defined("WP_DEBUG") && constant("WP_DEBUG") ) {
						$logfile = WP_CONTENT_DIR.'/debug.log'; // Enter the server path to your logs file here
				} else {
						$logfile = WP_CONTENT_DIR.'/php_error.log'; // Enter the server path to your logs file here
				}
				
				// The maximum number of errors to display in the widget
      	$displayErrorsLimit = 100;
      	
      	// The maximum number of characters to display for each error
      	$errorLengthLimit = 300;
      	
      	$fileCleared = false;
      	$userCanClearLog = current_user_can( 'manage_options' );
      	// Clear file?
      	if ( $userCanClearLog && isset( $_GET["cbstdsys-php-errorlog"] ) && $_GET["cbstdsys-php-errorlog"]=="clear" ) {
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
          			if ( $userCanClearLog ) echo ' [ <b><a href="'.admin_url('?cbstdsys-php-errorlog=clear').'" onclick="return;">CLEAR LOG FILE</a></b> ]';
          			echo '</p>';
          			echo '<div id="cbstdsys-php-errorlog" style="height:250px;overflow:scroll;padding:2px;background-color:#faf9f7;border:1px solid #ccc;">';
          			echo '<ol style="padding:0;margin:0;">';
          			$i = 0;
          			foreach ( $errors as $error ) {
            				echo '<li style="padding:2px 4px 6px;border-bottom:1px solid #ececec;">';
            				$errorOutput = preg_replace( '/\[([^\]]+)\]/', '<b>[$1]</b>', $error, 1 );
            				if ( strlen( $errorOutput ) > $errorLengthLimit ) {
	            					 $errorOutput = substr( $errorOutput, 0, $errorLengthLimit ).' [&hellip;]';
	            					 echo balanceTags( $errorOutput, true );
            				} else {
            					 	echo balanceTags( $errorOutput, true );
            				}
            				echo '</li>';
            				$i++;
            				if ( $i > $displayErrorsLimit ) {
              					echo '<li class="howto">More than '.$displayErrorsLimit.' errors in log...</li>';
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
    function cbstdsys_add_phperror_log_dashboard_widget() {
				//check for WP_DEBUG
				if ( defined("WP_DEBUG") && constant("WP_DEBUG") ) {
        		wp_add_dashboard_widget( 'cbstdsys-php-errorlog', 'Debug Log (/wp-content/debug.log)', 'cbstdsys_phperror_log_dashboard_widget' );
				} else {
        		wp_add_dashboard_widget( 'cbstdsys-php-errorlog', 'Error Log (/wp-content/php_error.log)', 'cbstdsys_phperror_log_dashboard_widget' );
				}
    }
    if ( in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) )
        add_action( 'wp_dashboard_setup', 'cbstdsys_add_phperror_log_dashboard_widget' );



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
#echo '<pre>';var_dump($submenu);echo '</pre>';
        // Remove 'Posts'
        if ( ! cbstdsys_opts('m_blog') )
        unset($menu[multidimensional_search( $menu, array( 2 =>'edit.php') )]);

				// Remove 'Links'.
        if ( ! cbstdsys_opts('m_links') )
        unset($menu[multidimensional_search( $menu, array( 2 =>'link-manager.php') )]);
        
        // Remove 'Comments'. 
        if ( ! cbstdsys_opts('m_comments') )
        unset($menu[multidimensional_search( $menu, array( 2 =>'edit-comments.php') )]);

        // Remove Tags
        if ( ! cbstdsys_opts('m_tags') )
        unset($submenu['edit.php'][multidimensional_search( $submenu['edit.php'], array( 2 =>'edit-tags.php?taxonomy=post_tag') )]);

				// Removes 'Widgets'.
        if ( cbstdsys_opts('m_widgets') == 'not' ) {
        		unset($submenu['themes.php'][multidimensional_search( $submenu['themes.php'], array( 2 =>'widgets.php') )]);
				} elseif ( cbstdsys_opts('m_widgets') == 'admins' && !in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) ) {
						unset($submenu['themes.php'][multidimensional_search( $submenu['themes.php'], array( 2 =>'widgets.php') )]);
        } else {
            // get the the role object
            $role_object = get_role( apply_filters( 'cbstdsys_define_role_to_change_themeoptions', 'editor' ) );
            // add $cap capability to this role object
            $role_object->add_cap( 'edit_theme_options' );

				}

        // Show 'Menu' to Editors also
        if ( current_theme_supports( 'menus' ) ) {
            // get the the role object
            $role_object = get_role( apply_filters( 'cbstdsys_define_role_to_change_themeoptions', 'editor' ) );
            // add $cap capability to this role object
            $role_object->add_cap( 'edit_theme_options' );
        }

        // Removes 'Background'.
        if ( ! cbstdsys_opts('d_bg_images') )
        unset($submenu['themes.php'][multidimensional_search( $submenu['themes.php'], array( 2 =>'custom-background') )]);

				// Removes 'Header Images'.
        if ( ! cbstdsys_opts('d_header_images') )
        unset($submenu['themes.php'][multidimensional_search( $submenu['themes.php'], array( 2 =>'custom-header') )]);

        // Remove for non-Admins
        if ( !in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) ) {

            // Remove 'Plugins'.       
        		unset($menu[multidimensional_search( $menu, array( 2 =>'plugins.php') )]);

						// Remove 'Tools' or 'Werkzeuge'.	
        		unset($menu[multidimensional_search( $menu, array( 2 =>'tools.php') )]);

						// Remove Settings
        		unset($menu[multidimensional_search( $menu, array( 2 =>'options-general.php') )]);
						
            // Remove CF7 'Formular'.
        		unset($menu[multidimensional_search( $menu, array( 2 =>'wpcf7') )]);
							
            // Remove 'BackWPup'.
        		unset($menu[multidimensional_search( $menu, array( 2 =>'backwpup') )]);
            
            // Removes 'Updates' and duplicated submenu "Home"
            if ( current_user_can('manage_options') ) {
                unset($submenu['index.php'][multidimensional_search( $submenu['index.php'], array( 2 =>'update-core.php') )]);
                unset($submenu['index.php'][multidimensional_search( $submenu['index.php'], array( 2 =>'index.php') )]);
            }
            
            // Removes 'Themes'.
            unset($submenu['themes.php'][multidimensional_search( $submenu['themes.php'], array( 2 =>'themes.php') )]);

						// Removes 'Editor'.
            unset($submenu['themes.php'][multidimensional_search( $submenu['themes.php'], array( 2 =>'theme-editor.php') )]);
        }
    }
    add_action('admin_init', 'remove_menus');
    
    
    
    /**
     *  warning users, not to change anything on several settings-pages
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
    function remove_meta_boxes() {
        global $current_user;
        
				// Remove all Comments
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

				// Remove only Comments for Pages
				if( defined('CBSTDSYS_DISABLE_PAGE_COMMENTS') ) {
      			remove_meta_box( 'commentstatusdiv' , 'page' , 'normal' );
          	remove_meta_box( 'commentsdiv' , 'page' , 'normal' );
          	remove_meta_box( 'trackbacksdiv' , 'page' , 'normal' );
				}
				
      	// Titelform
      	// div#slugdiv just hidden with css, to keep functionality
      	
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
      	
        // Artikelbild
       	if ( ! current_theme_supports( 'post-thumbnails' ) ) {     	
            remove_meta_box( 'postimagediv' , 'post' , 'normal' ); 
          	remove_meta_box( 'postimagediv' , 'page' , 'normal' );
      	}
        // post formats metabox only, if there is more than one post format
        $post_formats = get_theme_support( 'post-formats' );
      	if ( ( current_theme_supports( 'post-formats' ) &&  count( $post_formats, COUNT_RECURSIVE ) <= 1 )
							|| ! current_theme_supports( 'post-formats' ) ) {
          	remove_meta_box( 'formatdiv' , 'post' , 'normal' );
          	remove_meta_box( 'formatdiv' , 'page' , 'normal' );
      	}
      	// Tags
      	if ( ! cbstdsys_opts('m_tags') ) {
          	remove_meta_box( 'tagsdiv-post_tag' , 'post' , 'side' ); 
          	remove_meta_box( 'tagsdiv-post_tag' , 'page' , 'side' );
      	}
      	// Categories
      	if ( ! cbstdsys_opts('m_blog') ) {
		      	remove_meta_box( 'categorydiv' , 'post' , 'normal' );
		      	remove_meta_box( 'categorydiv' , 'page' , 'normal' );
            remove_meta_box( 'add-category', 'nav-menus', 'side');
				}
      	
      	// "Einrichtung des mehrsprachigen Inhalts"
      	remove_meta_box( 'icl_div' , 'post' , 'advanced' );
      	remove_meta_box( 'icl_div_config' , 'post' , 'normal' );

				// Anordnung im Theme - >> Design >> Menüs
      	if ( !in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) ) {
       			remove_meta_box('nav-menu-theme-locations', 'nav-menus', 'side');
       	}
    }
    add_action( 'admin_head' , 'remove_meta_boxes' );



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
		
		
		
		/**
		 *  remove unneccessary widgets
		 *
		 *  @since  0.1.8
		 */
		function remove_unused_widgets(){

		 	if ( ! cbstdsys_opts('m_comments') ) 
				  unregister_widget( 'WP_Widget_Recent_Comments' );

		  if ( ! cbstdsys_opts('m_blog') ) {
					unregister_widget( 'WP_Widget_Categories' );
					unregister_widget( 'WP_Widget_Recent_Posts' );
		  }

		  if ( ! cbstdsys_opts('m_tags') ) 
					unregister_widget( 'WP_Widget_Tag_Cloud' );

		 	if ( ! cbstdsys_opts('m_search') ) 
		  		unregister_widget( 'WP_Widget_Search' );
      
      if ( ! cbstdsys_opts('m_links') )      
		  		unregister_widget( 'WP_Widget_Links' );
          
			// disable in all situations
			unregister_widget( 'WP_Widget_Meta' );

		}
		add_action( 'widgets_init', 'remove_unused_widgets', 1 );
		
		
		
    /**
     *  Adding Excerpts to Pages
     *
     *  @since    0.0.1
     *
     */
    function add_excerpts_to_pages ( ) {
        add_post_type_support('page', array('excerpt') );
    }
    add_action( 'init', 'add_excerpts_to_pages' );



    /**
     *  change wp-admin left footer text
     *  
     *  @since    0.0.2
     *
     *  @todo     add option for support infos, like name & email
     *  
     */                    
    function change_footer_admin ( ) {
        echo __('You\'re working with','cb-std-sys')." <a href='http://www.wordpress.org' target='_blank'>WordPress</a> \n";
        echo __('maintained &amp; designed by','cb-std-sys')." <a href='http://carsten-bach.de' target='_blank'>".cbstdsys_opts('a_admin_name')."</a>\n";
    }
    add_filter( 'admin_footer_text', 'change_footer_admin', 9999);
    
    
    
    /**
     *  hide WP Version to clients and only show cbstdsys-Version
     *  
     *  @since    0.0.2
     *  
     */                            
    function hide_version_admin ( $default ) {
        global $current_user, $wp_version;
        if ( !in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) ) {
            return 'CB-STD-SYS Version '.CB_STD_SYS_VERSION;
        } else {
            return 'WP-'.$default.' ('.$wp_version.') &amp;  CB-STD-SYS Version <a href="'. admin_url( 'theme-editor.php?file=/themes/cb-std-sys/inc/changelog.php&theme=cb-std-sys&dir=theme' ) . '">'.CB_STD_SYS_VERSION.'</a>';            
        }
    }
    add_filter( 'update_footer', 'hide_version_admin', 9999 );
    
    
    
    /**
     *  add own default gravatar
     *  
     *  @since    0.0.2
     *  
     */                     
    function cbstdsys_avatar_defaults ($avatar_defaults) {
        $avatar = get_stylesheet_directory_uri() . '/img/def-gravatar.gif';
        $avatar_defaults[$avatar] = get_bloginfo( 'name' )." ".__('Default Gravatar','cb-std-sys')." (".$avatar.")";
        return $avatar_defaults;
    }
    add_filter( 'avatar_defaults', 'cbstdsys_avatar_defaults' );
        


    /**
     *  disable update-info on dashboard for non super-admins
     *  
     *  also needs mu-plugin/no-update-nag.php to hide the "admin_notice"     
     *  
     *  @since  	0.0.6
     *
     *  @todo     add option for support infos, like name & email
     */
		function update_info_for_all_users() {

				global $pagenow, $current_user;

				// only non super-admins
				if ( !in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) ) {
						$plugin_update_count = $theme_update_count = $wordpress_update_count = 0;

						// check for plugin updates
						$update_plugins = get_site_transient( 'update_plugins' );
						if ( ! empty( $update_plugins->response ) )
							$plugin_update_count = count( $update_plugins->response );

						// check for theme-updates
						#$update_themes = get_site_transient( 'update_themes' );
						#if ( !empty($update_themes->response) )
						#	$theme_update_count = count( $update_themes->response );

						// check for core-updates
						$update_wordpress = get_core_updates( array('dismissed' => false) );
						if ( !empty($update_wordpress) && !in_array( $update_wordpress[0]->response, array('development', 'latest') ) && current_user_can('update_core') )
							$wordpress_update_count = 1;

						// count all together
						$total_update_count = $plugin_update_count + $theme_update_count + $wordpress_update_count;

						// print admin-notice with ready-to-sent mailto:link
						if ( $pagenow == 'index.php' && $total_update_count >= 5 && current_user_can( 'add_users' ) ) {
						      $mailbody	= _x('Hello','Update-Info Mail Body', 'cb-std-sys')." Carsten"."\n".__( 'Could you do some updating for me soon? ', 'cb-std-sys' )."\n\n".__( 'Best Regards', 'cb-std-sys' )." ".$current_user->first_name." ".$current_user->last_name;
						      echo '<div class="updated"><p>'.sprintf( __('Some components need an update. Contact %s, then the things will get done! ','cb-std-sys'), '<a href="mailto:Carsten Bach <'.get_option('admin_email').'>?subject='.$_SERVER['HTTP_HOST'].' '.__( 'needs some updates', 'cb-std-sys' ).'&body='.rawurlencode( $mailbody ).'">Carsten</a>').'</p></div>';
						}

		        // disable core updates
		        add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
		        add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );

						// disable plugin updates
		        add_action( 'admin_menu', create_function( '$a', "remove_action( 'load-plugins.php', 'wp_update_plugins' );" ) );
		        add_action( 'admin_init', create_function( '$a', "remove_action( 'admin_init', 'wp_update_plugins' );" ), 2 );
		        add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_update_plugins' );" ), 2 );
		        add_filter( 'pre_option_update_plugins', create_function( '$a', "return null;" ) );
		        add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );
				}
		}
		add_action( 'admin_notices', 'update_info_for_all_users' );



    /**
     *  add admin stylesheet and scripts
     *  
     *  @since    0.0.2
     *  
     */
		function admin_css () {
		    wp_enqueue_style( 'cb_std_sys_admin_styles',  '/css/cb_std_sys_admin.css', false, CB_STD_SYS_VERSION );
		}
		add_action( 'admin_head','admin_css' );
		
    
    /**
     *  add admin stylesheet and scripts
     *  
     *  @since    0.0.2
     *  
     */
		function admin_js () {
		    wp_enqueue_script( 'cb_std_sys_admin_js',  '/js/cbstdsys_admin.js', array('jquery'), CB_STD_SYS_VERSION, true );
		}
		add_action( 'admin_enqueue_scripts','admin_js' );  
    
    
      
		/**
		 *  Add jQuery PHP vars to be used in cb-std-sys-admin.js
		 *
		 *  @since  0.2.1
		 */
		function admin_js_vars ( ) {
        
        $admin_js_vars = array(
          'excerpt_length'      => apply_filters( 'excerpt_length', 55 ),
          'i18n_wordcount'     =>__( 'Word count:', 'cb-std-sys' ),
        );
        
	 			wp_localize_script( 'cb_std_sys_admin_js', 'cbstdsys_admin_js_vars', $admin_js_vars );
		}
		add_action( 'admin_print_scripts', 'admin_js_vars' );		
    
    
		/**
		 *  Add jQuery Quicktags to textwidgets to make editing easier
		 *
		 *  @since  0.2.0
		 */
		function jq_qtags_js ( ) {
				/**
         *  Translations for jQuery Quicktags
         *  @since    0.1.4
         */
        $jq_qtags_i18n = array(
          'strong'      => __( 'Tag', 'cb-std-sys' ),
          'em'      		=> __( 'Important', 'cb-std-sys' ),
          's'      			=> __( 'Deleted', 'cb-std-sys' ),
          'url'      		=> __( 'Link', 'cb-std-sys' ),
          'urlhref'			=> __( 'URL-Adress', 'cb-std-sys' ),
          'code'      	=> __( 'Code', 'cb-std-sys' ),
          'quote'      	=> __( 'Cite', 'cb-std-sys' ),
          'quotecite'  	=> __( 'Source', 'cb-std-sys' )
        );
        
				wp_enqueue_script( 'jq_qtags', '/js/libs/jquery-qtags/jquery.qtags-1.2.js', array('jquery'), CB_STD_SYS_VERSION, true );
	 			wp_localize_script( 'jq_qtags', 'jq_qtags', $jq_qtags_i18n );
		}
		add_action( 'admin_print_scripts-widgets.php', 'jq_qtags_js' );
		
		
		
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
    if ( WPLANG == 'en_EN' ) {
		    add_filter(  'gettext',  'change_post_to_article'  );
		    add_filter(  'ngettext',  'change_post_to_article'  );
    }



    /**
     *  Change columns of pages- and post-Listing
     *  
     *  @since    0.0.6
     */      
    function cbstdsys_column_init() {
    
        // columns for Posts
        add_filter( 'manage_posts_columns', 'cbstdsys_manage_columns' );
        
        // columns for Pages
        add_filter( 'manage_pages_columns', 'cbstdsys_manage_columns' );    

    }
    add_action( 'admin_init' , 'cbstdsys_column_init' );    
    


    /**
     *
     *  Add or Remove columns to posts- and pages-listings 
     *  
     *  @since  	0.0.6
     *  @param    array   Columns with key and i18n description
     *  @return   array   new cloumns          
     *  
     */          
    function cbstdsys_manage_columns ( $cols ) {
        if ( current_theme_supports( 'post-thumbnails' ) ) {  
            $cols['thumbnail'] = __('Image');
        }
        
        if ( ! cbstdsys_opts('m_tags') ) {
           unset( $cols['tags'] );
        }
        
        if ( ! cbstdsys_opts( 'm_multiauthor' ) ) {
           unset( $cols['author'] );        
        }
        
        
        return $cols;
    }
    
    
    
    /**
     *  Show post- or page-thumbnails in our new columns
     *
     *  @since    0.0.6
     */
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
		add_filter( 'get_image_tag', 'add_alt_attr_if_is_empty_for_image_tag', 10, 4 );



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
        			// trim all pathes to make sure, we don't have any specials chars arround
				rename( trim( $large_image_location ), trim( $uploaded_image_location ) );

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
		 * 	Modify the output of post statuses on post-list
		 *
		 * 	Allows for fine-grained control of styles and targeting protected posts, written
		 *
		 * 	@since  	0.1.8
		 *  @author   Pete Mall
		 *  @source 	http://wordpress.org/support/topic/plugin-ui-labs-classes-translated?replies=9#post-2661259
		 */
		function labs_display_post_states( $post_states ) {
		   $post_state = array();
       foreach ( $post_states as $post_state_key => $post_state_label )
		       $post_state[] = '<span class="' . strtolower( str_replace( ' ', '-', $post_state_key ) ) . '">' . $post_state_label . '</span>';
		   return $post_state;
		}
		add_filter( 'display_post_states', 'labs_display_post_states' );



		/**
		 *  Show hidden metaboxes on the edit screen by default
		 *
		 *  Removes custom fields from the default hidden elements,
		 *  normally set via 'Screen-Options-Tab' in the upper right corner
		 *
		 *  @since   	0.1.9
		 *
		 * 	The original ( wp-admin/includes/template.php#get_hidden_meta_boxes() ):
		 * 	array(
		 *      'slugdiv',
		 *      'trackbacksdiv',
		 *      'postcustom',      <-- we need this
		 *      'postexcerpt',
		 *      'commentstatusdiv',
		 *      'commentsdiv',
		 *      'authordiv',
		 *      'revisionsdiv'
		 * 	)
		 *
		 * It has no effect if the user has decided to hide the box.
		 * This option is saved in "metaboxhidden_{$screen->id}"
		 *
		 * 	@param  	array 	$hidden
		 * 	@return 	array 	$hidden
		 *
		 *  @author  	Thomas Scholz
		 *  @url      http://toscho.de
		 *  @version 	1.0
		 *  @required 3.1
		 *  @license  GPL
		 */
		function enable_custom_fields_per_default ( $hidden ) {
		    foreach ( $hidden as $i => $metabox ) {
		        if ( 'postexcerpt' == $metabox )
		        {
		            unset ( $hidden[$i] );
		        }
		    }
		    return $hidden;
		}
		add_filter( 'hidden_meta_boxes', 'enable_custom_fields_per_default', 20, 1 );



		/**
		 *  hide new "Welcome to WP..." Dashboard Panel
		 *  updates the user_setting to "dismiss"-state
		 *
		 *  @source   http://wordpress.stackexchange.com/a/36404  |   http://wordpress.stackexchange.com/a/13748
		 *  @since    0.1.8
		 */
		function remove_welcome_panel($user_id){
				update_user_meta( $user_id, 'show_welcome_panel', 0 );
		}
		add_action( 'user_register','remove_welcome_panel' );



		/**
		 *  Add css-classes to the <body> tag
		 *
		 *  @since    0.2.0
		 *
		 *  @used     in cbstdsys-admin.css & cbstdsys-admin.js
		 * 	@param  	string  $classes
		 * 	@return 	string  $classes
		 */
		function add_admin_body_classes( $classes ) {
				global $current_user;

				// more than one author
				if ( cbstdsys_opts( 'm_multiauthor' ) )
				    $classes .= ' multi-author';

				// are super-admins in tha house
				if ( in_array($current_user->ID, cbstdsys_opts( 'a_admin_user_IDs' ) ) )
				    $classes .= ' super-admin-logged-in';

				return $classes;
		}
		add_filter( 'admin_body_class', 'add_admin_body_classes' );



		/**
		 *  Remove comments columns from pages-listing
		 *
		 *  @since    0.1.8
		 */
		function remove_comment_for_pages_columns($cols) {
				if( defined('CBSTDSYS_DISABLE_PAGE_COMMENTS') )
					  unset($cols['comments']);
			  return $cols;
		}
		add_filter('manage_pages_columns', 'remove_comment_for_pages_columns',11);



/**
 * 	Adds two fields for credits to any media file: name and URL.
 *
 * 	@source http://net.tutsplus.com/?p=13076
 *
 * 	@author "Thomas Scholz"
 *  @uses   /mu-plugins/toscho_mediafile_artistfields.php
 *
 */
$Toscho_Media_Artist = new Toscho_Media_Artist(
    array (
        'artist_name' => array (
            'public' => 'toscho_artist_name'
        ,   'hidden' => '_toscho_artist_name'
        ,   'label'  => 'Fotograf (Name)'
        )
    ,   'artist_url' => array (
            'public' => 'toscho_artist_url'
        ,   'hidden' => '_toscho_artist_url'
        ,   'label'  => 'Fotograf (URL)'
        )
    )
,   'Foto: '
);



    /**
     *  Add CSS class to linked images, inserted via TinyMCE
     *  
     *  @since  0.2.1
     *  @source http://wordpress.stackexchange.com/a/11757
     *       
     *  @see    http://www.island94.org/2011/01/adding-class-to-wordpress-linked-images/          
     */     
    function cbstdsys_add_class_to_linked_images( $html, $attachment_id, $attachment ) {
        
        $css_class_to_add = 'linked-image';
        $linkptrn = '/<a[^>]*>/';
        $found = preg_match($linkptrn, $html, $a_elem);
    
        // If no link, do nothing
        if( $found <= 0 ) return $html;
    
        $a_elem = $a_elem[0];
    
        // Check to see if the link is to an uploaded image
        $is_attachment_link = strstr( $a_elem, get_option( 'upload_url_path' ) );
    
        // If link is to external resource, do nothing
        //if($is_attachment_link === FALSE) return $html;
        
        // If link already has class defined inject it to attribute
        if( strstr( $a_elem, 'class="' ) !== FALSE ) { 
            $a_elem_new = str_replace('class="', 'class="'.$css_class_to_add.' ', $a_elem);
            $html = str_replace($a_elem, $a_elem_new, $html);
        // If no class defined, just add class attribute
        } else { 
            $html = str_replace('<a ', '<a class="'.$css_class_to_add.' "', $html);
        }
    
        return $html;
    }
    add_filter( 'image_send_to_editor', 'cbstdsys_add_class_to_linked_images', 10, 3 );

?>