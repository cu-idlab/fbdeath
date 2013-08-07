<?php
function escape($chars) {
	return htmlspecialchars($chars, ENT_QUOTES | ENT_HTML5);
}

function unescape($chars) {
	return htmlspecialchars_decode($chars, ENT_QUOTES | ENT_HTML5);
}

function e_msg($msg) {
	return '
			<div class="alert alert-error" id="error_msg"  style="display: none;">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				'. $msg .'
			</div>
			<script type="text/javascript">
				$(function() {
					$(\'#error_msg\').fadeIn(1000);
				});
			</script>
		';
}

function g_msg($msg) {
	return '
			<div class="alert alert-success" id="success_msg"  style="display: none;">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				'. $msg .'
			</div>
			<script type="text/javascript">
				$(function() {
					$(\'#success_msg\').fadeIn(1000);
				});
			</script>
		';
}
?>