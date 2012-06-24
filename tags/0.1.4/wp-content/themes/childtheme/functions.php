<?php


if ( is_admin() ) {



} else {


		function add_styles() {


				$child_style  = get_stylesheet_directory_uri().'/style.css';
				wp_enqueue_style('child-styles', $child_style, array('style-css'), CB_STD_SYS_VERSION );

		}
  	add_action('wp_print_styles', 'add_styles');

}



?>