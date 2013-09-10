<?php
/*
Plugin Name: WP e-Commerce Predictive Search LITE
Plugin URI: http://a3rev.com/shop/wp-e-commerce-predictive-search-pro/
Description: Super charge you site with WP e-Commerce Predictive Products Search. Delivers stunning results as you type. Searches your entire WP e-Commerce product database
Version: 2.0.7
Author: A3 Revolution
Author URI: http://www.a3rev.com/
Requires at least: 3.3
Tested up to: 3.6
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
define( 'WPSC_PS_URL', str_replace( 'http://', '//', str_replace( 'https://', '//', WP_CONTENT_URL ) ).'/plugins/'.WPSC_PS_FOLDER );
define( 'WPSC_PS_JS_URL',  WPSC_PS_URL . '/assets/js' );
define( 'WPSC_PS_CSS_URL',  WPSC_PS_URL . '/assets/css' );
define( 'WPSC_PS_IMAGES_URL',  WPSC_PS_URL . '/assets/images' );
if(!defined("WPSC_PS_AUTHOR_URI"))
    define("WPSC_PS_AUTHOR_URI", "http://a3rev.com/shop/wp-e-commerce-predictive-search-pro/");
if(!defined("WPSC_PREDICTIVE_SEARCH_DOCS_URI"))
    define("WPSC_PREDICTIVE_SEARCH_DOCS_URI", "http://docs.a3rev.com/user-guides/wp-e-commerce/wpec-predictive-search/");

include 'classes/class-wpsc-predictive-search-filter.php';
include 'classes/class-wpsc-predictive-search.php';
include 'classes/class-wpsc-predictive-search-shortcodes.php';
include 'classes/class-wpsc-predictive-search-metabox.php';
include 'widget/wpsc-predictive-search-widgets.php';

include 'admin/classes/class-wpsc-predictive-search-admin.php';

// Editor
include 'tinymce3/tinymce.php';

include 'admin/wpsc-predictive-search-init.php';

/**
* Call when the plugin is activated
*/
register_activation_hook(__FILE__,'wpsc_predictive_install');

function wpsc_predictive_uninstall() {
	if ( get_option('ecommerce_search_clean_on_deletion') == 1 ) {
		delete_option('ecommerce_search_text_lenght');
		delete_option('ecommerce_search_result_items');
		delete_option('ecommerce_search_sku_enable');
		delete_option('ecommerce_search_price_enable');
		delete_option('ecommerce_search_addtocart_enable');
		delete_option('ecommerce_search_categories_enable');
		delete_option('ecommerce_search_tags_enable');
		delete_option('ecommerce_search_box_text');
		delete_option('ecommerce_search_page_id');
		delete_option('ecommerce_search_exclude_products');
		
		delete_option('ecommerce_search_exclude_p_categories');
		delete_option('ecommerce_search_exclude_p_tags');
		delete_option('ecommerce_search_exclude_posts');
		delete_option('ecommerce_search_exclude_pages');
		delete_option('ecommerce_search_focus_enable');
		delete_option('ecommerce_search_focus_plugin');
		delete_option('ecommerce_search_product_items');
		delete_option('ecommerce_search_p_sku_items');
		delete_option('ecommerce_search_p_cat_items');
		delete_option('ecommerce_search_p_tag_items');
		delete_option('ecommerce_search_post_items');
		delete_option('ecommerce_search_page_items');
		delete_option('ecommerce_search_character_max');
		delete_option('ecommerce_search_width');
		delete_option('ecommerce_search_padding_top');
		delete_option('ecommerce_search_padding_bottom');
		delete_option('ecommerce_search_padding_left');
		delete_option('ecommerce_search_padding_right');
		delete_option('ecommerce_search_custom_style');
		delete_option('ecommerce_search_global_search');
		
		delete_option('ecommerce_search_clean_on_deletion');
		
		delete_post_meta_by_key('_predictive_search_focuskw');
	}
}
if ( get_option('ecommerce_search_clean_on_deletion') == 1 ) {
	register_uninstall_hook( __FILE__, 'wpsc_predictive_uninstall' );
}
?>