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
		add_action('woocommerce_product_options_general_product_data',array($this,'add_est_field'));
		add_action('woocommerce_process_product_meta_simple',array($this,'save_simple_product_data'));
	}
	
	public function add_est_field($post_id){
		global $post, $thepostid;
		$thepostid = $post->ID;
		echo '<div class="options_group show_if_simple hide_if_external hide_if_variable">';
		$type = eddwc_option('display_type');
		$field_type = 'number';
		$fieldClass = '';
		if($type == 'general_date'){
			$field_type = 'hidden';
			$fieldClass = 'hidden';
			$value = get_post_meta($thepostid,EDDWCP_METAKEY,true);
			echo '<div class="eddwc_range_container" >';
			woocommerce_wp_text_input( 
				array( 
					'id' => 'js_range_est_selector', 
					'label' => __( 'Est. Dispatch Date:', EDDWC_TXT), 
					'placeholder' => __( 'number', EDDWC_TXT),
					'value' => $value,
					'type' => $field_type,  
				)
			);			
			echo '</div>';
		}
		woocommerce_wp_text_input( 
			array( 
				'id' => EDDWCP_METAKEY, 
				'label' => __( 'Est. Dispatch Date:', EDDWC_TXT), 
				'placeholder' => __( 'number', EDDWC_TXT), 
				'type' => $field_type,  
				'wrapper_class' => $fieldClass,
			)
		);
		echo '</div>';
	}
	
	public function save_simple_product_data($post_id){
		if(isset($_POST[EDDWCP_METAKEY])){
			$estDate = $_POST[EDDWCP_METAKEY];
			update_post_meta( $post_id, EDDWCP_METAKEY, wc_clean($estDate) );
		}
	}
	
}



?>