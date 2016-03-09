<?php 

if ( ! defined( 'WPINC' ) ) { die; }
 
class Estimated_Dispatch_Date_For_WooCommerce {
	public $version = '1.0';
	public $plugin_vars = array();
	
	protected static $_instance = null; # Required Plugin Class Instance
    protected static $functions = null; # Required Plugin Class Instance
	protected static $admin = null;     # Required Plugin Class Instance
	protected static $settings = null;  # Required Plugin Class Instance

    /**
     * Creates or returns an instance of this class.
     */
    public static function get_instance() {
        if ( null == self::$_instance ) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    /**
     * Class Constructor
     */
    public function __construct() {
        $this->define_constant();
		$this->set_vars();
        $this->load_required_files();
        $this->init_class();
        add_action( 'init', array( $this, 'init' )); 
    }
    
    /**
     * Triggers When INIT Action Called
     */
    public function init(){
        add_action('plugins_loaded', array( $this, 'after_plugins_loaded' ));
        add_filter('load_textdomain_mofile',  array( $this, 'load_plugin_mo_files' ), 10, 2);
    }
    
    /**
     * Loads Required Plugins For Plugin
     */
    private function load_required_files(){
       $this->load_files(EDDWC_INC.'class-common-*.php');
	   $this->load_files(EDDWC_ADMIN.'class-wp-*.php');
        
       if($this->is_request('admin')){
           $this->load_files(EDDWC_ADMIN.'class-*.php');
       } 

    }
    
    /**
     * Inits loaded Class
     */
    private function init_class(){
        self::$functions = new Estimated_Dispatch_Date_For_WooCommerce_Functions;
		self::$settings = new Estimated_Dispatch_Date_For_WooCommerce_Admin_Options; 

        if($this->is_request('admin')){
            self::$admin = new Estimated_Dispatch_Date_For_WooCommerce_Admin;
        }
    }
    
	# Returns Plugin's Functions Instance
	public function func(){
		return self::$functions;
	}
	
	# Returns Plugin's Settings Instance
	public function settings(){
		return self::$settings;
	}
	
	# Returns Plugin's Admin Instance
	public function admin(){
		return self::$admin;
	}
    
    /**
     * Loads Files Based On Give Path & regex
     */
    protected function load_files($path,$type = 'require'){
        foreach( glob( $path ) as $files ){
            if($type == 'require'){ require_once( $files ); } 
			else if($type == 'include'){ include_once( $files ); }
        } 
    }
    
    /**
     * Set Plugin Text Domain
     */
    public function after_plugins_loaded(){
        load_plugin_textdomain(PLUGIN_TEXT_DOMAIN, false, EDDWC_LANGUAGE_PATH );
    }
    
    /**
     * load translated mo file based on wp settings
     */
    public function load_plugin_mo_files($mofile, $domain) {
        if (EDDWC_TXT === $domain)
            return EDDWC_LANGUAGE_PATH.'/'.get_locale().'.mo';

        return $mofile;
    }
    
    /**
     * Define Required Constant
     */
    private function define_constant(){
        $this->define('EDDWC_NAME', 'Estimated Dispatch Date For WooCommerce'); # Plugin Name
        $this->define('EDDWC_SLUG', 'estimated-dispatch-date-woocommerce'); # Plugin Slug
        $this->define('EDDWC_TXT',  'estimated-dispatch-date-woocommerce'); #plugin lang Domain
		$this->define('EDDWC_DB', 'edd_wc_');
		
		$this->define('EDDWC_V',$this->version); # Plugin Version
		
		$this->define('EDDWC_PATH',plugin_dir_path( __FILE__ ).'/'); # Plugin DIR
		$this->define('EDDWC_LANGUAGE_PATH',EDDWC_PATH.'languages/'); # Plugin Language Folder
		$this->define('EDDWC_INC',EDDWC_PATH.'includes/'); # Plugin INC Folder
		$this->define('EDDWC_ADMIN',EDDWC_INC.'admin/'); # Plugin Admin Folder
		$this->define('EDDWC_SETTINGS',EDDWC_INC.'admin/settings/'); # Plugin Settings Folder
		
		$this->define('EDDWC_URL',plugins_url('', __FILE__ ).'/');  # Plugin URL
		$this->define('EDDWC_CSS',EDDWC_URL.'includes/css/'); # Plugin CSS URL
		$this->define('EDDWC_IMG',EDDWC_URL.'includes/img/'); # Plugin IMG URL
		$this->define('EDDWC_JS',EDDWC_URL.'includes/js/'); # Plugin JS URL
		
        $this->define('EDDWC_FILE',plugin_basename( __FILE__ )); # Current File

		$this->define('EDDWCP_METAKEY','_est_dispatch_date');
    }
	
	private function set_vars(){
		
	}
    
    /**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
    protected function define($key,$value){
        if(!defined($key)){
            define($key,$value);
        }
    }
    
	/**
	 * Get Plugin Vars
	 */
	private function get_vars($key){
		if(isset($this->plugin_vars[$key])){
			return $this->plugin_vars[$key];
		}
									
		return false;
	}
	
	/**
	 * Set Plugin Vars
	 */
	private function add_vars($key,$value){
		if(!isset($this->plugin_vars[$key])){
			$this->plugin_vars[$key] = $value;
		}
	}
	
	
	public function get_option($key){
		$key = EDDWC_DB.$key;
		return self::$settings->get_option($key);
	}
	
	
	/**
	 * What type of request is this?
	 * string $type ajax, frontend or admin
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}
}
?>