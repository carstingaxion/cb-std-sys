<?php

if ( is_admin() ) {

		/**
		 *
		 *  Rename files during upload
		 *  OldFilename.jpeg  ->  (Attachement-Title)-(Title of post, the file is attached to)-(Website-Url)-###.jpg
		 *
		 *  @source Based on 'Rename Media'-Plugin http://urbangiraffe.com/plugins/rename-media/
		 *  @since  0.1.1
		 *
		 */
#http://wordpressapi.com/2012/01/11/change-uploaded-image-name-to-post-slug-during-upload-using-variables/
		function rename_media_save( $post, $attachment ) {

#debug( $post['ID'] .' ===> '. $post['post_parent']);


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

					// Update all posts, with new filepath
					// to avoid 404

#$tochange = get_posts('numberposts=-1&post_type=any');
#$tochange = get_posts('include=900,914&post_type=any');
#debug($tochange);
/*
foreach($tochange as $post_to_change){
setup_postdata($post_to_change);
debug($post_to_change);
debug($old_filename);
debug($new_filename);
				$contentchange = str_replace($old_filename, $new_filename, $post_to_change->post_content ) ;
debug($contentchange);

				$tochange = array();
        $tochange['ID'] = $post->ID;
        $tochange['post_content'] = $contentchange;
        #$out = wp_update_post($tochange);
        unset($tochange);

    }
wp_reset_query();
 */
				}
			}

			return $post;
		}

		#add_filter( 'attachment_fields_to_save', 'rename_media_save', 10, 2 );



function update_info_for_all_users() {
    #if ( !in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) ) {
				global $pagenow, $current_user;

				$plugin_update_count = $theme_update_count = $wordpress_update_count = 0;

				$update_plugins = get_site_transient( 'update_plugins' );
				if ( ! empty( $update_plugins->response ) )
					$plugin_update_count = count( $update_plugins->response );

				#$update_themes = get_site_transient( 'update_themes' );
				#if ( !empty($update_themes->response) )
				#	$theme_update_count = count( $update_themes->response );

				$update_wordpress = get_core_updates( array('dismissed' => false) );
				if ( !empty($update_wordpress) && !in_array( $update_wordpress[0]->response, array('development', 'latest') ) && current_user_can('update_core') )
					$wordpress_update_count = 1;

				$total_update_count = $plugin_update_count + $theme_update_count + $wordpress_update_count;

				if ( $pagenow == 'index.php' && $total_update_count >= 5 ) {
				      $mailbody	= _x('Hello','Update-Info Mail Body', 'cb-std-sys')." Carsten"."\n".__( 'Could you do some updating for me soon? ', 'cb-std-sys' )."\n\n".__( 'Best Regards', 'cb-std-sys' )." ".$current_user->first_name." ".$current_user->last_name;
				      echo '<div class="updated"><p>'.sprintf( __('Some components need an update. Contact %s, sure he will help you! ','cb-std-sys'), '<a href="mailto:Carsten Bach <'.get_option('admin_email').'>?subject='.$_SERVER['HTTP_HOST'].' '.__( 'needs some updates', 'cb-std-sys' ).'&body='.rawurlencode( $mailbody ).'">Carsten</a>').'</p></div>';
				}
		#}
}
add_action('admin_notices', 'update_info_for_all_users');




// nice to show/hide all wanted metaboxes by default
// http://wordpress.stackexchange.com/questions/15376/how-to-set-default-screen-options/19972#19972

