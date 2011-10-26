<?php
	// MYSQL DATABASE STUFF

	function DB_QueryReplace($Query, $ReplaceArray) {
		if (!isset($Query)) return false;
		if (!isset($ReplaceArray) OR count($ReplaceArray) == 0) return $Query;
		$patterns = array();
		$replacements = array();
		foreach ($ReplaceArray as $Patt=>$Rep) {
			array_push($patterns,$Patt);
			array_push($replacements,$Rep);
		}
		$q = preg_replace($patterns, $replacements , $Query);
		return $q;
	}
	function DB_GetDate() {
		$q_timestamp = "SELECT UTC_TIMESTAMP";
		$res = mysql_query($q_timestamp);
		$sqldate = mysql_fetch_row($res);
		$dbNOW = $sqldate[0];
		return $dbNOW;
	}
	function DB_Connect() {
		$_CFG = $_SESSION["CFG"];
		$dblink = mysql_connect ($_CFG["db_server"],$_CFG["db_username"],$_CFG["db_password"]);
		if (!$dblink) return false;
		$db = mysql_select_db ($_CFG["db_name"], $dblink);
		if (!$db) return false;
		return $db;
	}
	
	
	// SAFETY STUFF
	function Security_POSTcheck() {
		if (!isset($_POST["browserID"])) {
			array_push($_SESSION["REPLY"]["ERRORS"], "no browserID");
			return false;
		} else if (strlen($_POST["browserID"])!=12) {
			array_push($_SESSION["REPLY"]["ERRORS"], "wrong browserID");
			return false;
		}
		if (!isset($_POST["job"])) {
			array_push($_SESSION[$_POST["browserID"]]["REPLY"]["ERRORS"], "no job");
			return false;
		} else {
			if (!in_array($_POST["job"], $_SESSION["CFG"]["PossibleJobs"])) { 
				array_push($_SESSION[$_POST["browserID"]]["REPLY"]["ERRORS"], "bad job");
				return false;
			} else {
				$_SESSION[$_POST["browserID"]]["REPLY"]["job"] = $_POST["job"];
			}
		}
		return true;
	}
	
	// CONTROL STUFF
	function Quit() {
		REPLY_JSON();
		exit;
	}
	
	// VIEWS
	
	function REPLY_JSON() {
		$reply = array("ERRORS" => "bad client");
		if (isset($_SESSION[$_POST["browserID"]]) AND isset($_SESSION[$_POST["browserID"]]["REPLY"])) {
			$reply = $_SESSION[$_POST["browserID"]]["REPLY"];
		} else if (isset($_SESSION["REPLY"])){
			$reply = $_SESSION["REPLY"];
		}
		echo json_encode($reply);
		return true;
	}
	
	// ######################################################
	
	function User__checkLogin($login, $password) {
		$_CFG = $_SESSION["CFG"];
		$_QU = $_SESSION["QUERIES"];
		$RepArray = array(
			"/@@_V_username/" => $login,
			"/@@_V_password/" => $password
		);
		$myQuery = DB_QueryReplace($_QU["get_userid_by_login"], $RepArray);
		$Res = mysql_query($myQuery);	
		if ($Res == false) return false;
		$RowCount = mysql_num_rows($Res);
		if ($RowCount == 1) {
			$row = mysql_fetch_array($Res, MYSQL_ASSOC);
			return $row[$_CFG["usertable_fields"]["user_id"]];
		} else if ($RowCount == 0) {
			return false;
		} else {
			// should not happen
			return false;
		}
	}
	
	function User__GetUID($username = "") {
		if ($username == "") return -1;
		GLOBAL $bid;
		$_CFG = $_SESSION["CFG"];
		$_QU = $_SESSION["QUERIES"];
		$RepArray = array(
			"/@@_V_username/" => $username
		);
		$myQuery = DB_QueryReplace($_QU["get_userid_by_username"], $RepArray);
		$Res = mysql_query($myQuery);	
		if ($Res == false) {
			return -1;
		}
		$RowCount = mysql_num_rows($Res);
		if ($RowCount!=1) {
			return -1;
		} else {
			$row = mysql_fetch_array($Res, MYSQL_ASSOC);
			return $row[$_CFG["usertable_fields"]["user_id"]];
		}
		
	}

	function User__GetInfo($uid = 0) {
		GLOBAL $bid;
		$_CFG = $_SESSION["CFG"];
		$_QU = $_SESSION["QUERIES"];
		$RepArray = array(
			"/@@_V_userid/" => $uid
		);
		$myQuery = DB_QueryReplace($_QU["get_userInfo_by_userID"], $RepArray);
		$Res = mysql_query($myQuery);	
		if ($Res == false) {
			//array_push($_SESSION[$bid]["REPLY"]["ERRORS"], $myQuery);
			return false;
		}
		$RowCount = mysql_num_rows($Res);
		$userinfo = array();
		if ($RowCount>0) {
			while ($row = mysql_fetch_row($Res)) {
				$userinfo[$row[0]] = $row[1];
			}
		}
		$myQuery = DB_QueryReplace($_QU["get_username_by_userid"], $RepArray);
		$Res = mysql_query($myQuery);	
		if ($Res == false) {
			//array_push($_SESSION[$bid]["REPLY"]["ERRORS"], $myQuery);
			return false;
		}
		if ($RowCount>0) {
			$row = mysql_fetch_assoc($Res);
			$userinfo[$_CFG["usertable_fields"]["username"]] = $row[$_CFG["usertable_fields"]["username"]];
		}
		$userinfo[$_CFG["usertable_fields"]["user_id"]] = $uid;
		return $userinfo;
	}
	
	
	function User__logout() {
		GLOBAL $bid;
		$_SESSION[$bid]["userinfo"] = array();
		return true;
	}

	
	function User__Add($username,$password) {
		GLOBAL $bid;
		$_CFG = $_SESSION["CFG"];
		$_QU = $_SESSION["QUERIES"];
		$RepArray = array(
			"/@@_V_username/" => $username,
			"/@@_V_password/" => $password,
			"/@@_V_now/" =>  $_SESSION[$bid]["now"]
		);
		$myQuery = DB_QueryReplace($_QU["add_user_by_login"], $RepArray);
		$Res = mysql_query($myQuery);
		$iid = mysql_insert_id(); 
		if ($Res == true && $iid>0) {
			return true;
		} else {
			$error = "MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$myQuery\n<br>"; 
			return $error;
		}
	}


	function User__Remove($mode,$info) {
		GLOBAL $bid;
		$_CFG = $_SESSION["CFG"];
		$_QU = $_SESSION["QUERIES"];
		if ($mode == "username") {
			$RepArray = array(
				"/@@_V_username/" => $info,
			);
			$myQuery = DB_QueryReplace($_QU["delete_user_by_username"], $RepArray);
		} else if ($mode == "uid") {
			$RepArray = array(
				"/@@_V_userid/" => $info,
			);
			$myQuery = DB_QueryReplace($_QU["delete_user_by_userid"], $RepArray);
		}
		$Res = mysql_query($myQuery);
		if ($Res == true) {
			return true;
		} else {
			$error = "MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$myQuery\n<br>"; 
			return $error;
		}
	}


	function User__UserNameExists($username) {
		$_CFG = $_SESSION["CFG"];
		$_QU = $_SESSION["QUERIES"];
		$bid = $_POST["browserID"];
		$RepArray = array(
			"/@@_V_username/" => $username
		);
		$myQuery = DB_QueryReplace($_QU["get_userid_by_MD5username"], $RepArray);
		$Res = mysql_query($myQuery);	
		$RowCount = mysql_num_rows($Res);
		if ($RowCount == 1) {
			return true;
		} else if ($RowCount == 0) {
			return false;
		} else {
			// should not happen
			return true;
		}
	}
	
	function User__AddInfo($uid,$thing,$content) {
		global $bid;
		$_CFG = $_SESSION["CFG"];
		$_QU = $_SESSION["QUERIES"];
		$RepArray = array(
			"/@@_V_userid/" => $uid, 
			"/@@_V_thing/" => $thing, 
			"/@@_V_content/" => $content
		);
		$myQuery = DB_QueryReplace($_QU["add_userInfo_by_userID"], $RepArray);
		$Res = mysql_query($myQuery);	
		if ($Res == true) {
			return true;
		} else {
			// should not happen
			return true;
		}
	}
	
	
	
?>