<?php

		$cbstdsys_opts = get_option('cbstdsys_options');  
    
    
    
    /**
     *  show default index page until Live-Launch
     *  
     *  @since    0.0.6
     *  
     */                    
    function default_index_html() {
        include_once $_SERVER['DOCUMENT_ROOT'].'/defaultindex.php';   die();
    }
    if ( $cbstdsys_opts['a_default_index'] && !is_user_logged_in()  ) {
        add_action('get_header', 'default_index_html'); 
    }



    /**
     *  switch ON maintenance mode when debugging is on
     *  
     *  @since    0.0.3
     *  
     */                     
    function maintenance_mode() {
 
      if ( WP_DEBUG === true ) {
        if ( is_user_logged_in() ) {
          print('<!--');
          print_r( get_defined_constants() );
          print('-->');
        }
        if ( !in_array($current_user->ID, $cbstdsys_opts['a_admin_user_IDs'] ) )
        include_once WP_CONTENT_DIR.'/maintenance.php';        
      }
    }
    add_action('get_header', 'maintenance_mode',1);
    


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
        echo "<copyright>".cb_std_sys_copyright()."</copyright>\n";     
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
    remove_action( 'wp_head' , 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
    remove_action( 'wp_head' , 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
    remove_action( 'wp_head' , 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
    remove_action( 'wp_head' , 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
    remove_action( 'wp_head' , 'index_rel_link' ); // index link
    remove_action( 'wp_head' , 'parent_post_rel_link', 10, 0 ); // prev link
    remove_action( 'wp_head' , 'start_post_rel_link', 10, 0 ); // start link
    remove_action( 'wp_head' , 'adjacent_posts_rel_link_wp_head', 10, 0 ); // Display relational links for the posts adjacent to the current post.
    remove_action( 'wp_head' , 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version
    
    
    
    /**
     *  adjust  WPML output
     *  
     *  - remove basic-css files
     *  - remove navigation-css files
     *  - remove basic-js
     *  + filter home_url() to use icl_home_url()
     *  + build own language_selector_flags() function
     *  
     *  @since    0.0.7                         
     *               
     */         
    if (  $cbstdsys_opts['m_multi_lang']  ) {    
        // WP Plugin WPML sitepress-multilingual-cms
        function get_icl_home_url($url) {
            $iclurl  = icl_get_home_url();
            $url  = str_replace( WP_HOME.'/', $iclurl, $url );
            return $url;
        }
        // Sprachenumschalter mit Flaggen über WPML zur Verfügung stellen  
        function language_selector_flags(){
            $languages = icl_get_languages('skip_missing=0&orderby=code');
            if(!empty($languages)){
                $lang_selector  = '<ul id="icl_lang_selector">';
                    foreach($languages as $l){
                        if(!$l['active']) {
                        $lang_selector  .= '<li class="selector-element-'.$l['language_code'].'"><a hreflang="'.$l['language_code'].'" href="/'.$l['language_code'].'">';
                        #$lang_selector  .= '<img src="'.plugins_url('sitepress-multilingual-cms/res/flags/'.$l['language_code'].'.png').'" alt="'.$l['native_name'].'" >';
                        $lang_selector  .= '<span lang="'.$l['language_code'].'">'.$l['native_name'].'</span>';
                        $lang_selector  .= '</a></li>';
                        }
                    }
                $lang_selector  .= '</ul>';
                }
            return $lang_selector;
        }   
         
        define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS' , true); 
        define('ICL_DONT_LOAD_LANGUAGES_JS' , true); 
        define('ICL_DONT_LOAD_NAVIGATION_CSS' , true);
        add_filter('home_url','get_icl_home_url',0,1);
    }

    
    
    /**
     *  change language of all feeds ouput    
     *  
     *  @since    0.0.5
     *  
     */                    
    function change_rss_lang(){
        if (  $cbstdsys_opts['m_multi_lang']  ) {
            return ICL_LANGUAGE_CODE;
        } else {
            return substr( WPLANG, 0, 2 );
        }
    }   
    add_filter('pre_option_rss_language', 'change_rss_lang');        
       
    
    
    /**
     *  adjust GeoMashup Output
     *  
     *  - prevent Plugin Loading
     *  - remove css files
     *  - remove js files     
     *  + add <meta> Elements with geo-information
     *  
     *  @since    0.0.7
     *  
     */                                            
    if ( class_exists( 'GeoMashup' ) ) { 
    
        remove_action( 'init', array('GeoMashup', 'init') );    
        remove_action( 'wp_head', array('GeoMashup', 'wp_head'));
        add_action ( 'wp_head', 'myGeoMashupWpHead' );
        
        function myGeoMashupWpHead(){
        		global $wp_query;
         		
            $loc = GeoMashupDB::get_object_location( 'post', $wp_query->post->ID );      
        		if (is_singular() && !empty($loc))	{
        				$title = esc_html(convert_chars(strip_tags(get_bloginfo('name'))." - ".$wp_query->post->post_title));
                echo '  <meta name="ICBM" content="' . esc_attr( $loc->lat . ', ' . $loc->lng ) . '">' . "\n\t";
        				echo '  <meta name="DC.title" content="' . esc_attr( $title ) . '">' . "\n";
        				echo '  <meta name="geo.position" content="' .  esc_attr( $loc->lat . ';' . $loc->lng ) . '">' . "\n";
    						echo '  <meta name="geo.region" content="' . esc_attr( $loc->country_code ) . '">' . "\n";
    						echo '  <meta name="geo.placename" content="' . esc_attr( $loc->address ) . '">' . "\n";
        		}
        		else  {
        			$saved_locations = GeoMashupDB::get_saved_locations( );
        			if ( !empty( $saved_locations ) )
        			{
        				foreach ( $saved_locations as $saved_location ) {
        					if ( $saved_location->saved_name == 'default' ) {
        						$title = esc_html(convert_chars(strip_tags(get_bloginfo('name'))));
        						echo '  <meta name="ICBM" content="' . esc_attr( $saved_location->lat . ', ' . $saved_location->lng ) . '">'. "\n";
        						echo '  <meta name="DC.title" content="' . esc_attr( $title ) . '">' . "\n";
        						echo '  <meta name="geo.position" content="' . esc_attr( $saved_location->lat . ';' . $saved_location->lng ) . '">' . "\n";
        						echo '  <meta name="geo.region" content="' . esc_attr( $saved_location->country_code ) . '">' . "\n";
        						echo '  <meta name="geo.placename" content="' . esc_attr( $saved_location->address ) . '">' . "\n";
        					}
                }
        			}
        		}
        }
    }
    
    
    /**
     *  get meta-value for author
     *  
     *  @since    0.0.1
     *  
     */                    
    function cb_std_sys_get_page_author_meta() {
        global $cbstdsys_opts;
        if ( is_singular() || is_feed() ) {
            global $post;
            $cur_author = get_userdata($post->post_author);
            if ( $cur_author->user_firstname != '' && $cur_author->user_lastname != '' && !in_array($post->post_author, $cbstdsys_opts['a_admin_user_IDs'] ) ) {
                return $cur_author->user_firstname." ".$cur_author->user_lastname;       
            } else {
                return $cbstdsys_opts['c_main_author'];
            }
        } else {
            return $cbstdsys_opts['c_main_author'];
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
     */                                       
    function filter_the_author( $author ) {
        $author = cb_std_sys_get_page_author_meta();
        return $author;
    }
    add_filter('the_author', 'filter_the_author');
    
    
    
    /**
     *  create copyright YEAR-Info from real published posts
     *  take first year from Options-Page or from realy first post
     *  
     *  @since    0.0.5
     *  
     */                         
    function cb_std_sys_copyright() {
        global $cbstdsys_opts;    
        global $wpdb;
        $copyright_dates = $wpdb->get_results("
                            SELECT YEAR(min(post_date_gmt)) AS firstdate, YEAR(max(post_date_gmt)) AS lastdate
                            FROM $wpdb->posts WHERE post_status = 'publish'");
        if($copyright_dates) {
            $copyright = $copyright_dates[0]->firstdate;
            if ( $cbstdsys_opts['c_first_pub'] !='' ) {
            $copyright = $cbstdsys_opts['c_first_pub']; }
            if($copyright_dates[0]->firstdate != $copyright_dates[0]->lastdate) {
                $copyright .= '-' . $copyright_dates[0]->lastdate;
            }
        }
        return $copyright." ".get_bloginfo("name").". ".__('All rights reserved', 'cb-std-sys').".";
    } 
    
    
    
    /**
     *  add css and js to pages and posts with a CONTACT FORM 7 PLUGIN shortcode 
     *  
     *  @since    0.0.7
     *
     */     
    function conditionally_add_scripts_and_styles_for_cf7($posts){
        if ( empty($posts) ) return $posts;
        if ( conditionally_load_if_shortcode_used('[contact-form', $posts ) ) {
        		wpcf7_enqueue_scripts();
        		$cf7_css  = wp_css( '../../../wp-content/plugins/contact-form-7/styles.css' , false );
        	  wp_enqueue_style( 'contact-form-7', $cf7_css, false, WPCF7_VERSION );
        }
        return $posts;
    }
    if ( defined( 'WPCF7_VERSION' ) && !is_admin() ) 
        add_filter('the_posts', 'conditionally_add_scripts_and_styles_for_cf7', 1); // the_posts gets triggered before wp_head         
        
    
        
    /**
     *  add google-analytics script section to footer,
     *  when tracking-code is defined via Options-Page
     *  
     *  @since    0.0.6
     *  
     */                    
    function add_google_analytics_section () { 
      global $cbstdsys_opts;?>
  <script>var _gaq=[["_setAccount","UA-<?php echo $cbstdsys_opts['m_ga_tracking']; ?>"],["_trackPageview"],['_gat._anonymizeIp']];
  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
  g.src=("https:"==location.protocol?"//ssl":"//www")+".google-analytics.com/ga.js";
  s.parentNode.insertBefore(g,s)}(document,"script"));</script><?php
    }
    if ( !empty( $cbstdsys_opts['m_ga_tracking'] ) && !is_user_logged_in() )
      add_action( 'wp_footer', 'add_google_analytics_section' );
    
    
    
    /**
     *  add semantic css-classes in wp body_class
     *  
     *  @since    0.0.2
     *  
     */             
    function add_body_classes($classes) {
        global $cbstdsys_opts, $current_user, $post, $is_lynx, $is_gecko, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
        
      	if($is_lynx)       $classes[]  = 'lynx';
      	elseif($is_gecko)  $classes[]  = 'gecko';
      	elseif($is_opera)  $classes[]  = 'opera';
      	elseif($is_NS4)    $classes[]  = 'ns4';
      	elseif($is_safari) $classes[]  = 'safari';
      	elseif($is_chrome) $classes[]  = 'chrome';
      	elseif($is_iphone) $classes[]  = 'iphone';
      	else{} // do nothing
        
      	// Add a master-admin Class if some 'master-admin' is logged in
        if ( in_array($current_user->ID, $cbstdsys_opts['a_admin_user_IDs'] ) )  
        $classes[] = 'superadmin-logged-in';      
        
        // Applies the time- and date-based classes (below) to BODY element
      	good_old_sandbox_date_classes( time(), $classes );
        
        if ( is_singular() ) {
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
        if ( is_404() ) {
                $classes[] = 'page';
        }
				return $classes;
    }
    add_filter('body_class', 'add_body_classes');
    
    
    
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
     *  add semantic css-classes in wp comment_class
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
      	echo "Disallow: */feed\n";
      	echo "Disallow: */comments\n";
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
            
            add_filter('home_url','cbstdsys_root_relative_url');
            add_filter('site_url','cbstdsys_root_relative_url');
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
    add_filter('plugins_url', 'clean_plugins_path');
    add_filter('bloginfo', 'clean_plugins_path');



    /**
     *  stop WP redirecting miss-spelled URLS to canonical similars
     *  
     *  @since    0.0.4
     *  
     */                      
    remove_filter('template_redirect', 'redirect_canonical'); 



    /**
     *  remove default CSS for [gallery]-shortcode
     *  
     *  @since    0.0.1
     *  
     */                    
    function remove_gallery_style($css) {
        return preg_replace("#<style type='text/css'>(.*?)</style>#s", '', $css);
    }
    add_filter('gallery_style', 'remove_gallery_style');
    
    
    
    /**
     *  add YUI-profiling-link to admin-bar
     *  
     *  @since    0.0.2
     *  
     */                    
    function add_profilink_link_to_admin_bar() {
        global $wp_admin_bar;
        if ( !is_admin_bar_showing() )
            return;
        $wp_admin_bar->add_menu( 
        array(
        'id'      => 'cb-std-sys-debugging',
        'title'   => __( 'Debug','cb-std-sys'),
        'parent'  => false,
        'href'    => false,
        'class'   => 'menupop'
        ) );
        $wp_admin_bar->add_menu( 
        array(
        'id'      => 'yui_profiling',
        'parent'  => 'cb-std-sys-debugging',
        'title'   => __( 'YUI Profiling','cb-std-sys'),
        'href'    => $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"].'?profiling=1'
         ) );              
    }
    if ( in_array($current_user->ID, $cbstdsys_opts['a_admin_user_IDs'] ) ) {
    add_action('admin_bar_menu', 'add_profilink_link_to_admin_bar', 1000);    
    }



    /**
     *  additionaly log 404-errors to php_error_log
     *  
     *  @since    0.0.5
     *  
     */                    
    function log_404() {
    	if (is_404() && get_settings('permalink_structure')) {
    	   $log_line  = '[client: '. $_SERVER['REMOTE_ADDR'] .'] ';
    		 $log_line .= 'File does not exsist: '. substr(ABSPATH, 0, -1) . $_SERVER['REQUEST_URI'];
    		if ($_SERVER['HTTP_REFERER'])
    			$log_line .= ', referer: '.$_SERVER['HTTP_REFERER'];
    		error_log($log_line, 0);
    	}
    }
    add_action('shutdown', 'log_404');   
    
    
    
    /**
     *  add some additional reccurences to the wp_cron
     *  
     *  @since    0.0.4
     *  
     */                    
    function more_reccurences() {
        return array(
            'weekly'      => array('interval' => 604800, 'display' => 'Once Weekly'),
            'fortnightly' => array('interval' => 1209600, 'display' => 'Once Fortnightly'),
        );
    }
    add_filter('cron_schedules', 'more_reccurences');
    
    
    
    /**
     *  send error log via email to admin, twice a month
     *  
     *  @since  0.0.2
     *  
     */                    
    if (!wp_next_scheduled('send_errorlog_to_admin')) {
    	 wp_schedule_event( time(), 'weekly', 'send_errorlog_to_admin' );
    }
    function do_send_errorlog_to_admin() {
        if ( file_get_contents( WP_CONTENT_DIR."/php_error.log" ) != '' )
    	  wp_mail('mail@carsten-bach.de', 'Error Log von "'.get_bloginfo('name').'"', file_get_contents( WP_CONTENT_DIR."/php_error.log" ) );
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
#        wp_clear_scheduled_hook('send_errorlog_to_admin');

		    $timestamp = wp_next_scheduled( 'send_errorlog_to_admin' );
				wp_clear_scheduled_hook( $timestamp, 'send_errorlog_to_admin' );
    }
    #add_action( 'send_errorlog_to_admin', 'my_task_deactivate' );
    
 

    /**
     *  Block logged-in users from wp-admin, if they don't have the needed access-level
     *  
     *  @since    0.0.1
     *  
     */                     
    function prevent_admin_access( ) {
        if (strpos(admin_url( 'admin-ajax.php' ), strtolower($_SERVER['REQUEST_URI']) ) === false ) {
            if (strpos(admin_url( ), strtolower($_SERVER['REQUEST_URI']) ) !== false && 
                !current_user_can('level_4') ) {
                wp_redirect( WP_HOME );
            }
        }
    }
    add_action('init', 'prevent_admin_access', 0); 
    
    
    
    /**
     *  disable Login Errors for security reasons 
     *  
     *  @since    0.0.1
     *  
     */                      
    add_filter('login_errors',create_function('$a', "return null;"));  



    /**
     *  use personalized CSS-styles for Login Screen
     *  
     *  @since    0.0.1
     *  
     */                    
    function cbstdsys_login_css() {
        echo '<link href="'.WP_THEME_URL. '/css/cb_std_sys_admin.css" rel="stylesheet" />';
    }
    add_action('login_head', 'cbstdsys_login_css');

    
    
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
    add_filter('login_headertitle', 'cbstdsys_login_headertitle'); 

    
    
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
    
    		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . 'class="' .$class_names .'">';
    
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
     *       
     */          
    function fancy_amp($title) {
      #if(in_the_loop()) {
        $title = preg_replace("@(?![^<]+>)&amp;@", "<span class=\"amp\">&amp;</span>", "$title");
        $title = preg_replace("@(?![^<]+>)&#038;@", "<span class=\"amp\">&#038;</span>", "$title");
      #}
      return $title;
    }
    add_filter('the_title', 'fancy_amp');
    add_filter('get_the_title', 'fancy_amp');
    

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
        		);
        	if ( is_search() ) {
        		$pagination['next_text'] = __( 'Next posts', 'cb-std-sys' ).' <span class="meta-nav">&rarr;</span>';
        		$pagination['prev_text'] = '<span class="meta-nav">&larr;</span> '. __( 'Previous posts', 'cb-std-sys' );          
          } elseif ( is_archive() ) {
        		$pagination['next_text'] = __( 'Older posts', 'cb-std-sys' ).' <span class="meta-nav">&rarr;</span>';
        		$pagination['prev_text'] = '<span class="meta-nav">&larr;</span> '. __( 'Newer posts', 'cb-std-sys' );              
          } elseif ( is_singular() ) {
            $prev = get_adjacent_post($in_same_cat, $excluded_categories, true);
            if ($prev) {
        		    $pagination['prev_post_cb'] = '<a href="' . get_permalink($prev) . '" rel="prev">' . _x( '&larr;', 'Previous post link', 'cb-std-sys' ) . ' ' . $prev->post_title .'</a>';
            } 
            $next = get_adjacent_post($in_same_cat, $excluded_categories, false);         
            if ($next) {
        		    $pagination['next_post_cb'] = '<a href="' . get_permalink($next) . '" rel="next">' . $next->post_title . ' ' . _x( '&rarr;', 'Next post link', 'cb-std-sys' ) . '</a>';            
            }
          } else{
        		$pagination['next_text'] = __( 'Newer posts DEFAULT', 'cb-std-sys' ).' <span class="meta-nav">&rarr;</span>';
        		$pagination['prev_text'] = '<span class="meta-nav">&larr;</span> '. __( 'Older posts DEFAULT', 'cb-std-sys' );              
          }
         
        	if( $wp_rewrite->using_permalinks() )
        		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . __('page').'/%#%/', 'paged' );
         
        	if( !empty($wp_query->query_vars['s']) )
        		$pagination['add_args'] = array( 's' => get_query_var( 's' ) );
         
          $paginav   = '<nav id="nav-'.$position.'" class="pagination pagination-'.$context.'">';
          $paginav  .= apply_filters( 'cb_pagination_prev_link', $pagination['prev_post_cb'] );
          $paginav  .= paginate_links( $pagination );
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




   
?>