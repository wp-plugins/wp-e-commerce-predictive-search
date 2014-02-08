<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
/*-----------------------------------------------------------------------------------
WPSC Predictive Search Global Settings

TABLE OF CONTENTS

- var parent_tab
- var subtab_data
- var option_name
- var form_key
- var position
- var form_fields
- var form_messages

- __construct()
- subtab_init()
- set_default_settings()
- get_settings()
- subtab_data()
- add_subtab()
- settings_form()
- init_form_fields()

-----------------------------------------------------------------------------------*/

class WPSC_Predictive_Search_Global_Settings extends WPSC_Predictive_Search_Admin_UI
{
	
	/**
	 * @var string
	 */
	private $parent_tab = 'global-settings';
	
	/**
	 * @var array
	 */
	private $subtab_data;
	
	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = '';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'wpsc_predictive_search_global_settings';
	
	/**
	 * @var string
	 * You can change the order show of this sub tab in list sub tabs
	 */
	private $position = 1;
	
	/**
	 * @var array
	 */
	public $form_fields = array();
	
	/**
	 * @var array
	 */
	public $form_messages = array();
	
	/*-----------------------------------------------------------------------------------*/
	/* __construct() */
	/* Settings Constructor */
	/*-----------------------------------------------------------------------------------*/
	public function __construct() {
		
		$this->init_form_fields();
		$this->subtab_init();
		
		$this->form_messages = array(
				'success_message'	=> __( 'Global Settings successfully saved.', 'wpscps' ),
				'error_message'		=> __( 'Error: Global Settings can not save.', 'wpscps' ),
				'reset_message'		=> __( 'Global Settings successfully reseted.', 'wpscps' ),
			);
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_end', array( $this, 'include_script' ) );
			
		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'reset_default_settings' ) );
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'after_save_settings' ) );
		//add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );
		
		// Add yellow border for pro fields
		add_action( $this->plugin_name . '_settings_pro_focus_keywords_before', array( $this, 'pro_fields_before' ) );
		add_action( $this->plugin_name . '_settings_pro_seo_focus_keywords_after', array( $this, 'pro_fields_after' ) );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* subtab_init() */
	/* Sub Tab Init */
	/*-----------------------------------------------------------------------------------*/
	public function subtab_init() {
		
		add_filter( $this->plugin_name . '-' . $this->parent_tab . '_settings_subtabs_array', array( $this, 'add_subtab' ), $this->position );
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* set_default_settings()
	/* Set default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function set_default_settings() {
		global $wpsc_predictive_search_admin_interface;
		
		$wpsc_predictive_search_admin_interface->reset_settings( $this->form_fields, $this->option_name, false );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* reset_default_settings()
	/* Reset default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function reset_default_settings() {
		global $wpsc_predictive_search_admin_interface;
		
		$wpsc_predictive_search_admin_interface->reset_settings( $this->form_fields, $this->option_name, true, true );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* after_save_settings()
	/* Process when clean on deletion option is un selected */
	/*-----------------------------------------------------------------------------------*/
	public function after_save_settings() {
		if ( ( isset( $_POST['bt_save_settings'] ) || isset( $_POST['bt_reset_settings'] ) ) && get_option( 'ecommerce_search_lite_clean_on_deletion' ) == 0  )  {
			$uninstallable_plugins = (array) get_option('uninstall_plugins');
			unset($uninstallable_plugins[WPSC_PS_NAME]);
			update_option('uninstall_plugins', $uninstallable_plugins);
		}
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* get_settings()
	/* Get settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function get_settings() {
		global $wpsc_predictive_search_admin_interface;
		
		$wpsc_predictive_search_admin_interface->get_settings( $this->form_fields, $this->option_name );
	}
	
	/**
	 * subtab_data()
	 * Get SubTab Data
	 * =============================================
	 * array ( 
	 *		'name'				=> 'my_subtab_name'				: (required) Enter your subtab name that you want to set for this subtab
	 *		'label'				=> 'My SubTab Name'				: (required) Enter the subtab label
	 * 		'callback_function'	=> 'my_callback_function'		: (required) The callback function is called to show content of this subtab
	 * )
	 *
	 */
	public function subtab_data() {
		
		$subtab_data = array( 
			'name'				=> 'global-settings',
			'label'				=> __( 'Settings', 'wpscps' ),
			'callback_function'	=> 'wpsc_predictive_search_global_settings_form',
		);
		
		if ( $this->subtab_data ) return $this->subtab_data;
		return $this->subtab_data = $subtab_data;
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* add_subtab() */
	/* Add Subtab to Admin Init
	/*-----------------------------------------------------------------------------------*/
	public function add_subtab( $subtabs_array ) {
	
		if ( ! is_array( $subtabs_array ) ) $subtabs_array = array();
		$subtabs_array[] = $this->subtab_data();
		
		return $subtabs_array;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* settings_form() */
	/* Call the form from Admin Interface
	/*-----------------------------------------------------------------------------------*/
	public function settings_form() {
		global $wpsc_predictive_search_admin_interface;
		
		$output = '';
		$output .= $wpsc_predictive_search_admin_interface->admin_forms( $this->form_fields, $this->form_key, $this->option_name, $this->form_messages );
		
		return $output;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* init_form_fields() */
	/* Init all fields of this form */
	/*-----------------------------------------------------------------------------------*/
	public function init_form_fields() {
		
  		// Define settings			
     	$this->form_fields = apply_filters( $this->option_name . '_settings_fields', array(
		
			array(
            	'name' 		=> __( 'Advanced Site Search Optimization', 'wpscps' ),
				'desc'		=> __( 'Get Complete Control over Search results, for every product, post and page with Predictive Search Focus Keywords and / or the Focus Keywords you have set in leading SEO plugins.', 'wpscps' ),
                'type' 		=> 'heading',
				'id'		=> 'pro_focus_keywords',
           	),
			array(  
				'name' 		=> __( 'Predictive Search', 'wpscps' ),
				'class'		=> 'ecommerce_search_focus_enable',
				'id' 		=> 'ecommerce_search_focus_enable',
				'type' 		=> 'onoff_checkbox',
				'default'	=> '0',
				'checked_value'		=> '1',
				'unchecked_value'	=> '0',
				'checked_label'		=> __( 'ON', 'wpscps' ),
				'unchecked_label' 	=> __( 'OFF', 'wpscps' ),
			),
			
			array(
                'type' 		=> 'heading',
				'class'		=> 'ecommerce_search_focus_plugin_container',
				'id'		=> 'pro_seo_focus_keywords',
           	),
			array(  
				'name' 		=> __( "SEO Focus Keywords", 'wpscps' ),
				'desc' 		=> __("Supported plugins, WordPress SEO and ALL in ONE SEO Pack.", 'wpscps'),
				'id' 		=> 'ecommerce_search_focus_plugin',
				'type' 		=> 'select',
				'default'	=> 'none',
				'options'	=> array(
						'none'						=> __( 'Select SEO plugin', 'wpscps' ) ,							
						'yoast_seo_plugin'			=> __( 'Yoast WordPress SEO', 'wpscps' ) ,
						'all_in_one_seo_plugin'		=> __( 'All in One SEO', 'wpscps' ) ,
					),
			),
			
			array(
            	'name' 		=> __( 'Global Search Box Text', 'wpscps' ),
                'type' 		=> 'heading',
           	),
			array(
            	'name' 		=> __( 'Text to Show', 'wpscps' ),
				'desc'		=> __('&lt;empty&gt; shows nothing', 'wpscps'),
                'type' 		=> 'text',
				'id'		=> 'ecommerce_search_box_text',
				'default'	=> '',
				'free_version'		=> true,
           	),
			
      		array(
            	'name' 		=> __('Search Page Configuration', 'wpscps'),
                'type' 		=> 'heading',
                'desc' 		=> __('A search results page needs to be selected so that WP e-Commerce Predictive Search knows where to show search results. This page should have been created upon installation of the plugin, if not you need to create it.', 'wpscps'),
           	),
			array(  
				'name' 		=> __( 'Search Page', 'wpscps' ),
				'desc' 		=> __('Page contents:', 'wpscps').' [ecommerce_search]',
				'id' 		=> 'ecommerce_search_page_id',
				'type' 		=> 'single_select_page',
				'free_version'		=> true,
			),
			
			array(	
				'name' 		=> __( 'House Keeping', 'wpscps' ).' :', 
				'type' 		=> 'heading',
			),
			array(  
				'name' 		=> __( 'Clean up on Deletion', 'wpscps' ),
				'desc' 		=> __( 'On deletion (not deactivate) the plugin it will completely remove all of its code and tables it has created, leaving no trace it was ever here.', 'wpscps' ),
				'id' 		=> 'ecommerce_search_lite_clean_on_deletion',
				'default'	=> '0',
				'type' 		=> 'onoff_checkbox',
				'separate_option'	=> true,
				'free_version'		=> true,
				'checked_value'		=> '1',
				'unchecked_value'	=> '0',
				'checked_label'		=> __( 'ON', 'wpscps' ),
				'unchecked_label' 	=> __( 'OFF', 'wpscps' ),
			),
		
        ));
	}
	
	public function include_script() {
	?>
<script>
(function($) {
	
	$(document).ready(function() {
		
		if ( $("input.ecommerce_search_focus_enable:checked").val() == '1') {
			$(".ecommerce_search_focus_plugin_container").show();
		} else {
			$(".ecommerce_search_focus_plugin_container").hide();
		}
			
		$(document).on( "a3rev-ui-onoff_checkbox-switch", '.ecommerce_search_focus_enable', function( event, value, status ) {
			if ( status == 'true' ) {
				$(".ecommerce_search_focus_plugin_container").slideDown();
			} else {
				$(".ecommerce_search_focus_plugin_container").slideUp();
			}
		});
		
	});
	
})(jQuery);
</script>
    <?php	
	}
	
}

global $wpsc_predictive_search_global_settings;
$wpsc_predictive_search_global_settings = new WPSC_Predictive_Search_Global_Settings();

/** 
 * wpsc_predictive_search_global_settings_form()
 * Define the callback function to show subtab content
 */
function wpsc_predictive_search_global_settings_form() {
	global $wpsc_predictive_search_global_settings;
	$wpsc_predictive_search_global_settings->settings_form();
}

?>