<?php get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
				
					</header>
						<?php if ( is_front_page() || is_home() ) { ?>
							<h2 class="entry-title" itemprop="name"><?php the_title(); ?></h2>
						<?php } else { ?>
							<h1 class="entry-title" itemprop="name"><?php the_title(); ?></h1>
						<?php } ?>
					</header>
					
					<div class="entry-content">
						<?php the_content(); ?>
						<?php do_action( 'numbered_in_page_links' ); ?>
						<?php edit_post_link( __( 'Edit', 'cb-std-sys' ), '', '' ); ?>
					</div><!-- .entry-content -->
				
				</article><!-- #post-## -->
				
<?php endwhile; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>