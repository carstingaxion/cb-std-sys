<?php
/*******************************************************************************
 *
 *  Global helper functions
 *
 *******************************************************************************/

/**
 *  check if shortcode is used
 *
 *  @since    0.0.7
 *
 *  @params   $shortcode  $string
 *  @params   $posts      $obj
 *  @return   boolean     true|false
 *
 */
function conditionally_load_if_shortcode_used($shortcode, $posts){
		if (empty($posts)) return false;
		$shortcode_found = false;
		foreach ($posts as $post) {
			if (stripos($post->post_content, $shortcode) !== false ) {
				$shortcode_found = true; // bingo!
				break;
			}
		}
		return $shortcode_found;
}

/**
 *  Check Array of $post-Object for special Value
 *
 *  @since  0.1.2
 *
 *  @params   $array    	$array of $objects
 *  @params   $property   $string
 *  @params   $value   		$string
 *  @return   boolean     true|false
 *
 *  @source http://www.php.net/manual/en/function.in-array.php#98455
 */
function find_value_in_postobjects_array($array, $property, $value) {
    $flag = false;
    foreach($array as $object) {
        if(!is_object($object) || !property_exists($object, $property)) {
            return false;
        }
        if($object->$property == $value) {
            $flag = true;
        }
    }
    return $flag;
}



// http://www.php.net/manual/en/function.array-search.php#106107
function multidimensional_search($parents, $searched, $key_or_value = 'key' ) {
  if (empty($searched) || empty($parents)) {
    return false;
  }

  foreach ($parents as $key => $value) {
    $exists = true;
    foreach ($searched as $skey => $svalue) {
      $exists = ($exists && IsSet($parents[$key][$skey]) && $parents[$key][$skey] == $svalue);
    }
    if($exists && $key_or_value == 'key' ){ return $key; }
    if($exists && $key_or_value == 'value' ){ return $value; }
  }

  return false;
}

/**
 *  Get CB-STD-SYS Options
 *
 *  @since  0.1.5
 */
function cbstdsys_opts( $option = '' ) {
    $cbstdsys_opts = get_option('cbstdsys_options');
    if ( !$option ){
    		return $cbstdsys_opts;
		} else {
    		return $cbstdsys_opts[$option];
		}
}



/**
 *  Keep all visitors outside from Admin-Panel and Loginscreen
 *
 *  @since  0.1.5
 *  
 *  @todo set filter /redaktion  
 */
function keep_out_subscribers_and_anonymous(){
			// define filter for user cap, needed to access backend
			$keep_out_cap = apply_filters( 'cbstdsys_keep_out_user_by_capability', 'edit_others_posts' );

			// Look for the presence of /wp-admin in the url
			if ( ( stripos( $_SERVER['REQUEST_URI'], '/wp-admin' ) !== false  ||  stripos( $_SERVER['REQUEST_URI'], '/wp-login')  !== false )
						&&
						// Disallow anonymous users and such with missing caps
						( !is_user_logged_in() || !current_user_can( $keep_out_cap ) )
						&&
						// Allow calls to async-upload.php
						stripos($_SERVER['REQUEST_URI'],'async-upload.php') == false
						&&
						// Allow calls to admin-ajax.php
						stripos($_SERVER['REQUEST_URI'],'admin-ajax.php') == false
						&&
						// we're not comming from our login site
						stripos( $_SERVER['HTTP_REFERER'], '/redaktion' ) === false

				 ) {
							wp_redirect( WP_SITEURL, 302 );
							exit;
			}
}
add_action( 'init', 'keep_out_subscribers_and_anonymous', 0 );


/**
 *  Return custom wp_die handler to match theme
 *  
 *  @source    http://www.velvetblues.com/web-development-blog/custom-wordpress-comment-error-page/
 *  @since     0.2.1
 */
function get_customized_wp_die_handler() {
    return 'customized_wp_die_handler';
} 
add_filter('wp_die_handler', 'get_customized_wp_die_handler');


/**
 *  Setup custom wp_die handler
 *  
 */
