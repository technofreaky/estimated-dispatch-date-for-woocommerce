<?php
/**
 * Plugin Name:       Estimated Dispatch Date For WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/estimated-dispatch-date-woocommerce/
 * Description:       CALCULATE ESTIMATED DISPATCH DATE FOR PRODUCTS & ORDERS
 * Version:           1.1
 * Author:            Varun Sridharan
 * Author URI:        http://varunsridharan.in
 * Text Domain:       estimated-dispatch-date-woocommerce
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt 
 * Domain Path: /languages/
 * GitHub Plugin URI: https://github.com/technofreaky/estimated-dispatch-date-for-woocommerce
 */

if ( ! defined( 'WPINC' ) ) { die; }
 
define('EDDWC_FILE',plugin_basename( __FILE__ )); # Current File

require_once(plugin_dir_path(__FILE__).'bootstrap.php');
require_once(plugin_dir_path(__FILE__).'includes/class-welcome-page.php');
require_once(plugin_dir_path(__FILE__).'includes/plugin-functions.php');
require_once(plugin_dir_path(__FILE__).'includes/class-dependencies.php');


if(Estimated_Dispatch_Date_For_WooCommerce_Dependencies()){
	if(!function_exists('EDDWC')){
		function EDDWC(){
			return Estimated_Dispatch_Date_For_WooCommerce::get_instance();
		}
		
		
	}
	EDDWC();
	new estimated_dispatch_date_woocommerce_welcome_page;
}

?>