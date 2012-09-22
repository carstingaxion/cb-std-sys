<?php

get_header(); ?>


<?php
      /**
       *  Add action here to insert markup after the header.php is loaded
       *  
       *  @since    0.2.1
       */                                     
        
      do_action( 'cbstdsys_after_get_header' ); 
?>




<?php

/**
 *    Show random images attached to posts and pages
 *
 *    used with jQuery roundabout
 *    which is a fancy carousel
 *
 *

		$args = array(
        'posts_per_page' 			=> 6,
#       'nopaging'						=> true,
        'post_type'           => 'attachment',
				'orderby'             => 'rand',
    );

		$myposts = get_posts( $args );
    if ( $myposts[0] ) {

				echo '<div id="roundabout" class="random-attachements">';
				echo '<h2 class="hidden section-title random-attachements-title"><span>'.__('Zufällige Bilder','cb-std-sys').'</span></h2>';
    
				foreach( $myposts as $post ) :	setup_postdata($post);

						$content='';
						$content.= '<article id="post-'. get_the_ID().'" class="' . implode(" ",  get_post_class('clearfix featured-image') ) .'" itemscope itemtype="http://schema.org/ImageObject">';

						$inner = wp_get_attachment_image(get_the_ID(), 'medium', false );
						$inner.= '<div class="caption"><h5 itemprop="name">'.get_the_title().'</h5><p itemprop="description">'.get_the_excerpt().'</p></div>';

						if ( $post->post_parent != 0 ) {
								$content.= '<a href="'. get_permalink( $post->post_parent ).'">'.$inner.'</a>';
						} else {
		        		$content.= $inner;
						}
						$content.= '</article><!-- #post-## -->';
						echo $content;

		 		endforeach;
				echo '</div> <!-- //end #roundabout  -->';
		}
    wp_reset_postdata();
*/
?>






<?php
/**
 *  Show sticky posts
 *
 *

	  $sticky = get_option( 'sticky_posts' );
		$args = array(
        'cat'             		=> 1, // Blog
        'posts_per_page' 			=> -1,
				'post__in'  					=> $sticky, // only stickys
				'ignore_sticky_posts' => 1

    );
    
    $the_query = new WP_Query( $args );
    if ( $sticky[0] ) {
        if ( $the_query->have_posts() ) {
						echo '<div class="sticky-posts">';
						echo '<h2 class="section-title sticky-posts-title"><span>'.__('Aktuell','cb-std-sys').'</span></h2>';
		        while ( $the_query->have_posts() ) : $the_query->the_post();

								$format = get_post_format();
					      if ( false === $format )
					            $format = 'standard';
					      get_template_part( 'format', $format );
      
      
		        endwhile;
						echo '</div> <!-- //end .sticky-posts  -->';
        }
    }
    wp_reset_postdata();
*/
?>

<?php
/**
 *  Last (blog) posts
 *
 *
*/
    $args = array(
        'cat'             => 1, //  Blog
        'posts_per_page'  => 1,  // show 1 post
        'post__not_in' 		=> get_option( 'sticky_posts' ) // exclude stickys
    );
    
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) {
    
				echo '<div class="sticky-posts">';
        echo '<h2 class="section-title sticky-posts-title"><span>'.__('zuletzt im Blog','cb-std-sys').'</span></h2>';
        while ( $the_query->have_posts() ) : $the_query->the_post();

						$format = get_post_format();
					  if ( false === $format )
					        $format = 'standard';
					  get_template_part( 'format', $format );
  
        endwhile;
				echo '</div> <!-- //end .last-posts  -->';
    }
    wp_reset_postdata();

?>



<?php get_sidebar(); ?>

<?php get_footer(); ?>