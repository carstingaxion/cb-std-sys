<?php

if ( file_exists( WP_CHILD_DIR . '/inc/constants.php' ) ) {
    include_once WP_CHILD_DIR . '/inc/constants.php';
}

 // @todo manually set this in style.css
define('CB_STD_SYS_VERSION','0.2.1');


if(!defined('CBSTDSYS_SUPER_ADMIN_EMAIL'))
		define( 'CBSTDSYS_SUPER_ADMIN_EMAIL', 'carsten@medienstadt.info' ); 
    
    
?>