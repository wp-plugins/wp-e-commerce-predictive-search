<?php
/*
Plugin Name: WP e-Commerce Predictive Search LITE
Description: Super charge you site with WP e-Commerce Predictive Products Search. Delivers stunning results as you type. Searches your entire WP e-Commerce product database
Version: 1.1.2
Author: A3 Revolution
Author URI: http://www.a3rev.com/
Requires at least: 3.3
Tested up to: 3.3
License: GPLv2 or later

	WP e-Commerce Predictive Search LITE. Plugin for the WP e-Commerce plugin.
	Copyright © 2011 A3 Revolution Software Development team

	A3 Revolution Software Development team
	admin@a3rev.com
	PO Box 1170
	Gympie 4570
	QLD Australia
*/
?>
<?php
define( 'WPSC_PS_FILE_PATH', dirname(__FILE__) );
define( 'WPSC_PS_DIR_NAME', basename(WPSC_PS_FILE_PATH) );
define( 'WPSC_PS_FOLDER', dirname(plugin_basename(__FILE__)) );
define( 'WPSC_PS_NAME', plugin_basename(__FILE__) );
define( 'WPSC_PS_URL', WP_CONTENT_URL.'/plugins/'.WPSC_PS_FOLDER );
define( 'WPSC_PS_JS_URL',  WPSC_PS_URL . '/assets/js' );
define( 'WPSC_PS_CSS_URL',  WPSC_PS_URL . '/assets/css' );
define( 'WPSC_PS_IMAGES_URL',  WPSC_PS_URL . '/assets/images' );
if(!defined("WPSC_PS_AUTHOR_URI"))
    define("WPSC_PS_AUTHOR_URI", "http://a3rev.com/products-page/wp-e-commerce/wpec-predictive-search-pro/");

include ('classes/class-wpsc-predictive-search-filter.php');
include ('classes/class-wpsc-predictive-search.php');
include ('classes/class-wpsc-predictive-search-shortcodes.php');
include ('widget/wpsc-predictive-search-widgets.php');
include ('admin/wpsc-predictive-search-init.php');
include ('admin/classes/class-wpsc-predictive-search-admin.php');

/**
* Call when the plugin is activated
*/
register_activation_hook(__FILE__,'wpsc_predictive_install');
?>