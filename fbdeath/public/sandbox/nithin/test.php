<?php
	require_once 'config.php';





	if ($epilogue->is_loggedin()) {

		echo 'logged in';

		print_r($user->name());
	}else{
		$params = array(
			'scope' => 'read_stream, friends_likes',
			'redirect_uri' => 'http://epilogue.baldwinc.com/test.php',
			'display' => 'popup'
		);

		$loginUrl = $facebook->getLoginUrl($params);

		echo 'not logged in.. going to log you in! at: <a href="'. $loginUrl .'">login</a>';

		echo $facebook->getUser();
	}

?>