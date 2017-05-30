<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	// reqiure the functions file, it's the brains of the operation
	include_once dirname(dirname(__FILE__)) . '/functions.php';

	$urlVar = $_GET['q'];

	//call information back from database
	global $wpdb;
	$table_name = $wpdb->prefix . "shareapassword";
	$results = $wpdb->get_results(
	"SELECT * FROM $table_name WHERE info1 = '$urlVar'");
	foreach( $results as $result )
	$info2 = $result->info2;
	$info3 = $result->info3;
	$viewCount = $result->views;
	$viewCountNew = $viewCount+1;
	// decrypt
	$decrypt = share_ap_decrypt($info3,$info2);
	// add to the view count
	$wpdb->update( $table_name, array( 'views' => $viewCountNew ), array( 'info1' => $urlVar ) );

	// display the password
	echo '<p><strong>Here is the information you seek:</strong></p><p class="secret">' . nl2br($decrypt) . '</p><p class="sap_count">This secret has been viewed '.$viewCountNew.' times</p>';
?>