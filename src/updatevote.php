<?php
	/*Copyright (C) Tyler Hackett 2014*/
	$connection = mysqli_connect("mysql25.freehostia.com","tylhac_aitoo","10102S33K3R17","tylhac_aitoo");
	
	$query = $_GET["q"];
	
	if(substr($query,0,1) == "n")
	{
		$voteType = "notalone";
		$id = intval(substr($query,2,strlen($query)));
	}
	else if(substr($query,0,1) == "a")
	{
		$voteType = "alone";
		$id = intval(substr($query,1,strlen($query)));
	}
	else
	{
		echo "Invalid voting type";
		die;
	}
	
	mysqli_query($connection,"UPDATE submissions SET " . $voteType . "=" . $voteType . "+1 WHERE id=" . $id);
?>