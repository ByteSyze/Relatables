<?php
	include 'global.php';
	
	$user = new User($_SESSION['id']);
	
	echo 'Welcome ' . $user->getUsername() . '.';
	
	if($user->isAdmin())
		echo ' Mere peasants cannot see this message. <span style="color:red;">Muahahaha!</span> ';
		
	$user->setUsername('Adam Sander');
	$user->setAdmin();
	
	$user->update();
	
	echo 'Your name has been changed to "' . $user->getUsername() . '"';
	
	$user->notify('You suck. Lern 2 cōd');