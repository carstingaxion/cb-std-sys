<?php
/*******************************************************************************
 *
 *  Globals
 *
 *******************************************************************************/
include_once 'inc/constants.php';
include_once 'inc/base.php';
include_once 'inc/cbstdsys_options.php';
#include_once 'inc/options.php';
include_once 'inc/search.php';
include_once 'inc/conditional_tags.php';
include_once 'inc/dropins.extensions.php';
include_once 'inc/plugins.extensions.php';
/*******************************************************************************
 *
 *  Dashboard and Admin-Add-Ons
 *
 *******************************************************************************/
if ( is_admin() ) {    
    include_once 'inc/backend.php';
#		include_once 'inc/update.php';
		include_once 'inc/changelog.php';
/*******************************************************************************
 *
 *  Theme and Frontend Add-Ons
 *  
 *******************************************************************************/
} else {
    include_once 'inc/frontend.php';
#    include_once 'inc/hooks.php';
}
/*******************************************************************************
 *
 *  customized user web functions
 *
 *******************************************************************************/
include_once 'functions_userweb.php';
?>