<?php
if ( is_admin() ) {




		
} else {


		/**
		 *  Deregister jQuery by default for every post / page
		 *
		 *  just left it inside for domain.tld/?authorized
		 *  used by 'Mailchimp SOCIAL'-Plugin to redirect after
		 *  twitter- or facebook-Login
		 *
		 *  @since  0.1.5
		 */
		function mailchimp_social_authorized_needs_jquery() {
		    if ( !get_query_var('authorized') ){
						wp_deregister_script('jquery');
		    }
		}
		add_action('init', 'mailchimp_social_authorized_needs_jquery');



		/**
     *	deregister l10n.js
     *	(new since WordPress 3.1)
     *
     *	why you might want to keep it:
     *	http://wordpress.stackexchange.com/questions/5451/what-does-l10n-js-do-in-wordpress-3-1-and-how-do-i-remove-it/5484#5484
     */
    wp_deregister_script('l10n');



		/**
		 *  Prepare, Load and Minify CSS and JS
		 *  conditionally based on shortcodes, post->types and conditional_tags
		 *
		 *  @since  0.1.2
		 */
		if ( ! function_exists( 'cond_load_js_and_css' ) ) :
				function cond_load_js_and_css($post){

            global $current_user;
            
		        /**
		         *  Construct corret URIs for use with 'BWP-Minify'-PLugin
		         *
		         *  @since  0.1.2
		         */
            $bwp_minify_plugins_url_addon = $bwp_minify_themes_url_addon 	= ''; 
		        if ( class_exists('BWP_MINIFY') ) {
								$bwp_minify_plugins_url_addon = WP_CONTENT_URL;
								$bwp_minify_themes_url_addon 	= WP_CONTENT_URL.str_replace( WP_CONTENT_DIR, '', TEMPLATEPATH );
						}


		        /**
		         *  Load jQuery via Google CDN
		         *  and fallback to local version
		         *
		         *  @since 0.1.2
		         */
						$jquery = 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js';
						if ( !fopen($jquery, 'r') ) {
		      	$jquery = $bwp_minify_themes_url_addon.'/js/libs/jquery-1.8.1.min.js';
						}
		        $jquery_ver = '1.8.1';
/*
// liefert leider immer NULL
var_dump( wp_register_script( 'jquery', includes_url( 'js/jquery/jquery.js' ), null, $jquery_ver, true ) );

// Load jQuery in footer
if ( ! is_a( $wp_scripts, 'WP_Scripts' ) ) {
    $wp_scripts = new WP_Scripts();
}
$jquery = $wp_scripts->query( 'jquery' );
if ( ! empty( $jquery ) ) {
    $jquery_ver = $wp_scripts->query( 'jquery' )->ver;
    wp_deregister_script( 'jquery' );
    //wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/' . $jquery_ver . '/jquery.min.js', null, null, true );
    wp_register_script( 'jquery', includes_url( 'js/jquery/jquery.js' ), null, $jquery_ver, true );
} else {
    $jquery_ver = '1.7.2';
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', WP_THEME_URL . '/js/libs/jquery-' . $jquery_ver . '.min.js', null, null, true );
}
wp_enqueue_script( 'jquery' );
*/


						/**
						 *  Load fancybox scripts and styles
						 *
						 *  @since 0.1.2
						 */
		        $fancybox   		= $bwp_minify_themes_url_addon.'/js/libs/fancybox/jquery.fancybox-1.3.4.pack.js';
		        $fancybox_ver 	= '1.3.4';
		        $mousewheel 		= $bwp_minify_themes_url_addon.'/js/libs/fancybox/jquery.mousewheel-3.0.4.pack.js';
		        $mousewheel_ver = '3.0.4';
		        $easing     		= $bwp_minify_themes_url_addon.'/js/libs/fancybox/jquery.easing-1.3.pack.js';
		        $easing_ver 		= '1.3';
		        $fancybox_c			= $bwp_minify_themes_url_addon.'/js/jquery.fancybox.caller.js';

		        $fancycss 			= $bwp_minify_themes_url_addon.'/js/libs/fancybox/jquery.fancybox-1.3.4.css';
		        $fancycss_ver 	= '1.3.4';


						/**
						 *  Load default script with additional functions
						 *
						 *  @since  0.1.2
						 */
		        $std_script_path  = $bwp_minify_themes_url_addon.'/js/script.js';


		        /**
		         *  Translations for the default script contents
		         *
		         *  @since    0.0.6
		         */
		        $std_script_i18n = array(
		          'goback'      => __( '&larr; Take me back from where I came!', 'cb-std-sys' ),
		        );


		        /**
		         *  Comments Scripts from
		         *  - 'MailChimp Social'-Plugin
		         *  - jQuery Quicktags
		         *
		         *  @since  0.1.2
		         */
		         $mc_social_js  = $bwp_minify_plugins_url_addon.'/plugins/social/assets/social.js';
		         $jq_qtags      = $bwp_minify_themes_url_addon.'/js/libs/jquery-qtags/jquery.qtags-1.2.js';


						/**
		         *  Translations for jQuery Quicktags
		         *
		         *  @since    0.1.4
		         */
		        $jq_qtags_i18n = array(
		          'strong'      => __( 'Tag', 'cb-std-sys' ),
		          'em'      		=> __( 'Important', 'cb-std-sys' ),
		          's'      			=> __( 'Deleted', 'cb-std-sys' ),
		          'url'      		=> __( 'Link', 'cb-std-sys' ),
		          'urlhref'			=> __( 'URL-Adress', 'cb-std-sys' ),
		          'code'      	=> __( 'Code', 'cb-std-sys' ),
		          'quote'      	=> __( 'Cite', 'cb-std-sys' ),
		          'quotecite'  	=> __( 'Source', 'cb-std-sys' )
		        );

						/**
						 *  Auto resize textareas
						 *
						 *  @since  0.1.4
						 *  @source http://www.azoffdesign.com/autoresize
						 */
						$jq_resize_textarea = $bwp_minify_themes_url_addon.'/js/libs/jquery.autoresize.textarea.js';


						/**
						 *  Form & Comment CSS
						 *
						 *  @since  0.1.2
						 */
						 $forms_css 		= $bwp_minify_themes_url_addon.'/css/forms.css';
						 $comment_css  	= $bwp_minify_themes_url_addon.'/css/comments.css';


						/**
						 *  schema.org markup debug
						 *
						 *  @since  0.1.8
						 *  @source https://github.com/KrofDrakula/microdata-tool
						 */
						$schema_org_debug_lib = $bwp_minify_themes_url_addon.'/js/libs/microdata-debug/jquery.microdata.js';
						$schema_org_debug = $bwp_minify_themes_url_addon.'/js/libs/microdata-debug/schemas.js';
						

						// style.css - Default Styles
						wp_enqueue_style( 'style-css', $bwp_minify_themes_url_addon.'/css/style.css', false, CB_STD_SYS_VERSION );

						// handheld.css - for the oldskool mobile devices
						$agents = array('Windows CE', 'Pocket', 'Mobile','iPhone', 'Mini','Portable', 'Smartphone', 'SDA','PDA', 'Handheld', 'Symbian','WAP', 'Palm', 'Avantgo','cHTML', 'BlackBerry', 'Opera Mini','Nokia', 'Android');
						if( isset( $_SERVER["HTTP_USER_AGENT"] ) && preg_match( '/'.strtolower( join("|", $agents) ).'/', strtolower( $_SERVER["HTTP_USER_AGENT"] ) ) !== 0 ) {
								wp_enqueue_style( 'handheld-css', $bwp_minify_themes_url_addon.'/css/handheld.css', array('style-css'), CB_STD_SYS_VERSION, 'handheld' );
						}

						// load forms.css for deafult input styles, used by the search box and result page
						if ( cbstdsys_opts( 'm_search' ) )
								wp_enqueue_style( 'forms-css', $forms_css, false, CB_STD_SYS_VERSION );


						// 404
						if (  empty($post) || is_404() ) {

				        wp_enqueue_script('jquery', $jquery, false, $jquery_ver, true);

				        wp_enqueue_script( 'std_script', $std_script_path, array('jquery'), CB_STD_SYS_VERSION,  true );
				        wp_localize_script( 'std_script', 'std_script', $std_script_i18n );
		        }


						// Gallery
						if ( conditionally_load_if_shortcode_used( '[gallery', $post ) && !empty($post) ) {

		            wp_enqueue_script( 'jquery', $jquery, false, $jquery_ver, true);

#		            wp_enqueue_script( 'fancybox', $fancybox, array('jquery'),$fancybox_ver,true);
#		            wp_enqueue_script( 'mousewheel', $mousewheel, array('fancybox'),$mousewheel_ver,true);
#		            wp_enqueue_script( 'easing', $easing, array('fancybox'),$easing_ver,true);

#		            wp_enqueue_script( 'fancybox-caller', $fancybox_c, array('jquery','fancybox','easing','mousewheel'), CB_STD_SYS_VERSION,  true );

#		            wp_enqueue_style( 'fancybox-css', $fancycss, false, $fancycss_ver );

		            add_filter( 'wp_get_attachment_link', 'add_rel_attribute_to_attachment_link', 1, 2 );
		        }

						// Comments
		        if ( class_exists('Social') ) {
								if (  is_single() || ( is_page() && !defined('CBSTDSYS_DISABLE_PAGE_COMMENTS') ) && ( find_value_in_postobjects_array($post, comment_status, 'open')  || ( find_value_in_postobjects_array($post, comment_status, 'closed')  && !find_value_in_postobjects_array($post, comment_count, '0') ) ) && !empty($post)   ) {
								#if( defined( 'CBSTDSYS_COMMENTS_USED' )  ) {
										wp_enqueue_script('jquery', $jquery, false, $jquery_ver, true);

				            wp_enqueue_script('mc_social_js', $mc_social_js, array('jquery'), Social::$version, true);
										wp_enqueue_script( 'std_script', $std_script_path, array('jquery'), CB_STD_SYS_VERSION,  true );
						        wp_localize_script( 'std_script', 'std_script', $std_script_i18n );

				        	  wp_enqueue_style( 'comment-css', $comment_css, array('style-css'), CB_STD_SYS_VERSION );

										if ( find_value_in_postobjects_array($post, comment_status, 'open') ) {
												wp_enqueue_script('jq_qtags', $jq_qtags, array('jquery','mc_social_js'), CB_STD_SYS_VERSION, true );
						       			wp_localize_script( 'jq_qtags', 'jq_qtags', $jq_qtags_i18n );
												wp_enqueue_style( 'forms-css', $forms_css, array('style-css', 'comment-css'), CB_STD_SYS_VERSION );
										}

										if ( get_option( 'thread_comments' ) )
												wp_enqueue_script( 'comment-reply' );

				            wp_enqueue_script('jq_resize_textarea', $jq_resize_textarea, array('jquery'),false, true);
				        }
		        }


						// Contact
						if ( is_singular() && conditionally_load_if_shortcode_used('[contact-form', $post ) && !empty($post)  ) {

		            wp_enqueue_script('jquery', $jquery, false, $jquery_ver, true);
								wpcf7_enqueue_scripts();

		            wp_enqueue_script('jq_resize_textarea', $jq_resize_textarea, array('jquery'),false, true);

		            wp_enqueue_style( 'forms-css', $forms_css, false, CB_STD_SYS_VERSION );
						}
						
						
						// Microdata schema.org Debug
/*
						if ( ( (defined( 'WP_LOCAL_DEV' ) && constant( 'WP_LOCAL_DEV' ) ) || constant( 'WP_DEBUG' ) ) && in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) ) {
		            wp_enqueue_script('jquery', $jquery, false, $jquery_ver, true);
		            wp_enqueue_script('schema_org_debug_lib', $schema_org_debug_lib, array('jquery'),false, true);
		            wp_enqueue_script('schema_org_debug', $schema_org_debug, array('jquery','schema_org_debug_lib'),false, true);
						}
*/
		        return $post;
		    }

				

        /** 
         *
         *  Load all scripts and styles depending on the mainly queried posts
         *  go only once through the array of $post objects         
         *  
         *  @since    0.1.8
         */                                            
        function main_query_script_and_style_loader( $query ) {
            if ( $query->is_main_query() )
        				add_filter( 'the_posts', 'cond_load_js_and_css', 100 ); // the_posts gets triggered before wp_head
            else
        				remove_filter( 'the_posts', 'cond_load_js_and_css', 100 ); // the_posts gets triggered before wp_head                
        }
        add_action( 'pre_get_posts', 'main_query_script_and_style_loader' );


    endif;  // END : 	if ( ! function_exists( 'cond_load_js_and_css' ) ) 



		/**
		 *  Conditional load fix-scripts for IE
		 *
		 *  Selectivizr - CSS3 pseudo-class and attribute selectors for IE 6-8
		 *  @link   http://selectivizr.com/
		 *
		 *  DD_belatedPNG - Medicine for your IE6/PNG headache
		 *  @link   http://www.dillerdesign.com/experiment/DD_belatedPNG/
		 *
		 *  @since  0.1.3
		 */
		if ( ! function_exists( 'add_ie_cond_js' ) ) :
				function add_ie_cond_js() {
		    		global $is_winIE, $is_macIE;
		    		if ( $is_macIE || $is_winIE ) {	

		        /**
		         *  Construct corret URIs for use with 'BWP-Minify'-PLugin
		         *
		         *  @since  0.1.2
		         */
            $bwp_minify_plugins_url_addon = $bwp_minify_themes_url_addon 	= ''; 
		        if ( class_exists('BWP_MINIFY') ) {
								$bwp_minify_plugins_url_addon = WP_CONTENT_URL;
								$bwp_minify_themes_url_addon 	= WP_CONTENT_URL.str_replace( WP_CONTENT_DIR, '', TEMPLATEPATH );
						} ?>
			<!--[if (gte IE 6)&(lte IE 9)]>
				<script src="<?php echo $bwp_minify_themes_url_addon; ?>/js/libs/selectivizr.1.0.2.min.js"></script>
			<![endif]-->
			<!--[if lt IE 7 ]>
				<script src="<?php echo $bwp_minify_themes_url_addon; ?>/js/libs/dd_belatedpng.js"></script>
			<![endif]-->
				<?php } }
				add_action('wp_footer', 'add_ie_cond_js', 900);
		endif;



		/**
		 *  Conditional load fix-script for IE
		 *
		 *  html5shiv - 
		 *  @link   
		 *
		 *  @since  0.2.1
		 */
		if ( ! function_exists( 'add_ie_cond_js_before_html' ) ) :
				function add_ie_cond_js_before_html() {
		    		global $is_winIE, $is_macIE;
		    		if ( $is_macIE || $is_winIE ) {	

		        /**
		         *  Construct corret URIs for use with 'BWP-Minify'-PLugin
		         *
		         *  @since  0.1.2
		         */
            $bwp_minify_plugins_url_addon = $bwp_minify_themes_url_addon 	= ''; 
		        if ( class_exists('BWP_MINIFY') ) {
								$bwp_minify_plugins_url_addon = WP_CONTENT_URL;
								$bwp_minify_themes_url_addon 	= WP_CONTENT_URL.str_replace( WP_CONTENT_DIR, '', TEMPLATEPATH );
						} ?>
      <!--[if lt IE 9]>
        <script src="<?php echo $bwp_minify_themes_url_addon; ?>/js/libs/html5shiv.js"></script>
      <![endif]-->
				<?php } }
				add_action('wp_head', 'add_ie_cond_js_before_html', 900);
		endif;
    
    
    
    /**
     *  Next & Previous Navigation for Fancybox
     *	manipulate the [gallery]-shortcode and integrate rel-Attributes
     *
     *  @source   http://wordpress.mfields.org/2010/thickbox-for-wordpress-gallery/
     *  @since    0.1.2
     */
    function add_rel_attribute_to_attachment_link( $anchor_tag, $image_id ) {
        $image = get_post( $image_id );
        $rel = '';
        if( isset( $image->post_parent ) ) {
            $rel = ' rel="attached-to-' . (int) $image->post_parent . '"';
        }
        if( !empty( $rel ) ) {
            $anchor_tag = str_replace( '<a', '<a class="fb-gal" ' . $rel, $anchor_tag );
        }
        return $anchor_tag;
    }



    /**
     *  serve humans.txt
     *           
     *  @since    0.0.8
     *  
     */               
		if ( ! function_exists( 'serve_humanstxt' ) ) :
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
		endif;
    if( $_SERVER['REQUEST_URI'] == '/humans.txt') {
        add_action('init','serve_humanstxt');
    }



		/**
		 *  Add custom background-colors through 'Colorpicker'-Plugin
		 *
		 *  @since  0.1.3
		 */
		if ( ! function_exists( 'custom_background_color_via_colorpickerplugin' ) ) :
				function custom_background_color_via_colorpickerplugin() {
						global $post;
						if ( get_post_meta($post->ID,'colorpicker',true) ) {
							  echo "<style type='text/css'><!--";
							  echo "html  { background:".get_post_meta($post->ID,'colorpicker',true).";}";
							  echo "::selection{ background: #000; color:".get_post_meta($post->ID,'colorpicker',true).";}::-moz-selection{ background: #000; color: ".get_post_meta($post->ID,'colorpicker',true)."; }";
							  echo "//--></style>\n";
						}
				}
				if ( cbstdsys_opts('d_bg_colors') )
				add_action( 'wp_head', 'custom_background_color_via_colorpickerplugin' );
		endif;


		/**
		 *  Change Image-Size of Avatar-Img in Author-Bios
		 *
		 *  @since  0.1.4
		 */
		if ( ! function_exists( 'author_bio_avatar_size' ) ) :
				function author_bio_avatar_size( $size ) {
						return $size;
				}
				add_filter('cbstdsys_author_bio_avatar_size', 'author_bio_avatar_size');
		endif;


		/**
		 *	Prevents WordPress from testing ssl capability on domain.com/xmlrpc.php?rsd
		 *
		 *  @since  0.1.4
		 *  @source http://wordpress.stackexchange.com/questions/1567/best-collection-of-code-for-your-functions-php-file/1769#1769
		 */
		remove_filter('atom_service_url','atom_service_url_filter');



		/**
		 * Sets the post excerpt length
		 * by counting words
		 *
		 * @since  0.1.4
		 * @source Twenty Ten 1.0
		 * @return int
		 */
		if ( ! function_exists( 'cbstdsys_excerpt_length' ) ) :
				function cbstdsys_excerpt_length( $length ) {
					if ( !is_singular() ) {
							return 40;
					} else {
							return $length;
					}
				}
				add_filter( 'excerpt_length', 'cbstdsys_excerpt_length' );
		endif;


		/**
		 * Returns a "Continue Reading" link for excerpts
		 *
		 * @since   0.1.4
		 * @source	Twenty Ten 1.0
		 * @return string "Continue Reading" link
		 */
		if ( ! function_exists( 'cbstdsys_continue_reading_text' ) ) :
				function cbstdsys_continue_reading_text() {
					return sprintf( __( 'Continue reading \'%s\'', 'cb-std-sys' ), get_the_title() ). '<span class="meta-nav">&rarr;</span>';
				}
		endif;
		
		
		/**
		 * Returns a "Continue Reading" link for excerpts
		 *
		 * @since   0.1.4
		 * @source	Twenty Ten 1.0
		 * @return string "Continue Reading" link
		 */
		if ( ! function_exists( 'cbstdsys_continue_reading_link' ) ) :
				function cbstdsys_continue_reading_link() {
					return ' <a class="readmore" href="'. get_permalink() . '" title="' .  sprintf( esc_attr__( 'Continue reading \'%s\'', 'cb-std-sys' ), the_title_attribute( 'echo=0' ) ) . '">' . cbstdsys_continue_reading_text() . '</a>';
				}
		endif;


		/**
		 * Replaces "[...]" (appended to automatically generated excerpts)
		 * with an ellipsis and cbstdsys_continue_reading_link().
		 *
		 * @since   0.1.4
		 * @source	Twenty Ten 1.0
		 * @return string An ellipsis
		 */
		if ( ! function_exists( 'cbstdsys_auto_excerpt_more' ) ) :
				function cbstdsys_auto_excerpt_more( $more ) {
					if ( !is_singular() ) {
					return ' &hellip; ' . cbstdsys_continue_reading_link();
					}
				}
				add_filter( 'excerpt_more', 'cbstdsys_auto_excerpt_more' );
		endif;


		/**
		 * Adds a pretty "Continue Reading" link to custom post excerpts.
		 *
		 * @since   0.1.4
		 * @source	Twenty Ten 1.0
		 * @return string Excerpt with a pretty "Continue Reading" link
		 */
		if ( ! function_exists( 'cbstdsys_custom_excerpt_more' ) ) :
				function cbstdsys_custom_excerpt_more( $output ) {
					if ( ( !has_excerpt() && ! is_attachment() ) || !is_singular() ) {
						$output .= cbstdsys_continue_reading_link();
					}
					return $output;
				}
				add_filter( 'the_excerpt', 'cbstdsys_custom_excerpt_more' );
		endif;



		/**
		 * Returns HTML with meta information for the current post-date/time and author.
		 *
		 * @since   0.1.4
		 * @siurce	Twenty Ten 1.0
		 */
		if ( ! function_exists( 'cbstdsys_posted_on' ) ) :
				function cbstdsys_posted_on() {
		        if ( cbstdsys_opts('m_multiauthor') ) {
								printf( __( '<span class="%1$s">Posted on</span> %2$s %3$s', 'cb-std-sys' ),
									'meta-prep meta-prep-author',
									sprintf( '<a href="%1$s" title="%2$s" rel="bookmark" itemprop="url"><time datetime="'. get_the_time('c') .'" pubdate class="updated entry-date published" itemprop="datePublished" content="%3$s" title="%3$s">%4$s</time></a>',
										get_permalink(),
										esc_attr( get_the_time() ),
										get_the_date('Y-m-d'),
										get_the_date()
									),
									sprintf( '<span class="meta-sep">%4$s</span> <address class="author vcard" itemprop="author" itemscope itemtype="http://schema.org/Person"><a class="url fn n" href="%1$s" title="%2$s" itemprop="name url" rel="author">%3$s</a></address>',
										get_author_posts_url( get_the_author_meta( 'ID' ) ),
										sprintf( esc_attr__( 'View all posts by %s', 'cb-std-sys' ), get_the_author() ),
										get_the_author(),
										__( 'by', 'cb-std-sys' )
									)
								);
						} else {
								printf( __( '<span class="%1$s">Posted on</span> %2$s', 'cb-std-sys' ),
									'meta-prep meta-prep-author',
									sprintf( '<a href="%1$s" title="%2$s" rel="bookmark" itemprop="url"><time datetime="'. get_the_time('c') .'" pubdate class="updated entry-date published" itemprop="datePublished" content="%3$s" title="%3$s">%4$s</time></a>',
										get_permalink(),
										esc_attr( get_the_time() ),
										get_the_date('Y-m-d'),
										get_the_date()
									)
								);
						}
				}
		endif;



		/**
		 * Prints HTML with meta information for the current post (category, tags and permalink).
		 *
		 * @since   0.1.4
		 * @source	Twenty Ten 1.0
		 */
		if ( ! function_exists( 'cbstdsys_posted_in' ) ) :
				function cbstdsys_posted_in() {
						$tag_list = get_the_tag_list( '', ', ' );
						// we don't need the permalink on archives or search pages
            if ( !is_archive() && !is_search() ) {
								$bookmark = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark" itemprop="url">permalink</a>.', 'cb-std-sys' );
						}

						if ( $tag_list && cbstdsys_opts('m_tags') ) {
								$posted_in = __( 'This entry was posted in %1$s and tagged %2$s.', 'cb-std-sys' ).' '.$bookmark;
						} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) && cbstdsys_opts('m_blog') ) {
								$posted_in = __( 'This entry was posted in %1$s.', 'cb-std-sys' ).' '.$bookmark;
						} else {
								$posted_in = $bookmark;
						}
						// Prints the string, replacing the placeholders.
						printf(
							'<p class="posted-in">'.$posted_in.'</p>',
							get_the_category_list( ', ' ),
							$tag_list,
							get_permalink(),
							the_title_attribute( 'echo=0' )
						);
				}
		endif;



} // else is_admin()



