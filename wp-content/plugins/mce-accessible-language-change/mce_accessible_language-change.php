<?php
/**
 Plugin Name: MCE Accessible Language Change
 Plugin URI: http://sprungmarker.de/thema/plugins/
 Description: Add <strong>accessible language change</strong> to the WordPress WYSIWYG editor.
 Version: 1.0
 Author: Sylvia Egger (developer@sprungmarker.de)
 Author URI: http://sprungmarker.de
 License: GNU General Public License v2.0
 License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

    Plugin: Copyright 2010 Sylvia Egger  (email : info@sprungmarker.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! function_exists('plugin_mce_css') ) {
	function plugin_mce_css($wp) {
		//$tadv_options = get_option('tadv_options', array());

		if ( $tadv_options['importcss'] == '1' )
			$wp .= ',' . get_bloginfo('stylesheet_url');

		$wp .= ',' . WP_PLUGIN_URL . '/mce-accessible-language-change/css/changelang.css';
		return trim($wp, ' ,');
	}
}
add_filter( 'mce_css', 'plugin_mce_css' );

if ( ! function_exists('mce_accessible_langs') ) {
	function mce_accessible_langs($langpath) {
		$langpath = WP_PLUGIN_DIR . '/mce-accessible-language-change/language/langs/langs.php';
	}
}
add_filter( 'mce_external_languages', 'mce_accessible_langs' );

if ( ! function_exists('mce_accessible_lang_setup') ) {
	function mce_accessible_lang_setup() {
		//only if editing permissions do we bother
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) return;
	
		if ( get_user_option('rich_editing') == 'true') {
			add_filter("mce_external_plugins", "add_accessible_lang_tinymce_plugin");
			add_filter('mce_buttons', 'register_accessible_lang_button');
		}
	}
}

if ( ! function_exists('register_accessible_lang_button') ) {
	function register_accessible_lang_button($buttons) {
	   array_push($buttons, "separator", "clang,langattribs");
	   return $buttons;
	}
}

if ( ! function_exists('add_accessible_lang_tinymce_plugin') ) {
	function add_accessible_lang_tinymce_plugin($plugin_array) {
	   $plugin_array['changelang'] = WP_PLUGIN_URL.'/mce-accessible-language-change/language/editor_plugin.js';
	   return $plugin_array;
	}
}

add_action("admin_init","mce_accessible_lang_setup");

?>