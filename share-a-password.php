<?php
/*
Plugin Name: Share a Password
Plugin URI: http://www.fatlabwebsupport.com/
Description: Securly (as possible) share a password via temporary URL. Passwords are stored in an encrypted format and deleted after 24 hours. The concept is that you will send the intended recpient thier username and other info by other means and then the password via this tool. Keeping full access information out of insecure formats such as email.
Version: 0.3 BETA
Author: FatLab Web Support
Author URI: http://www.fatlabwebsupport.com
License: GPL2
*/



//====================
//! Start Activation
//====================

// create/setup our data on activation
function sap_setup()
{
	// store plugin version in wp_options table
	add_option( 'sap_version', '0.4', '', 'no' );
	// create our main table
    global $wpdb;
	$table_name = $wpdb->prefix . "shareapassword";
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
	{
		$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		info1 varchar(50) NOT NULL,
	    info2 varchar(50) NOT NULL,
	    info3 varchar(5000) NOT NULL,
	    views int DEFAULT 0 NOT NULL,
	    creation_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		UNIQUE KEY id (id)
		);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

}
	register_activation_hook( __FILE__, 'sap_setup' );




	//Register cron job to clear out old entries every hour
	register_activation_hook( __FILE__, 'sap_create_schedule' );
	add_action( 'sap_hourly_cleanup', 'sap_cleanup' );

	function sap_create_schedule() {
		wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'sap_hourly_cleanup' );
	}

	function sap_cleanup() {
		global $wpdb;
		$table_name = $wpdb->prefix . "shareapassword";
		$wpdb->query("DELETE FROM $table_name WHERE creation_time < DATE_SUB(NOW(), INTERVAL 24 HOUR)");
	}



	//Register our short code
	add_shortcode('share-a-password', 'sap_short');


	// setup admin page for admin
	function sap_admin() {
		include('sap-admin.php');
	}

	// create an admin menu item under Settings
	function sap_admin_actions() {
		add_submenu_page("tools.php","Share a Password", "Share a Password", 1, "shareapassword", "sap_admin");
	}
	add_action('admin_menu', 'sap_admin_actions');

//==================
//! end activation
//==================


//====================
//! start short code
//====================
function sap_short() {
	if(isset($_POST['sapInput']))
	{
	include('includes/sap-process.php');
	}
	elseif (isset($_GET['q']))
	{
	include('includes/sap-call.php');
	}
	else
	{
	return include('includes/sap-form.php');
	}
}
//==================
//! end short code
//==================


//===========================
//! start deactivation
//===========================

// remove cron job
register_deactivation_hook( __FILE__, 'sap_remove_schedule' );
function sap_remove_schedule() {
	wp_clear_scheduled_hook( 'sap_hourly_cleanup' );
}

// remove records from table
register_deactivation_hook( __FILE__, 'sap_remove_records' );
function sap_remove_records() {
	global $wpdb;
	$table_name = $wpdb->prefix . "shareapassword";
	$wpdb->query("DELETE FROM $table_name");
	}

// remove our cron job
register_deactivation_hook( __FILE__, 'sap_remove_cron' );
function sap_remove_cron() {
    wp_clear_scheduled_hook('sap_hourly_cleanup');
}

//====================
//! Uninstall Plugin
//====================

// remove our the db table
register_uninstall_hook(__FILE__,'sap_uninstall_plugin');
function sap_uninstall_plugin(){
    global $wpdb;
    $table_name = $wpdb->prefix . "shareapassword";
    //Remove our table (if it exists)
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}
// remove our cron job
register_deactivation_hook( __FILE__, 'sap_uninstall_plugin_cron' );
function sap_uninstall_plugin_cron() {
    wp_clear_scheduled_hook('sap_hourly_cleanup');
}
// remove options version
delete_option( 'shareapassword_version' );
?>
