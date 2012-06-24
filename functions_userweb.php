<?php

if ( is_admin() ) {

} else {

    // deregister l10n.js (new since WordPress 3.1)
    // why you might want to keep it: http://wordpress.stackexchange.com/questions/5451/what-does-l10n-js-do-in-wordpress-3-1-and-how-do-i-remove-it/5484#5484
    wp_deregister_script('l10n');

    wp_deregister_script('jquery');
    $jquery = 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js';
    wp_enqueue_script('jquery', $jquery, false, '1.6.1', true);

    $std_script_path  = '/js/script.js';
    wp_enqueue_script( 'std_script', $std_script_path, array('jquery'), CB_STD_SYS_VERSION,  true );     


    /**
     *  Translations for the default script contents
     *
     *  @since    0.0.6  
     *       
     */     
      $std_script_i18n = array(
        'goback'      => __( '&larr; Take me back from where I came!', 'cb-std-sys' ),
      );
      wp_localize_script( 'std_script', 'std_script', $std_script_i18n );

    /**
     *  serve humans.txt
     *           
     *  @since    0.0.8
     *  
     */               
    function serve_humanstxt(){
    	header( 'Content-Type: text/plain; charset=utf-8' );
    ?>
    /* TEAM */
          Web Designer & Programmer: Carsten Bach
          Contact via : http://carsten-bach.de
          Twitter: @carstenbach
          From: Stuttgart, Baden-Würrtemberg, Germany
              
    							
    /* THANKS */
          Name: 
    			
    /* SITE */
          Last update: <?php echo mysql2date('l, d.m.Y H:i:s', get_lastpostmodified('GMT'), false); ?>          
          Language: <?php echo WPLANG."\n"; ?>
          Doctype: HTML5
          Tools: WordPress, PHP
          Standards: CSS3, WAI-ARIA, BITV 1
          Components: Modernizr, jQuery, html5boilerplate	  
    <?php	
      die();
    }
    if( $_SERVER['REQUEST_URI'] == '/humans.txt') {
        add_action('init','serve_humanstxt');
    }


                            

} // else is_admin()


?>