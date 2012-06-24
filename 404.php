<?php
 
$ASKAPACHE_S_C = array(
'400' => array(
  __('Bad Request','cb-std-sys'),  
  __('Your browser sent a request that this server could not understand.','cb-std-sys') ),
'401' => array(
  __('Authorization Required','cb-std-sys'),  
  __('This server could not verify that you are authorized to access the document requested. Either you supplied the wrong credentials (e.g., bad password), or your browser doesn\'t understand how to supply the credentials required.','cb-std-sys') ),
'402' => array(
  __('Payment Required','cb-std-sys'),  
  __('INTERROR','cb-std-sys') ),
'403' => array(
  __('Forbidden','cb-std-sys'),  
  __('You don\'t have permission to access THEREQUESTURI on this server.','cb-std-sys') ),
'404' => array(
  __('Not Found','cb-std-sys'), 
  __('We couldn\'t find <abbr title="THEREQUESTURI">that uri </abbr> on our server, though it\'s most certainly not your fault.','cb-std-sys') ),
'405' => array(
  __('Method Not Allowed','cb-std-sys'),  
  __('The requested method THEREQMETH is not allowed for the URL THEREQUESTURI.','cb-std-sys') ),
'406' => array(
  __('Not Acceptable','cb-std-sys'),  
  __('An appropriate representation of the requested resource THEREQUESTURI could not be found on this server.','cb-std-sys') ),
'407' => array(
  __('Proxy Authentication Required','cb-std-sys'),  
  __('This server could not verify that you are authorized to access the document requested. Either you supplied the wrong credentials (e.g., bad password), or your browser doesn\'t understand how to supply the credentials required.','cb-std-sys') ),
'408' => array(
  __('Request Time-out','cb-std-sys'),  
  __('Server timeout waiting for the HTTP request from the client.','cb-std-sys') ),
'409' => array(
  __('Conflict','cb-std-sys'),  
  __('INTERROR','cb-std-sys') ),
'410' => array(
  __('Gone','cb-std-sys'),  
  __('The requested resource THEREQUESTURI is no longer available on this server and there is no forwarding address. Please remove all references to this resource.','cb-std-sys') ),
'411' => array(
  __('Length Required','cb-std-sys'),  
  __('A request of the requested method GET requires a valid Content-length.','cb-std-sys') ),
'412' => array(
  __('Precondition Failed','cb-std-sys'),  
  __('The precondition on the request for the URL THEREQUESTURI evaluated to false.','cb-std-sys') ),
'413' => array(
  __('Request Entity Too Large','cb-std-sys'),  
  __('The requested resource THEREQUESTURI does not allow request data with GET requests, or the amount of data provided in the request exceeds the capacity limit.','cb-std-sys') ),
'414' => array(
  __('Request-URI Too Large','cb-std-sys'),  
  __('The requested URL\'s length exceeds the capacity limit for this server.','cb-std-sys') ),
'415' => array(
  __('Unsupported Media Type','cb-std-sys'),  
  __('The supplied request data is not in a format acceptable for processing by this resource.','cb-std-sys') ),
'416' => array(
  __('Requested Range Not Satisfiable','cb-std-sys'),  
  ''),
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
  __('INTERROR','cb-std-sys') ),
'426' => array(
  __('Upgrade Required', 'cb-std-sys'), 
  __('The requested resource can only be retrieved using SSL. The server is willing to upgrade the current connection to SSL, but your client doesn\'t support it. Either upgrade your client, or try requesting the page using https://','cb-std-sys') ),
'500' => array(
  __('Internal Server Error', 'cb-std-sys'), 
  __('INTERROR','cb-std-sys') ),
'501' => array(
  __('Method Not Implemented', 'cb-std-sys'), 
  __('GET to THEREQUESTURI not supported.','cb-std-sys') ),
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
  __('INTERROR','cb-std-sys') ),
'506' => array(
  __('Variant Also Negotiates', 'cb-std-sys'), 
  __('A variant for the requested resource <code>THEREQUESTURI</code> is itself a negotiable resource. This indicates a configuration error.','cb-std-sys') ),
'507' => array(
  __('Insufficient Storage', 'cb-std-sys'), 
  __('The method could not be performed on the resource because the server is unable to store the representation needed to successfully complete the request. There is insufficient free space left in your storage allocation.','cb-std-sys') ),
