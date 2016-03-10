<?php
/**
 * The admin-specific functionality of the plugin.
 * @package    @TODO
 * @subpackage @TODO
 * @author     Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class Estimated_Dispatch_Date_For_WooCommerce_Admin_Product_Settings extends Estimated_Dispatch_Date_For_WooCommerce_Admin {
    
	public function __construct() {
		add_action('edd_wc__add_extra_field',array($this,'add_holiday_fields'));
		add_action('woocommerce_product_options_inventory_product_data',array($this,'add_est_simple_field'));
		add_action('woocommerce_product_after_variable_attributes',array($this,'add_est_variation_field'),10,10);
		
		add_action('woocommerce_process_product_meta_simple',array($this,'save_simple_product_data'));
		add_action('woocommerce_process_product_meta_variable',array($this,'save_variable_product_data'));
		add_action('woocommerce_save_product_variation',array($this,'save_variation_product_data'),10,2);
		add_action('woocommerce_admin_order_data_after_order_details',array($this,'add_dates'));

	}
	
	public function add_dates($order){
		echo '<p class="form-field form-field-wide">';
		$id = $order->id;
		$est_date = get_post_meta($id,'_eddwc_order_date',true);
		$title = eddwc_option('order_page_title');
		echo '<label for="eddwc_order_date"> '.$title.'</label>';
		echo ' <input type="text" value="'.$est_date.'" id="eddwc_order_date" name="_eddwc_order_date" >';
		echo '</p>';
	}
	
	public function add_est_variation_field($loop,$variable,$variable_product){
		$post_ID = $variable_product->ID;
		$values = eddwc_get_variation($post_ID);
		
		$type = eddwc_option('display_type');
		$field_type = 'number';
		$fieldClass = 'form-row form-row-full';
		$custom_attributes = '';
		echo '<div>';
			if($type == 'general_date'){
				$field_type = 'hidden';
				$custom_attributes = array('date-type' => 'range_select');
			} 
		
			woocommerce_wp_text_input( 
				array( 
					'id' => EDDWCP_METAKEY.'_'.$loop, 
					'label' => __( 'Est. Dispatch Date:', EDDWC_TXT), 
					'placeholder' => __( 'number', EDDWC_TXT), 
					'type' => $field_type,  
					'wrapper_class' => 'form-row form-row-full',
					'name' => 'est_date_variation_'.$loop,
					'custom_attributes' => $custom_attributes,
					'value' => $values,
				)
			);
		echo '</div>';
	}
	
	
	public function add_est_simple_field($post_id){
		global $post, $thepostid,$product;
		$thepostid = $post->ID;
		$product = wc_get_product($thepostid);
		$get_value = 'eddwc_get_';
		$get_value .= $product->get_type();
		echo '<div class="options_group show_if_simple hide_if_external show_if_variable">';
			$type = eddwc_option('display_type');
			$field_type = 'number';
			$fieldClass = '';
			$custom_attributes = '';
			$value = $get_value($thepostid);

		
			if($type == 'general_date'){
				$field_type = 'hidden';
				$custom_attributes = array('date-type' => 'range_select');
			} else {
				if($value != ''){
					$value = explode(',',$value);
					if(isset($value[0]) && isset($value[1]) && $value[0] > $value[1]){
						$value = $value[0];
					} else if(isset($value[0]) && ! isset($value[1])) {
						$value = $value[0];
					} else {
						$value = $value[1];
					}
				}
			}
		
		
		
			woocommerce_wp_text_input( 
				array( 
					'id' => EDDWCP_METAKEY, 
					'label' => __( 'Est. Dispatch Date:', EDDWC_TXT), 
					'placeholder' => __( 'number', EDDWC_TXT), 
					'type' => $field_type,  
					'wrapper_class' => $fieldClass,
					'value' => $value,
					'custom_attributes' => $custom_attributes
				)
			);
		echo '</div>';
	}
	
	
	public function add_holiday_fields(){
		$field = '';
		$existing_data = eddwc_option('holiday');
		$field .= '<table data-total-count="0" style="width:80%;margin-top:5px" class="eddwc-holidays widefat" cellspacing="0">';
			$field .= '<thead>';
				$field .= '<tr>';
					//$field .= '<th class="checkbox" ></th>';
					$field .= '<th class="title">'.__("Holiday Name",EDDWC_TXT).'</th>';
					$field .= '<th class="date">'.__("Holiday Date",EDDWC_TXT).'</th>';
					$field .= '<th class="actions">'.__("Actions",EDDWC_TXT).'</th>';
				$field .= '</tr>';
			$field .= '</thead>';
			$field .= '<tbody>';
		
				if(! empty($existing_data)){
					 
				
				foreach($existing_data as $dataK => $dataV){
					$field .= '<tr>';
						//$field .= '<td><input type="checkbox" /></td> ';
						$field .= '<td><input value="'.$dataV['name'].'" class="holiday_name_input" name="eddwc_holidays['.$dataK.'][name]" type="text" /></td> ';
						$field .= '<td><input class="holiday_date_input" data-type="datepicker" name="eddwc_holidays['.$dataK.'][date]" type="text" value="'.$dataV['date'].'" /></td> ';
						$field .= '<td>
						<button type="button" class="add eddwc_btn button button-primary" id="add"><span class="dashicons dashicons-plus-alt"></span></button> 
						<button type="button" class="edit eddwc_btn button button-edit" id="edit"><span class="dashicons dashicons-edit"></span></button> 
						<button type="button" class="save eddwc_btn button button-save" id="save"><span class="dashicons dashicons-yes"></span></button> 
						<button type="button" class="delete eddwc_btn button button-delete" id="delete"><span class="dashicons dashicons-trash"></span></button>
						</td> ';
					$field .= '</tr>';
				} 
				}
				$field .= '<tr>';
					//$field .= '<td><input type="checkbox" /></td> ';
					$field .= '<td><input class="holiday_name_input" name="eddwc_holidays[][name]" type="text" /></td> ';
					$field .= '<td><input class="holiday_date_input" data-type="datepicker" name="eddwc_holidays[][date]" type="text" /></td> ';
					$field .= '<td>
					<button type="button" class="add eddwc_btn button button-primary" id="add"><span class="dashicons dashicons-plus-alt"></span></button> 
					<button type="button" class="edit eddwc_btn button button-edit" id="edit"><span class="dashicons dashicons-edit"></span></button> 
					<button type="button" class="save eddwc_btn button button-save" id="save"><span class="dashicons dashicons-yes"></span></button> 
					<button type="button" class="delete eddwc_btn button button-delete" id="delete"><span class="dashicons dashicons-trash"></span></button>
					</td> ';
				$field .= '</tr>';
		
				$field .= '<tr class="hidden" id="template_settings">';
					//$field .= '<td><input type="checkbox" /></td> ';
					$field .= '<td><input class="holiday_name_input" type="text" /></td> ';
					$field .= '<td><input class="holiday_date_input" data-type="datepicker" type="text" /></td> ';
					$field .= '<td>
					<button type="button" class="add eddwc_btn button button-primary" id="add"><span class="dashicons dashicons-plus-alt"></span></button> <button type="button" class="edit eddwc_btn button button-edit" id="edit"><span class="dashicons dashicons-edit"></span></button> <button type="button" class="save eddwc_btn button button-save" id="save"><span class="dashicons dashicons-yes"></span></button> <button type="button" class="delete eddwc_btn button button-delete" id="delete"><span class="dashicons dashicons-trash"></span></button>
					</td> ';
				$field .= '</tr>';
		
		
			$field .= '</tbody>';
		$field .= '</table>';
		
		echo $field;
	}
	
	
	
	
	public function save_simple_product_data($post_id){
		if(isset($_POST[EDDWCP_METAKEY])){
			$estDate = $_POST[EDDWCP_METAKEY]; 
			eddwc_update_simple($post_id,$estDate);
		}
	}
	
	public function save_variable_product_data($post_id){
		if(isset($_POST[EDDWCP_METAKEY])){
			$estDate = $_POST[EDDWCP_METAKEY]; 
			eddwc_update_variable($post_id,$estDate);
		}
	}	
	
	public function save_variation_product_data($post_id,$loop_no){
		if(isset($_POST['est_date_variation_'.$loop_no])){
			$estDate = $_POST['est_date_variation_'.$loop_no]; 
			eddwc_update_variation($post_id,$estDate);
		}
	}	
}
?>