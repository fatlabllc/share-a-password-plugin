<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// figure out the css path
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
	$css='https://'.$_SERVER['HTTP_HOST'].'/wp-content/plugins/share-a-password/includes/sap.css';
} else {
    $css='http://'.$_SERVER['HTTP_HOST'].'/wp-content/plugins/share-a-password/includes/sap.css';
}
// output form
return '
    <!-- start sap form -->
    <link rel="stylesheet" id="sap-css"  href="'.$css.'" />
    <div id="sapFormDiv">
    <div class="sapInstructions">Paste a password or other secret in the box below and click Submit to retrieve your unique URL.</div>
	<form id="sapForm" action="" method="post">
		<textarea name="sapInput" class="sapInput" maxlength="900"></textarea>
		<input type="submit" value="Submit" name="submit" class="sapSubmit">
	</form>
	</div>
	<!-- end sap form -->
	'
?>