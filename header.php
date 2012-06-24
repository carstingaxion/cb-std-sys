<?php	$cbstdsys_opts = get_option('cbstdsys_options'); ?>
<!DOCTYPE html>
<?php if ( $cbstdsys_opts['m_multi_lang'] ) { ?>
<!--[if lt IE 7 ]> <html class="ie ie6 no-js" dir="ltr" lang="<?php echo ICL_LANGUAGE_CODE; ?>"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 no-js" dir="ltr" lang="<?php echo ICL_LANGUAGE_CODE; ?>"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 no-js" dir="ltr" lang="<?php echo ICL_LANGUAGE_CODE; ?>"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 no-js" dir="ltr" lang="<?php echo ICL_LANGUAGE_CODE; ?>"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html dir="ltr" lang="<?php echo ICL_LANGUAGE_CODE; ?>" class="no-js"> <!--<![endif]-->
<?php } else { ?>
<!--[if lt IE 7 ]> <html class="ie ie6 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<?php } ?>
<head>
  <meta charset="utf-8">
<?php // www.phpied.com/conditional-comments-block-downloads/ ?>
  <!--[if IE]><![endif]-->
  <base href="<?php echo WP_HOME; ?>">  
  <meta name='author' content='<?php echo cb_std_sys_get_page_author_meta(); ?>'>
  <link type="text/plain" rel="author" href="/humans.txt">  
  <meta name='creator' content='Carsten Bach'>
  <meta name="contact" content="<?php echo $cbstdsys_opts['c_main_email']; ?>">
  <meta name="copyright" content="<?php echo cb_std_sys_copyright(); ?>">
<?php // dns-handshake with foreign domains for faster loading ?>
<?php // https://github.com/paulirish/html5-boilerplate/wiki/head-Tips ?>
  <link rel="dns-prefetch" href="//ajax.googleapis.com">
<?php // Suppress IE6 Image Toolbar ?>
  <!--[if IE]><meta http-equiv="imagetoolbar" content="false" /><![endif]-->
<?php // IE9 Pinned Sites ?>
  <meta name="application-name" content="xx<?php bloginfo( 'name' ); ?>">
  <meta name="msapplication-tooltip" content="<?php bloginfo( 'description' ); ?>">	
  <meta name="msapplication-starturl" content="/?pinned=true">
<?php // Facebook Open Graph Data ?>
  <meta property="og:title" content="<?php bloginfo( 'name' ); ?>">
  <meta property="og:description" content="<?php bloginfo( 'description' ); ?>">
  <meta property="og:image" content="/favicon.ico"> 
  <title><?php wp_title( '|', true, 'right' ); ?></title>
  <meta name="description" content="<?php if ((is_home()) || (is_front_page())) {
	bloginfo( 'description' );
} elseif(is_singular()) {
	#echo wp_trim_excerpt();	
} elseif(is_category()) {
	echo trim( strip_tags( category_description() ) );
} elseif(is_tag()) {
	printf( __( 'Tag Archives: %s', 'cb-std-sys' ), '' . single_tag_title( '', false ) . '' );
} elseif ( is_day() ) {
  printf( __( 'Daily Archives: %s', 'cb-std-sys' ), get_the_date() ); 
} elseif ( is_month() ) {
  printf( __( 'Monthly Archives: %s', 'cb-std-sys' ), get_the_date('F Y') ); 
} elseif ( is_year() ) {
  printf( __( 'Yearly Archives: %s', 'cb-std-sys' ), get_the_date('Y') ); 
} elseif ( is_author() ) {
#  printf( __( 'Author Archives: %s', 'cb-std-sys' ), get_the_author( $post->post_author ) ); 

} else {
	#echo get_post_meta($post->ID, 'metadescription', true);
} ?>">
  <link rel="profile" href="http://gmpg.org/xfn/11">
<?php /**  
  Mobile Viewport Fix
  j.mp/mobileviewport & davidbcalhoun.com/2010/viewport-metatag 
  
  device-width : Occupy full width of the screen in its current orientation
  initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
  maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width
  
  **/ ?>
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
  <link rel='shortcut icon' href='/favicon.ico'>    
<?php // For iPhone 4 with high-resolution Retina display: ?>
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-114x114-precomposed.png">
<?php // For first-generation iPad: ?>
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-72x72-precomposed.png">
<?php // For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: ?>
  <link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png">
<?php // For nokia devices: ?>
  <link rel="shortcut icon" href="/apple-touch-icon-precomposed.png">
  <link rel="stylesheet" type="text/css" href="css/style.css?v=<?php echo CB_STD_SYS_VERSION; ?>">
  <link rel="stylesheet" media="handheld" href="css/handheld.css?v=<?php echo CB_STD_SYS_VERSION; ?>">	
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
  <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
  <script src="js/libs/modernizr-1.7.min.js"></script>
<?php	wp_head(); ?>
</head>
<?php if ( $cbstdsys_opts['m_multi_lang'] ) { ?>
<body dir="ltr" lang="<?php echo ICL_LANGUAGE_CODE; ?>" <?php body_class(); ?> role="document">
<?php }else{ ?>
<body <?php language_attributes(); ?> <?php body_class(); ?> role="document">
<?php } ?>
  <p id="jsnotice" class="error"><?php if ( $cbstdsys_opts['m_multi_lang'] ) { 
echo sprintf( __('This Website is better usable and much more pretty with <a href="%1$s">javascript</a> enabled. <a href="%2$s" title="How to enable Javascript in your browser">Give it a try</a>!', 'cb-std-sys' ),'http://'.ICL_LANGUAGE_CODE.'.wikipedia.org/wiki/Javascript','http://www.enable-javascript.com/'.ICL_LANGUAGE_CODE.'/');
}else{ 
echo sprintf( __('This Website is better usable and much more pretty with <a href="%1$s">javascript</a> enabled. <a href="%2$s" title="How to enable Javascript in your browser">Give it a try</a>!', 'cb-std-sys' ),'http://wikipedia.org/wiki/Javascript','http://www.enable-javascript.com/');
} ?></p>
  <!--[if lt IE 7 ]><p id="ie-message" class="error"><?php echo sprintf( __('Sorry, but your browser is much too old, to show and use all features of this website in full pleasure. Please update to the <a href="%1$s">current Internet Explorer</a> or use a modern browser like <a href="%2$s">Firefox</a>, <a href="%3$s">Chrome</a> or <a href="%4$s">Safari</a>.','cb-std-sys'),'http://windows.microsoft.com/ie9', 'http://www.mozilla.com', 'http://www.google.com/chrome', 'http://www.apple.com/safari/');?></p><![endif]-->
  <header role="banner" id="top">
				<hgroup>
					<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
					<<?php echo $heading_tag; ?> id="site-title">
					<span>
						<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					</span>
					</<?php echo $heading_tag; ?>>
					<p id="site-description"><?php bloginfo( 'description' ); ?></p>
				</hgroup>

  <nav id="access" role="navigation">
<?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
    <a href="#content" class="visuallyhidden focusable" title="<?php esc_attr_e( 'Skip to content', 'cb-std-sys' ); ?>"><?php _e( 'Skip to content', 'cb-std-sys' ); ?></a>
<?php if ( function_exists( 'language_selector_flags' ) ) { echo language_selector_flags(); } ?>
<?php  		if ( $cbstdsys_opts['m_search'] ) { get_search_form(); } ?>
<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
<?php   $cbstdsys_with_menu_description = new cbstdsys_Walker; ?>
<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary', 'walker' => $cbstdsys_with_menu_description ) ); ?>
  </nav>

  </header>  
  <section id="content" role="main">