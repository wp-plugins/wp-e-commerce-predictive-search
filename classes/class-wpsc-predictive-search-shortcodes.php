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
 * wpscps_display_search()
 * wpscps_get_result_search_page()
 */
class WPSC_Predictive_Search_Shortcodes {
	public static function parse_shortcode_search_widget($attributes) {}
	
	function add_search_widget_icon($context){
		$image_btn = WPSC_PS_IMAGES_URL . "/ps_icon.png";
		$out = '<a href="#TB_inline?width=670&height=650&modal=false&inlineId=search_widget_shortcode" class="thickbox" title="'.__('Insert WP e-Commerce Predictive Search Shortcode', 'wpscps').'"><img class="search_widget_shortcode_icon" src="'.$image_btn.'" alt="'.__('Insert WP e-Commerce Predictive Search Shortcode', 'wpscps').'" /></a>';
		return $context . $out;
	}
	
	//Action target that displays the popup to insert a form to a post/page
	function add_search_widget_mce_popup(){
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
		.field_content {
			padding:0 0 0 40px;
		}
		.field_content label{
			width:150px;
			float:left;
			text-align:left;
		}
		#wpsc_predictive_upgrade_area { border:2px solid #FF0;-webkit-border-radius:10px;-moz-border-radius:10px;-o-border-radius:10px; border-radius: 10px; padding:0; position:relative}
	  	#wpsc_predictive_upgrade_area h3{ margin-left:10px;}
	   	#wpsc_predictive_extensions { background: url("<?php echo WPSC_PS_IMAGES_URL; ?>/logo_a3blue.png") no-repeat scroll 4px 6px #FFFBCC; -webkit-border-radius:4px;-moz-border-radius:4px;-o-border-radius:4px; border-radius: 4px 4px 4px 4px; color: #555555; float: right; margin: 0px; padding: 4px 8px 4px 38px; position: absolute; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8); width: 260px; right:10px; top:140px; border:1px solid #E6DB55}
		</style>
		<div id="search_widget_shortcode" style="display:none;">
		  <div class="">
			<h3><?php _e('Customize the Predictive Search Shortcode', 'wpscps'); ?></h3>
			<div style="clear:both"></div>
            <div id="wpsc_predictive_upgrade_area"><?php echo WPSC_Settings_Tab_Ps_Settings::predictive_extension_shortcode(); ?>
			<div class="field_content">
            	<p><label for="wpsc_search_number_items"><?php _e('Results', 'wpscps'); ?>:</label> <input disabled="disabled" style="width:100px;" size="10" id="wpsc_search_number_items" name="wpsc_search_number_items" type="text" value="6" /> <span class="description"><?php _e('Number of results to show in dropdown', 'wpscps'); ?></span></p>
            	<p><label for="wpsc_search_text_lenght"><?php _e('Characters', 'wpscps'); ?>:</label> <input disabled="disabled" style="width:100px;" size="10" id="wpsc_search_text_lenght" name="wpsc_search_text_lenght" type="text" value="100" /> <span class="description"><?php _e('Number of product description characters', 'wpscps'); ?></span></p>
                <p><label for="wpsc_search_align"><?php _e('Alignment', 'wpscps'); ?>:</label> <select disabled="disabled" style="width:100px" id="wpsc_search_align" name="wpsc_search_align"><option value="none" selected="selected"><?php _e('None', 'wpscps'); ?></option><option value="left-wrap"><?php _e('Left - wrap', 'wpscps'); ?></option><option value="left"><?php _e('Left - no wrap', 'wpscps'); ?></option><option value="center"><?php _e('Center', 'wpscps'); ?></option><option value="right-wrap"><?php _e('Right - wrap', 'wpscps'); ?></option><option value="right"><?php _e('Right - no wrap', 'wpscps'); ?></option></select> <span class="description"><?php _e('Horizontal aliginment of search box', 'wpscps'); ?></span></p>
                <p><label for="wpsc_search_width"><?php _e('Search box width', 'wpscps'); ?>:</label> <input disabled="disabled" style="width:100px;" size="10" id="wpsc_search_width" name="wpsc_search_width" type="text" value="200" /> px</p>
                <p><label for="wpsc_search_padding_top"><?php _e('Padding - Above', 'wpscps'); ?>:</label> <input disabled="disabled" style="width:100px;" size="10" id="wpsc_search_padding_top" name="wpsc_search_padding_top" type="text" value="10" /> px</p>
                <p><label for="wpsc_search_padding_bottom"><?php _e('Padding - Below', 'wpscps'); ?>:</label> <input disabled="disabled" style="width:100px;" size="10" id="wpsc_search_padding_bottom" name="wpsc_search_padding_bottom" type="text" value="10" /> px</p>
                <p><label for="wpsc_search_padding_left"><?php _e('Padding - Left', 'wpscps'); ?>:</label> <input disabled="disabled" style="width:100px;" size="10" id="wpsc_search_padding_left" name="wpsc_search_padding_left" type="text" value="0" /> px</p>
                <p><label for="wpsc_search_padding_right"><?php _e('Padding - Right', 'wpscps'); ?>:</label> <input disabled="disabled" style="width:100px;" size="10" id="wpsc_search_padding_right" name="wpsc_search_padding_right" type="text" value="0" /> px</p>
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
    	return WPSC_Predictive_Search_Shortcodes::wpscps_display_search();	
    }
	