// add_action('user_register', 'set_user_metaboxes');
#add_action('admin_init', 'set_user_metaboxes');
function set_user_metaboxes($user_id=NULL) {

    // These are the metakeys we will need to update
    $meta_key['order'] = 'meta-box-order_post';
    $meta_key['hidden'] = 'metaboxhidden_post';

    // So this can be used without hooking into user_register
    if ( ! $user_id)
        $user_id = get_current_user_id();

    // Set the default order if it has not been set yet
    if ( ! get_user_meta( $user_id, $meta_key['order'], true) ) {
        $meta_value = array(
            'side' => 'submitdiv,formatdiv,categorydiv,postimagediv',
            'normal' => 'postexcerpt,tagsdiv-post_tag,postcustom,commentstatusdiv,commentsdiv,trackbacksdiv,slugdiv,authordiv,revisionsdiv',
            'advanced' => '',
        );
        update_user_meta( $user_id, $meta_key['order'], $meta_value );
    }

    // Set the default hiddens if it has not been set yet
    if ( ! get_user_meta( $user_id, $meta_key['hidden'], true) ) {
        $meta_value = array('postcustom','trackbacksdiv','commentstatusdiv','commentsdiv','slugdiv','authordiv','revisionsdiv');
        update_user_meta( $user_id, $meta_key['hidden'], $meta_value );
    }
}


function remove_unused_widgets(){
  unregister_widget( 'WP_Widget_Calendar' );
  unregister_widget( 'WP_Widget_Search' );
  unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Categories' );
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Links' );
/*
WP_Widget_Pages                   = Pages Widget
WP_Widget_Calendar                = Calendar Widget
WP_Widget_Archives                = Archives Widget
WP_Widget_Links                   = Links Widget
WP_Widget_Meta                    = Meta Widget
WP_Widget_Search                  = Search Widget
WP_Widget_Text                    = Text Widget
WP_Widget_Categories              = Categories Widget
WP_Widget_Recent_Posts            = Recent Posts Widget
WP_Widget_Recent_Comments         = Recent Comments Widget
WP_Widget_RSS                     = RSS Widget
WP_Widget_Tag_Cloud               = Tag Cloud Widget
WP_Nav_Menu_Widget                = Menus Widget
*/

}

#add_action('widgets_init','remove_unused_widgets', 1);



function custom_dashboard_help() {
	echo '
		<p>Need help? That "help" tab up top provides contextual help throughout the administrative panel. If you need additional support, you can contact your web team at <a href="http://www.cmurrayconsulting.com">C. Murray Consulting</a>:</p>
		<p><strong>phone:</strong> 401.228.7660</p>
		<p><strong>email:</strong> <a href="mailto:2010@cmurrayconsulting.com">2010@cmurrayconsulting.com</a><p>
	';
}
/**
 * dort wo auch pf.de dazu kommt
 * 	wp_add_dashboard_widget('custom_help_widget', 'Help and Support', 'custom_dashboard_help'); // add a new custom widget for help and support
 */


} else {




function wplogin_filter( $url, $path, $orig_scheme )
{
 $old  = array( "/(wp-login\.php)/");
 $new  = array( "redaktion");
 return preg_replace( $old, $new, $url, 1);
}
#add_filter('site_url',  'wplogin_filter', 10, 3);


		/**
		 *  Conditional Tag to check wether current page is
		 *  wp-login.php or wp-register.php
		 *
		 *  @since  0.1.5
		 *  @source http://stackoverflow.com/questions/5266945/wordpress-how-detect-if-current-page-is-the-login-page/5892694#5892694

		// does not work for childthemes

		function is_login_page() {
		    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
		}
		 */




}










    /**
     * 	Adding a custom field to the media modal to exclude images from the gallery
     *
     *  @link  http://net.tutsplus.com/tutorials/wordpress/creating-custom-fields-for-attachments-in-wordpress/comment-page-1/#comment-314503
     *
     * 	@param array $form_fields
     * 	@param object $post
     * 	@return array

    function exclude_in_gallery_attachment_fields_to_edit ($form_fields, $post) {
        if ( substr( $post->post_mime_type, 0, 5 ) == 'image' ) {
						$current_value = get_post_meta($post->ID, "_exclude-from-gallery", true);
						$checked = ($current_value == "1") ? ' checked ' : '';

						// update 2010-08-05 @ 5:10pm CDT: hidden field takes over if the checkbox is unchecked, in essence deleting the value
						$myCheckBoxHtml = "
							<input type='hidden' name='attachments[{$post->ID}][exclude-from-gallery]' value='' />
							<input type='checkbox' name='attachments[{$post->ID}][exclude-from-gallery]' id='attachments[{$post->ID}][exclude-from-gallery]' value='1' {$checked} /> "
							.__("Yes, please do not show this image in the gallery.");

						$form_fields["exclude-from-gallery"]["label"] = __("Exclude in gallery");
						$form_fields["exclude-from-gallery"]["input"] = "html";
						$form_fields["exclude-from-gallery"]["html"] = $myCheckBoxHtml;

				}
				return $form_fields;
    }
    add_filter("attachment_fields_to_edit", "exclude_in_gallery_attachment_fields_to_edit", 10, 2);
     */


		/**
		 * 	Saving the custom field to attachement-post if excluded from the gallery
		 *
		 * 	@param array $post
		 * 	@param array $attachment
		 * 	@return array

		function exclude_in_gallery_attachment_fields_to_save ($post, $attachment) {
		    if( isset($attachment['exclude-from-gallery']) ){
		        update_post_meta($post['ID'], '_exclude-from-gallery', $attachment['exclude-from-gallery']);
		    }
		    return $post;
		}
		add_filter("attachment_fields_to_save", "exclude_in_gallery_attachment_fields_to_save", 10, 2);
		 */


