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

require_once "private/mod_include.php";

$sql_user = 'epilogue_main';
$sql_db = 'epilogue_fbdeath';
$sql_pass = 'fbdeath';


$config = array();
$config['appId'] = '121943451320482';
$config['secret'] = 'df83040bf2c54c834c8d3b2a8d115a94';
$config['fileUpload'] = false; // optional

$facebook = new Facebook($config);



session_name('epi');
session_set_cookie_params(0, '/', '.epilogue.baldwinc.com');
session_start();


$sql = new SQL($sql_user, $sql_pass, $sql_db);

$epilogue = new Epilogue($sql, $facebook);

$epilogue->fb_check();

if ($epilogue->is_loggedin()) {
	$user = new User($sql, $facebook, $epilogue->id);
}

?>