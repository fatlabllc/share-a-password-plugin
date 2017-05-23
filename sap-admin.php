<?php

//check to see if the the form variable is present
if(isset($_POST['sapInput'])) {

	// functions to handle all the important stuff

	// generate a random string
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
	echo "<p>salt : " . $salt . "</p>";
	//create url code
	$urlcode = generatString(17);
	echo "<p>url code : " . $urlcode . "</p>";

	$encrypt = encrypt($userEntry,$salt);
	$decrypt = decrypt($encrypt,$salt);

	echo "<p>This is the encrypted password : " . $encrypt . "</p>";
	echo "<p>This is the decrypted password : " . $decrypt . "</p>";

	//put this in the database
	global $wpdb;
	$table_name = $wpdb->prefix . "shareapassword";
	$wpdb->insert( $table_name, array( 'info2' => $salt, 'info3' => $encrypt, 'info1' => $urlcode),
	array ('%s','%s','%s')
	);

	echo "<p>The data is stored in our database now</p>";

	//call information back from database
	global $wpdb;
	$table_name = $wpdb->prefix . "shareapassword";
	$results = $wpdb->get_results(
	"
	SELECT *
	FROM $table_name
	WHERE info1 = '$urlcode'
	"
	);
	foreach( $results as $result )
	$info1 = $result->info1;
	$info2 = $result->info2;
	$info3 = $result->info3;

	echo "<p>urlcode we are looking up : " . $urlcode . "</p>";
	echo "<p>Info1 from DB : " . $info1 . "</p>";
	echo "<p>Info2 from DB : " . $info2 . "</p>";
	echo "<p>Info3 from DB : " . $info3 . "</p>";







}


?>

<div class="sapForm">
	<form id="createPass" action="options-general.php?page=shareapassword" method="post">
		<input type="text" name="sapInput">
		<input type="submit" value="submit" name="submit">
	</form>
</div>