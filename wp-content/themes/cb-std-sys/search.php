<?php get_header(); ?> 

<?php
      /**
       *  Add action here to insert markup after the header.php is loaded
       *  
       *  @since    0.2.1
       */                                     
        
      do_action( 'cbstdsys_after_get_header' ); 
?>

<?php $q  =  get_search_query(); ?>
<?php if ( have_posts() ) : ?>
<?php if ($q=='' || $q==' ') : ?>
				<article id="post-0" class="post hentry no-results not-found">
					<h1 class="entry-title"><?php _e( 'Nothing Found', 'cb-std-sys' ); ?></h1>
					<div class="entry-content">
  					<p class="error"><?php _e( 'Sorry, but you need to enter a search word in the search field.', 'cb-std-sys' ); ?></p>
  					<?php get_search_form(); ?>			
					</div>
				</article>
				
				
<?php else : ?>
				<header>
				  <h1 class="page-title">
            <?php 
            	 printf( _n( 'There is one Search result for: %2$s', 'There are %1$s Search results for: %2$s', $wp_query->found_posts, 'cb-std-sys' ),
			         number_format_i18n( $wp_query->found_posts ), ' <strong class=\'search-phrase\'>'.$q.'</strong>' );
            ?>
            </h1>
				</header>				
<?php
      /**
       *  Add action here to insert markup before the loop starts
       *  
       *  @since    0.2.1
       */                                     
        
      do_action( 'cbstdsys_before_loop' ); 
?>

				<?php get_template_part( 'loop', 'search' ); ?>
				
				
<?php endif; ?>				
<?php else : ?>
				<article id="post-0" class="post hentry no-results not-found">
					<h1 class="entry-title"><?php _e( 'Nothing Found for:', 'cb-std-sys' ); echo ' <strong class=\'search-phrase\'>'.$q.'</strong>';  ?></h1>
					<div class="entry-content">					
            <p class="error">
              <?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'cb-std-sys' ); ?>
              <?php #echo spell_suggest(); ?>
            </p>
  					  <?php get_search_form(); ?>
					</div>
				</article>					
<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>