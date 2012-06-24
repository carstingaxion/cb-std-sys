<?php
/* DON’T CHANGE THIS – IT TELLS SEARCH ENGINES THE SITE IS ONLY TEMPORARILY UNAVAILABLE */
$protocol = $_SERVER["SERVER_PROTOCOL"];
if ( 'HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol )
   $protocol = 'HTTP/1.0';header( "$protocol 503 Service Unavailable", true, 503 );
header( 'Content-Type: text/html; charset=utf-8' );
header( 'Retry-After: 600' );
/* END DON’T CHANGE THIS */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7 ]> <html class="ie ie6" xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7" xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8" xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9" xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de"> <!--<![endif]-->

	<head profile="http://gmpg.org/xfn/11">
  	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name='content-language' content='de' />
    <meta name='language' content='de' />
		<title>Wartungsarbeiten</title>
    <link rel='stylesheet' id='login-css'  href='/wp-admin/css/login.css' type='text/css' media='all' />
		<link rel="stylesheet" href="/wp-content/themes/cb-std-sys/css/cb_std_sys_admin.css" />
		<link rel="stylesheet" href="/wp-content/themes/childtheme/css/cb_std_sys_admin.css" />
		<meta name='robots' content='noindex,noarchive,follow' />

	</head>
	<body class="appache-error-doc error-doc">
	<div id="error-msg">
			<h1><a href="/" title="Startseite">Startseite</a></h1>
			<div id="error-box">
				<h2>Wartungsarbeiten</h2>
				<p>Wir arbeiten für Sie gerade an der Verbesserung dieser Seite.</p>
				<p>Bitte entschuldigen Sie die Unterbrechung. Wir sind in Kürze zurück</p>
			</div>
	</div>


</body>
</html>

<?php
/* DON’T CHANGE THIS – IT PASSES CONTROL BACK TO THE WORDPRESS UPGRADE ROUTINE */
die();
/* END DON’T CHANGE THIS */
?>