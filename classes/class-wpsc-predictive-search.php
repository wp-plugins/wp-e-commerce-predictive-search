<?php
/**
 * WPSC Predictive Search
 *
 * Class Function into ecommerce plugin
 *
 * Table Of Contents
 *
 * wpscps_get_product_thumbnail()
 * wpscps_limit_words()
 * wpscps_get_result_popup()
 * create_page()
 * get_product_variation_price_available()
 */
class WPSC_Predictive_Search{
	function wpscps_get_product_thumbnail( $post_id, $size = 'product-thumbnails', $placeholder_width = 0, $placeholder_height = 0  ) {
		global $ecommerce;
		$mediumSRC = '';
		if ( $placeholder_width == 0 )
			$placeholder_width = $ecommerce->get_image_size( 'product_image_width' );
		if ( $placeholder_height == 0 )
			$placeholder_height = $ecommerce->get_image_size( 'product_image_height' );
		
		if ( has_post_thumbnail($post_id) ) {
			return get_the_post_thumbnail( $post_id, $size ); 
		}
		
		if (trim($mediumSRC == '')) {
			$args = array( 'post_parent' => $post_id ,'numberposts' => 1, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'DESC', 'orderby' => 'ID', 'post_status' => null); 
			$attachments = get_posts($args);
			if ($attachments) {
				foreach ( $attachments as $attachment ) {
					$mediumSRC = wp_get_attachment_image( $attachment->ID, $size, true );
					break;
				}
			}
		}
		
		if (trim($mediumSRC == '')) {
			// Load the product
			$product = get_post( $post_id );
			
			// Get ID of parent product if one exists
			if ( !empty( $product->post_parent ) )
				$post_id = $product->post_parent;
				
			if (has_post_thumbnail($post_id)) {
				return get_the_post_thumbnail( $post_id, $size ); 
			}
			
			if (trim($mediumSRC == '')) {
				$args = array( 'post_parent' => $post_id ,'numberposts' => 1, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'DESC', 'orderby' => 'ID', 'post_status' => null); 
				$attachments = get_posts($args);
				if ($attachments) {
					foreach ( $attachments as $attachment ) {
						$mediumSRC = wp_get_attachment_image( $attachment->ID, $size, true );
						break;
					}
				}
			}
		}
		
		if (trim($mediumSRC != '')) {
			return $mediumSRC;
		} else {
			return '<img src="'. WPSC_CORE_THEME_URL . 'wpsc-images/noimage.png" alt="Placeholder" width="' . $placeholder_width . '" height="' . $placeholder_height . '" />';
		}
	}
	
	function wpscps_limit_words($str='',$len=100,$more=true) {
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
	
	function wpscps_get_result_popup() {
		check_ajax_referer( 'wpscps-get-result-popup', 'security' );
		$row = 6;
		$text_lenght = 100;
		$search_keyword = '';
		$cat_slug = '';
		$tag_slug = '';
		$extra_parameter = '';
		if (isset($_REQUEST['q']) && trim($_REQUEST['q']) != '') $search_keyword = $_REQUEST['q'];
		
		$end_row = $row;
		
		if ($search_keyword != '') {
			$args = array( 's' => $search_keyword, 'numberposts' => $row+1, 'offset'=> 0, 'orderby' => 'title', 'order' => 'ASC', 'post_type' => 'wpsc-product', 'post_status' => 'publish');
			if ($cat_slug != '') {
				$args['tax_query'] = array( array('taxonomy' => 'wpsc_product_category', 'field' => 'slug', 'terms' => $cat_slug) );
				$extra_parameter .= '&scat='.$cat_slug;
			} elseif($tag_slug != '') {
				$args['tax_query'] = array( array('taxonomy' => 'product_tag', 'field' => 'slug', 'terms' => $tag_slug) );
				$extra_parameter .= '&stag='.$tag_slug;
			}
			$total_args = $args;
			$total_args['numberposts'] = -1;
			
			//$search_all_products = get_posts($total_args);
			
			$search_products = get_posts($args);
						
			if ( $search_products && count($search_products) > 0 ) {
				echo "<div class='ajax_search_content_title'>".__('Products', 'wpscps')."</div>|#|$search_keyword\n";
				foreach ( $search_products as $product ) {
					$link_detail = get_permalink($product->ID);
					$avatar = WPSC_Predictive_Search::wpscps_get_product_thumbnail($product->ID,'product-thumbnails',64,64);
					$item = '<div class="ajax_search_content"><div class="result_row"><a href="'.$link_detail.'"><span class="rs_avatar">'.$avatar.'</span><div class="rs_content_popup"><span class="rs_name">'.stripslashes( $product->post_title).'</span><span class="rs_description">'.WPSC_Predictive_Search::wpscps_limit_words(strip_tags($product->post_content),$text_lenght,'...').'</span></div></a></div></div>';
					echo "$item|$link_detail|".stripslashes( $product->post_title)."\n";
					$end_row--;
					if ($end_row < 1) break;
				}
				$rs_item = '';
				if ( count($search_products) > $row ) {
					$link_search = get_permalink(get_option('ecommerce_search_page_id')).'?rs='.$search_keyword.$extra_parameter;
					$rs_item .= '<div class="more_result"><a href="'.$link_search.'">'.__('See more results for', 'wpscps').' '.$search_keyword.' <span class="see_more_arrow"></span></a><span>'.__('Displaying top', 'wpscps').' '.$row.' '.__('results', 'wpscps').'</span></div>';
					echo "$rs_item|$link_search|$search_keyword\n";
				}
			} else {
				echo '<div class="ajax_no_result">'.__('Keep typing...', 'wpscps').'</div>';
			}
		}
		die();
	}
	
	function create_page( $slug, $option, $page_title = '', $page_content = '', $post_parent = 0 ) {
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
	
	function get_product_variation_price_available($product_id){
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
		$price = apply_filters( 'wpsc_do_convert_price', $prices[0] );
				
		return $price;
	}
}
?>
