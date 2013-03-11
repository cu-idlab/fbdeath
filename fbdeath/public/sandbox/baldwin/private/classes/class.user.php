<?php
/*

	class.user.php
	
	author: Baldwin Chang (baldwin@baldwinc.com)

*/

class user {

	private $_sql = '';

	function __construct($sql, $id) {
		$this->_sql_con = $sql;
		$this->_id = $id;
	
		$this->info = $this->_info();
	}


	private function _info() {
		return $this->_sql_con->query("SELECT * FROM `user_db` LEFT JOIN `user_settings` ON `user_db`.id = '". $this->_id ."' AND `user_db`.id=`user_settings`.id ");
	}

	public function full_name() {
		return $this->info['first_name'] .' '. $this->info['last_name'];
	}

	public function has_flag($flag) {
		return in_array($flag, explode(",", $this->info['user_flags']));
	}

	function __destruct() {


	}


}


?>