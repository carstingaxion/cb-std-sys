<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query. 
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage cb-std-sys
 * @since 0.0.1
 */

  get_header(); 

  /**
   *  Add action here to insert markup after the header.php is loaded
   *  
   *  @since    0.2.1
   */                                     
  do_action( 'cbstdsys_after_get_header' ); 


  /**
   *  Add action here to insert markup before the loop starts
   *  
   *  @since    0.2.1
   */                                     
  do_action( 'cbstdsys_before_loop' ); 
  
  get_template_part( 'loop', 'index' );
  
  get_sidebar(); 
  
  get_footer(); 

?>