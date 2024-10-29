<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across the side of the admin area.
 *
 * @link       https://www.vsourz.com/
 * @since      1.0.0
 *
 * @package    All_In_One_Redirection
 * @subpackage All_In_One_Redirection/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    All_In_One_Redirection
 * @subpackage All_In_One_Redirection/includes
 * @author     Vsourz Digital <mehul@vsourz.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
 
class All_In_One_Redirection {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      All_In_One_Redirection_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	
	/**
	 * The table name for this plugin
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $db_table_name    The table name for this plugin.
	 */
	protected $db_table_name;
	
	/**
	 * The default site url for this plugin
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $def_site_url    The default site url for this plugin.
	 */
	protected $def_site_url;
	
	/**
	 * The default setting site url for this plugin
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $def_setting_site_url    The default site url for this plugin.
	 */
	protected $def_setting_site_url;
	
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		global $wpdb;
				
		$def_url = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name = %s LIMIT 1", 'siteurl' ) );
		
		$this->plugin_name = 'all-in-one-redirection';
		$this->version = '2.1.1';
		$this->db_table_name = $wpdb->prefix.'all_in_one_redirection';
		$this->def_site_url = rtrim($def_url->option_value, '/');
		
		
		
		$def_setting_site_url_val = $this->def_site_url;
		$redirection_setting_array = json_decode(get_option('all-in-one-redirection-setting'));
				
		// check the host type
		if($redirection_setting_array->host=='HTTP'){
			if(!strpos($def_setting_site_url_val,'http:')){				
				$def_setting_site_url_val = str_ireplace('https:','http:',$def_setting_site_url_val);
			}
		}
		elseif($redirection_setting_array->host=='HTTPS'){				
			if(!strpos($def_setting_site_url_val,'https:')){
				$def_setting_site_url_val = str_ireplace('http:','https:',$def_setting_site_url_val);
			}
		}
		
		// check the www and non-www condition
		if($redirection_setting_array->www=='WWW'){
			if(!strpos($def_setting_site_url_val,'www')){
				$def_setting_site_url_val = str_ireplace('//','//www.',$def_setting_site_url_val);
			}
		}
		elseif($redirection_setting_array->www=='NON-WWW'){
			if(strpos($def_setting_site_url_val,'www')){
				$def_setting_site_url_val = str_ireplace('//www.','//',$def_setting_site_url_val);
			}
		}
		$this->def_setting_site_url = $def_setting_site_url_val;
		
		
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - All_In_One_Redirection_Loader. Orchestrates the hooks of the plugin.
	 * - All_In_One_Redirection_i18n. Defines internationalization functionality.
	 * - All_In_One_Redirection_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-all-in-one-redirection-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-all-in-one-redirection-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-all-in-one-redirection-admin.php';
		
		/**
		 * The class responsible for defining all actions that occur in the admin dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class-all-in-one-redirection-admin-display.php';
		
		$this->loader = new All_In_One_Redirection_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the All_In_One_Redirection_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new All_In_One_Redirection_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		
		$plugin_admin = new All_In_One_Redirection_Admin( $this->get_plugin_name(), $this->get_version(),  $this->get_table_name(), $this->def_site_url(), $this->def_setting_site_url() );
		
		$plugin_admin_screen = new All_In_One_Redirection_Admin_Screen( $this->get_plugin_name(), $this->get_version(), $this->get_table_name(), $this->def_site_url(), $this->def_setting_site_url() );
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'vsz_redirection' );
		
		$plugin_basename = plugin_basename(dirname(dirname(__FILE__ ))).'/all-in-one-redirection.php';		
		$this->loader->add_filter( 'plugin_action_links_'.$plugin_basename, $plugin_admin, 'plugin_setting_link' );
		
		if(!is_admin()){
			//added on 07-06-2023 used for insert 404 entry and redirect to homepage
			$this->loader->add_action( 'template_redirect', $plugin_admin, 'vsz_redirect_404_pages_to_homepage' );
		}
		
		$this->loader->add_action( 'admin_menu', $plugin_admin_screen, 'vsz_add_admin_menu' );
		
		

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    All_In_One_Redirection_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
	/**
	 * Retrieve the database table name for the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The name of table
	 */
	public function get_table_name(){
		return $this->db_table_name;
	}
	
	/**
	 * Retrieve the default site url for the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    Default site URL
	 */
	public function def_site_url(){
		return $this->def_site_url;
	}
	
	
	public function def_setting_site_url(){		
		return $this->def_setting_site_url;
	}	

}