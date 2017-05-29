<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	// reqiure the functions file, it's the brains of the operation
	include_once dirname(dirname(__FILE__)) . '/functions.php';

	//get our form variable: unencrypted data provided by user
	$userEntry = sanitize_textarea_field( $_POST['sapInput'] );

	//create unique salt
	$key = share_ap_generateString(32);
	//create url code
	$urlcode = share_ap_generateString(17);
	//encrypt that sucker
	$encrypt = share_ap_encrypt($userEntry,$key);
	//put this in the database
	global $wpdb;
	$table_name = $wpdb->prefix . "shareapassword";
	$wpdb->insert( $table_name, array( 'info2' => $key, 'info3' => $encrypt, 'info1' => $urlcode),
	array ('%s','%s','%s')
	);
	// figure out the page's URL
	$pageVar=strtok($_SERVER["REQUEST_URI"],'?');
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
    	$urlVar='https://'.$_SERVER['HTTP_HOST'].$pageVar.'?q='.$urlcode;
	} else {
	    $urlVar='http://'.$_SERVER['HTTP_HOST'].$pageVar.'?q='.$urlcode;
	}
	echo "<p>Your Temporary URL is:</p><strong>" . $urlVar . "</strong></p><p>Simply copy this URL and send. The link is good for 24 hours. After 24 hours all data and the link will be permanently deleted from the system.</p>"
?>