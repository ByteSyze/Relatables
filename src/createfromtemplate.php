<?php

	$placeholders = array("[Username]","[Location]","[Date]");
	$user_info = array('Username'=>"Bobby",'Location'=>"N/A","Date"=>date("F d, Y"));
			
	$template = file_get_contents("http://www.relatablez.com/templates/user_template.php");
	
	$profile = str_replace($placeholders, $user_info, $template);
	
	print($profile);
	
	$user = "Bobby";

	mkdir("user/".$user."/");
	//$fp = fopen("user/".$user."/index.php","w") or die("Couldn't open file");
	//fwrite($fp, $profile);
	//fclose($fp);
?>