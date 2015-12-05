<?php
/**
 * functionality of the plugin.
 * @author     Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class Estimated_Dispatch_Date_For_WooCommerce_Functions {
	public function __construct() {
		add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'add_est_date' ) ,30,2 );
		add_action( 'woocommerce_cart_totals_after_order_total', array( $this, 'eddwc_display_order' ) );
		add_action( 'woocommerce_review_order_before_shipping' , array( $this, 'eddwc_display_order' ) );
		add_action( 'woocommerce_checkout_update_order_meta' , array( $this, 'eddwc_add_order_meta' ) , 2 );
	}
	
	public function add_est_date(){
		if ( ! isset($post_id) ) {
			global $post , $product;
			$post_id = $post->ID;
		}
		
		$type = eddwc_option('display_type');
		$days = $this->get_product_est_count($post_id);
		$prod_label = '';
 		$eddwc_prod_date = '';
		$function_to_call = 'eddwc_product_'.$type;
		$prod_label = $this->$function_to_call($days);
		$prod_label = str_replace('[br]', '<br />', $prod_label);
		echo '<p class="cuzd-days-s">' . $prod_label . '</p>';		
	}
	
	
	public function eddwc_product_general_date($days){
		$eddwc_range = explode(',' , $days);
		$general_options = eddwc_option('product_general_date_settings');
		if ($eddwc_range[0] == $eddwc_range[1]) {
			$days = $eddwc_range[0];
		} else {
			if (isset($general_options['actual_date'])){
				$days = $this->eddwc_get_dispatch_date($eddwc_range[0]).' - '.$this->eddwc_get_dispatch_date($eddwc_range[1]);
			} else {
				$days = $eddwc_range[0].' - '.$eddwc_range[1];								
			}
		}

		$eddwc_prod_field = eddwc_option('product_general_title');
		$prod_label = str_replace('[range]', $days, $eddwc_prod_field);	
		return $prod_label;
	}
	
	/**
	 * Generates Actual Date
	 */
	public function eddwc_product_actual_date($days){
		$eddwc_prod_date = $this->eddwc_get_dispatch_date($days);
		$field = eddwc_option('product_actual_title') ;
		$prod_label = str_replace('[date]', $eddwc_prod_date , $eddwc_prod_field);
		return $prod_label;
	}

	
	public function eddwc_product_average_date($days){
		$eddwc_days = array('day','days');
		if ($days > 1){
			$day = $eddwc_days[0];
		} else {
			$day = $eddwc_days[1];
		}
		
		$field = eddwc_option('product_average_title');
		$prod_label = str_replace('[number]', $days , $field);
		$prod_label = str_replace('[days]', $day , $prod_label);
		return $prod_label;
	}
	
		
	private function eddwc_get_dispatch_date($date) { 
		$eddwc_workdays = eddwc_option('operation_days');
		$cutOff = eddwc_option('day_cutoftime');
		list($cut_hrs,$cut_min) = explode(':',$cutOff);
		$cut_hrs = intval($cut_hrs);
		$cut_min = intval($cut_min);

		$wp_timezone_string = get_option('timezone_string');
		$wp_timezone_offset = get_option('gmt_offset');

		if ($wp_timezone_string) {
			$eddwc_timezone = $wp_timezone_string;
		} else {
			$eddwc_timezone = ini_get('date.timezone');
		}
		date_default_timezone_set($eddwc_timezone);

		$eddwc_date = new DateTime;
		$eddwc_cut_off = $cutOff;
		$eddwc_time = clone $eddwc_date;
		$eddwc_time->setTime($cut_hrs,$cut_min);

		$eddwc_next_date = clone $eddwc_date;

		if ($eddwc_date >= $eddwc_time){
			$eddwc_next_date->modify('+1 day');				
		}

		$i = 0;
		while ($i < $date){
			$eddwc_next_date->modify('+1 day');
			$ndate = strtolower($eddwc_next_date->format('D'));
			if (in_array($ndate, $eddwc_workdays)) {
				$i++;
				//if (in_array($eddwc_next_date->format('d-m-Y'), $eddwc_holidays) == false) {
				//	$i++;
				//}
			}

		}
		return $eddwc_next_date->format(eddwc_option('date_display_format'));
	}	
	
	
	public function eddwc_display_order(){
		$where_to_show = eddwc_option('where_to_display');
		$general_options = eddwc_option('product_general_date_settings');
		if (isset($general_options['range_date_checkout'])){
			$cuzd_current_cart = $this->eddwc_cart_max_range();
		} else {
			$cuzd_current_cart = $this->eddwc_get_dispatch_date($this->cuzd_get_cart_max_date());
		}
		
		if ( isset($where_to_show['order_page'])){				
			if ($cuzd_current_cart != ''){				
				echo '<tr><th>' . esc_attr(eddwc_option('order_page_title')) . '</th><td>' . $cuzd_current_cart . '</td></tr>';
			}
		}
	}
	
	function eddwc_cart_max_range(){
		global $woocommerce;
		$items = $woocommerce->cart->get_cart();
		$cuzd_range_date = '';
		$cuzd_min_range = array();
		$cuzd_max_range = array();
		$general_options = eddwc_option('product_general_date_settings');
		
		foreach($items as $item => $values) {
			if (!empty($values['variation_id'])){
				$item_id = $values['variation_id'];
			} else {
				$item_id = $values['product_id'];
			}
			if (get_post_meta($item_id , 'cuzd-prod-general-v' , true ) != '') {
				$item_range = get_post_meta($item_id , 'cuzd-prod-general-v' , true );
			} else {
				if (get_post_meta($item_id , 'cuzd-prod-general-s' , true ) != '') {
					$item_range = get_post_meta($item_id , 'cuzd-prod-general-s' , true );
				}
			}
			$item_range = explode(',',$item_range);
			$cuzd_min_range[] = $item_range[0];
			$cuzd_max_range[] = $item_range[1];
		}
		asort($cuzd_min_range);
		arsort($cuzd_max_range);
		if (isset($general_options['actual_date'])){
			$cuzd_range_date = 	$this->eddwc_get_dispatch_date($cuzd_min_range[0]).' - '.$this->eddwc_get_dispatch_date($cuzd_max_range[0]);
		} else {
			$cuzd_range_date = 	$cuzd_min_range[0].' - '.$cuzd_max_range[0].' '.eddwc_option('general_range_title');
		}

		return $cuzd_range_date;
	}	

	
	public function eddwc_add_order_meta($order_id){
		$general_options = eddwc_option('product_general_date_settings');
		if (isset($general_options['actual_date'])){
			update_post_meta( $order_id, '_eddwc_order_range', $this->cuzd_get_cart_max_range());
		}
		update_post_meta( $order_id, '_eddwc_order_date', $this->eddwc_get_dispatch_date($this->eddwc_get_cart_max_date()));
		update_post_meta( $order_id, '_eddwc_order_format', eddwc_option('date_display_format'));
	}	
	
	public function get_product_est_count($post_id){
		$count = get_post_meta($post_id,EDDWCP_METAKEY,true);
		if(!empty($count)){ return $count;}
		return false;	
	}
	
	
}
