<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	// generate a random string function
	// this is called twice, once to generate the url and once to provide a unique salt
    function generatString($stringLength)
	{
	   $listAlpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	   return str_shuffle(
	      substr(str_shuffle($listAlpha),0,$stringLength)
	  );
	}
	//encrypt function

	function encrypt($text,$key) {
	    // Remove the base64 encoding from our key
	    $encryption_key = base64_decode($key);
	    // Generate an initialization vector
	    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
	    // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
	    $encrypted = openssl_encrypt($text, 'aes-256-cbc', $encryption_key, 0, $iv);
	    return base64_encode($encrypted . '::' . $iv);
	}

	//get our form variable: unencrypted data provided by user
	$userEntry = sanitize_textarea_field( $_POST['sapInput'] );

	//create unique salt
	$key = generatString(32);
	//create url code
	$urlcode = generatString(17);
	//encrypt that sucker
	$encrypt = encrypt($userEntry,$key);
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