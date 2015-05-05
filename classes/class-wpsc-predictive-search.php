<?php
/**
 * WPSC Predictive Search
 *
 * Class Function into ecommerce plugin
 *
 * Table Of Contents
 *
 * set_setting()
 * plugins_loaded()
 * get_id_excludes()
 * wpscps_get_product_thumbnail()
 * wpscps_limit_words()
 * wpscps_get_result_popup()
 * create_page()
 * get_product_variation_price_available()
 * strip_shortcodes()
 * upgrade_version_2_0()
 */
class WPSC_Predictive_Search
{
	
	public static function plugins_loaded() {
		global $wpsc_predictive_id_excludes;
		
		WPSC_Predictive_Search::get_id_excludes();
	}
	
	public static function get_id_excludes() {
		global $wpsc_predictive_id_excludes;
		
		$exclude_products = get_option('ecommerce_search_exclude_products', '');
		if (is_array($exclude_products)) {
			$exclude_products = implode(",", $exclude_products);
		}
		
		$wpsc_predictive_id_excludes = array();
		$wpsc_predictive_id_excludes['exclude_products'] = $exclude_products;
		
		return $wpsc_predictive_id_excludes;
	}	
	
	public static function wpscps_get_product_thumbnail( $product_id, $size = 'product-thumbnails', $placeholder_width = 0, $placeholder_height = 0  ) {
		$image_url = '';
		$thumbnail_id = 0;
		if ( $placeholder_width == 0 )
			$placeholder_width = 64;
		if ( $placeholder_height == 0 )
			$placeholder_height = 64;
			// Use product thumbnail
		if ( has_post_thumbnail( $product_id ) ) {
				$thumbnail_id = get_post_thumbnail_id( $product_id  );
			// Use first product image
		} else {
		
				// Get all attached images to this product
				$attached_images = (array)get_posts( array(
					'post_type'   => 'attachment',
					'numberposts' => 1,
					'post_status' => null,
					'post_parent' => $product_id ,
					'orderby'     => 'menu_order',
					'order'       => 'ASC'
				) );
		
				if ( !empty( $attached_images ) )
					$thumbnail_id = $attached_images[0]->ID;
		}
			
		if ($thumbnail_id != 0) {
			$image_attribute = wp_get_attachment_image_src( $thumbnail_id, 'full');	
	
		
			$image_lager_default_url = $image_attribute[0];
			$width_old = $image_attribute[1];
			$height_old = $image_attribute[2];
			$g_thumb_width  = $placeholder_width;
			$g_thumb_height = $placeholder_height;
			$thumb_height = $g_thumb_height;
			$thumb_width = $g_thumb_width;
			if($width_old > $g_thumb_width){
				$factor = ($width_old / $g_thumb_width);
				$thumb_height = round($height_old / $factor);
			
				$intermediate_size = "wpsc-{$thumb_width}x{$thumb_height}";
				$image_meta = get_post_meta( $thumbnail_id, '' );
				
				// Clean up the meta array
				foreach ( $image_meta as $meta_name => $meta_value )
				$image_meta[$meta_name] = maybe_unserialize( array_pop( $meta_value ) );
				
				$attachment_metadata = $image_meta['_wp_attachment_metadata'];
				// Determine if we already have an image of this size
				if ( isset( $attachment_metadata['sizes'] ) && (count( $attachment_metadata['sizes'] ) > 0) && ( isset( $attachment_metadata['sizes'][$intermediate_size] ) ) ) {
					$intermediate_image_data = image_get_intermediate_size( $thumbnail_id, $intermediate_size );
					$uploads = wp_upload_dir();
					if ( $intermediate_image_data['path'] != '' && file_exists( $uploads['basedir'] . "/" .$intermediate_image_data['path'] ) ) {
						$image_url = $intermediate_image_data['url'];
					} else {
						$image_url = home_url( "index.php?wpsc_action=scale_image&amp;attachment_id={$thumbnail_id}&amp;width=$thumb_width&amp;height=$thumb_height" );
					}
				} else {
					$image_url = home_url( "index.php?wpsc_action=scale_image&amp;attachment_id={$thumbnail_id}&amp;width=$thumb_width&amp;height=$thumb_height" );
				}
			} else {
				$image_url = $image_lager_default_url;
			}
		}
			
		if (trim($image_url != '')) {
			return '<img src="' . $image_url . '" alt="" width="' . $placeholder_width . '" />';
		} else {
			return '<img src="'. WPSC_CORE_THEME_URL . 'wpsc-images/noimage.png" alt="Placeholder" width="' . $placeholder_width . '" height="' . $placeholder_height . '" />';
		}
	}
	
