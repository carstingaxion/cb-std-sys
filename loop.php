<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage Twenty_Ten_Five
 * @since Twenty Ten Five 1.0
 */
?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ( $wp_query->max_num_pages > 1 ) : ?>
<?php echo contextual_pagination( 'loop', 'above' ); ?>
<?php endif; ?>

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<article id="post-0" class="post error404 not-found">
		<header>
			<h1 class="entry-title"><?php _e( 'Nothing Found', 'cb-std-sys' ); ?></h1>
		</header>

		<div class="entry-content">
			<p class="error"><?php _e( 'Apologies, but no results were found for the requested archive.', 'cb-std-sys' ); ?></p>
<?php
 		$cbstdsys_opts = get_option('cbstdsys_options');
 		if ( $cbstdsys_opts['m_search'] ) {
?>
				
				<h2><?php _e( 'Perhaps searching will help.', 'cb-std-sys' ); ?></h2>
				<?php get_search_form(); ?>
<?php     } ?>
		</div><!-- .entry-content -->
	</article><!-- #post-0 -->
<?php endif; ?>

<?php
	/* Start the Loop.
	 *
	 * In Twenty Ten we use the same loop in multiple contexts.
	 * It is broken into three main parts: when we're displaying
	 * posts that are in the gallery category, when we're displaying
	 * posts in the asides category, and finally all other posts.
	 *
	 * Additionally, we sometimes check for whether we are on an
	 * archive page, a search page, etc., allowing for small differences
	 * in the loop on each template without actually duplicating
	 * the rest of the loop that is shared.
	 *
	 * Without further ado, the loop:
	 */ ?>
<?php while ( have_posts() ) : the_post(); ?>

<?php /* How to display posts in the Gallery category. */ ?>

	<?php if ( in_category( _x('gallery', 'gallery category slug', 'cb-std-sys') ) ) : ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header>
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cb-std-sys' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

				<p class="entry-meta">
					<?php twentyten_posted_on(); ?>
				</p><!-- .entry-meta -->
			</header>

			<div class="entry-content">
<?php if ( post_password_required() ) : ?>
				<?php the_content(); ?>
<?php else : ?>
				<div class="gallery-thumb">
<?php
	$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
	$total_images = count( $images );
	$image = array_shift( $images );
	$image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
?>
					<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
				</div><!-- .gallery-thumb -->
				<p><em><?php printf( __( 'This gallery contains <a %1$s>%2$s photos</a>.', 'cb-std-sys' ),
						'href="' . get_permalink() . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'cb-std-sys' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
						$total_images
					); ?></em></p>

				<?php the_excerpt(); ?>
<?php endif; ?>
			</div><!-- .entry-content -->

			<footer class="entry-utility">
				<a href="<?php echo get_term_link( _x('gallery', 'gallery category slug', 'cb-std-sys'), 'category' ); ?>" title="<?php esc_attr_e( 'View posts in the Gallery category', 'cb-std-sys' ); ?>"><?php _e( 'More Galleries', 'cb-std-sys' ); ?></a>
				<span class="meta-sep">|</span>
				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'cb-std-sys' ), __( '1 Comment', 'cb-std-sys' ), __( '% Comments', 'cb-std-sys' ) ); ?></span>
				<?php edit_post_link( __( 'Edit', 'cb-std-sys' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
			</footer><!-- .entry-utility -->
		</article><!-- #post-## -->

<?php /* How to display posts in the asides category */ ?>

	<?php elseif ( in_category( _x('asides', 'asides category slug', 'cb-std-sys') ) ) : ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php if ( is_archive() || is_search() ) : // Display excerpts for archives and search. ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
		<?php else : ?>
			<div class="entry-content">
				<?php the_content( sprintf( __( 'Continue reading \'%s\'', 'cb-std-sys' ), get_the_title() ). '<span class="meta-nav">&rarr;</span>' ); ?>
			</div><!-- .entry-content -->
		<?php endif; ?>

			<footer class="entry-utility">
				<?php twentyten_posted_on(); ?>
				<span class="meta-sep">|</span>
				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'cb-std-sys' ), __( '1 Comment', 'cb-std-sys' ), __( '% Comments', 'cb-std-sys' ) ); ?></span>
				<?php edit_post_link( __( 'Edit', 'cb-std-sys' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
			</footer><!-- .entry-utility -->
		</article><!-- #post-## -->

<?php /* How to display all other posts. */ ?>
<!--
 

<?php
  if( isset($paged) && ( intval($paged) > 1 ) ) {
  echo '<ol start="' . ( ($paged - 1) * $posts_per_page + 1 ) . '">';
  } else {
  echo '</ol><ol>';
  } ?>
<?php while (have_posts()) : the_post(); ?>
<li class="search-result">
<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
<!-- der Relevanssi Textauszug (s.u.) -->
<?php if (function_exists('relevanssi_the_excerpt')) { relevanssi_the_excerpt(); }; ?>
</li>
<?php endwhile; ?>
</ol>
-->



 */ ?>
	<?php else : ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header>
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cb-std-sys' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

				<p class="entry-meta">
					<?php twentyten_posted_on(); ?>
				</p><!-- .entry-meta -->
			</header>

	<?php if ( is_archive() || is_search() ) : // Only display excerpts for archives and search. ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
	<?php else : ?>
			<div class="entry-content">
				<?php the_content( sprintf( __( 'Continue reading \'%s\'', 'cb-std-sys' ), get_the_title() ). '<span class="meta-nav">&rarr;</span>' ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'cb-std-sys' ), 'after' => '</div>' ) ); ?>
			</div><!-- .entry-content -->
	<?php endif; ?>

			<footer class="entry-utility">
				<?php if ( count( get_the_category() ) ) : ?>
					<span class="cat-links">
						<?php printf( __( '<span class="%1$s">Posted in</span> %2$s', 'cb-std-sys' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
					</span>
					<span class="meta-sep">|</span>
				<?php endif; ?>
				<?php
					$tags_list = get_the_tag_list( '', ', ' );
					if ( $tags_list ):
				?>
					<span class="tag-links">
						<?php printf( __( '<span class="%1$s">Tagged</span> %2$s', 'cb-std-sys' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?>
					</span>
					<span class="meta-sep">|</span>
				<?php endif; ?>
				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'cb-std-sys' ), __( '1 Comment', 'cb-std-sys' ), __( '% Comments', 'cb-std-sys' ) ); ?></span>
				<?php edit_post_link( __( 'Edit', 'cb-std-sys' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
			</footer><!-- .entry-utility -->
		</article><!-- #post-## -->

		<?php comments_template( '', true ); ?>

	<?php endif; // This was the if statement that broke the loop into three parts based on categories. ?>

<?php endwhile; // End the loop. Whew. ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
<?php echo contextual_pagination( 'loop', 'below' ); ?>
<?php endif; ?>

<?php /* #top */ ?>
      <a href="<?php echo $_SERVER['REQUEST_URI']; ?>#top"  class="visuallyhidden focusable" title="<?php _e('back to top','cb-std-sys')?>"><?php _e('back to top','cb-std-sys')?></a>
