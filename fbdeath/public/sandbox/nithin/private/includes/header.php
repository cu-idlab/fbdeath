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

	require_once "/home/epilogue/public_html/config.php";

	if (!isset($cfg['pageTitle'])) $cfg['pageTitle'] = 'Unknown'
?>
<!DOCTYPE html>
<html>
 	<head>
		<title>Epilogue - <?=$cfg['pageTitle']?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link href="http://epilogue.baldwinc.com/public/assets/css/bootstrap.css" rel="stylesheet" media="screen">
		<link href="http://epilogue.baldwinc.com/public/assets/css/bootstrap-responsive.css" rel="stylesheet" media="screen">

		<link href="http://epilogue.baldwinc.com/public/assets/css/font-awesome.css" rel="stylesheet" media="screen">

		<link href="http://epilogue.baldwinc.com/public/assets/css/epilogue_main.css" rel="stylesheet" media="screen">

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
		<script src="http://epilogue.baldwinc.com/public/assets/js/bootstrap.js"></script>
	</head>
	<body>
<?php
	if ($cfg['pageTitle'] != 'Welcome') {
		$epilogue->nav_bar();
	}
?>