<?php
	
	function login($id,$pass)
	{
		$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");

		if($statement = $connection->prepare("SELECT username, password, IFNULL(email,0) FROM accounts WHERE id = (?)"))
		{
		
			$statement->bind_param("i",$id);
			
			$statement->execute();

            $not_pending = 1;
			
			$statement->store_result();
			$statement->bind_result($dbUser, $dbPass, $not_pending);
			$result = $statement->fetch();
			
			if(($pass == $dbPass) && ($not_pending == true))
			{
				$_SESSION['username']=$dbUser;
				$_SESSION['id']=$id;
				
				//Update their last login date
				mysqli_query($connection, "UPDATE accounts SET last_login=NOW() WHERE username='".$dbUser."'");
			}
			else
				die('not_pending');
		}
		else
			die($connection->error);
	}
?>