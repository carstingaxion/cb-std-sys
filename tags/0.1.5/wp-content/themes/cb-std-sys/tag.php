<?php
/**
 * The template for displaying Tag Archive pages.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */

get_header(); ?>

		<header>
			<h1 class="page-title">
				<?php printf( __( 'Tag Archives: %s', 'cb-std-sys' ), '<strong>' . single_tag_title( '', false ) . '</strong>' ); ?>
			</h1>
		</header>
<?php
/* Run the loop for the tag archive to output the posts
 * If you want to overload this in a child theme then include a file
 * called loop-tag.php and that will be used instead.
 */
 get_template_part( 'loop', 'tag' );
?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>