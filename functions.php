<?php

	//encrypt function
	function share_ap_encrypt($text,$key) {
	    // Remove the base64 encoding from our key
	    $encryption_key = base64_decode($key);
	    // Generate an initialization vector
	    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
	    // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
	    $encrypted = openssl_encrypt($text, 'aes-256-cbc', $encryption_key, 0, $iv);
	    return base64_encode($encrypted . '::' . $iv);
	}

	//decrypt function
	function share_ap_decrypt($text,$key) {
	    // Remove the base64 encoding from our key
	    $encryption_key = base64_decode($key);
	    // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
	    list($encrypted_data, $iv) = explode('::', base64_decode($text), 2);
	    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
	}

	// generate a random string function
    function share_ap_generateString($stringLength)
	{
	   $listAlpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	   return str_shuffle(
	      substr(str_shuffle($listAlpha),0,$stringLength)
	  );
	}
	?>