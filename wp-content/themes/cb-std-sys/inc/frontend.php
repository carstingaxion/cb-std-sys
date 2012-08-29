<?php

    /**
     *  show default index page until Live-Launch
     *
     *  @since    0.0.6
     *
     */
    function default_index_html() {
        include_once $_SERVER['DOCUMENT_ROOT'].get_stylesheet_directory_uri().'/defaultindex.php';   die();
    }
    if ( cbstdsys_opts('a_default_index') && !is_user_logged_in()  ) {
        add_action('get_header', 'default_index_html');
    }



    /**
     *  Change the frontend output on debugging
     *
     *  1. Show all defined constants in HTML comments
     *  2. switch ON maintenance mode for normal users in case we're Live
     *  
     *  @since    0.0.3
     *  
     */                     
    function maintenance_mode() {
        global $current_user;
	      if ( WP_DEBUG === true ) {
	        if ( is_user_logged_in() ) {
	          print('<!--');
	          print_r( get_defined_constants() );
	          print('-->');
	        }
	        if ( !in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) && constant( 'WP_LOCAL_DEV' ) === false )
	        include_once WP_CONTENT_DIR.'/maintenance.php';
	      }
    }
    add_action( 'get_header', 'maintenance_mode', 1 );
    


		/**
		 *  Rename Login URL to match /redaktion
		 *  for security reasons
		 *
		 *  if you want to change, DO NOT FORGET to change it in the .htaccess also
		 *
		 *  @since    0.0.2
		 *  
		 *  @todo     fix for : after login the site from http://domain.tld/redaktion is redirected to /redaktion (without protocol and domain)          

		function wplogin_filter( $url ){
				$old  = array( "/^.+?\/wp-login.php/");
				$new  = array( "redaktion");
				$new  = apply_filters( 'cbstdsys_login_url', $new );
				return preg_replace( $old, $new, $url, 1 );
		}
		add_filter( 'login_url', 'wplogin_filter', 10 );
		add_filter( 'logout_url' , 'wplogin_filter' , 10 );

		function wplogin_filter_odd( $url, $path, $orig_scheme ){
				$old  = array( "/^.+?\/wp-login.php/");
				$new  = array( "redaktion");
				$new  = apply_filters( 'cbstdsys_login_url', $new );
				return preg_replace( $old, $new, $url, 1 );
		}
		add_filter( 'site_url', 'wplogin_filter', 10, 3 );
*/


    /**
     *  remove version info from head and feeds for security reasons
     *  
     *  @since    0.0.2
     *  
     */                    
    add_filter('the_generator',create_function('$a', "return null;"));
    
    
    
    /**
     *  add image and icons to RSS and ATOM feeds
     *  
     *  @since    0.0.7
     *  
     */
    function add_rss_image() {
        echo "<image>\n<title>". get_bloginfo_rss("name").get_wp_title_rss() ." Logo</title>\n";
        echo "<url>". WP_HOME ."/apple-touch-icon-114x114-precomposed.png</url>\n";
        echo "<link>". WP_HOME ."</link>\n";
        echo "<width>114</width><height>114</height>\n";
        echo "<description>". get_bloginfo_rss("description") ."</description>\n</image>\n";
    }
    add_action('rss2_head','add_rss_image');
    add_action('rss_head','add_rss_image');
    add_action('commentsrss2_head','add_rss_image');  

    function add_atom_image() {
        echo "<icon>". WP_HOME ."/favicon.ico</icon>\n";
        echo "<logo>". WP_HOME ."/apple-touch-icon-114x114-precomposed.png</logo>\n";
    }
    add_action('atom_head','add_atom_image');     



    /**
     *  add copyright note to rss and atom feeds
     *  
     *  @since    0.0.7
     *                 
     */
     function add_copyright_to_rss_atom(){
        echo "<copyright>".$cb_std_sys_copyright = strip_tags( cb_std_sys_copyright() )."</copyright>\n";
     }           
    add_action('rss2_head','add_copyright_to_rss_atom');
    add_action('rss_head','add_copyright_to_rss_atom');
    add_action('commentsrss2_head','add_copyright_to_rss_atom');  
    add_action('atom_head','add_copyright_to_rss_atom');    
    

    /**
     *  clean wp_head output from unused default stuff
     *
     *  @since  0.0.1
     *             
     */
    remove_action( 'wp_head' , 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
    remove_action( 'wp_head' , 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
    remove_action( 'wp_head' , 'index_rel_link' ); // index link
    remove_action( 'wp_head' , 'parent_post_rel_link', 10, 0 ); // prev link
    remove_action( 'wp_head' , 'start_post_rel_link', 10, 0 ); // start link
    remove_action( 'wp_head' , 'adjacent_posts_rel_link_wp_head', 10, 0 ); // Display relational links for the posts adjacent to the current post.
    remove_action( 'wp_head' , 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version

    
  
    
    /**
     *  change language of all feeds ouput    
     *  
     *  @since    0.0.5
     *  
     */                    
    function change_rss_lang(){
        if (  cbstdsys_opts('m_multi_lang')  ) {
            return ICL_LANGUAGE_CODE;
        } else {
            return substr( WPLANG, 0, 2 );
        }
    }   
    add_filter('pre_option_rss_language', 'change_rss_lang');        
       
    

    
    
    /**
     *  get meta-value for author
     *  
     *  @since    0.0.1
     *  
     */                    
    function cb_std_sys_get_page_author_meta() {
        if ( is_singular() || is_feed() ) {
            global $post;
            $cur_author = get_userdata($post->post_author);
            if ( $cur_author->user_firstname != '' && $cur_author->user_lastname != '' && !in_array($post->post_author, cbstdsys_opts('a_admin_user_IDs') ) ) {
                return $cur_author->user_firstname." ".$cur_author->user_lastname;       
            } else {
                return cbstdsys_opts('c_main_author');
            }
        } else {
            return cbstdsys_opts('c_main_author');
        }
    }
    
    
    
    /**
     *  filter the_author()
     *  
     *  User Postauthors First & Lastname or the default Author
     *  but not, 'carsten' or a similar admins name
     *  
     *  @since  0.0.6
     *  
     *  @todo : auskommentiert und keine Ahnung warum, Fehler war (glaube ich) bei die-wo-spielen.de
     */                                       
    function filter_the_author( $author ) {
        $author = cb_std_sys_get_page_author_meta();
        return $author;
    }
    #add_filter('the_author', 'filter_the_author');
    
    
    
    /**
     *  create copyright YEAR-Info from real published posts, pages and CPTs
     *  take first year from Options-Page or from realy first post
     *  
     *  @since    0.0.5
     *  
     */                         
    function cb_std_sys_copyright() {
        global $wpdb;
        $copyright_dates = $wpdb->get_results("
                            SELECT YEAR(min(post_date_gmt)) AS firstdate, YEAR(max(post_date_gmt)) AS lastdate
                            FROM $wpdb->posts WHERE post_status = 'publish'");
        if( $copyright_dates ) {
            $copyright = $copyright_dates[0]->firstdate;
            if ( cbstdsys_opts('c_first_pub') !='' )
            		$copyright = cbstdsys_opts('c_first_pub');

						if( $copyright != $copyright_dates[0]->lastdate )
                $copyright .= '-' . $copyright_dates[0]->lastdate;

        }
        return '<span class="copy-time">' .$copyright. '</span> '.apply_filters( 'cb_std_sys_copyright', get_bloginfo("name").". ".__('All rights reserved', 'cb-std-sys')."." );
    } 
    

		function cb_std_sys_copyright_link () {
				// set default
				$link  =	'<a class="copy-note" '.
										 'href="'. get_home_url( '/' ) .'" '.
										 'title="'. esc_attr( strip_tags( get_bloginfo( 'name', 'display' ) ) ).'" '.
										 'rel="home">'.
										 '&copy; '. cb_std_sys_copyright().
										 '</a>';

				// apply filter and return
				return apply_filters( 'cb_std_sys_copyright_link', $link );
		}

        
    /**
     *  add google-analytics script section to footer,
     *  when tracking-code is defined via Options-Page
     *  
     *  @since    0.0.6
     *  
     */                    
    function add_google_analytics_section () { ?>
  <script>var _gaq=[["_setAccount","UA-<?php echo cbstdsys_opts('m_ga_tracking'); ?>"],["_trackPageview"],['_gat._anonymizeIp'],['_trackPageLoadTime'],['_setDomainName("<?php echo str_replace('http://','',WP_SITEURL)?>")']];
  setTimeout('_gaq.push([\'_trackEvent\', \'NoBounce\', \'Over 10 seconds\'])',10000);
  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
  g.src=("https:"==location.protocol?"//ssl":"//www")+".google-analytics.com/ga.js";
  s.parentNode.insertBefore(g,s)}(document,"script"));</script><?php
    }
    if ( cbstdsys_opts('m_ga_tracking') && !current_user_can('publish_posts') )
      add_action( 'wp_footer', 'add_google_analytics_section' );
    
    
    
    /**
     *  add semantic css-classes in wp body_class
     *  
     *  @since    0.0.2
     *  
     */             
    function add_body_classes($classes) {
        global $current_user, $post, $is_lynx, $is_gecko, $is_opera, $is_NS4, $is_safari, $is_winIE, $is_macIE, $is_chrome, $is_iphone;
        
      	if($is_lynx)       $classes[]  = 'lynx';
      	elseif($is_gecko)  $classes[]  = 'gecko';
      	elseif($is_opera)  $classes[]  = 'opera';
      	elseif($is_NS4)    $classes[]  = 'ns4';
      	elseif($is_safari) $classes[]  = 'safari';
      	elseif($is_winIE)  $classes[]  = 'ie-win';
      	elseif($is_macIE)  $classes[]  = 'ie-mac';
      	elseif($is_chrome) $classes[]  = 'chrome';
      	elseif($is_iphone) $classes[]  = 'iphone';
      	else{} // do nothing
        
      	// Add a super-admin Class if some 'super-admin' is logged in
        if ( in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) )
        $classes[] = 'superadmin-logged-in';      
        
        // Applies the time- and date-based classes (below) to BODY element
      	good_old_sandbox_date_classes( time(), $classes );
        
        if ( is_singular() ) {
            $classes[] = 'singular';
            	
            // category nicenames
            foreach((get_the_category($post->ID)) as $category)
                $classes[] = 'cat-'.$category->category_nicename;
        		// tag classes for each tags
        		if ( $tags = get_the_tags() )
        			foreach ( $tags as $tag )
        				$classes[] = 'tag-' . $tag->slug;
            // get the author
        		$classes[] = 'author-' . sanitize_title_with_dashes( strtolower( cb_std_sys_get_page_author_meta() ) );  
            // Applies the time- and date-based classes
            good_old_sandbox_date_classes( mysql2date( 'U', $post->post_date ), $classes, 's-' );				
        }
        
        // Add parent page slug
        // http://wordpress.stackexchange.com/questions/1567/best-collection-of-code-for-your-functions-php-file/9990#9990
		    if (is_page()) {
		        if ($post->post_parent) {
		            $parent  = end(get_post_ancestors($current_page_id));
		        } else {
		            $parent = $post->ID;
		        }
		        $post_data = get_post($parent, ARRAY_A);
		        $classes[] = 'section-' . $post_data['post_name'];
		    }
		    
        if ( is_404() ) {
                $classes[] = 'page';
        }
        
        // add hAtom base class
        $classes[] = 'hfeed';
        
				return $classes;
    }
    add_filter( 'body_class', 'add_body_classes' );
    
    
    
    /**
     *  add semantic css-classes in wp post_class
     *  
     *  @since    0.0.2
     *  
     */             
    function add_post_classes($classes) {
        global $post, $sandbox_post_alt;
      	// hentry for hAtom compliace, gets 'alt' for every other post DIV, describes the post type and p[n]
      	$classes[] = "p$sandbox_post_alt";
				// sticky posts
				if ( is_sticky( $post->ID ) )
      	$classes[] = 'sticky';
      	// if post has thumbnail attached
      	if( has_post_thumbnail( $post->ID ) )
				$classes[] = 'has-thumb';
				// Title for the post queried
      	$classes[] = 't-'. sanitize_title( get_the_title()  );
      	// Author for the post queried
      	$classes[] = 'author-' . sanitize_title_with_dashes( strtolower( cb_std_sys_get_page_author_meta() ) );  
        // Applies the time- and date-based classes 
      	good_old_sandbox_date_classes( mysql2date( 'U', $post->post_date ), $classes );
      	// If it's the other to the every, then add 'alt' class
      	if ( ++$sandbox_post_alt % 2 )
      		  $classes[] = 'alt';
        return $classes;
    }
    // Define the num val for 'alt' classes (in post DIV and comment LI)
    $sandbox_post_alt = 1;
    add_filter('post_class', 'add_post_classes');
    
    
    
    /**
     *  add semantic css-classes in comment_class
     *  
     *  @since    0.0.2
     *  
     */              
    function add_comment_classes($classes) {
        global $comment;
    	  good_old_sandbox_date_classes( mysql2date( 'U', $comment->comment_date ), $classes, 'c-' );
        return $classes;
    }
    add_filter('comment_class', 'add_comment_classes');
    
    
    
    /**
     *  Generates time- and date-based CSS-classes for BODY, post DIVs, and comment LIs; relative to GMT (UTC)
     *  
     *  @since    0.0.2
     *  
     */                    
    function good_old_sandbox_date_classes( $t, &$classes, $p = '' ) {
    	$t = $t + ( get_option('gmt_offset') * 3600 );
    	$classes[] = $p . 'y' . gmdate( 'Y', $t ); // Year
    	$classes[] = $p . 'm' . gmdate( 'm', $t ); // Month
    	$classes[] = $p . 'd' . gmdate( 'd', $t ); // Day
    	$classes[] = $p . 'h' . gmdate( 'H', $t ); // Hour
    }
    

    
    /**
     *  add to robots.txt
     *  
     *  @since    0.0.2
     *  
     */                    
    function cbstdsys_robots() {
      	echo "Disallow: /cgi-bin\n";
      	echo "Disallow: /min\n";
      	echo "Disallow: /cache\n";
      	echo "Disallow: /wp-admin\n";
      	echo "Disallow: /wp-includes\n";
      	echo "Disallow: /wp-content/plugins\n";
      	echo "Disallow: /plugins\n";
      	echo "Disallow: /wp-content/cache\n";
      	echo "Disallow: /wp-content/themes\n";
      	echo "Disallow: /css\n";
      	echo "Disallow: /js\n";              	
      	echo "Disallow: /trackback\n";
      	echo "Disallow: /feed\n";
      	echo "Disallow: /comments\n";
      	echo "Disallow: */trackback\n";
      	echo "Disallow: */comments\n";
      	echo "Disallow: /tag\n";
      	echo "Disallow: /*?*\n";
      	echo "Disallow: /*?\n";
      	echo "Allow: /wp-content/uploads\n";
    }
    add_action('do_robots', 'cbstdsys_robots');    
    


    /**
     *  root relative URLs for everything
     *  
     *  @source   http://www.456bereastreet.com/archive/201010/how_to_make_wordpress_urls_root_relative/
     *  @thanks   Scott Walkinshaw (scottwalkinshaw.com)
     *  
     */          
    function cbstdsys_root_relative_url($input) {
        preg_match('/(https?:\/\/[^\/]+)/', $input, $matches);
      	return str_replace(end($matches), '', $input);
    }   
    function filter_everything_then_feeds() {
      	global $wp_query;
      	if (!is_feed()) {
            add_filter('the_permalink', 'cbstdsys_root_relative_url');
            add_filter('page_link','cbstdsys_root_relative_url');
            add_filter('post_link','cbstdsys_root_relative_url');
            add_filter('term_link','cbstdsys_root_relative_url');
            add_filter('tag_link','cbstdsys_root_relative_url');
            add_filter('category_link','cbstdsys_root_relative_url');
            add_filter('post_type_link','cbstdsys_root_relative_url');
            add_filter('year_link','cbstdsys_root_relative_url');
            add_filter('month_link','cbstdsys_root_relative_url');
            add_filter('day_link','cbstdsys_root_relative_url');
            add_filter('search_link','cbstdsys_root_relative_url');
        
            #add_filter('attachment_link','cbstdsys_root_relative_url');    
            #add_filter('the_attachment_link','cbstdsys_root_relative_url');    
            #add_filter('get_attachment_link','cbstdsys_root_relative_url');
            #add_filter('wp_get_attachment_url', 'cbstdsys_root_relative_url');
            #add_filter('wp_get_attachment_link', 'cbstdsys_root_relative_url');
                
            add_filter('feed_link','cbstdsys_root_relative_url');
            add_filter('post_comments_feed_link','cbstdsys_root_relative_url');
            add_filter('author_feed_link','cbstdsys_root_relative_url');
            add_filter('category_feed_link','cbstdsys_root_relative_url');
            add_filter('taxonomy_feed_link','cbstdsys_root_relative_url');
            add_filter('search_feed_link','cbstdsys_root_relative_url');
            
            add_filter('get_edit_tag_link','cbstdsys_root_relative_url');
            add_filter('get_edit_post_link','cbstdsys_root_relative_url');
            add_filter('get_delete_post_link','cbstdsys_root_relative_url');
            add_filter('get_edit_comment_link','cbstdsys_root_relative_url');
            add_filter('get_edit_bookmark_link','cbstdsys_root_relative_url');
            
            add_filter('index_rel_link','cbstdsys_root_relative_url');
            add_filter('parent_post_rel_link','cbstdsys_root_relative_url');
            add_filter('previous_post_rel_link','cbstdsys_root_relative_url');
            add_filter('next_post_rel_link','cbstdsys_root_relative_url');
            add_filter('start_post_rel_link','cbstdsys_root_relative_url');
            add_filter('end_post_rel_link','cbstdsys_root_relative_url');
            
            add_filter('previous_post_link','cbstdsys_root_relative_url');
            add_filter('next_post_link','cbstdsys_root_relative_url');
            add_filter('the_content_more_link', 'cbstdsys_root_relative_url');
                
            add_filter('get_pagenum_link','cbstdsys_root_relative_url');
            add_filter('get_comments_pagenum_link','cbstdsys_root_relative_url');
            add_filter('shortcut_link','cbstdsys_root_relative_url');
            add_filter('get_shortlink','cbstdsys_root_relative_url');
            
            #add_filter('home_url','cbstdsys_root_relative_url');
						// removed to make 'Mailchimp-SOCIAL'-Plugin work
						#add_filter('site_url','cbstdsys_root_relative_url');
            add_filter('admin_url','cbstdsys_root_relative_url');
            add_filter('includes_url','cbstdsys_root_relative_url');
            add_filter('content_url','cbstdsys_root_relative_url');
            add_filter('plugins_url','cbstdsys_root_relative_url');
        
            add_filter('bloginfo_url', 'cbstdsys_root_relative_url');
            add_filter('theme_root_uri', 'cbstdsys_root_relative_url');
            add_filter('stylesheet_directory_uri', 'cbstdsys_root_relative_url');
            add_filter('template_directory_uri', 'cbstdsys_root_relative_url');

/*
            add_filter('get_author_posts_url', 'cbstdsys_root_relative_url');

            add_filter('wp_list_pages', 'cbstdsys_root_relative_url');
            add_filter('wp_list_categories', 'cbstdsys_root_relative_url');
            add_filter('wp_nav_menu', 'cbstdsys_root_relative_url');
            
            add_filter('the_tags', 'cbstdsys_root_relative_url');
            add_filter('get_comment_link', 'cbstdsys_root_relative_url');
*/
      	}    
    }  
    add_action('pre_get_posts', 'filter_everything_then_feeds' );




    /** 
     *  clean wp-content/plugins/path for Rewriting
     *  
     *  @since    0.0.3
     *  
     */                        
    function clean_plugins_path($content) {
        $current_path = '/wp-content/plugins';
        $new_path = '/plugins';
        $content = str_replace($current_path, $new_path, $content);
        return $content;
    }
		#if (!class_exists('BWP_MINIFY')) {
		    add_filter('plugins_url', 'clean_plugins_path');
		    add_filter('bloginfo', 'clean_plugins_path');
    #}



    /**
     *  stop WP redirecting miss-spelled URLS to canonical similars
     *  
     *  @since    0.0.4
     *  
     */                      
    remove_filter( 'template_redirect', 'redirect_canonical' );



    /**
     *  remove default CSS for [gallery]-shortcode
     *  
     *  @since    0.0.1
     *  
     */                    
    function remove_gallery_style($css) {
        return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
    }
    add_filter( 'gallery_style', 'remove_gallery_style' );
		add_filter( 'use_default_gallery_style', '__return_false' );
    


    /**
     *  additionaly log 404-errors to php_error.log
     *  
     *  @since    0.0.5
     *  
     */                    
    function log_404() {
        global $is_lynx, $is_gecko, $is_opera, $is_NS4, $is_safari, $is_winIE, $is_macIE, $is_chrome, $is_iphone;

	    	if ( is_404() && get_settings( 'permalink_structure' ) ) {
	    	   
           // Client IP 
           $log_line  = '[client: '. $_SERVER['REMOTE_ADDR'] .'] ';
           
           // Client Browser if is known
           if($is_lynx)       $log_line .= '[lynx] ';
        	 elseif($is_gecko)  $log_line .= '[gecko] ';
        	 elseif($is_opera)  $log_line .= '[opera] ';
        	 elseif($is_NS4)    $log_line .= '[ns4] ';
        	 elseif($is_safari) $log_line .= '[safari] ';
        	 elseif($is_winIE)  $log_line .= '[ie-win] ';
        	 elseif($is_macIE)  $log_line .= '[ie-mac] ';
        	 elseif($is_chrome) $log_line .= '[chrome] ';
        	 elseif($is_iphone) $log_line .= '[iphone] ';
           
           // missing URL
	    		 $log_line .= '[Error 404] : '. $_SERVER['REQUEST_URI'];
	    		 
           // Referer if is given
           if ( isset( $_SERVER['HTTP_REFERER'] ) )
	    		 		$log_line .= ', [HTTP_REFERER : '.$_SERVER['HTTP_REFERER'].']';
              
	    		 error_log( $log_line, 0 );
	    	}
    }
    add_action( 'shutdown', 'log_404' );
    
    
    
    /**
     *  add some additional reccurences to the wp_cron
     *  
     *  @since    0.0.4
     *  
     */                    
    function more_reccurences ( ) {
        return array(
            'weekly'      => array( 'interval' => 604800,  'display' => 'Once Weekly' ),
            'fortnightly' => array( 'interval' => 1209600, 'display' => 'Once Fortnightly' ),
        );
    }
    add_filter('cron_schedules', 'more_reccurences');
    
    
    
    /**
     *  send error log via email to admin, weekly
     *  
     *  @since  0.0.2
     *
     *  @todo   use global support information
     *  
     */                    
    if (!wp_next_scheduled('send_errorlog_to_admin')) {
    	 wp_schedule_event( time(), 'weekly', 'send_errorlog_to_admin' );
    }
    function do_send_errorlog_to_admin() {
        if ( file_get_contents( WP_CONTENT_DIR."/php_error.log" ) != '' )
    	  wp_mail( cbstdsys_opts('a_admin_email'), 'Error Log von "'.get_bloginfo('name').'"', file_get_contents( WP_CONTENT_DIR."/php_error.log" ) );
    }
    add_action( 'send_errorlog_to_admin', 'do_send_errorlog_to_admin' );    
    
    
    
    /**
     *  clean the scheduler
     *  only needed, if something goes wrong
     *  
     *  @since    0.0.4
     *  
     */                         
    function my_task_deactivate() {
		    $timestamp = wp_next_scheduled( 'send_errorlog_to_admin' );
				wp_clear_scheduled_hook( $timestamp, 'send_errorlog_to_admin' );
    }
    #add_action( 'send_errorlog_to_admin', 'my_task_deactivate' );
    

    
    /**
     *  disable Login Errors for security reasons 
     *  
     *  @since    0.0.1
     *  
     */                      
    add_filter('login_errors',create_function('$a', "return null;"));  



    /**
     *  define custom login-header-url
     *  link to website-home, not to WP
     *  
     *  @since    0.0.6
     *                      
     */       
    function cbstdsys_login_headerurl(){
      return WP_HOME;
    }
    add_filter('login_headerurl', 'cbstdsys_login_headerurl');
    
    
    
    /**
     *  replace title-attribute of home-link on login-page
     *  
     *  @since    0.0.6
     *  
     */                        
    function cbstdsys_login_headertitle(){
      return esc_attr( get_bloginfo( 'name', 'display' ) ).' - '.esc_attr__('maintained &amp; designed by','cb-std-sys')." Carsten Bach";
    }
    add_filter( 'login_headertitle', 'cbstdsys_login_headertitle' );

    
    
    /**
     *  get description from menu options and print as <span> inside the <a> 
     *  
     *  @since    0.0.5
     *  
     */                    
    class cbstdsys_Walker extends Walker_Nav_Menu {
    	function start_el(&$output, $item, $depth, $args) {
    		global $wp_query;
    		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
    
    		$class_names = $value = '';
    
    		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
    
    		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
    
    		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . ' class="' .$class_names .'">';
    
    		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
    		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
    		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
    		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
    
    		$item_output = $args->before;
    		$item_output .= '<a'. $attributes .'>';
    		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
    		$item_description = trim( $item->description );
    		if ( !empty( $item_description ) )
        $item_output .= '<span>' . $item_description . '</span>';
    		$item_output .= '</a>';
    		$item_output .= $args->after;
    
    		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    	}
    }



    /**
     *  small typography adjustments to have nice looking ampersands 
     * 
     *  @since    0.0.6
     */          
    function fancy_amp($title) {
      #if(in_the_loop()) {
        $title = preg_replace("@(?![^<]+>)&amp;@", "<span class=\"amp\">&amp;</span>", "$title");
        $title = preg_replace("@(?![^<]+>)&#038;@", "<span class=\"amp\">&#038;</span>", "$title");
      #}
      return $title;
    }
    add_filter('the_title', 'fancy_amp');
    add_filter('widget_title', 'fancy_amp');



    /**
     *  Pagination for all areas
     *
     *  @since    0.0.6  
     */ 
      function contextual_pagination( $context, $position, $in_same_cat = false, $excluded_categories = ''  ) {
   
        	global $wp_query, $wp_rewrite, $post;
        	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
          
          

        	$pagination = array(
        		'base' => @add_query_arg('page','%#%'),
        		'format' => '',
        		'total' => $wp_query->max_num_pages,
        		'current' => $current,
        		'show_all' => false,
        		'end_size'  =>  1,
        		'mid_size'  =>  2,
        		'type' => 'plain',
        		'next_post_cb' => '', // non default array element
        		'prev_post_cb' => '', // non default array element
            );
            
            
        	if ( is_search() ) {
        		$pagination['next_text'] = __( 'Older posts', 'cb-std-sys' ).' <span class="meta-nav">&rarr;</span>';
        		$pagination['prev_text'] = '<span class="meta-nav">&larr;</span> '. __( 'Newer posts', 'cb-std-sys' );
          } elseif ( is_archive() ) {
        		$pagination['next_text'] = __( 'Older posts', 'cb-std-sys' ).' <span class="meta-nav">&rarr;</span>';
        		$pagination['prev_text'] = '<span class="meta-nav">&larr;</span> '. __( 'Newer posts', 'cb-std-sys' );              
          } elseif ( is_singular() ) {
            $prev = get_adjacent_post($in_same_cat, $excluded_categories, true);
            if ($prev) {
        		    $pagination['prev_post_cb'] = '<a href="' . get_permalink($prev) . '" rel="prev" class="prev button">' . _x( '&larr;', 'Previous post link', 'cb-std-sys' ) . ' ' . $prev->post_title .'</a>';
            } 
            $next = get_adjacent_post($in_same_cat, $excluded_categories, false);         
            if ($next) {
        		    $pagination['next_post_cb'] = '<a href="' . get_permalink($next) . '" rel="next" class="next button">' . $next->post_title . ' ' . _x( '&rarr;', 'Next post link', 'cb-std-sys' ) . '</a>';
            }
          } else{
        		$pagination['next_text'] = __( 'Older posts', 'cb-std-sys' ).' <span class="meta-nav">&rarr;</span>';
        		$pagination['prev_text'] = '<span class="meta-nav">&larr;</span> '. __( 'Newer posts', 'cb-std-sys' );
          }
         
        	if( $wp_rewrite->using_permalinks() )
        		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . __('page').'/%#%/', 'paged' );
         
        	if( !empty($wp_query->query_vars['s']) )
        		$pagination['add_args'] = array( 's' => get_query_var( 's' ) );
         
          $paginav   = '<nav id="nav-'.$position.'" class="clearfix pagination pagination-'.$context.'">';
           // is shown on is_singular
          $paginav  .= apply_filters( 'cb_pagination_prev_link', $pagination['prev_post_cb'] );
           // is shown on !is_singular
          if ( function_exists ( 'childtheme_paginate_links' ) ) {
              $paginav  .= childtheme_paginate_links( apply_filters( 'cbstdsys_paginate_links_args', $pagination ) );          
          } else {
              $paginav  .= paginate_links( apply_filters( 'cbstdsys_paginate_links_args', $pagination ) );
          }          
           // is shown on is_singular
          $paginav  .= apply_filters( 'cb_pagination_next_link', $pagination['next_post_cb'] );
          $paginav  .= '</nav>';
        	
          return $paginav;
      }



      /**
       *  Strip width & Height attr from img-tags
       *  to setup the base for responsive webdesign 
       *  
       *  @since    0.1.0       
       *  @source   http://wordpress.org/support/topic/how-do-you-strip-or-filter-attributes-from-a-posts-tags?replies=1#post-1573311
       *  
       */          
      function clean_wp_width_height($string){
      	return preg_replace('/\<(.*?)(width="(.*?)")(.*?)(height="(.*?)")(.*?)\>/i', '<$1$4$7>',$string);
      }
			#add_filter( '', 'clean_wp_width_height' )



			/**
			 *  Remove ugly <br /> tags from [gallery] output
			 *
			 *  @since    0.1.8
			 */
			function remove_gallery_br_tags ( $output ) {
			    return preg_replace('/<br style=(.*)>/mi','',$output);
			}
			add_filter( 'the_content', 'remove_gallery_br_tags', 11, 2);




    /**
     *  Display Performance Statistics for WordPress Pages
     *
     *  @since    0.1.1
     *
     */
    function add_performance_stats_for_admins () {
		    $stat = sprintf(  '%d queries in %s seconds, using %.2f MB memory',
		        get_num_queries(),
		        timer_stop( 0, 3 ),
		        memory_get_peak_usage() / 1024 / 1024
		        );
				if ( current_user_can( 'level_10' ) )
		    		echo "\n<!-- {$stat} -->\n\n" ;
    }
    add_action( 'wp_footer', 'add_performance_stats_for_admins' );



		/**
		 *  Trimmed excerpt for use in meta-description
		 *
		 *  @since    0.1.1
		 *  @todo     PHP Notice:  Undefined variable: raw_excerpt     
		 *
		 */
		function cb_wp_trim_excerpt( $text, $excerpt ) {

		    if ( !empty($excerpt) ) return $excerpt;

        $raw_excerpt = $text;

		    $text = strip_shortcodes( $text );

		    $text = apply_filters('the_content', $text);
		    $text = str_replace(']]>', ']]&gt;', $text);
		    $text = str_replace('&nbsp;', '', $text);
		    $text = strip_tags($text);
		    $excerpt_length = apply_filters('cb_wp_trim_excerpt_length', 55);
		    $excerpt_more = apply_filters('cb_wp_trim_excerpt_more', ' [...]');
		    $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
		    if ( count($words) > $excerpt_length ) {
		            array_pop($words);
		            $text = implode(' ', $words);
		            $text = $text . $excerpt_more;
		    } else {
		            $text = implode(' ', $words);
		    }

		    return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
		}



		/**
		 *  Return Site Description for use in different meta-Tags
		 *
		 *  @since 0.1.1
		 *
		 */
		function get_meta_description( $post ){
				if ((is_home()) || (is_front_page())) {
						$meta_desc =	get_bloginfo( 'description' );

				} elseif(is_singular()) {
				  	$meta_desc =	cb_wp_trim_excerpt( $post->post_content, $post->post_excerpt );

				} elseif(is_category()) {
						$category_description = category_description();
						if ( ! empty( $category_description ) )  {
								$meta_desc =	$category_description;
						} else {
								$meta_desc =	get_bloginfo( 'description' );
						}

				} elseif(is_tag()) {
						$meta_desc =	sprintf( __( 'Tag Archives: %s', 'cb-std-sys' ), '' . single_tag_title( '', false ) . '' );

				} elseif ( is_day() ) {
				  	$meta_desc =	sprintf( __( 'Daily Archives: %s', 'cb-std-sys' ), get_the_date() );

				} elseif ( is_month() ) {
				  	$meta_desc =	sprintf( __( 'Monthly Archives: %s', 'cb-std-sys' ), get_the_date('F Y') );

				} elseif ( is_year() ) {
				  	$meta_desc =	sprintf( __( 'Yearly Archives: %s', 'cb-std-sys' ), get_the_date('Y') );

				} elseif ( is_author() ) {
				  	$meta_desc =	sprintf( __( 'Author Archives: %s', 'cb-std-sys' ), get_the_author( $post->post_author ) );
				  if ( get_the_author_meta( 'description', $post->post_author ) )  // If a user has filled out their description, show a bio on their entries
								$meta_desc .=	 ', '.get_the_author_meta( 'description', $post->post_author );

				} else {
						$meta_desc =	get_bloginfo( 'description' );
				}

				return trim( strip_tags( $meta_desc,"\x22\x27\n" ) );
		}



		/**
		 *  Add rel="nofollow" to tag-links from 'the_tags' and 'wp_tag_cloud' for better SEO
		 *
		 *  @since 0.1.1
		 *
		 */
		function add_rel_nofollow_tag($text) {
				return str_replace('rel="tag"', 'rel="nofollow tag"',  $text);
		}
		add_filter('wp_tag_cloud', 'add_rel_nofollow_tag');
		add_filter('the_tags', 'add_rel_nofollow_tag');



		/**
		 * Makes some changes to the <title> tag, by filtering the output of wp_title().
		 *
		 * @source	Twenty Ten Theme 1.0
		 * @since   0.1.2
		 *
		 * @param string $title Title generated by wp_title()
		 * @param string $separator The separator passed to wp_title(). Twenty Ten uses a
		 * 	vertical bar, "|", as a separator in header.php.
		 * @return string The new title, ready for the <title> tag.
		 */
		function cbstdsys_filter_wp_title( $title, $separator ) {
				if ( is_feed() )
						return $title;

				global $paged, $page, $post;

				if ( is_search() ) {
						$title = sprintf( __( 'Search results for %s', 'cb-std-sys' ), '\'' . get_search_query() . '\'' );
						if ( $paged >= 2 )
								$title .= " $separator " . sprintf( __( 'Page %s', 'cb-std-sys' ), $paged );
						$title .= " $separator " . get_bloginfo( 'name', 'display' );
						return $title;
				}

				if ( is_singular() && has_category( $post ) ) {
					  $cats = array_reverse( get_the_category( $post->ID ) );
					  if (!empty($cats) ){
								$parents = get_category_parents( $cats[0]->term_id, false, $separator );
								$parents	=	substr($parents,0,-1) ;
								$parents = trim($parents);
						  	$parents = explode($separator,$parents);
								$parents = array_reverse($parents);
								array_walk($parents, create_function('&$parents', '$parents = $parents." | ";'));
								$parents = implode(" ",$parents );
								$title .= $parents;
						}
				}

				$title .= get_bloginfo( 'name', 'display' );

				$site_description = get_bloginfo( 'description', 'display' );
				if ( $site_description && ( is_home() || is_front_page() ) )
						$title .= " $separator " . $site_description;

				if ( $paged >= 2 || $page >= 2 )
						$title .= " $separator " . sprintf( __( 'Page %s', 'cb-std-sys' ), max( $paged, $page ) );

				return $title;
		}
		add_filter( 'wp_title', 'cbstdsys_filter_wp_title', 10, 2 );



		/**
		 * Removes the default styles that are packaged with the Recent Comments widget.
		 *
		 * @source  Twenty Ten Theme 1.0
		 * @since   0.1.2
		 */
		function remove_recent_comments_style() {
				add_filter( 'show_recent_comments_widget_style', '__return_false' );
		}
		add_action( 'widgets_init', 'remove_recent_comments_style' );



		/**
		 *  Author Meta description info
		 *
		 *  is shown, only when author has filled a description on profile page
		 *
		 *  @since  0.1.4
		 *  @used   author.php, single.php
		 */
		function cbstdsys_get_author_meta_block() {
      $amb = '';
			if ( cbstdsys_opts('m_multiauthor') && get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries
				    $amb	= '<address itemscope itemtype="http://schema.org/Person" class="author-bio vcard clearfix">';
						$amb .= get_avatar( get_the_author_meta('ID'), apply_filters( 'cbstdsys_author_bio_avatar_size', 60 ) );
						$amb .= "<h2>". sprintf( esc_attr__( 'About %s', 'cb-std-sys' ), '<span itemprop="name">'.get_the_author().'</span>' )."</h2>";
						$amb .= '<p class="author-description">'.get_the_author_meta( 'description' ).'</p>';
						if ( is_archive() ) {
		        		if ( get_the_author_meta('user_url') ) {
										$amb .= '<a class="user-url author-url"  itemprop="url" href="'. get_the_author_meta('user_url') .'">';
										$amb .= sprintf( __( 'View Website of %s', 'cb-std-sys' ), get_the_author() );
										$amb .= '</a>';
								}
						} else {
								$amb .= '<a class="user-archive author-url"  itemprop="url" href="'. get_author_posts_url( get_the_author_meta( 'ID' ) ) .'">';
								$amb .= sprintf( __( 'View all posts by %s', 'cb-std-sys' ), get_the_author() );
								$amb .= '</a>';
						}
						$amb .= '</address>';
				endif;
				echo $amb;
		}



		/**
		 *  Add alt-Attribute and schema.org Attriobutes to Avatar-Images
		 *
		 *  @since  0.1.4
		 */
		function add_alt_attr_to_avatar( $avatar_alt ) {
		  global $in_comment_loop;
				if ( $in_comment_loop ) {
						 $user  =   get_comment_author();
				} else {
						 $user  =   get_the_author();
				}
				$avatar_alt = str_replace( 'alt=""', 'itemprop="image" alt="'.sprintf( __( 'User Avatar of %s', 'cb-std-sys' ), $user ).'"', $avatar_alt );
				$avatar_alt = str_replace( "alt=''", 'itemprop="image" alt="'.sprintf( __( 'User Avatar of %s', 'cb-std-sys' ), $user ).'"', $avatar_alt );
				return $avatar_alt;
		}
		add_filter( 'get_avatar','add_alt_attr_to_avatar', 100 );



		/**
		 *  Remove rel="nofollow"
		 *
		 *  @since 	0.1.4
		 *  @source http://wpcanyon.com/tipsandtricks/remove-nofollow-from-wordpress-comments/
		 */
		function remove_nofollow($str) {
		    $str = preg_replace(
		        '~<a ([^>]*)\s*(["|\']{1}\w*)\s*nofollow([^>]*)>~U',
		        '<a ${1}${2}${3}>', $str);
		    return str_replace(array(' rel=""', " rel=''"), '', $str);
		}
		remove_filter( 'pre_comment_content',     'wp_rel_nofollow' );
		add_filter   ( 'get_comment_author_link', 'remove_nofollow' );
		add_filter   ( 'post_comments_link',      'remove_nofollow' );
		add_filter   ( 'comment_reply_link',      'remove_nofollow' );
		add_filter   ( 'comment_text',            'remove_nofollow' );



		/**
		 *  Replace DIV with html5 compliant figcaption-Tags
		 *  for image captions
		 *
		 *  @since  0.1.4
		 *  @source http://wordpress.stackexchange.com/questions/1567/best-collection-of-code-for-your-functions-php-file/19490#19490
		 */
		function use_html5_figcaption( $attr, $content = null ) {
		    $output = apply_filters( 'img_caption_shortcode', '', $attr, $content );
		    if ( $output != '' )
		        return $output;

		    extract( shortcode_atts ( array(
				    'id' => '',
				    'align' => 'alignnone',
				    'width'=> '',
				    'caption' => ''
		    ), $attr ) );

		    if ( 1 > (int) $width || empty( $caption ) )
		        return $content;

		    if ( $id ) $id = 'id="' . $id . '" ';

		    return '<figure ' . $id . 'class="wp-caption ' . $align . '" style="width: ' . $width . 'px" itemscope>'
		. do_shortcode( $content ) . '<figcaption class="wp-caption-text" itemprop="name">' . $caption . '</figcaption></figure>';
		}
		add_shortcode( 'wp_caption', 'use_html5_figcaption' );
		add_shortcode( 'caption', 'use_html5_figcaption' );



		/**
		 *  Back to top Link
		 *
		 *  @since  0.1.4
		 */
		function backtotop_link() {
        global $pagenow;
				return "\n<a href='". $pagenow ."#top'  class='backtotop visuallyhidden focusable' title='" .__('back to top','cb-std-sys') ."'>". __('back to top','cb-std-sys') ."</a>\n";
		}



		/**
		 * 	Modification of wp_link_pages() with an extra element to highlight the current page.
		 *  Replacement for wp_link_pages with numbers. Use do_action( 'numbered_in_page_links' );
		 *
		 *  @since  0.1.4
		 *  @source http://wordpress.stackexchange.com/questions/14406/how-to-style-current-page-number-wp-link-pages/14460#14460
		 *  @author http://toscho.de
		 *
		 * 	@param  array $args
		 * 	@return void
		 */
		function numbered_in_page_links( $args = array () ) {
		    $defaults = array(
		        'before'      => '<nav class="pagination numbered-in-page-links">' . __('Pages:', 'cb-std-sys' )
		    ,   'after'       => '</nav>'
		    ,   'link_before' => ''
		    ,   'link_after'  => ''
		    ,   'pagelink'    => '%'
		    ,   'echo'        => 1
		        // element for the current page
		    ,   'highlight'   => 'span'
		    );

		    $r = wp_parse_args( $args, $defaults );
		    $r = apply_filters( 'wp_link_pages_args', $r );
		    extract( $r, EXTR_SKIP );

		    global $page, $numpages, $multipage, $more, $pagenow;

		    if ( ! $multipage )
		    {
		        return;
		    }

		    $output = $before;

		    for ( $i = 1; $i < ( $numpages + 1 ); $i++ )
		    {
		        $j       = str_replace( '%', $i, $pagelink );
		        $output .= ' ';

		        if ( $i != $page || ( ! $more && 1 == $page ) )
		        {
		            $output .= _wp_link_page( $i ) . "{$link_before}{$j}{$link_after}</a>";
		        }
		        else
		        {   // highlight the current page
		            // not sure if we need $link_before and $link_after
		            $class  = ' class="current"';
		            $output .= "<{$highlight}{$class}>{$link_before}{$j}{$link_after}</$highlight>";
		        }
		    }

		    print $output . $after;
		}
		add_action( 'numbered_in_page_links', 'numbered_in_page_links', 10, 1 );



    /**
     *	Kill attachment, author, date archive pages
     *  same as in hook_search.php
     *
     *  @since  0.1.5
     *  @source http://betterwp.net/wordpress-tips/disable-some-wordpress-pages/
     */
    function kill_page_and_feed_types ( $posts ) {
        global $wp_query, $post;
        $cbstdsys_opts  =  cbstdsys_opts();

        if ( is_author() && !$cbstdsys_opts['m_multiauthor'] ) {
            $wp_query->set_404();
            status_header( 404 );
        }
/*
        if ( is_attachment()  ) {
            $wp_query->set_404();
        }
*/

        if ( is_date() && !$cbstdsys_opts['m_blog'] ) {
            $wp_query->set_404();
            status_header( 404 );
        }

        if ( is_feed() ) {
        
            if ( !$cbstdsys_opts['m_multiauthor'] ) {
            		$author     = get_query_var('author_name');
            }
/*
            $attachment = get_query_var('attachment');
            $attachment = (empty($attachment)) ? get_query_var('attachment_id') : $attachment;
*/
            if ( !$cbstdsys_opts['m_blog'] ) {
            		$day        = get_query_var('day');
            		$month      = get_query_var('month');
            		$year       = get_query_var('year');
						}
						
            if ( !$cbstdsys_opts['m_search'] ) {
            		$search     = get_query_var('s');
						}
						
            if (!empty($author) || !empty($attachment) || !empty($day) || !empty($search)) {
                $wp_query->set_404();
                $wp_query->is_feed = false;
                status_header( 404 );
            }
        }
        return $posts;
    }
    add_action('the_posts', 'kill_page_and_feed_types', 1);



		/**
		 *  define allowed html-tags for commentform
		 *  only allow <strong>, <em>, pre, code, and <a href=""> tags
		 *
		 *  @since  0.1.5
		 */
		function my_html_tags_code() {
			if ( !defined( 'CUSTOM_TAGS' ) )
					define( 'CUSTOM_TAGS', true);
					
		  global $allowedposttags, $allowedtags;
		  
		  $allowedposttags = array(
		      'strong' => array(),
		      'em' => array(),
		      's' =>  array(),
		      'blockquote'  => array(
						 'cite' =>  array()
					),
		      'code' => array(),
		      'a' => array(
		        'href' => array (),
		        'title' => array ()
					)
		  );

		  $allowedtags = array(
		      'strong' => array(),
		      'em' => array(),
		      's' =>  array(),
		      'blockquote'  => array(
						 'cite' =>  array()
					),
		      'code' => array(),
		      'a' => array(
		        'href' => array (),
		        'title' => array ()
					)
		  );
		}
		add_action('init', 'my_html_tags_code', 10);



		/**
		 *    Rewrite WP pathes dynamically based on theme-names
		 *
		 *    @since  0.1.8
		 *    @source http://wordpress.stackexchange.com/a/35118
		 *
		 *    @todo   make this work with BWP minify, because this lib doesn't understand rewritten pathes
		 *    @todo   add flush_rules
		 *    @todo   http://wordpress.stackexchange.com/a/25494
		 */
		function path_add_rewrites($content) {
		  $theme_name = next(explode('/themes/', get_stylesheet_directory()));
		  global $wp_rewrite;
		  $roots_new_non_wp_rules = array(
		    'css/(.*)'      => 'wp-content/themes/'. $theme_name . '/css/$1',
		    'js/(.*)'       => 'wp-content/themes/'. $theme_name . '/js/$1',
		    'img/(.*)'      => 'wp-content/themes/'. $theme_name . '/img/$1',
		    'plugins/(.*)'  => 'wp-content/plugins/$1',
		  );
		  $wp_rewrite->non_wp_rules += $roots_new_non_wp_rules;
		}
		#add_action('generate_rewrite_rules', 'path_add_rewrites');



		/**
		 *	Stop images getting wrapped up in p tags when they get dumped out with the_content() for easier theme styling
		 *
		 *  @since  0.1.8
		 */
		function filter_ptags_on_images($content){
				return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
		}
		add_filter('the_content', 'filter_ptags_on_images');



		/**
		 *  Strip ending slashes from xhtml selfclosing-tags
		 *
		 *  we don't need to self-close these tags in html5, e.g. <img>, <input>
		 *
		 *  @source  	http://wordpress.stackexchange.com/a/116
		 *
		 *  @param    string    html-strings generated by WP
		 *  @return   string    cleaned html
		 *
		 *  @since    0.1.8
		 */
		if ( !is_feed() &&  (! defined('DOING_AJAX') || ( defined('DOING_AJAX') && ! DOING_AJAX ) ) ) {
		    ob_start( 'html5_slash_fixer' );
		    add_action( 'shutdown', 'html5_slash_fixer_flush' );
		}

		function html5_slash_fixer( $buffer ) {
		    return str_replace( ' />', '>', $buffer );
		}

		function html5_slash_fixer_flush() {
		    #ob_end_flush();
        ob_get_clean();
		}
		

		/**
		 *  Disable Attachement-Pages
		 *
		 *  Filter will return the Attachement-Url, not the Attachement-Page URL
		 *
		 *  @file   cbstdsys_admin.js   Choose "Link to file" at Gallery Settings by default
		 *  @file   attachement.php     Redirect 301 t  o the Attachement URL for older links
		 *
		 *  @param  string  the given URL to the Attachement-Page
		 *  @param  int     the ID of the Attachement
		 *
		 *	@since  0.1.8
		 */
		function disable_attachement_pages ( $url , $post_id) {
				return wp_get_attachment_url( $post_id );
		}
		add_filter('attachment_link','disable_attachement_pages', 10, 2);
		add_filter('the_attachment_link','disable_attachement_pages', 10, 2);
		add_filter('get_attachment_link','disable_attachement_pages', 10, 2);
		#add_filter('wp_get_attachment_link', 'disable_attachement_pages', 10, 2);



		/**
		 *  Use default tagcloud with em, not with px
		 *
		 *  @since    0.1.9
		 */
		function cbstdsys_tag_cloud_filter($args = array()) {
		   $args['smallest'] = 1;
		   $args['largest'] = 2;
		   $args['unit'] = 'em';
		   return $args;
		}
		add_filter('widget_tag_cloud_args', 'cbstdsys_tag_cloud_filter', 90);



		/**
		 *  Remove "Private:" and "Protected:" from post_title
		 *
		 *  @url  	http://www.viper007bond.com/2011/12/20/how-to-remove-or-change-private-or-protected-from-wordpress-post-titles/
		 *  @since  0.1.8
		 */
		function yourprefix_private_title_format( $format ) {
		    return '%s';
		}
		add_filter( 'private_title_format', 'yourprefix_private_title_format' );
		add_filter( 'protected_title_format', 'yourprefix_private_title_format' );



		/**
		 *	Exclude passworded posts from search and archive
		 *
		 *  @since  0.2.0
		 *  @source http://iyware.com/making-wordpress-password-protected-posts-more-private/
		 *
		 *  @todo   add option to theme options page
		 */
		function password_post_exclude($where, $wp_query = NULL) {
		    global $wpdb;
		    if ( ! $wp_query ) {
		        global $wp_query;
		    }
		    if ( $wp_query->is_archive  || $wp_query->is_search ) {
		        $where .= " AND $wpdb->posts.post_password = ''";
		    }
		    return $where;
		}
		#add_filter( 'posts_where','password_post_exclude', 1, 2 );



		/**
		 * 	Adds a Schema.org-Attribute to post thumbnail thumbnails
		 *
		 *  @url    http://schema.org/ImageObject
		 *
		 * 	@since 	0.2.0
		 * 	@param 	array $attr Attributes including src, class, alt, title
		 * 	@return array
		 */
		function filter_attachment_image_attributes( $attr ) {
		        $attr['itemprop'] = 'contentURL';
		        return $attr;
		}
		add_filter( 'wp_get_attachment_image_attributes', 'filter_attachment_image_attributes' );




		/**
		 *  Use default styles for Logins, Errors and dropins
		 *
		 *  @uses 		cbstdsys_login_css() in /inc/dropins.extensions.php
		 *  @since    0.2.0
		 */
		add_action( 'login_head', 'cbstdsys_login_css', 999 );
    
    
    
    /**
     *  Add first and last classes, and numbered "widget-n" classes for widgets
     *  
     *  @since    0.2.1
     *  @source   http://wordpress.org/support/topic/how-to-first-and-last-css-classes-for-sidebar-widgets
     */   
    function append_widget_first_and_last_classes($params) {
      global $my_widget_num;
      $this_id = $params[0]['id'];
      $arr_registered_widgets = wp_get_sidebars_widgets();
    
      if (!$my_widget_num) {
        $my_widget_num = array();
      }
    
      if (!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) {
        return $params;
      }
    
      if (isset($my_widget_num[$this_id])) {
        $my_widget_num[$this_id] ++;
      } else {
        $my_widget_num[$this_id] = 1;
      }
    
      $class = 'class="widget-' . $my_widget_num[$this_id] . ' ';
    
      if ($my_widget_num[$this_id] == 1) {
        $class .= 'widget-first ';
      } elseif ($my_widget_num[$this_id] == count($arr_registered_widgets[$this_id])) {
        $class .= 'widget-last ';
      }
    
      // Insert our new classes into "before widget"
      $params[0]['before_widget'] = preg_replace('/class=\"/', "$class", $params[0]['before_widget'], 1);
    
      return $params;
    
    }
    add_filter('dynamic_sidebar_params', 'append_widget_first_and_last_classes');    
    
    
    
    
    
    
    /**
     *  Add Filters to append CSS classes to existing markup from the parent theme
     *  without the need to modify core files
     *  
     *  @since    0.2.1
     */
    function cbstdsys_add_css_classes_via_filters ( $element_id ) {
    
        if ( empty( $element_id ) )
            return false;

        $css_attr = apply_filters( 'cbstdsys_add_html_class_for_id_'.$element_id, '' );
       
        if ( !empty( $css_attr ) )
            $css_attr = ' class="' . $css_attr . '"';
        
        return $css_attr;
    }



// http://plugins.svn.wordpress.org/widget-logic/trunk/widget_logic.php (0.52)
add_filter( 'dynamic_sidebar_params', 'widget_logic_widget_display_callback', 10); 			// redirect the widget callback so the output can be buffered and filtered
    
// CALLED ON 'dynamic_sidebar_params' FILTER - this is called during 'dynamic_sidebar' just before each callback is run
// swap out the original call back and replace it with our own
function widget_logic_widget_display_callback($params)
{	global $wp_registered_widgets;
	$id=$params[0]['widget_id'];
	$wp_registered_widgets[$id]['callback_wl_redirect']=$wp_registered_widgets[$id]['callback'];
	$wp_registered_widgets[$id]['callback']='widget_logic_redirected_callback';
	return $params;
}


// the redirection comes here
function widget_logic_redirected_callback()
{	global $wp_registered_widgets, $wp_reset_query_is_done;

	// replace the original callback data
	$params=func_get_args();
	$id=$params[0]['widget_id'];
	$callback=$wp_registered_widgets[$id]['callback_wl_redirect'];
	$wp_registered_widgets[$id]['callback']=$callback;

	// run the callback but capture and filter the output using PHP output buffering
	if ( is_callable($callback) ) 
	{	ob_start();
		call_user_func_array($callback, $params);
		$widget_content = ob_get_contents();
		ob_end_clean();
		echo apply_filters( 'widget_content', $widget_content, $id);
	}
}


/**
 *  Solve Ticket #16541 Problem
 *  get_search_form() ignores $echo if searchform.php exists 
 *  
 *  @see    http://core.trac.wordpress.org/ticket/16541
 *  @since  0.2.1
      
function do_use_echo_on_get_search_form () {

	$search_form_template = locate_template('searchform.php');
	if ( '' != $search_form_template ) {
		require($search_form_template);
		return;
	}

	$form = '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/' ) ) . '" >
	<div><label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
	<input type="text" value="' . get_search_query() . '" name="s" id="s" />
	<input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
	</div>
	</form>';

	if ( $echo )
		echo apply_filters('get_search_form', $form);
	else
		return apply_filters('get_search_form', $form);
}
}
add_action( 'get_search_form', 'do_use_echo_on_get_search_form' );    
 */



		/**
		 *  Allow Overlays to overlay flash (inserting wmode=opaque)
		 *
		 *  @todo   move to frontend.php or backend.php in according to caller fn
		 */
		function add_video_wmode_transparent($html, $url, $attr) {
			if ( strpos( $html, "<embed src=" ) !== false )
			   { return str_replace('</param><embed', '</param><param name="wmode" value="opaque"></param><embed wmode="opaque" ', $html); }
			elseif ( strpos ( $html, 'feature=oembed' ) !== false )
			   { return str_replace( 'feature=oembed', 'feature=oembed&wmode=opaque', $html ); }
			else
			   { return $html; }
		}
		add_filter( 'embed_oembed_html', 'add_video_wmode_transparent', 10, 3);
?>