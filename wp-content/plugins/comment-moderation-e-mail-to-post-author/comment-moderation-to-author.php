<?php
/*
Plugin Name: Comment Moderation E-mail to Post Author
Plugin URI: http://4visions.nl/en/wordpress-plugins/comment-moderation-e-mail-to-post-author/
Description: ## CB bearbeitet :: Setting: ModerationEmail ergänzt ## Makes WordPress send the comment moderation notification to the actual posts author's e-mail address instead of the main site e-mail address. <strong>Happy with it? <em><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ravanhagen%40gmail%2ecom&item_name=Comment%20Moderation%20E-mail%20to%20Post%20Author&item_number=0%2e1&no_shipping=0&tax=0&bn=PP%2dDonationsBF&charset=UTF%2d8&lc=us">"Tippin' me jar"</a></em> is much appreciated. Thanks! :)</strong>
Version: 0.1
Author: RavanH
Author URI: http://4visions.nl/
*/

// A new notification function to override the one in pluggable.php

if ( !function_exists('wp_notify_moderator') ) :

function wp_notify_moderator($comment_id) {
	global $wpdb;

	if( get_option( "moderation_notify" ) == 0 )
		return true;

	$comment = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_ID=%d LIMIT 1", $comment_id));
	$post = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE ID=%d LIMIT 1", $comment->comment_post_ID));

	$comment_author_domain = @gethostbyaddr($comment->comment_author_IP);
	$comments_waiting = $wpdb->get_var("SELECT count(comment_ID) FROM $wpdb->comments WHERE comment_approved = '0'");

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	switch ($comment->comment_type)
	{
		case 'trackback':
			$notify_message  = sprintf( __('A new trackback on the post "%s" is waiting for your approval'), $post->post_title ) . "\r\n";
			$notify_message .= get_permalink($comment->comment_post_ID) . "\r\n\r\n";
			$notify_message .= sprintf( __('Website : %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
			$notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
			$notify_message .= __('Trackback excerpt: ') . "\r\n" . $comment->comment_content . "\r\n\r\n";
			break;
		case 'pingback':
			$notify_message  = sprintf( __('A new pingback on the post "%s" is waiting for your approval'), $post->post_title ) . "\r\n";
			$notify_message .= get_permalink($comment->comment_post_ID) . "\r\n\r\n";
			$notify_message .= sprintf( __('Website : %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
			$notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
			$notify_message .= __('Pingback excerpt: ') . "\r\n" . $comment->comment_content . "\r\n\r\n";
			break;
		default: //Comments
			$notify_message  = sprintf( __('A new comment on the post "%s" is waiting for your approval'), $post->post_title ) . "\r\n";
			$notify_message .= get_permalink($comment->comment_post_ID) . "\r\n\r\n";
			$notify_message .= sprintf( __('Author : %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
			$notify_message .= sprintf( __('E-mail : %s'), $comment->comment_author_email ) . "\r\n";
			$notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
			$notify_message .= sprintf( __('Whois  : http://ws.arin.net/cgi-bin/whois.pl?queryinput=%s'), $comment->comment_author_IP ) . "\r\n";
			$notify_message .= __('Comment: ') . "\r\n" . $comment->comment_content . "\r\n\r\n";
			break;
	}

	$notify_message .= sprintf( __('Approve it: %s'),  admin_url("comment.php?action=approve&c=$comment_id") ) . "\r\n";
	if ( EMPTY_TRASH_DAYS )
		$notify_message .= sprintf( __('Trash it: %s'), admin_url("comment.php?action=trash&c=$comment_id") ) . "\r\n";
	else
		$notify_message .= sprintf( __('Delete it: %s'), admin_url("comment.php?action=delete&c=$comment_id") ) . "\r\n";
	$notify_message .= sprintf( __('Spam it: %s'), admin_url("comment.php?action=spam&c=$comment_id") ) . "\r\n";

	$notify_message .= sprintf( _n('Currently %s comment is waiting for approval. Please visit the moderation panel:',
 		'Currently %s comments are waiting for approval. Please visit the moderation panel:', $comments_waiting), number_format_i18n($comments_waiting) ) . "\r\n";
	$notify_message .= admin_url("edit-comments.php?comment_status=moderated") . "\r\n";

	$subject = sprintf( __('[%1$s] Please moderate: "%2$s"'), $blogname, $post->post_title );

	// and here is where we differ from the function in pluggable.php: 
	// the To address will become the Author e-mail unless that is empty...
	$user = get_userdata( $post->post_author );
	$admin_email = get_option('cb_std_sys_moderation_email');
	$message_headers = '';

	$notify_message = apply_filters('comment_moderation_text', $notify_message, $comment_id);
	$subject = apply_filters('comment_moderation_subject', $subject, $comment_id);
	$message_headers = apply_filters('comment_moderation_headers', $message_headers);

	@wp_mail($admin_email, $subject, $notify_message, $message_headers);

	return true;
}

endif;

