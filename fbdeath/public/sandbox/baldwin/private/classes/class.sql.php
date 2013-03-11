<?php
/*
	class.sql.php
	
	author: Baldwin Chang (baldwin@baldwinc.com)

*/

class sql {

	private $_sql_db = '';
	private $_sql_user = '';
	private $_sql_pass = '';

	private $_sql = '';

	function __construct($user, $pass, $db) {
		$this->_sql_db = $db;
		$this->_sql_user = $user;
		$this->_sql_pass = $pass;

		$this->_sql = mysql_connect("localhost", $this->_sql_user, $this->_sql_pass);

		mysql_select_db($this->_sql_db, $this->_sql);
	}

	private function raw_query($query) {
		return mysql_query($query, $this->_sql);
	}

	public function query($query) {
		return mysql_fetch_array($this->raw_query($query));
	}

	function __destruct() {
		mysql_close($this->_sql);
	} 

}

?>