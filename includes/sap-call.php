<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	$urlVar = $_GET['q'];

	// figure out the css path
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
		$css='https://'.$_SERVER['HTTP_HOST'].'/wp-content/plugins/share-a-password/includes/sap.css';
	} else {
	    $css='http://'.$_SERVER['HTTP_HOST'].'/wp-content/plugins/share-a-password/includes/sap.css';
	}

	//decrypt function
	function decrypt($text,$key) {
	    // Remove the base64 encoding from our key
	    $encryption_key = base64_decode($key);
	    // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
	    list($encrypted_data, $iv) = explode('::', base64_decode($text), 2);
	    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
	}

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
	$decrypt = decrypt($info3,$info2);
	// add to the view count
	$wpdb->update( $table_name, array( 'views' => $viewCountNew ), array( 'info1' => $urlVar ) );
	// output CSS
	echo '<link rel="stylesheet" id="sap-css"  href="'.$css.'" />';
	// display the password
	echo '<p><strong>Here is the information you seek:</strong></p><p class="secret">' . nl2br($decrypt) . '</p><p class="sap_count">This secret has been viewed '.$viewCountNew.' times</p>';
?>