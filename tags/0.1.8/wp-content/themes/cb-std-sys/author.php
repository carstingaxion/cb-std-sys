<?php
/**
 * The template for displaying Author Archive pages.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */

get_header(); ?>

<?php
	/* Queue the first post, that way we know who
	 * the author is when we try to get their name,
	 * URL, description, avatar, etc.
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
	if ( have_posts() )
		the_post();
?>

				<header>
					<h1 class="page-title"><?php
					printf( __( 'Author Archives: %s', 'cb-std-sys' ),  '<strong>' . get_the_author() . '</strong>' );
				?></h1>
				<?php
					if ( cbstdsys_get_author_meta_block() )
						echo '<div class="archive-meta">' . cbstdsys_get_author_meta_block() . '</div>'; ?>
				</header>

<?php
	/* Since we called the_post() above, we need to
	 * rewind the loop back to the beginning that way
	 * we can run the loop properly, in full.
	 */
	rewind_posts();

	/* Run the loop for the author archive page to output the authors posts
	 * If you want to overload this in a child theme then include a file
	 * called loop-author.php and that will be used instead.
	 */
	 get_template_part( 'loop', 'author' );
?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>