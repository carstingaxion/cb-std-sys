<?php
/*******************************************************************************
 *
 *  This file modifies common used plugins
 *
 *******************************************************************************/

		/**   WPML - Sitepress Multilingual CMS    **/
		global $sitepress;
		// remove language switcher, 'cause it messes with the WP backend styles
		remove_action('in_admin_header', array($sitepress, 'admin_language_switcher'));
		// remove meta-generator tag
		remove_action('wp_head', array($sitepress, 'meta_generator_tag'));
    
    
    
    /**
     *  adjust  WPML output
     *  
     *  - remove basic-css files
     *  - remove navigation-css files
     *  - remove basic-js
     *  + filter home_url() to use icl_home_url()
     *  + build own language_selector_flags() function
     *  
     *  @since    0.0.7
     *
     *  @todo : auskommentiert und keine Ahnung warum, Fehler war (glaube ich) bei die-wo-spielen.de
     *               
     */         
    if (  cbstdsys_opts('m_multi_lang')  ) {
        // WP Plugin WPML sitepress-multilingual-cms
        function get_icl_home_url($url) {
            $iclurl  = icl_get_home_url();
            $url  = str_replace( WP_HOME.'/', $iclurl, $url );
            return $url;
        }
        // Sprachenumschalter mit Flaggen über WPML zur Verfügung stellen  
        function language_selector_flags(){
            $languages = icl_get_languages('skip_missing=0&orderby=code');
            if(!empty($languages)){
                $lang_selector  = '<ul id="icl_lang_selector">';
                    foreach($languages as $l){
                    #var_dump($l);
                        if(!$l['active']) {
                        $lang_selector  .= '<li class="selector-element-'.$l['language_code'].'"><a hreflang="'.$l['language_code'].'" href="'.$l['url'].'">';
                        #$lang_selector  .= '<img src="'.plugins_url('sitepress-multilingual-cms/res/flags/'.$l['language_code'].'.png').'" alt="'.$l['native_name'].'" >';
                        $lang_selector  .= '<span lang="'.$l['language_code'].'">'.$l['native_name'].'</span>';
                        $lang_selector  .= '</a></li>';
                        }
                    }
                $lang_selector  .= '</ul>';
                }
            return $lang_selector;
        }   
         
        define( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS' , true);
        define( 'ICL_DONT_LOAD_LANGUAGES_JS' , true);
        define( 'ICL_DONT_LOAD_NAVIGATION_CSS' , true);
        #add_filter( 'home_url','get_icl_home_url', 0, 1 );
    }

  
  		

		/**	 SOCIAL - by Mailchimp **/
		// Disable default CSS and JS for the Social plugin
		define('SOCIAL_COMMENTS_CSS', false);
		define('SOCIAL_COMMENTS_JS', false);

		// Define custom comments file for Social plugin
		define('SOCIAL_COMMENTS_FILE', STYLESHEETPATH.'/social-comments.php');
    
    
    
		/**
		 *  Show "User Photo"-Plugin Thumb in userlist on users.php
		 *
		 *  @since  0.1.8
		 */
		function filter_users_table_output ( ) {
				add_filter( 'get_avatar', 'userphoto_filter_get_avatar', 10, 4 );
		}
		if( get_option( 'userphoto_override_avatar' ) )
				add_action( 'init', 'filter_users_table_output' );

    
?>