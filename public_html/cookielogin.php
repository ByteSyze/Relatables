<?php
	
	function login($cookie_login)
	{
		$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");

		if($statement = $connection->prepare("SELECT id, username, password, IFNULL(email,0) FROM accounts WHERE cookie_login = (?)"))
		{
		
			$statement->bind_param("s",$cookie_login);
			
			$statement->execute();

            $not_pending = 1;
			
			$statement->store_result();
			$statement->bind_result($id, $dbUser, $dbPass, $not_pending);
			$result = $statement->fetch();
			
			if($statement->num_rows && $not_pending)
			{
				$_SESSION['username']=$dbUser;
				$_SESSION['id']=$id;
					
				//Update their last login date and unique cookie login ID.
				$cookie_login = md5(date('isdHYm').$id.$dbPass);
				$expire = time()+(60*60*24*365*5);
				
				mysqli_query($connection, "UPDATE accounts SET last_login=NOW(), cookie_login=$cookie_login WHERE id=$id");
				setcookie("rrm",$cookie_login,$expire);
			}
			else
				die('not_pending');
		}
		else
			die($connection->error);
	}
?>