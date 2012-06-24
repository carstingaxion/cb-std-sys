  <nav id="access" role="navigation">
<?php if ( cbstdsys_opts('m_multi_lang') && function_exists( 'language_selector_flags' ) ) { echo language_selector_flags(); } ?>
<?php get_search_form(); ?>
<?php   $cbstdsys_with_menu_description = new cbstdsys_Walker; ?>
<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary', 'walker' => $cbstdsys_with_menu_description ) ); ?>
  </nav>
  
  </section>
  
  <footer role="contentinfo" id="colophon">
<?php get_sidebar( 'footer' ); ?>
    <div id="site-info">
      <a class="copy-note" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">&copy; <?php echo cb_std_sys_copyright();?></a>
		<?php echo backtotop_link(); ?>
    </div>
  </footer>
  <div id="user-hints"><p id="jsnotice" class="error"><?php if ( cbstdsys_opts('m_multi_lang') ) {
echo sprintf( __('This Website is better usable and much more pretty with <a href="%1$s">javascript</a> enabled. <a href="%2$s" title="How to enable Javascript in your browser">Give it a try</a>!', 'cb-std-sys' ),'http://'.ICL_LANGUAGE_CODE.'.wikipedia.org/wiki/Javascript','http://www.enable-javascript.com/'.ICL_LANGUAGE_CODE.'/');
}else{
echo sprintf( __('This Website is better usable and much more pretty with <a href="%1$s">javascript</a> enabled. <a href="%2$s" title="How to enable Javascript in your browser">Give it a try</a>!', 'cb-std-sys' ),'http://wikipedia.org/wiki/Javascript','http://www.enable-javascript.com/');
} ?></p>
  <!--[if lt IE 7 ]><p id="ie-message" class="error"><?php echo sprintf( __('Sorry, but your browser is much too old, to show and use all features of this website in full pleasure. Please update to the <a href="%1$s">current Internet Explorer</a> or use a modern browser like <a href="%2$s">Firefox</a>, <a href="%3$s">Chrome</a> or <a href="%4$s">Safari</a>.','cb-std-sys'),'http://windows.microsoft.com/ie9', 'http://www.mozilla.com', 'http://www.google.com/chrome', 'http://www.apple.com/safari/');?></p><![endif]-->
  </div>
<?php wp_footer();?> 
</body>
</html>