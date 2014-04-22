<?php
	
	function login($id,$pass)
	{
		$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");

		if($statement = $connection->prepare("SELECT username, password, pending FROM accounts WHERE id = (?)"))
		{
		
			$statement->bind_param("i",$id);
			
			$statement->execute();

            $pending = 1;
			
			$statement->store_result();
			$statement->bind_result($dbUser, $dbPass, $pending);
			$result = $statement->fetch();

			if(($pass == $dbPass) && ($pending != 1) && (!empty($result)))
			{
				$_SESSION["username"]=$dbUser;
				
				//Update their last login date
				$result = mysqli_query($connection, "UPDATE accounts SET last_login = [CURDATE()] WHERE id = " . $id);
			}
		}
	}
?>