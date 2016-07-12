<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$submission = $_POST['s'];	
	$category = $_POST['c'];
	$anon = $_POST['a'];
	$mediaType = $_POST['m'];
	
	$media = '';
	
	if($mediaType == 'image')
	{
		if(getimagesize($_FILES['i']["tmp_name"]))
		{	
			$date = getdate();
			$target_dir = "/images/media/posts/{$date['year']}/{$date['mon']}/{$date['mday']}";
			$full_image_path = $_SERVER['DOCUMENT_ROOT'].$target_dir;
			
			if(!is_dir($full_image_path)) //Create the directory if it doesn't exist yet.
			{
				echo 'creating directory: ' . $full_image_path;
				mkdir($full_image_path, 0777, true);
			}
			
			$imageFileType = pathinfo($_FILES['i']['name'], PATHINFO_EXTENSION);
			$target_file = $full_image_path . '/' . time() . '.' . $imageFileType;
		
			if ($_FILES['i']["size"] > 2000000)
			{
				die('3');
			}
			else
			{
				// Allow certain file formats
				if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg"
				|| $imageFileType == "gif" ) 
				{
					while (file_exists($target_file))
						$target_file = $full_image_path . '/' . time() . '.' . $imageFileType;
					
					if(!move_uploaded_file($_FILES['i']["tmp_name"], $target_file))
					{
						die('4');
					}
					
					$media = $target_file;
				}
			}
		}
	}
	else if($mediaType == 'video')
	{
		$media = $_POST['v'];
	}
	
	$sublen = strlen($submission);
	
	if($sublen < 19 || $sublen > 300)
		die('1');
	else if($category < 1 || $category > 20)
		die('2');
	
	if(($_SESSION['id'] == null))
	{	
		$_SESSION['id'] = 0;
		$anon = 1;
	}
	
	$connection = GlobalUtils::getConnection();
	
	if($statement = $connection->prepare("INSERT INTO submissions (uid, verification, category, submission, media, mediatype, anonymous) VALUES (?,?,?,?,?,?,?)"))
	{	
		$temp_verif = 1234;
		$statement->bind_param("iiisssi",$_SESSION['id'], $temp_verif, $category, $submission, $media, $mediaType, $anon);
		
		echo ($statement->execute() ? 0 : -1);
	}
