<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/password.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	function login($cookie_login)
	{
		$connection = GlobalUtils::getConnection();

		if($statement = $connection->prepare("SELECT id, username, password, IFNULL(email,0) FROM accounts WHERE cookie_login = (?)"))
		{
		
			$statement->bind_param("s",$cookie_login);
			
			$statement->execute();

            $not_pending = 1;
			
			$statement->bind_result($id, $dbUser, $dbPass, $not_pending);
			$result = $statement->fetch();
			
			if($statement->num_rows && $not_pending)
			{
				$_SESSION['username']=$dbUser;
				$_SESSION['id']=$id;
					
				//Update their last login date and unique cookie login ID.
				$cookie_login = password_hash(date('isdHYm').$dbPass, PASSWORD_DEFAULT);
				$expire = time()+(60*60*24*365*5);
				
				mysqli_query($connection, "UPDATE accounts SET last_login=NOW(), cookie_login='$cookie_login' WHERE id=$id");
				setcookie("rrm",$cookie_login,$expire,'/');
			}
			else
				die('not_pending');
		}
	}
?>