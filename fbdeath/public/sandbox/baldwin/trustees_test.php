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

	if (!$epilogue->is_loggedin()) {
		die('You are not logged in.');
	}

?>
<br /><br />

<form method="post">
<input type="text" class="typeahead" style="margin: 0 auto;" autocomplete="off" data-provide="typeahead" placeholder="Select a Trustee..."/>
<input type="text" id="trusteeId" name="trusteeId" />
<input type="submit" />
</form>

<h1><?=$_POST['trusteeId']?></h1>
	<script type="text/javascript">
		$(function(){

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


			$(".typeahead").typeahead({
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
					$( "#trusteeId" ).val( trusteeObjs[ selectedName ].id );

					//return the string you want to go into the textbox (the name)
					return selectedName;
				}
			});
		});
	</script>


<?php
	require_once "private/includes/footer.php";
?>