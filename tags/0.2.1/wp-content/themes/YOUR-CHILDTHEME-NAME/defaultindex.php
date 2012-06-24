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
		<title>Wir sind noch nicht wieder online</title>
<?php
echo cbstdsys_login_css();
?>

	<script type="text/javascript" src="<?php echo WP_CHILD_URL; ?>/js/turn/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo WP_CHILD_URL; ?>/js/turn/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo WP_CHILD_URL; ?>/js/turn/turn.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo WP_CHILD_URL; ?>/js/turn/turn.css">

	<script type="text/javascript">
	$(document).ready(function(){
		$( '#target' ).fold({
			side: 'left',								// Right! Whoa!
		  directory: '<?php echo WP_CHILD_URL; ?>/js/turn',			// The directory we're in
  	  maxHeight: 1000,					// The maximum height. Duh.
  		startingWidth: 100,			// The height and width
  		startingHeight: 100			// with which	to start
		});
	});
	</script>
		<meta name='robots' content='noindex,noarchive,follow' />
	</head>
	<body class="appache-error-doc error-doc default-index login">
	<div id="error-msg">
			<h1><a href="/" title="Startseite">Startseite</a></h1>
			<div id="error-box">
				<h2>Wir sind noch nicht wieder online</h2>
				<p>Versuchen Sie es doch einfach später noch einmal.</p>
				<p>Besten Dank &amp; bis bald!</p>
			</div>
	</div>
<img id="target" src="<?php echo WP_CHILD_URL; ?>/img/preview.jpg">
<?php echo add_google_analytics_section(); ?>
</body>
</html>