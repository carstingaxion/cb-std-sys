<?php
if ( !isset( $wp_themed_error_css_echo ) )
    $wp_themed_error_css_echo  = false;
if ( !isset( $wp_themed_error_body_class ) )
    $wp_themed_error_body_class  = '';
if ( !isset( $wp_themed_error_title_tag ) )
    $wp_themed_error_title_tag   = 'Fehler';  
if ( !isset( $wp_themed_error_title ) )
    $wp_themed_error_title       = 'Fehler';
if ( !isset( $wp_themed_error_home_link ) )
    $wp_themed_error_home_link   = '<a href="/" title="Startseite">Startseite</a>'; 
if ( !isset( $wp_themed_error_content ) )
    $wp_themed_error_content     = '<p>Es ist ein Fehler aufgetreten.</p>';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7 ]> <html class="ie ie6" xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7" xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8" xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9" xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de"> <!--<![endif]-->

	<head profile="http://gmpg.org/xfn/11">
	  <base href="<?php echo ( defined( 'WP_SITEURL' ) ) ? WP_SITEURL : "/"; ?>" />
  	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name='content-language' content='de' />
    <meta name='language' content='de' />
		<title><?php echo $wp_themed_error_title_tag; ?></title>
<?php
include_once getenv("DOCUMENT_ROOT").'/wp-content/themes/cb-std-sys/inc/dropins.extensions.php';
echo cbstdsys_login_css( $wp_themed_error_css_echo );
?>
		<meta name='robots' content='noindex,noarchive,follow' />

	</head>
	<body class="error-doc wp-themed-error-php <?php echo $wp_themed_error_body_class; ?>">
	<div id="error-msg">
			<h1><?php echo $wp_themed_error_home_link; ?></h1>
			<div id="error-box">
				<h2><?php echo $wp_themed_error_title; ?></h2> 
				<?php echo $wp_themed_error_content; ?>
			</div>
	</div>


</body>
</html>

<?php
/* DON’T CHANGE THIS – IT PASSES CONTROL BACK TO WORDPRESS */
die();
/* END DON’T CHANGE THIS */
?>