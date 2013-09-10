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
 * add_scripts()
 * plugin_pro_notice()
 * predictive_extension_shortcode()
 */
class WPSC_Settings_Tab_Ps_Settings 
{
	
	public function is_submit_button_displayed() {
		return true;
	}
	public function is_update_message_displayed() {
		if(isset($_REQUEST['wpsc-update-options'])){
			$this->update_settings($_POST);
		}
		
		return true;
	}
	public function update_settings($request){
		
		if( is_array($request) && count($request) > 0 ){
			unset($request['wpsc_admin_action']);
			unset($request['wpsc-update-options']);
			unset($request['_wp_http_referer']);
			unset($request['updateoption']);
			foreach($request as $key=>$value){
				update_option($key,$value);
			}
			if (!isset($request['ecommerce_search_exclude_products'])) update_option('ecommerce_search_exclude_products', array() );
			if (!isset($request['ecommerce_search_clean_on_deletion'])) {
				update_option('ecommerce_search_clean_on_deletion', 0);
				$uninstallable_plugins = (array) get_option('uninstall_plugins');
				unset($uninstallable_plugins[WPSC_PS_NAME]);
				update_option('uninstall_plugins', $uninstallable_plugins);
			}
			WPSC_Predictive_Search::set_setting();
		}
	}
	public function display() {
		global $wpdb;
		$all_products = array();
		$all_posts = array();
		$all_pages = array();
		$all_p_categories = array();
		$all_p_tags = array();
		$results_products = $wpdb->get_results("SELECT ID, post_title FROM ".$wpdb->prefix."posts WHERE post_type='wpsc-product' AND post_status='publish' ORDER BY post_title ASC");
		if ($results_products) {
			foreach($results_products as $product_data) {
				$all_products[$product_data->ID] = $product_data->post_title;
			}
		}
		$results_posts = $wpdb->get_results("SELECT ID, post_title FROM ".$wpdb->prefix."posts WHERE post_type='post' AND post_status='publish' ORDER BY post_title ASC");
		if ($results_posts) {
			foreach($results_posts as $post_data) {
				$all_posts[$post_data->ID] = $post_data->post_title;
			}
		}
		$results_pages = $wpdb->get_results("SELECT ID, post_title FROM ".$wpdb->prefix."posts WHERE post_type='page' AND post_status='publish' ORDER BY post_title ASC");
		if ($results_pages) {
			foreach($results_pages as $page_data) {
				$all_pages[$page_data->ID] = $page_data->post_title;
			}
		}
		$results_p_categories = $wpdb->get_results("SELECT t.term_id, t.name FROM ".$wpdb->prefix."terms AS t INNER JOIN ".$wpdb->prefix."term_taxonomy AS tt ON(t.term_id=tt.term_id) WHERE tt.taxonomy='wpsc_product_category' ORDER BY t.name ASC");
		if ($results_p_categories) {
			foreach($results_p_categories as $p_categories_data) {
				$all_p_categories[$p_categories_data->term_id] = $p_categories_data->name;
			}
		}
		$results_p_tags = $wpdb->get_results("SELECT t.term_id, t.name FROM ".$wpdb->prefix."terms AS t INNER JOIN ".$wpdb->prefix."term_taxonomy AS tt ON(t.term_id=tt.term_id) WHERE tt.taxonomy='product_tag' ORDER BY t.name ASC");
		if ($results_p_tags) {
			foreach($results_p_tags as $p_tags_data) {
				$all_p_tags[$p_tags_data->term_id] = $p_tags_data->name;
			}
		}
		
		$wpsc_predictive_search= wp_create_nonce("wpsc_predictive_search");
		?>
        <style type="text/css">
		.code, code { font-family: inherit; font-size:inherit; }
		.form-table{margin:0;border-collapse:separate;}
		.description{font-family: sans-serif;font-size: 12px;font-style: italic;color:#666666;}
		.subsubsub { white-space:normal;}
		.subsubsub li { white-space:nowrap;}
		.a3-view-docs-button {
			background-color: #FFFFE0 !important;
			border: 1px solid #E6DB55 !important;
			text-shadow:none !important;
			font-weight:normal !important;
		}
		#wpec_predictive_search_panel_container { position:relative; margin-top:10px;}
		#wpec_predictive_search_panel_fields {width:60%; float:left;}
		#wpec_predictive_search_upgrade_area { position:relative; margin-left: 60%; padding-left:10px;}
		#wpec_predictive_search_extensions { border:2px solid #E6DB55;-webkit-border-radius:10px;-moz-border-radius:10px;-o-border-radius:10px; border-radius: 10px; color: #555555; margin: 0px; padding: 5px; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8); background:#FFFBCC; }
		.pro_feature_fields { margin-right: -12px; position: relative; z-index: 10; border:2px solid #E6DB55;-webkit-border-radius:10px 0 0 10px;-moz-border-radius:10px 0 0 10px;-o-border-radius:10px 0 0 10px; border-radius: 10px 0 0 10px; border-right: 2px solid #FFFFFF; }
		.pro_feature_fields h3, .pro_feature_fields p { margin-left:5px; }
		.pro_feature_fields h3 { margin-bottom:5px; }
		.pro_feature_fields .form-table td, .pro_feature_fields .form-table th { padding:4px 10px; }
        </style>
<div id="wpec_predictive_search_panel_container">
<div id="wpec_predictive_search_panel_fields" class="a3_subsubsub_section">
	<ul class="subsubsub">
		<li><a href="#global-settings" class="current"><?php _e('Predictive Search', 'wpscps'); ?></a> | </li>
		<li><a href="#all-results-pages"><?php _e('All Results Pages', 'wpscps'); ?></a> | </li>
		<li><a href="#exclude-content"><?php _e('Exclude Content', 'wpscps'); ?></a> | </li>
		<li><a href="#search-function"><?php _e('Search Function', 'wpscps'); ?></a></li>
	</ul>
    <br class="clear">
    <div class="section" id="global-settings">
    	<div class="pro_feature_fields">
    	<h3><?php _e('Focus Keywords', 'wpscps'); ?> <a class="add-new-h2 a3-view-docs-button" target="_blank" href="<?php echo WPSC_PREDICTIVE_SEARCH_DOCS_URI; ?>#section-11" ><?php _e('View Docs', 'wpscps'); ?></a></h3>
		<table class="form-table">
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_focus_enable"><?php _e('Predictive Search', 'wpscps');?></label></th>
		    <td class="forminp">
              <label><input disabled="disabled" type="checkbox" value="1" id="ecommerce_search_focus_enable" name="ecommerce_search_focus_enable" /> <span class=""><?php _e("Activate to optimize your sites content with Predictive Search 'Focus keywords'", 'wpscps');?></span></label>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_focus_plugin"><?php _e("Activate SEO 'Focus Keywords'", 'wpscps');?></label></th>
		    <td class="forminp">
            <?php
			$ecommerce_search_focus_plugin = get_option('ecommerce_search_focus_plugin');
			?>
            	<select class="chzn-select" size="1" name="ecommerce_search_focus_plugin" id="ecommerce_search_focus_plugin" style="width:300px">
					<option selected="selected" value="none"><?php _e('Select SEO plugin','wpscps'); ?></option>
					<option value="yoast_seo_plugin"><?php _e('Yoast WordPress SEO','wpscps'); ?></option>
                    <option value="all_in_one_seo_plugin"><?php _e('All in One SEO','wpscps'); ?></option>
				</select>
            </td>
		  </tr>
		</table>
        </div>
        <h3><?php _e('Global Search Box Text', 'wpscps'); ?></h3>
		<table class="form-table">
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_box_text"><?php _e('Predictive Search', 'wpscps');?></label></th>
		    <td class="forminp">
            <input type="text" value="<?php esc_attr_e( stripslashes( get_option('ecommerce_search_box_text') ) );?>" id="ecommerce_search_box_text" name="ecommerce_search_box_text" style="min-width:300px;" /> <span class="description"><?php _e("&lt;empty&gt; shows nothing", 'wpscps');?></span>
            </td>
		  </tr>
		</table>
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
			$pages = get_pages('title_li=&orderby=name');
			$ecommerce_search_page_id = get_option('ecommerce_search_page_id');
			?>
            	<select class="chzn-select" size="1" name="ecommerce_search_page_id" id="ecommerce_search_page_id" style="width:300px">
					<option selected='selected' value='0'><?php _e('Select Page','wpscps'); ?></option>
					<?php
					foreach ( $pages as $page ) {
						if ( $page->ID == $ecommerce_search_page_id ) {
							$selected = "selected='selected'";	
						} else {
							$selected = "";		
						}
						echo "<option $selected value='".$page->ID."'>". $page->post_title."</option>";
					}
					?>
				</select>
				<span class="description"><?php _e('Page contents:', 'wpscps');?> [ecommerce_search]</span>
            </td>
		  </tr>
		</table>
        <h3><?php _e('House Keeping', 'wpscps');?> :</h3>		
        <table class="form-table">
            <tr valign="top" class="">
				<th class="titledesc" scope="row"><label for="ecommerce_search_clean_on_deletion"><?php _e('Clean up on Deletion', 'wpscps');?></label></th>
				<td class="forminp">
						<label>
						<input <?php checked( get_option('ecommerce_search_clean_on_deletion'), 1); ?> type="checkbox" value="1" id="ecommerce_search_clean_on_deletion" name="ecommerce_search_clean_on_deletion">
						<?php _e('Check this box and if you ever delete this plugin it will completely remove all tables and data it created, leaving no trace it was ever here.', 'wpscps');?></label> <br>
				</td>
			</tr>
		</table>
	</div>
    <div class="section" id="all-results-pages"> 
    	<div class="pro_feature_fields">       
        <h3><?php _e('Search results page settings', 'wpscps'); ?> <a class="add-new-h2 a3-view-docs-button" target="_blank" href="<?php echo WPSC_PREDICTIVE_SEARCH_DOCS_URI; ?>#section-13" ><?php _e('View Docs', 'wpscps'); ?></a></h3>
        <table class="form-table">
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_result_items"><?php _e('Results', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="<?php esc_attr_e( stripslashes( get_option('ecommerce_search_result_items') ) );?>" size="6" id="ecommerce_search_result_items" name="ecommerce_search_result_items" />
              <span class="description"><?php _e('The number of results to show before endless scroll click to see more results.', 'wpscps');?></span>
            </td>
		  </tr>
		  <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_text_lenght"><?php _e('Description character count', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="<?php esc_attr_e( stripslashes( get_option('ecommerce_search_text_lenght') ) );?>" size="6" id="ecommerce_search_text_lenght" name="ecommerce_search_text_lenght" />
              <span class="description"><?php _e('The number of characters from product descriptions that shows with each search result.', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_sku_enable"><?php _e('SKU', 'wpscps');?></label></th>
		    <td class="forminp">
              <label><input disabled="disabled" type="checkbox" <?php if(get_option('ecommerce_search_sku_enable') == '' || get_option('ecommerce_search_sku_enable') == 1){ echo 'checked="checked"'; } ?> value="1" id="ecommerce_search_sku_enable" name="ecommerce_search_sku_enable" /> <span class=""><?php _e('Show product SKU with search results', 'wpscps');?></span></label>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_price_enable"><?php _e('Price', 'wpscps');?></label></th>
		    <td class="forminp">
              <label><input disabled="disabled" type="checkbox" <?php if(get_option('ecommerce_search_price_enable') == '' || get_option('ecommerce_search_price_enable') == 1){ echo 'checked="checked"'; } ?> value="1" id="ecommerce_search_price_enable" name="ecommerce_search_price_enable" /> <span class=""><?php _e('Show product price with search results', 'wpscps');?></span></label>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_categories_enable"><?php _e('Product Categories', 'wpscps');?></label></th>
		    <td class="forminp">
              <label><input disabled="disabled" type="checkbox" <?php if(get_option('ecommerce_search_categories_enable') == '' || get_option('ecommerce_search_categories_enable') == 1){ echo 'checked="checked"'; } ?> value="1" id="ecommerce_search_categories_enable" name="ecommerce_search_categories_enable" /> <span class=""><?php _e('Show categories with search results', 'wpscps');?></span></label>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_tags_enable"><?php _e('Product Tags', 'wpscps');?></label></th>
		    <td class="forminp">
              <label><input disabled="disabled" type="checkbox" <?php if(get_option('ecommerce_search_tags_enable') == '' || get_option('ecommerce_search_tags_enable') == 1){ echo 'checked="checked"'; } ?> value="1" id="ecommerce_search_tags_enable" name="ecommerce_search_tags_enable" /> <span class=""><?php _e('Show tags with search results', 'wpscps');?></span></label>
            </td>
		  </tr>
        </table>
        </div>
	</div>
    <div class="section" id="exclude-content">
    	<h3><?php _e('Exclude From Predictive Search', 'wpscps'); ?> <a class="add-new-h2 a3-view-docs-button" target="_blank" href="<?php echo WPSC_PREDICTIVE_SEARCH_DOCS_URI; ?>#section-12" ><?php _e('View Docs', 'wpscps'); ?></a></h3>
        <table class="form-table">
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_exclude_products"><?php _e('Exclude Products', 'wpscps');?></label></th>
		    <td class="forminp">
            	<?php $exclude_products = (array) get_option('ecommerce_search_exclude_products'); ?>
				<select multiple="multiple" name="ecommerce_search_exclude_products[]" data-placeholder="<?php _e('Choose Products', 'wpscps'); ?>" style="display:none; width:300px;" class="chzn-select">
                <?php
                foreach ($all_products as $key => $val) {
                ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( in_array($key, $exclude_products), true ); ?>><?php echo $val ?></option>
                <?php
                }
                ?>
				</select>
            </td>
		  </tr>
        </table>
        <div class="pro_feature_fields">
        <table class="form-table">
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_exclude_p_categories"><?php _e('Exclude Product Categories', 'wpscps');?></label></th>
		    <td class="forminp">
            	<?php $exclude_p_categories = (array) get_option('ecommerce_search_exclude_p_categories'); ?>
				<select multiple="multiple" name="ecommerce_search_exclude_p_categories[]" data-placeholder="<?php _e('Choose Product Categories', 'wpscps'); ?>" style="display:none; width:300px;" class="chzn-select">
                <?php
                foreach ($all_p_categories as $key => $val) {
                ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( in_array($key, $exclude_p_categories), true ); ?>><?php echo $val ?></option>
                <?php
                }
                ?>
				</select>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_exclude_p_tags"><?php _e('Exclude Product Tags', 'wpscps');?></label></th>
		    <td class="forminp">
            	<?php $exclude_p_tags = (array) get_option('ecommerce_search_exclude_p_tags'); ?>
				<select multiple="multiple" name="ecommerce_search_exclude_p_tags[]" data-placeholder="<?php _e('Choose Product Tags', 'wpscps'); ?>" style="display:none; width:300px;" class="chzn-select">
                <?php
                foreach ($all_p_tags as $key => $val) {
                ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( in_array($key, $exclude_p_tags), true ); ?>><?php echo $val ?></option>
                <?php
                }
                ?>
				</select>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_exclude_posts"><?php _e('Exclude Posts', 'wpscps');?></label></th>
		    <td class="forminp">
            	<?php $exclude_posts = (array) get_option('ecommerce_search_exclude_posts'); ?>
				<select multiple="multiple" name="ecommerce_search_exclude_posts[]" data-placeholder="<?php _e('Choose Posts', 'wpscps'); ?>" style="display:none; width:300px;" class="chzn-select">
                <?php
                foreach ($all_posts as $key => $val) {
                ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( in_array($key, $exclude_posts), true ); ?>><?php echo $val ?></option>
                <?php
                }
                ?>
				</select>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_exclude_pages"><?php _e('Exclude Pages', 'wpscps');?></label></th>
		    <td class="forminp">
            	<?php $exclude_pages = (array) get_option('ecommerce_search_exclude_pages'); ?>
				<select multiple="multiple" name="ecommerce_search_exclude_pages[]" data-placeholder="<?php _e('Choose Pages', 'wpscps'); ?>" style="display:none; width:300px;" class="chzn-select">
                <?php
                foreach ($all_pages as $key => $val) {
                ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( in_array($key, $exclude_pages), true ); ?>><?php echo $val ?></option>
                <?php
                }
                ?>
				</select>
            </td>
		  </tr>
        </table>
        </div>
    </div>
    <div class="section" id="search-function">
    	<div class="pro_feature_fields">
		<h3><?php _e('Predictive Search Function', 'wpscps'); ?> <a class="add-new-h2 a3-view-docs-button" target="_blank" href="<?php echo WPSC_PREDICTIVE_SEARCH_DOCS_URI; ?>#section-14" ><?php _e('View Docs', 'wpscps'); ?></a></h3>
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
              <span class="description"><?php _e('Number of Product Name to show in search field drop-down.', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_p_sku_items"><?php _e('Product SKU', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_p_sku_items" name="ecommerce_search_p_sku_items" />
              <span class="description"><?php _e('Number of Product SKU to show in search field drop-down.', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_p_cat_items"><?php _e('Product category', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_p_cat_items" name="ecommerce_search_p_cat_items" />
              <span class="description"><?php _e('Number of Product Categories to show in search field drop-down.', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_p_tag_items"><?php _e('Product tag', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_p_tag_items" name="ecommerce_search_p_tag_items" />
              <span class="description"><?php _e('Number of Product Tags to show in search field drop-down.', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_post_items"><?php _e('Post', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_post_items" name="ecommerce_search_post_items" />
              <span class="description"><?php _e('Number of Posts to show in search field drop-down.', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_page_items"><?php _e('Page', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_page_items" name="ecommerce_search_page_items" />
              <span class="description"><?php _e('Number of Pages to show in search field drop-down.', 'wpscps');?></span>
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
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_width" name="ecommerce_search_width" />
              <span class="description">px <?php _e('Leave &lt;empty&gt; for 100% wide', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_padding_top"><?php _e('Padding top', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_padding_top" name="ecommerce_search_padding_top" /><span class="description">px</span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_padding_bottom"><?php _e('Padding bottom', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_padding_bottom" name="ecommerce_search_padding_bottom" /><span class="description">px</span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_padding_left"><?php _e('Padding left', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_padding_left" name="ecommerce_search_padding_left" /><span class="description">px</span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_padding_right"><?php _e('Padding right', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" size="6" id="ecommerce_search_padding_right" name="ecommerce_search_padding_right" /><span class="description">px</span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_custom_style"><?php _e('Custom style', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="text" value="" id="ecommerce_search_custom_style" name="ecommerce_search_custom_style" style="min-width:300px;" />
              <span class="description"><?php _e('Put other custom style for the Predictive search box', 'wpscps');?></span>
            </td>
		  </tr>
          <tr valign="top">
		    <th class="titledesc" scope="row"><label for="ecommerce_search_global_search"><?php _e('Global search', 'wpscps');?></label></th>
		    <td class="forminp">
              <input disabled="disabled" type="checkbox" value="1" id="ecommerce_search_global_search" name="ecommerce_search_global_search" /> <span class="description"><label for="ecommerce_search_global_search"><?php _e('Set global search or search in current product category or current product tag. "Checked" to activate global search.', 'wpscps');?></label></span>
            </td>
		  </tr>
        </table>
        </div>
	</div>
</div>
<div id="wpec_predictive_search_upgrade_area"><?php echo WPSC_Settings_Tab_Ps_Settings::plugin_pro_notice(); ?></div>
</div>
<div style="clear:both;"></div>
<script type="text/javascript">
	jQuery(window).load(function(){
		// Subsubsub tabs
		jQuery('div.a3_subsubsub_section ul.subsubsub li a:eq(0)').addClass('current');
		jQuery('div.a3_subsubsub_section .section:gt(0)').hide();

		jQuery('div.a3_subsubsub_section ul.subsubsub li a').click(function(){
			var $clicked = jQuery(this);
			var $section = $clicked.closest('.a3_subsubsub_section');
			var $target  = $clicked.attr('href');

			$section.find('a').removeClass('current');

			if ( $section.find('.section:visible').size() > 0 ) {
				$section.find('.section:visible').fadeOut( 100, function() {
					$section.find( $target ).fadeIn('fast');
				});
			} else {
				$section.find( $target ).fadeIn('fast');
			}

			$clicked.addClass('current');
			jQuery('#last_tab').val( $target );
	
			return false;
		});

	<?php if (isset($_GET['subtab']) && $_GET['subtab']) echo 'jQuery("div.a3_subsubsub_section ul.subsubsub li a[href=#'.$_GET['subtab'].']").click();'; ?>
	});
</script>
<?php add_action('admin_footer', array(&$this, 'add_scripts'), 10); ?>
		<?php
	}
	
	public function add_scripts(){
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script('jquery');
		
		wp_enqueue_style( 'a3rev-chosen-style', WPSC_PS_JS_URL . '/chosen/chosen.css' );
		wp_enqueue_script( 'chosen', WPSC_PS_JS_URL . '/chosen/chosen.jquery'.$suffix.'.js', array(), false, true );
		
		wp_enqueue_script( 'a3rev-chosen-script-init', WPSC_PS_JS_URL.'/init-chosen.js', array(), false, true );
	}
	
	public static function plugin_pro_notice() {
		$html = '';
		$html .= '<div id="wpec_predictive_search_extensions">';
		$html .= '<a href="http://a3rev.com/shop/" target="_blank" style="float:right;margin-top:5px; margin-left:10px;" ><img src="'.WPSC_PS_IMAGES_URL.'/a3logo.png" /></a>';
		$html .= '<h3>'.__('Upgrade to Predictive Search Pro', 'wpscps').'</h3>';
		$html .= '<p>'.__("<strong>NOTE:</strong> All the functions inside the Yellow border on the plugins admin panel are extra functionality that is activated by upgrading to the Pro version", 'wpscps').':</p>';
		$html .= '<h3>* <a href="'.WPSC_PS_AUTHOR_URI.'" target="_blank">'.__('WPEC Predictive Search Pro', 'wpscps').'</a></h3>';
		$html .= '<h3>'.__('Activates these advanced features', 'wpscps').':</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>1. '.__("Activate site search optimization with Predictive Search 'Focus Keywords'.", 'wpscps').'</li>';
		$html .= '<li>2. '.__('Activate integration with Yoasts WordPress SEO or All in One SEO plugins.', 'wpscps').'</li>';
		$html .= '<li>3. '.__('Activate the Advance All Search results page customization setting.', 'wpscps').'</li>';
		$html .= '<li>4. '.__('Activate Search by Product Categories, Product Tags, Posts and Pages options in the search widgets.', 'wpscps').'</li>';
		$html .= '<li>5. '.__('Activate Search shortcodes for Posts and pages.', 'wpscps').'</li>';
		$html .= '<li>6. '.__('Activate Exclude Product Cats, Product Tags , Posts and pages from search results.', 'wpscps').'</li>';
		$html .= '<li>7. '.__('Activate Predictive Search Function to place the search box in any non widget area of your site - example the header.', 'wpscps').'</li>';
		$html .= '<li>8. '.__("Activate 'Smart Search' function on Widgets, Shortcode and the search Function", 'wpscps').'</li>';
		$html .= '</ul>';
		$html .= '</p>';
		
		$html .= '<h3>'.__('View this plugins', 'wpscps').' <a href="http://docs.a3rev.com/user-guides/wp-e-commerce/wpec-predictive-search/" target="_blank">'.__('documentation', 'wpscps').'</a></h3>';
		$html .= '<h3>'.__('Visit this plugins', 'wpscps').' <a href="http://wordpress.org/support/plugin/wp-e-commerce-predictive-search/" target="_blank">'.__('support forum', 'wpscps').'</a></h3>';
		$html .= '<h3>'.__('More FREE a3rev WP e-Commerce Plugins', 'wpscps').'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-e-commerce-products-quick-view/" target="_blank">'.__('WP e-Commerce Products Quick View', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-e-commerce-dynamic-gallery/" target="_blank">'.__('WP e-Commerce Dynamic Gallery', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-ecommerce-compare-products/" target="_blank">'.__('WP e-Commerce Compare Products', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-e-commerce-catalog-visibility-and-email-inquiry/" target="_blank">'.__('WP e-Commerce Catalog Visibility & Email Inquiry', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-e-commerce-grid-view/" target="_blank">'.__('WP e-Commerce Grid View', 'wpscps').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
		$html .= '<h3>'.__('FREE a3rev WordPress Plugins', 'wpscps').'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/plugins/contact-us-page-contact-people/" target="_blank">'.__('Contact Us Page - Contact People', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-email-template/" target="_blank">'.__('WordPress Email Template', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/page-views-count/" target="_blank">'.__('Page View Count', 'wpscps').'</a></li>';
		$html .= '</div>';
		return $html;
	}
	
	public static function predictive_extension_shortcode() {
		$html = '';
		$html .= '<div id="wpsc_predictive_extensions">'.__("Yes you'll love the Predictive Search shortcode feature. Upgrading to the", 'wpscps').' <a target="_blank" href="'.WPSC_PS_AUTHOR_URI.'">'.__('Pro Version', 'wpscps').'</a> '.__("activates this shortcode feature as well as the awesome 'Smart Search' feature, per widget controls, the All Search Results page customization settings and function features.", 'wpscps').'</div>';
		return $html;	
	}
}
?>