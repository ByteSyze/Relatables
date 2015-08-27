<?php
	session_start();

	$country_id = $_GET['location'];
	
	echo $country_id;
	
	if(!is_numeric($country_id))
		die('Bad location.');
	if(($country_id < 1) || ($country_id > 250))
		die('Location out of bounds.');
		
	include('userinfo.php');
	
	setCountry($country_id, $_SESSION['id']);
	
	header('Location: http://www.relatables.com/settings/profile');
	