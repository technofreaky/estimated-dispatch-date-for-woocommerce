<?php
/**
 * Plugin Name:       Estimated Dispatch Date For WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/estimated-dispatch-date-for-woocommerce/
 * Description:       Sample Plugin For WooCommerce
 * Version:           0.1
 * Author:            Varun Sridharan
 * Author URI:        http://varunsridharan.in
 * Text Domain:       estimated-dispatch-date-for-woocommerce
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt 
 * GitHub Plugin URI: @TODO
 */

if ( ! defined( 'WPINC' ) ) { die; }
 

require_once(plugin_dir_path(__FILE__).'bootstrap.php');
require_once(plugin_dir_path(__FILE__).'includes/plugin-functions.php');
require_once(plugin_dir_path(__FILE__).'includes/class-dependencies.php');


if(Estimated_Dispatch_Date_For_WooCommerce_Dependencies()){
	if(!function_exists('EDDWC')){
		function EDDWC(){
			return Estimated_Dispatch_Date_For_WooCommerce::get_instance();
		}
	}
	EDDWC();
}

?>