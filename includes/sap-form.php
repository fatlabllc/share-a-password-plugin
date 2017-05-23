<?php
return '
    <!-- start sap form -->
    <link rel="stylesheet" id="sap-css"  href="http://'.$_SERVER['HTTP_HOST'].'/wp-content/plugins/share-a-password/includes/sap.css" />
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