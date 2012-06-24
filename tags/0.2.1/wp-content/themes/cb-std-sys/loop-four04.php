<?php

// exclude attachements
function four04_strip_attachments($where) {
	$where .= ' AND post_type != "attachment"';
	return $where;
}
add_filter('posts_where','four04_strip_attachments');   ?>

	<article id="post-0" class="post error404 not-found" role="main">
		<?php
		if ( is_archive() ) { ?>
				<header><h2 class="entry-title"><?php echo  __('Nothing Found','cb-std-sys') ?></h2></header>
		<?php } else { ?>
				<h1 class="page-title entry-title"><?php echo  __('Nothing Found','cb-std-sys') ?></h1>
		<?php } ?>
		
		<div class="entry-content">
		<p class="entry-summary error">
		<?php
		if ( is_archive() ) {
		    _e( 'Apologies, but no results were found for the requested archive.', 'cb-std-sys' );
		} else {
				printf( __('We couldn\'t find <abbr title="%s">that URL</abbr> on our server, though it\'s most certainly not your fault.','cb-std-sys'), htmlentities(strip_tags($_SERVER['REQUEST_URI'])) );
		}  ?>
</p>

<?php
 		if ( cbstdsys_opts('m_search') ) {
?>

				<h2><?php _e( 'Perhaps searching will help.', 'cb-std-sys' ); ?></h2>
				<?php get_search_form(); ?>
<?php } ?>


<?php // get missspelled string from the url and look for matching posts and pages

$s 					= preg_replace("/(.*)-(html|htm|php|asp|aspx)$/","$1",$wp_query->query_vars['name']);
$args 			= array( 'post_type' => 'any', 'posts_per_page'=> 10, 'name' => $s );
$the_query 	= new WP_Query( $args );
$s 					= str_replace("-"," ",$s);

if ( !$the_query->have_posts() ) {
    $args = array( 'post_type' => 'any', 'posts_per_page'=> 10, 's' => $s );
    $the_query = new WP_Query( $args );
}
if ( $the_query->have_posts() ) {
		echo "<h2>". __( 'Read on', 'cb-std-sys' ) ."</h2>";
		echo '<ul class="postlisting">';
		while ( $the_query->have_posts() ) : $the_query->the_post();
			echo '         <li><a href="'.get_permalink($post->ID).'" title="'. sprintf( esc_attr__( 'Permalink to %s', 'cb-std-sys' ), the_title_attribute( 'echo=0' ) ).'" rel="bookmark">'.get_the_title().'</a></li>';
		endwhile;
		echo '</ul>';
}
wp_reset_postdata();

?>


				<a class="button gohome" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php _e( 'Take me home. &rarr; ', 'cb-std-sys' ); ?></a>

		</div><!-- .entry-content -->
	</article>