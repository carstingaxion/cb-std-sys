<?php
		/*
		Plugin Name: Wordpress Color Picker
		Plugin URI: http://ijlalhasnain.com
		Description: Plugin for displaying ColorPicker UI Control in Post/Page edit panel.
		Author: M Ijlal Hasnain
		Version: 1.0
		Author URI: http://ijlalhasnain.com
		*/

/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'colorpicker_add_custom_box');

/* Use the save_post action to do something with the data entered */
add_action('save_post', 'colorpicker_save_postdata');

/* Adds a custom section to the "advanced" Post and Page edit screens */
function colorpicker_add_custom_box() {

    add_meta_box( 'colorpicker_sectionid', __( 'Color Picker', 'colorpicker_textdomain' ), 
                'colorpicker_inner_custom_box', 'post', 'advanced','high' );
    add_meta_box( 'colorpicker_sectionid', __( 'Color Picker', 'colorpicker_textdomain' ), 
                'colorpicker_inner_custom_box', 'page', 'advanced','low' );

	wp_enqueue_script('iColorPicker', WP_PLUGIN_URL . '/colorpicker/jscolor/jscolor.js', array('jquery'));
	#wp_register_script('jquery', '/wp-includes/js/jquery/jquery.js', false, '1.4.2', true);
	#wp_enqueue_script('jquery');
}
   
/* Prints the inner fields for the custom post/page section */
function colorpicker_inner_custom_box() {

  // Use nonce for verification
  echo '<input type="hidden" name="colorpicker_noncename" id="colorpicker_noncename" value="' . 
  wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

  // The actual fields for data entry
  global $post;
  $color_value=get_post_meta($post->ID,'colorpicker',true);
  echo '<label for="colorpicker_new_field">' . __("Select a color by clicking in the box", 'myplugin_textdomain' ) . '</label> ';
  echo '<input type="text" name="colorpicker_new_field" size="25" id="colorpicker_new_id" class="color {hash:true, adjust:false}" value="'.$color_value.'" />';
}

/* Prints the edit form for pre-WordPress 2.5 post/page */

/* When the post is saved, saves our custom data */
function colorpicker_save_postdata( $post_id ) {

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['colorpicker_noncename'], plugin_basename(__FILE__) )) {
    return $post_id;
  }

  // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
  // to do anything
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
    return $post_id;

  
  // Check permissions
  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
      return $post_id;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
      return $post_id;
  }

  // OK, we're authenticated: we need to find and save the data

  $mydata = $_POST['colorpicker_new_field'];
  update_post_meta($post_id, 'colorpicker', $mydata);
  return $mydata;
}
		
?>