  </section>
  <footer role="contentinfo" id="colophon">
<?php get_sidebar( 'footer' ); ?>
    <div id="site-info">
      <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">&copy; <?php echo cb_std_sys_copyright();?></a>
      <a href="<?php echo $_SERVER['REQUEST_URI']; ?>#top"  class="visuallyhidden focusable" title="<?php _e('back to top','cb-std-sys')?>"><?php _e('back to top','cb-std-sys')?></a>
    </div>
  </footer>
<?php wp_footer();?> 
  <!-- scripts concatenated and minified via ant build script-->
  <script src="js/plugins.js"></script>
  
  <?php 
        // ausgelagert in die /hook_frontend.php
        
        // Utilities class aus  "NakedCompass"-Theme hilft hier weiter
  
  ?>

  <!-- end concatenated and minified scripts-->
  <!--[if (gte IE 6)&(lte IE 9)]>
   <script src="js/libs/selectivizr.1.0.2.min.js"></script>
  <![endif]-->
  <!--[if lt IE 7 ]>
  <script src="js/libs/dd_belatedpng.js"></script>
  <script>DD_belatedPNG.fix("img, .png_bg");</script>
  <![endif]-->
<?php	
if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
if ( is_user_logged_in() ) {
  $current_user = wp_get_current_user();
  if ( $current_user->ID == 1 && isset( $_GET['profiling'] ) )  { ?>
  <!-- yui profiler and profileviewer  -->
  <script src="js/profiling/yahoo-profiling.min.js?v=1"></script>
  <script src="js/profiling/config.js?v=1"></script>
  <!-- end profiling code -->  
<?php } }?>

</body>
</html>