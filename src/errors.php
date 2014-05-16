<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	////////////////////////////////////
	//
	//	This is a list of premade error messages to send to the user, in the event that they
	//	did something wrong (or stupid in some cases). All error messages should be user/idiot-
	//	friendly, as they'll be used to send directly to the user in certain parts of the site.
	//
	////////////////////////////////////
	
	$_USR_ERR = 
	array(
		"Username too long.",
		"Username too short.",
		"Username contains invalid characters.",
		"Username unavailable"
	);
	
	$_PSW_ERR =
	array(
		"Password too short.",
		"Re entered password does not match.",
		"Incorrect password provided."
	);
	
	$_EML_ERR =
	array(
		"Email too long.",
		"Email too short.",
		"Invalid email.", // This would be triggered if the provided email does not contain both @ sign and a period.
		"Email taken."
	);
	
	$_CTR_ERR =
	array(
		"Invalid country code." //Every country has an id. There are 250 countries in our database. any id less than 1 and greater than 250 is invalid.
	);
	
	$_DSC_ERR = 
	array(
		"Description too long."
	);