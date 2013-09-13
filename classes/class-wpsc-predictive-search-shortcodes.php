<?php
/**
 * WPSC Predictive Search Hook Filter
 *
 * Hook anf Filter into ecommerce plugin
 *
 * Table Of Contents
 *
 * parse_shortcode_search_widget()
 * add_search_widget_icon()
 * add_search_widget_mce_popup()
 * parse_shortcode_search_result()
 * get_product_price()
 * get_product_price_dropdown()
 * get_product_addtocart()
 * get_product_categories()
 * get_post_categories()
 * get_product_tags()
 * get_post_tags()
 * display_search()
 * get_result_search_page()
 */
class WPSC_Predictive_Search_Shortcodes 
{
	public static function parse_shortcode_search_widget($attributes) {}
	
	function add_search_widget_icon($context){
		$image_btn = WPSC_PS_IMAGES_URL . "/ps_icon.png";
		$out = '<a href="#TB_inline?width=670&height=680&modal=false&inlineId=search_widget_shortcode" class="thickbox" title="'.__('Insert WP e-Commerce Predictive Search Shortcode', 'wpscps').'"><img class="search_widget_shortcode_icon" src="'.$image_btn.'" alt="'.__('Insert WP e-Commerce Predictive Search Shortcode', 'wpscps').'" /></a>';
		return $context . $out;
	}
	
