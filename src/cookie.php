<?php
	echo "file included. ";

	function login($id, $pass)
	{
		echo "inside login function. ";
		
		$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");

		if($statement = $connection->prepare("SELECT id, username, password, pending FROM accounts WHERE id = (?)"))
		{
		
			echo "grabbed user -- Authenticating. ";
		
			$statement->bind_param("i",$id);
			
			$statement->execute();
			
			$statement->store_result();
			$statement->bind_result($id, $dbUser, $dbPass, $pending);
			$result = $statement->fetch();
			
			else if(($pass == $dbPass) && ($pending != 1) && (!empty($result))
			{
				$_SESSION["username"]=$dbUser;
				
				echo "Logged in! ";
				
				//Update their last login date
				$result = mysqli_query($connection, "UPDATE accounts SET last_login = [CURDATE()] WHERE username LIKE " . $dbUser);		
				
				echo "Updated last login date";
			}
		}
		else
			echo $connection->error;
	}
?>