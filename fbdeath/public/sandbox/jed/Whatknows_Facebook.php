<?php

class Whatknows_Facebook {
	
	private $facebook;
	
	function __construct() {

		// Create our Application instance (replace this with your appId and secret).
		$appId = '172714089530733';
		$secret = '10cd80f48635ee97a301c2f998da6bfa';
		$this->facebook = new Facebook(array('appId' => $appId, 'secret' => $secret));
		
	}
	
	public function getUser($userId) {
		return $this->facebook->api($userId);
	}
	
	public function getFriends($userId) {
		return $this->facebook->api('/'.$userId.'/friends');
	}
	
	public function getAlbums($userId) {
		
		// // get albums
		// $albums = $this->facebook->api('/'.$userId.'/albums');
		
		// foreach($albums['data'] as $album)
			// {
			// 	// get all photos for album
			// 	$photos = $facebook->api("/{$album['id']}/photos");
		
			// 	foreach($photos['data'] as $photo)
				// 	{
				// 		echo "<img src='{$photo['source']}' />", "<br />";
				// 	}
				// }
		
		
	}
	
	public function getStatus($userId) {
		
		return  $this->facebook->api("/{$userID}/statuses");
		
// 		foreach($statuses['data'] as $status){
// 			// processing likes array for calculating fanbase.
		
// 			foreach($status['likes']['data'] as $likesData){
// 				$frid = $likesData['id'];
// 				$frname = $likesData['name'];
// 				$friendArray[$frid] = $frname;
// 			}
		
// 			foreach($status['comments']['data'] as $comArray){
// 				// processing comments array for calculating fanbase
// 				$frid = $comArray['from']['id'];
// 				$frname = $comArray['from']['name'];
// 			}
	}
 
}


?>