<?php
	include 'global.php';
	
	$user = new User($_SESSION['id']);
	
	echo 'Welcome ' . $user->getUsername() . '\r\n \r\n';
	
	if($user->isAdmin())
		echo 'Mere peasants cannot see this message. <span style="color:red;">Muahahaha!</span>';