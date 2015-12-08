<?php
/**
 * The admin-specific functionality of the plugin.
 * @package    @TODO
 * @subpackage @TODO
 * @author     Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class Estimated_Dispatch_Date_For_WooCommerce_Admin extends Estimated_Dispatch_Date_For_WooCommerce {

    /**
	 * Initialize the class and set its properties.
	 * @since      0.1
	 */
	public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ),99);
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_init', array( $this, 'admin_init' ));
        add_action( 'plugins_loaded', array( $this, 'init' ) );
		add_filter( 'plugin_row_meta', array($this, 'plugin_row_links' ), 10, 2 );
		add_filter( 'woocommerce_screen_ids',array($this,'set_wc_screen_ids'),99);
		add_action( 'wp_ajax_save_holiday_dates',array($this, 'my_action_callback') );
	}

	public function my_action_callback(){
		//eddwc_holidays
		$holidays = array();
		$holidays['edd_wc_holiday'] = isset($_POST['eddwc_holidays']) ? $_POST['eddwc_holidays'] : array();
		update_option('edd_wc_holiday',$holidays);
		
		exit;
	}
	
	
    /**
     * Inits Admin Sttings
     */
    public function admin_init(){
		$this->product_settings = new Estimated_Dispatch_Date_For_WooCommerce_Admin_Product_Settings;
    } 
    
    /**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() { 
        if(in_array($this->current_screen() , $this->get_screen_ids())) {
            wp_enqueue_style(EDDWC_SLUG.'_core_style',EDDWC_CSS.'admin-style.css' , array(), EDDWC_V, 'all' );  
			wp_enqueue_style(EDDWC_SLUG.'_range_style',EDDWC_CSS.'jquery-range.css' , array(), EDDWC_V, 'all' );  
			wp_enqueue_style(EDDWC_SLUG.'_date_picker',EDDWC_CSS.'date-picker.css' , array(), EDDWC_V, 'all' );  
        }
	}
	
    
    /**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() { 
		if(in_array($this->current_screen() , $this->get_screen_ids())) {
			wp_enqueue_script(EDDWC_SLUG.'_range_js', EDDWC_JS.'jquery-range.js', array('jquery'), EDDWC_V, false ); 
			wp_enqueue_script(EDDWC_SLUG.'_date_picker', EDDWC_JS.'date-picker.js', array('jquery'), EDDWC_V, false ); 
			wp_enqueue_script(EDDWC_SLUG.'_core_script', EDDWC_JS.'admin-script.js', array('jquery'), EDDWC_V, false ); 
        }
		
		if($this->current_screen() == 'woocommerce_page_edd_wc_settings') {
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');		
		}
	}
    
    /**
     * Gets Current Screen ID from wordpress
     * @return string [Current Screen ID]
     */
    public function current_screen(){
       $screen =  get_current_screen();
       return $screen->id;
    }
    
    /**
     * Returns Predefined Screen IDS
     * @return [Array] 
     */
    public function get_screen_ids(){
        $screen_ids = array();
		$screen_ids[] = 'woocommerce_page_edd_wc_settings';
		$screen_ids[] = 'product';
        return $screen_ids;
    }
    
    
    public function set_wc_screen_ids($screens){
        $screen = $screens; 
        $screen[] = 'woocommerce_page_edd_wc_settings';
        return $screen;
    } 
	
    /**
	 * Adds Some Plugin Options
	 * @param  array  $plugin_meta
	 * @param  string $plugin_file
	 * @since 0.11
	 * @return array
	 */
	public function plugin_row_links( $plugin_meta, $plugin_file ) {
		if ( EDDWC_FILE == $plugin_file ) {
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', '#', __('Settings',EDDWC_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', '#', __('F.A.Q',EDDWC_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', '#', __('View On Github',EDDWC_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', '#', __('Report Issue',EDDWC_TXT) );
            $plugin_meta[] = sprintf('&hearts; <a href="%s">%s</a>', '#', $this->__('Donate',EDDWC_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', 'http://varunsridharan.in/plugin-support/', __('Contact Author',EDDWC_TXT) );
		}
		return $plugin_meta;
	}	    
}

?>