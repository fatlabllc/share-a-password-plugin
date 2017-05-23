<?php
	$urlVar = $_GET['q'];

	//decrypt function
	function decrypt($text,$salt) {
	    $args = func_get_args();
	    $text= $args[0];
	    $salt = $args[1];
	    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    return trim(mcrypt_decrypt(MCRYPT_BLOWFISH, $salt, base64_decode($text), MCRYPT_MODE_ECB, $iv));
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
	echo '<link rel="stylesheet" id="sap-css"  href="http://'.$_SERVER['HTTP_HOST'].'/wp-content/plugins/share-a-password/includes/sap.css" />';
	// display the password
	echo '<p><strong>Here is the information you seek:</strong></p><p class="secret">' . nl2br($decrypt) . '</p><p class="sap_count">This secret has been viewed '.$viewCountNew.' times</p>';
?>