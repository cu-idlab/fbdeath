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

	require_once "../config.php";

	if (!$epilogue->is_loggedin())
		die('You are not logged in.');


header("Content-Type: application/json");

$query = isset($_GET['query'])? strtolower($_GET['query']) : $null;

$friends = $user->graph_call('fields=friends');

$n_friends = array();

foreach ($friends->friends->data as $id => $obj) {
	if (strpos(strtolower($obj->name), $query) === false) {

	}else{
		$n_friends = array_merge($n_friends, array(array('name' => $obj->name, 'id' => $obj->id, 'photo' => 'https://graph.facebook.com/'. $obj->id .'/picture?type=small')));		
	}
}


echo json_encode($n_friends);

?>
	
