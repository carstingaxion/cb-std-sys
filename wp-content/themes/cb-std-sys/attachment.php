<?php
/**
 * 	The template for displaying attachments.
 *
 *  Disable attachement-pages by default, redirect to the media directly
 *
 * 	@package 		WordPress
 * 	@subpackage cbstdsys
 * 	@since    	0.1.8
 *
 *  @idea 			http://wordpress.org/support/topic/disable-attachment-posts-without-remove-the-medias?replies=17#post-2054968
 */


header ('HTTP/1.1 301 Moved Permanently');
header ('Location: '.wp_get_attachment_url());
?>

