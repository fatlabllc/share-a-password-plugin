<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	// reqiure the functions file, it's the brains of the operation
	include_once dirname(dirname(__FILE__)) . '/functions.php';

// output form
return '
    <!-- start sap form -->
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