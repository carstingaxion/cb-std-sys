<?php
/**
 * The template for displaying Tag Archive pages.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
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

		<header>
			<h1 class="page-title">
				<?php printf( __( 'Tag Archives: %s', 'cb-std-sys' ), '<strong>' . single_tag_title( '', false ) . '</strong>' ); ?>
			</h1>
		</header>

<?php
      /**
       *  Add action here to insert markup before the loop starts
       *  
       *  @since    0.2.1
       */                                     
        
      do_action( 'cbstdsys_before_loop' ); 
?>

<?php
/* Run the loop for the tag archive to output the posts
 * If you want to overload this in a child theme then include a file
 * called loop-tag.php and that will be used instead.
 */
 get_template_part( 'loop', 'tag' );
?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>