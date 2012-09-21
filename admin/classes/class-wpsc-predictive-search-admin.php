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
		.form-table { margin:0; }
		#wpsc_predictive_upgrade_area { border:2px solid #E6DB55;-webkit-border-radius:10px;-moz-border-radius:10px;-o-border-radius:10px; border-radius: 10px; padding:0 40% 0 0; position:relative; background:#FFFBCC;}
		#wpsc_predictive_upgrade_inner { background:#FFF; -webkit-border-radius:10px 0 0 10px;-moz-border-radius:10px 0 0 10px;-o-border-radius:10px 0 0 10px; border-radius: 10px 0 0 10px;}
		#wpsc_predictive_upgrade_inner h3{ margin-left:10px;}
		#wpsc_predictive_extensions { -webkit-border-radius:4px;-moz-border-radius:4px;-o-border-radius:4px; border-radius: 4px 4px 4px 4px; color: #555555; float: right; margin: 0px; padding: 5px; position: absolute; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8); width: 38%; right:0; top:0px;}		
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
        <table class="form-table"><tr valign="top"><td style="padding:0;"><div id="wpsc_predictive_upgrade_area"><?php echo WPSC_Settings_Tab_Ps_Settings::predictive_extension(); ?><div id="wpsc_predictive_upgrade_inner">
        <h3 style="margin-top:0; padding-top:10px;"><?php _e('Search results page settings', 'wpscps'); ?></h3>
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
            <br /><code>&lt;?php if(function_exists('wpsc_search_widget')) wpsc_search_widget($product_items, $product_category_items, $product_tag_items, $post_items, $page_items, $character_max, $style, $global_search); ?&gt;</code>
            <br /><br />
            <p><?php _e('Parameters', 'wpscps');?> :
            <br /><code>$product_items (int)</code> : <?php _e('Number of Products to show in search field drop-down. Default value is "6".', 'wpscps');?>
            <br /><code>$product_category_items (int)</code> : <?php _e('Number of Product Categories to show in search field drop-down. Default value is "0".', 'wpscps');?>
            <br /><code>$product_tag_items (int)</code> : <?php _e('Number of Product Tags to show in search field drop-down. Default value is "0".', 'wpscps');?>
            <br /><code>$post_items (int)</code> : <?php _e('Number of Posts to show in search field drop-down. Default value is "0".', 'wpscps');?>
            <br /><code>$page_items (int)</code> : <?php _e('Number of Pages to show in search field drop-down. Default value is "0".', 'wpscps');?>
            <br /><code>$character_max (int)</code> : <?php _e('Number of characters from product description to show in search field drop-down. Default value is "100".', 'wpscps');?>
            <br /><code>$style (string)</code> : <?php _e('Use to create a custom style for the Predictive search box | Example: ', 'wpscps');?><code>"padding-top:10px;padding-bottom:10px;padding-left:0px;padding-right:0px;"</code>
            <br /><code>$global_search (bool)</code> : <?php _e('Set global search or search in current product category or current product tag. Default value is "true", global search.', 'wpscps');?>
            </p>
            </td>
          </tr>
        </table>
        </div></div></td></tr></table>
		<?php
	}
	
	function predictive_extension() {
		$html = '';
		$html .= '<div id="wpsc_predictive_extensions">';
		$html .= '<h3>'.__('No Donations Accepted', 'wpscps').'</h3>';
		$html .= '<img src="'.WPSC_PS_IMAGES_URL.'/btn_donate.png" />';
		$html .= '<h3>'.__('Upgrade to the Pro version for Just', '').' $18 '.__('to', 'wpscps').'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>1. '.__('Activate the search results pages settings in this yellow border.', 'wpscps').'</li>';
		$html .= '<li>2. '.__('Activate Search by Product Categories, Product Tags, Posts and Pages options in the search widgets.', 'wpscps').'</li>';
		$html .= '<li>3. '.__('Activate Search shortcodes for Posts and pages.', 'wpscps').'</li>';
		$html .= '<li>4. '.__('Same day priority support.', 'wpscps').'</li>';
		$html .= '</ul>';
		$html .= '</p>';
		$html .= '<p>* '.__('See the Pro version on the', 'wpscps').' <a href="'.WPSC_PS_AUTHOR_URI.'" target="_blank">'.__('A3 market place', 'wpscps').'</a></p>';
		$html .= '<h3>'.__('Go Pro and help us help you.', 'wpscps').'</h3>';
		$html .= '<p>'.__('A Pro upgrade license fee helps fund and support the maintenance and ongoing development of this plugin.', 'wpscps').'</p>';
		$html .= '<h3>'.__('More WP e-Commerce Plugins from A3 Rev', 'wpscps').'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-dynamic-gallery/" target="_blank">'.__('WP e-Commerce Dynamic Gallery', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-ecommerce-compare-products/" target="_blank">'.__('WP e-Commerce Compare Products', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-email-template/" target="_blank">'.__('WP Email Template', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-catalog-visibility-and-email-inquiry/" target="_blank">'.__('WP e-Commerce Catalog Visibility', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-grid-view/" target="_blank">'.__('WP e-Commerce Grid View', 'wpscps').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
		$html .= '<h3>'.__('Spreading the Word about this plugin.', 'wpscps').'</h3>';
		$html .= '<p>'.__("Things you can do to help others find this plugin", 'wpscps');
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-predictive-search/" target="_blank">'.__('Rate this plugin 5', 'wpscps').' <img src="'.WPSC_PS_IMAGES_URL.'/stars.png" align="top" /> '.__('on WordPress.org', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="'.WPSC_PS_AUTHOR_URI.'" target="_blank">'.__('Write about it in your blog', 'wpscps').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
		$html .= '<h3>'.__('Thank you for your support!', 'wpscps').'</h3>';
		$html .= '</div>';
		return $html;
	}
	
	function predictive_extension_shortcode() {
		$html = '';
		$html .= '<div id="wpsc_predictive_extensions">'.__("Yes you'll love the Predictive Search shortcode feature. Upgrading to the", 'wpscps').' <a target="_blank" href="'.WPSC_PS_AUTHOR_URI.'">'.__('Pro Version', 'wpscps').'</a> '.__("activates this shortcode feature as well as the awesome 'Smart Search' feature, per widget controls, the All Search Results page customization settings and function features.", 'wpscps').'</div>';
		return $html;	
	}
}
?>