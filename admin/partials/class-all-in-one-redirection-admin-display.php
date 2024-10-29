<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.vsourz.com/
 * @since      1.0.0
 *
 * @package    All_In_One_Redirection
 * @subpackage All_In_One_Redirection/admin/partials
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//<!-- This file should primarily consist of HTML with a little bit of PHP. -->
class All_In_One_Redirection_Admin_Screen {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $db_table_name, $def_site_url, $def_setting_site_url ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->db_table_name = $db_table_name;
		$this->def_site_url = $def_site_url;
		$this->def_setting_site_url = $def_setting_site_url;
	}

	// set the menu in admin section
	public function vsz_add_admin_menu(){		
		add_menu_page(__( 'Redirection', 'all-in-one-redirection' ), __( 'Redirection', 'all-in-one-redirection' ), 'manage_options', 'all-in-one-redirection', array($this,'vsz_admin_redirection_screen'), 'dashicons-randomize', 40 );
		
		add_submenu_page('all-in-one-redirection',__( 'Redirection Setting', 'all-in-one-redirection' ), __( 'Redirection Setting', 'all-in-one-redirection' ), 'manage_options', 'all-in-one-redirection-setting', array($this,'vsz_admin_redirection_setting_screen'),'', 41);
		
		add_submenu_page('all-in-one-redirection',__( '404 Pages List', 'all-in-one-redirection' ), __( '404 Pages List', 'all-in-one-redirection' ), 'manage_options', 'all-in-one-redirection-404-pages-list', array($this,'vsz_admin_404_redirection_list_screen'),'', 42);
		
		add_submenu_page('all-in-one-redirection',__( 'Tool', 'all-in-one-redirection' ), __( 'Tool', 'all-in-one-redirection' ), 'manage_options', 'all-in-one-redirection-tool', array($this,'vsz_admin_page_redirection_tool_screen'),'', 43);
	}
	
	// Redirection setting
	public function vsz_admin_redirection_setting_screen(){
		echo '<div class="vsz-redirection">';
		echo '<div class="vsz-title">'.__( 'Redirection Setting', 'all-in-one-redirection' ).'</div>';
			
			echo '<div class="vsz-redirection-setting-sec">';
			
			if(isset($_POST['redirection_setting_btn']) && !empty($_POST['redirection_setting_btn'])){
				if(wp_verify_nonce( sanitize_text_field($_POST['setting-nonce']), 'setting-page-redirection' )){				
					$redirection_setting = array();
					$redirection_setting['host']=sanitize_text_field($_POST['host']);
					$redirection_setting['www']=sanitize_text_field($_POST['www']);
					
					//enable-disable host setting
					if(sanitize_text_field($_POST['additional_setting'])=='CUSTOM-SETTING'){
						$redirection_setting['setting_host_www']='TRUE';
					}
					else{
						$redirection_setting['setting_host_www']='FALSE';
					}
					
					//default seeting based on site url
					if(sanitize_text_field($_POST['additional_setting'])=='DEFAULT-SETTING'){
						$redirection_setting['setting_default_siteurl']='TRUE';
						$wp_vsz_site_url = $this->def_site_url;
						
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
					else{
						$redirection_setting['setting_default_siteurl']='FALSE';
					}
					
					//setting to remove all data during plugin deactive
					if(sanitize_text_field($_POST['remove-redirection'])){
						$redirection_setting['remove_redirection']='TRUE';
					}
					else{
						$redirection_setting['remove_redirection']='FALSE';
					}
                    
					//setting to save redirect 404 pages to the Home page value
					if(sanitize_text_field($_POST['redirect-404-to-home'])){
						$redirection_setting['redirect_404_to_home']='YES';
					}
					else{
						$redirection_setting['redirect_404_to_home']='NO';
					}
                    
					update_option('all-in-one-redirection-setting',json_encode($redirection_setting));
					echo '<div class="record-success">'.__( 'Setting saved successfully.', 'all-in-one-redirection' ).'</div>';
				}
				else{
					exit;
				}
			}
			
				echo '<form name="redirection-setting-form" id="redirection-setting-form" action="" method="POST">';
				$nonce_setting = wp_create_nonce('setting-page-redirection');				
				$redirection_setting_array = json_decode(get_option('all-in-one-redirection-setting'));

				?>
                
                <div>
                    <div class="lbl"><?php echo __( 'Host Setting', 'all-in-one-redirection' ); ?></div>
                    <div>
                    <span><label><input type="radio" name="host" <?php if($redirection_setting_array->setting_default_siteurl=='TRUE'){echo 'disabled="disabled"';} ?> value="HTTP" <?php if($redirection_setting_array->host=="HTTP"){echo 'checked="checked"';} ?> /> HTTP</label></span>
                    <span><label><input type="radio" name="host" <?php if($redirection_setting_array->setting_default_siteurl=='TRUE'){echo 'disabled="disabled"';} ?> value="HTTPS" <?php if($redirection_setting_array->host=="HTTPS"){echo 'checked="checked"';} ?> /> HTTPS</label></span>
                    </div>
				</div>
				
				<div>
                    <div class="lbl"><?php echo __( 'WWW Setting', 'all-in-one-redirection' ); ?></div>
                    <div>
                    <span><label><input type="radio" name="www" <?php if($redirection_setting_array->setting_default_siteurl=='TRUE'){echo 'disabled="disabled"';} ?> value="WWW" <?php if($redirection_setting_array->www=="WWW"){echo 'checked="checked"';} ?> /> WWW</label></span>
                    <span><label><input type="radio" name="www" <?php if($redirection_setting_array->setting_default_siteurl=='TRUE'){echo 'disabled="disabled"';} ?> value="NON-WWW" <?php if($redirection_setting_array->www=="NON-WWW"){echo 'checked="checked"';} ?> /> Non WWW</label></span>
                    </div>
				</div>
                
                <div class="additional-setting">
                	 <div class="lbl"><?php echo __( 'Additional Setting', 'all-in-one-redirection' ); ?></div>
                    <div><label><input type="radio" class="additional_setting_radio" name="additional_setting" value="CUSTOM-SETTING" <?php if($redirection_setting_array->setting_host_www=='TRUE'){echo 'checked="checked"';} ?> /><?php echo __( 'Enable Host and WWW setting.', 'all-in-one-redirection' ); ?></label></div>
                    <div><label><input type="radio" class="additional_setting_radio" name="additional_setting" value="DEFAULT-SETTING" <?php if($redirection_setting_array->setting_default_siteurl=='TRUE'){echo 'checked="checked"';} ?> /><?php echo __( 'Set the default redirection based on website URL.', 'all-in-one-redirection' ); ?></label></div>
                    <div class="remove_entry"><label><input type="checkbox" name="remove-redirection" value="TRUE" <?php if($redirection_setting_array->remove_redirection=='TRUE'){echo 'checked="checked"';} ?> /><?php echo __( 'Delete all Redirection once deactivate the plugin.', 'all-in-one-redirection' ); ?></label></div>
                    <!-- added on 07-06-2023 - if checked then redirect to home page -->
                    <div class="remove_entry"><label><input type="checkbox" name="redirect-404-to-home" value="YES" <?php if(isset($redirection_setting_array->redirect_404_to_home) && $redirection_setting_array->redirect_404_to_home=='YES'){echo 'checked="checked"';} ?> /><?php echo __( 'Forcefully redirect 404 pages to the Home page.', 'all-in-one-redirection' ); ?></label></div>
                </div>
                <div class="setting-note" style="color:#f00;"><strong><?php echo __( 'Note:', 'all-in-one-redirection' ); ?></strong> <?php echo __( 'Please deactivate this plugin before you migrate the website from one server to another server and then reactivate this plugin on the new server.', 'all-in-one-redirection' ); ?></div>              
				<?php
				echo '<input type="hidden" name="setting-nonce" value="'.$nonce_setting.'" />';
				echo '<input type="submit" name="redirection_setting_btn" value="'.__( 'Save', 'all-in-one-redirection' ).'" />';
				echo '</form>';
				
			
			
			
			echo '</div>';
		echo '</div>';
	}
	
			
	/*
	* Add new redirection record
	* List of all the redirection records
	* Update optino for all records
	* Delete Particular single records
	* Delete all records
	*/
	public function vsz_admin_redirection_screen(){
		global $wpdb;
		$post_per_page = 20;
		$no_of_page = 0;
		$current_page = 0;
		$record_start = 0;
		
		$table_name = $this->db_table_name;
		
		echo '<div class="vsz-redirection vsz-insert-form-sec">';
		
		echo '<div class="vsz-title">'.__( 'Add New Redirection', 'all-in-one-redirection' ).'</div>';		
		echo '<form id="redirection_insert_form" name="redirection_insert_form" action="" method="POST">';
		
		// Save new record
		if(isset($_POST['insert_redirection_btn'])){
			$source_url = rtrim(trim($_POST['source_url_insert']),'/');			
			$source_url = str_ireplace($this->def_setting_site_url,'',trim($source_url));				
			if(empty($source_url)){
				$source_url = '/';
			}
			
			
			if(!isset($_POST['destination_url_insert']) || empty($_POST['destination_url_insert'])){
				$destination_url = $this->def_setting_site_url.'/';
			}
			else{
				$destination_url = esc_url($_POST['destination_url_insert']);
			}
			$regexpression_check = sanitize_text_field($_POST['reg_expression_check']);
			if($regexpression_check!=1){
				$regexpression_check=0;
			}
			
			if(wp_verify_nonce( sanitize_text_field($_POST['insert-nonce']), 'insert-page-redirection' )){
				$def_source = trim($_POST['source_url_insert']);
				
				if(isset($def_source) && !empty($def_source)){
					if(substr($source_url,0,1) == '/'){
						// conver space to %20
						$source_url = str_ireplace(' ', '%20', trim($source_url));
						
						
						$source_url = stripslashes(trim($source_url));
						$chk_exist_rule = 0;
						//Changed dated 15-05-2023
						$chk_exist_rule_data = $wpdb->get_row($wpdb->prepare("select * from {$table_name} where source_url=%s and reg_expression=%s and hide_url=0 and rtype!=404 order by id DESC",$source_url, $regexpression_check));

						if($chk_exist_rule_data){
							if($chk_exist_rule_data->source_url==stripslashes(trim($source_url))){
								$chk_exist_rule = 1;
							}
						}
						
						if(!$chk_exist_rule){						
							$r_insert = $wpdb->insert( 
									$table_name, 
									array( 
										'rtype' => trim(sanitize_text_field($_POST['redirection_type'])),
										'source_url' => stripslashes(trim($source_url)),
										'destination_url' => trim($destination_url),
										'reg_expression' => trim($regexpression_check),
										'time' => current_time( 'mysql' )
									) 
								);
							if($r_insert){
								echo '<div class="record-success">'.__( 'Record added successfully.', 'all-in-one-redirection' ).'</div>';
							}
						}
						else{
							echo '<div class="no-record">'.__( 'Please insert valid source url because of duplicate entry.', 'all-in-one-redirection' ).'</div>';
						}
					}
					else{
						echo '<div class="no-record">'.__( 'Please insert valid source url with slash(/).', 'all-in-one-redirection' ).'</div>';
					}
				}
				else{
					echo '<div class="no-record">'.__( 'Please insert the source url.', 'all-in-one-redirection' ).'</div>';
				}
			}
			else{
				exit;
			}
		}
		
		echo '<div><label>'.__( 'Redirection Type', 'all-in-one-redirection' ).'</label><span><select name="redirection_type"><option value="301">301 (Permanent)</option><option value="302">302 (Temporary)</option></select></span></div>';
		echo '<div><label>'.__( 'Source URL', 'all-in-one-redirection' ).'</label><span><input type="text" name="source_url_insert" placeholder="'.__( 'Eg.', 'all-in-one-redirection' ).' /about.html" /></span></div>';
		echo '<div><label>'.__( 'Destination URL', 'all-in-one-redirection' ).'</label><span><input type="text" name="destination_url_insert" placeholder="'.__( 'Eg.', 'all-in-one-redirection' ).' '.$this->def_setting_site_url.'/about-us/" /></span></div>';
		$nonce_insert = wp_create_nonce('insert-page-redirection');
		echo '<div><label>'.__( 'Regular Expression', 'all-in-one-redirection' ).'</label><span><input type="checkbox" name="reg_expression_check" value="1" /></span></div>';
		echo '<div><input type="hidden" name="insert-nonce" value="'.$nonce_insert.'" /><input type="submit" name="insert_redirection_btn" value="'.__( 'Add Redirection', 'all-in-one-redirection' ).'" /></div>';
		echo '</form>';
		echo '</div>';
		
		echo '<div class="vsz-redirection">';
		
		echo '<div class="vsz-title">'.__( 'Redirection List', 'all-in-one-redirection' ).'</div>';
		
		// set the filter variables
		if(isset($_REQUEST['record-filter']) && !empty($_REQUEST['record-filter'])){
			$filter_key = trim($_REQUEST['keyword']);
			$filter_type = sanitize_text_field($_REQUEST['type']);
			$filter_hide_record = sanitize_text_field($_REQUEST['hide']);
			$filter_reg_record = sanitize_text_field($_REQUEST['regexp']);
		}
		// filter record form
		echo '<div class="filter-record-sec">';
			echo '<form name="filter-record-form" class="filter-record-form" action="" method="GET">';				
				echo '<input type="hidden" name="page" value="all-in-one-redirection" />';				
				?>
                <div class="filter-field search-field">
               		<input type="text" name="keyword" value="<?php if(isset($filter_key)){echo htmlspecialchars(stripslashes($filter_key));} ?>" placeholder="<?php echo __( 'Search Keyword', 'all-in-one-redirection' ); ?>" />
                </div>
                <div class="filter-field select-field">
                    <select name="type">
                        <option value=""><?php echo __( 'Select Type', 'all-in-one-redirection' ); ?></option>
                        <option value="301" <?php if(isset($filter_type) && $filter_type=='301'){echo 'selected="selected"';} ?>>301</option>
                        <option value="302" <?php if(isset($filter_type) && $filter_type=='302'){echo 'selected="selected"';} ?>>302</option>
                    </select>
                </div>
                <div class="filter-field select-field">
                    <select name="hide">
                        <option value=""><?php echo __( 'Select Hidden Records', 'all-in-one-redirection' ); ?></option>
                        <option value="true" <?php if(isset($filter_hide_record) && $filter_hide_record=='true'){echo 'selected="selected"';} ?>><?php echo __( 'True', 'all-in-one-redirection' ); ?></option>
                        <option value="false" <?php if(isset($filter_hide_record) && $filter_hide_record=='false'){echo 'selected="selected"';} ?>><?php echo __( 'False', 'all-in-one-redirection' ); ?></option>
                    </select>
                </div>
                <div class="filter-field select-field">
                    <select name="regexp">
                        <option value=""><?php echo __( 'Select Reg. Exp. Records', 'all-in-one-redirection' ); ?></option>
                        <option value="true" <?php if(isset($filter_reg_record) && $filter_reg_record=='true'){echo 'selected="selected"';} ?>><?php echo __( 'True', 'all-in-one-redirection' ); ?></option>
                        <option value="false" <?php if(isset($filter_reg_record) && $filter_reg_record=='false'){echo 'selected="selected"';} ?>><?php echo __( 'False', 'all-in-one-redirection' ); ?></option>
                    </select>
                </div>
                <div class="filter-field submit-field">
                	<input type="submit" value="<?php echo __( 'Filter', 'all-in-one-redirection' ); ?>" />
                </div>
				<?php
				echo '<input type="hidden" name="record-filter" value="yes" />';
			echo '</form>';
		echo '</div>';
		
		// Delete all records
		if(isset($_POST['delete_all_btn']) && !empty($_POST['delete_all_btn'])){
			if(wp_verify_nonce( sanitize_text_field($_POST['delete-all-nonce']), 'delete-all-page-redirection' )){
				//Changed dated 15-05-2023
				$del_all = $wpdb->query($wpdb->prepare("DELETE FROM $table_name where rtype!=404"));
				if($del_all){
					echo '<div class="record-success">'.__( 'All Record deleted successfully.', 'all-in-one-redirection' ).'</div>';
				}
			}
			else{
				exit;
			}
		}
		
		// Reset Hits Records
		if(isset($_POST['reset_hits_btn']) && !empty($_POST['reset_hits_btn'])){
			if(wp_verify_nonce( sanitize_text_field($_POST['reset-all-hits-nonce']), 'reset-all-hits-redirection' )){
				//Changed dated 15-05-2023			
				$reset_hits = $wpdb->query($wpdb->prepare("update $table_name set hits=0 where rtype!=404"));
				if($reset_hits){
					echo '<div class="record-success">'.__( 'Hits records are reset successfully.', 'all-in-one-redirection' ).'</div>';
				}
			}
			else{
				exit;
			}
		}
		
		// Update all the records
		if(isset($_POST['update_list'])){
			if(wp_verify_nonce( sanitize_text_field($_POST['redirection-list-nonce']), 'list-page-redirection' )){
				$c_list = 0;
				while($c_list<count($_POST['source_url'])){
					$sourceurl = rtrim(trim($_POST['source_url'][$c_list]),'/');
					$sourceurl = str_ireplace($this->def_setting_site_url,'',trim($sourceurl));
					if(empty($sourceurl)){
						$sourceurl = '/';
					}
					
					if(!isset($_POST['destination_url'][$c_list]) || empty($_POST['destination_url'][$c_list])){
						$destinationurl = $this->def_setting_site_url.'/';
					}
					else{
						$destinationurl = esc_url($_POST['destination_url'][$c_list]);
					}
					if(isset($_POST['check_reg_exp_btn'][sanitize_text_field($_POST['redirection_id'][$c_list])])){
						$regexpression = sanitize_text_field($_POST['check_reg_exp_btn'][sanitize_text_field($_POST['redirection_id'][$c_list])]);
						if($regexpression!=1){
							$regexpression=0;
						}
					}
					else{
						$regexpression=0;
					}
					
					if(isset($_POST['check_hide_btn'][sanitize_text_field($_POST['redirection_id'][$c_list])])){
						$redirectionhide = sanitize_text_field($_POST['check_hide_btn'][sanitize_text_field($_POST['redirection_id'][$c_list])]);
						if($redirectionhide!=1){
							$redirectionhide=0;
						}
					}
					else{
						$redirectionhide=0;
					}
					
					$defsource = trim($_POST['source_url'][$c_list]);
					if(isset($defsource) && !empty($defsource) && substr($sourceurl,0,1) == '/'){
						$sourceurl = str_ireplace(' ', '%20', trim($sourceurl));
						
						$wpdb->update(
							$table_name,
							array(
								'rtype' => trim(sanitize_text_field($_POST['redirection_type'][$c_list])),
								'source_url' => stripslashes(trim($sourceurl)),
								'destination_url' => trim($destinationurl),
								'reg_expression' => trim($regexpression),
								'hide_url' => trim($redirectionhide)
							), 
							array( 'id' => sanitize_text_field($_POST['redirection_id'][$c_list]) )
						);
					}
					$c_list++;
				}
				echo '<div class="record-success">'.__( 'Record updated successfully.', 'all-in-one-redirection' ).'</div>';
			}
			else{
				exit;
			}
		}
		elseif(isset($_POST['delete_selected_list'])){
			if(wp_verify_nonce( sanitize_text_field($_POST['redirection-list-nonce']), 'list-page-redirection' )){				
				if(!empty($_POST['check_delete_btns'])){
					$page_id_list = implode(",",array_map('sanitize_text_field',$_POST['check_delete_btns']));
					//Changed dated 15-05-2023
					$del_record = $wpdb->query($wpdb->prepare("DELETE from $table_name where id IN ({$page_id_list})"));
					if($del_record){
						echo '<div class="record-success">'.__( 'Record deleted successfully.', 'all-in-one-redirection' ).'</div>';
					}
				}
			}
			else{
				exit;
			}
		}
		
		// filter record fetch
		$filter_where_condition = '';		
		if(isset($_REQUEST['record-filter']) && !empty($_REQUEST['record-filter'])){
				if(isset($filter_key) && !empty($filter_key)){
					$search_str   = '%' . $wpdb->esc_like($filter_key) . '%';
					$filter_where_condition .= $wpdb->prepare(" AND (source_url LIKE %s OR destination_url LIKE %s)", $search_str, $search_str);
				}
				
				if(isset($filter_type) && !empty($filter_type)){
					$filter_where_condition .= " AND rtype=$filter_type";
				}
				
				if(isset($filter_hide_record) && !empty($filter_hide_record)){
					if($filter_hide_record=='true'){
						$filter_where_condition .= " AND hide_url=1";
					}
					elseif($filter_hide_record=='false'){
						$filter_where_condition .= " AND hide_url=0";
					}
				}
				
				if(isset($filter_reg_record) && !empty($filter_reg_record)){
					if($filter_reg_record=='true'){
						$filter_where_condition .= " AND reg_expression=1";
					}
					elseif($filter_reg_record=='false'){
						$filter_where_condition .= " AND reg_expression=0";
					}
				}
		}
		
		//No. of page  - Pagination
		$no_of_total_page = $wpdb->get_var($wpdb->prepare("select COUNT(id) as no_of_total_record from $table_name where rtype!=404$filter_where_condition"));
		$no_of_page = ceil($wpdb->get_var($wpdb->prepare("select COUNT(id) as no_of_record from $table_name where rtype!=404$filter_where_condition"))/$post_per_page);
		if(isset($_GET['paged']) && $_GET['paged']!=0){
			$current_page = sanitize_text_field($_GET['paged']);
			$record_start = ($current_page-1)*$post_per_page;
		}
		else{
			$current_page = 1;
			$record_start = ($current_page-1)*$post_per_page;			
		}
		
		$select_query = $wpdb->prepare("SELECT * FROM $table_name where rtype!=404$filter_where_condition order by id DESC limit $record_start,$post_per_page");
		$redirection_list = $wpdb->get_results($select_query);
		if($redirection_list){
		echo '<form name="redirection-delete-all-form" id="redirection-delete-all-form" action="" method="POST">';
			$nonce_delete_all = wp_create_nonce('delete-all-page-redirection');
			echo '<input type="hidden" name="delete-all-nonce" value="'.$nonce_delete_all.'" />';
			echo '<input type="submit" name="delete_all_btn" value="'.__( 'Delete All', 'all-in-one-redirection' ).'" />';
		echo '</form>';
		
		echo '<form name="redirection-hits-reset" id="redirection-hits-reset" action="" method="POST">';
			$nonce_hits_reset = wp_create_nonce('reset-all-hits-redirection');
			echo '<input type="hidden" name="reset-all-hits-nonce" value="'.$nonce_hits_reset.'" />';
			echo '<input type="submit" name="reset_hits_btn" value="'.__( 'Reset Hits', 'all-in-one-redirection' ).'" />';
		echo '</form>';
		
		echo '<form name="redirection-list-form" class="redirection-list-form" action="" method="POST">';
		echo '<table class="redirection-list">';
			echo '<thead>';
			echo '<tr>';
				echo '<th class="del_checkbox"><input type="checkbox" id="all_check_delete" name="all_check_delete" value="TRUE" /></th>';
				echo '<th class="rtype">'.__( 'Type', 'all-in-one-redirection' ).'</th>';
				echo '<th class="surl">'.__( 'Source URL', 'all-in-one-redirection' ).'<span>('.__( 'Eg.', 'all-in-one-redirection' ).' /about.html)</span></th>';
				echo '<th class="durl">'.__( 'Destination URL', 'all-in-one-redirection' ).'<span>('.__( 'Eg.', 'all-in-one-redirection' ).' '.$this->def_setting_site_url.'/about-us/)</span></th>';
				
				echo '<th class="hits">'.__( 'Hits', 'all-in-one-redirection' ).'</th>';				
				echo '<th class="setting">'.__( 'Setting', 'all-in-one-redirection' ).'</th>';
				
			echo '</tr>';
			echo '</thead>';
			echo '</tbody>';
			$tr_color=0;
			$tr_color_class='odd';
			foreach($redirection_list as $key => $r_list){
				$tr_color++;
				if(fmod($tr_color,2)==0){
					$tr_color_class='even';
				}
				else{
					$tr_color_class='odd';
				}
				if($r_list->hide_url=='1'){
					$tr_color_class.=' hide-record';
				}
				
				echo '<tr class="'.$tr_color_class.'">';
				
				echo '<td class="del_checkbox"><input type="checkbox" name="check_delete_btns[]" value="'.esc_html($r_list->id).'" /><input type="hidden" name="redirection_id[]" value="'.esc_html($r_list->id).'" /></td>';
				
				echo '<td class="rtype">';
				?>			
					<select name="redirection_type[]">
                    	<option value="301" <?php if($r_list->rtype=='301'){echo 'selected="selected"';} ?>>301</option>
                        <option value="302" <?php if($r_list->rtype=='302'){echo 'selected="selected"';} ?>>302</option>
                    </select>
                <?php
				echo '</td>';
				echo '<td class="surl"><input type="text" name="source_url[]" value="'.esc_html($r_list->source_url).'" /></td>';
				echo '<td class="durl"><input type="text" name="destination_url[]" value="'.esc_html($r_list->destination_url).'" /></td>';
				echo '<td class="hits">'.esc_html($r_list->hits).'</td>';
				echo '<td class="setting"><span class="list-setting-btn" title="'.__( 'More Setting', 'all-in-one-redirection' ).'"></span></td>';
				echo '</tr>';
				echo '<tr class="add-more-info '.$tr_color_class.'">';
					echo '<td colspan="6">';
					?>
						<div><span class="lbl"><?php echo __( 'Regular Expression', 'all-in-one-redirection' ); ?></span><span class="lbl-val"><input type="checkbox" name="check_reg_exp_btn[<?php echo esc_html($r_list->id); ?>]" <?php if($r_list->reg_expression=='1'){echo 'checked="checked"';} ?> value="1" /></span></div>
                        <div><span class="lbl"><?php echo __( 'Hide', 'all-in-one-redirection' ); ?></span><span class="lbl-val"><input type="checkbox" name="check_hide_btn[<?php echo esc_html($r_list->id); ?>]" <?php if($r_list->hide_url=='1'){echo 'checked="checked"';} ?> value="1" /></span></div>
                        <div><span class="lbl"><?php echo __( 'Last Access', 'all-in-one-redirection' ); ?></span><span class="lbl-val"><?php if($r_list->last_access=='0000-00-00 00:00:00'){echo '-';}else{echo date('M d, Y h:i A',strtotime($r_list->last_access));} ?></span></div>
                        <div><span class="lbl"><?php echo __( 'Referrer', 'all-in-one-redirection' ); ?></span><span class="lbl-val"><?php if(isset($r_list->referrer)){echo $r_list->referrer;}else{echo '-';} ?></span></div>
                        <div><span class="lbl"><?php echo __( 'Access IP', 'all-in-one-redirection' ); ?></span><span class="lbl-val"><?php if(isset($r_list->access_ip)){echo $r_list->access_ip;}else{echo '-';} ?></span></div>
					<?php
                    echo '</td>';
				echo '</tr>';
			}
			echo '</tbody>';
		echo '</table>';
		$nonce_update = wp_create_nonce('list-page-redirection');
		echo '<input type="hidden" name="redirection-list-nonce" value="'.$nonce_update.'" />';
		echo '<input type="submit" name="update_list" value="'.__( 'Update', 'all-in-one-redirection' ).'" />';
		echo '<input type="submit" id="delete-redirection" name="delete_selected_list" class="marl15" value="'.__( 'Delete', 'all-in-one-redirection' ).'" />';
		echo '<span class="total-no-of-record"><strong>'.__( 'Total', 'all-in-one-redirection' ).':</strong> '.$no_of_total_page.' '.__( 'Records', 'all-in-one-redirection' ).'</span>';
		echo '</form>';
		
		// custom pagination
		$pagination_info = array();
		$pagination_info['total_pages'] = $no_of_page;
		$pagination_info['curr_page'] = $current_page;
		
		if($no_of_page>1){
			$filter_pagination_condition = '';		
			if(isset($_REQUEST['record-filter']) && !empty($_REQUEST['record-filter'])){
					$filter_pagination_condition .= '&keyword='.stripslashes($filter_key);
					$filter_pagination_condition .= '&type='.$filter_type;
					$filter_pagination_condition .= '&hide='.$filter_hide_record;
					$filter_pagination_condition .= '&regexp='.$filter_reg_record;
					$filter_pagination_condition .= '&record-filter=yes';					
			}
			echo '<div class="pagination-sec">';
				echo '<ul>';
					//If the current page is more than 1, show the First and Previous links
					if($pagination_info['curr_page'] > 1){
						echo '<li class="extra-page first-page"><a href="'.menu_page_url('all-in-one-redirection',false).$filter_pagination_condition.'&paged=1" title="Page 1">'.__( 'First', 'all-in-one-redirection' ).'</a></li>';
						echo '<li class="extra-page prev-page"><a href="'.menu_page_url('all-in-one-redirection',false).$filter_pagination_condition.'&paged='.($pagination_info['curr_page'] - 1).'" title="Page '.($pagination_info['curr_page'] - 1).'">'.__( 'Prev', 'all-in-one-redirection' ).'</a></li>';
					}
					else{
						echo '<li class="extra-page first-page disable-page"><a href="javascript:;">'.__( 'First', 'all-in-one-redirection' ).'</a></li>';
						echo '<li class="extra-page prev-page disable-page"><a href="javascript:;">'.__( 'Prev', 'all-in-one-redirection' ).'</a></li>';
					}
	
					//setup starting point
	
					//$max_pages is equal to number of links shown
					$max_pages = 7;
					if($pagination_info['curr_page'] < $max_pages)
						$sp = 1;
					elseif($pagination_info['curr_page'] >= ($pagination_info['total_pages'] - floor($max_pages / 2)) )
						$sp = $pagination_info['total_pages'] - $max_pages + 1;
					elseif($pagination_info['curr_page'] >= $max_pages)
						$sp = $pagination_info['curr_page']  - floor($max_pages/2);
	
					//If the current page >= $max_pages then show link to 1st page
					/*
					if($pagination_info['curr_page'] >= $max_pages){
						echo '<li><a href="'.menu_page_url('all-in-one-redirection',false).'&paged=1">1</a></li>';
						echo '..';
					}
					*/
					
					//Loop though max_pages number of pages shown and show links either side equal to $max_pages / 2				
					for($i = $sp; $i <= ($sp + $max_pages -1); $i++){
						if($i > $pagination_info['total_pages']){
							continue;
						}
						if($pagination_info['curr_page'] == $i){
						   echo '<li class="active"><a href="'.menu_page_url('all-in-one-redirection',false).$filter_pagination_condition.'&paged='.$i.'">'.$i.'</a></li>';
						}
						else{
							echo '<li><a href="'.menu_page_url('all-in-one-redirection',false).$filter_pagination_condition.'&paged='.$i.'">'.$i.'</a></li>';
						}
					}
	
					//If the current page is less than say the last page minus $max_pages pages divided by 2			    
					/*
					if($pagination_info['curr_page'] < ($pagination_info['total_pages'] - floor($max_pages / 2))){
						echo '..';
						echo '<li><a href="'.menu_page_url('all-in-one-redirection',false).'&paged='.$pagination_info["total_pages"].'">'.$pagination_info["total_pages"].'</a></li>';
					}
					*/
	
					//Show last two pages if we're not near them    
					if($pagination_info['curr_page'] < $pagination_info['total_pages']){                
						echo '<li class="extra-page next-page"><a href="'.menu_page_url('all-in-one-redirection',false).$filter_pagination_condition.'&paged='.($pagination_info["curr_page"] + 1) .'">'.__( 'Next', 'all-in-one-redirection' ).'</a></li>';
						
						echo '<li class="extra-page last-page"><a href="'.menu_page_url('all-in-one-redirection',false).$filter_pagination_condition.'&paged='.$pagination_info["total_pages"].'">'.__( 'Last', 'all-in-one-redirection' ).'</a></li>';
					}
					else{
						echo '<li class="extra-page next-page disable-page"><a href="javascript:;">'.__( 'Next', 'all-in-one-redirection' ).'</a></li>';
						
						echo '<li class="extra-page last-page disable-page"><a href="javascript:;">'.__( 'Last', 'all-in-one-redirection' ).'</a></li>';
					}
				echo '</ul>';
			echo '</div>';
		} // END custom pagination
				
		}
		else{
			if(!isset($del_all)){
			echo '<div class="no-record">'.__( 'No record found.', 'all-in-one-redirection' ).'</div>';
			}
		}
		
		echo '</div>';
	}

	/*
	* 404 pages list
	* Add 404 page in redirection
	*/
	public function vsz_admin_404_redirection_list_screen(){
		global $wpdb;
		$post_per_page = 20;
		$no_of_page = 0;
		$current_page = 0;
		$record_start = 0;
		
		$table_name = $this->db_table_name;
		
		echo '<div class="vsz-redirection">';
		echo '<div class="vsz-title">'.__( '404 Pages List', 'all-in-one-redirection' ).'</div>';
		
		// set the filter variables
		if(isset($_REQUEST['record-404-filter']) && !empty($_REQUEST['record-404-filter'])){
			$filter_404_key = trim($_REQUEST['keyword']);
			//var_dump($filter_404_key);exit;
		}
		
		// filter 404 record form
		echo '<div class="filter-record-sec">';
			echo '<form name="filter-404-record-form" class="filter-record-form filter-404-record" action="" method="GET">';				
				echo '<input type="hidden" name="page" value="all-in-one-redirection-404-pages-list" />';				
				?>
                <div class="filter-field search-field">
               		<input type="text" name="keyword" value="<?php if(isset($filter_404_key)){echo htmlspecialchars(stripslashes($filter_404_key));} ?>" placeholder="<?php echo __( 'Search Keyword', 'all-in-one-redirection' ); ?>" />
                </div>
                <div class="filter-field submit-field">
                	<input type="submit" value="<?php echo __( 'Filter', 'all-in-one-redirection' ); ?>" />
                </div>
				<?php
				echo '<input type="hidden" name="record-404-filter" value="yes" />';
			echo '</form>';
		echo '</div>';
		
		// delete selected all 404 page records
		if(isset($_POST['delete-all-404-pages']) && !empty($_POST['delete-all-404-pages'])){
			if(wp_verify_nonce( sanitize_text_field($_POST['delete-404-page-nonce']), 'delete-404-page-list' )){
				//Changed dated 15-05-2023	
				$del_404_page_all = $wpdb->query($wpdb->prepare("DELETE FROM $table_name where rtype=404"));
				if($del_404_page_all){
					echo '<div class="record-success">'.__( 'All Record deleted successfully.', 'all-in-one-redirection' ).'</div>';
				}
			}
			else{
				exit;
			}
		}
		
		// delete selected 404 page records
		if(isset($_POST['delete-404-pages']) && !empty($_POST['delete-404-pages'])){
			if(wp_verify_nonce( sanitize_text_field($_POST['delete-404-page-nonce']), 'delete-404-page-list' )){
				if(!empty($_POST['check_404_page_delete_btns'])){
					$page_404_id_list = implode(",",array_map('sanitize_text_field',$_POST['check_404_page_delete_btns']));
					$del_404_page = $wpdb->query($wpdb->prepare("DELETE from $table_name where id IN ({$page_404_id_list})"));
					if($del_404_page){
						echo '<div class="record-success">'.__( 'Record deleted successfully.', 'all-in-one-redirection' ).'</div>';
					}
				}
			}
			else{
				exit;
			}
		}
		
		// Save new 404 page record
		if(isset($_POST['insert_404_page_redirection_btn'])){
			$source_url = rtrim(trim($_POST['source_url_insert']),'/');
			$source_url = str_ireplace($this->def_setting_site_url,'',trim($source_url));				
			if(empty($source_url)){
				$source_url = '/';
			}
			
			if(!isset($_POST['destination_url_insert']) || empty($_POST['destination_url_insert'])){
				$destination_url = $this->def_setting_site_url.'/';
			}
			else{
				$destination_url = esc_url($_POST['destination_url_insert']);
			}
			$regexpression_check = sanitize_text_field($_POST['reg_expression_check']);
			if($regexpression_check!=1){
				$regexpression_check=0;
			}
					
			if(wp_verify_nonce( sanitize_text_field($_POST['insert-404-page-nonce']), 'insert-404-page-redirection' )){
				$def_source = trim($_POST['source_url_insert']);
				
				if(isset($def_source) && !empty($def_source)){
					if(substr($source_url,0,1) == '/'){
						$source_url = str_ireplace(' ', '%20', trim($source_url));
						
						
						
						$chk_exist_rule = 0;
						//Changed dated 15-05-2023
						$source_url = stripslashes(trim($source_url));
						$chk_exist_rule_data = $wpdb->get_row($wpdb->prepare("select * from {$table_name} where source_url=%s and reg_expression=%s and hide_url=0 and rtype!=404 order by id DESC",$source_url, $regexpression_check));	
						if($chk_exist_rule_data){
							if($chk_exist_rule_data->source_url==stripslashes(trim($source_url))){
								$chk_exist_rule = 1;
							}
						}
						
						if(!$chk_exist_rule){
							$r_insert = $wpdb->insert( 
									$table_name, 
									array( 
										'rtype' => trim(sanitize_text_field($_POST['redirection_type'])),
										'source_url' => stripslashes(trim($source_url)),
										'destination_url' => trim($destination_url),
										'reg_expression' => trim($regexpression_check),
										'time' => current_time( 'mysql' )
									) 
								);					
							if($r_insert){
								//Changed dated 15-05-2023
								$del_404_page_all = $wpdb->query($wpdb->prepare("DELETE FROM $table_name where id=%d", sanitize_text_field($_POST['page_404_id'])));
								echo '<div class="record-success">'.__( 'Record added successfully.', 'all-in-one-redirection' ).'</div>';
							}
						}
						else{
							echo '<div class="no-record marb15">'.__( 'Please insert valid source url because of duplicate entry.', 'all-in-one-redirection' ).'</div>';
						}
					}
					else{
						echo '<div class="no-record marb15">'.__( 'Please insert valid source url with slash(/).', 'all-in-one-redirection' ).'</div>';
					}
				}
				else{
					echo '<div class="no-record marb15">'.__( 'Please insert the source url.', 'all-in-one-redirection' ).'</div>';
				}
			}
			else{
				exit;
			}
		}
		
		//Changed dated 15-05-2023
		// filter record fetch
		$filter_404_where_condition = '';		
		if(isset($_REQUEST['record-404-filter']) && !empty($_REQUEST['record-404-filter'])){
				if(isset($filter_404_key) && !empty($filter_404_key)){
					$search_str   = '%' . $wpdb->esc_like($filter_404_key) . '%';
					$filter_404_where_condition .= $wpdb->prepare(" AND source_url LIKE %s",$search_str);
				}
		}
		
		//No. of page  - Pagination
		$no_of_total_page = $wpdb->get_var($wpdb->prepare("select COUNT(id) as no_of_total_record from $table_name where rtype=404 $filter_404_where_condition"));
		$no_of_page = ceil($wpdb->get_var($wpdb->prepare("select COUNT(id) as no_of_record from $table_name where rtype=404 $filter_404_where_condition"))/$post_per_page);
		if(isset($_GET['paged']) && $_GET['paged']!=0){
			$current_page = sanitize_text_field($_GET['paged']);
			$record_start = ($current_page-1)*$post_per_page;
		}
		else{
			$current_page = 1;
			$record_start = ($current_page-1)*$post_per_page;
			
		}
		
		$select_404_page_query = $wpdb->prepare("SELECT * FROM $table_name where rtype=404 $filter_404_where_condition order by last_access DESC limit $record_start,$post_per_page");
		$page_404_data = $wpdb->get_results($select_404_page_query);
		
		if($page_404_data){
			echo '<form name="list-404-pages-form" class="list-404-pages-form" action="" method="POST">';
			echo '<table class="redirection-list list-404-pages">';
				echo '<thead>';
					echo '<tr>';
						echo '<th class="del_404_checkbox"><input type="checkbox" id="all_check_404_delete" name="all_check_404_delete" value="TRUE" /></th>';
						echo '<th class="acc404_date">'.__( 'Access Date', 'all-in-one-redirection' ).'</th>';
						echo '<th class="s404url">'.__( 'Source URL', 'all-in-one-redirection' ).'</th>';
						echo '<th class="referrer404">'.__( 'Referrer', 'all-in-one-redirection' ).'</th>';
						echo '<th class="ip404">'.__( 'Access IP', 'all-in-one-redirection' ).'</th>';
						echo '<th class="hits404">'.__( 'Hits', 'all-in-one-redirection' ).'</th>';
						echo '<th class="action404">'.__( 'Action', 'all-in-one-redirection' ).'</th>';
					echo '</tr>';
				echo '</thead>';
				
				echo '<tbody>';
				$tr_color=0;
				$tr_color_class='odd';
				foreach($page_404_data as $key => $page_404_data_item){
					$tr_color++;
					if(fmod($tr_color,2)==0){
						$tr_color_class='even';
					}
					else{
						$tr_color_class='odd';
					}
					echo '<tr class="'.$tr_color_class.'">';
							echo '<td class="del_404_checkbox"><input type="checkbox" name="check_404_page_delete_btns[]" value="'.esc_html($page_404_data_item->id).'" /></td>';
							echo '<td class="acc404_date">'.date('M d, Y',strtotime($page_404_data_item->last_access)).'<br />'.date('h:i A',strtotime($page_404_data_item->last_access)).'</td>';
							echo '<td class="s404url surlid-'.esc_html($page_404_data_item->id).'">'.$page_404_data_item->source_url.'</td>';
							echo '<td class="referrer404">'.$page_404_data_item->referrer.'</td>';
							echo '<td class="ip404">'.$page_404_data_item->access_ip.'</td>';
							echo '<td class="hits404">'.$page_404_data_item->hits.'</td>';
							echo '<td class="action404"><span class="list-add-404-rule-btn" page_id="'.esc_html($page_404_data_item->id).'" title="'.__( 'Add Redirection', 'all-in-one-redirection' ).'"></span></td>';
					echo '</tr>';
				}
				echo '</tbody>';
			echo '</table>';
			$nonce_delete_404_page = wp_create_nonce('delete-404-page-list');
			echo '<input type="hidden" name="delete-404-page-nonce" value="'.$nonce_delete_404_page.'" />';
			echo '<input type="submit" id="delete-404-pages" name="delete-404-pages" value="'.__( 'Delete', 'all-in-one-redirection' ).'" />';
			echo '<input type="submit" id="delete-all-404-pages" class="marl15" name="delete-all-404-pages" value="'.__( 'Delete All', 'all-in-one-redirection' ).'" />';
			echo '<span class="total-no-of-record"><strong>'.__( 'Total', 'all-in-one-redirection' ).':</strong> '.$no_of_total_page.' '.__( 'Records', 'all-in-one-redirection' ).'</span>';
			echo '</form>';
			
			// custom pagination
			$pagination_info = array();
			$pagination_info['total_pages'] = $no_of_page;
			$pagination_info['curr_page'] = $current_page;
			
			if($no_of_page>1){
				$filter_404_pagination_condition = '';		
				if(isset($_REQUEST['record-404-filter']) && !empty($_REQUEST['record-404-filter'])){
						$filter_404_pagination_condition .= '&keyword='.stripslashes($filter_404_key);
						$filter_404_pagination_condition .= '&record-404-filter=yes';					
				}
				echo '<div class="pagination-sec">';
					echo '<ul>';
						//If the current page is more than 1, show the First and Previous links
						if($pagination_info['curr_page'] > 1){
							echo '<li class="extra-page first-page"><a href="'.menu_page_url('all-in-one-redirection-404-pages-list',false).$filter_404_pagination_condition.'&paged=1" title="Page 1">'.__( 'First', 'all-in-one-redirection' ).'</a></li>';
							echo '<li class="extra-page prev-page"><a href="'.menu_page_url('all-in-one-redirection-404-pages-list',false).$filter_404_pagination_condition.'&paged='.($pagination_info['curr_page'] - 1).'" title="Page '.($pagination_info['curr_page'] - 1).'">'.__( 'Prev', 'all-in-one-redirection' ).'</a></li>';
						}
						else{
							echo '<li class="extra-page first-page disable-page"><a href="javascript:;">'.__( 'First', 'all-in-one-redirection' ).'</a></li>';
							echo '<li class="extra-page prev-page disable-page"><a href="javascript:;">'.__( 'Prev', 'all-in-one-redirection' ).'</a></li>';
						}
		
						//setup starting point
		
						//$max_pages is equal to number of links shown
						$max_pages = 7;
						if($pagination_info['curr_page'] < $max_pages)
							$sp = 1;
						elseif($pagination_info['curr_page'] >= ($pagination_info['total_pages'] - floor($max_pages / 2)) )
							$sp = $pagination_info['total_pages'] - $max_pages + 1;
						elseif($pagination_info['curr_page'] >= $max_pages)
							$sp = $pagination_info['curr_page']  - floor($max_pages/2);
		
						//If the current page >= $max_pages then show link to 1st page
						/*
						if($pagination_info['curr_page'] >= $max_pages){
							echo '<li><a href="'.menu_page_url('all-in-one-redirection-404-pages-list',false).'&paged=1">1</a></li>';
							echo '..';
						}
						*/
						
						//Loop though max_pages number of pages shown and show links either side equal to $max_pages / 2				
						for($i = $sp; $i <= ($sp + $max_pages -1); $i++){
							if($i > $pagination_info['total_pages']){
								continue;
							}
							if($pagination_info['curr_page'] == $i){
							   echo '<li class="active"><a href="'.menu_page_url('all-in-one-redirection-404-pages-list',false).$filter_404_pagination_condition.'&paged='.$i.'">'.$i.'</a></li>';
							}
							else{
								echo '<li><a href="'.menu_page_url('all-in-one-redirection-404-pages-list',false).$filter_404_pagination_condition.'&paged='.$i.'">'.$i.'</a></li>';
							}
						}
		
						//If the current page is less than say the last page minus $max_pages pages divided by 2			    
						/*
						if($pagination_info['curr_page'] < ($pagination_info['total_pages'] - floor($max_pages / 2))){
							echo '..';
							echo '<li><a href="'.menu_page_url('all-in-one-redirection-404-pages-list',false).'&paged='.$pagination_info["total_pages"].'">'.$pagination_info["total_pages"].'</a></li>';
						}
						*/
		
						//Show last two pages if we're not near them    
						if($pagination_info['curr_page'] < $pagination_info['total_pages']){                
							echo '<li class="extra-page next-page"><a href="'.menu_page_url('all-in-one-redirection-404-pages-list',false).$filter_404_pagination_condition.'&paged='.($pagination_info["curr_page"] + 1) .'">'.__( 'Next', 'all-in-one-redirection' ).'</a></li>';
							
							echo '<li class="extra-page last-page"><a href="'.menu_page_url('all-in-one-redirection-404-pages-list',false).$filter_404_pagination_condition.'&paged='.$pagination_info["total_pages"].'">'.__( 'Last', 'all-in-one-redirection' ).'</a></li>';
						}
						else{
							echo '<li class="extra-page next-page disable-page"><a href="javascript:;">'.__( 'Next', 'all-in-one-redirection' ).'</a></li>';						
							echo '<li class="extra-page last-page disable-page"><a href="javascript:;">'.__( 'Last', 'all-in-one-redirection' ).'</a></li>';
						}
					echo '</ul>';
				echo '</div>';
			} // END custom pagination
			
			
			echo '<div id="vsz-insert-form-404-page-sec" class="vsz-redirection vsz-insert-form-sec">';
			echo '<div class="vsz-title">'.__( 'Add New Redirection', 'all-in-one-redirection' ).'</div>';		
				echo '<form id="redirection_insert_form" class="redirection_insert_404_page_form" name="redirection_insert_404_page_form" action="" method="POST">';	
					echo '<div><label>'.__( 'Redirection Type', 'all-in-one-redirection' ).'</label><span><select name="redirection_type"><option value="301">301 (Permanent)</option><option value="302">302 (Temporary)</option></select></span></div>';
					echo '<div><label>'.__( 'Source URL', 'all-in-one-redirection' ).'</label><span><input type="text" name="source_url_insert" readonly="readonly" placeholder="'.__( 'Eg.', 'all-in-one-redirection' ).' /about.html" /></span></div>';
					echo '<div><label>'.__( 'Destination URL', 'all-in-one-redirection' ).'</label><span><input type="text" name="destination_url_insert" placeholder="'.__( 'Eg.', 'all-in-one-redirection' ).' '.$this->def_setting_site_url.'/about-us/" /></span></div>';					
					echo '<div><label>'.__( 'Regular Expression', 'all-in-one-redirection' ).'</label><span><input type="checkbox" name="reg_expression_check" value="1" /></span></div>';
					$nonce_404_page_insert = wp_create_nonce('insert-404-page-redirection');
					echo '<div><input type="hidden" name="insert-404-page-nonce" value="'.$nonce_404_page_insert.'" /><input type="hidden" name="page_404_id" value="0" /><input type="submit" name="insert_404_page_redirection_btn" value="'.__( 'Add Redirection', 'all-in-one-redirection' ).'" /></div>';
				echo '</form>';
			echo '</div>';			
			
		}
		else{
			if(!isset($del_404_page_all)){
				echo '<div class="no-record">'.__( 'No record found.', 'all-in-one-redirection' ).'</div>';
			}
		}
		
		echo '</div>';
	}
	
	/*
	* Tool - Import/Export Functionality
	*/
	public function vsz_admin_page_redirection_tool_screen(){
		global $wpdb;
		$table_name = $this->db_table_name;		
		
		echo '<div class="vsz-redirection">';
		echo '<div class="vsz-redirection-import-export-sec">';
		
		echo '<div class="file-import-sec">';
		echo '<div class="vsz-title">'.__( 'Redirection Bulk Import', 'all-in-one-redirection' ).'</div>';
		
		if(isset($_POST['import_file_redirection_btn']) && !empty($_POST['import_file_redirection_btn'])){
			if(wp_verify_nonce( sanitize_text_field($_POST['file-import-nonce']), 'import-file-page-redirection' )){
				require_once plugin_dir_path( __FILE__ ).'class/csv.class.php';
				
				$flag=1;
				$error_msg="";
				
				$import_file_name = $_FILES['redirection-file']['name'];
								
				$file_path = plugin_dir_path(dirname(dirname( __FILE__ ))).'upload/import/';
				
				$file_basename = substr($import_file_name, 0, strripos($import_file_name, '.')); // get file name 
				$file_ext = substr($import_file_name, strripos($import_file_name, '.')); // get file extention
				
				$allowed_file_types = array('.csv');
				
				if($import_file_name){
					if(in_array($file_ext,$allowed_file_types)){
						
						$newfilename = $file_path.'all-in-one-redirection-import-'.date('Ymdhis').$file_ext;
						
						if(move_uploaded_file($_FILES["redirection-file"]["tmp_name"], $newfilename)){							
							$import_csv_file =  $newfilename; 
				
							if (($handle = fopen($import_csv_file, "r")) !== FALSE) {
								
								$objCsv = new CSV($import_csv_file,"R");
								
								$header = array("Codes","Status");
												
								//Seperating the values by semicolon in csv
								$row_count=0;
								$skip_record_list=0;
								$skip_record_flag=true;
								while ($csv_data = $objCsv->GetArray(false,false,true)){
									$row_count++;
									if($row_count!=1){
										$num_field = count($csv_data);
										if($num_field == 5){
											if(!empty($csv_data[0])){
												$flag=1;
												
												$file_source_url = rtrim($csv_data[1],'/');
												$def_source = trim($csv_data[1]);
												
												$file_source_url = str_ireplace($this->def_setting_site_url,'',trim($file_source_url));
												if(empty($file_source_url)){
													$file_source_url = '/';
												}
					
												if(!isset($csv_data[2]) || empty($csv_data[2])){
													$file_destination_url = $this->def_setting_site_url.'/';
												}
												else{
													$file_destination_url = esc_url($csv_data[2]);
												}
												
												$redirection_type = trim($csv_data[0]);
												if($redirection_type!=301 && $redirection_type!=302){
													$redirection_type=301;
												}
												
												$redirection_reg_expression = trim($csv_data[3]);
												if($redirection_reg_expression!=1){
													$redirection_reg_expression=0;
												}
												
												$redirection_hide = trim($csv_data[4]);
												if($redirection_hide!=1){
													$redirection_hide=0;
												}
												
												$skip_record_list++;
												
												
												$chk_exist_rule = 0;
												//changed dated 15-05-2023
												$file_source_url = stripslashes(trim($file_source_url));
												$chk_exist_rule_data = $wpdb->get_row($wpdb->prepare("select * from {$table_name} where source_url=%s and reg_expression=%s and hide_url=%s and rtype!=404 order by id DESC",$file_source_url, $redirection_reg_expression, $redirection_hide));
		
												if($chk_exist_rule_data){
													if($chk_exist_rule_data->source_url==stripslashes(trim($file_source_url))){
														$chk_exist_rule = 1;
													}
												}
												
												if(isset($def_source) && !empty($def_source) && substr($file_source_url,0,1) == '/' && !$chk_exist_rule){
													$wpdb->insert( 
														$table_name, 
														array( 
															'rtype' => $redirection_type,
															'source_url' => stripslashes(trim($file_source_url)),
															'destination_url' => trim($file_destination_url),
															'reg_expression' => $redirection_reg_expression,
															'hide_url' => $redirection_hide,
															'time' => current_time( 'mysql' )
														) 
													);
												}
												else{													
													if($skip_record_flag){
														echo '<div class="skip-record-title">'.__( 'Below records are skipped because duplicate entry or source url is not valid. So, please check it.', 'all-in-one-redirection' ).'</div>';
														$skip_record_flag=false;
													}
													echo '<div class="skip-record-item"><strong>'.__( 'Index ', 'all-in-one-redirection').($skip_record_list+1).':</strong> '.$csv_data[1].'</div>';
												}
											}
											
										}
										else{
											$flag=0;
											$error_msg = __( 'Number of fields in csv file does not matched and file must be separated with comma.', 'all-in-one-redirection' );
											}
									}
								}									
							}
							else{
								$flag=0;
								$error_msg = __( 'File permission not valid.', 'all-in-one-redirection' );
							}
						}
						else{
							$flag=0;
							$error_msg = __( 'Folder permission not valid.', 'all-in-one-redirection' );
						}
					}
					else{
						$flag=0;
						$error_msg = __( 'Invalid file type. Please upload the csv file.', 'all-in-one-redirection' );
					}
				}
				else{
					$flag=0;
					$error_msg = __( 'Please upload the file.', 'all-in-one-redirection' );
					}
					
				if($flag==0){
					echo '<div class="no-record marb15">'.$error_msg.'</div>';
				}
				else{
					echo '<div class="record-success mart10">'.__( 'All records are added successfully.', 'all-in-one-redirection' ).'</div>';
						//import csv delete from file path var import_csv_file
						if(isset($import_csv_file) && !empty($import_csv_file)){
						unlink($import_csv_file);
					}
				}
				
			}
			else{
				echo '<div class="no-record marb15">'.__( 'There is some technical problem. Please try again.', 'all-in-one-redirection' ).'</div>';
			}
		}
		echo '<form id="redirection_import_form" name="redirection_import_form" action="" method="POST" enctype="multipart/form-data">';
			$example_csv = plugin_dir_url(dirname(dirname( __FILE__))).'upload/example/redirection-example.csv';
			echo '<div class="import-lbl"><label>'.__( 'Select CSV File', 'all-in-one-redirection' ).'<a href="'.$example_csv.'" class="example-file">'.__( 'Example CSV', 'all-in-one-redirection' ).'</a></label></div>';
			
			echo '<input type="file" name="redirection-file" />';
			
			$nonce_file_import = wp_create_nonce('import-file-page-redirection');
		echo '<div class="btn-sec"><input type="hidden" name="file-import-nonce" value="'.$nonce_file_import.'" /><input type="submit" name="import_file_redirection_btn" value="'.__( 'Import', 'all-in-one-redirection' ).'" /></div>';
		echo '</form>';
		echo '</div>';
		
		echo '<div class="file-export-sec">';
		echo '<div class="vsz-title">'.__( 'Redirection Bulk Export', 'all-in-one-redirection' ).'</div>';
		
		if(isset($_POST['export_file_redirection_btn']) && !empty($_POST['export_file_redirection_btn'])){
			if(wp_verify_nonce( sanitize_text_field($_POST['file-export-nonce']), 'export-file-page-redirection' )){				
				require_once plugin_dir_path( __FILE__ ).'class/csv.class.php';

				$file_path = plugin_dir_path(dirname(dirname( __FILE__ ))).'upload/export/';
				
				$file_field_header = array('Type','Source-URL','Destination-URL','Regular-Expression','Hide');				
				$newFileName = 'all-in-one-redirection-export-'.date('Ymdhis').'.csv';
				$exportedFileName = $file_path.$newFileName;		
				
				// create csv object
				$csvObj = new CSV($exportedFileName, "W",",");

				if($csvObj->filename){
				
				$flagCsv = $csvObj->CSV();					
					
				$arrInfo = array();
				$arrInfo = array_values($file_field_header);
				$csvObj->addArray($arrInfo);
				unset($arrInfo);
					
					$redirection_list_select = $wpdb->get_results( "SELECT * FROM $table_name where rtype!=404 order by id DESC" );
					foreach($redirection_list_select as $key => $redirection_page){
						$file_field_value = array($redirection_page->rtype,$redirection_page->source_url,$redirection_page->destination_url,$redirection_page->reg_expression,$redirection_page->hide_url);
						$arrInfo = array();
						$arrInfo = array_values($file_field_value);
						$csvObj->addArray($arrInfo);
						unset($arrInfo);
					}
				$csvObj->close();
				
				$file_export_path = plugin_dir_url(dirname(dirname( __FILE__))).'upload/export/'.$newFileName;
				
				echo '<div class="click-download"><a href="'.$file_export_path.'">'.__( 'Click here', 'all-in-one-redirection' ).'</a>'.__( ' to download the file.', 'all-in-one-redirection' ).'</div>';
				//header('Location: '.$file_export_path);
				//exit;
				}
				else{
					echo '<div class="no-record marb15">'.__( 'There is some folder permission issue. Please set the proper file permission.', 'all-in-one-redirection' ).'</div>';
				}
			}
			else{
				echo '<div class="no-record marb15">'.__( 'There is some technical problem. Please try again.', 'all-in-one-redirection' ).'</div>';
			}
		}
		elseif(isset($_POST['export_file_404_pages_list_btn']) && !empty($_POST['export_file_404_pages_list_btn'])){
			if(wp_verify_nonce( sanitize_text_field($_POST['file-export-nonce']), 'export-file-page-redirection' )){				
				require_once plugin_dir_path( __FILE__ ).'class/csv.class.php';

				$file_path = plugin_dir_path(dirname(dirname( __FILE__ ))).'upload/export/';
				
				$file_field_header = array('Access-Date','Source-URL','Hits');				
				$newFileName = 'all-in-one-404-page-list-export-'.date('Ymdhis').'.csv';
				$exportedFileName = $file_path.$newFileName;		
				
				// create csv object
				$csvObj = new CSV($exportedFileName, "W",",");

				if($csvObj->filename){
				
				$flagCsv = $csvObj->CSV();					
					
				$arrInfo = array();
				$arrInfo = array_values($file_field_header);
				$csvObj->addArray($arrInfo);
				unset($arrInfo);
					//Changed dated 15-05-2023
					$redirection_list_select = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $table_name where rtype=404 order by last_access DESC") );
					foreach($redirection_list_select as $key => $redirection_page){
						$file_field_value = array(date('M d, Y h:i A',strtotime($redirection_page->last_access)),$redirection_page->source_url,$redirection_page->hits);
						$arrInfo = array();
						$arrInfo = array_values($file_field_value);
						$csvObj->addArray($arrInfo);
						unset($arrInfo);
					}
				$csvObj->close();
				
				$file_export_path = plugin_dir_url(dirname(dirname( __FILE__))).'upload/export/'.$newFileName;
				echo '<div class="click-download"><a href="'.$file_export_path.'">'.__( 'Click here', 'all-in-one-redirection' ).'</a>'.__( ' to download the file.', 'all-in-one-redirection' ).'</div>';
				//header('Location: '.$file_export_path);
				//exit;
				}
				else{
					echo '<div class="no-record marb15">'.__( 'There is some folder permission issue. Please set the proper file permission.', 'all-in-one-redirection' ).'</div>';
				}
			}
			else{
				echo '<div class="no-record marb15">'.__( 'There is some technical problem. Please try again.', 'all-in-one-redirection' ).'</div>';
			}
		}
		
		echo '<form id="redirection_import_form" name="redirection_import_form" action="" method="POST">';
			$nonce_file_export = wp_create_nonce('export-file-page-redirection');
		echo '<div><input type="hidden" name="file-export-nonce" value="'.$nonce_file_export.'" /><input type="submit" name="export_file_redirection_btn" value="'.__( 'Export All Redirection List', 'all-in-one-redirection' ).'" /><input type="submit" class="marl15" name="export_file_404_pages_list_btn" value="'.__( 'Export 404 Pages List', 'all-in-one-redirection' ).'" /></div>';
		
		echo '</form>';		
		echo '</div>';
		
		echo '</div>';
		echo '</div>';
		
	}
}