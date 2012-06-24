<?php

 		if ( cbstdsys_opts('m_search') ) {

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
              
              /* whitespace, leerzeichen und leere Zeilen löschen */
               $data = trim( strip_tags( $data,"\x22\x27\n" ));
            
              /* Ausgeben */
              return sprintf( '<p>&hellip; %s &hellip;</p>', preg_replace( '/((<[^>]*)|' .implode('|', $keywords). ')/ieu', '"\2"=="\1"? "\1":"<strong class=\'search-phrase\'>\1</strong>"', $data ) );
              
          } else {
              
              return $excerpt;
              
          }
        }
        add_filter('the_excerpt','the_search_excerpt');
        
            

        /**
         *  Redirect /?s= to /search
         *  /search will be replaced by i18n-string
         *
         *  @since  0.1.5
         *  @source http://txfx.net/wordpress-plugins/nice-search/
         */
        function search_pretty_i18n_permalink_redirect() {
	        	if (is_search() && strpos($_SERVER['REQUEST_URI'], '/wp-admin/') === false && strpos($_SERVER['REQUEST_URI'], '/'._x('search','search rewrite slug for use with permalinks','cb-std-sys').'/') === false) {
		            wp_redirect(home_url( _x('search','search rewrite slug for use with permalinks','cb-std-sys'). '/' . str_replace( array(' ', '%20'), array('+', '+'), urlencode( get_query_var( 's' ) ) ) ), 301);
		            exit();
	        	}
        }
        add_action('template_redirect', 'search_pretty_i18n_permalink_redirect');



				/**
				 *  Translate the pretty search permalink in the current language
				 *
				 *  @since  0.1.5
				 *  @TODO   benötigt wird eine Funktion, die flush_rewrite_rules() nach dem Sprachwechsel ausführt damit /search/e in die passende Sprache umgeschrieben wird
				 */
				function i18n_search_url( $search_rewrite ) {

						if( !is_array( $search_rewrite ) ) { return $search_rewrite; }

						$new_array = array();
						foreach( $search_rewrite as $pattern => $s_query_string ) {
								$new_array[ str_replace( 'search/', _x('search','search rewrite slug for use with permalinks','cb-std-sys').'/', $pattern ) ] = $s_query_string;
						}
						$search_rewrite = $new_array;
						unset( $new_array );

						return $search_rewrite;
				}
				add_filter('search_rewrite_rules', 'i18n_search_url');



				/**
				 *  Filter & Clean Serach Query Output
				 *
				 *  @since  	0.1.5
				 *  @source   roots-Theme
				 */
        function roots_search_query($escaped = true) {
        	$query = apply_filters('roots_search_query', get_query_var('s'));
        	if ($escaped) {
            	$query = esc_attr( $query );
        	}
          	return urldecode($query);
        }
        add_filter('get_search_query', 'roots_search_query',1);



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
                $wp_query->is_search = true;
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

          	$yahooappid  = '3uiRXEzV34EzyTK7mz8RgdQABoMFswanQj_7q15.wFx_N4fv8_RPdxkD5cn89qc-';
            $yql_url     = 'http://query.yahooapis.com/v1/public/yql?';
            $query       = " SELECT * FROM search.spelling WHERE query=\"$s\" and appid=\"$yahooappid\" ";
            $query_url   = $yql_url . 'q=' . rawurlencode($query); 
            $suggests    = simplexml_load_file($query_url);
          	
          	if ( !empty( $suggests->results ) ) {
          			foreach ($suggests->results as $result) {
          				$output .= '<a href="/?s='.$result->suggestion.'" rel="nofollow">'.$result->suggestion.'</a>';
          			}
          			return _e( 'Or did you mean', 'cb-std-sys' ).' <strong class="spell-suggest">'.$output.'</strong>?';
          	} else {
          		  return false;
          	}
          }        



    } else { // if search is not used

    
    
		    /**
		     *	Deactivates search pages & feeds
		     *  same as in hook_frontend.php
		     *
		     *  @since  0.1.5
		     *  @source http://betterwp.net/wordpress-tips/disable-some-wordpress-pages/
		     */
		    function kill_search_page_and_feed ( $posts ) {
		        global $wp_query, $post;

		        if ( is_search() ) {
		            $wp_query->set_404();
		            status_header( 404 );
		        }

		        if (is_feed()) {
		            $search     = get_query_var('s');

		            if ( !empty($search) ) {
		                $wp_query->set_404();
		                $wp_query->is_feed = false;
		                status_header( 404 );
		            }
		        }
		        return $posts;
		    }
		    add_action('the_posts', 'kill_search_page_and_feed');

}

?>