<?php get_header(); ?>

<?php
      /**
       *  Add action here to insert markup after the header.php is loaded
       *  
       *  @since    0.2.1
       */                                     
        
      do_action( 'cbstdsys_after_get_header' ); 
?>

<?php
  /**
   *  Add action here to insert markup before the loop starts
   *  
   *  @since    0.2.1
   */                                     
    
  do_action( 'cbstdsys_before_loop' ); 
?>  

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
	</article>
        <?php echo contextual_pagination( 'single', 'below' ); ?>
				<?php comments_template( '/social-comments.php', true ); ?>

<?php endwhile; // end of the loop. ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>