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

	if (!$epilogue->is_loggedin()) {
		die('You are not logged in.');
	}

?>
<div style="margin-top: 100px">
	<div class="text-center">
		<h1>Who to entrust your account to?</h1>
		<h4>This designatee will be transferred primary control of your account at transition.</h4>
		<br />
	</div>
	<div class="span6 offset3" style="background: #F2F2F2; border-radius: 10px; padding: 10px;">
		<table width="100%" style="margin: 10px; vertical-align: middle;">
				<th width="30px"></th>
				<th></th>
				<th></th>
				<tr>
					<td>
						<i class="icon-user"></i>
					</td>
					<td>
						Primary Trustee
					</td>
					<td>
						<div class="input-prepend">
							<span class="add-on"><i class="icon-search"></i></span>
							<input class="span2" type="text" placeholder="search">
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<i class="icon-user"></i>
					</td>
					<td>
						Secondary Trustee
					</td>
					<td>
						<div class="input-prepend">
							<span class="add-on"><i class="icon-search"></i></span>
							<input class="span2" type="text" placeholder="search">
						</div>
					</td>
				</tr>
		</table>
	</div>
</div>

<?php
	require_once "private/includes/footer.php";
?>