/*
function filter_gallery_output($content = null, $attr, $attachments ) {

		debug($attr);

		$images_to_exclude  = array();

		foreach($attachments as $image){
				if( get_post_meta($image->ID, '_exclude-from-gallery', true) == 1 ){
            array_push( $images_to_exclude, $image->ID );
				}
		}

		$attr['exclude']  = join( ',', $images_to_exclude );
		debug($attr);
}
add_filter( 'post_gallery_output', 'filter_gallery_output', 1, 3 );
*/






	/**
	 * @see Walker::end_el()
	 * @since 2.7.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $comment
	 * @param int $depth Depth of comment.
	 * @param array $args
	 */
	 /*
	function end_el(&$output, $comment, $depth, $args) {
		if ( !empty($args['end-callback']) ) {
			call_user_func($args['end-callback'], $comment, $args, $depth);
			return;
		}
		if ( 'div' == $args['style'] )
			echo "</div>\n";
		elseif ( 'article' == $args['style'] )
			echo "</article>\n";
		else
			echo "</li>\n";
	}
*/



// switch post formats in theme options, look   hook_backend    remove_post_custom_fields()

/** geht nicht
// "Einrichtung des mehrsprachigen Inhalts"
      	remove_meta_box( 'icl_div' , 'post' , 'normal' );
      	remove_meta_box( 'icl_div_config' , 'page' , 'normal' );
**/









/**
 *
 *    IDEEN aus
 *    https://github.com/retlehs/roots/blob/master/inc/roots-cleanup.php
 */


//clean up the default WordPress style tags
function roots_clean_style_tag($input) {
  preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
  //only display media if it's print
  $media = $matches[3][0] === 'print' ? ' media="print"' : '';
  return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
}
#add_filter('style_loader_tag', 'roots_clean_style_tag');



// first and last classes for widgets
// http://wordpress.org/support/topic/how-to-first-and-last-css-classes-for-sidebar-widgets
function roots_widget_first_last_classes($params) {
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

  $params[0]['before_widget'] = str_replace('class="', $class, $params[0]['before_widget']);

  return $params;

}
#add_filter('dynamic_sidebar_params', 'roots_widget_first_last_classes');


# http://code.google.com/p/wp-basis-theme/source/browse/trunk/basis-html5/functions.php
// we don't need to self-close these tags in html5:
// <img>, <input>
function roots_remove_self_closing_tags($input) {
  return str_replace(' />', '>', $input);
}

#add_filter('get_avatar', 'roots_remove_self_closing_tags');
#add_filter('comment_id_fields', 'roots_remove_self_closing_tags');
#add_filter('post_thumbnail_html', 'roots_remove_self_closing_tags');






 
?>