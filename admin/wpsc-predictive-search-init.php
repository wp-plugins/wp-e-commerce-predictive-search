<?php
/**
 * Register Activation Hook
 */
update_option('wpsc_predictive_search_plugin', 'wpsc_predictive_search');
function wpsc_predictive_install() {
	global $wpdb;
	WPSC_Predictive_Search::create_page( 'ecommerce-search', 'ecommerce_search_page_id', __('Predictive Search', 'wpscps'), '[ecommerce_search]' );
	WPSC_Predictive_Search::set_setting();
}

function wpscps_init() {
	load_plugin_textdomain( 'wpscps', false, WPSC_PS_FOLDER.'/languages' );
}

function register_widget_wpscps_predictive_search() {
	register_widget('WPSC_Predictive_Search_Widgets');
}

// Add language
add_action('init', 'wpscps_init');

// Load Global variables
add_action( 'plugins_loaded', array( 'WPSC_Predictive_Search', 'plugins_loaded' ), 8 );

// Add text on right of Visit the plugin on Plugin manager page
add_filter( 'plugin_row_meta', array('WPSC_Predictive_Search_Hook_Filter', 'plugin_extra_links'), 10, 2 );
// Add Predictive Search tab into Store settings 	
add_filter( 'wpsc_settings_tabs', array('WPSC_Predictive_Search_Hook_Filter', 'add_wpsc_settings_tabs') );

// Registry widget
add_action('widgets_init', 'register_widget_wpscps_predictive_search');

// Add shortcode [ecommerce_search]
add_shortcode('ecommerce_search', array('WPSC_Predictive_Search_Shortcodes', 'parse_shortcode_search_result'));

// Add search widget icon to Page Editor
if(in_array(basename($_SERVER['PHP_SELF']), array('post.php', 'page.php', 'page-new.php', 'post-new.php'))){
	add_action('media_buttons_context', array('WPSC_Predictive_Search_Shortcodes','add_search_widget_icon') );
	add_action('admin_footer', array('WPSC_Predictive_Search_Shortcodes','add_search_widget_mce_popup'));
}

add_filter( 'posts_search', array('WPSC_Predictive_Search_Hook_Filter', 'wpscps_search_by_title_only'), 500, 2 );

// AJAX get result search page
add_action('wp_ajax_wpscps_get_result_search_page', array('WPSC_Predictive_Search_Shortcodes','wpscps_get_result_search_page'));
add_action('wp_ajax_nopriv_wpscps_get_result_search_page', array('WPSC_Predictive_Search_Shortcodes','wpscps_get_result_search_page'));

// AJAX get result search popup
add_action('wp_ajax_wpscps_get_result_popup', array('WPSC_Predictive_Search','wpscps_get_result_popup'));
add_action('wp_ajax_nopriv_wpscps_get_result_popup', array('WPSC_Predictive_Search','wpscps_get_result_popup'));
?>