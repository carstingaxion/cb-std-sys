<?php	ob_start(); // important for firPHPdebug ?>
<!DOCTYPE html>
<?php $fbml	= ( is_facebook() ) ? ' xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/"' : '';	?>
<?php if ( cbstdsys_opts('m_multi_lang') ) { ?>
<!--[if lt IE 7 ]> <html class="ie ie6 no-js" dir="ltr" lang="<?php echo ICL_LANGUAGE_CODE; ?>"<?php echo $fbml; ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 no-js" dir="ltr" lang="<?php echo ICL_LANGUAGE_CODE; ?>"<?php echo $fbml; ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 no-js" dir="ltr" lang="<?php echo ICL_LANGUAGE_CODE; ?>"<?php echo $fbml; ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 no-js" dir="ltr" lang="<?php echo ICL_LANGUAGE_CODE; ?>"<?php echo $fbml; ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html dir="ltr" lang="<?php echo ICL_LANGUAGE_CODE; ?>"<?php echo $fbml; ?> class="gte-ie9 no-js"> <!--<![endif]-->
<?php } else { ?>
<!--[if lt IE 7 ]> <html class="ie ie6 no-js" <?php language_attributes(); echo $fbml; ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 no-js" <?php language_attributes(); echo $fbml; ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 no-js" <?php language_attributes(); echo $fbml; ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 no-js" <?php language_attributes(); echo $fbml; ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php  language_attributes(); echo $fbml; ?> class="gte-ie9 no-js"> <!--<![endif]-->
<?php } ?>
<head>
	<meta charset="utf-8">
<?php // www.phpied.com/conditional-comments-block-downloads/ ?>
	<!--[if IE]><![endif]-->
	<base href="<?php echo WP_HOME; ?>">
	<meta name='author' content='<?php echo cb_std_sys_get_page_author_meta(); ?>'>
	<link type="text/plain" rel="author" href="/humans.txt">
	<meta name='creator' content='Carsten Bach'>
	<meta name="contact" content="<?php echo cbstdsys_opts('c_main_email'); ?>">
	<meta name="copyright" content="<?php echo strip_tags( cb_std_sys_copyright() ); ?>">
<?php // dns-handshake with foreign domains for faster loading ?>
<?php // https://github.com/paulirish/html5-boilerplate/wiki/head-Tips ?>
	<link rel="dns-prefetch" href="//ajax.googleapis.com">
<?php // Suppress IE6 Image Toolbar ?>
	<!--[if IE]><meta http-equiv="imagetoolbar" content="false" /><![endif]-->
<?php // IE9 Pinned Sites ?>
	<meta name="application-name" content="<?php wp_title( '|', true, 'right' ); ?>">
	<meta name="msapplication-tooltip" content="<?php echo get_meta_description( $post ) ?>">
	<meta name="msapplication-starturl" content="/?pinned=true">

<?php // Facebook Open Graph Data ?>
<?php if(is_facebook()){?>
	<meta property="og:title" content="<?php wp_title( '|', true, 'right' ); ?>">
	<meta property="og:description" content="<?php echo get_meta_description( $post ) ?>">
	<meta property="og:type" content="article"/>
	<meta property="og:image" content="<?php $img_path	=	wp_get_attachment_image_src ( get_post_thumbnail_id ( $post->ID ), 'thumbnail' );
	if( $img_path ){	echo $img_path[0];	} else {	echo '/apple-touch-icon-114x114-precomposed.png';	}	?>"/>
	<meta property="og:url" content="http://<?php echo $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];?>"/>
	<meta property="og:site_name" content="<?php bloginfo( 'name' ); ?>"/>
<?php }?>

	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<meta name="description" content="<?php echo get_meta_description( $post ) ?>">
	<meta name='robots' content='<?php if ((is_home() && ($paged < 2 )) || is_single() || is_page() || is_category()) {
	echo "index,archive,follow";  } else { 	echo "noindex,noarchive,follow";} ?>'>
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
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
<?php	wp_head(); ?>
	<script src="/js/libs/modernizr-1.7.min.js"></script>
</head>
<?php if ( cbstdsys_opts('m_multi_lang') ) { ?>
<body dir="ltr" lang="<?php echo ICL_LANGUAGE_CODE; ?>" <?php body_class(); ?> role="document">
<?php }else{ ?>
<body <?php language_attributes(); ?> <?php body_class(); ?> role="document">
<?php } ?>
	<header role="banner" id="top"<?php echo cbstdsys_add_css_classes_via_filters ( 'top' ); ?>>
					<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
					<<?php echo $heading_tag; ?> id="site-title">
					<span>
						<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					</span>
					</<?php echo $heading_tag; ?>>
					<p id="site-description"><?php bloginfo( 'description' ); ?></p>
	</header>
	<section id="content" role="main"<?php echo cbstdsys_add_css_classes_via_filters ( 'content' ); ?>>
<?php /*  Allow screen readers / text browsers to skip the content and get right to the navigation menu */ ?>
    <a href="#access" class="visuallyhidden focusable" title="<?php esc_attr_e( 'Jump to Navigation', 'cb-std-sys' ); ?>"><?php _e( 'Jump to Navigation', 'cb-std-sys' ); ?></a>

<?php
  /**
   *  Add action here to insert markup before the loop starts
   *  
   *  @since    0.2.1
   */                                     
    
  do_action( 'cbstdsys_before_content' ); 
?> 

<?php /* Wrap the whoole loop markup into div#main */ ?>
<?php if ( apply_filters( 'cbstdsys_wrap_loop_markup', false ) ) : ?>
<div id="main">
<?php endif; // wrap loop into markup-div ?>