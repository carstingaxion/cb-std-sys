<?php
/*
Plugin Name: OpenSearch
Plugin URI: http://wordpress.org/extend/plugins/opensearch/
Description: ## CB schwer angepasst ## Add OpenSearch discovery and querying to your WordPress site.
Version: 1.0
Author: Jeff Waugh
Author URI: http://bethesignal.org/
*/

/*
Copyright (C) Jeff Waugh <http://bethesignal.org/>

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
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function opensearch_head() {
	if ( get_option('permalink_structure') == '' ) {
		$osd_url = '?opensearch_osd=1';
	} else {
		$osd_url = 'osd.xml';
	}
?>
  <link rel="search" type="application/opensearchdescription+xml" href="/<?php echo $osd_url; ?>" title="<?php bloginfo('name'); ?>">
<?php }

function opensearch_query_vars($vars) {
	$vars[] = 'opensearch_osd';
	return $vars;
}

function opensearch_flush_rewrite_rules() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

function opensearch_rewrite_rules($wp_rewrite) {
	global $wp_rewrite;
	$rules = array(
		'osd.xml$' => $wp_rewrite->index . '?opensearch_osd=1'
	);
	$wp_rewrite->rules = $rules + $wp_rewrite->rules;
}

function opensearch_canonical($redirect_url, $requested_url) {
	if ( substr($requested_url, -7) == 'osd.xml' )
		return false;
	return $redirect_url;
}

function opensearch_osd() {
	global $wp_query;
	if ( ! empty($wp_query->query_vars['opensearch_osd']) ) {
		header('Content-Type: text/xml');
		echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
  <ShortName><?php bloginfo('name'); ?></ShortName>
  <LongName><?php bloginfo('description'); ?></LongName>
  <Description>Search &#x201C;<?php bloginfo('name'); ?>&#x201D;</Description>
  <Contact><?php echo get_option('cb_std_sys_moderation_email'); ?></Contact>
  <Url type="text/html" template="<?php bloginfo('url'); ?>/search/{searchTerms}"/>
  <Url type="application/atom+xml" template="/?feed=atom&amp;s={searchTerms}"/>
  <Url type="application/rss+xml" template="/?feed=rss2&amp;s={searchTerms}"/>
  <Image height="16" width="16" type="image/x-icon">/favicon.ico</Image>
<?php if ( defined (ICL_LANGUAGE_CODE ) ) { ?>
  <Language><?php echo ICL_LANGUAGE_CODE; ?></Language>
<?php } else { ?>  
  <Language><?php bloginfo('language'); ?></Language>
<?php } ?>
	<Query role="example" searchTerms="blog"/>
	<Developer>Carsten Bach</Developer>
	<Attribution>
	    Search data Copyright <?php echo cb_std_sys_copyright(); ?>, <?php bloginfo( 'name' ); ?>, All Rights Reserved
	</Attribution>

	<SyndicationRight>open</SyndicationRight>
	<AdultContent>false</AdultContent>
  <OutputEncoding>utf-8</OutputEncoding>
  <InputEncoding>utf-8</InputEncoding>
</OpenSearchDescription><?php
		exit;
	}
	return;
}

add_filter('init', 'opensearch_flush_rewrite_rules');
add_filter('generate_rewrite_rules', 'opensearch_rewrite_rules');
add_filter('query_vars', 'opensearch_query_vars');
add_filter('redirect_canonical', 'opensearch_canonical', 10, 2);

add_action('wp_head', 'opensearch_head');
add_action('template_redirect', 'opensearch_osd');
