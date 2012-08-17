<?php
/**
 * WPSC_Settings_Tab_Ps_Settings Class
 *
 * Class Function into WP e-Commerce plugin
 *
 * Table Of Contents
 *
 * fonts
 * wpsc_dynamic_gallery_set_setting()
 * is_submit_button_displayed()
 * is_update_message_displayed()
 * update_settings()
 * display()
 */
class WPSC_Settings_Tab_Ps_Settings {
	
	public function is_submit_button_displayed() {
		return true;
	}
	public function is_update_message_displayed() {
		if(isset($_REQUEST['updateoption'])){
			$this->update_settings($_POST);
		}
		return true;
	}
	function update_settings($request){
		
		if( is_array($request) && count($request) > 0 ){
			unset($request['wpsc_admin_action']);
			unset($request['wpsc-update-options']);
			unset($request['_wp_http_referer']);
			unset($request['updateoption']);
			foreach($request as $key=>$value){
				update_option($key,$value);
			}
			update_option('ecommerce_search_result_items', 5);
			update_option('ecommerce_search_text_lenght', 100);
			update_option('ecommerce_search_price_enable', 0);
			update_option('ecommerce_search_categories_enable', 0);
			update_option('ecommerce_search_tags_enable', 0);
			
		}
	}
	public function display() {
		global $wpdb;
		$wpsc_predictive_search= wp_create_nonce("wpsc_predictive_search");
		?>
        <style type="text/css">
		.description{font-family: sans-serif;font-size: 12px;font-style: italic;color:#666666;}
		input.colorpick{text-transform:uppercase;}
		#wpsc_predictive_upgrade_area { border:2px solid #FF0;-webkit-border-radius:10px;-moz-border-radius:10px;-o-border-radius:10px; border-radius: 10px; padding:0; position:relative}
	  	#wpsc_predictive_upgrade_area h3{ margin-left:10px;}
	   	#wpsc_predictive_extensions { background: url("<?php echo WPSC_PS_IMAGES_URL; ?>/logo_a3blue.png") no-repeat scroll 4px 6px #FFFBCC; -webkit-border-radius:4px;-moz-border-radius:4px;-o-border-radius:4px; border-radius: 4px 4px 4px 4px; color: #555555; float: right; margin: 0px; padding: 4px 8px 4px 38px; position: absolute; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8); width: 300px; right:10px; top:10px; border:1px solid #E6DB55}
        </style>
        
		<h3><?php _e('Global Settings', 'wpscps'); ?></h3>
		<table class="form-table">
          <tr valign="top">
		    <td class="forminp" colspan="2">
            <?php _e('A search results page needs to be selected so that WP e-Commerce Predictive Search knows where to show search results. This page should have been created upon installation of the plugin, if not you need to create it.', 'wpscps');?>
            </td>
          </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_page_id"><?php _e('Search Page', 'wpscps');?></label></th>
		    <td class="forminp">
            <?php
			$args = array( 'name'				=> 'ecommerce_search_page_id',
            				   'id'					=> 'ecommerce_search_page_id',
            				   'sort_column' 		=> 'menu_order',
            				   'sort_order'			=> 'ASC',
            				   'show_option_none' 	=> __('Please select', 'wpscps'),
            				
            				   'echo' 				=> false,
            				   'selected'			=> get_option('ecommerce_search_page_id'));
           echo  wp_dropdown_pages($args);
			?> 
              <span class="description"><?php _e('Page contents:', 'wpscps');?> [ecommerce_search]</span>
            </td>
		  </tr>
		</table>
        <table class="form-table"><tr valign="top"><td style="padding:0;"><div id="wpsc_predictive_upgrade_area"><?php echo WPSC_Settings_Tab_Ps_Settings::predictive_extension(); ?>
        <h3><?php _e('Search results page settings', 'wpscps'); ?></h3>
        <table class="form-table">
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_result_items"><?php _e('Results', 'wpscps');?>	</label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="5" size="6" id="ecommerce_search_result_items" name="ecommerce_search_result_items" />
              <span class="description"><?php _e('The number of results to show before endless scroll click to see more results.', 'wpscps');?></span>
            </td>
		  </tr>
		  <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_text_lenght"><?php _e('Description character count', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="100" size="6" id="ecommerce_search_text_lenght" name="ecommerce_search_text_lenght" />
              <span class="description"><?php _e('The number of characters from product descriptions that shows with each search result.', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_price_enable"><?php _e('Price', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="checkbox" value="1" id="ecommerce_search_price_enable" name="ecommerce_search_price_enable" /> <span class="description"><?php _e('Show product price with search results', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_categories_enable"><?php _e('Product Categories', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="checkbox" value="1" id="ecommerce_search_categories_enable" name="ecommerce_search_categories_enable" /> <span class="description"><?php _e('Show categories with search results', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_tags_enable"><?php _e('Product Tags', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="checkbox" value="1" id="ecommerce_search_tags_enable" name="ecommerce_search_tags_enable" /> <span class="description"><?php _e('Show tags with search results', 'wpscps');?></span>
            </td>
		  </tr>
        </table>
        <h3><?php _e('Code', 'wpscps'); ?></h3>
		<table class="form-table">
          <tr valign="top">
		    <td class="forminp" colspan="2">
            <?php _e('Use this function to place the Predictive Search feature anywhere in your theme.', 'wpscps');?>
            <br /><code>&lt;?php if(function_exists('wpsc_search_widget')) wpsc_search_widget($items, $character_max, $style, $global_search); ?&gt;</code>
            <br /><br />
            <p><?php _e('Parameters', 'wpscps');?> :
            <br /><code>$items (int)</code> : <?php _e('Number of search results to show in search field drop-down. Default value is "6".', 'wpscps');?>
            <br /><code>$character_max (int)</code> : <?php _e('Number of characters from product description to show in search field drop-down. Default value is "100".', 'wpscps');?>
            <br /><code>$style (string)</code> : <?php _e('Use to create a custom style for the Predictive search box | Example: ', 'wpscps');?><code>"padding-top:10px;padding-bottom:10px;padding-left:0px;padding-right:0px;"</code>
            <br /><code>$global_search (bool)</code> : <?php _e('Set global search or search in current product category or current product tag. Default value is "true", global search.', 'wpscps');?>
            </p>
            </td>
          </tr>
        </table>
        </div></td></tr></table>
		<?php
	}
	
	function predictive_extension() {
		$html = '';
		$html .= '<div id="wpsc_predictive_extensions">'.__("Activate 'Smart Search', sidebar widget controls and the advance search results page features with our limited", 'wpscps').' <strong>$10</strong> <a target="_blank" href="'.WPSC_PS_AUTHOR_URI.'">'.__('WP e-Commerce Predictive Search Pro', 'wpscps').'</a> '.__('upgrade offer.', 'wpscps').' '.__('Hurry only limited numbers at this price.', 'wpscps').'</div>';
		return $html;	
	}
	
	function predictive_extension_shortcode() {
		$html = '';
		$html .= '<div id="wpsc_predictive_extensions">'.__("Please upgrade to the Pro version to activate this shortcode feature. Upgrade now with our limited", 'wpscps').' <strong>$10</strong> <a target="_blank" href="'.WPSC_PS_AUTHOR_URI.'">'.__('WP e-Commerce Predictive Search Pro', 'wpscps').'</a> '.__('upgrade offer.', 'wpscps').' '.__('Hurry only limited numbers at this price.', 'wpscps').'</div>';
		return $html;	
	}
}
?>
