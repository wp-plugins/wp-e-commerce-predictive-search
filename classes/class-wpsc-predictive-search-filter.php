<?php
/**
 * WPSC Predictive Search Hook Filter
 *
 * Hook anf Filter into ecommerce plugin
 *
 * Table Of Contents
 *
 * wpscps_add_settings_search()
 * wpscps_add_frontend_script()
 * wpscps_search_by_title_only()
 */
class WPSC_Predictive_Search_Hook_Filter {
	
	function add_wpsc_settings_tabs($tabs){
		$tabs['ps_settings'] = __('Predictive Search', 'wpscps');
		return $tabs;
	}
	
	/*
	* Include the script for widget search and Search page
	*/
	function wpscps_add_frontend_script() {
		wp_enqueue_style( 'ajax-wpsc-autocomplete-style', WPSC_PS_JS_URL . '/ajax-autocomplete/jquery.autocomplete.css' );
		wp_enqueue_script( 'ajax-wpsc-autocomplete-script', WPSC_PS_JS_URL . '/ajax-autocomplete/jquery.autocomplete.js' );
	}
	
	function wpscps_search_by_title_only1( $search, &$wp_query ) {
		global $wpdb;
		$q = $wp_query->query_vars;
		if ( empty( $q['predictive_s'] ) || trim($q['predictive_s']) == '' )
			return $search; // skip processing - no search term in query
		$search = '';
		$term = esc_sql( like_escape( trim($q['predictive_s']) ) );
		$search .= "{$searchand}($wpdb->posts.post_title LIKE '%{$term}%')";
		if ( ! empty( $search ) ) {
			$search = " AND ({$search}) ";
		}
		return $search;
	}
	
	function wpscps_search_by_title_only( $search, &$wp_query ) {
		global $wpdb;
		$q = $wp_query->query_vars;
		if ( empty( $search) )
			return $search; // skip processing - no search term in query
		$search = '';
		$term = esc_sql( like_escape( trim($q['s']) ) );
		$search .= "($wpdb->posts.post_title LIKE '{$term}%' OR $wpdb->posts.post_title LIKE '% {$term}%')";
		if ( ! empty( $search ) ) {
			$search = " AND ({$search}) ";
		}
		return $search;
	}
}
?>