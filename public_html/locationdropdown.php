<?php
	/*Copyright (C) Tyler Hackett 2014*/

	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';

	function getLocationDropdown($id)
	{
		echo '<select data-type=\'location\' id=\'location-input\' name=\'location\'>';
			
		$connection = GlobalUtils::getConnection();
		$countries = mysqli_query($connection,'SELECT country_id, short_name FROM countries');
		
		if($id === -1)
			echo '<option value=\'-1\'>Select a Country</option>';
		
		while($row = mysqli_fetch_array($countries))
		{
			echo $row['country_id'] . '::' . $id . ':: ' . ($row['country_id'] == $id);
			if($row['country_id'] == $id)
				echo '<option value=\''.$row['country_id'].'\' selected>'.$row['short_name'].'</option>';
			else
				echo '<option value=\''.$row['country_id'].'\'>'.$row['short_name'].'</option>';			
		}
		
		echo '</select>';
	}