<?php
    function cb_std_sys_contextual_help($contextual_help, $screen_id, $screen) {
    
      	if ( $screen_id == 'post' || $screen_id == 'page' ) {
      	
          	$contextual_help ='';
          	
        		$contextual_help .= "<h4>".__('Usage of [shortcodes] for your website','cb-std-sys')." \"".get_bloginfo('title')."\"</h4>";
        		$contextual_help .= "<p>".__('To make use of special features you can add one of the following shortcodes to this post or page.','cb-std-sys')."</p>";
            $contextual_help .= "<ul>";
            
            
        		$contextual_help .= "<li class='videotut'>";
        		$contextual_help .= "<a title='".__('1st Default Video Tutorial','cb-std-sys')."' 
                                    href='http://www.youtube.com/embed/Nz6xK-3nppg?s=1&amp;autoplay=1&amp;KeepThis=true&amp;TB_iframe=true&amp;height=349&amp;width=560' 
                                    class='thickbox'>";
        		$contextual_help .= "<img src='http://i3.ytimg.com/vi/Nz6xK-3nppg/default.jpg' alt='Vorschaubild'>";               
        		$contextual_help .= "</a>"; 
        		$contextual_help .= "<h5>";
        		$contextual_help .= "<a title='".__('Default Video Tutorial','cb-std-sys')."' 
                                    href='http://www.youtube.com/embed/Nz6xK-3nppg?KeepThis=true&amp;TB_iframe=true&amp;height=349&amp;width=560' 
                                    class='thickbox'>";        		
            $contextual_help .= __('Default Video Tutorial','cb-std-sys');
        		$contextual_help .= "</a>";            
            $contextual_help .= "</h5>";
            $contextual_help .= "<p>Lorem ipsum dolor sit amet consectetuer nec at feugiat nunc neque. Semper tellus Curabitur ipsum id risus. Semper tellus Curabitur ipsum id risus.</p>";
            $contextual_help .= "<p>Lorem ipsum dolor sit amet consectetuer nec at feugiat nunc neque. Semper tellus Curabitur ipsum id risus.</p>";
            $contextual_help .= "</li>"; 
            
        		$contextual_help .= "<li class='videotut'>";
        		$contextual_help .= "<a title='".__('Default Video Tutorial','cb-std-sys')."' 
                                    href='http://www.youtube.com/embed/Nz6xK-3nppg?KeepThis=true&amp;TB_iframe=true&amp;height=349&amp;width=560' 
                                    class='thickbox'>";
        		$contextual_help .= "<img src='http://i3.ytimg.com/vi/Nz6xK-3nppg/default.jpg' alt='Vorschaubild'>";               
        		$contextual_help .= "</a>"; 
        		$contextual_help .= "<h5>";
        		$contextual_help .= "<a title='".__('Default Video Tutorial','cb-std-sys')."' 
                                    href='http://www.youtube.com/embed/Nz6xK-3nppg?KeepThis=true&amp;TB_iframe=true&amp;height=349&amp;width=560' 
                                    class='thickbox'>";        		
            $contextual_help .= __('Default Video Tutorial','cb-std-sys');
        		$contextual_help .= "</a>";            
            $contextual_help .= "</h5>";
            $contextual_help .= "<p>Lorem ipsum dolor sit amet consectetuer nec at feugiat nunc neque. Semper tellus Curabitur ipsum id risus. Semper tellus Curabitur ipsum id risus.</p>";
            $contextual_help .= "<p>Lorem ipsum dolor sit amet consectetuer nec at feugiat nunc neque. Semper tellus Curabitur ipsum id risus.</p>";
            $contextual_help .= "</li>"; 
            
        		$contextual_help .= "<li class='videotut'>";
        		$contextual_help .= "<a title='".__('Default Video Tutorial','cb-std-sys')."' 
                                    href='http://www.youtube.com/embed/Nz6xK-3nppg?KeepThis=true&amp;TB_iframe=true&amp;height=349&amp;width=560' 
                                    class='thickbox'>";
        		$contextual_help .= "<img src='http://i3.ytimg.com/vi/Nz6xK-3nppg/default.jpg' alt='Vorschaubild'>";               
        		$contextual_help .= "</a>"; 
        		$contextual_help .= "<h5>";
        		$contextual_help .= "<a title='".__('Default Video Tutorial','cb-std-sys')."' 
                                    href='http://www.youtube.com/embed/Nz6xK-3nppg?KeepThis=true&amp;TB_iframe=true&amp;height=349&amp;width=560' 
                                    class='thickbox'>";        		
            $contextual_help .= __('Default Video Tutorial','cb-std-sys');
        		$contextual_help .= "</a>";            
            $contextual_help .= "</h5>";
            $contextual_help .= "<p>Lorem ipsum dolor sit amet consectetuer nec at feugiat nunc neque. Semper tellus Curabitur ipsum id risus. Semper tellus Curabitur ipsum id risus.</p>";
            $contextual_help .= "<p>Lorem ipsum dolor sit amet consectetuer nec at feugiat nunc neque. Semper tellus Curabitur ipsum id risus.</p>";
            $contextual_help .= "</li>";                   
            
            
            // user vcard
            if (cbstdsys_is_plugin_active('user-vcard/user-vcard.php') ) {
        		$contextual_help .= "<li><p>".sprintf( __('To show a <strong>personal businesscard</strong> of one of your <a href="%s">Users</a>, just use','cb-std-sys'), admin_url('users.php') )." <strong><code>[user_vcard name='".__('Username')."'] </code></strong></p></li>";
            }
    
            // cloudmade maps
            if (cbstdsys_is_plugin_active('cloudmade-map/cloudmade-map.php') ) {
              $CMM_pp = get_option( 'CMM_general_opts');
              if ( $CMM_pp['CMM_pp_static'] ) {
              		$contextual_help .= "<li><p>".__('For a simple <strong>static map</strong> inside a post or page use','CloudMadeMap')." <strong><code>[CMM-Static-Map]</code></strong> " . __('The generated map will use the default values from this page.','CloudMadeMap')." ";
              		$contextual_help .= __('To show a personalized static map use one or more of the following attributes:','CloudMadeMap')."</p>";
                  $contextual_help .= "<dl>";
                  $contextual_help .= "<dt><code>width=''</code></dt><dd>". __('Map width','CloudMadeMap'). "</dd>";
                  $contextual_help .= "<dt><code>height=''</code></dt><dd>". __('Map height','CloudMadeMap'). "</dd>";        
                  $contextual_help .= "<dt><code>zoom=''</code></dt><dd>". __('Zoom level','CloudMadeMap'). "</dd>";            
                  $contextual_help .= "<dt><code>background='true'</code></dt><dd>". __('to show the map as a background-image','CloudMadeMap'). "</dd>";
                  $contextual_help .= "</dl>";          
              		$contextual_help .= "<p>".__('Width and Height are awaiting integers (in px) only, do not add any units.','CloudMadeMap')."</p>";
              		$contextual_help .= "<p>".__('Try adding two shortcodes, one for a small preview and one for the background and give them different zoomlevels ;)','CloudMadeMap')."</p></li>";
          		}
          		if ( $CMM_pp['CMM_pp_active'] ) {
          		
          		}
    /*
        		$contextual_help .= "<p>".__('To show a personalized static map use one or more of the following attributes:','cb-std-sys')."</p>";
            $contextual_help .= "<dl>";
            $contextual_help .= "<dt><code>width=''</code></dt><dd>". __('Map width','cb-std-sys'). "</dd>";
            $contextual_help .= "<dt><code>height=''</code></dt><dd>". __('Map height','cb-std-sys'). "</dd>";        
            $contextual_help .= "<dt><code>zoom=''</code></dt><dd>". __('Zoom level','cb-std-sys'). "</dd>";            
            $contextual_help .= "<dt><code>background='true'</code></dt><dd>". __('to show the map as a background-image','cb-std-sys'). "</dd>";
            $contextual_help .= "</dl>";     
    */             
           
           
           
            }
            
            
            
            $contextual_help .= "</ul>";
  
        }     
    
        return $contextual_help;
          	  	
    }

    /***  add contextual help for (only) used plugins */
    add_filter('contextual_help', 'cb_std_sys_contextual_help', 10, 3);
?>