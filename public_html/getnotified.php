<?php
	/*Copyright (C) Tyler Hacket 2016*/

	if(!isset($_POST["email"]))
		die("Please enter an email.");
	
	$email = $_POST['email'];
	
	if(strlen($email) == 0)
		die("Please enter an email.");
	else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		die("Provided email is invalid.");
	else
	{
		$connection = mysqli_connect('localhost','Relatables','10102S33k3r17','RelatablesNewsletter');
		
		if($statement = $connection->prepare("INSERT INTO subscribers (email, date) VALUES (?, NOW())"))
		{
			$statement->bind_param("s", $email);
			
			if($statement->execute())
			{
				die();
			}
			else
			{
				die("That email is already subscribed.");
			}
		}
		else
		{
			die("We're currently experiencing some technical issues. Sorry!");
		}
	}
	