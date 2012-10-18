<?php

	define( 'YOUR_CHILDTHEME_VERSION', '0.1');
	define( 'YOUR_CHILDTHEME_TEXTDOMAIN', 'your-childtheme-textdomain');

/***************************************************************************************************************
 *
 *      								Modification of frontend AND backend stuff, working in DOCROOT/
 *
 **************************************************************************************************************/

		/**
		 *  Set the content width based on the theme's design and stylesheet.
		 *
     *  @since    YOUR-CHILDTHEME-NAME 0.1
		 */
		#$content_width = ;


		
		/**
		 *    Load localization for child theme
		 *
		 *    Replace all YOUR_CHILDTHEME_TEXTDOMAIN in this functions.php with your textdomain string
		 *
		 *		@since    0.2.0
		 */
		function add_childtheme_textdomain ( ) {
		    load_child_theme_textdomain( YOUR_CHILDTHEME_TEXTDOMAIN, WP_CHILD_DIR . '/languages');
		}
		add_action('after_setup_theme', 'add_childtheme_textdomain');



		/**
		 *		Remove admin bar per role
		 *
		 *    @since    0.2.0
		 */
		function remove_show_admin_bar_filter ( ) {
				if ( current_user_can( 'publish_posts' ) )
						return TRUE;
				else
						return FALSE;
				}
		#add_filter( 'show_admin_bar', 'remove_show_admin_bar_filter' );



		/**
		 *  Change initialised sidebars
		 *
     *  @since    YOUR-CHILDTHEME-NAME 0.1
		 */
		function childtheme_sidebars_init() {
			  return $sidebars = array(
			      array(__( 'Primary Widget Area', 'cb-std-sys' ),'primary-widget-area', __( 'The primary widget area', 'cb-std-sys' )),
            array(__( 'Secondary Widget Area', 'cb-std-sys' ),'secondary-widget-area', __( 'The secondary widget area', 'cb-std-sys' )),
			  );
		}
		#add_filter( 'cbstdsys_sidebars_init', 'childtheme_sidebars_init' );



    /**
     *  Returns additional custom thumbnail sizes
     *  Overwrites pluggable function from cbstdsys-parent-theme     
     *
     *  @since    YOUR-CHILDTHEME-NAME 0.1

    function custom_thumbnail_setup () {
    		// Array of custom image sizes to add
    		$custom_thumbnail_sizes = array(
    				array( 'name'=>'campaign-full', 'width'=>1260, 'height'=>488, 'crop'=>true ),
    				array( 'name'=>'campaign-medium', 'width'=>626, 'height'=>190, 'crop'=>true ),
    		);
    		return $custom_thumbnail_sizes;
    }
     */	
  



    /**
     *  Postthumbnail Definitions
     *
     *  - define post_thumbnail size
     *  - define if standard sizes should be cropped
     *  - get custom_thumbnail-sizes and update their options
     *
     *  @since  0.1.3
     *  @source http://wordpress.org/support/topic/hack-crop-custom-thumbnail-sizes?replies=17#post-2041676
     */
		function thumbnail_setup () {
				// define post_thumbnail size
				set_post_thumbnail_size( '100', '100', true );

				// define all post_types, which use thumbnails
				add_theme_support('post-thumbnails' );

				// define if standard sizes should be cropped
				// http://wordpress.org/support/topic/force-crop-to-medium-size
			  if(false === get_option("medium_crop")) add_option("medium_crop", "1");
			  else                                    update_option("medium_crop", "1");

				// get custom_thumbnail-sizes ...
				$custom_thumbnail_sizes =  custom_thumbnail_setup();

				// ... and update their options
				// For each new image size, run add_image_size() and update_option() to add the necessary info.
				// update_option() is good because it only updates the database if the value has changed. It also adds the option if it doesn't exist
				foreach ( $custom_thumbnail_sizes as $custom_thumbnail_size ){
						add_image_size( $custom_thumbnail_size['name'], $custom_thumbnail_size['width'], $custom_thumbnail_size['height'], $custom_thumbnail_size['crop'] );
						update_option( $custom_thumbnail_size['name']."_size_w", $custom_thumbnail_size['width'] );
						update_option( $custom_thumbnail_size['name']."_size_h", $custom_thumbnail_size['height'] );
						update_option( $custom_thumbnail_size['name']."_crop", $custom_thumbnail_size['crop'] );
				}
		}
		add_action( 'after_setup_theme', 'thumbnail_setup' );


  
  	/**
  	 *  Add childtheme scripts
  	 *
     *  @since    YOUR-CHILDTHEME-NAME 0.1
  	 */
  	function add_childtheme_scripts ( ) {
        
        /**
         *  Construct corret URIs for use with 'BWP-Minify'-PLugin
         *
         *  @since  0.1.2
         */
        $bwp_minify_plugins_url_addon = $bwp_minify_themes_url_addon 	= ''; 
        if ( class_exists('BWP_MINIFY') ) {
						$bwp_minify_plugins_url_addon = WP_CONTENT_URL;
						$bwp_minify_themes_url_addon 	= WP_CONTENT_URL.str_replace( WP_CONTENT_DIR, '', TEMPLATEPATH );
				}


        /**
         *  Load jQuery via Google CDN
         *  and fallback to local version
         *
         *  @since 0.1.2
         */
				$jquery = 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js';
				if ( !fopen($jquery, 'r') ) {
      	$jquery = $bwp_minify_themes_url_addon.'/js/libs/jquery-1.8.1.min.js';
				}
        $jquery_ver = '1.8.1';  
        wp_enqueue_script('jquery', $jquery, false, $jquery_ver, true);
        
        /**
         *  Load jQuery Plugins
         */                             
    		$childtheme_plugins_js = WP_CHILD_URL.'/js/jquery.plugins.js';
    		wp_enqueue_script( 'childtheme_plugins_js', $childtheme_plugins_js, array( 'jquery' ), YOUR_CHILDTHEME_VERSION, true );
        
        /**
         *  Load jQuery Caller
         */         
        $childtheme_main_js = WP_CHILD_URL.'/js/main.js';
    		wp_enqueue_script( 'childtheme_main_js', $childtheme_main_js, array( 'jquery' ), YOUR_CHILDTHEME_VERSION, true );
        
        /**
         *  Load threaded comments reply
         */          
        if ( is_single()  && get_option( 'thread_comments' ) )
				    wp_enqueue_script( 'comment-reply' );        
  	}
  	#add_action('wp_head', 'add_childtheme_scripts');



    
    