/**
 *  Postthumbnail Definitions
 *
 *  - define post_thumbnail size
 *  - define if standard sizes should be cropped
 *  - get custom_thumbnail-sizes and update their options
 *
 *  @since  0.1.3
 *  @source http://wordpress.org/support/topic/hack-crop-custom-thumbnail-sizes?replies=17#post-2041676
 */
if ( ! function_exists( 'thumbnail_setup' ) ) :
		function thumbnail_setup () {
				// define post_thumbnail size
				set_post_thumbnail_size( '100', '100', true );

				// define all post_types, which use thumbnails
				add_theme_support('post-thumbnails' );

				// define if standard sizes should be cropped
				// http://wordpress.org/support/topic/force-crop-to-medium-size
			  if(false === get_option("medium_crop")) add_option("medium_crop", "1");
			  else                                    update_option("medium_crop", "1");

				// get custom_thumbnail-sizes ...
				$custom_thumbnail_sizes =  custom_thumbnail_setup();

				// ... and update their options
				// For each new image size, run add_image_size() and update_option() to add the necessary info.
				// update_option() is good because it only updates the database if the value has changed. It also adds the option if it doesn't exist
				foreach ( $custom_thumbnail_sizes as $custom_thumbnail_size ){
						add_image_size( $custom_thumbnail_size['name'], $custom_thumbnail_size['width'], $custom_thumbnail_size['height'], $custom_thumbnail_size['crop'] );
						update_option( $custom_thumbnail_size['name']."_size_w", $custom_thumbnail_size['width'] );
						update_option( $custom_thumbnail_size['name']."_size_h", $custom_thumbnail_size['height'] );
						update_option( $custom_thumbnail_size['name']."_crop", $custom_thumbnail_size['crop'] );
				}
		}
		add_action( 'after_setup_theme', 'thumbnail_setup' );
