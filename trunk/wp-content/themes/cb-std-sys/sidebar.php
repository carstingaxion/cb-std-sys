<?php if ( apply_filters( 'cbstdsys_wrap_loop_markup', false ) ) : ?>
</div>  <!-- // end div#main  -->
<?php endif; // wrap loop into markup-div ?>

<?php if ( is_active_sidebar( 'primary-widget-area' ) || is_active_sidebar( 'secondary-widget-area' ) ) : ?>
<aside id="sidebar" role="complementary">
<?php if ( is_active_sidebar( 'primary-widget-area' ) ) : ?>
  <section class="xoxo" id="primary-sidebar">
<?php dynamic_sidebar( 'primary-widget-area' ); ?>
  </section>
<?php endif; ?>  
<?php if ( is_active_sidebar( 'secondary-widget-area' ) ) : ?>
  <section class="xoxo" id="secondary-sidebar">
<?php dynamic_sidebar( 'secondary-widget-area' ); ?>
  </section>
<?php endif; ?>
</aside>
<?php endif; ?>