function customized_wp_die_handler( $message, $title='', $args = array( 'back_link' => true ) ) {
  
    global $pagenow;
    
    $errorTemplate = getenv('DOCUMENT_ROOT').'/wp-content/wp_themed_error.php';
    $defaults = array( 
       'response' => 500
      ,'back_link' => true
    );
    $have_gettext = function_exists('__');
    
    if( file_exists( $errorTemplate ) ) {

        $r = wp_parse_args($args, $defaults);
      
        if ( function_exists( 'is_wp_error' ) && is_wp_error( $message ) ) {
            if ( empty( $title ) ) {
                $error_data = $message->get_error_data();
                if ( is_array( $error_data ) && isset( $error_data['title'] ) )
                    $title = $error_data['title'];
            }
            
            $errors = $message->get_error_messages();
            
            switch ( count( $errors ) ) :
                case 0 :
                    $message = '';
                    break;
                case 1 :
                    $message = "<p>{$errors[0]}</p>";
                    break;
                default :
                    $message = "<ul>\n\t\t<li>" . join( "</li>\n\t\t<li>", $errors ) . "</li>\n\t</ul>";
                    break;
            endswitch;
        } elseif ( is_string( $message ) ) {
            $message = "<p>$message</p>";
        }
        if ( isset( $r['back_link'] ) && $r['back_link'] ) {
            $back_text = $have_gettext? __('&laquo; Back') : '&laquo; Back';
            $message .= "\n<p><a class='button' href='javascript:history.back()'>$back_text</a></p>";
        }
        if ( empty($title) )
            $title = $have_gettext ? __('Fehler') : 'Fehler';
      
        $wp_themed_error_body_class  = 'wp-die-error ' . str_replace( '.', '-', $pagenow);
        $wp_themed_error_title_tag   = $title;  
        $wp_themed_error_title       = $title;
        $wp_themed_error_home_link   = '<a href="'. WP_SITEURL .'" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) .' Startseite" rel="home">' . get_option( 'blogname' ) .' Startseite</a>'; 
        $wp_themed_error_content     = str_replace( '<strong>FEHLER</strong>: ','', $message );
         
        include_once $errorTemplate;
        die();
        
    } else {
        _default_wp_die_handler( $message, $title, $args );
    }
}        

/*******************************************************************************
 *
 *  Basic Theme Settings
 *
 *******************************************************************************/


/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the tiny_content-elements.css stylesheet.
 *
 *  It is not possible to have several conditional $content_width based on template
 *  http://wordpress.stackexchange.com/a/6500
 */
if ( ! isset( $content_width ) )
		$content_width = 460;
	
	
// Adding WP 3+ Functions & Theme Support
function cbstdsys_theme_support() {

		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		load_theme_textdomain( 'cb-std-sys', TEMPLATEPATH . '/languages' );
		$locale = get_locale();
		$locale_file = TEMPLATEPATH . "/languages/$locale.php";
		if ( is_readable( $locale_file ) )
				require_once( $locale_file );
		
		
		// This theme styles the visual editor with "editor-style.css" to match the theme style.
    $add_editor_style_array = array();
		$add_editor_style_array[]  = 'css/tiny-content-elements.css';
		$add_editor_style_array = apply_filters( 'cbstdsys_add_editor_style_array', $add_editor_style_array );
		add_editor_style( $add_editor_style_array );


		// add support for post thumbnails
		// sizes handled in functions_userweb.php
		add_theme_support('post-thumbnails');


		// let admins choose a custom background
		if ( cbstdsys_opts( 'd_bg_images' ) ) {
        $defaults = array(
        	'default-color'          => '000',
        	'default-image'          => '',
        	'wp-head-callback'       => '_custom_background_cb',
        	'admin-head-callback'    => '',
        	'admin-preview-callback' => ''
        );
        add_theme_support( 'custom-background', apply_filters( 'cbstdsys_custom_background_args', $defaults ) );    
    }


		// let admins choose a custom background header-image
		if ( cbstdsys_opts( 'd_header_images' ) ) {

        /**
         *  DEPRECATED since 3.4
         *  
                                   		    
        add_custom_image_header( create_function('$a', 'return null;'), create_function('$a', 'return null;')  );
				
        if ( ! defined( 'HEADER_TEXTCOLOR' ) )
					 define( 'HEADER_TEXTCOLOR', '' );

				// Path to header Image, the %s is a placeholder for the theme template directory URI.
				// to add additional images use register_default_headers()
				if ( ! defined( 'HEADER_IMAGE' ) )
					 define( 'HEADER_IMAGE', '%s/images/headers/path.jpg' );

				// The height and width of your custom header
				if ( ! defined( 'HEADER_IMAGE_WIDTH' ) )				
            define( 'HEADER_IMAGE_WIDTH', 960 );
				if ( ! defined( 'HEADER_IMAGE_HEIGHT' ) )				
            define( 'HEADER_IMAGE_HEIGHT', 200 );

				// Don't support text inside the header image.
				if ( ! defined( 'NO_HEADER_TEXT' ) )
					 define( 'NO_HEADER_TEXT', true );

        *
        *
        */                                        
                        
        $defaults = array(
        	'default-image'          => '',
        	'random-default'         => false,
        	'width'                  => 0,
        	'height'                 => 0,
        	'flex-height'            => false,
        	'flex-width'             => false,
        	'default-text-color'     => '',
        	'header-text'            => true,
        	'uploads'                => true,
        	'wp-head-callback'       => '',
        	'admin-head-callback'    => '',
        	'admin-preview-callback' => '',
        );
				add_theme_support( 'custom-header', apply_filters( 'cbstdsys_custom_header_args', $defaults ) );
		}


		// Add feed URLs to wp_head
		if ( cbstdsys_opts( 'm_blog' ) )
				add_theme_support('automatic-feed-links');


		// adding post format support
		$theme_supported_postformats	=	array(
				'aside', // title less blurb
				'gallery', // gallery of images
				'link', // quick link to other site
				'image', // an image
				'quote', // a quick quote
				'status', // a Facebook like status update
				'video', // video
				'audio', // audio
				'chat' // chat transcript
		);
		add_theme_support( 'post-formats', apply_filters( 'cbstdsys_theme_supported_postformats', $theme_supported_postformats )	);


		// Add support for WP 3+ menus
		add_theme_support( 'menus' );
		$nav_menus  =  array(
			'primary' => __( 'Main Navigation', 'cb-std-sys' ),
			'utility' => __( 'Utility Navigation', 'cb-std-sys' ),
		);
		register_nav_menus( apply_filters( 'cbstdsys_theme_supported_nav_menus', $nav_menus ) );

		// 	disable comments on pages
		//  @todo add an option
		// 	http://wpengineer.com/2302/how-to-disable-comments-for-wordpress-pages-in-any-theme/
		$add_a_settings_option  = true;
		if ( $add_a_settings_option ) {
				define('CBSTDSYS_DISABLE_PAGE_COMMENTS', true);
		}
}
add_action('after_setup_theme','cbstdsys_theme_support');



