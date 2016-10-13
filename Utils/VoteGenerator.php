<?php
	/**
	
		Generates votes for posts.
		
		The number of votes generated is a random number in the set [voteLowLim, voteHiLim]. The distribution of 
		'up votes' to 'down votes' is intended to be fairly even, but can vary drastically.
		
		The $posts array holds specific post ID's and determines which posts we will generate votes for. It can
		either be edited directly, or $allPosts can be set to true to automatically grab all non-pending post
		ID's. There are no restrictions on what post can be specified when writing them in directly.
	
	**/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$connection = GlobalUtils::getConnection();
	
	$allPosts = false; //If true, polls the Relatables database for all non-pending posts and appends their ID's to $posts.
	
	$posts = array(); //An array of post ID's to add votes to.
	
	$voteLowLim = 20; //Lower boundary for number of votes to generate.
	$voteHiLim = 40; //Upper boundary for number of votes to generate.
	
	if($allPosts)
	{
		if($statement = $connection->prepare("SELECT id FROM submissions WHERE NOT pending"))
		{
			$statement->execute();
			$postID = 0;
			
			$statement->bind_result($postID);
			
			while($statement->fetch())
			{
				$posts[] = $postID;
			}
			
			$statement->close();
		}
	}
	
	for($i = 0; $i < count($posts); $i++)
	{
		//Determine the amount of votes to add.
		$voteCount = mt_rand($voteLowLim, $voteHiLim);
		
		echo "$voteCount\r\n";
		
		for($j = 0; $j < $voteCount; $j++)
		{
			$alone = mt_rand(0, 1);
			$uid = PHP_INT_MAX-$j; // Use a predictable uid that is unlikely to belong to a real user, at the upper bounds of possible uid values.
			
			if($statement = $connection->prepare("INSERT INTO related (pid, uid, alone) VALUES (?,?,?)"))
			{
				$statement->bind_param('iii', $posts[$i], $uid, $alone);
				
				$statement->execute();
				$statement->fetch();
				
				$statement->close();
			}
			
			echo $connection->error;
		}
	}
	