	public static function wpscps_limit_words($str='',$len=100,$more=true) {
	   if (trim($len) == '' || $len < 0) $len = 100;
	   if ( $str=="" || $str==NULL ) return $str;
	   if ( is_array($str) ) return $str;
	   $str = trim($str);
	   $str = strip_tags(str_replace("\r\n", "", $str));
	   if ( strlen($str) <= $len ) return $str;
	   $str = substr($str,0,$len);
	   if ( $str != "" ) {
			if ( !substr_count($str," ") ) {
					  if ( $more ) $str .= " ...";
					return $str;
			}
			while( strlen($str) && ($str[strlen($str)-1] != " ") ) {
					$str = substr($str,0,-1);
			}
			$str = substr($str,0,-1);
			if ( $more ) $str .= " ...";
			}
			return $str;
	}
	
	public static function wpscps_get_result_popup() {
		add_filter( 'posts_search', array('WPSC_Predictive_Search_Hook_Filter', 'search_by_title_only'), 500, 2 );
		add_filter( 'posts_orderby', array('WPSC_Predictive_Search_Hook_Filter', 'predictive_posts_orderby'), 500, 2 );
		add_filter( 'posts_request', array('WPSC_Predictive_Search_Hook_Filter', 'posts_request_unconflict_role_scoper_plugin'), 500, 2);
		global $wpdb;
		global $wpsc_predictive_id_excludes;
		$row = 5;
		$text_lenght = 100;
		$show_price = 1;
		$search_keyword = '';
		$cat_slug = '';
		$tag_slug = '';
		$extra_parameter = '';
		if (isset($_REQUEST['row']) && $_REQUEST['row'] > 0) $row = stripslashes( strip_tags( $_REQUEST['row'] ) );
		if (isset($_REQUEST['text_lenght']) && $_REQUEST['text_lenght'] >= 0) $text_lenght = stripslashes( strip_tags( $_REQUEST['text_lenght'] ) );
		if (isset($_REQUEST['show_price']) && trim($_REQUEST['show_price']) != '') $show_price = stripslashes( strip_tags( $_REQUEST['show_price'] ) );
		if (isset($_REQUEST['q']) && trim($_REQUEST['q']) != '') $search_keyword = stripslashes( strip_tags( $_REQUEST['q'] ) );
		
		$end_row = $row;
		
		if ($search_keyword != '') {
			$args = array( 's' => $search_keyword, 'numberposts' => $row+1, 'offset'=> 0, 'orderby' => 'predictive', 'order' => 'ASC', 'post_type' => 'wpsc-product', 'post_status' => 'publish', 'exclude' => $wpsc_predictive_id_excludes['exclude_products'], 'suppress_filters' => FALSE);
			
			$total_args = $args;
			$total_args['numberposts'] = -1;
			
			//$search_all_products = get_posts($total_args);
			
			$search_products = get_posts($args);
						
			if ( $search_products && count($search_products) > 0 ) {
				echo "<div class='ajax_search_content_title'>".__('Products', 'wpscps')."</div>[|]#[|]$search_keyword\n";
				foreach ( $search_products as $product ) {
					$link_detail = get_permalink($product->ID);
					$avatar = WPSC_Predictive_Search::wpscps_get_product_thumbnail($product->ID,'product-thumbnails',64,64);
					$product_description = WPSC_Predictive_Search::wpscps_limit_words(strip_tags( WPSC_Predictive_Search::strip_shortcodes( strip_shortcodes( str_replace("\n", "", $product->post_content) ) ) ),$text_lenght,'...');
					if (trim($product_description) == '') $product_description = WPSC_Predictive_Search::wpscps_limit_words(strip_tags( WPSC_Predictive_Search::strip_shortcodes( strip_shortcodes( str_replace("\n", "", $product->post_excerpt) ) ) ),$text_lenght,'...');
					
					$price_html = '';
					if ( $show_price == 1)
						$price_html = WPSC_Predictive_Search_Shortcodes::get_product_price_dropdown($product->ID);
					
					$item = '<div class="ajax_search_content"><div class="result_row"><a href="'.$link_detail.'"><span class="rs_avatar">'.$avatar.'</span><div class="rs_content_popup"><span class="rs_name">'.stripslashes( $product->post_title).'</span>'.$price_html.'<span class="rs_description">'.$product_description.'</span></div></a></div></div>';
					echo $item.'[|]'.$link_detail.'[|]'.stripslashes( $product->post_title)."\n";
					$end_row--;
					if ($end_row < 1) break;
				}
				$rs_item = '';
				if ( count($search_products) > $row ) {
					if (get_option('permalink_structure') == '')
						$link_search = get_permalink(get_option('ecommerce_search_page_id')).'&rs='. urlencode($search_keyword) .$extra_parameter;
					else
						$link_search = rtrim( get_permalink(get_option('ecommerce_search_page_id')), '/' ).'/keyword/'. urlencode($search_keyword) .$extra_parameter;
					$rs_item .= '<div class="more_result" rel="more_result"><a href="'.$link_search.'">'.__('See more results for', 'wpscps').' '.$search_keyword.' <span class="see_more_arrow"></span></a><span>'.__('Displaying top', 'wpscps').' '.$row.' '.__('results', 'wpscps').'</span></div>';
					echo $rs_item.'[|]'.$link_search.'[|]'.$search_keyword."\n";
				}
			} else {
				echo '<div class="ajax_no_result">'.__('Nothing found for that name. Try a different spelling or name.', 'wpscps').'</div>';
			}
		}
		die();
	}
	
