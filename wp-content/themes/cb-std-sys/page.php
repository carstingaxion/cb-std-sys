<?php
/**
 * @package WordPress
 * @subpackage cb-std-sys
 * @since cb-std-sys 0.0.8
 */
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
   *  Add action here to insert markup before the loop starts
   *  
   *  @since    0.2.1
   */                                     
    
  do_action( 'cbstdsys_before_loop' ); 
?>  

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
				
					<header>
						<?php if ( is_front_page() || is_home() ) { ?>
							<h2 class="entry-title" itemprop="name"><?php the_title(); ?></h2>
						<?php } else { ?>
							<h1 class="entry-title" itemprop="name"><?php the_title(); ?></h1>
						<?php } ?>
					</header>
					
					<div class="entry-content">
						<?php the_content(); ?>
						<?php do_action( 'numbered_in_page_links' ); ?>
						<?php edit_post_link( __( 'Edit', 'cb-std-sys' ), '', '' ); ?>
					</div><!-- .entry-content -->
				
				</article><!-- #post-## -->
				
<?php endwhile; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>