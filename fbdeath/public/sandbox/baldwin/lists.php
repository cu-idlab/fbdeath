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

	if (isset($_POST['type']) ) {

		switch ($_POST['type']) {
			case add:
				new_list($user->info['fb_id'], $sql);
			break;

		}
	}
	if (is_numeric($_GET['delete'])) {
		if (is_list($_GET['delete'], $sql)) {
			$l = new FbList($sql, $_GET['delete']);
			$l->delete();
		}
	}

?>

<div class="container" style="margin-top: 100px">
	<div class="text-center">
		<h1>Create Lists</h1>
		<br />
	</div>
	
	<div class="span6 offset3" style="background: #F2F2F2; border-radius: 10px; padding: 15px;">
		<table width="100%" style="margin: 1px; vertical-align: middle;">
				<tr>
					<td align="center">
						
					</td>
					<td align="center">
						
					</td>
					<td align="center">
						
					</td>
				</tr>
				
		</table>
		<table class="table table-striped table-hover table-bordered">

<?php

if (is_numeric($_GET['modify'])) {
	$id = $_GET['modify'];
	if (is_list($id, $sql)) {
		$l = new FbList($sql, $id);
		if (isset($_POST['save'])) {
			$l->clear();
			$_POST['friends'] = (count($_POST['friends']))? $_POST['friends'] : array(0);

 			foreach ($_POST['friends'] as $id) {
				if ($id > 0) 
					$l->add_fb_id($id);
			}
		}elseif (isset($_POST['savename'])) {
			$l->save_name(escape($_POST['name']));
		}

		echo '<th>Field</th><th>Value</th>';
		echo '<tr>
				<td>List name</td>
				<td><form method="post"><input type="text" name="name" value="'. escape($l->name()) .'" /> <input type="hidden" name="savename" value="1" /><input type="submit" class="btn btn-warning" value="save name"></form></td>
				</tr>
				</table>
				<form method="post">
					<table class="table table-striped table-hover table-bordered" id="contain">
					<th>People on list</th><th colspan="2"><a class="btn btn-primary" onclick="new_friend()">add</a></th>';
					$i=0;
					foreach ($l->list_of_ids() as $f_id) {
						$i++;

						$fb_obj = $user->graph_call_on_user($f_id, 'fields=name');
						$stuff = "init($(this), $('#friends_". $i ."'))";
						echo '<tr class="friend_'. $i .'"><td>Friend #'. $i .'</td><td><input class="disabled" type="text" autocomplete="off" value="'. escape($fb_obj->name) .'" disabled /><input type="hidden" id="friends_'. $i .'" name="friends[]" value="'. $f_id .'" /> <button onclick="if (confirm(\'Are you sure you want to remove friend #'. $i .'?\')) {rem('. $i .') }else{ return false }" class="btn btn-danger">remove</button></td></tr>';
					}
					if ($i == 0) {
						echo '<tr class="error"><td colspan="3">There isn\'t anyone on the list... why don\'t you add someone? :)</td></tr>';
					} 

					echo '<script type="text/javascript">var i = '. $l->length() .';</script>';

					echo '</table>
						<input type="hidden" value="1" name="save" />
						<a href="/lists.php" class="btn btn-primary"><i class="icon-arrow-left"></i> back</a><input type="submit" value="save" class="btn btn-success pull-right" />
</form>
				';


	}
}else{
	echo '			<th>List</th>
			<th># on list</th>';
	$lists = list_lists($user->info['fb_id'], $sql);
	$i = 0;
	foreach ($lists as $list) {
		$i++;
		$l = new FbList($sql, $list);
		echo '<tr>
					<td>
					<a href="lists.php?modify='. $l->id .'" class="btn btn-inverse btn-mini"><i class="icon-pencil"></i></a> <a href="lists.php?delete='. $l->id .'" class="btn btn-danger btn-mini"><i class="icon-trash"></i></a> <strong>'. $l->name() .'</strong>
					</td>
					<td>
					'. $l->length() .'
					</td>
				</tr>';
	}
	if ($i == 0) {
		echo '<tr class="error"><td colspan="2">There aren\'t any lists... why don\'t you create one? :)</td></tr>';
	} 
	echo '	</table>

	<form method="post" style="display:inline;"><input type="hidden" name="type" value="add" /><button class="btn btn-small"> Add</button></form>';
}


?>
	

			


	</div>
</div>

	<script type="text/javascript">
		if (i < 0) {
			var i = 0;
		}
		var container = "#contain";

		function new_friend() {
			i++;
			var name = "$('#friends_"+ i +"')";
			var lo = "$('#friendsL_"+ i +"')";
			var stuff = "init($(this), "+ name +", "+ lo +")";
			$(container).append('<tr class="friend_'+ i +'"><td>Friend #'+ i +'</td><td><input onclick="'+ stuff +'" type="text" autocomplete="off" placeholder/><input type="hidden" id="friends_'+ i +'" name="friends[]" /> <button onclick="if (confirm(\'Are you sure you want to remove friend #'+ i +'?\')) {rem('+ i +') }else{ return false }" class="btn btn-danger">remove</button></td><td id="friendsL_'+ i +'" style="display: none"><i class="icon-spinner icon-spin"></i></td></tr>');


		}

		function init(obj, hidden, loader) {
			var trusteeObjs = {};
			var trusteeNames = [];

			//get the data to populate the typeahead (plus an id value)
			var throttledRequest = _.debounce(function(query, process){
				//get the data to populate the typeahead (plus an id value)
				$.ajax({
					type: 'GET'
					,data: 'query='+ query
					,url: 'trustees.json'
					,cache: false
					,success: function(data){
						//reset these containers every time the user searches
						//because we're potentially getting entirely different results from the api
						trusteeObjs = {};
						trusteeNames = [];

						//Using underscore.js for a functional approach at looping over the returned data.
						_.each( data, function(item, ix, list){

							//for each iteration of this loop the "item" argument contains
							//1 trustee object from the array in our json, such as:
							// { "id":7, "name":"Pierce Brosnan" }

							//add the label to the display array
							trusteeNames.push( item.name );

							//also store a hashmap so that when bootstrap gives us the selected
							//name we can map that back to an id value
							trusteeObjs[ item.name ] = item;
						});

						//send the array of results to bootstrap for display
						process( trusteeNames );
					}
				});
			}, 300);
			obj.typeahead({
				source: function ( query, process ) {
					loader.show();
					//here we pass the query (search) and process callback arguments to the throttled function
					throttledRequest( query, process );

				}
        ,highlighter: function( item ){
          var trustee = trusteeObjs[ item ];
          loader.hide();
          return '<div class="trustee" style="display: table-cell;">'
                +'<div style="height: 30px; width: 30px; background: url(\'' + trustee.photo + '\') top center; float: left; vertical-align: top;"></div>'
                +'<div style="vertical-align: top; display: table-cell; padding-left: 10px;"><strong>' + trustee.name + '</strong></div>'
                +'</div>';
        }
				, updater: function ( selectedName ) {
          
          //note that the "selectedName" has nothing to do with the markup provided
          //by the highlighter function. It corresponds to the array of names
          //that we sent from the source function.

					//save the id value into the hidden field

					hidden.val( trusteeObjs[ selectedName ].id );

					//return the string you want to go into the textbox (the name)
					return selectedName;
				}
			});
		}

		function rem(id) {
			$('.friend_'+ id).remove();
		}



	</script>





<?php
	require_once "private/includes/footer.php";
?>