'510' => array(
  __('Not Extended', 'cb-std-sys'), 
  __('A mandatory extension policy in the request is not accepted by the server for this resource.','cb-std-sys') )
);
 
 

// prints out the html for the error, taking the status code as input
function aa_print_html ($AA_C){
    global $AA_REQUEST_METHOD, $AA_REASON_PHRASE, $AA_MESSAGE;
    
    if($AA_C == '400'||$AA_C == '403'||$AA_C == '405'||$AA_C[0] == '5'){
        @header("Connection: close",1);
        
        if($AA_C=='405')@header('Allow: GET,HEAD,POST,OPTIONS,TRACE');
        
        echo "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n<html><head>";
        echo "<title>$AA_C $AA_REASON_PHRASE</title>";
        echo "<h1>$AA_REASON_PHRASE</h1>\n<p>$AA_MESSAGE<br>\n</p>\n</body></html>";
        return true;
    } else return false;
}


// Tries to determine the error status code encountered by the server
if(!isset($_REQUEST['error']))  $AA_STATUS_CODE = '404';
else  $AA_STATUS_CODE = $_REQUEST['error'];

if(isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS']!='200') 
$AA_STATUS_CODE = $_SERVER['REDIRECT_STATUS'];
 
 
$AA_REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];
$AA_THE_REQUEST = htmlentities(strip_tags($_SERVER['REQUEST_URI']));
$AA_REASON_PHRASE = $ASKAPACHE_S_C[$AA_STATUS_CODE][0];
$AA_M_SR=array(array('INTERROR','THEREQUESTURI','THEREQMETH'),
array('The server encountered an internal error or misconfiguration '.
'and was unable to complete your request.',$AA_THE_REQUEST,$AA_REQUEST_METHOD));
$AA_MESSAGE=str_replace($AA_M_SR[0],$AA_M_SR[1],$ASKAPACHE_S_C[$AA_STATUS_CODE][1]);

// exclude attachements 
function four04_strip_attachments($where) {
	$where .= ' AND post_type != "attachment"';
	return $where;
}
add_filter('posts_where','four04_strip_attachments');

// begin the output buffer to send headers and response
ob_start();
@header("HTTP/1.1 $AA_STATUS_CODE $AA_REASON_PHRASE",1);
@header("Status: $AA_STATUS_CODE $AA_REASON_PHRASE",1);

if(!aa_print_html($AA_STATUS_CODE)){
    ?>
    <?php get_header();?>
    
			<header>
				<h1 class="page-title"><?php echo $AA_STATUS_CODE.' '.$AA_REASON_PHRASE ?></h1>
			</header>
			<article id="post-0" class="post error404 not-found" role="main">
				<p class="error"><?php echo $AA_MESSAGE ?></p>

<?php
 		$cbstdsys_opts = get_option('cbstdsys_options');
 		if ( $cbstdsys_opts['m_search'] ) {
?>
				
				<h2><?php _e( 'Perhaps searching will help.', 'cb-std-sys' ); ?></h2>
				<?php get_search_form(); ?>
<?php     } ?>


<?php // get missspelled string from the url and look for matching posts and pages

$s = preg_replace("/(.*)-(html|htm|php|asp|aspx)$/","$1",$wp_query->query_vars['name']);
$args = array( 'post_type' => 'any', 'posts_per_page'=> 10, 'name' => $s );
$the_query = new WP_Query( $args );
$s = str_replace("-"," ",$s);
if ( !$the_query->have_posts() ) {
    $args = array( 'post_type' => 'any', 'posts_per_page'=> 10, 's' => $s );
    $the_query = new WP_Query( $args );
}
if ( $the_query->have_posts() ) { ?>
        <h2><?php _e( 'Or try something of this.', 'cb-std-sys' ); ?></h2>
        <ul class="postlisting"> 
<?php
while ( $the_query->have_posts() ) : $the_query->the_post();
	echo '         <li><a href="'.get_permalink($post->ID).'" title="'. sprintf( esc_attr__( 'Permalink to %s', 'cb-std-sys' ), the_title_attribute( 'echo=0' ) ).'" rel="bookmark">'.get_the_title().'</a></li>';
endwhile;
?></ul><?php } ?>


				<a class="button gohome" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php _e( 'Take me home. &rarr; ', 'cb-std-sys' ); ?></a>
				
			</article>
    
    <?php get_sidebar(); ?>
    <?php get_footer(); ?>
<?php } 
exit; exit();
?>