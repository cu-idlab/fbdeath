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

?>
    <div id="epilogue_welcome" class="row-fluid">
        <div class="container span8 offset2">
	          	<div class="text-center">
	          		<div id="welcome" style="color: #DDD; font-size: 6em;font-weight:bold; line-height: 4em;display:none;text-shadow: 1px 1px 1px #333;">Epilogue</div>

	          		<div id="fb_login">
	          			<?php
	          				if ($epilogue->is_loggedin()) {
	          					echo '<button class="btn btn-primary btn-large" style="margin-top:-5em;">Weclome '. $user->name() .'</button> <a href="logout.php"><button class="btn btn-warning btn-large" style="margin-top:-5em;">logout</button></a>';
	          				}else{
	          							$params = array(
											'scope' => 'read_stream, friends_likes, user_status',
											'redirect_uri' => 'http://epilogue.baldwinc.com/',
										);

										$loginUrl = $facebook->getLoginUrl($params);
	          					echo '<a href="'. $loginUrl .'"><button class="btn btn-primary btn-large" style="margin-top:-5em;"><i class="icon-facebook"></i> | Connect to Facebook</button></a>';
	          				}
	          			?>
	          		</div>
	          	</div>
        </div>
    </div>
    <div style="padding: 25px; text-align:" class="row-fluid span6 offset3">
    	<ul class="thumbnails">
			<li class="span2">
				<div class="thumbnail">
	    			<img src="http://epilogue.baldwinc.com/public/images/epilogue_2.jpg" alt="" />
	      			<h3>Hello</h3>
	      			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer quam purus, pulvinar tristique volutpat quis, consectetur ut orci. Pellentesque in lacus eu tortor sagittis facilisis sed eget neque. In non est nisi, sed sagittis nibh. Fusce ullamcorper turpis non quam porta nec iaculis orci ornare. Sed euismod iaculis eros eget pharetra. Vivamus convallis urna eu risus tristique vel interdum diam luctus. Nulla lacinia nibh ut leo pretium eget commodo diam mollis.</p>
	    		</div>
  			</li>
 			<li class="span2">
				<div class="thumbnail">
	    			<img src="http://epilogue.baldwinc.com/public/images/epilogue_2.jpg" alt="" />
	      			<h3>Hello</h3>
	      			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer quam purus, pulvinar tristique volutpat quis, consectetur ut orci. Pellentesque in lacus eu tortor sagittis facilisis sed eget neque. In non est nisi, sed sagittis nibh. Fusce ullamcorper turpis non quam porta nec iaculis orci ornare. Sed euismod iaculis eros eget pharetra. Vivamus convallis urna eu risus tristique vel interdum diam luctus. Nulla lacinia nibh ut leo pretium eget commodo diam mollis.</p>
	    		</div>
  			</li>
 			<li class="span2">
				<div class="thumbnail">
	    			<img src="http://epilogue.baldwinc.com/public/images/epilogue_2.jpg" alt="" />
	      			<h3>Hello</h3>
	      			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer quam purus, pulvinar tristique volutpat quis, consectetur ut orci. Pellentesque in lacus eu tortor sagittis facilisis sed eget neque. In non est nisi, sed sagittis nibh. Fusce ullamcorper turpis non quam porta nec iaculis orci ornare. Sed euismod iaculis eros eget pharetra. Vivamus convallis urna eu risus tristique vel interdum diam luctus. Nulla lacinia nibh ut leo pretium eget commodo diam mollis.</p>
	    		</div>
  			</li>
		</ul>
    </div>
    <script type="text/javascript">
			$(function() {
				$('#welcome').fadeIn(1000);
			});
    </script>
<?php
	require_once "private/includes/footer.php";
?>