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

# make a new list
function new_list($fb_id, $sql, $name = 'Unnamed List') {
	return $sql->raw_query("INSERT INTO `epilogue_fbdeath`.`epi_lists` (`fb_id`, `name`, `created`) VALUES ('". $fb_id ."', '". $name."', '". time() ."');");
}

# return an array of ids of the lists
# the user owns
function list_lists($fb_id, $sql) {
	$out = array();
	$query = $sql->raw_query("SELECT * FROM `epi_lists` WHERE `fb_id` = '". $fb_id ."'");
	while($list = mysql_fetch_array($query)) {
		$out = array_merge($out, array($list['id']));
	}
	return $out;
}

function is_list($id, $sql) {
	$query = $sql->query("SELECT COUNT( * ) AS TOTALFOUND FROM `epi_lists` WHERE `id` = '". $id ."'");
	return ($query['TOTALFOUND'] > 0);
}

class FbList {
	public $id = 0;
	private $_sql = '';

	function __construct($sql, $id) {
		$this->id = $id;
		$this->_sql = $sql;
	}

	public function name() {
		$query = $this->_sql->query("SELECT `name` FROM `epi_lists` WHERE id = '". $this->id ."'");
		return $query['name'];
	}

	public function list_of_ids() {
		$query = $this->_sql->raw_query("SELECT * FROM `epi_list_people` WHERE `list_id` = '". $this->id ."'");
		$output = array();
		while ($person = mysql_fetch_array($query)) {
			$output = array_merge($output, array($person['friend_id']));
		}
		return $output;
	}

	public function clear() {
		$this->_sql->raw_query("DELETE FROM `epi_list_people` WHERE `list_id` = '". $this->id ."'");		
	}

	public function save_name($name = 'Unnamed List') {
		$this->_sql->raw_query("UPDATE `epi_lists` SET `name` = '". $name ."' WHERE `id` = '". $this->id ."'");		
	}

	public function length() {
		return count($this->list_of_ids());
	}

	private function is_on_list($id) {
		return in_array($id, $this->list_of_ids());
	}

	public function add_fb_id($id) {
		if (!$this->is_on_list($id)) {
			return $this->_sql->raw_query("INSERT INTO `epilogue_fbdeath`.`epi_list_people` (`list_id`, `friend_id`) VALUES ('". $this->id ."', '". $id ."');");
		}

	}

	public function remove_fb_id($id) {
		if ($this->is_on_list($id)) {
			return $this->_sql->raw_query("DELETE FROM `epilogue_fbdeath`.`epi_list_people` WHERE `friend_id` = '". $id ."'");
		}

	}

	public function delete() {
		$this->_sql->raw_query("DELETE FROM `epi_list_people` WHERE `list_id` = '". $this->id ."'");
		$this->_sql->raw_query("DELETE FROM `epi_lists` WHERE `id` = '". $this->id ."'");
	}


	function __destruct() {

	}


}


?>