	public static function create_page( $slug, $option, $page_title = '', $page_content = '', $post_parent = 0 ) {
		global $wpdb;
		 
		$option_value = get_option($option); 
		 
		if ( $option_value > 0 && get_post( $option_value ) ) 
			return;
		
		$page_found = $wpdb->get_var("SELECT ID FROM " . $wpdb->posts . " WHERE post_name = '$slug' LIMIT 1;");
		if ( $page_found ) :
			if ( ! $option_value ) 
				update_option( $option, $page_found );
			return;
		endif;
		
		$page_data = array(
			'post_status' 		=> 'publish',
			'post_type' 		=> 'page',
			'post_author' 		=> 1,
			'post_name' 		=> $slug,
			'post_title' 		=> $page_title,
			'post_content' 		=> $page_content,
			'post_parent' 		=> $post_parent,
			'comment_status' 	=> 'closed'
		);
		$page_id = wp_insert_post( $page_data );
		
		update_option( $option, $page_id );
	}
	
	public static function get_product_variation_price_available($product_id){
		global $wpdb;
		
		$joins = array(
			"INNER JOIN {$wpdb->postmeta} AS pm ON pm.post_id = p.id AND pm.meta_key = '_wpsc_price'",
		);
	
		$selects = array(
			'pm.meta_value AS price',
		);
	
		$joins[] = "INNER JOIN {$wpdb->postmeta} AS pm2 ON pm2.post_id = p.id AND pm2.meta_key = '_wpsc_special_price'";
		$selects[] = 'pm2.meta_value AS special_price';
	
		$joins = implode( ' ', $joins );
		$selects = implode( ', ', $selects );
	
		$sql = $wpdb->prepare( "
			SELECT {$selects}
			FROM {$wpdb->posts} AS p
			{$joins}
			WHERE
				p.post_type = 'wpsc-product'
				AND
				p.post_parent = %d
		", $product_id );
	
		$results = $wpdb->get_results( $sql );
		$prices = array();
	
		foreach ( $results as $row ) {
			$price = (float) $row->price;
			$special_price = (float) $row->special_price;
			if ( $special_price != 0 && $special_price < $price )
				$price = $special_price;
			$prices[] = $price;
		}
	
		sort( $prices );
		if (count($prices) > 0)
			$price = apply_filters( 'wpsc_do_convert_price', $prices[0] );
		else 
			$price = 0;
				
		return $price;
	}
	
	public static function strip_shortcodes ($content='') {
		$content = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $content);
		
		return $content;
	}
	
