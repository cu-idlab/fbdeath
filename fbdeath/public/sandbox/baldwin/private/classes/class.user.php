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

class User {

	private $_sql = '';
	private $_facebook = '';
	private $_id = '';
	private $_token = '';

	public $info = '';

	function __construct($sql, $facebook, $id) {
		$this->_sql = $sql;
		$this->_facebook = $facebook;
		$this->_id = $id;

		$this->_token = $facebook->getAccessToken();
		$this->info = $this->_info();
	}

	private function _info() {
		return $this->_sql->query("SELECT * FROM `epi_users` LEFT JOIN `epi_fbtokens` ON `epi_users`.fb_id = '". $this->_id ."' AND `epi_users`.fb_id=`epi_fbtokens`.fb_id WHERE `epi_users`.`fb_id` = '". $this->_id ."'");
	}

	public function graph_call($call) {
		$url = 'https://graph.facebook.com/'. $this->_id .'?'. $call .'&amp;access_token='. $this->info['fb_token'];
		$from_fb = @file_get_contents($url);
		return json_decode($from_fb);
	}

	public function name() {
		$graph = $this->graph_call('');
		return $graph->name;
	}

	function __destruct() {

	}
}

?>
