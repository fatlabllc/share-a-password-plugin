<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	//call information back from database
	global $wpdb;
	$table_name = $wpdb->prefix . "shareapassword";
	$num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
	// get the plugin version number
	$vnum = get_option('sap_version');
?>
<h1>Share a Password</h1>
<h3>v.<?php echo $vnum;?></h3>
<p>Each password or piece of secret information will be kept in the database for 24 hours and then be permanently deleted.</p>
<p><strong>There are currently <?php echo $num_rows;?> in the database</strong></p>