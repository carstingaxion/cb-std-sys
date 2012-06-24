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

<?php get_template_part( 'loop', 'four04' ); ?>
    
<?php get_sidebar(); ?>
<?php get_footer(); ?>