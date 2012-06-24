<?php
/**
 * RSS2 Feed Template for displaying Help Feed
 *
 * @package cb-std-sys
 */
/*
    // authenticate with carsten-bach.de Login Credentials
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="Hilfe & Anleitungen zur Verwendung Ihrer Webseite"');
        header('HTTP/1.0 401 Unauthorized');
        #echo 'Text to send if user hits Cancel button';
        exit;
      } else {
        if (!user_pass_ok($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'])) {
            header('WWW-Authenticate: Basic realm="Verwenden Sie Ihre Logindaten vom Helpdesk auf carsten-bach.de"');
            header('HTTP/1.0 401 Unauthorized');
            #echo 'Text to sends incorrect username and password';
            exit;

        } else {
            //  can proceed normally

*/
header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	<?php do_action('rss2_ns'); ?>
>

<channel>
	<title>HELP FEED <?php bloginfo_rss('name'); wp_title_rss(); ?></title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php bloginfo_rss('url') ?></link>
	<description><?php bloginfo_rss("description") ?></description>
	<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
	<language><?php echo get_option('rss_language'); ?></language>
	<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
	<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
	<?php do_action('rss2_head'); ?>
	<?php $document = new WP_Query( array( 'post_type' => 'wp-help', 'p' => $document_id , 'posts_per_page' => -1 ) ); ?>
	<?php while ( $document->have_posts() ) : $document->the_post(); ?>	
	<item>
		<title><?php the_title_rss() ?></title>
		<link><?php the_permalink_rss() ?></link>
		<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
		<dc:creator><?php the_author() ?></dc:creator>
		<?php the_category_rss('rss2') ?>

		<guid isPermaLink="false"><?php the_guid(); ?></guid>
 
<?php
// Variables to store each of our possible taxonomy lists  
// This one checks for an Operating System classification  
$terms = get_the_terms( get_the_ID(), 'help_context' );
if ( !is_wp_error($terms)) : 
#$terms = array(array(1,2,3),array(4,5,6));
#echo '<pre>';
#var_dump($help_contexts);
#echo '</pre>';  
# 
unset($screen_IDs);
foreach ( $terms as $obj ){
  $screen_IDs[] = $obj->name;
}
#var_dump($screen_IDs);
$help_contexts = join(", ", $screen_IDs);
?>
    <category><![CDATA[<?php echo $help_contexts; ?>]]></category>		
<?php
$yt_video_id  = get_post_custom_values( 'yt_video_id', get_the_ID() );  
?>    
    <video><![CDATA[<?php echo $yt_video_id[0]; ?>]]></video>    
    
<?php endif ?>		
<?php if (get_option('rss_use_excerpt')) : ?>
		<description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
<?php else : ?>
		<description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
	<?php if ( strlen( $post->post_content ) > 0 ) : ?>
		<content:encoded><![CDATA[<?php the_content_feed('rss2') ?>]]></content:encoded>
	<?php else : ?>
		<content:encoded><![CDATA[<?php the_excerpt_rss() ?>]]></content:encoded>
	<?php endif; ?>
<?php endif; ?>
		<wfw:commentRss><?php echo esc_url( get_post_comments_feed_link(null, 'rss2') ); ?></wfw:commentRss>
		<slash:comments><?php echo get_comments_number(); ?></slash:comments>
<?php rss_enclosure(); ?>
	<?php do_action('rss2_item'); ?>
	</item>
	<?php endwhile; ?>
</channel>
</rss>

<?php
#        } // if user_pass_ok
#    } // if isset $_SERVER['PHP_AUTH_USER']
?>