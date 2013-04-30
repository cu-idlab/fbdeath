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

class Epilogue {

	private $_sql = '';
	private $_facebook = '';
	private $_id = '';
	private $_token = '';

	public $id = '';

	function __construct($sql, $facebook) {
		$this->_sql = $sql;
		$this->_facebook = $facebook;
		$this->_id = $this->_facebook->getUser();
		$this->_token = $facebook->getAccessToken();

		$this->id = $this->_id;
	}

	public function nav_bar() {
		echo '
	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="./">Epilogue</a>

       	<ul class="nav">
			<li><a href="#">home</a></li>
			<li><a href="#">about</a></li>
			<li><a href="#">features</a></li>
			<li><a href="#">contact</a></li>
		</ul>
        </div>
      </div>
	</div>
			';
	}

	public function has_account($fb_id) {
			$check = $this->_sql->query("SELECT COUNT( * ) AS TOTALFOUND FROM `epi_users` WHERE `fb_id` = '". $fb_id ."' LIMIT 1");
			return ($check['TOTALFOUND']);
	}

	private function make_account($fb_id) {
		$this->_sql->raw_query("INSERT INTO `epilogue_fbdeath`.`epi_users` (`id`, `fb_id`, `account_created`) VALUES (NULL, ". $fb_id .", ". time() .")");
		$this->_sql->raw_query("INSERT INTO `epilogue_fbdeath`.`epi_fbtokens` (`id`, `fb_id`, `fb_token`, `time_generated`) VALUES (NULL, ". $fb_id .", '". $this->_token ."', ". time() .")");
	}

	public function is_loggedin() {
		if ($this->_id && isset($_SESSION['epi_id'])) { // Check if user has a current ID...
			if (!$this->has_account($this->_id)) {
				$this->make_account($this->_id);
			}
			// Check session id with database.
			$result = $this->_sql->query("SELECT * FROM `epi_sessions` WHERE `user_id`='". $_SESSION['epi_id'] ."' LIMIT 1");
			return (session_id() == $result['session_id']);
		}else{
			return false; // No Session with user.
		}
	}

	public function fb_check() {
		if ($this->_id && !$this->is_loggedin()) {
			$this->login($this->_id);
		}
	}

	public function login($fb_id) {

		if (!$this->is_loggedin()){ // Not logged in...
			if (!$this->has_account($this->_id)) { // make account!
				$this->make_account($this->_id);
			}
			$check = $this->_sql->query("SELECT COUNT( * ) AS TOTALFOUND FROM `epi_users` WHERE `fb_id` = '". $fb_id ."' LIMIT 1");
			if ($check['TOTALFOUND'] > 0) {
				$info = $this->_sql->query("SELECT * FROM `epi_users` WHERE `fb_id` = '". $fb_id ."' LIMIT 1");
				$this->session_build($info['id']);
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function logout() {
		if ($this->is_loggedin()){ 
			return $this->_sql->session_destroy();
		}else{
			return true;
		}	
	}


	public function session_rebuild() {
		if (!$this->is_loggedin() && isset($_COOKIE['epi_loginkey'])) {
			/* 
				Check if the user has the same IP and Browser
			*/
			
			$result = $this->_sql->query("SELECT COUNT( * ) AS TOTALFOUND, user_id FROM `epi_sessions` WHERE (`ip` = '". $_SERVER['REMOTE_ADDR'] ."' AND `user_agent` = '". $_SERVER['HTTP_USER_AGENT'] ."' AND `login_key` = '". $_COOKIE['epi_loginkey'] ."') LIMIT 1");
			if ($result['TOTALFOUND'] > 0) {
				$this->session_build($result['user_id']);
			}
		}else{
			/* Refresh the session */
			$result = $this->_sql->query("SELECT *, COUNT( * ) AS TOTALFOUND FROM `user_sessions` WHERE `login_key` = '". $_COOKIE['epi_loginkey'] ."'  LIMIT 1");
			if ($result['TOTALFOUND'] > 0) {
				$this->session_build($result['user_id']);
			}
		}

		if ($this->is_loggedin()) {
			setcookie("epi_id", $_SESSION['epi_id'], 0, "/");
			setcookie("epi_session", session_id(), 0, "/");	
			setcookie("epi_loginkey", $_SESSION['epi_loginkey'], time()+60*60*24*365*10, "/");
			$this->_sql->raw_query("UPDATE `epi_sessions` SET `last_action` = '". time() ."' WHERE `login_key` = '". $_SESSION['epi_loginkey'] ."' LIMIT 1");

		}
	}

	private function session_build($id) {

		
		$genKey = md5(time()*time()+(60*60*24*365*10) ."key". session_id());

		$this->_sql->session_add($id, $genKey);

		$_SESSION['epi_id'] = $id;
		$_SESSION['epi_loginkey'] = $genKey;
		
		setcookie("epi_id", $id, 0, "/");
		setcookie("epi_session", session_id(), 0, "/");	
		setcookie("epi_loginkey", $genKey, time()+60*60*24*365*10, "/");
	}	

	public function create_account($fb_id, $fb_token) {

		return;
	} 




	function __destruct() {

	}
}

?>