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
        <h3><?php _e('Exclude From Predictive Search', 'wpscps'); ?></h3>
        <table class="form-table">
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_exclude_products"><?php _e('Exclude Products', 'wpscps');?></label></th>
		    <td class="forminp">
              <input type="text" value="<?php esc_attr_e( stripslashes( get_option('ecommerce_search_exclude_products') ) );?>" id="ecommerce_search_exclude_products" name="ecommerce_search_exclude_products" style="min-width:300px;" />
              <span class="description"><?php _e("Enter Product ID's comma separated", 'wpscps');?></span>
            </td>
		  </tr>
        </table>
        <table class="form-table"><tr valign="top"><td style="padding:0;"><div id="wpsc_predictive_upgrade_area"><?php echo WPSC_Settings_Tab_Ps_Settings::predictive_extension(); ?><div id="wpsc_predictive_upgrade_inner">
        <table class="form-table">
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_exclude_p_categories"><?php _e('Exclude Product Categories', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" id="ecommerce_search_exclude_p_categories" name="ecommerce_search_exclude_p_categories" style="min-width:300px;" />
              <p class="description"><?php _e("Enter Product Category ID's comma separated", 'wpscps');?></p>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_exclude_p_tags"><?php _e('Exclude Product Tags', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" id="ecommerce_search_exclude_p_tags" name="ecommerce_search_exclude_p_tags" style="min-width:300px;" />
              <p class="description"><?php _e("Enter Product Tag ID's comma separated", 'wpscps');?></p>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_exclude_posts"><?php _e('Exclude Posts', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" id="ecommerce_search_exclude_posts" name="ecommerce_search_exclude_posts" style="min-width:300px;" />
              <p class="description"><?php _e("Enter Post ID's comma separated", 'wpscps');?></p>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_exclude_pages"><?php _e('Exclude Pages', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" id="ecommerce_search_exclude_pages" name="ecommerce_search_exclude_pages" style="min-width:300px;" />
              <p class="description"><?php _e("Enter Page ID's comma separated", 'wpscps');?></p>
            </td>
		  </tr>
        </table>
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
        <h3><?php _e('Predictive Search Function', 'wpscps'); ?></h3>
		<table class="form-table">
          <tr valign="top">
		    <td class="forminp" colspan="2">
            <?php _e('Copy and paste this global function into your themes header.php file to replace any existing search function. (Be sure to delete the existing WordPress, WP e-Commerce or Theme search function)', 'wpscps');?>
            <br /><code>&lt;?php if(function_exists('wpsc_search_widget')) wpsc_search_widget(); ?&gt;</code>
            </td>
		  </tr>
        </table>
		 <h3><?php _e('Customize Search Function values', 'wpscps');?> :</h3>
         <table class="form-table">
          <tr valign="top">
		    <td class="forminp" colspan="2">
            <?php _e("The values you set here will be shown when you add the global search function to your header.php file. After adding the global function to your header.php file you can change the values here and 'Update' and they will be auto updated in the function.", "wpscps"); ?>
            </td>
          </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_product_items"><?php _e('Product name', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_product_items" name="ecommerce_search_product_items" />
              <span class="description"><?php _e('Number of Product Name to show in search field drop-down. Leave &lt;empty&gt; for not activated', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_p_sku_items"><?php _e('Product SKU', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_p_sku_items" name="ecommerce_search_p_sku_items" />
              <span class="description"><?php _e('Number of Product SKU to show in search field drop-down. Leave &lt;empty&gt; for not activated', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_p_cat_items"><?php _e('Product category', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_p_cat_items" name="ecommerce_search_p_cat_items" />
              <span class="description"><?php _e('Number of Product Categories to show in search field drop-down. Leave &lt;empty&gt; for not activated', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_p_tag_items"><?php _e('Product tag', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_p_tag_items" name="ecommerce_search_p_tag_items" />
              <span class="description"><?php _e('Number of Product Tags to show in search field drop-down. Leave &lt;empty&gt; for not activated', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_post_items"><?php _e('Post', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_post_items" name="ecommerce_search_post_items" />
              <span class="description"><?php _e('Number of Posts to show in search field drop-down. Leave &lt;empty&gt; for not activated', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_page_items"><?php _e('Page', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_page_items" name="ecommerce_search_page_items" />
              <span class="description"><?php _e('Number of Pages to show in search field drop-down. Leave &lt;empty&gt; for not activated', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_character_max"><?php _e('Description Characters', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_character_max" name="ecommerce_search_character_max" />
              <span class="description"><?php _e('Number of characters from product description to show in search field drop-down. Default value is "100".', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_width"><?php _e('Width', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_width" name="ecommerce_search_width" />px
              <span class="description"><?php _e('Leave &lt;empty&gt; for 100% wide', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_padding_top"><?php _e('Padding top', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_padding_top" name="ecommerce_search_padding_top" />px
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_padding_bottom"><?php _e('Padding bottom', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_padding_bottom" name="ecommerce_search_padding_bottom" />px
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_padding_left"><?php _e('Padding left', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_padding_left" name="ecommerce_search_padding_left" />px
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_padding_right"><?php _e('Padding right', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_padding_right" name="ecommerce_search_padding_right" />px
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_custom_style"><?php _e('Custom style', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" id="ecommerce_search_custom_style" name="ecommerce_search_custom_style" style="min-width:300px;" />
              <p class="description"><?php _e('Put other custom style for the Predictive search box', 'wpscps');?></p>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_global_search"><?php _e('Global search', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="checkbox" checked="checked" value="1" id="ecommerce_search_global_search" name="ecommerce_search_global_search" /> <span class="description"><label for="ecommerce_search_global_search"><?php _e('Set global search or search in current product category or current product tag. "Checked" to activate global search.', 'wpscps');?></label></span>
            </td>
		  </tr>
        </table>
        </div></div></td></tr></table>
		<?php
	}
	
	function predictive_extension() {
		$html = '';
		$html .= '<div id="wpsc_predictive_extensions">';
		$html .= '<a href="http://a3rev.com/shop/" target="_blank" style="float:right;margin-top:5px; margin-left:10px;" ><img src="'.WPSC_PS_IMAGES_URL.'/a3logo.png" /></a>';
		$html .= '<h3>'.__('Upgrade to Predictive Search Pro', 'wpscps').'</h3>';
		$html .= '<p>'.__("Visit the", 'wpscps').' <a href="'.WPSC_PS_AUTHOR_URI.'" target="_blank">'.__("a3rev website", 'wpscps').'</a> '.__("to see all the extra features the Pro version of this plugin offers like", 'wpscps').':</p>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>1. '.__('Activate the search results pages settings in this yellow border.', 'wpscps').'</li>';
		$html .= '<li>2. '.__('Activate Search by Product Categories, Product Tags, Posts and Pages options in the search widgets.', 'wpscps').'</li>';
		$html .= '<li>3. '.__('Activate Search shortcodes for Posts and pages.', 'wpscps').'</li>';
		$html .= '<li>4. '.__('Exclude Products, Product categories, tags post and pages from search results.', 'wpscps').'</li>';
		$html .= '</ul>';
		$html .= '</p>';
		$html .= '<h3>'.__('Plugin Documentation', 'wpscps').'</h3>';
		$html .= '<p>'.__('All of our plugins have comprehensive online documentation. Please refer to the plugins docs before raising a support request', 'wpscps').'. <a href="http://docs.a3rev.com/user-guides/wp-e-commerce/wpec-predictive-search/" target="_blank">'.__('Visit the a3rev wiki.', 'wpscps').'</a></p>';
		$html .= '<h3>'.__('More a3rev Quality Plugins', 'wpscps').'</h3>';
		$html .= '<p>'.__('Below is a list of the a3rev plugins that are available for free download from wordpress.org', 'wpscps').'</p>';
		$html .= '<h3>'.__('WP e-Commerce Plugins', 'wpscps').'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-dynamic-gallery/" target="_blank">'.__('WP e-Commerce Dynamic Gallery', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-predictive-search/" target="_blank">'.__('WP e-Commerce Predictive Search', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-ecommerce-compare-products/" target="_blank">'.__('WP e-Commerce Compare Products', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-catalog-visibility-and-email-inquiry/" target="_blank">'.__('WP e-Commerce Catalog Visibility & Email Inquiry', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-grid-view/" target="_blank">'.__('WP e-Commerce Grid View', 'wpscps').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
		
		$html .= '<h3>'.__('WordPress Plugins', 'wpscps').'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-email-template/" target="_blank">'.__('WordPress Email Template', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/page-views-count/" target="_blank">'.__('Page View Count', 'wpscps').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
		
		$html .= '<h3>'.__('WooCommerce Plugins', 'wpscps').'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/woocommerce-dynamic-gallery/" target="_blank">'.__('WooCommerce Dynamic Products Gallery', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/woocommerce-predictive-search/" target="_blank">'.__('WooCommerce Predictive Search', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/woocommerce-compare-products/" target="_blank">'.__('WooCommerce Compare Products', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/woo-widget-product-slideshow/" target="_blank">'.__('WooCommerce Widget Product Slideshow', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/woocommerce-email-inquiry-cart-options/" target="_blank">'.__('WooCommerce Email Inquiry & Cart Options', 'wpscps').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
		
		$html .= '<h3>'.__('Help spread the Word about this plugin', 'wpscps').'</h3>';
		$html .= '<p>'.__("Things you can do to help others find this plugin", 'wpscps');
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-predictive-search/" target="_blank">'.__('Rate this plugin 5', 'wpscps').' <img src="'.WPSC_PS_IMAGES_URL.'/stars.png" align="top" /> '.__('on WordPress.org', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-predictive-search/" target="_blank">'.__('Mark the plugin as a fourite', 'wpscps').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
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