<?php
/*
Plugin Name: FirePHP Debbuger
Plugin URI:
Description: usage:  new firePHPdebug( 'hallo debug', 'e' );
Version: 0.0.1
Author: Carsten Bach
Author URI: http://carsten-bach.de

License:

Copyright (c) 2011 Carsten bach

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

if (!class_exists('firePHPdebug')) {
    class firePHPdebug {

    public function __construct( $var, $arg = '' ) {
				#$current_user = wp_get_current_user();
				#if ( in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) || defined( 'WP_LOCAL_DEV' ) ) {
        if ( defined( 'WP_LOCAL_DEV' ) ) {
						include_once( getenv("DOCUMENT_ROOT").'/FirePHPCore/fb.php' );
						switch ($arg) {
								case 'trace':
										fb( $var, FirePHP::TRACE );
										break;
								case 'e':
										fb( $var, FirePHP::ERROR );
										break;
								case 'w':
										fb( $var, FirePHP::WARN );
										break;
								case 'i':
										fb( $var, FirePHP::INFO );
										break;
								default:
										fb( $var, FirePHP::LOG );
										break;
						}
			 }
    }
	}
}
?>