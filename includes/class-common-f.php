<?php 
/**
 * functionality of the plugin.
 * @author     Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class Estimated_Dispatch_Date_For_WooCommerce_Functions {
	
	public function __construct() {
		$this->var_type = 'cuzd-dispatch-date-v';
		$this->sim_type = 'cuzd-dispatch-date-s';
		$this->grp_type = 'cuzd-dispatch-date-g';		
		add_action( 'wp_enqueue_scripts', array($this,'cuzd_enqueue') );
		add_action('woocommerce_before_add_to_cart_button', array($this,'cuzd_display_product'), '40');
		add_action( 'woocommerce_cart_totals_after_order_total', array( $this, 'eddwc_display_order' ) );
		add_action( 'woocommerce_review_order_before_shipping' , array( $this, 'eddwc_display_order' ) );
		add_action( 'woocommerce_checkout_update_order_meta' , array( $this, 'eddwc_add_order_meta' ) , 2 );
		add_filter( 'woocommerce_get_order_item_totals', array($this, 'eddwc_show_thankYou'),10,2);
	}
	
	public function eddwc_show_thankYou($ids,$order){
		$ids['eddwc_order']['label'] = esc_attr(eddwc_option('order_page_title'));
		$ids['eddwc_order']['value'] = $this->get_order_display_date($order->post->ID);
		return $ids;
	}
	
	public function get_order_display_date($id){
		$order_date = '';
		if (get_post_meta($id, '_eddwc_order_range', true ) != ''){
			$order_date = get_post_meta($id, '_eddwc_order_range', true );
		} else {
			$order_date = get_post_meta($id, '_eddwc_order_date', true );
		}
		return $order_date;
	}
	
	public function cuzd_enqueue(){
		wp_enqueue_script(EDDWC_SLUG.'display',  EDDWC_JS.'eddwc-dispatch.js', array( 'jquery' ) , EDDWC_V, true );
	}	
	
	public function cuzd_display_product(){
		if ( ! isset($post_id) ) {
			global $post , $product;
			$post_id = $post->ID;
		}
		$this->post_id = $post_id;
		$ouput = '';
		$prod_label = '';
 		$eddwc_prod_date = '';
		$product_type = $product->get_type();
		$type = eddwc_option('display_type');
		$function_to_call = 'render_product_'.$type.'_'.$product_type;
		
		if($type == 'general_date'){
			$this->var_type = 'cuzd-dispatch-general-v';
			$this->sim_type = 'cuzd-dispatch-general-s';
			$this->grp_type = 'cuzd-dispatch-general-g';
		}
		
		if($product_type == 'simple'){
			$ouput = $this->render_simple_product($post_id,$function_to_call,$type);
		}
		
		if($product_type == 'variable'){
			$ouput = $this->render_variable_product($post_id,$function_to_call,$type);
		
		}
		echo $ouput;
		
	}
	
	public function render_simple_product($post_id,$function_to_call,$type){
		$return = '';
		$value = eddwc_get_simple($post_id);
		$value = $this->$function_to_call($value);
		$return .= '<div id="'.$this->sim_type.'">';
		$return .= '	<h3 class="cuzd-title">'.eddwc_option('product_page_title').'</h3>';
		$return .= '	<div class="cuzd-desc">';
		$return .= '		<p class="cuzd-days-s">'.$value.'</p>';
		$return .= '	</div>';
		$return .= '</div>';
		return $return;
	}
	
	public function render_variable_product($post_id,$function_to_call,$type){
		$return = '';
		
		if($type == 'general_date'){$cuzd_prod_field = eddwc_option('product_general_title'); }
		if($type == 'actual_date') {$cuzd_prod_field = eddwc_option('product_actual_title'); }
		if($type == 'average_date') {$cuzd_prod_field = eddwc_option('product_average_title'); }
		
		$var_array = $this->render_variation_product($post_id,$function_to_call,$type);
		$return .= '<div id="'.$this->var_type.'" style="display:none">';
			$return .= '<h3 class="cuzd-title">'.esc_attr(eddwc_option('product_page_title')).'</h3>';
			$return .= '<div class="cuzd-desc">';
				$return .= '<p class="cuzd-days-v" data-cuzd-id="' . esc_attr( json_encode( $var_array ) ) . '">';
					$return .= str_replace('[days]', '[days:' . eddwc_option('product_average_day_trans') . ']', $cuzd_prod_field);
				$return .= '</p>';

			$return .= '</div>';
		$return .= '</div>';
		return $return;
	}
	
	public function render_variation_product($id,$function_to_call,$type){
		global $post , $product;
		$available_variations = $product->get_available_variations('variation_id');
		$variation_count = count($available_variations);
		$var_array = array();
		
		foreach ($available_variations as $var ){
			$days = eddwc_get_variation($var['variation_id']);
			$prod_label = $this->$function_to_call($days);
			if(empty($prod_label)){continue;}
			$var_array[$var['variation_id']] = $prod_label;
		} 
		return $var_array;
	}	
	
	public function render_product_actual_date_simple($value){
		$value = eddwc_get_static_date($value);
		$date = eddwc_get_actual_date($value);
		$label = eddwc_option('product_actual_title');
		$prod_label  = str_replace('[date]', $date, $label);
		return $prod_label;
	}
	
	public function render_product_average_date_simple($value){
		$value = eddwc_get_static_date($value);
		$label = eddwc_option('product_average_title');
		$days_trans = get_option('product_average_day_trans');
		$days_trans = explode(',', $days_trans);
		if ($value > 1) {
			$day = $days_trans[0];
		} else {
			$day = $days_trans[1];
		}

		$prod_label = str_replace('[number]', $value, $label);
		$prod_label = str_replace('[days]', $day, $prod_label);
		return $prod_label;
	}
	
	public function render_product_general_date_simple($value){
		$final_date = eddwc_get_general_date($value);
		$label = eddwc_option('product_general_title');
		$prod_label = str_replace('[range]', $final_date, $label);
		return $prod_label;
	}
	
	public function render_product_general_date_variable($value){
		$final_date = eddwc_get_general_date($value,','); 
		if(empty($final_date)){
			$final_date = eddwc_get_variable($this->post_id);
			$final_date =  eddwc_get_general_date($final_date,',');
		}
		return $final_date;
	}
	
	public function render_product_average_date_variable($value){ 
		$value = eddwc_get_static_date($value);
		return $value;
	}
	
	public function render_product_actual_date_variable($value){
		$value = eddwc_get_static_date($value);
		$date = eddwc_get_actual_date($value);
		return $date;
	}
	
	public function eddwc_cart_max_range(){
		global $woocommerce;
		$items = $woocommerce->cart->get_cart();
		$cuzd_range_date = '';
		$cuzd_min_range = array();
		$cuzd_max_range = array();
		$general_options = eddwc_option('product_general_date_settings');
		
		foreach($items as $item => $values) {
			if (!empty($values['variation_id'])){
				$item_id = $values['variation_id'];
				$item_range = eddwc_get_variation($item_id);
			} else {
				$item_id = $values['product_id'];
				$item_range = eddwc_get_simple($item_id);
			}
			
			
			$item_range = explode(',',$item_range);
			$cuzd_min_range[] = $item_range[0];
			$cuzd_max_range[] = $item_range[1];
		}
		asort($cuzd_min_range);
		arsort($cuzd_max_range);
		if (isset($general_options['actual_date'])){
			$cuzd_range_date = 	eddwc_get_dispatch_date($cuzd_min_range[0]).' - '. eddwc_get_dispatch_date($cuzd_max_range[0]);
		} else {
			$cuzd_range_date = 	$cuzd_min_range[0].' - '.$cuzd_max_range[0].' '.eddwc_option('general_range_title');
		}

		return $cuzd_range_date;
	} 
	
	public function eddwc_display_order(){
		$where_to_show = eddwc_option('where_to_display');
		$general_options = eddwc_option('product_general_date_settings');
		
		if (isset($general_options['range_date_checkout'])){
			$cuzd_current_cart = $this->eddwc_cart_max_range();
		} else {
			$cuzd_current_cart = eddwc_get_dispatch_date($this->eddwc_cart_max_date());
		}
		
		if ( isset($where_to_show['order_page'])){				
			if ($cuzd_current_cart != ''){				
				echo '<tr><th>' . esc_attr(eddwc_option('order_page_title')) . '</th><td>' . $cuzd_current_cart . '</td></tr>';
			}
		}
	}	
	
	public function eddwc_add_order_meta($order_id){
		$general_options = eddwc_option('product_general_date_settings');
		if (isset($general_options['actual_date'])){
			update_post_meta( $order_id, '_eddwc_order_range', $this->eddwc_cart_max_range());
		}
		update_post_meta( $order_id, '_eddwc_order_date', $this->eddwc_get_dispatch_date($this->eddwc_cart_max_date()));
		update_post_meta( $order_id, '_eddwc_order_format', eddwc_option('date_display_format'));
	}	
	
}
		
?>