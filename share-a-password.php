<?php
/*
Plugin Name: Share a Password
Plugin URI: http://www.fatlabwebsupport.com/
Description: Securly (as possible) share a password or other secret via temporary URL. Passwords are stored in an encrypted format and deleted after 24 hours. Add your secret and share the temporary URL with the recipient.
Version: 1
Author: FatLab, LLC
Author URI: http://www.fatlabwebsupport.com
License: License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



//====================
//! Start Activation
//====================

// create/setup our data on activation
function share_ap_setup()
{
	// store plugin version in wp_options table
	add_option( 'sap_version', '1', '', 'no' );
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
	register_activation_hook( __FILE__, 'share_ap_setup' );




	//Register cron job to clear out old entries every hour
	register_activation_hook( __FILE__, 'share_ap_create_schedule' );
	add_action( 'share_ap_hourly_cleanup', 'share_ap_cleanup' );

	function share_ap_create_schedule() {
		wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'share_ap_hourly_cleanup' );
	}

	function share_ap_cleanup() {
		global $wpdb;
		$table_name = $wpdb->prefix . "shareapassword";
		$wpdb->query("DELETE FROM $table_name WHERE creation_time < DATE_SUB(NOW(), INTERVAL 24 HOUR)");
	}



	//Register our short code
	add_shortcode('share-a-password', 'share_ap_short');


	// setup admin page for admin
	function share_ap_admin() {
		include('sap-admin.php');
	}

	// create an admin menu item under Settings
	function share_ap_admin_actions() {
		add_submenu_page("tools.php","Share a Password", "Share a Password", 1, "shareapassword", "share_ap_admin");
	}
	add_action('admin_menu', 'share_ap_admin_actions');

//==================
//! end activation
//==================


//====================
//! start short code
//====================
function share_ap_short() {
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
register_deactivation_hook( __FILE__, 'share_ap_remove_schedule' );
function share_ap_remove_schedule() {
	wp_clear_scheduled_hook( 'share_ap_hourly_cleanup' );
}

// remove records from table
register_deactivation_hook( __FILE__, 'share_ap_remove_records' );
function share_ap_remove_records() {
	global $wpdb;
	$table_name = $wpdb->prefix . "shareapassword";
	$wpdb->query("DELETE FROM $table_name");
	}

// remove our cron job
register_deactivation_hook( __FILE__, 'share_ap_remove_cron' );
function share_ap_remove_cron() {
    wp_clear_scheduled_hook('share_ap_hourly_cleanup');
}

//====================
//! Uninstall Plugin
//====================

// remove our the db table
register_uninstall_hook(__FILE__,'share_ap_uninstall_plugin');
function share_ap_uninstall_plugin(){
    global $wpdb;
    $table_name = $wpdb->prefix . "shareapassword";
    //Remove our table (if it exists)
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}
// remove our cron job
register_deactivation_hook( __FILE__, 'share_ap_uninstall_plugin_cron' );
function share_ap_uninstall_plugin_cron() {
    wp_clear_scheduled_hook('share_ap_hourly_cleanup');
}
// remove options version
delete_option( 'sap_version' );
?>
