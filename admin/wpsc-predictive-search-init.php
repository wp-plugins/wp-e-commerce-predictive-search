<?php
/**
 * Register Activation Hook
 */
update_option('wpsc_predictive_search_plugin', 'wpsc_predictive_search');
function wpsc_predictive_install() {
	global $wpdb;
	global $wp_rewrite;
	WPSC_Predictive_Search::create_page( 'ecommerce-search', 'ecommerce_search_page_id', __('Predictive Search', 'wpscps'), '[ecommerce_search]' );
	
	// Set Settings Default from Admin Init
	global $wpsc_predictive_search_admin_init;
	$wpsc_predictive_search_admin_init->set_default_settings();
	
	update_option('wpsc_predictive_search_lite_version', '2.1.1.4');
	update_option('wpsc_predictive_search_version', '2.1.1.4');
	$wp_rewrite->flush_rules();
	
	update_option('wpsc_predictive_search_just_installed', true);
}

function wpscps_init() {
	if ( get_option('wpsc_predictive_search_just_installed') ) {
		delete_option('wpsc_predictive_search_just_installed');
		wp_redirect( admin_url( 'edit.php?post_type=wpsc-product&page=wpsc-predictive-search', 'relative' ) );
		exit;
	}
	load_plugin_textdomain( 'wpscps', false, WPSC_PS_FOLDER.'/languages' );
}

function register_widget_wpscps_predictive_search() {
	register_widget('WPSC_Predictive_Search_Widgets');
}

// Add language
add_action('init', 'wpscps_init');

// Add custom style to dashboard
add_action( 'admin_enqueue_scripts', array( 'WPSC_Predictive_Search_Hook_Filter', 'a3_wp_admin' ) );

// Load Global variables
add_action( 'plugins_loaded', array( 'WPSC_Predictive_Search', 'plugins_loaded' ), 8 );

// Add text on right of Visit the plugin on Plugin manager page
add_filter( 'plugin_row_meta', array('WPSC_Predictive_Search_Hook_Filter', 'plugin_extra_links'), 10, 2 );

// Need to call Admin Init to show Admin UI
global $wpsc_predictive_search_admin_init;
$wpsc_predictive_search_admin_init->init();

// Add upgrade notice to Dashboard pages
add_filter( $wpsc_predictive_search_admin_init->plugin_name . '_plugin_extension', array( 'WPSC_Predictive_Search', 'plugin_extension' ) );

// Custom Rewrite Rules
add_action( 'init', array('WPSC_Predictive_Search_Hook_Filter', 'custom_rewrite_rule' ) );

// Registry widget
add_action('widgets_init', 'register_widget_wpscps_predictive_search');

// Add shortcode [ecommerce_search]
add_shortcode('ecommerce_search', array('WPSC_Predictive_Search_Shortcodes', 'parse_shortcode_search_result'));

// Add Predictive Search Meta Box to all post type
add_action( 'add_meta_boxes', array('WPSC_Predictive_Search_Meta','create_custombox'), 9 );

// Save Predictive Search Meta Box to all post type
if(in_array(basename($_SERVER['PHP_SELF']), array('post.php', 'page.php', 'page-new.php', 'post-new.php'))){
	add_action( 'save_post', array('WPSC_Predictive_Search_Meta','save_custombox' ) );
}

// Add search widget icon to Page Editor
if(in_array(basename($_SERVER['PHP_SELF']), array('post.php', 'page.php', 'page-new.php', 'post-new.php'))){
	add_action('media_buttons_context', array('WPSC_Predictive_Search_Shortcodes','add_search_widget_icon') );
	add_action('admin_footer', array('WPSC_Predictive_Search_Shortcodes','add_search_widget_mce_popup'));
}

if (!is_admin()) {
	add_filter( 'posts_search', array('WPSC_Predictive_Search_Hook_Filter', 'search_by_title_only'), 500, 2 );
	add_filter( 'posts_orderby', array('WPSC_Predictive_Search_Hook_Filter', 'predictive_posts_orderby'), 500, 2 );
	add_filter( 'posts_request', array('WPSC_Predictive_Search_Hook_Filter', 'posts_request_unconflict_role_scoper_plugin'), 500, 2);
}

// AJAX get result search page
add_action('wp_ajax_wpscps_get_result_search_page', array('WPSC_Predictive_Search_Shortcodes','get_result_search_page'));
add_action('wp_ajax_nopriv_wpscps_get_result_search_page', array('WPSC_Predictive_Search_Shortcodes','get_result_search_page'));

// AJAX get result search popup
add_action('wp_ajax_wpscps_get_result_popup', array('WPSC_Predictive_Search','wpscps_get_result_popup'));
add_action('wp_ajax_nopriv_wpscps_get_result_popup', array('WPSC_Predictive_Search','wpscps_get_result_popup'));

//Add ajax search box at header
if ( ! is_admin() )
	add_action('init',array('WPSC_Predictive_Search_Hook_Filter','wpscps_add_frontend_style'));

// Check upgrade functions
add_action('plugins_loaded', 'wpsc_predictive_search_lite_upgrade_plugin');
function wpsc_predictive_search_lite_upgrade_plugin () {
	
	// Upgrade to 2.0
	if(version_compare(get_option('wpsc_predictive_search_version'), '2.0') === -1){
		WPSC_Predictive_Search::upgrade_version_2_0();
		update_option('wpsc_predictive_search_version', '2.0');
	}
	
	update_option('wpsc_predictive_search_lite_version', '2.1.1.4');
	update_option('wpsc_predictive_search_version', '2.1.1.4');

}
?>