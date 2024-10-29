<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.vsourz.com/
 * @since      1.0.0
 *
 * @package    All_In_One_Redirection
 * @subpackage All_In_One_Redirection/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
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

class All_In_One_Redirection_Deactivator {

	/**
	 * Deactive Plugin
	 *
	 * Remove the redirection table from database once deactive the plugin
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;		
		$redirection_setting_array = json_decode(get_option('all-in-one-redirection-setting'));
		
		if($redirection_setting_array->remove_redirection=='TRUE'){
			$table_name = $wpdb->prefix . 'all_in_one_redirection';
			$wpdb->query('DROP TABLE '.$table_name);
		}
	}

}
