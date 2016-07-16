<?php
	/*Copyright (C) Tyler Hacket 2016*/

	if(!isset($_POST["email"]))
		die("Plrease enter an email.");
	
	$email = $_POST['email'];
	
	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		die("Provided email is invalid.");
	else
	{
		$connection = mysqli_connect('localhost','Relatables','10102S33k3r17','u683362690_rtblz');
		
		if($statement = $connection->prepare("INSERT INTO newslettersubs (email, date) VALUES (?, NOW())"))
		{
			$statement->bind_param("s", $email);
			
			if($statement->execute())
			{
				die("You are now subscribed to the Relatables newsletter!");
			}
			else
			{
				die("That email is already subscribed.");
			}
		}
	}
	