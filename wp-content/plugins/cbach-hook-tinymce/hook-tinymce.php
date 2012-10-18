<?php
/*
Plugin Name: Hook TinyMCE
Plugin URI: 
Description: 
Version: 0.0.4
Author: Carsten Bach
Author URI: http://carsten-bach.de/
Last Change: 

0.0.4 - 11.10.2012
  fixed missing icons.gif from wp-includes
  Added "Definition List" support
  Added the "safari" Plugin, that fixes many errors for mac-users
  Removed disabling of flash-uploader; it's unsused in current WP Versions
  
0.0.3 - 28.08.2012
  fixed "undefined index" errors


0.0.2
*/   
  	// Setup localization
  	load_plugin_textdomain( 'cbach-hook-tinymce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		$cbstdsys_opts = get_option('cbstdsys_options');
    #$current_user = wp_get_current_user();
		#echo $current_user;
    function hide_html_editor(){
      global $current_user,$cbstdsys_opts; #echo '########'.$current_user;
      if ( !in_array($current_user->ID, $cbstdsys_opts['a_admin_user_IDs'] ) )
      echo '<script type="text/javascript">jQuery(document).ready(function($) {   $(".wp-switch-editor").remove(); });</script>';
    }
    add_action('admin_footer','hide_html_editor',1000);


    add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );    
    
    /*** allow more tags in TinyMCE including iframes */
    function change_mce_options($options) {
    	$ext = 'pre[id|name|class|style],iframe[align|longdesc|name|width|height|frameborder|scrolling|marginheight|marginwidth|src]';	
    	$html5Elements = array(
    		'article[#|cite|pubdate]',
    		'aside[#]',
    		'audio[#]',
    		'canvas[#]',
    		'command[#]',
    		'datalist[#]',
    		'details[#]',
    		'figure[#]',
    		'figcaption[#]',
    		'footer[#]',
    		'header[#]',
    		'hgroup[#]',
    		'mark[#]',
    		'meter[#]',
    		'nav[#]',
    		'output[#]',
    		'progress[#]',
    		'section[#]',
    		'summary[#]',
    		'time[#|datetime]',
    		'video[#]'
    	);
    	if (isset($initArray['extended_valid_elements'])) {
    		$options['extended_valid_elements'] .= ',' . $ext;
    		$options['extended_valid_elements'] .= ',' . $html5Elements;
    	} else {
    		$options['extended_valid_elements'] = $ext;
    	}
    	return $options;
    }
    
    function cbstdsys_styleselect_before_init( $settings ) {
        $settings['theme_advanced_blockformats'] = 'p,h2,h3,h4,h5,h6,dt,dd';
    
        $style_formats = array(
            array(
            	'title' => __('highlight','cbach-hook-tinymce'),
            	'inline' => 'span',
            	'classes' => 'highlight',
            	'wrapper' => true
            ),
            array(
            	'title' => __('dimmed','cbach-hook-tinymce'),
            	'inline' => 'span',
            	'classes' => 'dimmed',
            	'wrapper' => true
            ),
            array(
            	'title' => __('info','cbach-hook-tinymce'),
            	'block' => 'div',
            	'classes' => 'info',
            	'wrapper' => true
            ),
            array(
            	'title' => __('memo','cbach-hook-tinymce'),
            	'block' => 'div',
            	'classes' => 'memo',
            	'wrapper' => true
            ),
            array(
            	'title' => __('important','cbach-hook-tinymce'),
            	'block' => 'div',
            	'classes' => 'important',
            	'wrapper' => true
            )
            /*
            ,
            array(
            	'title' => __('column align left','cbach-hook-tinymce'),
            	'block' => 'div',
            	'classes' => 'cloumn-half-left',
            	'wrapper' => true
            ),
            array(
            	'title' => __('column align right','cbach-hook-tinymce'),
            	'block' => 'div',
            	'classes' => 'cloumn-half-right',
            	'wrapper' => true
            ) 
                 */      
        );
    
        $settings['style_formats'] = json_encode( $style_formats );
    
        return $settings;
    
    }
    
    function cbstdsys_tiny_plugins_register($plugin_array) {
        $external_plugins = array(
          'xhtmlxtras',
          'table',
          'visualchars',
          'mailto',
          'nonbreaking',
          'advlist',
          'safari',
//          'definitionlist'
        );
        foreach( $external_plugins as $v ) {
            $new_plugin_array[$v] = plugins_url( '/tinymce/'.$v.'/editor_plugin.js', __FILE__ );
        }
        $plugin_array = array_merge( $plugin_array, $new_plugin_array );
        return $plugin_array;
    }
    
    function enable_more_buttons_first_row($mce_buttons_one) {
        $mce_buttons_one = array(
          'bold', 
          'italic', 
          'underline', 
          '|', 
          'ins', 
          'del', 
          'abbr',
          '|',
          'bullist', 
          'numlist',
          'definitionlist',              
          '|',
          'outdent', 
          'indent',  
          '|', 
          'link', 
          'anchor',
          'mailto',
          'unlink', 
          '|',  
          'wp_more', 
          'wp_page', 
          '|', 
          'spellchecker', 
          'fullscreen',
/*
          'wp_adv',
          'wp_adv_start', // just there, that no buttons will be hidden
          'wp_adv_end',  // just there, that no buttons will be hidden
*/
           );
        return $mce_buttons_one;
    }
    function enable_more_buttons_second_row($mce_buttons_two) {
        $mce_buttons_two = array(   
          'styleselect',
          'formatselect', 
          '|', 
          'sub',
          'sup',
          '|',      
          'blockquote', 
          'cite',    
          '|', 
          'undo', 
          'redo', 
          '|',    
          'pastetext', 
          'pasteword', 
          '|',          
          'removeformat', 
          'cleanup', 
          '|', 
          'charmap',
          'visualchars', 
          'nonbreaking',
          '|', 
          'separator',
          

           );
        return $mce_buttons_two;
    }
    function enable_more_buttons_third_row($mce_buttons_three) {
        $mce_buttons_three = array(          

          'tablecontrols',          
        );
        return $mce_buttons_three;
    }
    
    
    // init process for button control
    function cbstdsys_addbuttons() {
      // Don't bother doing this stuff if the current user lacks permissions
      if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
        return;
    
      // Add only in Rich Editor mode
      if ( get_user_option('rich_editing') == 'true') {
        add_filter( 'tiny_mce_before_init', 'cbstdsys_styleselect_before_init' );
        add_filter( 'tiny_mce_before_init', 'change_mce_options');        
        add_filter( 'mce_external_plugins', "cbstdsys_tiny_plugins_register");
        add_filter( 'mce_buttons', 'enable_more_buttons_first_row',1);
        add_filter( 'mce_buttons_2', 'enable_more_buttons_second_row',1);
        add_filter( 'mce_buttons_3', 'enable_more_buttons_third_row',1);    
      }
    }    
    add_action('init', 'cbstdsys_addbuttons'); 
    
    /**
     *  disable the flash uploader
     *  
     *  @since  0.0.1
     *  
     */
#     add_filter('flash_uploader', create_function('$flash', 'return false;'));                        
?>