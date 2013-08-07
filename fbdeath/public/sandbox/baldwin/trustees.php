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


	if (is_numeric($_POST['trust_1']) && is_numeric($_POST['trust_2'])) {
		if ($_POST['trust_1'] != $_POST['trust_2']) {
			$epilogue->save_trustee($user, $_POST['trust_1'], $_POST['trust_2']);
			$conf = g_msg('Trustees saved!');
		}else{
			$conf = e_msg('Cannot have the same person twice! Trustees were not saved.');
		}
	}

?>
<div class="container" style="margin-top: 100px">
	<?=$conf?>
	<div class="text-center">
		<h1>Who to entrust your account to?</h1>
		<h4>This designatee will be transferred primary control of your account at transition.</h4>
		<br />
	</div>
	<div class="span6 offset3" style="background: #F2F2F2; border-radius: 10px; padding: 10px;">
		<table width="100%" style="margin: 10px; vertical-align: middle;">
		<form action="" method="post">
				<th width="30px"></th>
				<th></th>
				<th></th>
				<tr>
					<td>
						<i class="icon-user"></i>
					</td>
					<td>
						Primary Trustee
					</td>
					<td>
						<?php
							if (!$epilogue->has_trustees($user) || isset($_POST['change'])) {
								echo '
								<div class="input-prepend">
									<span class="add-on"><i class="icon-search"></i></span>
									<input id="trustee" class="span2 typeahead_1" data-provide="typeahead" autocomplete="off" type="text" placeholder="search">
									<input type="hidden" id="trusteeId_1" name="trust_1" />
								</div>
								<span class="type1_loading" style="display: none">
									<i class="icon-spinner icon-spin"></i>
								</span>
								';
							}else{
								$t1 = $epilogue->trustee_info($user, 'primary');
								echo '<div style="height: 30px; width: 30px; margin-right: 10px;background: url(\'https://graph.facebook.com/'. $t1['id'] .'/picture?type=small\') top center; float: left; vertical-align: top;"></div> '. $t1['name'];
							}
						?>
					</td>
				</tr>
				<tr>
					<td>
						<i class="icon-user"></i>
					</td>
					<td>
						Secondary Trustee
					</td>
					<td>
						<?php
							if (!$epilogue->has_trustees($user) || isset($_POST['change'])) {
								echo '
								<div class="input-prepend">
									<span class="add-on"><i class="icon-search"></i></span>
									<input id="trustee" class="span2 typeahead_2" data-provide="typeahead" autocomplete="off" type="text" placeholder="search">
									<input type="hidden" id="trusteeId_2" name="trust_2" />
								</div>
								<span class="type2_loading" style="display: none">
									<i class="icon-spinner icon-spin"></i>
								</span>
								';
							}else{
								$t2 = $epilogue->trustee_info($user, 'secondary');
								echo '<div style="height: 30px; width: 30px; margin-right: 10px;background: url(\'https://graph.facebook.com/'. $t2['id'] .'/picture?type=small\') top center; float: left; vertical-align: top;"></div> '. $t2['name'];
							}
						?>
					</td>
				</tr>
				<tr>
					<td colspan="3" style="text-align: center;">
						<?php if ($epilogue->has_trustees($user)  && !isset($_POST['change'])) { echo '<br /><input type="hidden" name="change" value="1" /><input type="submit" class="btn btn-primary"  value="Change Trustees" />'; }else{ echo '<input type="submit" class="btn btn-primary" value="Save" />'; } ?>
					</td>
				</tr>
		</form>
		</table>
	</div>
</div>

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


			$(".typeahead_1").typeahead({
				source: function ( query, process ) {

					$(".type1_loading").show();
					//here we pass the query (search) and process callback arguments to the throttled function
					throttledRequest( query, process );


				}
        ,highlighter: function( item ){
          var trustee = trusteeObjs[ item ];
 					$(".type1_loading").hide();         
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
					$( "#trusteeId_1" ).val( trusteeObjs[ selectedName ].id );

					//return the string you want to go into the textbox (the name)
					return selectedName;
				}
			});


			$(".typeahead_2").typeahead({
				source: function ( query, process ) {
					$(".type2_loading").show();
					//here we pass the query (search) and process callback arguments to the throttled function
					throttledRequest( query, process );

				}
        ,highlighter: function( item ){
          var trustee = trusteeObjs[ item ];
 					$(".type2_loading").hide();               
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
					$( "#trusteeId_2" ).val( trusteeObjs[ selectedName ].id );

					//return the string you want to go into the textbox (the name)
					return selectedName;
				}
			});


		});


	</script>

<?php
	require_once "private/includes/footer.php";
?>