	function get_product_price($product_id, $show_price=true) {}
	
	function get_product_categories($product_id, $show_categories=true) {}
	
	function get_product_tags($product_id, $show_tags=true) {}
	
	function wpscps_display_search() {
		$p = 0;
		$row = 5;
		$search_keyword = '';
		$cat_slug = '';
		$tag_slug = '';
		$extra_parameter = '';
		$show_price = false;
		$show_categories = false;
		$show_tags = false;
		if (isset($_REQUEST['rs']) && trim($_REQUEST['rs']) != '') $search_keyword = $_REQUEST['rs'];
		
		$start = $p * $row;
		$end_row = $row;
				
		if ($search_keyword != '') {
			$args = array( 's' => $search_keyword, 'numberposts' => $row+1, 'offset'=> $start, 'orderby' => 'title', 'order' => 'ASC', 'post_type' => 'wpsc-product', 'post_status' => 'publish');
			
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
				.rs_more_result{width:89.5%;float:left;padding:10px 5%;text-align:center;margin:10px 0;background: #EDEFF4;border: 1px solid #D8DFEA;position:relative;}
				.rs_rs_price .oldprice{text-decoration:line-through; font-size:80%;}
				</style>';
				$html .= '<div class="rs_ajax_search_content">';
				foreach ( $search_products as $product ) {
					$link_detail = get_permalink($product->ID);
					
					$avatar = WPSC_Predictive_Search::wpscps_get_product_thumbnail($product->ID,'product-thumbnails',64,64);
					
					$product_price_output = WPSC_Predictive_Search_Shortcodes::get_product_price($product->ID, $show_price);
						
					$product_cats_output = WPSC_Predictive_Search_Shortcodes::get_product_categories($product->ID, $show_categories);
					
					$product_tags_output = WPSC_Predictive_Search_Shortcodes::get_product_tags($product->ID, $show_tags);
					
					$html .= '<div class="rs_result_row"><span class="rs_rs_avatar">'.$avatar.'</span><div class="rs_content"><a href="'.$link_detail.'"><span class="rs_rs_name">'.stripslashes( $product->post_title).'</span></a>'.$product_price_output.'<div class="rs_rs_description">'.WPSC_Predictive_Search::wpscps_limit_words($product->post_content,get_option('ecommerce_search_text_lenght'),'...').'</div>'.$product_cats_output.$product_tags_output.'</div></div>';
					
					$html .= '<div style="clear:both"></div>';
					$end_row--;
					if ($end_row < 1) break;
				}
				$html .= '</div>';
				if ( count($search_products) > $row ) {
					$wpscps_get_result_search_page = wp_create_nonce("wpscps-get-result-search-page");
					
					$html .= '<div id="search_more_rs"></div><div style="clear:both"></div><div class="rs_more_result"><span class="p_data">'.($p + 1).'</span><a class="see_more" href="#">'.__('See more results', 'wpscps').' <span>↓</span></a>
					<div class="ajax-wait">&nbsp;</div></div>';
					$html .= "<script>jQuery(document).ready(function() {
						
						jQuery('.see_more').live('click',function(){
							var wait = jQuery('.rs_more_result .ajax-wait');
							wait.css('display','block');
							var p_data_obj = jQuery(this).siblings('.p_data');
							var p_data = jQuery(this).siblings('.p_data').html();
							var urls = '&p='+p_data+'&row=".$row."&q=".$search_keyword.$extra_parameter."&action=wpscps_get_result_search_page&security=".$wpscps_get_result_search_page."';
							jQuery.post('".admin_url('admin-ajax.php')."', urls, function(theResponse){
								if(theResponse != ''){
									var num = parseInt(p_data)+1;
									p_data_obj.html(num);
									jQuery('#search_more_rs').append(theResponse);
								}else{
									jQuery('.rs_more_result').html('').hide();
								}
								wait.css('display','none');
							});
							return false;
						});});</script>";
				}
			} else {
				$html .= '<p style="text-align:center">'.__('No result', 'wpscps').'</p>';
			} 
			