	//Action target that displays the popup to insert a form to a post/page
	public static function add_search_widget_mce_popup(){
		$items_search_default = WPSC_Predictive_Search_Widgets::get_items_search();
		?>
		<script type="text/javascript">
			function alert_upgrade(text) {
				var answer = confirm(text)
				if (answer){
					window.open("<?php echo WPSC_PS_AUTHOR_URI; ?>", '_blank')
				}else{
					return false;
				}
			}
			
			
		</script>
		<style type="text/css">
		#TB_ajaxContent{width:auto !important;}
		#TB_ajaxContent p {
			padding:2px 0;	
			margin:6px 0;
		}
		.field_content {
			padding:0 0 0 40px;	
		}
		.field_content label{
			width:150px;
			float:left;
			text-align:left;
		}
		.a3-view-docs-button {
			background-color: #FFFFE0 !important;
			border: 1px solid #E6DB55 !important;
			border-radius: 3px;
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			color: #21759B !important;
			outline: 0 none;
			text-shadow:none !important;
			font-weight:normal !important;
			font-family: sans-serif;
			font-size: 12px;
			text-decoration: none;
			padding: 3px 8px;
			position: relative;
			margin-left: 4px;
			top: -3px;
		}
		.a3-view-docs-button:hover {
			color: #D54E21 !important;
		}
		#wpsc_predictive_upgrade_area { border:2px solid #E6DB55;-webkit-border-radius:10px;-moz-border-radius:10px;-o-border-radius:10px; border-radius: 10px; padding:0; position:relative}
	  	#wpsc_predictive_upgrade_area h3{ margin-left:10px;}
	   	#wpsc_predictive_extensions { background: url("<?php echo WPSC_PS_IMAGES_URL; ?>/logo_a3blue.png") no-repeat scroll 4px 6px #FFFBCC; -webkit-border-radius:10px 10px 0 0;-moz-border-radius:10px 10px 0 0;-o-border-radius:10px 10px 0 0; border-radius: 10px 10px 0 0; color: #555555; margin: 0px; padding: 4px 8px 4px 100px; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8);}
		</style>
		<div id="search_widget_shortcode" style="display:none;">
		  <div class="">
			<h3><?php _e('Customize the Predictive Search Shortcode', 'wpscps'); ?> <a class="add-new-h2 a3-view-docs-button" target="_blank" href="<?php echo WPSC_PREDICTIVE_SEARCH_DOCS_URI; ?>#section-16" ><?php _e('View Docs', 'wpscps'); ?></a></h3>
			<div style="clear:both"></div>
            <div id="wpsc_predictive_upgrade_area"><?php echo WPSC_Settings_Tab_Ps_Settings::predictive_extension_shortcode(); ?>
			<div class="field_content">
            	<?php foreach ($items_search_default as $key => $data) { ?>
                <p><label for="wpsc_search_<?php echo $key ?>_items"><?php echo $data['name']; ?>:</label> <input disabled="disabled" style="width:100px;" size="10" id="wpsc_search_<?php echo $key ?>_items" name="wpsc_search_<?php echo $key ?>_items" type="text" value="<?php echo $data['number'] ?>" /> <span class="description"><?php _e('Number of', 'wpscps'); echo ' '.$data['name'].' '; _e('results to show in dropdown', 'wpscps'); ?></span></p> 
                <?php } ?>
                <p><label for="wpsc_search_show_price"><?php _e('Price', 'wpscps'); ?>:</label> <input disabled="disabled" type="checkbox" checked="checked" id="wpsc_search_show_price" name="wpsc_search_show_price" value="1" /> <span class="description"><?php _e('Show Product prices', 'wpscps'); ?></span></p>
            	<p><label for="wpsc_search_text_lenght"><?php _e('Characters', 'wpscps'); ?>:</label> <input disabled="disabled" style="width:100px;" size="10" id="wpsc_search_text_lenght" name="wpsc_search_text_lenght" type="text" value="100" /> <span class="description"><?php _e('Number of product description characters', 'wpscps'); ?></span></p>
                <p><label for="wpsc_search_align"><?php _e('Alignment', 'wpscps'); ?>:</label> <select disabled="disabled" style="width:100px" id="wpsc_search_align" name="wpsc_search_align"><option value="none" selected="selected"><?php _e('None', 'wpscps'); ?></option><option value="left-wrap"><?php _e('Left - wrap', 'wpscps'); ?></option><option value="left"><?php _e('Left - no wrap', 'wpscps'); ?></option><option value="center"><?php _e('Center', 'wpscps'); ?></option><option value="right-wrap"><?php _e('Right - wrap', 'wpscps'); ?></option><option value="right"><?php _e('Right - no wrap', 'wpscps'); ?></option></select> <span class="description"><?php _e('Horizontal aliginment of search box', 'wpscps'); ?></span></p>
                <p><label for="wpsc_search_width"><?php _e('Search box width', 'wpscps'); ?>:</label> <input disabled="disabled" style="width:100px;" size="10" id="wpsc_search_width" name="wpsc_search_width" type="text" value="200" />px</p>
                <p><label for="wpsc_search_box_text"><?php _e('Search box text message', 'wpscps'); ?>:</label> <input disabled="disabled" style="width:300px;" size="10" id="wpsc_search_box_text" name="wpsc_search_box_text" type="text" value="<?php echo get_option('ecommerce_search_box_text'); ?>" /></p>
                <p><label for="wpsc_search_padding"><strong><?php _e('Padding', 'wpscps'); ?></strong>:</label><br /> 
				<label for="wpsc_search_padding_top" style="width:auto; float:none"><?php _e('Above', 'wpscps'); ?>:</label><input disabled="disabled" style="width:50px;" size="10" id="wpsc_search_padding_top" name="wpsc_search_padding_top" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="wpsc_search_padding_bottom" style="width:auto; float:none"><?php _e('Below', 'wpscps'); ?>:</label> <input disabled="disabled" style="width:50px;" size="10" id="wpsc_search_padding_bottom" name="wpsc_search_padding_bottom" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="wpsc_search_padding_left" style="width:auto; float:none"><?php _e('Left', 'wpscps'); ?>:</label> <input disabled="disabled" style="width:50px;" size="10" id="wpsc_search_padding_left" name="wpsc_search_padding_left" type="text" value="0" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="wpsc_search_padding_right" style="width:auto; float:none"><?php _e('Right', 'wpscps'); ?>:</label> <input disabled="disabled" style="width:50px;" size="10" id="wpsc_search_padding_right" name="wpsc_search_padding_right" type="text" value="0" />px
                </p>
			</div>
            <p>&nbsp;&nbsp;<input disabled="disabled" type="button" class="button-primary" value="<?php _e('Insert Shortcode', 'wpscps'); ?>" onclick="return alert_upgrade('<?php _e('Please upgrade to the Pro Version to activate Smart Search, sidebar widget controls and the advance search results page features with our limited', 'wpscps' ); ?>');"/>&nbsp;&nbsp;&nbsp;
            <a class="button" style="" href="#" onclick="tb_remove(); return false;"><?php _e('Cancel', 'wpscps'); ?></a>
			</p>
            </div>
		  </div>
		</div>
<?php
	}
	
	public static function parse_shortcode_search_result($attributes) {
    	return WPSC_Predictive_Search_Shortcodes::display_search();	
    }
	
	public static function get_product_price($product_id, $show_price=true) {}
	
	public static function get_product_price_dropdown($product_id) {
		$product_price_output = '';
			$variable_price = WPSC_Predictive_Search::get_product_variation_price_available($product_id);
			if ($variable_price > 0) {
				$variable_price = apply_filters( 'wpsc_do_convert_price', $variable_price );
				$args = array(
						'display_as_html' => false,
						'display_decimal_point' => true
				);
				$product_price_output = '<span class="rs_price">'.__('Priced from', 'wpscps').': ';
				$product_price_output .= '<span class="currentprice pricedisplay">'.wpsc_currency_display( $variable_price, $args ).'</span></span>';
			} else {
				$price = $full_price = get_post_meta( $product_id, '_wpsc_price', true );
		
				$special_price = get_post_meta( $product_id, '_wpsc_special_price', true );
			
				if ( ( $full_price > $special_price ) && ( $special_price > 0 ) )
					$price = $special_price;
			
				$price = apply_filters( 'wpsc_do_convert_price', $price );
				$full_price = apply_filters( 'wpsc_do_convert_price', $full_price );
				$args = array(
						'display_as_html' => false,
						'display_decimal_point' => true
				);
				if($price > 0){
					$product_price_output = '<span class="rs_price">'.__('Price', 'wpscps').': ';
					if ( ( $full_price > $special_price ) && ( $special_price > 0 ) )
						$product_price_output .= '<span class="oldprice">'.wpsc_currency_display( $full_price, $args ).'</span> ';
					$product_price_output .= '<span class="currentprice pricedisplay">'.wpsc_currency_display( $price, $args ).'</span></span>';
				}
			}
		
		return $product_price_output;
	}
	
	public static function get_product_addtocart($product_id, $show_addtocart=true) {}
	
	public static function get_product_categories($product_id, $show_categories=true) {}
	
	public static function get_product_tags($product_id, $show_tags=true) {}
	
	public static function display_search() {
		global $wp_query;
		global $wpdb;
		global $wpsc_predictive_id_excludes;
		$p = 0;
		$row = 5;
		$search_keyword = '';
		$cat_slug = '';
		$tag_slug = '';
		$extra_parameter = '';
		$show_price = false;
		$show_categories = false;
		$show_tags = false;
		
		if (isset($wp_query->query_vars['keyword'])) $search_keyword = stripslashes( strip_tags( urldecode( $wp_query->query_vars['keyword'] ) ) );
		else if (isset($_REQUEST['rs']) && trim($_REQUEST['rs']) != '') $search_keyword = stripslashes( strip_tags( $_REQUEST['rs'] ) );
		
		$start = $p * $row;
		$end_row = $row;
				
		if ($search_keyword != '') {
			$args = array( 's' => $search_keyword, 'numberposts' => $row+1, 'offset'=> $start, 'orderby' => 'predictive', 'order' => 'ASC', 'post_type' => 'wpsc-product', 'post_status' => 'publish', 'exclude' => $wpsc_predictive_id_excludes['exclude_products'], 'suppress_filters' => FALSE);
			
			$total_args = $args;
			$total_args['numberposts'] = -1;
			$total_args['offset'] = 0;
			
			//$search_all_products = get_posts($total_args);
									
			$search_products = get_posts($args);
						
			$html = '<p class="rs_result_heading">'.__('Showing all results for your search', 'wpscps').' | '.$search_keyword.'</p>';
			if ( $search_products && count($search_products) > 0 ){
					
				$html .= '<style type="text/css">
				.rs_result_heading{margin:15px 0;}
				.ajax-wait{display: none; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; background:url("'.WPSC_PS_IMAGES_URL.'/ajax-loader.gif") no-repeat center center #EDEFF4; opacity: 1;text-align:center;}
				.ajax-wait img{margin-top:14px;}
				.p_data,.r_data,.q_data{display:none;}
				.rs_date{color:#777;font-size:small;}
				.rs_result_row{width:100%;float:left;margin:0px 0 10px;padding :0px 0 10px; 6px;border-bottom:1px solid #c2c2c2;}
				.rs_result_row:hover{opacity:1;}
				.rs_rs_avatar{width:64px;margin-right:10px;overflow: hidden;float:left;text-align:center}
				.rs_rs_avatar img{width:100%;height:auto; padding:0 !important; margin:0 !important; border: none !important;}
				.rs_rs_name{margin-left:0px;}
				.rs_content{margin-left:74px;}
				.rs_more_result{display:none;width:240px;text-align:center;position:fixed;bottom:50%;left:50%;margin-left:-125px;background-color: black;opacity: .75;color: white;padding: 10px;border-radius:10px;-webkit-border-radius: 10px;-moz-border-radius: 10px}
				.rs_rs_price .oldprice{text-decoration:line-through; font-size:80%;}
				</style>';
				$html .= '<div class="rs_ajax_search_content">';
				$text_lenght = get_option('ecommerce_search_text_lenght');
				foreach ( $search_products as $product ) {
					$link_detail = get_permalink($product->ID);
					
					$avatar = WPSC_Predictive_Search::wpscps_get_product_thumbnail($product->ID,'product-thumbnails',64,64);
					
					$product_price_output = WPSC_Predictive_Search_Shortcodes::get_product_price($product->ID, $show_price);
						
					$product_cats_output = WPSC_Predictive_Search_Shortcodes::get_product_categories($product->ID, $show_categories);
					
					$product_tags_output = WPSC_Predictive_Search_Shortcodes::get_product_tags($product->ID, $show_tags);
					
					$product_description = WPSC_Predictive_Search::wpscps_limit_words( strip_tags( WPSC_Predictive_Search::strip_shortcodes( strip_shortcodes( $product->post_content ) ) ),$text_lenght,'...');
					if (trim($product_description) == '') $product_description = WPSC_Predictive_Search::wpscps_limit_words( strip_tags( WPSC_Predictive_Search::strip_shortcodes( strip_shortcodes( $product->post_excerpt ) ) ),$text_lenght,'...');
					
					$html .= '<div class="rs_result_row"><span class="rs_rs_avatar">'.$avatar.'</span><div class="rs_content"><a href="'.$link_detail.'"><span class="rs_rs_name">'.stripslashes( $product->post_title).'</span></a>'.$product_price_output.'<div class="rs_rs_description">'.$product_description.'</div>'.$product_cats_output.$product_tags_output.'</div></div>';
					
					$html .= '<div style="clear:both"></div>';
					$end_row--;
					if ($end_row < 1) break;
				}
				$html .= '</div>';
				if ( count($search_products) > $row ) {
					$wpscps_get_result_search_page = wp_create_nonce("wpscps-get-result-search-page");
					
					$html .= '<div id="search_more_rs"></div><div style="clear:both"></div><div id="rs_more_check"></div><div class="rs_more_result"><span class="p_data">'.($p + 1).'</span><img src="'.WPSC_PS_IMAGES_URL.'/more-results-loader.gif" /><div><em>'.__('Loading More Results...', 'wpscps').'</em></div></div>';
					$html .= "<script>jQuery(document).ready(function() {
var search_rs_obj = jQuery('#rs_more_check');
var is_loading = false;

function auto_click_more() {
	if (is_loading == false) {
		var visibleAtTop = search_rs_obj.offset().top + search_rs_obj.height() >= jQuery(window).scrollTop();
		var visibleAtBottom = search_rs_obj.offset().top <= jQuery(window).scrollTop() + jQuery(window).height();
		if (visibleAtTop && visibleAtBottom) {
			is_loading = true;
			jQuery('.rs_more_result').fadeIn('normal');
			var p_data_obj = jQuery('.rs_more_result .p_data');
			var p_data = p_data_obj.html();
			p_data_obj.html('');
			var urls = '&p='+p_data+'&row=".$row."&q=".$search_keyword.$extra_parameter."&action=wpscps_get_result_search_page&security=".$wpscps_get_result_search_page."';
			jQuery.post('". admin_url( 'admin-ajax.php', 'relative' ) ."', urls, function(theResponse){
				if(theResponse != ''){
					var num = parseInt(p_data)+1;
					p_data_obj.html(num);
					jQuery('#search_more_rs').append(theResponse);
					is_loading = false;
					jQuery('.rs_more_result').fadeOut('normal');
				}else{
					jQuery('.rs_more_result').html('<em>".__('No More Results to Show', 'wpscps')."</em>').fadeOut(2000);
				}
			});
			return false;
		}
	}
}
jQuery(window).scroll(function(){
	auto_click_more();
});
auto_click_more();
});</script>";
				}
			} else {
				$html .= '<p style="text-align:center">'.__('Nothing Found! Please refine your search and try again.', 'wpscps').'</p>';
			} 
			
			return $html;
		}
	}
	
	public static function get_result_search_page() {
		check_ajax_referer( 'wpscps-get-result-search-page', 'security' );
		add_filter( 'posts_search', array('WPSC_Predictive_Search_Hook_Filter', 'search_by_title_only'), 500, 2 );
		add_filter( 'posts_orderby', array('WPSC_Predictive_Search_Hook_Filter', 'predictive_posts_orderby'), 500, 2 );
		add_filter( 'posts_request', array('WPSC_Predictive_Search_Hook_Filter', 'posts_request_unconflict_role_scoper_plugin'), 500, 2);
		global $wpsc_predictive_id_excludes;
		$p = 1;
		$row = 5;
		$search_keyword = '';
		$cat_slug = '';
		$tag_slug = '';
		$extra_parameter = '';
		$show_price = false;
		$show_categories = false;
		$show_tags = false;
		if (isset($_REQUEST['p']) && $_REQUEST['p'] > 0) $p = stripslashes( strip_tags( $_REQUEST['p'] ) );
		if (isset($_REQUEST['row']) && $_REQUEST['row'] > 0) $row = stripslashes( strip_tags( $_REQUEST['row'] ) );
		if (isset($_REQUEST['q']) && trim($_REQUEST['q']) != '') $search_keyword = stripslashes( strip_tags( $_REQUEST['q'] ) );
		if (isset($_REQUEST['scat']) && trim($_REQUEST['scat']) != '') $cat_slug = stripslashes( strip_tags( $_REQUEST['scat'] ) );
		if (isset($_REQUEST['stag']) && trim($_REQUEST['stag']) != '') $tag_slug = stripslashes( strip_tags( $_REQUEST['stag'] ) );
		
		$start = $p * $row;
		$end = $start + $row;
		$end_row = $row;
		
		if ($search_keyword != '') {
			$args = array( 's' => $search_keyword, 'numberposts' => $row+1, 'offset'=> $start, 'orderby' => 'predictive', 'order' => 'ASC', 'post_type' => 'wpsc-product', 'post_status' => 'publish', 'exclude' => $wpsc_predictive_id_excludes['exclude_products'], 'suppress_filters' => FALSE);
			
			$total_args = $args;
			$total_args['numberposts'] = -1;
			$total_args['offset'] = 0;
			
			//$search_all_products = get_posts($total_args);
									
			$search_products = get_posts($args);
						
			$html = '';
			if ( $search_products && count($search_products) > 0 ){
				$html .= '<div class="rs_ajax_search_content">';
				$text_lenght = get_option('ecommerce_search_text_lenght');
				foreach ( $search_products as $product ) {
					$link_detail = get_permalink($product->ID);
					$avatar = WPSC_Predictive_Search::wpscps_get_product_thumbnail($product->ID,'product-thumbnails',64,64);
				
					$product_price_output = WPSC_Predictive_Search_Shortcodes::get_product_price($product->ID, $show_price);
						
					$product_cats_output = WPSC_Predictive_Search_Shortcodes::get_product_categories($product->ID, $show_categories);
					
					$product_tags_output = WPSC_Predictive_Search_Shortcodes::get_product_tags($product->ID, $show_tags);
					
					$product_description = WPSC_Predictive_Search::wpscps_limit_words( strip_tags( WPSC_Predictive_Search::strip_shortcodes( strip_shortcodes( $product->post_content ) ) ),$text_lenght,'...');
					if (trim($product_description) == '') $product_description = WPSC_Predictive_Search::wpscps_limit_words( strip_tags( WPSC_Predictive_Search::strip_shortcodes( strip_shortcodes( $product->post_excerpt ) ) ),$text_lenght,'...');
					
					$html .= '<div class="rs_result_row"><span class="rs_rs_avatar">'.$avatar.'</span><div class="rs_content"><a href="'.$link_detail.'"><span class="rs_rs_name">'.stripslashes( $product->post_title).'</span></a>'.$product_price_output.'<div class="rs_rs_description">'.$product_description.'</div>'.$product_cats_output.$product_tags_output.'</div></div>';
					$html .= '<div style="clear:both"></div>';
					$end_row--;
					if ($end_row < 1) break;
				}
				
				if ( count($search_products) <= $row ) {
					
					$html .= '';
				}
				
				$html .= '</div>';
			}
			echo $html;
		}
		die();
	}
}
?>