<?php

// header.php
function cbstdsys_head() { do_action('cbstdsys_head'); }
function cbstdsys_stylesheets() { do_action('cbstdsys_stylesheets'); }
function cbstdsys_wrap_before() { do_action('cbstdsys_wrap_before'); }
function cbstdsys_header_before() { do_action('cbstdsys_header_before'); }
function cbstdsys_header_inside() { do_action('cbstdsys_header_inside'); }
function cbstdsys_header_after() { do_action('cbstdsys_header_after'); }

// 404.php, archive.php, front-page.php, index.php, loop-page.php, loop-single.php,
// loop.php, page-custom.php, page-full.php, page.php, search.php, single.php
function cbstdsys_content_before() { do_action('cbstdsys_content_before'); }
function cbstdsys_content_after() { do_action('cbstdsys_content_after'); }
function cbstdsys_main_before() { do_action('cbstdsys_main_before'); }
function cbstdsys_main_after() { do_action('cbstdsys_main_after'); }
function cbstdsys_post_before() { do_action('cbstdsys_post_before'); }
function cbstdsys_post_after() { do_action('cbstdsys_post_after'); }
function cbstdsys_post_inside_before() { do_action('cbstdsys_post_inside_before'); }
function cbstdsys_post_inside_after() { do_action('cbstdsys_post_inside_after'); }
function cbstdsys_loop_before() { do_action('cbstdsys_loop_before'); }
function cbstdsys_loop_after() { do_action('cbstdsys_loop_after'); }
function cbstdsys_sidebar_before() { do_action('cbstdsys_sidebar_before'); }
function cbstdsys_sidebar_inside_before() { do_action('cbstdsys_sidebar_inside_before'); }
function cbstdsys_sidebar_inside_after() { do_action('cbstdsys_sidebar_inside_after'); }
function cbstdsys_sidebar_after() { do_action('cbstdsys_sidebar_after'); }

// footer.php
function cbstdsys_footer_before() { do_action('cbstdsys_footer_before'); }
function cbstdsys_footer_inside() { do_action('cbstdsys_footer_inside'); }
function cbstdsys_footer_after() { do_action('cbstdsys_footer_after'); }
function cbstdsys_footer() { do_action('cbstdsys_footer'); }

?>