/***************************************************************************************************************
 *
 *      								Modification of all backend stuff, working in DOCROOT/wp-admin/
 *
 **************************************************************************************************************/
if ( is_admin() ) {

		/**
		 *  	Add special childtheme fonts to the css-files-array used with TinyMCE
		 *  	to style the visual-editor
		 *
     *    @since    YOUR-CHILDTHEME-NAME 0.1
		 *    @param    array   currently enqueued fonts
		 *    @return   array   enqueued fonts including your childtheme-font
		 */
		function add_font_to_editor_styles( $editor_styles_array ) {
     		$editor_styles_array[]	= 'css/fonts/childtheme-font.css';
				return $editor_styles_array;
		}
		#add_filter( 'cbstdsys_add_editor_style_array' , 'add_font_to_editor_styles' );




		/**
		 *    Decide which "Post-Formats" to use in the current theme
		 *
     *    @since    YOUR-CHILDTHEME-NAME 0.1
		 *    @param    array   supported Post-Formats by default
		 *    @return   array   array of supported Post-Formats
		 */
		function add_theme_supported_postformats( $theme_supported_postformats ) {
				return $theme_supported_postformats = array(
#			 				'aside', 		// title less blurb
#							'gallery', 	// gallery of images
#							'link', 		// quick link to other site
#							'image', 		// an image
#							'quote', 		// a quick quote
#							'status', 	// a Facebook like status update
#							'video', 		// video
#							'audio', 		// audio
#							'chat' 			// chat transcript*
					);
		}
		#add_filter( 'cbstdsys_theme_supported_postformats' , 'add_theme_supported_postformats' );



		/**
		 *    Remove the support for "Post-Formats"
		 *
		 *    @since    0.2.0
		 */
		function remove_theme_supported_postformats( ) {
				remove_theme_support( 'post-formats' );
		}
		#add_action( 'after_setup_theme','remove_theme_supported_postformats',999 );



		/**
		 *    Add childtheme admin scripts
		 *
		 *    @since    0.2.0
		 */
		function add_childtheme_admin_scripts ( ) {
        $childtheme_admin_js = WP_CHILD_URL.'/js/admin.js';
				wp_enqueue_script( 'childtheme_admin_js', $childtheme_admin_js, array( 'jquery' ), '', true );

		}
    #add_action('admin_head', 'add_childtheme_admin_scripts');



		/**
		 *  	Hide (not really remove) the sticky-posts option from the publish-metabox
		 *  	because we don't need it here
		 *
     *    @since    YOUR-CHILDTHEME-NAME 0.1
		 */
		function remove_sticky_option() {
		    global $post_type, $pagenow, $current_user;
		    if( 'post.php' != $pagenow && 'post-new.php' != $pagenow  )
		        return;
		    ?>
		    <style type="text/css">#sticky-span { display:none!important }</style>
		    <?php
		}
		#add_action( 'admin_print_styles', 'remove_sticky_option' );



    /**
     *  	Add extra MIME-Types allowed for uploading
     *
     *    @since    0.2.0
     *    @param    array		existing MIME-Types as 'TYPE' => 'NAME'
     *    @return   array		all including MIME-Types-array
     *
     */
    function add_extra_upload_mimes ( $existing_mimes ) {
      	$existing_mimes['MIME'] = "application/type";
      	return $existing_mimes;
    }
    #add_filter( 'upload_mimes', 'add_extra_upload_mimes' );
    
    


		/**
		 *    Disable cbstdsys-Options without editing interfaces;
		 *    means all ugly hardcoded stuff like "Carsten Bach <mail@carsten-bach.de>
		 *
     *    @since    YOUR-CHILDTHEME-NAME 0.1
		 */
		// wp-admin footer text
		function childtheme_remove_filters(){
		    remove_all_filters( 'admin_footer_text', null );
		}
		#add_action( 'after_setup_theme', 'childtheme_remove_filters' );

		// Remove cbstdsys-Update-Notice
		#remove_action( 'admin_notices', 'update_info_for_all_users' );



		/**
			*   Define user level with access to the WP backend
			*
      *   @since    YOUR-CHILDTHEME-NAME 0.1
			*/
		function childtheme_keep_out_user_by_capability ( ) {
		   return 'publish_posts';
		}
		#add_filter( 'cbstdsys_keep_out_user_by_capability', 'childtheme_keep_out_user_by_capability' );
    
    
    
    /**
     *    Define "webdevelopment partner" dashboard widget
     *
     *    @since    0.1.8
     */
    #add_filter( 'cbstdsys_dashboard_widget_webdevpartner_title', 'News from YOUR-CHILDTHEME-NAME');
    #add_filter( 'cbstdsys_dashboard_widget_webdevpartner_feed', 'http://www.YOUR-CHILDTHEME-NAME.domain/feed/');

    
    
    /**
     *    Register custom nav menus, different to "Main" & "Utility"
     *
     *  @since    YOUR-CHILDTHEME-NAME 0.1
     */
		function register_childtheme_nav_menus ( ) {
				$nav_menus  =  array(
					'header'  => __( 'header-navigation', YOUR_CHILDTHEME_TEXTDOMAIN ),
          'primary' => __( 'primary-navigation', YOUR_CHILDTHEME_TEXTDOMAIN ),
					'utility' => __( 'utility-navigation', YOUR_CHILDTHEME_TEXTDOMAIN ),
				);
				return $nav_menus;
		}
		#add_filter( 'cbstdsys_theme_supported_nav_menus', 'register_childtheme_nav_menus' );
		


    
/***************************************************************************************************************
 *
 *      								Modification of all frontend stuff, working in DOCROOT/
 *
 **************************************************************************************************************/
} else {


    

		/**
		 *  Add childtheme childtheme styles
		 *
     *  @since    YOUR-CHILDTHEME-NAME 0.1
		 */
		function add_styles() {

				$child_style  = WP_CHILD_URL.'/css/base.css';
				wp_enqueue_style('child-styles', $child_style, array('style-css'), YOUR_CHILDTHEME_VERSION );

				$child_custom_styles  = WP_CHILD_URL.'/style.css';
				wp_enqueue_style('child-custom-styles', $child_custom_styles, array('child-styles'), YOUR_CHILDTHEME_VERSION );
				
				$child_font_style  = WP_CHILD_URL.'/css/fonts/fonts.css';
				wp_enqueue_style('child-font', $child_font_style, array('child-styles'), YOUR_CHILDTHEME_VERSION );

  			$tiny_content_elements  = WP_CHILD_URL.'/css/tiny-content-elements.css';
				wp_enqueue_style('tiny-content-elements', $tiny_content_elements, array('child-styles'), YOUR_CHILDTHEME_VERSION );

		}
  	#add_action( 'wp_print_styles', 'add_styles' );



    /**
     *  Remove default stylesheets & scripts from CBSTDSYS Parentheme
     *  
     *  @since    YOUR-CHILDTHEME-NAME 0.1
     */                   
    function remove_cbstdsys_default_styles ( ) {
        
        // html5bp, Eric Meyers reset-css, WP default classes, utility-classes
#        wp_dequeue_style( 'style-css' );  
              
        // oldskool mobile devices
#        wp_dequeue_style( 'handheld-css' );        
              
        // searchform, contact-form-7, commentform, all types of input, select, textarea, etc.
#        wp_dequeue_style( 'forms-css' );
        
        // fancybox
#        wp_dequeue_style( 'fancybox-css' );      
        
        // comments, trackbacks & threaded comments
#        wp_dequeue_style( 'comment-css' );    
        
    }
    #add_action( 'wp_print_styles', 'remove_cbstdsys_default_styles' );
    


		/**
		 *		Remove the fancy-amp filter
		 *
		 */
		function remove_fancy_amp_filter() {
				remove_filter('the_title', 'fancy_amp');
				remove_filter('widget_title', 'fancy_amp');
		}
		#add_action( 'after_setup_theme', 'remove_fancy_amp_filter' );



    /**
     *    Serve humans.txt
     *    plugged function from cb-std-sys - Parent-Theme
     *    
     *    @since    YOUR-CHILDTHEME-NAME 0.1
    */
    function serve_humanstxt(){
    	header( 'Content-Type: text/plain; charset=utf-8' );
    ?>
    /* TEAM */
        Web Design:   
        Contact via : 
        From:         
        
        Programming:  Carsten Bach
        Contact via : http://carsten-bach.de
        Twitter:      @carstenbach
        From:         Stuttgart, Baden-Würrtemberg, Germany
    
    /* SITE */
        Last update: <?php echo mysql2date('l, d.m.Y H:i:s', get_lastpostmodified('GMT'), false)."\n"; ?>
        Language: <?php echo WPLANG."\n"; ?>
        Doctype: HTML5
        Tools: WordPress, PHP
        Standards: CSS3, WAI-ARIA, BITV 1
        Components: Modernizr, jQuery, html5boilerplate, Starkers Theme, Roots Theme
    <?php
      die();
    }
     


		/**
		 *    Change copyright-text used in footer, <meta copyright>, <rss copyright>
		 *
		 *    @since  	0.2.0
		 *    @param    str     existing copyright-note
		 *    @return   str     new copyright-note
		 */
		function filter_childtheme_copyright( $copy ) {
				return get_bloginfo( 'name' ).". ".__( 'All rights reserved', YOUR_CHILDTHEME_TEXTDOMAIN ).".";
		}
    #add_filter( 'cb_std_sys_copyright', 'filter_childtheme_copyright' );




		/**
		 *    Change copyright-link used in footer
		 *
		 *    @since  	0.2.0
		 *    @param    str     a-tag to blog-home with rel="home"
		 *    @return   str     filtered a-tag
		 */
		function filter_childtheme_copyright_link( $link ) {
				$link  =	'<a class="copy-note" '.
										 'href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.de" '.
										 'title="'. esc_attr( strip_tags( cb_std_sys_copyright() ) ).'" '.
										 'rel="license">'.
										 cb_std_sys_copyright().
										 '</a>';
				return  $link;
		}
    #add_filter( 'cb_std_sys_copyright_link', 'filter_childtheme_copyright_link' );



    /**
     *    Add HTML classes to existing markup from the parent theme
     *    without the need to change files
     *    
     *    @since    YOUR-CHILDTHEME-NAME 0.1
		 *    @param    str     empty string
		 *    @return   str     whitespace seperated string of css classes
		 */
    #add_filter( 'cbstdsys_add_html_class_for_id_top', create_function('$a', "return null;") );                                      
    #add_filter( 'cbstdsys_add_html_class_for_id_content', create_function( '$a', 'return "clearfix";' ) );  
    #add_filter( 'cbstdsys_add_html_class_for_id_access', create_function( '$a', 'return null;' ) );    
    #add_filter( 'cbstdsys_add_html_class_for_id_colophon', create_function( '$a', 'return "clearfix";' ) );



		/**
		 *    Disable cbstdsys-Options without editing interfaces;
		 *    means all ugly hardcoded stuff like "Carsten Bach <mail@carsten-bach.de>
		 *
     *    @since    YOUR-CHILDTHEME-NAME 0.1
		 */
		function childtheme_login_headertitle( $title_attr ) {
				return esc_attr( get_bloginfo( 'name', 'display' ) );
		}
    #add_filter( 'login_headertitle', 'childtheme_login_headertitle', 999 );
    
    

    

    /**
     *    Add additional wrap around loop-output
     *  
     *    @since    YOUR-CHILDTHEME-NAME 0.1
     */
    #add_filter( 'cbstdsys_wrap_loop_markup', create_function('', 'return "true";' ) );
    
    
                    
  
   
  	/**
  	 *  Remove excerpt filters from parent theme
  	 * 
     *  @since    YOUR-CHILDTHEME-NAME 0.1
  	 */
  	function childtheme_remove_excerpt_filters() {
  		remove_filter( 'excerpt_more', 'cbstdsys_auto_excerpt_more' );
  		remove_filter( 'the_excerpt', 'cbstdsys_custom_excerpt_more' );
  	}
  	#add_action( 'init', 'childtheme_remove_excerpt_filters' );

    
    
    
  	/**
  	 *  Remove excerpt ellipsis completely
  	 * 
     *  @since    YOUR-CHILDTHEME-NAME 0.1
  	 */
  	function childtheme_excerpt_more( $more ) {
  		return '';
  	}
  	#add_filter( 'excerpt_more', 'childtheme_excerpt_more' );

    

    
    
    
} // end else is_admin()






?>