endif;


/**
 *  Returns additional custom thumbnail sizes
 *
 *  @since  0.1.3
 */
if ( ! function_exists( 'custom_thumbnail_setup' ) ) :
		function custom_thumbnail_setup () {
				// Array of custom image sizes to add
				$custom_thumbnail_sizes = array(
						#array( 'name'=>'slideshow-full', 'width'=>544, 'height'=>380, 'crop'=>true ),
						#array( 'name'=>'onenew-thumb', 'width'=>167, 'height'=>67, 'crop'=>true ),
				);

				return $custom_thumbnail_sizes;
		}
endif;


/**
 *  Save edit of Image to custom thumbnail sizes
 *
 *  Hook into the 'intermediate_image_sizes' filter used by image-edit.php.
 *  This adds the custom_thumbnail_sizes into the array of sizes it uses when editing/saving images.
 *
 *  @since  0.1.3
 *  @source http://wordpress.org/support/topic/hack-crop-custom-thumbnail-sizes?replies=17#post-2041676
 */
function also_edit_custom_thumb_sizes( $sizes ){
		$custom_thumbnail_sizes =  custom_thumbnail_setup();
		foreach ( $custom_thumbnail_sizes as $custom_thumbnail_size ){
				$sizes[] = $custom_thumbnail_size['name'];
		}
		return $sizes;
}
add_filter( 'intermediate_image_sizes', 'also_edit_custom_thumb_sizes' );




?>