			return $html;
		}
	}
	
	function wpscps_get_result_search_page() {
		check_ajax_referer( 'wpscps-get-result-search-page', 'security' );
		$p = 1;
		$row = 5;
		$search_keyword = '';
		$cat_slug = '';
		$tag_slug = '';
		$extra_parameter = '';
		$show_price = false;
		$show_categories = false;
		$show_tags = false;
		if (isset($_REQUEST['p']) && $_REQUEST['p'] > 0) $p = $_REQUEST['p'];
		if (isset($_REQUEST['row']) && $_REQUEST['row'] > 0) $row = $_REQUEST['row'];
		if (isset($_REQUEST['q']) && trim($_REQUEST['q']) != '') $search_keyword = $_REQUEST['q'];
		if (isset($_REQUEST['scat']) && trim($_REQUEST['scat']) != '') $cat_slug = $_REQUEST['scat'];
		if (isset($_REQUEST['stag']) && trim($_REQUEST['stag']) != '') $tag_slug = $_REQUEST['stag'];
		
		$start = $p * $row;
		$end = $start + $row;
		$end_row = $row;
		
		if ($search_keyword != '') {
			$args = array( 's' => $search_keyword, 'numberposts' => $row+1, 'offset'=> $start, 'orderby' => 'title', 'order' => 'ASC', 'post_type' => 'wpsc-product', 'post_status' => 'publish');
			
			$total_args = $args;
			$total_args['numberposts'] = -1;
			$total_args['offset'] = 0;
			
			//$search_all_products = get_posts($total_args);
									
			$search_products = get_posts($args);
						
			$html = '';
			if ( $search_products && count($search_products) > 0 ){
				$html .= '<div class="rs_ajax_search_content">';
				foreach ( $search_products as $product ) {
					$link_detail = get_permalink($product->ID);
					$avatar = WPSC_Predictive_Search::wpscps_get_product_thumbnail($product->ID,'product-thumbnails',64,64);
				
					$product_price_output = WPSC_Predictive_Search_Shortcodes::get_product_price($product->ID, $show_price);
						
					$product_cats_output = WPSC_Predictive_Search_Shortcodes::get_product_categories($product->ID, $show_categories);
					
					$product_tags_output = WPSC_Predictive_Search_Shortcodes::get_product_tags($product->ID, $show_tags);
					
					$html .= '<div class="rs_result_row"><span class="rs_rs_avatar">'.$avatar.'</span><div class="rs_content"><a href="'.$link_detail.'"><span class="rs_rs_name">'.stripslashes( $product->post_title).'</span></a>'.$product_price_output.'<div class="rs_rs_description">'.WPSC_Predictive_Search::wpscps_limit_words($product->post_content,get_option('ecommerce_search_text_lenght'),'...').'</div>'.$product_cats_output.$product_tags_output.'</div></div>';
					$html .= '<div style="clear:both"></div>';
					$end_row--;
					if ($end_row < 1) break;
				}
				
				if ( count($search_products) <= $row ) {
					
					$html .= '<style>.rs_more_result{display:none;}</style>';
				}
				
				$html .= '</div>';
			}
			echo $html;
		}
		die();
	}
}
?>
