<?php
// get wp environment
require_once(getenv("DOCUMENT_ROOT").'/wp-load.php');

ob_start();
@set_time_limit(5);
@ini_set('memory_limit', '64M');
@ini_set('display_errors', 'Off');
error_reporting(0);

function print_error_page ( ) {

    
    $status = array(
    '400' => array(
      __('Bad Request','cb-std-sys'),
      __('Your browser sent a request that this server could not understand.','cb-std-sys') ),
    '401' => array(
      __('Authorization Required','cb-std-sys'),
      __('This server could not verify that you are authorized to access the document requested. Either you supplied the wrong credentials (e.g., bad password), or your browser doesn\'t understand how to supply the credentials required.','cb-std-sys') ),
    '402' => array(
      __('Payment Required','cb-std-sys'),
      __('The server encountered an internal error or misconfiguration and was unable to complete your request.','cb-std-sys') ),
    '403' => array(
      __('Forbidden','cb-std-sys'),
      __('You don\'t have permission to access %U% on this server.','cb-std-sys') ),
    '404' => array(
      __('Not Found','cb-std-sys'),
      __('We couldn\'t find %U% on our server, though it\'s most certainly not your fault.','cb-std-sys') ),
    '405' => array(
      __('Method Not Allowed','cb-std-sys'),
      __('The requested method %M% is not allowed for the URL %U%.','cb-std-sys') ),
    '406' => array(
      __('Not Acceptable','cb-std-sys'),
      __('An appropriate representation of the requested resource %U% could not be found on this server.','cb-std-sys') ),
    '407' => array(
      __('Proxy Authentication Required','cb-std-sys'),
      __('This server could not verify that you are authorized to access the document requested. Either you supplied the wrong credentials (e.g., bad password), or your browser doesn\'t understand how to supply the credentials required.','cb-std-sys') ),
    '408' => array(
      __('Request Time-out','cb-std-sys'),
      __('Server timeout waiting for the HTTP request from the client.','cb-std-sys') ),
    '409' => array(
      __('Conflict','cb-std-sys'),
      __('The server encountered an internal error or misconfiguration and was unable to complete your request.','cb-std-sys') ),
    '410' => array(
      __('Gone','cb-std-sys'),
      __('The requested resource %U% is no longer available on this server and there is no forwarding address. Please remove all references to this resource.','cb-std-sys') ),
    '411' => array(
      __('Length Required','cb-std-sys'),
      __('A request of the requested method GET requires a valid Content-length.','cb-std-sys') ),
    '412' => array(
      __('Precondition Failed','cb-std-sys'),
      __('The precondition on the request for the URL %U% evaluated to false.','cb-std-sys') ),
    '413' => array(
      __('Request Entity Too Large','cb-std-sys'),
      __('The requested resource %U% does not allow request data with GET requests, or the amount of data provided in the request exceeds the capacity limit.','cb-std-sys') ),
    '414' => array(
      __('Request-URI Too Long','cb-std-sys'),
      __('The requested URL\'s length exceeds the capacity limit for this server.','cb-std-sys') ),
    '415' => array(
      __('Unsupported Media Type','cb-std-sys'),
      __('The supplied request data is not in a format acceptable for processing by this resource.','cb-std-sys') ),
    '416' => array(
      __('Requested Range Not Satisfiable','cb-std-sys'),
      __('Requested Range Not Satisfiable','cb-std-sys')),
    '417' => array(
      __('Expectation Failed','cb-std-sys'),
      __('The expectation given in the Expect request-header field could not be met by this server. The client sent <code>Expect:</code>','cb-std-sys') ),
    '422' => array(
      __('Unprocessable Entity', 'cb-std-sys'),
      __('The server understands the media type of the request entity, but was unable to process the contained instructions.','cb-std-sys') ),
    '423' => array(
      __('Locked', 'cb-std-sys'),
      __('The requested resource is currently locked. The lock must be released or proper identification given before the method can be applied.','cb-std-sys') ),
    '424' => array(
      __('Failed Dependency', 'cb-std-sys'),
      __('The method could not be performed on the resource because the requested action depended on another action and that other action failed.','cb-std-sys') ),
    '425' => array(
      __('No code', 'cb-std-sys'),
      __('The server encountered an internal error or misconfiguration and was unable to complete your request.','cb-std-sys') ),
    '426' => array(
      __('Upgrade Required', 'cb-std-sys'),
      __('The requested resource can only be retrieved using SSL. The server is willing to upgrade the current connection to SSL, but your client doesn\'t support it. Either upgrade your client, or try requesting the page using https://','cb-std-sys') ),
    '500' => array(
      __('Internal Server Error', 'cb-std-sys'),
      __('The server encountered an internal error or misconfiguration and was unable to complete your request.','cb-std-sys') ),
    '501' => array(
      __('Method Not Implemented', 'cb-std-sys'),
      __('This type of request method to %U% is not supported.','cb-std-sys') ),
    '502' => array(
      __('Bad Gateway', 'cb-std-sys'),
      __('The proxy server received an invalid response from an upstream server.','cb-std-sys') ),
    '503' => array(
      __('Service Temporarily Unavailable', 'cb-std-sys'),
      __('The server is temporarily unable to service your request due to maintenance downtime or capacity problems. Please try again later.','cb-std-sys') ),
    '504' => array(
      __('Gateway Time-out', 'cb-std-sys'),
      __('The proxy server did not receive a timely response from the upstream server.','cb-std-sys') ),
    '505' => array(
      __('HTTP Version Not Supported', 'cb-std-sys'),
      __('The server encountered an internal error or misconfiguration and was unable to complete your request.','cb-std-sys') ),
    '506' => array(
      __('Variant Also Negotiates', 'cb-std-sys'),
      __('A variant for the requested resource %U% is itself a negotiable resource. This indicates a configuration error.','cb-std-sys') ),
    '507' => array(
      __('Insufficient Storage', 'cb-std-sys'),
      __('The method could not be performed on the resource because the server is unable to store the representation needed to successfully complete the request. There is insufficient free space left in your storage allocation.','cb-std-sys') ),
    '510' => array(
      __('Not Extended', 'cb-std-sys'),
      __('A mandatory extension policy in the request is not accepted by the server for this resource.','cb-std-sys') )
    );


  
    // Get the Status Code
    if (isset($_SERVER['REDIRECT_STATUS']) && ($_SERVER['REDIRECT_STATUS'] != 200))$sc = $_SERVER['REDIRECT_STATUS'];
    elseif (isset($_SERVER['REDIRECT_REDIRECT_STATUS']) && ($_SERVER['REDIRECT_REDIRECT_STATUS'] != 200)) $sc = $_SERVER['REDIRECT_REDIRECT_STATUS'];
    $sc = (!isset($_GET['e']) ? 404 : $_GET['e']);
  
    $sc=abs(intval($sc));
  
    // Redirect to server home if called directly or if status is under 400
    if( ( (isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS'] == 200) && (floor($sc / 100) == 3) )
       || (!isset($_GET['e']) && $_SERVER['REDIRECT_STATUS'] == 200)  )
    {
        @header("Location: http://{$_SERVER['SERVER_NAME']}",1,302);
        die();
    }
  
    // Check range of code or issue 500
    if (($sc < 200) || ($sc > 599)) $sc = 500;
  
    // Check for valid protocols or else issue 505
    if (!in_array($_SERVER["SERVER_PROTOCOL"], array('HTTP/1.0','HTTP/1.1','HTTP/0.9'))) $sc = 505;
  
    // Get the status reason
    $reason = (isset($status[$sc][0]) ? $status[$sc][0] : '');
  
    // Get the status message
  #  $msg = (isset($status[$sc][1]) ? str_replace('%U%', htmlspecialchars(strip_tags(stripslashes($_SERVER['REQUEST_URI']))), $status[$sc][1]) : '');
    $msg = $status[$sc][1];
  	$msg = (isset($msg) ? str_replace('%U%', '<strong><em>'.htmlspecialchars(strip_tags(stripslashes($_SERVER['REQUEST_URI']))).'</strong></em>', $msg) : '');
    $msg = (isset($msg) ? str_replace('%M%', '<strong><em>'.htmlspecialchars(strip_tags(stripslashes($_SERVER['REQUEST_METHOD']))).'</strong></em>', $msg) : '');
  
    // issue optimized headers (optimized for your server)
    @header("{$_SERVER['SERVER_PROTOCOL']} {$sc} {$reason}", 1, $sc);
    if( @php_sapi_name() != 'cgi-fcgi' ) @header("Status: {$sc} {$reason}", 1, $sc);
  
  
    // A very small footprint for certain types of 4xx class errors and all 5xx class errors
    if (in_array($sc, array(400, 403, 405)) || (floor($sc / 100) == 5))
    {
      @header("Connection: close", 1);
      if ($sc == 405) @header('Allow: GET,HEAD,POST,OPTIONS', 1, 405);
    }
  
  
  
  
    
  $wp_themed_error_body_class  = 'appache-error-doc apache-error-' . $sc;
  $wp_themed_error_title_tag   = $sc .' '.$reason;  
  $wp_themed_error_title       = $sc .' '.$reason;
  $wp_themed_error_home_link   = '<a href="'. WP_SITEURL .'" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) .' Startseite" rel="home">' . get_option( 'blogname' ) .' Startseite</a>'; 
  
  $wp_themed_error_content     = '<p>' . $msg . '</p>';
  
#  $cbstdsys_opts = get_option('cbstdsys_options');
#  if ( $cbstdsys_opts['m_search'] ) {
#      $wp_themed_error_content    .= '<h3>' . __( 'Perhaps searching will help.', 'cb-std-sys' ) . '</h3>';
#      $wp_themed_error_content    .= get_search_form();
#      $wp_themed_error_content    .= require_once WP_CONTENT_DIR . '/cb-std-sys/searchform.php';
#  }
  
  
  query_posts('showposts=5');
  if (have_posts()) { 
      $wp_themed_error_content    .= '<h3>' . __( 'Go on here', 'cb-std-sys' ) . '</h3>';
      $wp_themed_error_content    .= '<ul class="postlisting">';        
         
      while (have_posts()) : the_post(); 
          $wp_themed_error_content    .= '<li><a href="' . get_permalink() .'"  title="' . sprintf( esc_attr__( 'Permalink to %s', 'cb-std-sys' ), the_title_attribute( 'echo=0' ) ) .'" rel="bookmark">' . get_the_title() . '</a></li>';    
      endwhile;
      $wp_themed_error_content    .= '</ul>';
  } 
  
  
  $errorTemplate = WP_CONTENT_DIR.'/wp_themed_error.php';
  include_once $errorTemplate;
  

}

print_error_page();
echo ob_get_clean();
exit;
?>