<?php
/*
	Epilogue Group

	advisor: Jed B.

	members: 
		Anita Marie G.
		Baldwin C.
		Nafiri K.
		Nithin J.

*/

	require_once "private/includes/header.php";

	if (!$epilogue->is_loggedin())
		die('You are not logged in.');

?>

<div style="margin-top: 100px">
	<div class="text-center">
		<h1>Preferences</h1>
		<br />
	</div>
	
</div>


<?php
	require_once "private/includes/footer.php";
?>