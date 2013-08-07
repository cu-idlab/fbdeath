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
		<h1>Messages</h1>
		<br />
	</div>
	
	<div class="span6" style="background: #F2F2F2; padding: 10px;">
		<span class="add-on"><i class="icon-envelope"></i></span>
		<textarea rows="18" placeholder="Compose your message here" class="span6"></textarea>
	</div>
	
	<div class = "span5" >
	<table align="center">
				<tr align="center">
					<td>
						<br> <br><br> <br><br> <br><br> <br><br> <br><br> <br>
						<i class="icon-user"></i> Select your Recipient
						<br>
						(Individual or Group)
						<br>
						<div class="input-prepend">
							<span class="add-on"><i class="icon-search"></i></span>
							<input class="span2" type="text" placeholder="search">
						</div>
						<br>
						<br>
						<div class="btn btn-small"></i> Send</div>
					</td>
				</tr>	
	</div>




<?php
	require_once "private/includes/footer.php";
?>