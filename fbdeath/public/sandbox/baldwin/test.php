<?php
print_r($_POST['friends']);
?>
<!DOCTYPE html>
<html>
 	<head>
		<title>Epilogue - Unknown</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link href="http://epilogue.baldwinc.com/public/assets/css/bootstrap.css" rel="stylesheet" media="screen">
		<link href="http://epilogue.baldwinc.com/public/assets/css/bootstrap-responsive.css" rel="stylesheet" media="screen">

		<link href="http://epilogue.baldwinc.com/public/assets/css/font-awesome.css" rel="stylesheet" media="screen">

		<link href="http://epilogue.baldwinc.com/public/assets/css/epilogue_main.css" rel="stylesheet" media="screen">

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
		<script src="http://epilogue.baldwinc.com/public/assets/js/bootstrap.js"></script>


		<script src="http://underscorejs.org/underscore-min.js"></script>
	</head>
	<body>

	<script type="text/javascript">
		var i = 0;
		var container = "#contain";

		function new_friend() {
			i++;
			var name = "$('#friends_"+ i +"')";
			var stuff = "init($(this), "+ name +")";
			$(container).append('<tr class="friend_'+ i +'"><td>Friend #'+ i +'</td><td><input onclick="'+ stuff +'" type="text" autocomplete="off" placeholder/><input type="hidden" id="friends_'+ i +'" name="friends[]" /> <button onclick="rem('+ i +')" class="btn btn-danger">remove</button></td></tr>');


		}

		function init(obj, hidden) {
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
					//here we pass the query (search) and process callback arguments to the throttled function
					throttledRequest( query, process );

				}
        ,highlighter: function( item ){
          var trustee = trusteeObjs[ item ];
          
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
			i--;
			$('.friend_'+ id).remove();
		}



	</script>



	<a class="btn btn-primary" onclick="new_friend()">party time</a>
<form method="post">
	<table id="contain">

	</table>
	<input type="submit" value="save" class="btn btn-primary" />
</form>

	</body>
</html>