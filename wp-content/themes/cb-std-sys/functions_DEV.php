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

// http://wordpress.org/extend/plugins/rename-media/screenshots/

// http://wordpress.org/extend/plugins/media-file-renamer/screenshots/


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










} else {





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











// http://gandamanurung.com/wordpress-trick/remove-empty-span-tag-on-wordpress-caused-by-read-more-link/
// removes empty span
function remove_empty_read_more_span($content) {
	return eregi_replace("(<p><span id=\"more-[0-9]{1,}\"></span></p>)", "", $content);
}
add_filter('the_content', 'remove_empty_read_more_span');




// http://codex.wordpress.org/Customizing_the_Read_More#Link_Jumps_to_More_or_Top_of_Page
// removes url hash to avoid the jump link
function remove_more_jump_link($link) {
   $offset = strpos($link, '#more-');
   if ($offset) {
      $end = strpos($link, '"',$offset);
   }
   if ($end) {
      $link = substr_replace($link, '', $offset, $end-$offset);
   }
   return $link;
}
add_filter('the_content_more_link', 'remove_more_jump_link');






 
?>