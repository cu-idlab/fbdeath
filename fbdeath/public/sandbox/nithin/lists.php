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
		<h1>Create Lists</h1>
		<br />
	</div>
	
	<div class="span6 offset3" style="background: #F2F2F2; border-radius: 10px; padding: 15px;">
		<table width="100%" style="margin: 1px; vertical-align: middle;">
				<tr>
					<td align="center">
						<div class="btn btn-small"></i> Add</div>
					</td>
					<td align="center">
						<div class="btn btn-small"></i> Edit</div>
					</td>
					<td align="center">
						<div class="btn btn-small"></i> Send message to list</div>
					</td>
				</tr>
				
		</table>
	</div>
</div>
	
	


<?php
	require_once "private/includes/footer.php";
?>