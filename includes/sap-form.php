<?php
return '
    <!-- start sap form -->
    <link rel="stylesheet" id="sap-css"  href="http://'.$_SERVER['HTTP_HOST'].'/wp-content/plugins/share-a-password/includes/sap.css" />
    <div id="sapFormDiv">
	<form id="sapForm" action="" method="post">
		<textarea name="sapInput" class="sapInput" maxlength="900"></textarea>
		<input type="submit" value="submit" name="submit" class="sapSubmit">
	</form>
	</div>
	<!-- end sap form -->
	'
?>