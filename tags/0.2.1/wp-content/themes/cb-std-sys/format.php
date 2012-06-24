<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> itemscope itemtype="http://schema.org/Article">

      <header>
				<h3 class="entry-title" itemprop="name"><?php #echo 'format '.get_post_format(); ?><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cb-std-sys' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" itemprop="url"><?php the_title(); ?></a></h3>
				<!--<div class="entry-meta posted-on"><?php cbstdsys_posted_on(); ?></div> .entry-meta -->
				<time datetime="<?php the_time('c') ?>" pubdate class="updated entry-date published" itemprop="datePublished" content="<?php the_date('Y-m-d') ?>" title="<?php echo get_the_date('Y-m-d') ?>"><?php echo get_the_date('d|m') ?></time>
			</header>

			<?php if(
					// sticky post on homepage
					( ( is_home() || is_front_page() ) && is_sticky() )
					// category blog
					|| ( is_category('1') ) ) :
          $content = get_the_content();
          $content = apply_filters('the_content', $content);
					$content = str_replace(']]>', ']]&gt;', $content);
			?>
					<p class="entry-summary" itemprop="description"><?php echo get_the_excerpt(); ?></p>
					<div class="entry-content" itemprop="description"><?php echo $content; ?></div>
			<?php else : ?>
        	<?php if ( has_post_thumbnail() ) : ?>
	        <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cb-std-sys' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="alignleft thumb-link">
					<?php echo clean_wp_width_height( get_the_post_thumbnail( get_the_ID(), 'thumbnail' ) ); ?>
           </a>
					<?php endif; ?>
					<?php if ( has_excerpt( ) ) : ?>
					<p class="entry-summary" itemprop="description"><?php echo get_the_excerpt(); ?></p>
					<?php endif; ?>

			<?php endif; ?>

			<footer class="entry-utility">

				<a class="author-link" href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )) ?>"><?php echo get_the_author_meta('display_name') ?></a>
				<?php	if(get_the_tag_list()) {
						    echo get_the_tag_list('<ul class="tags"><li>','</li><li>','</li></ul>');
						}
				?>
				<?php if ( comments_open() ) : ?>
				<?php comments_popup_link( '0', '1', '%', 'jump-to-comments', __( 'Comments are closed.', 'cb-std-sys' ) ); ?>
				<?php endif; ?>

			</footer><!-- .entry-utility -->

</article><!-- #post-## -->