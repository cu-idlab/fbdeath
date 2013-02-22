<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

echo "Hello world";

require '../facebook/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$appId = '172714089530733';
$secret = '10cd80f48635ee97a301c2f998da6bfa';
$facebook = new Facebook(array('appId' => $appId, 'secret' => $secret));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
//   	$loginUrl = $facebook->getLoginUrl();
  
  	header("Location:{$facebook->getLoginUrl(array('req_perms' => 'user_status,publish_stream,user_photos'))}");
  	exit;
  
}



// This call will always work since we are fetching public data.
$naitik = $facebook->api('/naitik');

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>php-sdk</title>
    <style>
      body {
        font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
      }
      h1 a {
        text-decoration: none;
        color: #3b5998;
      }
      h1 a:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body>
  <pre>
    <h1>php-sdk</h1>

    <?php if ($user): ?>
      <a href="<?php echo $logoutUrl; ?>">Logout</a>
    <?php else: ?>
      <div>
        Login using OAuth 2.0 handled by the PHP SDK:
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
      </div>
    <?php endif ?>

    <h3>PHP Session</h3>
    <pre><?php print_r($_SESSION); ?></pre>

    <?php if ($user): ?>
      <h3>You</h3>
      <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">

      <h3>Your User Object (/me)</h3>
      <pre><?php print_r($user_profile); ?></pre>
    <?php else: ?>
      <strong><em>You are not Connected.</em></strong>
    <?php endif ?>

    <h3>Public profile of Naitik</h3>
    <img src="https://graph.facebook.com/naitik/picture">
    <?php echo $naitik['name']; ?>
    
<?php 


// show friends
echo "\n\nFRIENDS\n";
$friends = $facebook->api('/me/friends');
var_dump($friends);

// // get albums
// $albums = $facebook->api('/me/albums');

// foreach($albums['data'] as $album)
// {
// 	// get all photos for album
// 	$photos = $facebook->api("/{$album['id']}/photos");

// 	foreach($photos['data'] as $photo)
// 	{
// 		echo "<img src='{$photo['source']}' />", "<br />";
// 	}
// }

$return = $facebook->api(array('method' => 'users.isappuser'));
echo (string)$return;

// // add a status message
// $status = $facebook->api('/me/feed', 'POST', array('message' => 'This post came from my app.'));

// var_dump($status);

// show Glenn's friends
echo "\n\nGLENN\n";

// <// $friendsOfFriends = $facebook->api('/28747/friends');
// var_dump($friendsOfFriends);-- THIS NEVER WORKS
// BUT YOU CAN ASK IF FRIEND X IS ALSO FRIENDS WITH Y.
// e.g., John Bono is also friends with Glenn
$mutualFriend = $facebook->api('/550502441/friends/28747');
print_r($mutualFriend);




?>      
    </pre>
  </body>
</html>
