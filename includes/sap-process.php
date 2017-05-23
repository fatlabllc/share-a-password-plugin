<?php
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
	function encrypt($text,$salt) {
		$args = func_get_args();
		$text= $args[0];
	    $salt = $args[1];
	    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    return trim(base64_encode(mcrypt_encrypt(MCRYPT_BLOWFISH, $salt, $text, MCRYPT_MODE_ECB, $iv)));
	}
	//decrypt function
	function decrypt($text,$salt) {
	    $args = func_get_args();
	    $text= $args[0];
	    $salt = $args[1];
	    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    return trim(mcrypt_decrypt(MCRYPT_BLOWFISH, $salt, base64_decode($text), MCRYPT_MODE_ECB, $iv));
	}

	//get our form variable: unencrypted data provided by user
	$userEntry = $_POST["sapInput"];
	//create unique salt
	$salt = generatString(30);
	//create url code
	$urlcode = generatString(17);
	//encrypt that sucker, with just a pinch of salt
	$encrypt = encrypt($userEntry,$salt);
	//put this in the database
	global $wpdb;
	$table_name = $wpdb->prefix . "shareapassword";
	$wpdb->insert( $table_name, array( 'info2' => $salt, 'info3' => $encrypt, 'info1' => $urlcode),
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