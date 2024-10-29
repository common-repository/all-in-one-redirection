<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.vsourz.com/
 * @since      1.0.0
 *
 * @package    All_In_One_Redirection
 * @subpackage All_In_One_Redirection/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
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

class All_In_One_Redirection_Activator {

	/**
	 * Activate the plugin
	 *
	 * Create new table in database and set defaule setting for redirection based in home url
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$def_url = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name = %s LIMIT 1", 'siteurl' ) );
		
		$redirection_setting = array();
		$redirection_setting['host']='HTTP';
		$redirection_setting['www']='WWW';
		$redirection_setting['setting_host_www']='FALSE';
		$redirection_setting['setting_default_siteurl']='TRUE';
		$redirection_setting['remove_redirection']='FALSE';
		
		if(isset($def_url) && !empty($def_url)){
			$wp_vsz_site_url = rtrim($def_url->option_value, '/');
			if(strpos($wp_vsz_site_url,'https://') !== false ){
				$redirection_setting['host']='HTTPS';
			}
			else{
				$redirection_setting['host']='HTTP';
			}
			
			if(strpos($wp_vsz_site_url,'www.')){
				$redirection_setting['www']='WWW';
			}
			else{
				$redirection_setting['www']='NON-WWW';
			}
		}
		
		update_option('all-in-one-redirection-setting',json_encode($redirection_setting));

		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'all_in_one_redirection';
		
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  rtype int(3) NOT NULL,
		  source_url varchar(255) NOT NULL,
		  destination_url varchar(255),
		  reg_expression int(1) DEFAULT 0 NOT NULL,
		  hide_url int(1) DEFAULT 0 NOT NULL,
		  hits int(11) DEFAULT 0 NOT NULL,
		  referrer varchar(255),
		  access_ip varchar(255),
		  last_access datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate;";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}
