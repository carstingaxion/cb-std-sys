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
					<h1 class="page-title"><?php
					printf( __( 'Category Archives: %s', 'cb-std-sys' ),  '<strong>' . single_cat_title( '', false ) . '</strong>' );
				?></h1>
				<?php
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo '<div class="archive-meta">' . $category_description . '</div>'; ?>
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
				get_template_part( 'loop', 'category' );
?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>