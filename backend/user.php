<?php
	require_once("bootstrap.php");
	
	$Session = Session__FindInDB(session_id());
	if ($Session == false) {
		$_SESSION[$bid]["REPLY"]["DB_Session"] = "false";
	} else {
		$_SESSION[$bid]["REPLY"]["DB_Session"] = "true";
	}
	
	
	if ($job == "hello") {
		$_SESSION[$bid]["REPLY"]["jobresult"] = "true";
		if ($Session != false) {
			$ui = User__GetInfo($Session);
			$_SESSION[$bid]["REPLY"]["userinfo"] = $ui;
			$_SESSION[$bid]["userinfo"] = $ui;
		}
	}
	
	if ($job == "login") {
		if ($Session == true) {
			array_push($_SESSION["REPLY"]["ERRORS"], "already logged in");
			$_SESSION[$bid]["REPLY"]["jobresult"] = "false";
			Quit();
		}
		if (isset($_POST["username"]) AND strlen($_POST["username"]) > 3 AND isset($_POST["password"]) AND strlen($_POST["password"]) > 3) {
			$login = User__checkLogin($_POST["username"], $_POST["password"]);
			if ($login == false) {
				array_push($_SESSION["REPLY"]["ERRORS"], "unknown login/password");
				$_SESSION[$bid]["REPLY"]["jobresult"] = "false";
			} else {
				$_SESSION[$bid]["user_id"] = $login;
				$SessionID = Session__SaveToDB();
				$_SESSION[$bid]["REPLY"]["jobresult"] = "true";
				$_SESSION[$bid]["REPLY"]["uid"] = $login;
				$_SESSION[$bid]["REPLY"]["userinfo"] = User__GetInfo($login);
				$_SESSION[$bid]["userinfo"] = $_SESSION[$bid]["REPLY"]["userinfo"];
				$_SESSION[$bid]["REPLY"]["DB_Session"] = "true";
			}
		} else {
			array_push($_SESSION["REPLY"]["ERRORS"], "bad login/password");
			$_SESSION[$bid]["REPLY"]["jobresult"] = "false";
		}
	}
	
	if ($job == "logout") {
		if ($Session == false) {
			array_push($_SESSION["REPLY"]["ERRORS"], "not logged in");
			$_SESSION[$bid]["REPLY"]["jobresult"] = "false";
		} else if ($Session == true) {
			$logout = User__logout();
			if ($logout == false) {
				array_push($_SESSION["REPLY"]["ERRORS"], "logout failed");
				$_SESSION[$bid]["REPLY"]["jobresult"] = "false";
			} else {
				$_SESSION[$bid]["REPLY"]["jobresult"] = "true";
				array_push($_SESSION[$bid]["REPLY"]["SUCCESSES"], "logout successful");
				$SessRemove = Session__RemoveFromDB();
				$_SESSION[$bid]["REPLY"]["userinfo"] = User__GetInfo();
				if ($SessRemove) {	
					array_push($_SESSION[$bid]["REPLY"]["SUCCESSES"], "session removed from db successfully");
					$_SESSION[$bid]["REPLY"]["DB_Session"] = "false";
				} else {
					array_push($_SESSION[$bid]["REPLY"]["ERRORS"], "failed to remove session from db");
				}
			}
		} 
	}
	
	if ($job == "username_availability") {
		$res = User__UserNameExists($_POST["untest"]);
		$_SESSION[$bid]["REPLY"]["jobresult"] = !$res;
		$_SESSION[$bid]["REPLY"]["queriedUserName"] = $_POST["untest"];
		array_push($_SESSION[$bid]["REPLY"]["SUCCESSES"], "usernamecheck done");
	}
	
	if ($job == "registration") {
		$exists = User__UserNameExists($_POST["username"]);
		if ($exists == false) {
			$result = User__Add($_POST["username"], $_POST["password"]);
			if ($result==true) {
				array_push($_SESSION[$bid]["REPLY"]["SUCCESSES"], "user added");
				$_SESSION[$bid]["REPLY"]["jobresult"] = "true";
				$uid = User__GetUID($_POST["username"]);
				if ($uid > -1) {
					User__AddInfo($uid,"user_email",$_POST["email"]);
					User__AddInfo($uid,"user_xslevel","1");
					array_push($_SESSION[$bid]["REPLY"]["SUCCESSES"], "userinfo added");
				
				} else {
					array_push($_SESSION[$bid]["REPLY"]["ERRORS"], "userinfo could not be added");
				
				}
				
			} else {
				array_push($_SESSION[$bid]["REPLY"]["ERRORS"], "user insertion failed at DB level\r\n".$result);
				$_SESSION[$bid]["REPLY"]["jobresult"] = "false";
			}
		} else {
			array_push($_SESSION[$bid]["REPLY"]["ERRORS"], "username already in DB\r\n".$exists);
			$_SESSION[$bid]["REPLY"]["jobresult"] = "false";
		
		}
	}
	
	Quit();
	
	
	
?>