/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since 0.1.8
 */
function cbstdsys_page_menu_args( $args ) {
		$args['show_home'] = true;
		return $args;
}
add_filter( 'wp_page_menu_args', 'cbstdsys_page_menu_args' );



/**
 *  Modify Admin Bar if is used
 *
 *  @since  0.1.8
 *  @source http://wpdevel.wordpress.com/2011/12/07/admin-bar-api-changes-in-3-3/
 */
function modify_admin_bar ( $wp_admin_bar ) {

		global $current_user;
	
		// make changes only to non-super-admins
		if ( !in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) ) {

				// disable by default
				$wp_admin_bar->remove_node('wp-logo');
				$wp_admin_bar->remove_node('themes');
				$wp_admin_bar->remove_node('updates');

				// disable all backUpWP stuff
				$wp_admin_bar->remove_node('backwpup');
				$wp_admin_bar->remove_node('backwpup-auftrag');
				$wp_admin_bar->remove_node('backwpup-job');
				
				// disable "Members"-Plugins stuff
				$wp_admin_bar->remove_node('members-new-role');
				
        // Remove 'Posts'
        if ( ! cbstdsys_opts('m_blog') )
						$wp_admin_bar->remove_node('post');

        // Remove 'Links'.
        if ( ! cbstdsys_opts('m_links') )
						$wp_admin_bar->remove_node('link');

				// disable background links
        if ( ! cbstdsys_opts('d_bg_images') )
						$wp_admin_bar->remove_node('background');

				// disable comments links
				if ( ! cbstdsys_opts('m_comments') )
						$wp_admin_bar->remove_node('comments');

				// disable search
				if ( ! cbstdsys_opts('m_search') )
						$wp_admin_bar->remove_node('search');
		}
}
add_action( 'admin_bar_menu', 'modify_admin_bar',999 );



/**
 * Register Sidebars with widgetized areas
 *
 * @since   0.1.4
 * @source	Twenty Ten 1.0
 * @uses register_sidebar
 */
function cbstdsys_sidebars_init() {
  $sidebars = array(
      array(__( 'Primary Widget Area', 'cb-std-sys' ),'primary-widget-area', __( 'The primary widget area', 'cb-std-sys' )),
      array(__( 'Secondary Widget Area', 'cb-std-sys' ),'secondary-widget-area', __( 'The secondary widget area', 'cb-std-sys' )),
      array(__( 'First Footer Widget Area', 'cb-std-sys' ),'first-footer-widget-area', __( 'The first footer widget area', 'cb-std-sys' )),
      array(__( 'Second Footer Widget Area', 'cb-std-sys' ),'second-footer-widget-area', __( 'The second footer widget area', 'cb-std-sys' )),
      array(__( 'Third Footer Widget Area', 'cb-std-sys' ),'third-footer-widget-area', __( 'The third footer widget area', 'cb-std-sys' )),
      array(__( 'Fourth Footer Widget Area', 'cb-std-sys' ),'fourth-footer-widget-area', __( 'The fourth footer widget area', 'cb-std-sys' )),
  );
  $sidebars = apply_filters( 'cbstdsys_sidebars_init' ,$sidebars );
  foreach ($sidebars as $sidebar) {
	  	register_sidebar(array(
	      'name'=> $sidebar[0],
	      'id'  => $sidebar[1],
	      'description' => $sidebar[2],
	  		'before_widget' => '<article id="%1$s" class="widget %2$s"><div class="container">',
	  		'after_widget' => '</div></article>',
	  		'before_title' => '<h3>',
	  		'after_title' => '</h3>'
	  	));
  }

}
add_action( 'widgets_init', 'cbstdsys_sidebars_init' );



?>