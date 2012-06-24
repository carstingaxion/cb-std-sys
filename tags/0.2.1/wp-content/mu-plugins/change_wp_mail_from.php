<?php
/**
* @package WP Mail From
* @author Frank Bültge
* @version 0.1
*/
/*
Plugin Name: WP Mail From
Plugin URI: thttp://wpengineer.com/1604/change-wordpress-mail-sender/
Description: ## CB :: nutzt jetzt $cbstdsys_opts['c_main_email'] ## Change the default address that WordPress sends it’s email from.
Version: 0.1
Author: Frank Bültge
Author URI: http://bueltge.de/
Last Change: 11.08.2009 08:41:06
*/
if ( !function_exists('add_action') ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
if ( !class_exists('wp_mail_from') ) {
    class wp_mail_from {
        function wp_mail_from() {
            add_filter( 'wp_mail_from', array(&$this, 'fb_mail_from') );
            add_filter( 'wp_mail_from_name', array(&$this, 'fb_mail_from_name') );
        }
        // new name
        function fb_mail_from_name($mail_from_name) {
					  if( $mail_from_name != "WordPress") {
					    	return $mail_from_name;
					  } else {
					    	return esc_attr( get_bloginfo('name') );
					  }
        }
        // new email-adress
        function fb_mail_from($mail_from_email) {
					  if( $mail_from_email != $_SERVER['SERVER_NAME'] ) {
					    	return $mail_from_email;
					  }
					  else {
								$cbstdsys_opts = get_option('cbstdsys_options');
					      $email = $cbstdsys_opts['c_main_email'];
					      $email = is_email($email);
					      return $email;
					  }
        }
    }
    $wp_mail_from = new wp_mail_from();
}
?>