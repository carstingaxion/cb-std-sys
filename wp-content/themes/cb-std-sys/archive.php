<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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

<?php
	/* Queue the first post, that way we know
	 * what date we're dealing with (if that is the case).
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
	if ( have_posts() )
		the_post();
?>

		<header>
			<h1 class="page-title">

<?php if ( is_day() ) : ?>
				<?php printf( __( 'Daily Archives: %s', 'cb-std-sys' ), '<strong>' . get_the_date() . '</strong>' ); ?>
<?php elseif ( is_month() ) : ?>
				<?php printf( __( 'Monthly Archives: %s', 'cb-std-sys' ), '<strong>' . get_the_date('F Y') . '</strong>' ); ?>
<?php elseif ( is_year() ) : ?>
				<?php printf( __( 'Yearly Archives: %s', 'cb-std-sys' ), '<strong>' . get_the_date('Y') . '</strong>' ); ?>
<?php elseif ( is_post_type_archive() ) : ?>
        <?php post_type_archive_title(); ?>
<?php elseif ( is_tax()  ) : ?>
				<?php echo get_queried_object()->name; ?>
<?php else : ?>
				<?php _e( 'Blog Archives', 'cb-std-sys' ); ?>
<?php endif; ?>

			</h1>
		</header>

<?php
	/* Since we called the_post() above, we need to
	 * rewind the loop back to the beginning that way
	 * we can run the loop properly, in full.
	 */
	rewind_posts();


  /**
   *  Add action here to insert markup before the loop starts
   *  
   *  @since    0.2.1
   */                                     
    
  do_action( 'cbstdsys_before_loop' ); 


	/* Run the loop for the archives page to output the posts.
	 * If you want to overload this in a child theme then include a file
	 * called loop-archives.php and that will be used instead.
	 */
?>
<?php if ( is_day() ) : ?>
				<?php get_template_part( 'loop', 'archive-day' ); ?>
<?php elseif ( is_month() ) : ?>
				<?php get_template_part( 'loop', 'archive-month' ); ?>
<?php elseif ( is_year() ) : ?>
				<?php get_template_part( 'loop', 'archive-year' ); ?>
<?php elseif ( is_post_type_archive() ) : ?>
				<?php get_template_part( 'loop-posttype-archive', sanitize_html_class( get_post_type() ) ); ?>
<?php elseif ( is_tax()  ) : ?>
				<?php get_template_part( 'loop-taxonomy-archive', sanitize_html_class( get_query_var( 'term' ) ) ); ?>
<?php else : ?>
				<?php get_template_part( 'loop', 'archive-blog' ); ?>
<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>