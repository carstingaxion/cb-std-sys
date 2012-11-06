<?php
/**
 * The template for displaying Category Archive pages.
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
<?php $tax = get_queried_object(); ?>
<?php if ( is_tax()  ) : ?>
				<?php echo $tax->name; ?>
<?php else : ?>
				<?php _e( 'Blog Archives', 'cb-std-sys' ); ?>
<?php endif; ?>        
        </h1>

<?php if ( ! empty( $tax->description ) )
						echo '<div class="archive-meta">' . $tax->description . '</div>'; ?>
				</header>
        
<?php   /**
         *  Add action here to insert markup before the loop starts
         *  
         *  @since    0.2.1
         */                                     
          
        do_action( 'cbstdsys_before_loop' ); 
?>        

        
<?php		/* Run the loop for the category page to output the posts.
				 * If you want to overload this in a child theme then include a file
				 * called loop-category.php and that will be used instead.
				 */
				 get_template_part( 'loop', 'archive-' . sanitize_html_class( get_query_var( 'term' ) ) ); 
?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>