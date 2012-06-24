<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ( $wp_query->max_num_pages > 1 ) : ?>
<?php #echo contextual_pagination( 'loop', 'above' ); ?>
<?php endif; ?>


<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
<?php get_template_part( 'loop', 'four04' ); ?>
<?php endif; ?>


<?php while ( have_posts() ) : the_post(); ?>

<?php
/**
 *    Choose template part by post-format
 *
 *    The markup for the standard post (default) goes into
 *      format-standard.php
 *    The markup for all other post-formats goes into
 *      format.php
 *
 *    Re-Use this in childthemes and add files like
 *      format-whatever.php
 *    to define your own templates
 *
 *    @source   http://www.rvoodoo.com/projects/wordpress/wordpress-tip-post-formats-get-template-part-loop-php-and-maximum-child-theme-compatibility/
 *    @related  http://dougal.gunters.org/blog/2010/12/10/smarter-post-formats/
 *    @related  http://www.itsananderson.com/2010/12/even-smarter-post-formats/
 *    @since    0.2.0
 */
 
			$format = get_post_format();
      if ( false === $format )
            $format = 'standard';
      get_template_part( 'format', $format );

?>

<?php endwhile; // End the loop. Whew. ?>


<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
<?php echo contextual_pagination( 'loop', 'below' ); ?>
<?php endif; ?>


<?php echo backtotop_link(); ?>  