<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ( $wp_query->max_num_pages > 1 ) : ?>
<?php #echo contextual_pagination( 'loop', 'above' ); ?>
<?php endif; ?>


<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
<?php get_template_part( 'loop', 'four04' ); ?>
<?php endif; ?>

<?php

// Get all the taxonomies for this post type
$taxonomies = get_object_taxonomies( (object) array( 'post_type' => get_post_type() ) );

// if there are taxonomies regsitered for this post_type
// loop over them and return corresponding posts
if ( $taxonomies ) {

// loop over ervery taxonomy once
foreach( $taxonomies as $taxonomy ) : 

    // Gets every term in this taxonomy to get the respective posts
    $terms = get_terms( $taxonomy );

    // loop over ervery taxonomy_term
    foreach( $terms as $term ) : 

        // prepare new WP_Query object with posts matching the current taxonomy_term
        $args = array(
            'taxonomy' => $taxonomy,
            'term'     => $term->slug,
        );
?>
				<header>
					<h2 class="page-title"><?php echo $term->name; ?></h2>
<?php if ( ! empty( $term->description ) )
						echo '<div class="archive-meta">' . $term->description . '</div>'; ?>
				</header>

<?php        
        // setup WP_Query object
        $wp_query = new WP_Query( $args );

        while ( have_posts() ) : the_post(); 
 
      			$format = get_post_format();
            if ( false === $format )
                  $format = 'standard';
            get_template_part( 'format', $format );
  
        endwhile;  // End the post loop.

    endforeach; // End the taxonomy_term loop

endforeach;  // End the taxonomy loop


// if there are no taxonomies, loop over posts directly
} else {

    while ( have_posts() ) : the_post(); 

  			$format = get_post_format();
        if ( false === $format )
              $format = 'standard';
        get_template_part( 'format', $format );

    endwhile;  // End the post loop.

}
?>


<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
<?php echo contextual_pagination( 'loop', 'below' ); ?>
<?php endif; ?>


<?php echo backtotop_link(); ?>  