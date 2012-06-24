<?php get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

          <?php echo contextual_pagination( 'single', 'above' ); ?>
          
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
		<header>	
					<h1 class="entry-title" itemprop="name"><?php the_title(); ?></h1>
					<div class="entry-meta posted-on"><?php cbstdsys_posted_on(); ?></div><!-- .entry-meta -->
		</header>

		<p class="entry-summary important" itemprop="description"><?php echo get_the_excerpt(); ?></p>
		
		<div class="entry-content">
						<?php the_content(); ?>
						<?php do_action( 'numbered_in_page_links' ); ?>

						<?php cbstdsys_get_author_meta_block(); ?>

						<?php cbstdsys_posted_in(); ?>
						<?php edit_post_link( __( 'Edit', 'cb-std-sys' ), '', '' ); ?>
						
		</div><!-- .entry-content -->
	</article>
        <?php echo contextual_pagination( 'single', 'below' ); ?>
				<?php comments_template( '/social-comments.php', true ); ?>

<?php endwhile; // end of the loop. ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>