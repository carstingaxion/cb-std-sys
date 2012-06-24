<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ( $wp_query->max_num_pages > 1 ) : ?>
<?php echo contextual_pagination( 'loop', 'above' ); ?>
<?php endif; ?>



<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
<?php get_template_part( 'loop', 'four04' ); ?>
<?php endif; ?>


<?php while ( have_posts() ) : the_post(); ?>

<?php /* How to display posts in the Gallery category. */ ?>

	<?php if ( in_category( _x('gallery', 'gallery category slug', 'cb-std-sys') ) ) : ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
			<header>
				<h2 class="entry-title" itemprop="name"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cb-std-sys' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" itemprop="url"><?php the_title(); ?></a></h2>
				<div class="entry-meta posted-on"><?php cbstdsys_posted_on(); ?></div><!-- .entry-meta -->
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

				<?php cbstdsys_posted_in(); ?>

				<?php if ( comments_open() ) : ?>
				<p class="comments-link">
					<?php comments_popup_link( __( 'Leave a comment', 'cb-std-sys' ), __( '1 Comment', 'cb-std-sys' ), __( '% Comments', 'cb-std-sys' ), 'jump-to-comments', __( 'Comments are closed.', 'cb-std-sys' ) ); ?>
				</p>
				<?php endif; ?>

				<?php edit_post_link( __( 'Edit', 'cb-std-sys' ), '', '' ); ?>

			</footer><!-- .entry-utility -->
		</article><!-- #post-## -->



<?php /* How to display posts in the asides category */ ?>

	<?php elseif ( in_category( _x('asides', 'asides category slug', 'cb-std-sys') ) ) : ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php if ( is_archive() || is_search() ) : // Display excerpts for archives and search. ?>
		<p class="entry-summary" itemprop="description"><?php the_excerpt(); ?></p>

		<?php else : ?>
			<div class="entry-content">
				<?php the_content( cbstdsys_continue_reading_text() ); ?>
			</div><!-- .entry-content -->
			
		<?php endif; ?>

			<footer class="entry-utility">

				<?php cbstdsys_posted_in(); ?>

				<?php if ( comments_open() ) : ?>
				<p class="comments-link">
					<?php comments_popup_link( __( 'Leave a comment', 'cb-std-sys' ), __( '1 Comment', 'cb-std-sys' ), __( '% Comments', 'cb-std-sys' ), 'jump-to-comments', __( 'Comments are closed.', 'cb-std-sys' ) ); ?>
				</p>
				<?php endif; ?>

				<?php edit_post_link( __( 'Edit', 'cb-std-sys' ), '', '' ); ?>

			</footer><!-- .entry-utility -->
		</article><!-- #post-## -->



<?php /* How to display all other posts. */ ?>

	<?php else : ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
			<header>
				<h2 class="entry-title" itemprop="name"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cb-std-sys' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" itemprop="url"><?php the_title(); ?></a></h2>
				<div class="entry-meta posted-on"><?php cbstdsys_posted_on(); ?></div><!-- .entry-meta -->
			</header>

	<?php if ( is_archive() || is_search() ) : // Only display excerpts for archives and search. ?>
		<p class="entry-summary" itemprop="description"><?php echo get_the_excerpt(); ?></p>
			
 <?php else : ?>
			<div class="entry-content">
				<?php the_content( cbstdsys_continue_reading_text() ); ?>
				<?php do_action( 'numbered_in_page_links' ); ?>
			</div><!-- .entry-content -->
			
 <?php endif; ?>

			<footer class="entry-utility">

				<?php cbstdsys_posted_in(); ?>

				<?php if ( comments_open() ) : ?>
				<p class="comments-link">
						<?php comments_popup_link( __( 'Leave a comment', 'cb-std-sys' ), __( '1 Comment', 'cb-std-sys' ), __( '% Comments', 'cb-std-sys' ), 'jump-to-comments', __( 'Comments are closed.', 'cb-std-sys' ) ); ?>
				</p>
				<?php endif; ?>
				
				<?php edit_post_link( __( 'Edit', 'cb-std-sys' ), '', '' ); ?>
				
			</footer><!-- .entry-utility -->
		</article><!-- #post-## -->
		
		<?php if ( !is_archive() || !is_search() ) : // Only display excerpts for archives and search. ?>
				<?php comments_template( '/social-comments.php', true ); ?>
		<?php endif; ?>
		
	<?php endif; // This was the if statement that broke the loop into three parts based on categories. ?>

<?php endwhile; // End the loop. Whew. ?>


<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
<?php echo contextual_pagination( 'loop', 'below' ); ?>
<?php endif; ?>


<?php echo backtotop_link(); ?>