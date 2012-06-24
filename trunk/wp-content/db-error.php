<?php 
include_once getenv('DOCUMENT_ROOT').'/wp-content/themes/cb-std-sys/inc/constants.php';
header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After: 3600'); // 1 hour = 3600 seconds
mail( CBSTDSYS_SUPER_ADMIN_EMAIL,
		 "WP Database Error",
		 "There is a problem with the database of ".$_SERVER['SERVER_NAME']." @ ".$_SERVER['SERVER_ADDR']."!",
		 "From: ".$_SERVER['SERVER_NAME']." <".CBSTDSYS_SUPER_ADMIN_EMAIL.">"
		 );

$wp_themed_error_css_echo    = true;
$wp_themed_error_body_class  = 'db-error';
$wp_themed_error_title_tag   = 'Datenbank-Schluckauf';  
$wp_themed_error_title       = 'Datenbank-Schluckauf';
$wp_themed_error_home_link   = '<a href="/" title="Startseite">Startseite</a>'; 
$wp_themed_error_content     = '<p>Unsere Datenbank hat sich wohl einen Schluckauf eingefangen.</p><p>Wir sind jetzt per Email über den Fehler informiert und werden ihn schnellstmöglich beheben.</p>';

include_once getenv('DOCUMENT_ROOT').'/wp-content/wp_themed_error.php';
?>

