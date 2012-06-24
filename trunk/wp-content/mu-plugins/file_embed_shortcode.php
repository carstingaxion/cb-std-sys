<?php
/*
Plugin Name: Document Embedding
Plugin URI: 
Description: 
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

// allow MIME types .PAGES, .TTF, .XPS


if (!class_exists('gDocFileEmbedder')) {
    class gDocFileEmbedder {
	
  	// allowed MIME types by GoogleDocs Viewer 
		var $newMimeTypes = array(
			"doc|docx"       => "application/msword",
			"xls|xlsx"       => "application/vnd.ms-excel",
			"ppt|pptx"       => "application/vnd.ms-powerpoint",			
			"pdf"            => "application/pdf",    
			"tif|tiff"       => "image/tiff",   			
			"ps|eps|ai"      => "application/postscript",			
			"svg"            => "image/svg+xml",			
			"dxf"            => "application/dxf",			
			"psd"            => "image/psd",			
		);
		
    function gDocFileEmbedder() {

        add_filter('upload_mimes', array( &$this, 'custom_upload_mimes' ) );
        add_filter("attachment_fields_to_edit", array( &$this, "insertButton"), 10, 2);
        add_filter("media_send_to_editor", array( &$this, "sendToEditor"));
        add_shortcode("gDocFileEmbedder", array( &$this, "shortcode") );    
    
    }
    
    function custom_upload_mimes ( $existing_mimes=array() ) {
      	foreach ( $this->newMimeTypes as $k => $v ) {
      	 $existing_mimes[$k] = $v;
      	}
      	return $existing_mimes;
    }

    function shortcode($atts, $content = null) {
      	extract(shortcode_atts(array(
      		"href" => ''
      	), $atts));
      	return '<iframe src="http://docs.google.com/viewer?url='.$href.'&amp;embedded=true"></iframe>';    	
    }


		
		/**
		 * Inserts "File Embedding" button into media library popup
		 * @return the amended form_fields structure
		 * @param $form_fields Object
		 * @param $post Object
		 */
		function insertButton($form_fields, $post) {
			global $wp_version;
			
			$file = wp_get_attachment_url($post->ID);
			
			// Only add the extra button if the attachment is an allowed mime type
			if ( in_array( $post->post_mime_type, $this->newMimeTypes ) ) {
				$form_fields["url"]["html"] .= "<button type='button' class='button urlfileembed file-embed-" . $post->ID . "' title='[gDocFileEmbedder href=" . urlencode( attribute_escape($file) )  . "]'>".__('embed file directly','gDocFileEmbedder')."</button>";
			}
			
			return $form_fields;
		}
		
		/**
		 * Format the html inserted when the "Filet Embed" ;) button is used
		 * @param $html String
		 * @return String 
		 */
		function sendToEditor($html) {
			if (preg_match("/<a ([^=]+=['\"][^\"']+['\"] )*href=['\"](\[gDocFileEmbedder href=([^\"']+\.\D{3,4})])['\"]( [^=]+=['\"][^\"']+['\"])*>([^<]*)<\/a>/i", $html, $matches)) {
				$html = $matches[2];
			}
			return $html;;
		}
    
	}
}

// Instantiate the class
if (class_exists('gDocFileEmbedder')) {
	global $gDocFileEmbedder;
	if (!isset($gDocFileEmbedder)) {
		if (version_compare(PHP_VERSION, '5.0.0', '<')) {
			$gDocFileEmbedder = &new gDocFileEmbedder();
		} else {
			$gDocFileEmbedder = new gDocFileEmbedder();
		}
	}
}    
?>