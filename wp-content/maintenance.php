<?php
/* DON’T CHANGE THIS – IT TELLS SEARCH ENGINES THE SITE IS ONLY TEMPORARILY UNAVAILABLE */
$protocol = $_SERVER["SERVER_PROTOCOL"];
if ( 'HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol )
   $protocol = 'HTTP/1.0';header( "$protocol 503 Service Unavailable", true, 503 );
header( 'Content-Type: text/html; charset=utf-8' );
header( 'Retry-After: 600' );
/* END DON’T CHANGE THIS */



$wp_themed_error_body_class  = 'maintenance-mode';
$wp_themed_error_title_tag   = 'Wartungsarbeiten';  
$wp_themed_error_title       = 'Wartungsarbeiten';
$wp_themed_error_home_link   = '<a href="'. WP_SITEURL .'" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) .' Startseite" rel="home">' . get_option( 'blogname' ) .' Startseite</a>'; 
$wp_themed_error_content     = '<p>Wir arbeiten für Sie gerade an der Verbesserung dieser Seite.</p><p>Bitte entschuldigen Sie die Unterbrechung. </p><p>Wir sind in Kürze zurück.</p>';

include_once getenv('DOCUMENT_ROOT').'/wp-content/wp_themed_error.php';
?>