	public static function plugin_extension() {
		$html = '';
		$html .= '<a href="http://a3rev.com/shop/" target="_blank" style="float:right;margin-top:5px; margin-left:10px;" ><div class="a3-plugin-ui-icon a3-plugin-ui-a3-rev-logo"></div></a>';
		$html .= '<h3>'.__('Upgrade to Predictive Search Pro', 'wpscps').'</h3>';
		$html .= '<p>'.__("<strong>NOTE:</strong> All the functions inside the Yellow border on the plugins admin panel are extra functionality that is activated by upgrading to the Pro version", 'wpscps').':</p>';
		$html .= '<h3>* <a href="'.WPSC_PS_AUTHOR_URI.'" target="_blank">'.__('WPEC Predictive Search Pro', 'wpscps').'</a> '.__('Features', 'wpscps').'</h3>';
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
		$html .= '<li>9. '.__("Multi Lingual Support. Fully compatible with WPML", 'wpscps').'</li>';
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
		$html .= '<li>* <a href="http://wordpress.org/plugins/a3-responsive-slider/" target="_blank">'.__('a3 Responsive Slider', 'wpscps').'</a>&nbsp;&nbsp;&nbsp;'.__( 'New Release!' , 'wpscps' ).'</li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/contact-us-page-contact-people/" target="_blank">'.__('Contact Us Page - Contact People', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-email-template/" target="_blank">'.__('WordPress Email Template', 'wpscps').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/page-views-count/" target="_blank">'.__('Page View Count', 'wpscps').'</a></li>';
		return $html;
	}
	
	public static function predictive_extension_shortcode() {
		$html = '';
		$html .= '<div id="wpsc_predictive_extensions">'.__("Yes you'll love the Predictive Search shortcode feature. Upgrading to the", 'wpscps').' <a target="_blank" href="'.WPSC_PS_AUTHOR_URI.'">'.__('Pro Version', 'wpscps').'</a> '.__("activates this shortcode feature as well as the awesome 'Smart Search' feature, per widget controls, the All Search Results page customization settings and function features.", 'wpscps').'</div>';
		return $html;	
	}
	
	public static function upgrade_version_2_0() {
		$exclude_products = get_option('ecommerce_search_exclude_products', '');
		
		if ($exclude_products !== false) {
			$exclude_products_array = explode(",", $exclude_products);
			if (is_array($exclude_products_array) && count($exclude_products_array) > 0) {
				$exclude_products_array_new = array();
				foreach ($exclude_products_array as $exclude_products_item) {
					if ( trim($exclude_products_item) > 0) $exclude_products_array_new[] = trim($exclude_products_item);
				}
				$exclude_products = $exclude_products_array_new;
			} else {
				$exclude_products = array();
			}
			update_option('ecommerce_search_exclude_products', (array) $exclude_products);
		} else {
			update_option('ecommerce_search_exclude_products', array());
		}
	}
}
?>