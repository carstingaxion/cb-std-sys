<?php
/********************************************************************************************************
 *
 *    This file is e.g. used by the maintenance mode
 *
 *    DO NOT ADD ANY WORDPRESS FUNCTIONS HERE
 *
 ********************************************************************************************************/


    /**
     *  use personalized CSS-styles for Login, Apache Error-docs, db-error.php & maintenance.php - Screens
     *
     *  @since    0.0.1
     *
     */
    function cbstdsys_login_css( $echo = false ) {

						# default wp-admin.css, typically catched via wp_admin_css( 'wp-admin', true );
						echo '<link href="/wp-admin/css/wp-admin.css" rel="stylesheet" />';
						# default wp-admin.css, typically catched via wp_admin_css( 'wp-admin', true );
						echo '<link href="/wp-admin/css/colors-fresh.css" rel="stylesheet"  />';
						# cb-std-sys base styles
						echo '<link href="/css/cb_std_sys_admin.css" rel="stylesheet" />';
						# childtheme modification
						$child_admin_style  = WP_CHILD_URL.'/css/cb_std_sys_admin.css';
						echo '<link href="'.$child_admin_style. '" rel="stylesheet" />';
    }



?>
