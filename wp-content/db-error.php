<?php 

	header('HTTP/1.1 503 Service Temporarily Unavailable');
	header('Status: 503 Service Temporarily Unavailable');
	header('Retry-After: 3600'); // 1 hour = 3600 seconds
	mail("carsten@medienstadt.info",
			 "WP Database Error",
			 "There is a problem with the database of ".$_SERVER['SERVER_NAME']." @ ".$_SERVER['SERVER_ADDR']."!",
			 "From: ".$_SERVER['SERVER_NAME']." <carsten@medienstadt.info>"
			 );

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
		<title>Datenbank-Schluckauf</title>
    <link rel='stylesheet' id='login-css'  href='/wp-admin/css/login.css' type='text/css' media='all' />
		<link rel="stylesheet" href="/wp-content/themes/cb-std-sys/css/cb_std_sys_admin.css" />
		<link rel="stylesheet" href="/wp-content/themes/childtheme/css/cb_std_sys_admin.css" />
		<meta name='robots' content='noindex,noarchive,follow' />

	</head>
	<body class="appache-error-doc error-doc">
	<div id="error-msg">
			<h1><a href="/" title="Startseite">Startseite</a></h1>
			<div id="error-box">
				<h2>Datenbank-Schluckauf</h2>
				<p>Unsere Datenbank hat sich wohl einen Schnupfen eingefangen.</p>
				<p>Wir sind jetzt über den Fehler informiert und werden ihn schnellstmöglich beheben.</p>
			</div>
	</div>


</body>
</html>
