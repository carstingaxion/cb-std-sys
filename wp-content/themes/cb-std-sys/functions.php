<?php

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640;

/** Tell WordPress to run twentyten_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'twentyten_setup' );

if ( ! function_exists( 'twentyten_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function twentyten_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
  add_editor_style('css/editor-style.css');

	// Post Format support. You can also use the legacy "gallery" or "asides" (note the plural) categories.
	add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );
  
  // http://codex.wordpress.org/Post_Formats
  // add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));



  // Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'cb-std-sys', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Main Navigation', 'cb-std-sys' ),
		'utility' => __( 'Utility Navigation', 'cb-std-sys' ),
	) );

	// This theme allows users to set a custom background
	add_custom_background();

	// Your changeable header business starts here
	if ( ! defined( 'HEADER_TEXTCOLOR' ) )
		define( 'HEADER_TEXTCOLOR', '' );

	// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
	if ( ! defined( 'HEADER_IMAGE' ) )
		define( 'HEADER_IMAGE', '%s/images/headers/path.jpg' );

	// The height and width of your custom header. You can hook into the theme's own filters to change these values.
	// Add a filter to twentyten_header_image_width and twentyten_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyten_header_image_width', 940 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyten_header_image_height', 198 ) );


	// Don't support text inside the header image.
	if ( ! defined( 'NO_HEADER_TEXT' ) )
		define( 'NO_HEADER_TEXT', true );




	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	/*
  register_default_headers( array(
		'berries' => array(
			'url' => '%s/images/headers/berries.jpg',
			'thumbnail_url' => '%s/images/headers/berries-thumbnail.jpg',
			// translators: header image description 
			'description' => __( 'Berries', 'cb-std-sys' )
		)
	) );
	*/
}
endif;






/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyten_page_menu_args' );







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

/**
 *  Debug Helper
 *
 *  @since  0.1.2

function debug ( $var, $arg = '' ) {
		$current_user = wp_get_current_user();
		if ( in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) || defined( 'WP_LOCAL_DEV' ) ) {
				include_once( getenv("DOCUMENT_ROOT").'/FirePHPCore/fb.php' );
				switch ($arg) {
						case 'trace':
								fb( $var, FirePHP::TRACE );
								break;
						case 'e':
								fb( $var, FirePHP::ERROR );
								break;
						case 'w':
								fb( $var, FirePHP::WARN );
								break;
						case 'i':
								fb( $var, FirePHP::INFO );
								break;
						default:
								fb( $var, FirePHP::LOG );
								break;
				}
		}
}
 */
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
 */
function keep_out_subscribers_and_anonymous(){
			// Look for the presence of /wp-admin in the url
			if ( ( stripos( $_SERVER['REQUEST_URI'], '/wp-admin' ) !== false  ||  stripos( $_SERVER['REQUEST_URI'], '/wp-login')  !== false )
						&&
						// Allow calls to async-upload.php
						stripos($_SERVER['REQUEST_URI'],'async-upload.php') == false
						&&
						// Allow calls to admin-ajax.php
						stripos($_SERVER['REQUEST_URI'],'admin-ajax.php') == false
						&&
						// we're not comming from our login site
						stripos( $_SERVER['HTTP_REFERER'], '/redaktion' ) === false
						&&
						// Disallow anonymous users and such with missing caps
						( !is_user_logged_in() || !current_user_can( 'edit_others_posts' ) )
				 ) {
							wp_redirect( WP_SITEURL, 302 );
							exit;
			}
}
add_action( 'init', 'keep_out_subscribers_and_anonymous', 0 );
/*******************************************************************************
 *
 *  Globals
 *
 *******************************************************************************/
include_once 'inc/changelog.php';
include_once 'inc/cbstdsys_options.php';
include_once 'inc/hook_search.php';
/*******************************************************************************
 *
 *  Dashboard and Admin-Add-Ons
 *
 *******************************************************************************/
if ( is_admin() ) {    
    include_once 'inc/hook_backend.php';
/*******************************************************************************
 *
 *  Theme and Frontend Add-Ons
 *  
 *******************************************************************************/
} else {
    include_once 'inc/hook_frontend.php';
    #include_once 'inc/template_shortcodes.php';
}
/*******************************************************************************
 *
 *  customized user web functions
 *
 *	use only when child-theme are not used, child themes can take its own functions.php
 *
 *******************************************************************************/
include_once 'functions_userweb.php';



      
?>