<?php

 		$cbstdsys_opts = get_option('cbstdsys_options');
 		if ( $cbstdsys_opts['m_search'] ) {

        /**
         *  highlight the search-terms in the SERP
         *           
         *  @since    0.0.2 
         *  @author   Sergej Müller         
         *  @source                      
         */ 
        function the_search_excerpt( $excerpt ) {
        
          if ( is_search() ) {
          
              /* chars before and after the search-phrase */
              $length = 255;
            
              /* Excerpt */
              $excerpt = strip_shortcodes( apply_filters( 'the_content', $GLOBALS['post']->post_content ) );
            
              /* Shortcodes entfernen */
              $excerpt = strip_shortcodes($excerpt);
            
              /* Suchwort */
              $search = get_search_query();
            
              /* Leere Werte? */
              if (empty($excerpt) || empty($search)) {
                return null;
              }
            
              /* Suchwörter */
              $keywords = explode(' ', $search);
              $keyword = $keywords[0];
            
              /* Vorbereiten */
              array_walk( $keywords, create_function( '&$a', '$a = preg_quote($a);' ) );
            
              /* Nur hervorheben */
              if (strlen($excerpt) <= $length) { echo preg_replace( '/((<[^>]*)|'.implode('|', $keywords). ')/ieu', '"\2"=="\1"? "\1":"<strong class=\'search-phrase\'>\1</strong>"', $excerpt  );
                 return null;
              }
            
              /* Init */
              $break = '[sm]';
              $diff = round(($length - strlen($keyword)) / 2);
            
              /* Leerzeichen maskieren */
              $data = preg_replace_callback( '/<(.*?)>/ui', create_function( '$a', 'return "<".str_replace(" ", "+", $a[1]).">";' ), $excerpt );
            
              /* Links */
              $data = wordwrap( $data, (stripos($data, $keyword) - $diff), $break );
              $data = substr( $data, (stripos($data, $break) + strlen($break)) );
              $data = str_replace( $break, ' ', $data );
            
              /* Rechts */
              if (strlen($data) > $length) { 
                $data = wordwrap( $data, $length, $break );
                $data = substr( $data, 0, stripos($data, $break) );
                $data = str_replace( $break, ' ', $data );
              }
            
              /* Leerzeichen umwandeln */
              $data = preg_replace_callback( '/<(.*?)>/ui', create_function( '$a', 'return "<".str_replace("+", " ", $a[1]).">";' ), $data );
            
              /* Tags prüfen */
              $data = force_balance_tags($data);
            
              /* Ausgeben */
              return sprintf( '[...] %s [...]', preg_replace( '/((<[^>]*)|' .implode('|', $keywords). ')/ieu', '"\2"=="\1"? "\1":"<strong class=\'search-phrase\'>\1</strong>"', $data ) );
              
          } else {
              
              return $excerpt;
              
          }
        }
        add_filter('the_excerpt','the_search_excerpt');
        
            
        // redirect /?s to /search/
        // http://txfx.net/wordpress-plugins/nice-search/
         
        #
        function roots_nice_search_redirect() {
        	if (is_search() && strpos($_SERVER['REQUEST_URI'], '/wp-admin/') === false && strpos($_SERVER['REQUEST_URI'], '/'.__('search','cb-std-sys').'/') === false) {
    #        wp_redirect(home_url( __('search','cb-std-sys'). '/' . str_replace(array(' ', '%20'), array('+', '+'), urlencode(get_query_var( 's' )))), 301);
            wp_redirect(home_url( __('suche','cb-std-sys'). '/' . str_replace(array(' ', '%20'), array('+', '+'), urlencode(get_query_var( 's' )))), 301);
    
            exit();
        	}
        }
        #add_action('template_redirect', 'roots_nice_search_redirect');
        
        function roots_search_query($escaped = true) {
        	$query = apply_filters('roots_search_query', get_query_var('s'));
        	if ($escaped) {
            	$query = esc_attr( $query );
        	}
          	return urldecode($query);
        }
        #add_filter('get_search_query', 'roots_search_query');


        /**
         *  Search result for zero input
         *           
         *  Returns the user to the search site and the search template will prompt for a new query
         *
         *  @since    0.0.6  
         */                   
        function blank_search($query){
            global $wp_query;
            if (isset($_GET['s']) && ( $_GET['s']=='' || $_GET['s']==' ' ) ) {
                $wp_query->set('s','');
                $wp_query->is_search=true;
            }
            return $query;
        }
        add_action('pre_get_posts','blank_search');
        


        /**
         *  Suggest a corrected Version for small typos in a search query       
         *
         *  @since    0.0.6  
         *                        
         */   
          function spell_suggest() {
          	global $s;

          	$wpurl 	     = get_bloginfo('wpurl');
          	$yahooappid  = '3uiRXEzV34EzyTK7mz8RgdQABoMFswanQj_7q15.wFx_N4fv8_RPdxkD5cn89qc-';
            $yql_url     = 'http://query.yahooapis.com/v1/public/yql?';
            $query       = " SELECT * FROM search.spelling WHERE query=\"$s\" and appid=\"$yahooappid\" ";
            $query_url   = $yql_url . 'q=' . rawurlencode($query); 
            $suggests    = simplexml_load_file($query_url);
          	
          	if ( !empty( $suggests->results ) ) {
          			foreach ($suggests->results as $result) {
          				$output .= '<a href="'.$wpurl.'?s='.$result->suggestion.'" rel="nofollow">'.$result->suggestion.'</a>';
          			}
          			return _e( 'Or did you mean', 'cb-std-sys' ).' <strong class="spell-suggest">'.$output.'</strong>?';
          	} else {
          		  return false;
          	}
          }        



    } else { // if search is not used

    
    
        /**
         *  Deactivate the search
         *           
         *  Returns the user to the 404 page if query is s=... or /search/...          
         *
         *  @since    0.0.6  
         *  @source   http://wpengineer.com/1042/disable-wordpress-search/                    
         */   
        function fb_filter_query( $query, $error = false ) {
            if ( is_search() ) {
                $query->is_search = false;
                $query->query_vars[s] = false;
                $query->query[s] = false;
            // to error
            if ( $error == true )
                $query->is_404 = true;
            }
        }
        add_action( 'parse_query', 'fb_filter_query',1 );
        add_filter( 'get_search_form', create_function( '$a', "return null;" ) ); 
        
        
        
    
        /**
         *  Deactivate the search widget
         *  and hide it from widget options and from FE, if is uesed                  
         *
         *  @since    0.0.6  
         */         
        function unregister_search_widgets() {
        	unregister_widget( 'WP_Widget_Search' );
        }
        add_action( 'widgets_init', 'unregister_search_widgets' );

}

?>